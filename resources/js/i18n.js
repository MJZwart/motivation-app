// @ts-nocheck
import {createI18n} from 'vue-i18n';
import en from '../lang/en.js';
import nl from '../lang/nl.js';
// import en from '../lang/en.js';

export default createI18n({
    legacy: false,
    globalInjection: true,
    locale: getLanguage(),
    fallbackLocale: import.meta.env.VUE_APP_I18N_FALLBACK_LOCALE || 'en',
    messages:{en, nl},
});

export function getLanguage() {
    const storedLang = localStorage.getItem('lang');
    if (storedLang) return storedLang;
    const browserPref = navigator.language.split('-')[0];
    if (browserPref) return browserPref;
    return 'en';
}