import Vue from "vue";
import VueRouter from "vue-router";
import store from '../store/store';

Vue.use(VueRouter);


let routes = [
    {
        path: "/",
        component: require('../pages/Home.vue').default,
        meta: { requiresAuth: true },
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
        path: "/overview",
        component: require('../pages/Overview.vue').default,
        meta: { requiresAuth: true },
    },
    {
        path: "/notifications",
        component: require('../pages/Notifications.vue').default,
        meta: { requiresAuth: true },
    },
    {
        path: "/settings",
        component: require('../pages/Settings.vue').default,
        meta: { requiresAuth: true },
    },
    {
        path: "/achievements",
        component: require('../pages/Achievements.vue').default,
        meta: { requiresAuth: true },
    },
    {
        name: "profile",
        path: "/profile/:id",
        component: require('../pages/Profile.vue').default,
    },
    {
        path: "/friends",
        component: require('../pages/Friends.vue').default,
        meta: { requiresAuth: true },
    }
];

const router = new VueRouter({
    routes,
});

router.beforeEach((to, from, next) => {
    store.dispatch('clearInformationBlock');
    store.dispatch('notification/hasUnreadNotifications');

    if (to.meta.requiresAuth && !store.getters['user/authenticated']) {
        return next({ path: '/login' });
    }

    next();
});

export default router;