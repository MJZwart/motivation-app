<template>
    <div>
        <navigation />
        <div class="content">
            <router-view />
        </div>
        <footer-comp />
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