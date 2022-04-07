import './bootstrap';
import Vue from 'vue';
import './interceptors';
import '../assets/scss/app.scss';

//Main pages
import App from './App.vue';

//Import store
import store from './store/store';

//Import router
import router from './router/router';

//Font-awesome icons
import {FontAwesomeIcon, FontAwesomeLayers} from '@fortawesome/vue-fontawesome';
Vue.component('FaIcon', FontAwesomeIcon);
Vue.component('FaIconLayers', FontAwesomeLayers);

import {library} from '@fortawesome/fontawesome-svg-core';
//Regular icons
import {
    faPenToSquare, 
    faFlag, 
    faBell, 
    faSquareCheck,
    faCirclePlay,
    faRectangleXmark,
} from '@fortawesome/free-regular-svg-icons';
//Solid icons
import {
    faTrash, 
    faSquarePlus, 
    faBan, 
    faPlus,
    faEnvelope, 
    faEnvelopeOpen,
    faCircle,
    faArrowTurnUp,
    faUserPlus,
} from '@fortawesome/free-solid-svg-icons';
library.add(
    faPenToSquare, 
    faTrash, 
    faEnvelope, 
    faSquarePlus, 
    faBan, 
    faFlag, 
    faBell, 
    faSquareCheck, 
    faPlus,
    faEnvelopeOpen,
    faCircle,
    faArrowTurnUp,
    faUserPlus,
    faCirclePlay,
    faRectangleXmark,
);

//Import vue-i18n translations
import VueI18n from 'vue-i18n';
Vue.use(VueI18n);

import i18n from './i18n';

new Vue({
    i18n,
    el: '#app',
    store,
    router,
    render: h => h(App),
});
