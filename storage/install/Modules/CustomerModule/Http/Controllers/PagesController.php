<?php

namespace Modules\CustomerModule\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PagesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function about_us(): Renderable
    {
        $page_data = business_config('about_us', 'pages_setup');
        return view('customermodule::index', compact('page_data'));
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function privacy_policy(): Renderable
    {
        $page_data = business_config('privacy_policy', 'pages_setup');
        return view('customermodule::index', compact('page_data'));
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function terms_and_conditions(): Renderable
    {
        $page_data = business_config('terms_and_conditions', 'pages_setup');
        return view('customermodule::index', compact('page_data'));
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function refund_policy(): Renderable
    {
        $page_data = business_config('refund_policy', 'pages_setup');
        return view('customermodule::index', compact('page_data'));
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function return_policy(): Renderable
    {
        $page_data = business_config('return_policy', 'pages_setup');
        return view('customermodule::index', compact('page_data'));
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function cancellation_policy(): Renderable
    {
        $page_data = business_config('cancellation_policy', 'pages_setup');
        return view('customermodule::index', compact('page_data'));
    }
}
