<template>
    <div :class="className">
        <slot name="header">
            <div v-if="title" class="d-flex">
                <h3>{{ $t(title) }}</h3>
                <span class="ml-auto">
                    <Tutorial v-if="tutorial" :tutorial="title" />
                </span>
            </div>
        </slot>
        <slot />
    </div>
</template>

<script setup lang="ts">
import {computed} from 'vue';

const props = defineProps({
    title: {
        type: String,
        required: false,
    },
    tutorial: {
        type: Boolean,
        required: false,
        default: false,
    },
    customClass: {
        type: String,
        required: false,
    },
});
const className = computed(() => {
    let className = 'content-block ';
    if (props.customClass) {
        className += ' ' + props.customClass;
    } else className += ' p-3';
    return className;
});
</script>