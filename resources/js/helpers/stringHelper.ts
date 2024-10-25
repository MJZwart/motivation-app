

import {Achievement, NewAchievement} from 'resources/types/achievement';
import {ACHIEVEMENT_TRIGGERS} from '/js/constants/achievementsConstants';
import i18n from '/js/i18n';

/** Parses the achievement description from the type (eg Made {0} friends) and the amount */
export function parseAchievementTriggerDesc(achievement: Achievement | NewAchievement) {
    if (typeof achievement.trigger_amount === 'string') achievement.trigger_amount = parseInt(achievement.trigger_amount);
    const trigger = ACHIEVEMENT_TRIGGERS.find(item => item.type === achievement.trigger_type);
    if (!trigger) return;
    return i18n.global.t(trigger.desc, achievement.trigger_amount);
}

export function capitalizeOnlyFirst(text: string) {
    return text.charAt(0).toUpperCase() + text.slice(1).toLowerCase();
}
