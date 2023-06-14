/* eslint-disable max-lines-per-function */
import {currentTheme, fetchDefaultTheme, setCurrentTheme} from '/js/services/themeService';
import {vi} from 'vitest';

function mockMatchMediaDark() {
    Object.defineProperty(window, 'matchMedia', {
        value: vi.fn().mockImplementation(query => ({
            matches: query === '(prefers-color-scheme: dark)',
        })),
    });
}

function mockMatchMediaLight() {
    Object.defineProperty(window, 'matchMedia', {
        value: vi.fn().mockImplementation(query => ({
            matches: query === '(prefers-color-scheme: light)',
        })),
    });
}
    
describe('Theme service', () => {
    beforeEach(() => {
        localStorage.clear();
        currentTheme.value = 'dark';
    });
    it('checks that the default theme is always dark', () => {
        // Assert
        expect(currentTheme.value).toBe('dark');
    });
    it('fetches the default theme from the window when the window setting is dark', () => {
        // Arrange
        mockMatchMediaDark();
        const spy = vi.spyOn(localStorage, 'setItem');
        // Act
        fetchDefaultTheme();
        // Assert
        expect(currentTheme.value).toBe('dark');
        expect(spy).toHaveBeenCalledWith('theme', 'dark');
    });
    it('fetches the default theme from the window when the window setting is light', () => {
        // Arrange
        mockMatchMediaLight();
        const spy = vi.spyOn(localStorage, 'setItem');
        // Act
        fetchDefaultTheme();
        // Assert
        expect(currentTheme.value).toBe('light');
        expect(spy).toHaveBeenCalledWith('theme', 'light');
    });
    it('picks the stored theme is overriding is not picked', () => {
        // Arrange
        localStorage.setItem('theme', 'light');
        // Act
        setCurrentTheme('dark', false)
        // Assert
        expect(currentTheme.value).toBe('light');
    });
    it('will override the stored theme when override is set to true', () => {
        // Arrange
        localStorage.setItem('theme', 'light');
        // Act
        setCurrentTheme('dark', true)
        // Assert
        expect(currentTheme.value).toBe('dark');
    });
    it('sets the theme when there is no stored theme', () => {
        // Arrange
        const spy = vi.spyOn(localStorage, 'setItem');
        // Act
        setCurrentTheme('light', false);
        // Assert
        expect(currentTheme.value).toBe('light');
        expect(spy).toHaveBeenCalledWith('theme', 'light');
    });
    it('is not able to set a theme that doesn\'t exist', () => {
        // Arrange
        const spy = vi.spyOn(localStorage, 'setItem');
        // Act
        setCurrentTheme('invalid');
        // Assert
        expect(currentTheme.value).toBe('dark');
        expect(spy).toHaveBeenCalledTimes(0);
    });
});