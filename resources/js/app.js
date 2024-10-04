import './bootstrap';
import './interceptors';
import '../assets/scss/app.scss';

import {createApp} from 'vue';
import App from './App.vue';
import {fetchDefaultTheme} from '/js/services/themeService';
import { getMe } from './services/userService';

const app = createApp(App);

// Set colour scheme
fetchDefaultTheme();

// app.config.errorHandler = (err, vm) => {
//     // eslint-disable-next-line no-console
//     console.log(err);
//     // eslint-disable-next-line no-console
//     console.log(vm);
// }
// // @ts-ignore
// app.config.warnHandler = (err, _, info) => {
//     // eslint-disable-next-line no-console
//     console.log(err);
//     // eslint-disable-next-line no-console
//     console.log(info);
// }

//Import store
import {createPinia} from 'pinia';
app.use(createPinia());

await getMe();

//Import router
import router from './router/router';
app.use(router);

//Import vue-i18n translations
import i18n from './i18n.js';
app.use(i18n);

//Global imports
import BaseFormError from '/js/components/global/BaseFormError.vue';
app.component('BaseFormError', BaseFormError);
import Modal from '/js/components/global/Modal.vue';
app.component('Modal', Modal);
import ClearModal from '/js/components/global/ClearModal.vue';
app.component('ClearModal', ClearModal);
import Tooltip from '/js/components/global/Tooltip.vue';
app.component('Tooltip', Tooltip);
import Loading from '/js/components/global/Loading.vue';
app.component('Loading', Loading);
import Input from '/js/components/global/Input.vue';
app.component('SimpleInput', Input);
import Textarea from '/js/components/global/Textarea.vue';
app.component('SimpleTextarea', Textarea);
import Tutorial from '/js/components/global/Tutorial.vue';
app.component('Tutorial', Tutorial);
import Datepicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
app.component('Datepicker', Datepicker);
import SubmitButton from '/js/components/global/small/SubmitButton.vue';
app.component('SubmitButton', SubmitButton);
import SimpleCheckbox from '/js/components/global/small/SimpleCheckbox.vue';
app.component('SimpleCheckbox', SimpleCheckbox);
import SimpleFormCheckbox from '/js/components/global/small/SimpleFormCheckbox.vue';
app.component('SimpleFormCheckbox', SimpleFormCheckbox);
import ContentBlock from '/js/components/global/ContentBlock.vue';
app.component('ContentBlock', ContentBlock);
import {Icon} from '@iconify/vue';
app.component('Icon', Icon);

app.mount('#app');