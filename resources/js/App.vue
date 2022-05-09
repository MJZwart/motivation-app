<template>
    <div id="app-container">
        <Navigation />
        <div class="content">
            <router-view />
        </div>
        <FooterComp />
        <Toasts />
    </div>
</template>

<script setup>
import Navigation from '/js/components/Navigation.vue';
import FooterComp from '/js/components/FooterComp.vue';
import Toasts from '/js/components/global/Toasts.vue';
</script>