<template>
    <div class="form-group">
        <label :for="name">{{label}}</label>
        <textarea
            :id="id" 
            :class="{invalid: isInvalid}"
            :type="type" 
            :name="name" 
            :value="modelValue"
            :rows="rows"
            :placeholder="placeholder" 
            @input="$emit('update:modelValue', $event.target.value)"
        />
        <div v-if="errorMsg">
            <div class="d-block invalid-feedback">{{ errorMsg }}</div>
        </div>
    </div>
</template>

<script setup>
import {computed} from 'vue';
import {useMainStore} from '/js/store/store';
const mainStore = useMainStore();

const props = defineProps({
    id: {
        type: String,
        required: false,
    },
    type: {
        type: String,
        required: false,
        default: 'text',
    },
    name: {
        type: String,
        required: true,
    },
    modelValue: {
        required: true,
    },
    placeholder: {
        type: String,
        required: false,
    },
    label: {
        type: String,
        required: false,
    },
    rows: {
        type: Number,
        required: false,
        default: 3,
    },
});
defineEmits(['update:modelValue']);
const errors = computed(() => mainStore.errors)

const isInvalid = computed(() => !!errors.value && !!errors.value[props.name]);

const errorMsg = computed(() => {
    if (!props.name || !errors.value) {
        return '';
    }
    return (errors.value[props.name] || [])[0] || '';
});
</script>