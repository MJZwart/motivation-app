<?php

namespace App\Helpers;

use App\Models\Character;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CharacterHandler {

    /**
     * Sets the character given as param as active and toggles all other characters as inactive
     *
     * @param Integer $userId
     * @param Integer $characterIdToActive
     * @return Character
     */
    public static function toggleCharacterActive($userId, $characterIdToActive){
        $allCharacters = CharacterHandler::findAllCharactersByUser($userId);
        $activeCharacter = null;
        foreach($allCharacters as $char){
            if($char->id == $characterIdToActive){
                $activeCharacter = CharacterHandler::activateCharacter($char->id);
            } else {
                CharacterHandler::deactivateCharacter($char->id);
            }
        }
        return $activeCharacter;
    }

    /**
     * Activates the given character
     *
     * @param Integer $characterId
     * @return Character
     */
    public static function activateCharacter($characterId){
        $character = Character::find($characterId);
        $character->active = true;
        $character->update();
        return $character;
    }

    /**
     * Deactivates the given character
     *
     * @param Integer $characterId
     */
    public static function deactivateCharacter($characterId){
        $character = Character::find($characterId);
        $character->active = false;
        $character->update();
    }

    /**
     * Creates a new character with the given name and automatically activates it
     *
     * @param Integer $userId
     * @param String $characterName
     * @return Character
     */
    public static function createNewCharacterAndActivate($userId, $characterName){
        $newCharacter = Character::create([
            'name' => $characterName,
            'user_id' => $userId]);
        CharacterHandler::toggleCharacterActive($userId, $newCharacter->id);
        return $newCharacter;
    }

    /**
     * Finds the active character from any given user by id
     *
     * @param Integer $userId
     * @return Character
     */
    public static function findActiveCharacter($userId){
        return Character::where('user_id', $userId)->where('active', true)->first();
    }

    /**
     * Finds all the characters linked to a given user by id
     *
     * @param Integer $userId
     * @return Characters
     */
    public static function findAllCharactersByUser($userId){
        return Character::where('user_id', $userId)->get();
    }

    public static function updateActiveCharacter($activeCharacter, $newName) {
        $character = Character::find($activeCharacter);
        if($character->user_id == Auth::user()->id){
            $character->update(['name' => $newName]);
            return $character->refresh();
        } else {
            //Throw exception
        }
    }

    public static function deactivateAllCharacters(User $user) {
        foreach ($user->characters as $character) {
            if($character->active) {
                $character->active = false;
                $character->save();
            }
        }
    }
}