// eslint-disable-next-line no-unused-vars, @typescript-eslint/no-unused-vars
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
    const start = DateTime.fromISO(date);
    const diff = today.diff(start, ['years', 'months', 'days', 'hours']);
    return parseTimeSince(diff);
}

/**
 * Parses an ISO string into a localized and timezoned string.
 * Expects a timestring in ISO format (including timezone)
 * If it's not an ISO string, set SQLString to true, so it can be rezoned into UTC
 * 
 * @param {string | Date | null} time
 * @param {boolean} SQLString
 */
export function parseDateTime(time, SQLString = false) {
    if (time === null) return;
    // const timezone = localStorage.getItem('timezone') || 'system';
    // const locale = localStorage.getItem('locale') || 'en-UK';
    const timezone = 'system';
    const locale = 'system';
    if (SQLString) {
        time = DateTime.fromSQL(time.toString()).setZone('UTC', {keepLocalTime: true}).toString();
    }
    const date = DateTime.fromISO(time.toString()).setZone(timezone).setLocale(locale);
    return date.toLocaleString(DateTime.DATETIME_MED);
}