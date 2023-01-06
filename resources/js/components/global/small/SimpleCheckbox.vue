<template>
    <div class="checkbox-wrapper">
        <span 
            :id="id" 
            class="checkbox" 
            :class="{green: modelValue, red: !modelValue}" 
            @click="emit('update:modelValue', !modelValue)">
            <Icon :icon="getIcon()" />
        </span>
    </div>
</template>

<script setup lang="ts">
import {Icon} from '@iconify/vue';

const props = defineProps<{modelValue?: boolean, id?: string}>();
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
        border-radius: 0.25rem;
        margin: 0.25rem;
        border: 1px solid var(--border-color);
        .iconify {
            display: flex;
            align-self: center;
            width: 20px;
            height: 20px;
            overflow:visible;
            margin-left: -2px;
            margin-top: 0;
        }
    }
    .checkbox.green {
        border: 1px solid var(--green);
        .iconify {
            color: var(--green);
        }
    }
    .checkbox.red {
        border: 1px solid var(--red);
        .iconify {
            color: var(--red);
        }
    }
}
</style>