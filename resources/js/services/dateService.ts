import {DateTime, Duration} from 'luxon';
import i18n from '/js/i18n';

// eslint-disable-next-line complexity
export function parseTimeSince(diff: Duration): string {
    const times = [];
    if (diff.years > 1) times.push(i18n.global.t('year', diff.years));
    if (diff.months) times.push(i18n.global.t('month', diff.months));
    if (diff.years < 1 && diff.days) times.push(i18n.global.t('day', diff.days));
    if (diff.days < 1 && diff.hours) times.push(i18n.global.t('hour', Math.round(diff.hours)));
    if (!diff.years && !diff.months && !diff.days && !diff.hours) times.push('Just now');
    return times.join(', ');
}

export function daysSince(date: string): string {
    const today = DateTime.now();
    const start = DateTime.fromISO(date);
    const diff = today.diff(start, ['years', 'months', 'days', 'hours']);
    return parseTimeSince(diff);
}

/**
 * Parses an ISO string into a localized and timezoned string.
 * Expects a timestring in ISO format (including timezone)
 * If it's not an ISO string, set SQLString to true, so it can be rezoned into UTC
 */
export function parseDateTime(time: string | Date | null, SQLString = false): string | undefined {
    const date = parseIntoDateTime(time, SQLString);
    if (!date) return;
    return parseIntoStringFormat(date);
}

function parseIntoDateTime(time: string | Date | null, SQLString: boolean) {
    if (time === null) return;
    // const timezone = localStorage.getItem('timezone') || 'system';
    // const locale = localStorage.getItem('locale') || 'en-UK';
    const timezone = 'system';
    const locale = 'system';
    if (SQLString) {
        time = DateTime.fromSQL(time.toString()).setZone('UTC', {keepLocalTime: true}).toString();
        if (time === null) return;
    }
    return DateTime.fromISO(time.toString()).setZone(timezone).setLocale(locale);
}

function parseIntoStringFormat(date: DateTime) {
    const currentDate = DateTime.now().toFormat('yyyyMMdd');
    const time = date.toFormat('HH:mm')
    if (date.toFormat('yyyyMMdd') === currentDate)
        return i18n.global.t('today') + ' ' + time;
    else if (date.plus({days: 1}).toFormat('yyyyMMdd') === currentDate)
        return i18n.global.t('yesterday') + ' ' + time;
    else if (date.minus({days: 1}).toFormat('yyyyMMdd') === currentDate)
        return i18n.global.t('tomorrow') + ' ' + time;
    return date.toLocaleString(DateTime.DATETIME_MED);
}

export function getDateWithAddedDays(date: Date, daysToAdd: number, parsed = true): DateTime | string {
    let dateTime = DateTime.fromISO(date.toString()).setZone('system').setLocale('system');
    dateTime = dateTime.plus({days: daysToAdd});
    if (!parsed) return dateTime;
    return dateTime.toLocaleString(DateTime.DATETIME_MED);
}

export function isDateItem(item: string) {
    return DateTime.fromISO(item).isValid;
}

export function getYesterdayDate(): Date {
    const date = new Date();
    date.setDate(date.getDate()-1);
    return date;
}