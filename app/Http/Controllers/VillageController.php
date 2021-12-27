<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Village;
use App\Http\Resources\VillageResource;
use Illuminate\Support\Facades\Auth;

class VillageController extends Controller
{

    /**
     * Returns all the villages owned by the authenticated user.
     */
    public function fetchAllVillagesByUser(){ //tested only when exists TODO
        $villages = Village::where('user_id', Auth::user()->id)->get();
        return $villages ? VillageResource::collection($villages) : null;
    }
}
