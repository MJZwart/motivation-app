import {DateTime} from 'luxon';

/**
 * Parses an ISO string into a localized and timezoned string.
 * Expects a timestring in ISO format (including timezone)
 * If it's not an ISO string, set SQLString to true, so it can be rezoned into UTC
 * 
 * @param {string} time
 * @param {boolean} SQLString
 */
export function parseDateTime(time, SQLString = false) {
    // const timezone = localStorage.getItem('timezone') || 'system';
    // const locale = localStorage.getItem('locale') || 'en-UK';
    const timezone = 'system';
    const locale = 'system';
    if (SQLString) {
        time = DateTime.fromSQL(time).setZone('UTC', {keepLocalTime: true}).toString();
    }
    const date = DateTime.fromISO(time).setZone(timezone).setLocale(locale);
    return date.toLocaleString(DateTime.DATETIME_MED);
}