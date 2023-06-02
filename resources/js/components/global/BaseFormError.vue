<template v-if="errorMsg">
    <div class="d-block invalid-feedback">{{ errorMsg }}</div>
</template>

<script setup>
import {computed} from 'vue';
import {errors} from '/js/services/errorService';

const props = defineProps({
    name: {type: String, required: true},
});
        
const errorMsg = computed(() => {
    if (!props.name || !errors.value) {
        return '';
    }
    return (errors.value[props.name] || [])[0] || '';
});
</script>
