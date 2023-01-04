<template>
    <div class="form-group d-flex">
        <SimpleCheckbox :id="id" :modelValue="modelValue" @update:model-value="emit('update:modelValue', $event)" />
        <label class="checkbox-label" @click="emit('update:modelValue', !modelValue)">{{ label }}</label>
        <BaseFormError :name="name" />
    </div>
</template>

<script setup lang="ts">
defineProps<{modelValue?: boolean, label?: string, name?: string, id?: string}>();
const emit = defineEmits(['update:modelValue']);
</script>

<style lang="scss" scoped>
.checkbox-label {
    margin-top: 0.2rem;
    cursor: pointer;
}
</style>