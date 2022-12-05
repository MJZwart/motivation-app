// import Test from '../pages/Test.vue';

export const routes = [
    {
        path: '/',
        component: () => import('../pages/home/Home.vue'),
    },
    {
        path: '/dashboard',
        component: () => import('../pages/dashboard/Dashboard.vue'),
        meta: {
            requiresAuth: true,
            title: 'Dashboard',
        },
    },
    {
        name: 'login',
        path: '/login',
        component: () => import('../pages/Login.vue'),
        meta: {
            title: 'Login',
        },
    },
    {
        path: '/register',
        component: () => import('../pages/Register.vue'),
        meta: {
            title: 'Register',
        },
    },
    {
        path: '/overview',
        component: () => import('../pages/overview/Overview.vue'),
        meta: {
            requiresAuth: true,
            title: 'Overview',
        },
    },
    {
        path: '/notifications',
        component: () => import('../pages/notifications/Notifications.vue'),
        meta: {
            requiresAuth: true,
            title: 'Notifications',
        },
    },
    {
        path: '/settings',
        component: () => import('../pages/settings/Settings.vue'),
        meta: {
            requiresAuth: true,
            title: 'Settings',
        },
    },
    {
        name: 'profile',
        path: '/profile/:id',
        component: () => import('../pages/profile/Profile.vue'),
        meta: {
            title: 'Profile',
        },
    },
    {
        path: '/admindashboard',
        component: () => import('../pages/admin/AdminDashboard.vue'),
        meta: {
            requiresAuth: true, 
            requiresAdmin: true,
            title: 'Admin dashboard',
        },
    },
    {
        path: '/welcome',
        component: () => import('../pages/Welcome.vue'),
        meta: {
            requiresAuth: true,
            title: 'Welcome',
        },
    },
    {
        path: '/bugreport',
        component: () => import('../pages/BugReport.vue'),
        meta: {
            title: 'Report a bug',
        },
    },
    {
        path: '/messages',
        component: () => import('../pages/messages/Messages.vue'),
        meta: {
            requiresAuth: true,
            title: 'Messages',
        },
    },
    {
        path: '/social',
        component: () => import('../pages/social/Social.vue'),
        meta: {
            requiresAuth: true,
            title: 'Social',
        },
    },
    {
        path: '/group/:id',
        component: () => import('../pages/social/components/groups/GroupPage.vue'),
        meta: {
            requiresAuth: true,
            title: 'Group',
            breadcrumbs: true,
        },
    },
    {
        path: '/faq',
        component: () => import('../pages/Faq.vue'),
        meta: {
            title: 'Frequently asked questions',
        },
    },
    {
        path: '/feedback',
        component: () => import('../pages/Feedback.vue'),
        meta: {
            title: 'Feedback',
        },
    },
    {
        path: '/privacy',
        component: () => import('../pages/legal/PrivacyPolicy.vue'),
        meta: {
            title: 'Privacy Policy',
        },
    },
    {
        path: '/tos',
        component: () => import('../pages/legal/TOS.vue'),
        meta: {
            title: 'Terms of Service',
        },
    },
    {
        path: '/:pathMatch(.*)*',
        component: () => import('../pages/ErrorPage.vue'),
        meta: {
            title: 'Error',
        },
    },
    {
        name: 'Error',
        path: '/error',
        component: () => import('../pages/ErrorPage.vue'),
        meta: {
            title: 'Error',
        },
    },
    {
        name: 'ForgotPassword',
        path: '/forgot-password',
        component: () => import('../pages/ForgotPassword.vue'),
        meta: {
            title: 'Forgot password',
        },
    },
    {
        name: 'ResetPassword',
        path: '/reset-password',
        component: () => import('../pages/ResetPassword.vue'),
        meta: {
            title: 'Reset password',
        },
    },
    {
        name: 'Changelog',
        path: '/changelog',
        component: () => import('../pages/changelog/Changelog.vue'),
        meta: {
            title: 'Changelog',
        },
    },
    // {
    //     path: '/test',
    //     component: require('../pages/Test.vue').default,
    // },
];