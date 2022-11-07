<template>
    <div v-if="bugReportToEdit">
        <form @submit.prevent="updateBugReport">
            <div class="form-group">
                <label for="type">{{$t('type')}}</label>
                <select
                    id="type" 
                    v-model="bugReportToEdit.type" 
                    name="type"
                    :placeholder="bugReportToEdit.type">
                    <option v-for="(option, index) in bugTypes" :key="index" :value="option.value">{{$t(option.text)}}</option>
                </select>
                <small class="form-text text-muted">{{$t('bug-type-desc')}}</small>
                <BaseFormError name="type" /> 
            </div>
            <div class="form-group">
                <label for="severity">{{$t('severity')}}</label>
                <select
                    id="severity" 
                    v-model="bugReportToEdit.severity"
                    name="severity" >
                    <option v-for="(option, index) in bugSeverity" :key="index" :value="option.value">{{$t(option.text)}}</option>
                </select>
                <small class="form-text text-muted">{{$t('bug-severity-desc')}}</small>
                <BaseFormError name="severity" /> 
            </div>
            <SimpleInput 
                id="admin-comment" 
                v-model="bugReportToEdit.admin_comment"
                type="text" 
                name="admin_comment" 
                :label="$t('admin-comment')"
                :placeholder="bugReportToEdit.admin_comment" />
            <small class="form-text text-muted">{{$t('bug-admin-comment-desc')}}</small>
            <div class="form-group">
                <label for="status">{{$t('status')}}</label>
                <select
                    id="status" 
                    v-model="bugReportToEdit.status"
                    name="status" >
                    <option v-for="(option, index) in bugStatus" :key="index" :value="option.value">{{$t(option.text)}}</option>
                </select>
                <small class="form-text text-muted">{{$t('bug-status-desc')}}</small>
                <BaseFormError name="status" /> 
            </div>
            <button type="submit" class="block">{{$t('update-bug-report')}}</button>
            <button type="button" class="block button-cancel" @click="close">{{$t('cancel')}}</button>
        </form>
    </div>
</template>


<script setup lang="ts">
import {onMounted, ref, PropType} from 'vue';
import {BUG_TYPES, BUG_SEVERITY, BUG_STATUS} from '/js/constants/bugConstants';
import {BugReport} from 'resources/types/bug';
import {NewMessage} from 'resources/types/message';
import {useAdminStore} from '/js/store/adminStore';
import {useMessageStore} from '/js/store/messageStore';
const adminStore = useAdminStore();
const messageStore = useMessageStore();

const props = defineProps({
    bugReport: {
        type: Object as PropType<BugReport>,
        required: true,
    },
});
const emit = defineEmits(['close']);

onMounted(() => {
    bugReportToEdit.value = Object.assign({}, props.bugReport);
});

const bugReportToEdit = ref<BugReport | null>(null);
const bugTypes = BUG_TYPES;
const bugSeverity = BUG_SEVERITY;
const bugStatus = BUG_STATUS;

async function updateBugReport() {
    if (!bugReportToEdit.value) return;
    await adminStore.updateBugReport(bugReportToEdit.value)
    if (bugReportToEdit.value.status == 3) {
        const message = {
            message: 'Your bug report has been resolved!',
            recipient_id: bugReportToEdit.value.user_id,
        } as NewMessage;
        messageStore.sendMessage(message);
    }
    close();
}
function close() {
    bugReportToEdit.value = null;
    emit('close');
}
</script>
