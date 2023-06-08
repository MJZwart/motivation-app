<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateGlobalSettingsRequest;
use App\Http\Resources\GlobalSettingResource;
use App\Models\GlobalSetting;
use Illuminate\Http\Request;

class GlobalSettingController extends Controller
{
    public function getGlobalSettings() 
    {
        return GlobalSettingResource::collection(GlobalSetting::all());
    }

    public function updateGlobalSettings(UpdateGlobalSettingsRequest $request)
    {
        $validated = $request->validated();

        foreach ($validated as $item) {
            GlobalSetting::where('key', $item['key'])->update(['value' => $item['value']]);
        }
        return GlobalSettingResource::collection(GlobalSetting::all());
    }
}
