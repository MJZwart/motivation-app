import {LanguageOption} from 'resources/types/global';
import {ref} from 'vue';
import i18n from '/js/i18n';

export const currentLang = ref(localStorage.getItem('lang') || 'en');

export const langs: LanguageOption[] = [
    {key: 'en', label: 'English', flag: 'fi-gb'}, 
    {key: 'nl', label: 'Nederlands', flag: 'fi-nl'},
];

export function changeLang(lang: 'en' | 'nl') {
    i18n.global.locale.value = lang;
    localStorage.setItem('lang', lang);
    currentLang.value = lang;
}