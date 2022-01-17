<?php

namespace App\Helpers;

class RewardEnums {

    public const EXPERIENCE = "experience";
    public const LEVEL = "level";

    public const STRENGTH_EXP = "strength_exp";
    public const AGILITY_EXP = "agility_exp";
    public const ENDURANCE_EXP = "endurance_exp";
    public const INTELLIGENCE_EXP = "intelligence_exp";
    public const CHARISMA_EXP = "charisma_exp";

    public const STRENGTH = "strength";
    public const AGILITY = "agility";
    public const ENDURANCE = "endurance";
    public const INTELLIGENCE = "intelligence";
    public const CHARISMA = "charisma";

    public const ECONOMY = "economy";
    public const LABOUR = "labour";
    public const CRAFT = "craft";
    public const ART = "art";
    public const COMMUNITY = "community";

    public const ECONOMY_EXP = "economy_exp";
    public const LABOUR_EXP = "labour_exp";
    public const CRAFT_EXP = "craft_exp";
    public const ART_EXP = "art_exp";
    public const COMMUNITY_EXP = "community_exp";

    public const CHAR_STAT_EXP_ARRAY = [
        RewardEnums::STRENGTH_EXP, 
        RewardEnums::AGILITY_EXP, 
        RewardEnums::ENDURANCE_EXP, 
        RewardEnums::INTELLIGENCE_EXP, 
        RewardEnums::CHARISMA_EXP, 
        RewardEnums::EXPERIENCE];
    public const CHAR_STAT_ARRAY = [
        RewardEnums::STRENGTH, 
        RewardEnums::AGILITY, 
        RewardEnums::ENDURANCE, 
        RewardEnums::INTELLIGENCE, 
        RewardEnums::CHARISMA, 
        RewardEnums::LEVEL];

    public const VILL_STAT_EXP_ARRAY = [
        RewardEnums::ECONOMY_EXP, 
        RewardEnums::LABOUR_EXP, 
        RewardEnums::CRAFT_EXP, 
        RewardEnums::ART_EXP, 
        RewardEnums::COMMUNITY_EXP, 
        RewardEnums::EXPERIENCE];
    public const VILL_STAT_ARRAY = [
        RewardEnums::ECONOMY, 
        RewardEnums::LABOUR, 
        RewardEnums::CRAFT, 
        RewardEnums::ART, 
        RewardEnums::COMMUNITY, 
        RewardEnums::LEVEL];

}