import {DateTime, Duration} from 'luxon';
import {parseTimeSince, daysSince, parseDateTime, isDateItem, getDateWithAddedDays, getYesterdayDate} 
    from '/js/services/dateService';

// eslint-disable-next-line max-lines-per-function
describe('Date service', () => {
    it('parses the time since', () => {
        // Arrange
        const oneYearThreeMonths = Duration.fromISO('P1Y3M1W');
        const threeDaysFourHours = Duration.fromISO('P3DT4H');
        const fourHoursTwentyMinutes = Duration.fromISO('PT4H20M');
        const justNow = Duration.fromISO('PT20S');

        // Act
        const parsedOneYearThreeMonths = parseTimeSince(oneYearThreeMonths);
        const parsedThreeDaysFourHours = parseTimeSince(threeDaysFourHours);
        const parsedFourHoursTwentyMinutes = parseTimeSince(fourHoursTwentyMinutes);
        const parsedJustNow = parseTimeSince(justNow);

        // Assert
        expect(parsedOneYearThreeMonths).toMatch('1 year, 3 months');
        expect(parsedThreeDaysFourHours).toMatch('3 days');
        expect(parsedFourHoursTwentyMinutes).toMatch('4 hours');
        expect(parsedJustNow).toMatch('Just now');
    });
    it('calculates the time between a given date and now correctly', () => {
        // Arrange
        const fourMonthsAgo = DateTime.now().minus(Duration.fromISO('P4M')).toISO();
        // Act
        const parsedFourMonths = daysSince(fourMonthsAgo);
        // Assert
        expect(parsedFourMonths).toMatch('4 months');
    });
    it('parses a Date into DateTime with locale', () => {
        // Arrange
        const newDate = new Date('12 August 2021 13:15').toISOString();
        // Act
        const parsedDateTime = parseDateTime(newDate);
        // Assert
        expect(parsedDateTime).toMatch('12 Aug 2021, 13:15');
    });
    it('parses a date as SQL string into DateTime with locale', () => {
        // Arrange
        const newDate = '2023-05-13 06:42:12'; //Date is set as SQL string, which is assumed UTC.
        // Act
        const parsedDateTime = parseDateTime(newDate, true);
        // Assert
        expect(parsedDateTime).toMatch('13 May 2023, 08:42'); //Expected time is 2hr (MAY FAIL ON DST)
    });
    it('returns undefined when null is given', () => {
        // Arrange
        const nullValue = null;
        // Act
        const shouldBeUndefined = parseDateTime(nullValue);
        // Assert
        expect(shouldBeUndefined).toBe(undefined);
    });
    it('return today when the date given is today', () => {
        // Arrange
        const todayAsDate = new Date().toISOString();
        // Act
        const parsedToday = parseDateTime(todayAsDate);
        // Assert
        expect(parsedToday).toMatch('Today');
    });
    it('return yesterday when the date given is yesterday', () => {
        // Arrange
        const yesterdayAsDate = DateTime.now().minus(Duration.fromISO('P1D')).toISO();
        // Act
        const parsedYesterday = parseDateTime(yesterdayAsDate);
        // Assert
        expect(parsedYesterday).toMatch('Yesterday');
    });
    it('return tomorrow when the date given is tomorrow', () => {
        // Arrange
        const tomorrowAsDate = DateTime.now().plus(Duration.fromISO('P1D')).toISO();
        // Act
        const parsedTomorrow = parseDateTime(tomorrowAsDate);
        // Assert
        expect(parsedTomorrow).toMatch('Tomorrow');
    });

    it('correctly spots a valid date string', () => {
        // Arrange
        const validDateString = DateTime.now().toISO();
        // Act
        const shouldBeTrue = isDateItem(validDateString);
        // Assert
        expect(shouldBeTrue).toBe(true);
    });
    it('correctly spots an invalid date string', () => {
        // Arrange
        const invalidDateString = '2021-32-40';
        // Act
        const shouldBeFalse = isDateItem(invalidDateString);
        // Assert
        expect(shouldBeFalse).toBe(false);
    });
    it('correctly adds days to a given date, not parsed', () => {
        // Arrange
        const dateObject = DateTime.now().toISO();
        const daysToAdd = 5;
        const dateWithFiveAddedDays = DateTime.now().plus({days: daysToAdd}).toLocaleString(DateTime.DATETIME_SHORT);
        // Act
        const dateWithAddedDays = getDateWithAddedDays(dateObject, daysToAdd, false);
        // Assert
        expect(dateWithAddedDays.toLocaleString(DateTime.DATETIME_SHORT)).toMatch(dateWithFiveAddedDays);
    });
    it('correctly adds days to a given date, parsed', () => {
        // Arrange
        const dateObject = DateTime.now().toISO();
        const daysToAdd = 7;
        const dateWithFiveAddedDays = DateTime.now().plus({days: daysToAdd}).toLocaleString(DateTime.DATETIME_MED);
        // Act
        const dateWithAddedDays = getDateWithAddedDays(dateObject, daysToAdd);
        // Assert
        expect(dateWithAddedDays).toMatch(dateWithFiveAddedDays);
    });
    it('correctly gets yesterdays date', () => {
        // Arrange
        const today = new Date();
        const yesterday = new Date();
        yesterday.setDate(today.getDate() -1);
        // Act
        const shouldBeYesterday = getYesterdayDate().toDateString();
        // Assert
        expect(shouldBeYesterday).toMatch(yesterday.toDateString());
    });
});