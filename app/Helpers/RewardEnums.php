<?php

namespace App\Helpers;

class RewardEnums
{

    public const EXPERIENCE = "experience";
    public const LEVEL = "level";

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

    public const COINS = "coins";

    public const STAT_EXP_ARRAY = [
        RewardEnums::ECONOMY_EXP,
        RewardEnums::LABOUR_EXP,
        RewardEnums::CRAFT_EXP,
        RewardEnums::ART_EXP,
        RewardEnums::COMMUNITY_EXP,
        RewardEnums::EXPERIENCE,
        RewardEnums::COINS
    ];
    public const STAT_ARRAY = [
        RewardEnums::ECONOMY,
        RewardEnums::LABOUR,
        RewardEnums::CRAFT,
        RewardEnums::ART,
        RewardEnums::COMMUNITY,
        RewardEnums::LEVEL
    ];
}
