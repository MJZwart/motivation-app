<template>
    <div>
        <ul class="navbar">
            <li>
                <router-link to="/" exact>Home</router-link>
            </li>
            <li>
                <router-link to="/overview">Overview</router-link>
            </li>
            <div class="flex-end">
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
export default {
    computed: {
        ...mapGetters({
            authenticated: 'user/authenticated',
            user: 'user/getUser',
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