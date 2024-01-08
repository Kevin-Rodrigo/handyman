<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Modules\BusinessSettingsModule\Entities\BusinessSettings;
use Modules\CategoryManagement\Entities\Category;

class LandingController extends Controller
{
    private BusinessSettings $businessSettings;
    private Category $category;

    public function __construct(BusinessSettings $businessSettings, Category $category)
    {
        $this->businessSettings = $businessSettings;
        $this->category = $category;
    }

    public function home()
    {
        $settings = $this->businessSettings->whereNotIn('settings_type', ['payment_config', 'third_party'])->get();
        $categories = $this->category->ofType('main')->ofStatus(1)->with(['children'])->withCount('zones')->get();
        return view('welcome', compact('settings', 'categories'));
    }

    public function about_us()
    {
        $settings = $this->businessSettings->whereNotIn('settings_type', ['payment_config', 'third_party'])->get();
        return view('about-us', compact('settings'));
    }

    public function privacy_policy()
    {
        $settings = $this->businessSettings->whereNotIn('settings_type', ['payment_config', 'third_party'])->get();
        return view('privacy-policy', compact('settings'));
    }

    public function terms_and_conditions()
    {
        $settings = $this->businessSettings->whereNotIn('settings_type', ['payment_config', 'third_party'])->get();
        return view('terms-and-conditions', compact('settings'));
    }

    public function contact_us()
    {
        $settings = $this->businessSettings->whereNotIn('settings_type', ['payment_config', 'third_party'])->get();
        return view('contact-us', compact('settings'));
    }

    public function cancellation_policy()
    {
        $settings = $this->businessSettings->whereNotIn('settings_type', ['payment_config', 'third_party'])->get();
        return view('cancellation-policy', compact('settings'));
    }

    public function refund_policy()
    {
        $settings = $this->businessSettings->whereNotIn('settings_type', ['payment_config', 'third_party'])->get();
        return view('refund-policy', compact('settings'));
    }
}
