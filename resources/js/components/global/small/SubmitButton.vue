<template>
    <button type="submit" @click="globalLoading = true">
        <slot>{{$t('submit')}}</slot>
        <Loading v-if="globalLoading" button />
    </button>
</template>

<script setup lang="ts">
import Loading from '../Loading.vue';
import {globalLoading} from '/js/services/loadingService';
</script>