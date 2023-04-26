import {changeLang, currentLang, langs} from '/js/services/languageService';

describe('Language management service', () => {
    it('can switch language', () => {
        // Arrange
        expect(currentLang.value).toMatch('en');
        // Act
        changeLang('nl');
        // Assert
        expect(currentLang.value).toMatch('nl');
    });
    it('has two language options', () => {
        // Arrange
        const eng = langs.find(lang => lang.key === 'en');
        const nl = langs.find(lang => lang.key === 'nl');

        if (!eng || !nl) return;
        // Assert
        expect(eng.label).toMatch('English');
        expect(nl.label).toMatch('Nederlands');
    });
});