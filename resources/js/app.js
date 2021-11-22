require("./bootstrap");
import Vue from "vue";
import './interceptors';
import '../assets/scss/app.scss';

//Main pages
import App from "./App.vue";

//Import store
import store from "./store/store";

//Import router
import router from "./router/router";

//Import BootstrapVue
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue';
import 'bootstrap-vue/dist/bootstrap-vue-icons.min.css';
//import 'bootstrap/dist/css/bootstrap.css'
Vue.use(BootstrapVueIcons);
Vue.use(BootstrapVue, {
    BButton: { variant: 'primary' }
});

new Vue({
    el: "#app",
    store,
    router,
    render: (h) => h(App),
});
