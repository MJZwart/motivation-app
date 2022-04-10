<template>
    <div class="sticky-top">
        <nav v-if="!authenticated" class="navbar box-shadow">
            <router-link to="/" exact-path>{{ $t('home') }}</router-link>
            <div class="ml-auto">
                <router-link to="/login">{{ $t('login') }}</router-link>
                <router-link to="/register">{{ $t('register') }}</router-link>
            </div>
        </nav>

        <nav v-if="authenticated" class="navbar box-shadow">
            <router-link to="/">{{ $t('home') }}</router-link>
            <router-link to="/overview">{{ $t('overview') }}</router-link>
            <router-link to="/bugreport">{{ $t('report-bug') }}</router-link>
            <router-link to="/social">{{$t('social')}}</router-link>

            <div v-if="admin">
                <router-link to="/admindashboard">{{ $t('admin') }}</router-link>
            </div>

            <div class="ml-auto">
                <router-link to="/messages">
                    <FaIconLayers class="mr-3 nav-icon-layers">
                        <FaIcon 
                            icon="envelope" 
                            class="icon-nav" 
                            size="2xl" />
                        <FaIcon 
                            v-if="hasMessages" 
                            icon="circle" 
                            class="red" 
                            style="left: 22px; top: -20px;" />
                    </FaIconLayers>
                </router-link>
                <router-link to="/notifications">
                    <FaIconLayers class="mr-3 nav-icon-layers">
                        <FaIcon 
                            :icon="['far', 'bell']" 
                            class="icon-nav" 
                            size="2xl" />
                        <FaIcon 
                            v-if="hasNotifications" 
                            icon="circle" 
                            class="red" 
                            style="left: 17px; top: -20px;" />
                    </FaIconLayers>
                </router-link>
                <Dropdown color="white">
                    <section class="option">
                        <router-link :to="{ name: 'profile', params: { id: user.id}}">
                            {{ $t('profile') }}
                        </router-link>
                    </section>
                    <section class="option">
                        <router-link to="/settings">{{ $t('settings') }}</router-link>
                    </section>
                    <section class="option">
                        <a @click="logout">{{ $t('logout') }}</a>
                    </section>
                </Dropdown>
            </div>
        </nav>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import Dropdown from './bootstrap/Dropdown.vue';
export default {
    components: {
        Dropdown,
    },
    data() {
        return {
            // isOpen: false,
            // dropdownMenuOpen: false,
        }
    },
    computed: {
        ...mapGetters({
            authenticated: 'user/authenticated',
            user: 'user/getUser',
            hasNotifications: 'notification/getHasNotifications',
            hasMessages: 'message/getHasMessages',
            admin: 'admin/isAdmin',
        }),
    },
    methods: {
        logout() {
            this.$store.dispatch('user/logout');
        },
    },
}
</script>


<style lang="scss">
@import '../../assets/scss/variables';
.navbar{
    background-color: $primary;
    display: flex;
    align-items: center;
    padding: 0.5rem 1rem;
        a{
            margin-right: 1rem;
            color: $nav-text;
            text-decoration: none;
        }
        a.router-link-active{
            font-weight:600;
        }
        section, a {
            display: inline-block;
        }
        section a {
            color: $primary;
        }
        .nav-icon-layers {
            vertical-align: 0.5rem;
        }
}
.box-shadow {
    box-shadow: 0 0.25rem 0.25rem $box-shade, inset 0 -1px 5px $box-shade;
}
.icon-nav{
    color: $nav-text;
}
// .router-link-exact-active .icon-nav{
//     color:rgba(255, 255, 255, 0.75);
// }
// .icon-nav-stack{
//     margin-top:5px;
//     margin-right:25px;
// }
// .icon-dot-red{
//     color:$warning;
// }
// .toggled-nav{
//     display: none;
// }
// .full-nav{
//     display: block;   
// }
// .nav-text{
//     .btn-primary{
//         color: rgba(255, 255, 255, 0.5) !important;
//     }
// }
// .dropdown-menu {

// }
// @media (max-width:767px){   
//     .toggled-nav{
//         display: block;
//     }
//     .full-nav{
//         display: none;
//     }
//     .navbar{
//         .navbar-nav{
//             flex-direction: row;
//                 .nav-item{
//                     margin-right: 0.8rem;
//                 }
//             }
//         .toggled {
//             flex-direction: column;
//         }
//     }
// }

// @media (max-width: 425px){
//     .nav-item{
//         margin-right: 0.5rem;
//     }
//     .navbar{
//         .toggled {
//             flex-direction: column;
//         }
//     }
// }
</style>
