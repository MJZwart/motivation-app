/* eslint-disable max-lines-per-function */
import {RouteLocationNormalized, createRouter, createWebHistory} from 'vue-router';
import {routes} from './routes';
import {errorToast} from '/js/services/toastService';
import {breadcrumbsVisible} from '/js/services/breadcrumbService';
import {clearErrors} from '../services/errorService';
import { authenticated, isAdmin, user } from '../services/userService';

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// eslint-disable-next-line complexity
router.beforeEach((to, from, next) => {
    clearErrors();

    window.scrollTo(0,0);

    handleDocumentTitle(to);
    breadcrumbsVisible.value = false;

    if (to.path == '/' && authenticated.value) {
        return next({path: '/dashboard'});
    }

    if (to.path != '/welcome' && user.value && user.value.first) {
        return next({path: '/welcome'});
    } else if (to.path === '/welcome' && user.value && !user.value.first) {
        return next({path: '/dashboard'});
    }

    if (to.meta) {
        if (to.meta.requiresAuth && !authenticated.value) {
            return next({path: '/login'});
        }

        if (to.meta.requiresAdmin && !isAdmin.value) {
            errorToast('You are not authorized to view this page');
            return next({path: '/dashboard'});
        }
        if (to.meta.breadcrumbs)
            breadcrumbsVisible.value = true; 
        if (to.meta.blockedForGuest && user.value && user.value.guest) {
            return next({path: '/dashboard'});
        }
    }

    next();
});

function handleDocumentTitle(to: RouteLocationNormalized) {
    if (to.meta && to.meta.title) {
        document.title = 'Questifyer - ' + to.meta.title;
    } else {
        document.title = 'Questifyer';
    }
}

export default router;
