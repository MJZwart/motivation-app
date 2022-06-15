// import Vue from 'vue';
import {createRouter, createWebHistory} from 'vue-router';
import {useMainStore} from '../store/store';
import {useMessageStore} from '../store/messageStore';

import Home from '../pages/home/Home.vue';
import Dashboard from '../pages/dashboard/Dashboard.vue';
import Login from '../pages/Login.vue';
import Register from '../pages/Register.vue';
import Overview from '../pages/overview/Overview.vue';
import Notifications from '../pages/notifications/Notifications.vue';
import Settings from '../pages/settings/Settings.vue';
import Profile from '../pages/profile/Profile.vue';
import AdminDashboard from '../pages/admin/AdminDashboard.vue';
import Welcome from '../pages/Welcome.vue';
import BugReport from '../pages/BugReport.vue';
import Messages from '../pages/messages/Messages.vue';
import Social from '../pages/social/Social.vue';
import Faq from '../pages/Faq.vue';
import Feedback from '../pages/Feedback.vue';
import Group from '../pages/social/components/GroupPage.vue';
import ErrorPage from '../pages/ErrorPage.vue';
import {useUserStore} from '../store/userStore';
// import Test from '../pages/Test.vue';

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
        path: '/group/:id',
        component: Group,
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
    {
        path: '/:pathMatch(.*)*',
        component: ErrorPage,
    },
    // {
    //     path: '/test',
    //     component: require('../pages/Test.vue').default,
    // },

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});


// eslint-disable-next-line complexity
router.beforeEach((to, from, next) => {
    const messageStore = useMessageStore();
    const mainStore = useMainStore();
    const userStore = useUserStore();
    mainStore.clearErrors();

    if (to.path == '/' && userStore.authenticated) {
        return next({path: '/dashboard'});
    }

    if (to.path != '/welcome' && userStore.user.first) {
        return next({path: '/welcome'});
    }

    if (to.meta && to.meta.requiresAuth && !userStore.authenticated) {
        return next({path: '/login'});
    }

    if (to.meta && to.meta.requiresAdmin && !userStore.isAdmin) {
        mainStore.addToast({'error': 'You are not authorized to view this page'});
        return next({path: '/dashboard'});
    }
    
    if (userStore.authenticated) {
        messageStore.hasUnread();
    }
    next();
});

export default router;
