import Vue from 'vue';
import VueRouter from 'vue-router';
import store from '../store/store';

Vue.use(VueRouter);

let routes = [
    {
        path: '/',
        component: require('../pages/Home.vue').default,
    },
    {
        path: '/dashboard',
        component: require('../pages/Dashboard.vue').default,
        meta: {requiresAuth: true},
    },
    {
        path: '/login',
        component: require('../pages/Login.vue').default,
    },
    {
        path: '/register',
        component: require('../pages/Register.vue').default,
    },
    {
        path: '/overview',
        component: require('../pages/Overview.vue').default,
        meta: {requiresAuth: true},
    },
    {
        path: '/notifications',
        component: require('../pages/Notifications.vue').default,
        meta: {requiresAuth: true},
    },
    {
        path: '/settings',
        component: require('../pages/Settings.vue').default,
        meta: {requiresAuth: true},
    },
    {
        name: 'profile',
        path: '/profile/:id',
        component: require('../pages/Profile.vue').default,
    },
    {
        path: '/friends',
        component: require('../pages/Friends.vue').default,
        meta: {requiresAuth: true},
    },
    {
        path: '/achievements',
        component: require('../pages/Achievements.vue').default,
        meta: {requiresAuth: true, requiresAdmin: true},
    },
    {
        path: '/admin',
        component: require('../pages/Admin.vue').default,
        meta: {requiresAuth: true, requiresAdmin: true},
    },
    {
        path: '/search',
        component: require('../pages/SearchResults.vue').default,
        meta: {requiresAuth: true},
    },
    {
        path: '/welcome',
        component: require('../pages/Welcome.vue').default,
        meta: {requiresAuth: true},
    },
    // {
    //     path: '/test',
    //     component: require('../pages/Test.vue').default,
    // },

];

const router = new VueRouter({
    routes,
});


// eslint-disable-next-line complexity
router.beforeEach((to, from, next) => {
    store.dispatch('clearErrors');

    if (to.path == '/' && store.getters['user/authenticated']) {
        return next({path: '/dashboard'});
    }

    if (to.path != '/welcome' && store.getters['user/getUser'].first) {
        return next({path: '/welcome'});
    }

    if (to.meta.requiresAuth && !store.getters['user/authenticated']) {
        return next({path: '/login'});
    }

    if (to.meta.requiresAdmin && !store.getters['admin/isAdmin']) {
        store.dispatch('user/logout');
        return next({path: '/login'});
    }
    
    if (store.getters['user/authenticated']) {
        store.dispatch('notification/hasUnreadNotifications');
    }
    
    next();
});

export default router;