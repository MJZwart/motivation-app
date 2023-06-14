import {capitalizeOnlyFirst} from '/js/services/stringService';


describe('String service', () => {
    it('', () => {
        // Arrange
        const str = 'tesTstRiNg';
        // Act
        const capitalizedStr = capitalizeOnlyFirst(str);
        // Assert
        expect(capitalizedStr).toBe('Teststring');
    });
});