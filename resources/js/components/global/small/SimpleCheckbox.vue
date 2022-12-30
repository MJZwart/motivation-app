<template>
    <span class="checkbox" @click="emit('update:modelValue', !modelValue)">
        <Icon :icon="getIcon()" 
              :class="{green: modelValue, red: !modelValue}"
        />
    </span>
</template>

<script setup lang="ts">
import {Icon} from '@iconify/vue';

const props = defineProps<{modelValue: boolean}>();
const emit = defineEmits(['update:modelValue']);

function getIcon() {
    return props.modelValue ? 'material-symbols:check-small-rounded' : 'charm:cross';
}
</script>

<style lang="scss" scoped>
.checkbox {
    cursor: pointer;
    width: 18px;
    height: 18px;
    display: flex;
    background-color: var(--input-background);
    border-radius: 0.25rem;
    .iconify {
        display: flex;
        align-self: center;
        width: 21px;
        height: 21px;
        overflow:visible;
        margin-left: -1px;
        margin-top: -1px;
    }
}
</style>