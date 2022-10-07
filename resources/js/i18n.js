// @ts-nocheck
import {createI18n} from 'vue-i18n';
import en from '../lang/en.js';
import nl from '../lang/nl.js';
// import en from '../lang/en.js';

export default createI18n({
    legacy: false,
    globalInjection: true,
    locale: localStorage.getItem('lang') || 'en',
    fallbackLocale: import.meta.env.VUE_APP_I18N_FALLBACK_LOCALE || 'en',
    messages:{en, nl},
});