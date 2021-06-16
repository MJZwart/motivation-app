import Vue from "vue";
import VueRouter from "vue-router";

Vue.use(VueRouter);

export default new VueRouter({
    routes: [
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
            path: "/profile/:id",
            component: require('../pages/Profile.vue').default,
        }
    ]
});
