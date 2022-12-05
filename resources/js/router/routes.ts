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
import Group from '../pages/social/components/groups/GroupPage.vue';
import ErrorPage from '../pages/ErrorPage.vue';
import PrivacyPolicy from '../pages/legal/PrivacyPolicy.vue';
import TOS from '../pages/legal/TOS.vue';
import ForgotPassword from '../pages/ForgotPassword.vue';
import ResetPassword from '../pages/ResetPassword.vue';
import Changelog from '../pages/changelog/Changelog.vue';
// import Test from '../pages/Test.vue';

export const routes = [
    {
        path: '/',
        component: Home,
    },
    {
        path: '/dashboard',
        component: Dashboard,
        meta: {
            requiresAuth: true,
            title: 'Dashboard',
        },
    },
    {
        name: 'login',
        path: '/login',
        component: Login,
        meta: {
            title: 'Login',
        },
    },
    {
        path: '/register',
        component: Register,
        meta: {
            title: 'Register',
        },
    },
    {
        path: '/overview',
        component: Overview,
        meta: {
            requiresAuth: true,
            title: 'Overview',
        },
    },
    {
        path: '/notifications',
        component: Notifications,
        meta: {
            requiresAuth: true,
            title: 'Notifications',
        },
    },
    {
        path: '/settings',
        component: Settings,
        meta: {
            requiresAuth: true,
            title: 'Settings',
        },
    },
    {
        name: 'profile',
        path: '/profile/:id',
        component: Profile,
        meta: {
            title: 'Profile',
        },
    },
    {
        path: '/admindashboard',
        component: AdminDashboard,
        meta: {
            requiresAuth: true, 
            requiresAdmin: true,
            title: 'Admin dashboard',
        },
    },
    {
        path: '/welcome',
        component: Welcome,
        meta: {
            requiresAuth: true,
            title: 'Welcome',
        },
    },
    {
        path: '/bugreport',
        component: BugReport,
        meta: {
            title: 'Report a bug',
        },
    },
    {
        path: '/messages',
        component: Messages,
        meta: {
            requiresAuth: true,
            title: 'Messages',
        },
    },
    {
        path: '/social',
        component: Social,
        meta: {
            requiresAuth: true,
            title: 'Social',
        },
    },
    {
        path: '/group/:id',
        component: Group,
        meta: {
            requiresAuth: true,
            title: 'Group',
            breadcrumbs: true,
        },
    },
    {
        path: '/faq',
        component: Faq,
        meta: {
            title: 'Frequently asked questions',
        },
    },
    {
        path: '/feedback',
        component: Feedback,
        meta: {
            title: 'Feedback',
        },
    },
    {
        path: '/privacy',
        component: PrivacyPolicy,
        meta: {
            title: 'Privacy Policy',
        },
    },
    {
        path: '/tos',
        component: TOS,
        meta: {
            title: 'Terms of Service',
        },
    },
    {
        path: '/:pathMatch(.*)*',
        component: ErrorPage,
        meta: {
            title: 'Error',
        },
    },
    {
        name: 'Error',
        path: '/error',
        component: ErrorPage,
        meta: {
            title: 'Error',
        },
    },
    {
        name: 'ForgotPassword',
        path: '/forgot-password',
        component: ForgotPassword,
        meta: {
            title: 'Forgot password',
        },
    },
    {
        name: 'ResetPassword',
        path: '/reset-password',
        component: ResetPassword,
        meta: {
            title: 'Reset password',
        },
    },
    {
        name: 'Changelog',
        path: '/changelog',
        component: Changelog,
        meta: {
            title: 'Changelog',
        },
    },
    // {
    //     path: '/test',
    //     component: require('../pages/Test.vue').default,
    // },
];
