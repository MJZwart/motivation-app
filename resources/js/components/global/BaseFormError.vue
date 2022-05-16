<template v-if="errorMsg">
    <div class="d-block invalid-feedback">{{ errorMsg }}</div>
</template>

<script setup>
import {computed} from 'vue';
import {useMainStore} from '/js/store/store';
const mainStore = useMainStore();

const props = defineProps({
    name: {type: String, required: true},
});

const responseMessage = computed(() => mainStore.errors);
        
const errorMsg = computed(() => {
    const errors = responseMessage.value;
    if (!props.name || !errors) {
        return '';
    }
    return (errors[props.name] || [])[0] || '';
});
</script>
