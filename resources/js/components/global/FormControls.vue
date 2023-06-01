<template>
    <div class="d-flex">
        <button class="ml-auto mr-2 cancel-button" @click="$emit('cancel')">{{ cancelText ?? $t('cancel') }}</button>
        <SubmitButton :disabled="disabled" @click="submit">
            {{ submitText ?? $t('submit') }}
        </SubmitButton>
    </div>
</template>

<script setup lang="ts">
import SubmitButton from './small/SubmitButton.vue';

const props = defineProps<{
    submitText?: string,
    cancelText?: string,
    disabled?: boolean,
}>();
const emit = defineEmits(['submit', 'cancel']);
function submit() {
    if (props.disabled) return;
    emit('submit');
}
</script>