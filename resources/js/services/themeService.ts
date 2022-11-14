import {ref} from 'vue';

export const currentTheme = ref('dark');

export function fetchDefaultTheme() {
    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
        setCurrentTheme('dark');
    } else if (window.matchMedia('(prefers-color-scheme: light)').matches) {
        setCurrentTheme('light');
    }
}

export function setCurrentTheme(theme: string, override = false) {
    const storedTheme = localStorage.getItem('theme');
    if (!override && storedTheme) {
        currentTheme.value = storedTheme;
        setThemeVariables(storedTheme);
    }
    else if (theme === 'dark' || theme === 'light') {
        setThemeVariables(theme);
        currentTheme.value = theme;
        localStorage.setItem('theme', theme);
    }
}

function setThemeVariables(theme: string) {
    const root = document.querySelector(':root') as HTMLElement;
    const style = root.style;
    if (theme === 'light') {
        lightStyle.forEach(element => {
            style.setProperty(element.key, element.value);
        });
    } else {
        darkStyles.forEach(element => {
            style.setProperty(element.key, element.value);
        });
    }
}

/**
 * Dark theme
 */
const darkStyles = [
    {key: '--primary',              value: '#252525'},
    {key: '--primary-as-text',      value: '#cecece'},
    {key: '--primary-text',         value: '#e4e4e4'},
    {key: '--secondary',            value: '#3b3b3b'},
    {key: '--body-text',            value: '#e4e4e4'},
    {key: '--action-button',        value: '#2d8562'},
    {key: '--action-button-hover',  value: '#22644a'},
    {key: '--button',               value: '#252525'},
    {key: '--button-hover',         value: '#353535'},
    {key: '--cancel-button',        value: '#323232'},
    {key: '--cancel-button-hover',  value: '#252525'},

    {key: '--grey',                 value: 'grey'},
    {key: '--light-green',          value: '#2d8562'},
    {key: '--green',                value: '#2d815f'},
    {key: '--dark-green',           value: '#123325'},

    {key: '--background',           value: '#121212'},
    {key: '--background-darker',    value: '#252525'},
    {key: '--background-2',         value: '#3b3b3b'},
    {key: '--background-2-text',    value: '#e9e9e9'},
    {key: '--input-background',     value: '#494949'},
    {key: '--input-active',         value: '#858585'},

    {key: '--border-color',         value: '#494949'},
    {key: '--link-text',            value: '#cecece'},

    {key: '--nth-of-type',          value: '#3b3b3bb4'},
    {key: '--hover',                value: '#3b3b3b81'},
];

/**
 * Light theme
 */
const lightStyle = [
    {key: '--primary',              value: '#1D3354'},
    {key: '--primary-as-text',      value: '#1D3354'},
    {key: '--primary-text',         value: 'white'},
    {key: '--secondary',            value: '#15253d'},
    {key: '--body-text',            value: 'rgb(29, 29, 29)'},
    {key: '--action-button',        value: '#1D3354'},
    {key: '--action-button-hover',  value: '#15253d'},
    {key: '--button',               value: '#1D3354'},
    {key: '--button-hover',         value: '#15253d'},
    {key: '--cancel-button',        value: '#888'},
    {key: '--cancel-button-hover',  value: '#666'},

    {key: '--grey',                 value: 'grey'},
    {key: '--light-green',          value: '#2d8562'},
    {key: '--green',                value: '#1d543e'},
    {key: '--dark-green',           value: '#123325'},

    {key: '--background',           value: '#f6f8fa'},
    {key: '--background-darker',    value: '#dee2e6'},
    {key: '--background-2',         value: 'white'},
    {key: '--background-2-text',    value: 'black'},
    {key: '--input-background',     value: 'white'},
    {key: '--input-active',         value: 'white'},

    {key: '--border-color',         value: '#dee2e6'},
    {key: '--link-text',            value: '#1D3354'},

    {key: '--nth-of-type',          value: 'rgba(0, 0, 0, .04)'},
    {key: '--hover',                value: 'rgba(0, 0, 0, .1)'},
];