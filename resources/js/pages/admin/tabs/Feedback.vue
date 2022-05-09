<template>
    <div>
        <h3>{{ $t('feedback') }}</h3>
        <Table
            :items="feedback"
            :fields="feedbackFields"
            :options="['table-striped', 'page-wide']"
        />
    </div>
</template>

<script setup>
import {ref, onMounted} from 'vue';
import {FEEDBACK_FIELDS} from '/js/constants/feedbackConstants.js';
import {useAdminStore} from '/js/store/adminStore';
const adminStore = useAdminStore();

onMounted(() => {
    feedback.value = adminStore.getFeedback();
})

const feedback = ref([]);
const feedbackFields = ref(FEEDBACK_FIELDS);
</script>