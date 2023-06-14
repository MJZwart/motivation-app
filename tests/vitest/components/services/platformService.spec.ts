import {getDiagnostics, parseDiagnostics, parseUserAgent} from '/js/services/platformService';
import platform from 'platform';

// Object.defineProperty(window, 'innerHeight', {value: 350});
// Object.defineProperty(window, 'innerWidth', {value: 500});
// Object.defineProperty(platform, 'description', {value: 'test platform'});

describe('Plaform service', () => {
    it('fetches the diagnostics', () => {
        // Arrange
        Object.defineProperty(window, 'innerHeight', {value: 350});
        Object.defineProperty(window, 'innerWidth', {value: 500});
        Object.defineProperty(platform, 'description', {value: 'test platform'});
        // Act
        const diags = getDiagnostics();
        // Assert
        expect(diags).toEqual('{"description":"test platform","windowHeight":350,"windowWidth":500}');
    });
    it('parses the user agent', () => {
        // Arrange
        const edgeWindowsUA = `Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 
        (KHTML, like Gecko) Chrome/42.0.2311.135 Safari/537.36 Edge/12.246`
        // Act
        const parsedUserAgent = parseUserAgent(edgeWindowsUA);
        // Assert
        expect(parsedUserAgent).toEqual('Microsoft Edge 12.246 on Windows 10 64-bit');
    });
    it('parses the diagnostics JSON into an object', () => {
        // Arrange
        const jsonDiagnostics = '{"description":"test platform","windowHeight":350,"windowWidth":500}';
        // Act
        const objectDiagnostics = parseDiagnostics(jsonDiagnostics);
        // Assert
        expect(objectDiagnostics).toEqual({'description':'test platform','windowHeight':350,'windowWidth':500});
    });
    it('returns null if the diagnostics are undefined', () => {
        // Act
        const diagsReturn = parseDiagnostics(undefined);
        // Assert
        expect(diagsReturn).toBe(null);
    });
});