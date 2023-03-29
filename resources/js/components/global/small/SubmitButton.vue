<template>
    <button type="submit" :class="{disabled: disabled}" @click="submit()">
        <slot>{{$t('submit')}}</slot>
        <Loading v-if="waitingOnResponse" button />
    </button>
</template>

<script setup lang="ts">
import Loading from '../Loading.vue';
import {waitingOnResponse} from '/js/services/loadingService';
const props = defineProps<{disabled?: boolean}>();
function submit() {
    if (props.disabled) return;
    waitingOnResponse.value = true;
}
</script>