import Vue from 'vue';
import VueRouter from 'vue-router';
import store from '../store/store';

import Home from '../pages/Home.vue';
import Dashboard from '../pages/Dashboard.vue';
import Login from '../pages/Login.vue';
import Register from '../pages/Register.vue';
import Overview from '../pages/Overview.vue';
import Notifications from '../pages/Notifications.vue';
import Settings from '../pages/Settings.vue';
import Profile from '../pages/Profile.vue';
import AdminDashboard from '../pages/AdminDashboard.vue';
import Welcome from '../pages/Welcome.vue';
import BugReport from '../pages/BugReport.vue';
import Messages from '../pages/Messages.vue';
import Social from '../pages/Social.vue';
import Faq from '../pages/Faq.vue';
import Feedback from '../pages/Feedback.vue';
// import Test from '../pages/Test.vue';

Vue.use(VueRouter);

let routes = [
    {
        path: '/',
        component: Home,
    },
    {
        path: '/dashboard',
        component: Dashboard,
        meta: {requiresAuth: true},
    },
    {
        path: '/login',
        component: Login,
    },
    {
        path: '/register',
        component: Register,
    },
    {
        path: '/overview',
        component: Overview,
        meta: {requiresAuth: true},
    },
    {
        path: '/notifications',
        component: Notifications,
        meta: {requiresAuth: true},
    },
    {
        path: '/settings',
        component: Settings,
        meta: {requiresAuth: true},
    },
    {
        name: 'profile',
        path: '/profile/:id',
        component: Profile,
    },
    {
        path: '/admindashboard',
        component: AdminDashboard,
        meta: {requiresAuth: true, requiresAdmin: true},
    },
    {
        path: '/welcome',
        component: Welcome,
        meta: {requiresAuth: true},
    },
    {
        path: '/bugreport',
        component: BugReport,
        meta: {requiresAuth: true},
    },
    {
        path: '/messages',
        component: Messages,
        meta: {requiresAuth: true},
    },
    {  
        path:'/social',
        component: Social,
        meta: {requiresAuth: true},
    },
    {
        path:'/faq',
        component: Faq,
    },
    {
        path:'/feedback',
        component: Feedback,
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

    if (to.meta && to.meta.requiresAuth && !store.getters['user/authenticated']) {
        return next({path: '/login'});
    }

    if (to.meta && to.meta.requiresAdmin && !store.getters['admin/isAdmin']) {
        store.dispatch('sendToasts', {'error': 'You are not authorized to view this page'});
        return next({path: '/dashboard'});
    }
    
    if (store.getters['user/authenticated']) {
        store.dispatch('hasUnread');
    }
    
    next();
});

export default router;
