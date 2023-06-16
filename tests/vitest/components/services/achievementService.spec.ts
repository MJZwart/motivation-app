import {newAchievementInstance, parseAchievementTriggerDesc} from '/js/services/achievementService';

const testAchievement = {
    description: 'This is a test achievement',
    name: 'Test achievement',
    trigger_amount: 10,
    trigger_type: 'TASKS_MADE',
}
const testAchievementWithString = {
    description: 'This is another test achievement',
    name: 'Test achievement',
    trigger_amount: '10',
    trigger_type: 'TASKS_COMPLETED',
}
const testAchievementWithUnknownType = {
    description: 'This is a third test achievement',
    name: 'Test achievement',
    trigger_amount: '10',
    trigger_type: 'TEST_TYPE',
}

describe('Achievement service', () => {
    it('parses the achievement trigger successfully', () => {
        // Act
        const parsedTriggerDesc = parseAchievementTriggerDesc(testAchievement);
        // Assert
        expect(parsedTriggerDesc).toMatch('Created 10 tasks.');
    });
    it('parses the achievement trigger even when the trigger amount is a string', () => {
        // Act
        const parsedTriggerDesc = parseAchievementTriggerDesc(testAchievementWithString);
        // Assert
        expect(parsedTriggerDesc).toMatch('Completed 10 tasks.');
    });
    it('doesn\'t parse the trigger if the type is unknown', () => {
        // Act
        const shouldBeUndefined = parseAchievementTriggerDesc(testAchievementWithUnknownType);
        // Assert
        expect(shouldBeUndefined).toBeUndefined();
    });
    it('creates an empty new achievement instance', () => {
        // Arrange
        const newAchievement = {
            description: '',
            name: '',
            trigger_amount: 0,
            trigger_type: '',
        };
        // Act
        const createdNewAchievement = newAchievementInstance();
        // Assert
        expect(createdNewAchievement).toEqual(newAchievement);
    });
});