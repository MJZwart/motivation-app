// @ts-nocheck
import Vue from 'vue';
import VueI18n from 'vue-i18n';
import en from '../lang/en.json'
// import en from '../lang/en.js';
Vue.use(VueI18n);

export default new VueI18n({
    locale: import.meta.env.VUE_APP_I18N_LOCALE || 'en',
    fallbackLocale: import.meta.env.VUE_APP_I18N_FALLBACK_LOCALE || 'en',
    messages:{en},
});