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
    <FormControls
        :submit-text="$t('close-bug-report')"
        @submit="$emit('submit', bugReport)"
        @cancel="$emit('close')" />
</template>

<script setup lang="ts">
import {BugReport} from 'resources/types/bug';
import {ref} from 'vue';
import {deepCopy} from '/js/helpers/copy';
import FormControls from '/js/components/global/FormControls.vue';

const props = defineProps<{form: BugReport}>();
defineEmits(['close', 'submit']);

const bugReport = ref(deepCopy(props.form));

</script>