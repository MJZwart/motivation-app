<?php

namespace App\Helpers;

use App\Models\Village;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class VillageHandler {

    /**
     * Sets the village given as param as active and toggles all other villages as inactive
     *
     * @param Integer $userId
     * @param Integer $villageIdToActive
     * @return Village
     */
    public static function toggleVillageActive($userId, $villageIdToActive){
        $allVillages = VillageHandler::findAllVillagesByUser($userId);
        $activeVillage = null;
        foreach($allVillages as $vill){
            if($vill->id == $villageIdToActive){
                $activeVillage = VillageHandler::activateVillage($vill->id);
            } else {
                VillageHandler::deactivateVillage($vill->id);
            }
        }
        return $activeVillage;
    }

    /**
     * Activates the given village
     *
     * @param Integer $villageId
     * @return Village
     */
    public static function activateVillage($villageId){
        $village = Village::find($villageId);
        $village->active = true;
        $village->update();
        return $village;
    }

    /**
     * Deactivates the given village
     *
     * @param Integer $villageId
     */
    public static function deactivateVillage($villageId){
        $village = Village::find($villageId);
        $village->active = false;
        $village->update();
    }

    /**
     * Creates a new village with the given name and automatically activates it
     *
     * @param Integer $userId
     * @param String $villageName
     * @return Village
     */
    public static function createNewVillageAndActivate($userId, $villageName){
        $newVillage = Village::create([
            'name' => $villageName,
            'user_id' => $userId]);
        VillageHandler::toggleVillageActive($userId, $newVillage->id);
        return $newVillage;
    }

    /**
     * Finds the active village from any given user by id
     *
     * @param Integer $userId
     * @return Village
     */
    public static function findActiveVillage($userId){
        return Village::where('user_id', $userId)->where('active', true)->first();
    }

    /**
     * Finds all the villages linked to a given user by id
     *
     * @param Integer $userId
     * @return Villages
     */
    public static function findAllVillagesByUser($userId){
        return Village::where('user_id', $userId)->get();
    }

    public static function updateActiveVillage($activeVillage, $newName) {
        $village = Village::find($activeVillage);
        if($village->user_id == Auth::user()->id){
            $village->update(['name' => $newName]);
            return $village->refresh();
        } else {
            //Throw exception
        }
    }
    
    public static function deactivateAllVillages(User $user) {
        foreach ($user->villages as $village) {
            if($village->active) {
                $village->active = false;
                $village->save();
            }
        }
    }
}