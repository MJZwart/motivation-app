<template>
    <div class="form-group">
        <label :for="name">{{label}}</label>
        <textarea
            :id="id" 
            :class="{invalid: isInvalid, disabled: disabled}"
            :type="type" 
            :name="name" 
            :value="modelValue"
            :rows="rows"
            :placeholder="placeholder" 
            :disabled="disabled"
            @input="updateModelValue($event)"
        />
        <div v-if="errorMsg">
            <div class="d-block invalid-feedback">{{ errorMsg }}</div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import {computed} from 'vue';
import {useMainStore} from '/js/store/store';
const mainStore = useMainStore();

const props = withDefaults(
    defineProps<{
        name: string,
        modelValue: string,
        id?: string,
        type?: string,
        placeholder?: string,
        label?: string,
        rows?: number,
        disabled?: boolean,
    }>(),
    {
        type: 'text',
        rows: 3,
        disabled: false,
    },
);

const emit = defineEmits(['update:modelValue']);
const errors = computed(() => mainStore.errors)

const isInvalid = computed(() => !!errors.value && !!errors.value[props.name]);

const errorMsg = computed(() => {
    if (!props.name || !errors.value) {
        return '';
    }
    return (errors.value[props.name] || [])[0] || '';
});

function updateModelValue(event: Event) {
    emit('update:modelValue', (event.target as HTMLInputElement).value)
}
</script>