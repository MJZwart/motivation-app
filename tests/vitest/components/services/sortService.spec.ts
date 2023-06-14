/* eslint-disable max-lines-per-function */
import {sortValues} from '/js/services/sortService';


describe('Sort service', () => {
    it('Sorts a basic array with numbers', () => {
        // Arrange
        const arr = [{nr: 4},{nr: 1},{nr: 2},{nr: 8},{nr: 3},{nr: 10}];
        // Act
        const sortedArr = sortValues(arr, 'nr');
        // Assert
        expect(sortedArr).toEqual([{nr: 1},{nr: 2},{nr: 3},{nr: 4},{nr: 8},{nr: 10}]);
    });
    it('Sorts a basic array with numbers in reverse', () => {
        // Arrange
        const arr = [{nr: 4},{nr: 1},{nr: 2},{nr: 8},{nr: 3},{nr: 10}];
        // Act
        const sortedArr = sortValues(arr, 'nr', 'desc');
        // Assert
        expect(sortedArr).toEqual([{nr: 10},{nr: 8},{nr: 4},{nr: 3},{nr: 2},{nr: 1}]);
    });
    it('Sorts a array with multiple values', () => {
        // Arrange
        const arr = [{nr: 4, name: 'four'},{nr: 1, name: 'one'},{nr: 8, name: 'eight'},{nr: 2, name: 'two'}];
        // Act
        const sortedArr = sortValues(arr, 'nr');
        // Assert
        expect(sortedArr).toEqual([{nr: 1, name: 'one'},{nr: 2, name: 'two'},{nr: 4, name: 'four'},{nr: 8, name: 'eight'}]);
    });
    it('Sorts a array with strings alphabetically', () => {
        // Arrange
        const arr = [{nr: 4, name: 'four'},{nr: 1, name: 'one'},{nr: 8, name: 'eight'},{nr: 2, name: 'two'}];
        // Act
        const sortedArr = sortValues(arr, 'name');
        // Assert
        expect(sortedArr).toEqual([{nr: 8, name: 'eight'},{nr: 4, name: 'four'},{nr: 1, name: 'one'},{nr: 2, name: 'two'}]);
    });
    it('Sorts a array where some items don\'t have the given value', () => {
        // Arrange
        const arr = [{nr: 4, name: 'four'},{nr: 1, name: 'one'},{nr: 8},{nr: 2},{nr: 3, name: 'three'},{nr:5}];
        // Act
        const sortedArr = sortValues(arr, 'name');
        // Assert
        expect(sortedArr).toEqual([{nr: 4, name: 'four'},{nr: 1, name: 'one'},{nr: 3, name: 'three'},{nr: 8},{nr: 2},{nr:5}]);
    });
    it('Sorts a array where some items have null values', () => {
        // Arrange
        const arr = [{nr: 4, name: null},{nr: 1, name: 'one'},{nr: 8, name: null},{nr: 2, name: 'two'}];
        // Act
        const sortedArr = sortValues(arr, 'name');
        // Assert
        expect(sortedArr).toEqual([{nr: 1, name: 'one'},{nr: 2, name: 'two'},{nr: 4, name: null},{nr: 8, name: null}]);
    });
});