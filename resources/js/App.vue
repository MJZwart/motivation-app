<template>
    <div id="app-container">
        <ModalContainer />
        <Navigation />
        <Breadcrumbs />
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
import Breadcrumbs from '/js/components/Breadcrumbs.vue';
import {fetchStoredToasts} from '/js/services/toastService';
import {onMounted} from 'vue';
import ModalContainer from '/js/components/modal/ModalContainer.vue';

onMounted(() => {
    fetchStoredToasts();
});
</script>