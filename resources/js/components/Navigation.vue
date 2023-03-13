<template>
    <div class="sticky-top">
        <nav v-if="!authenticated" class="navbar box-shadow">
            <router-link to="/" exact-path>{{ $t('home') }}</router-link>
            <div class="ml-auto">
                <router-link to="/login">{{ $t('login') }}</router-link>
                <router-link to="/register">{{ $t('register') }}</router-link>
            </div>
        </nav>

        <div v-if="authenticated">
            <nav class="navbar box-shadow">
                <router-link to="/">{{ $t('dashboard') }}</router-link>
                <router-link to="/overview">{{ $t('overview') }}</router-link>
                <router-link to="/social">{{$t('social')}}</router-link>

                <div v-if="admin && windowWidth > 350">
                    <router-link to="/admindashboard">{{ $t('admin') }}</router-link>
                </div>

                <div class="ml-auto">
                    <router-link v-if="windowWidth > 450" to="/messages">
                        <span class="icon-stack">
                            <Icon :icon="MAIL" class="icon-nav mail-icon"/>
                            <Icon v-if="hasMessages" :icon="DOT" class="red icon-dot" />
                        </span>
                    </router-link>
                    <router-link v-if="windowWidth > 450" to="/notifications">
                        <span class="icon-stack">
                            <Icon :icon="NOTIFICATION" class="icon-nav notification-icon" />
                            <Icon v-if="hasNotifications" :icon="DOT" class="red icon-dot" />
                        </span>
                    </router-link>
                    <Dropdown color="white">
                        <section v-if="admin && windowWidth < 350" class="option">
                            <router-link to="/admindashboard">{{ $t('admin') }}</router-link>
                        </section>
                        <section v-if="windowWidth < 450" class="option">
                            <router-link to="/messages">{{ $t('messages') }}</router-link>
                        </section>
                        <section v-if="windowWidth < 450" class="option">
                            <router-link to="/notifications">{{ $t('notifications') }}</router-link>
                        </section>
                        <section class="option">
                            <router-link :to="{ name: 'profile', params: { id: user?.id}}">
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
    </div>
</template>


<script setup>
import Dropdown from '/js/components/global/Dropdown.vue';
import {useUserStore} from '/js/store/userStore';
import {useMessageStore} from '/js/store/messageStore';
import {computed, onMounted, ref} from 'vue';
import {MAIL, DOT, NOTIFICATION} from '../constants/iconConstants';

onMounted(() => {
    window.addEventListener('resize', handleResize);
    if (!user.value) return;
    window.Echo.private(`unread.${user.value.id}`)
        .listen('NewNotification', () => {
            messageStore.hasNotifications = true;
        })
        .listen('NewMessage', () => {
            messageStore.hasMessages = true;
        });
});

const userStore = useUserStore();
const messageStore = useMessageStore();

const authenticated = computed(() => userStore.authenticated);
const user = computed(() => userStore.user);
const hasNotifications = computed(() => messageStore.hasNotifications);
const hasMessages = computed(() => messageStore.hasMessages);
const admin = computed(() => userStore.isAdmin);

const windowWidth = ref(window.innerWidth);

function handleResize() {
    windowWidth.value = window.innerWidth;
}

function logout() {
    userStore.logout();
}
</script>


<style lang="scss" scoped>
.navbar{
    background-color: var(--primary);
    display: flex;
    align-items: center;
    padding: 0.5rem 1rem;
    height: 50px;
    a{
        margin-right: 1rem;
        color: var(--nav-text);
        text-decoration: none;
    }
    a.router-link-active{
        font-family: var(--border-color);
    }
    section, a {
        display: inline-block;
    }
    section a {
        color: var(--primary);
    }
    .nav-icon-layers {
        vertical-align: 0.5rem;
        }
}
.box-shadow {
    box-shadow: 0 0.25rem 0.25rem var(--box-shade), inset 0 -1px 5px var(--box-shade);
}
.icon-nav{
    color: var(--nav-text);
}
.iconify.icon-nav {
    font-size: 2.2rem;
}
.icon-stack {
    display: inline-flex;
    position: relative;
    margin-top: 0.25rem;
    .icon-dot {
        position: absolute;
        left: 14px;
        top: -8px;
        font-size: 2rem;
    }
}
</style>
