<template>
    <div>
        <Navigation />
        <div class="content">
            <router-view />
        </div>
        <FooterComp />
        <Toasts />
    </div>
</template>

<script setup>
import Navigation from './components/Navigation.vue';
import FooterComp from './components/FooterComp.vue';
import Toasts from './components/Toasts.vue';
</script>