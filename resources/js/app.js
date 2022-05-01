import './bootstrap';
import './interceptors';
import '../assets/scss/app.scss';
      
import {createApp} from 'vue';
import App from './App.vue';

const app = createApp(App);

// @ts-ignore
app.config.errorHandler = (err, vm) => {
    // eslint-disable-next-line no-console
    console.log(err);
    // eslint-disable-next-line no-console
    console.log(vm);
}
// @ts-ignore
app.config.warnHandler = (err, _, info) => {
    // eslint-disable-next-line no-console
    console.log(err);
    // eslint-disable-next-line no-console
    console.log(info);
}

//Import store
import {createPinia} from 'pinia';
app.use(createPinia());

//Import router
import router from './router/router.js';
app.use(router);

//Import vue-i18n translations
import i18n from './i18n.js';
app.use(i18n);

//Font-awesome icons
import {FontAwesomeIcon, FontAwesomeLayers} from '@fortawesome/vue-fontawesome';
app.component('FaIcon', FontAwesomeIcon);
app.component('FaIconLayers', FontAwesomeLayers);
import './icons.js';

//Global imports
import BaseFormError from '/js/components/global/BaseFormError.vue';
app.component('BaseFormError', BaseFormError);
import Modal from '/js/components/global/Modal.vue';
app.component('Modal', Modal);
import Tooltip from '/js/components/global/Tooltip.vue';
app.component('Tooltip', Tooltip);
import Loading from '/js/components/global/Loading.vue';
app.component('Loading', Loading);
import Input from '/js/components/global/Input.vue';
app.component('Input', Input);

app.mount('#app');