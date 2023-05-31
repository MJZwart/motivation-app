<template>
    <div>
        <p><b>{{ $t('title') }}</b>: {{ bugReport.title }}</p>
        <p><b>{{ $t('page') }}</b>: {{ bugReport.page }}</p>
        <p><b>{{ $t('severity') }}</b>: {{ bugReport.severity }}</p>
        <p><b>{{ $t('comment') }}</b>: {{ bugReport.comment }}</p>
    </div>
    <SimpleTextarea 
        id="admin-comment" 
        v-model="bugReport.admin_comment"
        type="text" 
        name="admin_comment" 
        :label="$t('admin-comment')"
        :placeholder="bugReport.admin_comment" />
    <small class="form-text text-muted">{{$t('close-bug-admin-comment-desc')}}</small>
    <div class="d-flex">
        <SubmitButton class="ml-auto mr-1" @click="$emit('submit', bugReport)">{{ $t('close-bug-report') }}</SubmitButton>
        <button class="cancel-button">{{ $t('cancel') }}</button>
    </div>
</template>

<script setup lang="ts">
import SubmitButton from '/js/components/global/small/SubmitButton.vue';
import {BugReport} from 'resources/types/bug';
import {ref} from 'vue';
import {deepCopy} from '/js/helpers/copy';

const props = defineProps<{form: BugReport}>();
defineEmits(['close', 'submit']);

const bugReport = ref(deepCopy(props.form));

</script>