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

//Import BootstrapVue
import {BootstrapVue, BootstrapVueIcons} from 'bootstrap-vue';
import 'bootstrap-vue/dist/bootstrap-vue-icons.min.css';
Vue.use(BootstrapVueIcons);
Vue.use(BootstrapVue, {
    BButton: {variant: 'primary'},
});

//Font-awesome icons
import {library} from '@fortawesome/fontawesome-svg-core';
import {FontAwesomeIcon} from '@fortawesome/vue-fontawesome';
Vue.component('FaIcon', FontAwesomeIcon);

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
