<template>
    <div>
        <b-navbar type="dark" sticky="true" class="box-shadow">
            <b-navbar-nav v-if="authenticated">
                <b-nav-item to="/" exact>Home</b-nav-item>
                <b-nav-item to="/overview">Overview</b-nav-item>
                <b-nav-item to="/friends">Friends</b-nav-item>
            </b-navbar-nav>

            <b-navbar-nav v-if="admin">
                <b-nav-item to="/achievements">Achievements</b-nav-item>
                <b-nav-item to="/admin">Admin</b-nav-item>
            </b-navbar-nav>

            <b-navbar-nav class="ml-auto">
                <!-- <b-nav-item v-if="authenticated">
                    <search-bar></search-bar>
                </b-nav-item> -->
                <b-nav-item v-if="authenticated" to="/notifications">
                    <b-iconstack class="icon-nav-stack">
                        <b-icon-bell class="icon-nav"></b-icon-bell>
                        <b-icon-dot v-if="hasNotifications" font-scale="3" class="icon-dot-red" shift-h="-2" shift-v="7"></b-icon-dot>
                    </b-iconstack>
                </b-nav-item>
                <b-nav-item v-if="authenticated">
                    <b-dropdown id="user-dropdown" :text=user.username variant="primary">
                        <b-dropdown-item :to="{ name: 'profile', params: { id: user.id}}">Profile</b-dropdown-item>
                        <b-dropdown-item to="/settings">Settings</b-dropdown-item>
                        <b-dropdown-item @click="logout">Logout</b-dropdown-item>
                    </b-dropdown>
                </b-nav-item>
                <b-nav-item v-if="!authenticated" to="/login">Login</b-nav-item>
                <b-nav-item v-if="!authenticated" to="/register">Register</b-nav-item>
            </b-navbar-nav>

        </b-navbar>
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
    //Test for pushing
}
</script>


<style lang="scss">
@import '../../assets/scss/variables';

//TODO

//Working on getting SASS to work, and making the top navbar look like 
// https://bootstrap-vue.org/docs/components/modal
    .navbar{
        background-color: $primary;

        li {
            a.router-link-active{
                font-weight:600;
            }
        }
    }
    .box-shadow {
        box-shadow: 0 0.25rem 0.25rem $box-shade, inset 0 -1px 5px $box-shade;
    }
    .icon-nav{
        color:$secondary;
    }
    .icon-nav-stack{
        margin-top:5px;
        margin-right:25px;
    }
    .icon-dot-red{
        color:$warning;
    }
</style>