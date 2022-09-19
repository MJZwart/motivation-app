// import Vue from 'vue';
import {createRouter, createWebHistory} from 'vue-router';
import {useMainStore} from '../store/store';
import {useMessageStore} from '../store/messageStore';
import {useUserStore} from '../store/userStore';
// import Test from '../pages/Test.vue';
import {routes} from './routes';
import {errorToast} from '/js/services/toastService';

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

    window.scrollTo(0,0);
    if (to.meta && to.meta.title) {
        //@ts-ignore
        document.title = 'Questifyer - ' + to.meta.title.toString();
    } else {
        document.title = 'Questifyer';
    }
    
    if (to.path == '/' && userStore.isAuthenticated) {
        return next({path: '/dashboard'});
    }

    if (to.path != '/welcome' && userStore.user.first) {
        return next({path: '/welcome'});
    }

    if (to.meta && to.meta.requiresAuth && !userStore.isAuthenticated) {
        return next({path: '/login'});
    }

    if (to.meta && to.meta.requiresAdmin && !userStore.isAdmin) {
        errorToast('You are not authorized to view this page');
        return next({path: '/dashboard'});
    }
    
    if (userStore.isAuthenticated) {
        messageStore.hasUnread();
    }
    next();
});

export default router;
