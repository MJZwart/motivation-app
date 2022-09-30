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

<script setup lang="ts">
import Navigation from '/js/components/Navigation.vue';
import FooterComp from '/js/components/FooterComp.vue';
import Toasts from '/js/components/global/Toasts.vue';
import {fetchStoredToasts} from '/js/services/toastService';
import {onMounted} from 'vue';

onMounted(() => {
    fetchStoredToasts();
});
</script>