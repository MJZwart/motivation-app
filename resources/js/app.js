import './bootstrap';
import './interceptors';
import '../assets/scss/app.scss';
      
import {createApp} from 'vue';
import App from './App.vue';

const app = createApp(App);

//Import store
import {store} from './store/store';
import {createPinia} from 'pinia';
app.use(createPinia());
app.use(store);

//Import router
import router from './router/router';
app.use(router);

//Import vue-i18n translations
import i18n from './i18n';
app.use(i18n);

//Font-awesome icons
import {FontAwesomeIcon, FontAwesomeLayers} from '@fortawesome/vue-fontawesome';
app.component('FaIcon', FontAwesomeIcon);
app.component('FaIconLayers', FontAwesomeLayers);
import './icons.js';

app.mount('#app');