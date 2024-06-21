<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaintenanceBannerMessage;
use App\Models\MaintenanceBannerMessage;
use Carbon\Carbon;

class MaintenanceBannerController extends Controller
{
    // Gets the maintenance banners that's active right now
    public function get()
    {
        $now = Carbon::now();
        return MaintenanceBannerMessage::where('starts_at', '<', $now)->where('ends_at', '>', $now)->get();
    }

    public function getAll()
    {
        return MaintenanceBannerMessage::get();
    }

    public function store(StoreMaintenanceBannerMessage $message)
    {
        $validated = $message->validated();

        MaintenanceBannerMessage::create($validated);

        return MaintenanceBannerMessage::get();
    }

    public function destroy(MaintenanceBannerMessage $message)
    {
        $message->delete();

        return MaintenanceBannerMessage::get();
    }
}
