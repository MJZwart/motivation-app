import type {Diagnostics} from 'resources/types/global';
import platform from 'platform';

export function getDiagnostics() {
    const diagnostics ={} as Diagnostics;
    diagnostics.description = platform.description;
    diagnostics.windowHeight = window.innerHeight;
    diagnostics.windowWidth = window.innerWidth;
    return JSON.stringify(diagnostics);
}

export function parseUserAgent(userAgent: string) {
    return platform.parse(userAgent).toString();
}

export function parseDiagnostics(diagnostics: string | undefined): Diagnostics | null {
    if (!diagnostics) return null;
    return JSON.parse(diagnostics);
}