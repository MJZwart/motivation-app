<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMaintenanceBannerMessage;
use App\Models\MaintenanceBannerMessage;
use Carbon\Carbon;

class MaintenanceBannerController extends Controller
{
    public function getCurrentlyActive()
    {
        $now = Carbon::now();
        return MaintenanceBannerMessage::where('starts_at', '<', $now)->where('ends_at', '>', $now)->get();
    }

    public function getAll()
    {
        return MaintenanceBannerMessage::get();
    }

    public function store(StoreMaintenanceBannerMessage $request)
    {
        $validated = $request->all();

        MaintenanceBannerMessage::create($validated);

        return MaintenanceBannerMessage::get();
    }

    public function update(StoreMaintenanceBannerMessage $request, MaintenanceBannerMessage $banner) 
    {
        $validated = $request->all();

        $banner->update($validated);
        
        return MaintenanceBannerMessage::get();
    }

    public function destroy(MaintenanceBannerMessage $banner)
    {
        $banner->delete();

        return MaintenanceBannerMessage::get();
    }
}
