<template>
    <button type="submit" @click="waitingOnResponse = true">
        <slot>{{$t('submit')}}</slot>
        <Loading v-if="waitingOnResponse" button />
    </button>
</template>

<script setup lang="ts">
import Loading from '../Loading.vue';
import {waitingOnResponse} from '/js/services/loadingService';
</script>