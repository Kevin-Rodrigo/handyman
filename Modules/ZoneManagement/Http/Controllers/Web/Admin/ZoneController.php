<?php

namespace Modules\ZoneManagement\Http\Controllers\Web\Admin;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\ZoneManagement\Entities\Zone;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Grimzy\LaravelMysqlSpatial\Types\Polygon;
use Grimzy\LaravelMysqlSpatial\Types\LineString;
use Rap2hpoutre\FastExcel\FastExcel;
use Stevebauman\Location\Facades\Location;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ZoneController extends Controller
{
    private Zone $zone;

    public function __construct(Zone $zone)
    {
        $this->zone = $zone;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Application|Factory|View
     */
    public function create(Request $request): View|Factory|Application
    {
        if (!session()->has('location')) {
            $data = Location::get($request->ip());
            $location = [
                'lat' => $data ? $data->latitude : '23.757989',
                'lng' => $data ? $data->longitude : '90.360587'
            ];
            session()->put('location',$location);
        }
        $search = $request['search'];
        $query_param = $search ? ['search' => $request['search']] : '';

        $zones = $this->zone->withCount(['providers', 'categories'])
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                foreach ($keys as $key) {
                    $query->orWhere('name', 'LIKE', '%' . $key . '%');
                }
            })
            ->latest()->paginate(pagination_limit())->appends($query_param);
        return view('zonemanagement::admin.create', compact('zones', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => 'required|unique:zones|max:191',
            'coordinates' => 'required',
        ]);

        $value = $request->coordinates;
        foreach (explode('),(', trim($value, '()')) as $index => $single_array) {
            if ($index == 0) {
                $lastcord = explode(',', $single_array);
            }
            $coords = explode(',', $single_array);
            $polygon[] = new Point($coords[0], $coords[1]);
        }
        $polygon[] = new Point($lastcord[0], $lastcord[1]);

        DB::transaction(function () use ($polygon, $request) {
            $zone = $this->zone;
            $zone->name = $request->name;
            $zone->coordinates = new Polygon([new LineString($polygon)]);
            $zone->save();
        });

        Toastr::success(ZONE_STORE_200['message']);

        return back();
    }

    /**
     * Show the specified resource.
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $zone = $this->zone->where('id', $id)->first();
        if (isset($zone)) {
            return response()->json(response_formatter(DEFAULT_200, $zone), 200);
        }
        return response()->json(response_formatter(DEFAULT_204, $zone), 204);
    }


    public function edit(string $id): View|Factory|Application|RedirectResponse
    {
        $zone = Zone::selectRaw("*,ST_AsText(ST_Centroid(`coordinates`)) as center")->find($id);

        if (isset($zone)) {
            $current_zone = format_coordinates($zone->coordinates);
            $center_lat = trim(explode(' ', $zone->center)[1], 'POINT()');
            $center_lng = trim(explode(' ', $zone->center)[0], 'POINT()');

            return view('zonemanagement::admin.edit', compact('zone', 'current_zone', 'center_lat', 'center_lng'));
        }

        Toastr::error(DEFAULT_204['message']);
        return back();
    }

    public function get_active_zones($id): JsonResponse
    {
        $all_zones = Zone::where('id', '<>', $id)->where('is_active', 1)->get();
        $all_zone_data = [];

        foreach ($all_zones as $item) {
            $data = [];
            foreach ($item->coordinates as $coordinate) {
                $data[] = (object)['lat' => $coordinate->lat, 'lng' => $coordinate->lng];
            }
            $all_zone_data[] = $data;
        }
        return response()->json($all_zone_data, 200);
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function status_update(Request $request, $id): JsonResponse
    {
        $zone = $this->zone->where('id', $id)->first();
        $this->zone->where('id', $id)->update(['is_active' => !$zone->is_active]);

        return response()->json(DEFAULT_STATUS_UPDATE_200, 200);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'name' => 'required|max:191',
            'coordinates' => 'required',
        ]);

        $value = $request->coordinates;
        foreach (explode('),(', trim($value, '()')) as $index => $single_array) {
            if ($index == 0) {
                $lastcord = explode(',', $single_array);
            }
            $coords = explode(',', $single_array);
            $polygon[] = new Point($coords[0], $coords[1]);
        }
        $polygon[] = new Point($lastcord[0], $lastcord[1]);

        $zone = $this->zone->where('id', $id)->first();

        if (!isset($zone)) {
            Toastr::success(ZONE_404['message']);
            return back();
        }

        $zone->name = $request->name;
        $zone->coordinates = new Polygon([new LineString($polygon)]);
        $zone->save();

        Toastr::success(ZONE_UPDATE_200['message']);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        $this->zone->where('id', $id)->delete();
        Toastr::success(ZONE_DESTROY_200['message']);
        return back();
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return string|StreamedResponse
     */
    public function download(Request $request): string|StreamedResponse
    {
        $items = $this->zone->withCount(['providers', 'categories'])
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                foreach ($keys as $key) {
                    $query->orWhere('name', 'LIKE', '%' . $key . '%');
                }
            })
            ->latest()->get();
        return (new FastExcel($items))->download(time().'-file.xlsx');
    }

}
