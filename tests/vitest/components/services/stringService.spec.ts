import {capitalizeOnlyFirst} from '/js/services/stringService';

describe('String service', () => {
    it('Capitalizes the first letter and turns the rest into lowercase', () => {
        // Arrange
        const str = 'tesTstRiNg';
        // Act
        const capitalizedStr = capitalizeOnlyFirst(str);
        // Assert
        expect(capitalizedStr).toBe('Teststring');
    });
});