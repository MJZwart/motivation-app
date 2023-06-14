import {clearErrors, errors, setErrorMessages} from '/js/services/errorService';

describe('Error service', () => {
    it('sets an error', () => {
        // Arrange
        const error = {test: ['wrong', 'also more wrong']};
        // Act
        setErrorMessages(error);
        // Assert
        expect(errors.value).toEqual({test: ['wrong', 'also more wrong']});
    });
    it('clears all errors', () => {
        // Arrange
        const error = {test: ['wrong', 'also more wrong']};
        // Act
        setErrorMessages(error);
        clearErrors();
        // Assert
        expect(errors.value).toEqual(null);
    });
});