// import Vue from 'vue';
    
// import toastService from './services/toastService';
// const app = new Vue({
//     i18n,
//     el: '#app',
//     store,
//     router,
//     render: h => h(App),
// });
        
// //Import store
import {store} from './store/store';
        
// //Import router
import router from './router/router';
        
import './bootstrap';
import './interceptors';
import '../assets/scss/app.scss';
        
import {createApp} from 'vue';
import App from './App.vue';
        
const app = createApp(App);
        
app.use(store);
app.use(router);


//Import vue-i18n translations
import i18n from './i18n';
app.use(i18n);



//Font-awesome icons
import {FontAwesomeIcon, FontAwesomeLayers} from '@fortawesome/vue-fontawesome';
app.component('FaIcon', FontAwesomeIcon);
app.component('FaIconLayers', FontAwesomeLayers);

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

app.mount('#app');