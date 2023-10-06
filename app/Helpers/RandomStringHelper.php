<?php

declare(strict_types=1);

namespace App\Helpers;

class RandomStringHelper
{
    public static function getRandomAdjective()
    {
        $adjectiveList = json_decode(file_get_contents(__DIR__ . "/json/adjectives.json"));
        return $adjectiveList[mt_rand(0, count($adjectiveList) - 1)];
    }

    public static function getRandomAnimal()
    {
        $animalList = json_decode(file_get_contents(__DIR__ . "/json/animals.json"));
        return $animalList[mt_rand(0, count($animalList) - 1)];
    }

    public static function getVillageName()
    {
        $villageList = json_decode(file_get_contents(__DIR__ . "/json/villagenames.json"));
        $chosenVillageName = '';
        $prefix = mt_rand(0, 5) > 4 ? $villageList->prefixes[mt_rand(0, count($villageList->prefixes) - 1)] : null;
        if ($prefix) {
            $chosenVillageName .= $prefix . ' ';
        }
        $chosenVillageName .= $villageList->nouns[mt_rand(0, count($villageList->nouns) - 1)] . $villageList->suffixes[mt_rand(0, count($villageList->suffixes) - 1)];
        return $chosenVillageName;
    }

    public static function getCharacterName()
    {
        $characterList = json_decode(file_get_contents(__DIR__ . "/json/characterclasses.json"));
        return self::getRandomAdjective() . ' ' . $characterList[mt_rand(0, count($characterList) - 1)];
    }
}
