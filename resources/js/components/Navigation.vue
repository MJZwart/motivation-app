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
                            class="icon-nav icon-2xl" />
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
                            class="icon-nav icon-2xl" />
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


<script setup>
import Dropdown from './bootstrap/Dropdown.vue';
import {useUserStore} from '@/store/userStore';
import {useMessageStore} from '@/store/messageStore';
import {computed} from 'vue';

const userStore = useUserStore();
const messageStore = useMessageStore();

const authenticated = computed(() => userStore.authenticated);
const user = computed(() => userStore.user);
const hasNotifications = computed(() => messageStore.hasNotifications);
const hasMessages = computed(() => messageStore.hasMessage);
const admin = computed(() => userStore.isAdmin);

function logout() {
    userStore.logout();
}
</script>


<style lang="scss">
@import '../../assets/scss/variables';
.navbar{
    background-color: $primary;
    display: flex;
    align-items: center;
    padding: 0.5rem 1rem;
    height: 50px;
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
</style>
