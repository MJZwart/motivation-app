// import Vue from 'vue';
// import './interceptors';
// import '../assets/scss/app.scss';

// //Main pages
// import App from './App.vue';


// //Import BootstrapVue
// import 'bootstrap-vue/dist/bootstrap-vue-icons.min.css';
// Vue.use(BootstrapVueIcons);
// Vue.use(BootstrapVue, {
//     BButton: {variant: 'primary'},
// });
// Vue.use(ToastPlugin);
    
// //Import vue-i18n translations
// import VueI18n from 'vue-i18n';
// Vue.use(VueI18n);
    
// import i18n from './i18n';
    
// import toastService from './services/toastService';
// const app = new Vue({
//     i18n,
//     el: '#app',
//     store,
//     router,
//     render: h => h(App),
// });
        
// // @ts-ignore
// toastService.$app = app;
        
// //Import store
import store from './store/store';
        
// //Import router
import router from './router/router';
        
import './bootstrap';
        
import {createApp} from 'vue';
import App from './App.vue';
        
const app = createApp(App);
app.mount('#app');
        
app.use(store);
app.use(router);
        
import {BootstrapVue, BootstrapVueIcons, ToastPlugin} from 'bootstrap-vue';

app.use(BootstrapVue);