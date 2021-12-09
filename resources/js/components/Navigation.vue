<template>
    <div>
        <b-navbar type="dark" sticky class="box-shadow">
            <b-navbar-nav v-if="authenticated">
                <b-nav-item to="/" exact>{{ $t('home') }}</b-nav-item>
                <b-nav-item to="/overview">{{ $t('overview') }}</b-nav-item>
                <b-nav-item to="/friends">{{ $t('friends') }}</b-nav-item>
            </b-navbar-nav>

            <b-navbar-nav v-if="admin">
                <b-nav-item to="/achievements">{{ $t('achievements') }}</b-nav-item>
                <b-nav-item to="/admin">{{ $t('admin') }}</b-nav-item>
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
                        <b-dropdown-item :to="{ name: 'profile', params: { id: user.id}}">{{ $t('profile') }}</b-dropdown-item>
                        <b-dropdown-item to="/settings">{{ $t('settings') }}</b-dropdown-item>
                        <b-dropdown-item @click="logout">{{ $t('logout') }}</b-dropdown-item>
                    </b-dropdown>
                </b-nav-item>
                <b-nav-item v-if="!authenticated" to="/login">{{ $t('login') }}</b-nav-item>
                <b-nav-item v-if="!authenticated" to="/register">{{ $t('register') }}</b-nav-item>
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
}
</script>


<style lang="scss">
@import '../../assets/scss/variables';
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