// eslint-disable-next-line no-unused-vars
import {DateTime, Duration} from 'luxon';
import i18n from '/js/i18n';

/**
 * @param {Duration} diff
 * @returns {String}
 */
// eslint-disable-next-line complexity
export function parseTimeSince(diff) {
    let times = [];
    if (diff.years > 1) times.push(i18n.global.t('year', diff.years));
    if (diff.months) times.push(i18n.global.t('month', diff.months));
    if (diff.years < 1 && diff.days) times.push(i18n.global.t('day', diff.days));
    if (diff.days < 1 && diff.hours) times.push(i18n.global.t('hour', Math.round(diff.hours)));
    if (!diff.years && !diff.months && !diff.days && !diff.hours) times.push('Just now');
    return times.join(', ');
}

/**
 * @param {String} date
 * @returns {String}
 */
export function daysSince(date) {
    const today = DateTime.now();
    const start = DateTime.fromSQL(date);
    const diff = today.diff(start, ['years', 'months', 'days', 'hours']);
    return parseTimeSince(diff);
}