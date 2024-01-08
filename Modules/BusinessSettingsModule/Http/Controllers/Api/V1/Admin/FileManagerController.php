<?php

namespace Modules\BusinessSettingsModule\Http\Controllers\Api\V1\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class FileManagerController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $this->listFolderFiles(base_path('storage/app/public'));
    }

    function listFolderFiles($directory)
    {
        $file_and_folders = scandir($directory);

        unset($file_and_folders[array_search('.', $file_and_folders, true)]);
        unset($file_and_folders[array_search('..', $file_and_folders, true)]);

        // prevent empty ordered elements
        if (count($file_and_folders) < 1)
            return;
        echo '<ol>';

        foreach ($file_and_folders as $item) {
            echo '<li>' . $item;
            if (is_dir($directory . '/' . $item)) $this->listFolderFiles($directory . '/' . $item);
            echo '</li>';
        }
        echo '</ol>';
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('businesssettingsmodule::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('businesssettingsmodule::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('businesssettingsmodule::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
