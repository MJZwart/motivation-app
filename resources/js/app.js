import './bootstrap';
import './interceptors';
import '../assets/scss/app.scss';
      
// //Import store
import {store} from './store/store';
        
// //Import router
import router from './router/router';
        
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
import './icons.js';

app.mount('#app');