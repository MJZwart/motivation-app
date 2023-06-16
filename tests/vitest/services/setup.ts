Object.defineProperty(window, 'matchMedia', {
    writable: true,
    value: vi.fn().mockImplementation(query => ({
        matches: false,
        media: query,
        onchange: null,
        addListener: vi.fn(), // deprecated
        removeListener: vi.fn(), // deprecated
        addEventListener: vi.fn(),
        removeEventListener: vi.fn(),
        dispatchEvent: vi.fn(),
    })),
});


// since the test is running in node, localStorage is not defined, so we mock it
global.localStorage = {
    data: {},

    getItem(key) {
        if (key in this.data) return this.data[key];
        return null;
    },

    setItem(key, value) {
        this.data[key] = value;
    },

    clear() {
        this.data = {};
    },
    get length() {
        return Object.keys(global.localStorage).length;
    },
    key(_index) {
        return null;
    },
    removeItem(key) {
        delete global.localStorage[key];
    },
};
