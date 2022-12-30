<template>
    <div class="checkbox-wrapper">
        <span class="checkbox" @click="emit('update:modelValue', !modelValue)">
            <Icon :icon="getIcon()" 
                  :class="{green: modelValue, red: !modelValue}"
            />
        </span>
        <label @click="emit('update:modelValue', !modelValue)">{{ label }}</label>
    </div>
</template>

<script setup lang="ts">
import {Icon} from '@iconify/vue';

const props = defineProps<{modelValue?: boolean, label?: string}>();
const emit = defineEmits(['update:modelValue']);

function getIcon() {
    return props.modelValue ? 'material-symbols:check-small-rounded' : 'charm:cross';
}
</script>

<style lang="scss" scoped>
.checkbox-wrapper {
    display: inline-flex;
    .checkbox {
        cursor: pointer;
        width: 18px;
        height: 18px;
        display: inline-flex;
        background-color: var(--input-background);
        border-radius: 0.25rem;
        margin: 0.25rem;
        border: 1px solid var(--border-color);
        .iconify {
            display: flex;
            align-self: center;
            width: 21px;
            height: 21px;
            overflow:visible;
            margin-left: -2px;
            margin-top: -1px;
        }
    }
}
</style>