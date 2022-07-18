<template>
    <div>
        <div v-for="(error, index) in responseMessage" :key="index" class="d-block invalid-feedback">
            {{ error[0] }}
        </div>
    </div>
</template>

<script setup>
import {computed} from 'vue';
import {useMainStore} from '/js/store/store';
const mainStore = useMainStore();
// eslint-disable-next-line no-unused-vars
const responseMessage = computed(() => mainStore.errors);
</script>