import {LanguageOption} from 'resources/types/global';
import {ref} from 'vue';
import i18n, {getLanguage} from '/js/i18n';

export const currentLang = ref(getLanguage());

export const langs: LanguageOption[] = [
    {key: 'en', label: 'English', flag: 'fi-gb'}, 
    {key: 'nl', label: 'Nederlands', flag: 'fi-nl'},
];

export function changeLang(lang: 'en' | 'nl', auth = false) {
    i18n.global.locale.value = lang;
    if (!auth) localStorage.setItem('lang', lang);
    currentLang.value = lang;
}