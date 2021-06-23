import Vue from "vue";
import VueRouter from "vue-router";
import store from '../store/store';

Vue.use(VueRouter);


let routes = [
    {
        path: "/",
        component: require('../pages/Home.vue').default,
    },
    {
        path: "/login",
        component: require('../pages/Login.vue').default,
    },
    {
        path: "/register",
        component: require('../pages/Register.vue').default,
    },
    {
        path: "/character",
        component: require('../pages/Character.vue').default,
    },
    {
        path: "/notifications",
        component: require('../pages/Notifications.vue').default,
    },
    {
        path: "/settings",
        component: require('../pages/Settings.vue').default,
    },
    {
        path: "/achievements",
        component: require('../pages/Achievements.vue').default,
    },
    {
        name: "profile",
        path: "/profile/:id",
        component: require('../pages/Profile.vue').default,
    }
];

const router = new VueRouter({
    routes,
});

router.beforeEach((to, from, next) => {
    store.commit('setResponseMessage', []);
    store.commit('setStatus', 'hidden');

    next();
});

export default router;