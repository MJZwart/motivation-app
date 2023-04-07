<template>
    <div>
        <p><b>{{ $t('title') }}</b>: {{ bugReport.title }}</p>
        <p><b>{{ $t('page') }}</b>: {{ bugReport.page }}</p>
        <p><b>{{ $t('severity') }}</b>: {{ bugReport.severity }}</p>
        <p><b>{{ $t('comment') }}</b>: {{ bugReport.comment }}</p>
    </div>
    <form @submit.prevent="emit('submit', adminComment)">
        <SimpleTextarea 
            id="admin-comment" 
            v-model="adminComment"
            type="text" 
            name="admin_comment" 
            :label="$t('admin-comment')"
            :placeholder="adminComment" />
        <small class="form-text text-muted">{{$t('close-bug-admin-comment-desc')}}</small>
        <div class="d-flex">
            <SubmitButton class="ml-auto mr-1">{{ $t('close-bug-report') }}</SubmitButton>
            <button class="cancel-button">{{ $t('cancel') }}</button>
        </div>
    </form>
</template>

<script setup lang="ts">
import SubmitButton from '/js/components/global/small/SubmitButton.vue';
import {BugReport} from 'resources/types/bug';
import {onMounted, ref} from 'vue';

const adminComment = ref('');

onMounted(() => {
    adminComment.value = props.bugReport.admin_comment ?? '';
});

const props = defineProps<{bugReport: BugReport}>();
const emit = defineEmits(['close', 'submit']);
</script>