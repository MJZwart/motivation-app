require("./bootstrap");
import Vue from "vue";
import './interceptors';

//Main pages
import App from "./App.vue";

//Import store
import store from "./store/store";

//Import router
import router from "./router/router";

//Import BootstrapVue
import { BootstrapVue, BootstrapVueIcons } from 'bootstrap-vue';
import 'bootstrap-vue/dist/bootstrap-vue-icons.min.css';
Vue.use(BootstrapVueIcons);
Vue.use(BootstrapVue);

new Vue({
    el: "#app",
    store,
    router,
    render: (h) => h(App),
});
