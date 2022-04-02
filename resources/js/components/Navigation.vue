<template>
    <div>
        <nav v-if="!authenticated" class="navbar box-shadow sticky-top navbar-dark navbar-expand">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <router-link to="/" exact-path class="nav-link">{{ $t('home') }}</router-link>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <router-link to="/login" class="nav-link">{{ $t('login') }}</router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/register" class="nav-link">{{ $t('register') }}</router-link>
                </li>
            </ul>
        </nav>

        <nav v-if="authenticated" class="navbar box-shadow sticky-top navbar-dark navbar-expand-md">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <router-link to="/" class="nav-link">{{ $t('home') }}</router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/overview" class="nav-link">{{ $t('overview') }}</router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/bugreport" class="nav-link">{{ $t('report-bug') }}</router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/social" class="nav-link">{{$t('social')}}</router-link>
                </li>
            </ul>

            <!-- <button type="button" aria-label="Toggle navigation" class="navbar-toggler collapsed" 
                    aria-expanded="false" aria-controls="nav-collapse" style="overflow-nachor: none;" @click="isOpen = !isOpen">
                <span class="navbar-toggler-icon" />
            </button>
            <div id="nav-collapse" class="navbar-collapse collapse" :style="{'display:none': !isOpen}"> -->

            <ul v-if="admin" class="navbar-nav">
                <li class="nav-item">
                    <router-link to="/admindashboard" class="nav-link">{{ $t('admin') }}</router-link>
                </li>
            </ul>

            <div class="navbar-nav ml-auto">
                <router-link to="/messages" class="nav-link">
                    <!-- <div class="toggled-nav">
                        Messages
                    </div> -->
                    <!-- <div class="full-nav"> -->
                    <b-iconstack class="icon-nav-stack">
                        <b-icon-envelope class="icon-nav" /> 
                        <b-icon-dot v-if="hasMessages" font-scale="3" 
                                    class="icon-dot-red" shift-h="-2" shift-v="7" />
                    </b-iconstack>
                    <!-- </div> -->
                </router-link>
                <router-link to="/notifications" class="nav-link">
                    <!-- <div class="toggled-nav">
                        Notifications
                    </div> -->
                    <!-- <div class="full-nav"> -->
                    <b-iconstack class="icon-nav-stack">
                        <b-icon-bell class="icon-nav" />
                        <b-icon-dot v-if="hasNotifications" font-scale="3" 
                                    class="icon-dot-red" shift-h="-2" shift-v="7" />
                    </b-iconstack>
                    <!-- </div> -->
                </router-link>
                <!-- <div class="toggled-nav">
                    <b-nav-item :to="{ name: 'profile', params: { id: user.id}}">
                        {{ $t('profile') }}
                    </b-nav-item>
                    <b-nav-item to="/settings">{{ $t('settings') }}</b-nav-item>
                    <b-nav-item @click="logout">{{ $t('logout') }}</b-nav-item>
                </div> -->
                <!-- TODO When closing the navbar, you catch glimpse of the original design -->
                <!-- <div class="full-nav"> -->
                <!-- <button @click="dropdownMenuOpen = !dropdownMenuOpen">{{user.username}}</button>
                <div v-if="dropdownMenuOpen" class="dropdown-menu" :class="{'show': dropdownMenuOpen}"> -->
                <li class="nav-item">
                    <router-link :to="{ name: 'profile', params: { id: user.id}}" class="nav-link">
                        {{ $t('profile') }}
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/settings" class="nav-link">{{ $t('settings') }}</router-link>
                </li>
                <a class="nav-link" @click="logout">{{ $t('logout') }}</a>
                <!-- </div> -->
                <!-- <b-dropdown id="user-dropdown" :text="user.username" variant="primary" class="nav-text" offset="-5">
                    <b-dropdown-item :to="{ name: 'profile', params: { id: user.id}}">
                        {{ $t('profile') }}
                    </b-dropdown-item>
                    <b-dropdown-item to="/settings">{{ $t('settings') }}</b-dropdown-item>
                    <b-dropdown-item @click="logout">{{ $t('logout') }}</b-dropdown-item>
                </b-dropdown> -->
                <!-- </div> -->
            </div>
            <!-- </div> -->
        </nav>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
export default {
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
    color: rgba(255, 255, 255, 0.5);
}
.router-link-exact-active .icon-nav{
    color:rgba(255, 255, 255, 0.75);
}
.icon-nav-stack{
    margin-top:5px;
    margin-right:25px;
}
.icon-dot-red{
    color:$warning;
}
.toggled-nav{
    display: none;
}
.full-nav{
    display: block;   
}
.nav-text{
    .btn-primary{
        color: rgba(255, 255, 255, 0.5) !important;
    }
}
.dropdown-menu {

}
@media (max-width:767px){   
    .toggled-nav{
        display: block;
    }
    .full-nav{
        display: none;
    }
    .navbar{
        .navbar-nav{
            flex-direction: row;
                .nav-item{
                    margin-right: 0.8rem;
                }
            }
        .toggled {
            flex-direction: column;
        }
    }
}

@media (max-width: 425px){
    .nav-item{
        margin-right: 0.5rem;
    }
    .navbar{
        .toggled {
            flex-direction: column;
        }
    }
}
</style>
