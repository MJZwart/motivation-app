<template>
    <div>
        <ul class="navbar">
            <li v-if="authenticated">
                <router-link to="/" exact>Home</router-link>
            </li>
            <li v-if="authenticated">
                <router-link to="/overview">Overview</router-link>
            </li>
            <li v-if="authenticated">
                <router-link to="/friends">Friends</router-link>
            </li>

            <li v-if="admin">
                <router-link to="/achievements">Achievements</router-link>
            </li>
            <li v-if="admin">
                <router-link to="/admin">Admin</router-link>
            </li>

            <div class="flex-end">
                <li v-if="authenticated">
                    <search-bar>Friends</search-bar>
                </li>
                <router-link to="/notifications">
                    <b-iconstack class="icon-nav-stack">
                        <b-icon-bell class="icon-nav" v-if="authenticated"></b-icon-bell>
                        <b-icon-dot v-if="hasNotifications" font-scale="4" class="icon-dot-red"></b-icon-dot>
                    </b-iconstack>
                </router-link>
                <b-dropdown v-if="authenticated" id="user-dropdown" :text=user.username class="m-2">
                    <b-dropdown-item :to="{ name: 'profile', params: { id: user.id}}">Profile</b-dropdown-item>
                    <b-dropdown-item to="/settings">Settings</b-dropdown-item>
                    <b-dropdown-item @click="logout">Logout</b-dropdown-item>
                </b-dropdown>
                <li v-if="!authenticated">
                    <router-link to="/login">Login</router-link>
                </li>
                <li v-if="!authenticated">
                    <router-link to="/register">Register</router-link>
                </li>
            </div>
        </ul>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import SearchBar from '../components/small/SearchBar.vue';
export default {
    components: {
        SearchBar,
    },
    computed: {
        ...mapGetters({
            authenticated: 'user/authenticated',
            user: 'user/getUser',
            hasNotifications: 'notification/getHasNotifications',
            admin: 'admin/isAdmin',
        }),
    },

    methods: {
        logout(){
            this.$store.dispatch('user/logout');
        },
    },
}
</script>


<style>
    .navbar{
        display: flex;
    }
    .navbar li{
        padding: 10px;
        list-style: none;
        font-size: 1.5rem;
        font-weight: 500;
    }
    .navbar li a{
        color:black;
        text-decoration: none;
    }
    .navbar li a.router-link-active{
        color: teal;
        text-decoration: none;
    }
    .navbar li a:hover{
        color: rgba(0, 128, 128, 0.658);
    }
    .flex-end{
        display: flex;
        margin-left: auto !important;
    }
    .icon-nav{
        font-size:35px;
        margin:5px;
        color:teal;
    }
    .icon-nav-stack{
        margin-top:5px;
        margin-right:25px;
    }
    .icon-dot-red{
        color:darkred;
    }
    .dropdown-menu{
        display:none;
        float:left;
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        min-width: 8rem;
        padding: .5rem 0;
        margin: .125rem 0 0;
        color: #212529;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid rgba(0,0,0,.15);
        border-radius: .25rem;
    }
    .dropdown-menu.show{
        display:block;
    }
    .dropdown-menu li{
        font-size:1rem;
    }
</style>