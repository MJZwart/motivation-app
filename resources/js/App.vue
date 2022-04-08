<template>
    <div>
        <Navigation />
        <div class="content">
            <!-- <router-view v-slot="{Component}">
                <component :is="Component" />
            </router-view> -->
            <router-view />
        </div>
        <FooterComp />
        <Toasts />
    </div>
</template>

<script>
import Navigation from './components/Navigation.vue';
import FooterComp from './components/FooterComp.vue';
import Toasts from './components/Toasts.vue';

export default {
    components: {Navigation, FooterComp, Toasts},
    
};
</script>