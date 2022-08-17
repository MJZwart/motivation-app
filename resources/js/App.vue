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
import {useMainStore} from './store/store';
import {onMounted} from 'vue';
import {Toast} from 'resources/types/toast';

onMounted(() => {
    const storedToast = localStorage.getItem('queuedError');
    if (storedToast) {
        const mainStore = useMainStore();
        mainStore.addToast(reconstructToast(storedToast));
        localStorage.removeItem('queuedError');
    }
});

function reconstructToast(toastString: string) {
    const toastObject = JSON.parse(toastString);
    let toast: Toast;
    if (toastObject.type == 'error')
        toast = {'error': toastObject.message};
    else
        toast = {'info': toastObject.message};
    return toast;
}
</script>