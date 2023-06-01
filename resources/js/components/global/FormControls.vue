<template>
    <div class="d-flex">
        <button class="ml-auto mr-2 cancel-button" @click="$emit('cancel')">{{ cancelText ?? $t('cancel') }}</button>
        <SubmitButton @click="$emit('submit')">
            {{ submitText ?? $t('submit') }}
        </SubmitButton>
    </div>
</template>

<script setup lang="ts">
import SubmitButton from './small/SubmitButton.vue';

defineProps<{
    submitText?: string,
    cancelText?: string,
}>();
defineEmits(['submit', 'cancel']);
</script>