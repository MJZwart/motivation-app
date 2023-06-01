<template>
    <div>
        <div class="form-group">
            <label for="type">{{$t('type')}}</label>
            <select
                id="type" 
                v-model="bugReport.type" 
                name="type"
                :placeholder="bugReport.type">
                <option v-for="(option, index) in BUG_TYPES" :key="index" :value="option.value">{{$t(option.text)}}</option>
            </select>
            <small class="form-text text-muted">{{$t('bug-type-desc')}}</small>
            <BaseFormError name="type" /> 
        </div>
        <div class="form-group">
            <label for="severity">{{$t('severity')}}</label>
            <select
                id="severity" 
                v-model="bugReport.severity"
                name="severity" >
                <option v-for="(option, index) in BUG_SEVERITY" :key="index" :value="option.value">{{$t(option.text)}}</option>
            </select>
            <small class="form-text text-muted">{{$t('bug-severity-desc')}}</small>
            <BaseFormError name="severity" /> 
        </div>
        <SimpleInput 
            id="admin-comment" 
            v-model="bugReport.admin_comment"
            type="text" 
            name="admin_comment" 
            :label="$t('admin-comment')"
            :placeholder="bugReport.admin_comment" />
        <small class="form-text text-muted">{{$t('bug-admin-comment-desc')}}</small>
        <div class="form-group">
            <label for="status">{{$t('status')}}</label>
            <select
                id="status" 
                v-model="bugReport.status"
                name="status" >
                <option v-for="(option, index) in BUG_STATUS" :key="index" :value="option.value">{{$t(option.text)}}</option>
            </select>
            <small class="form-text text-muted">{{$t('bug-status-desc')}}</small>
            <BaseFormError name="status" /> 
        </div>
            
        <div v-if="bugReport.status == 3" class="form-group">
            <SimpleFormCheckbox
                id="send-message-to-reporter" 
                v-model="bugReport.sendMessageToReporter"
                name="send-message-to-reporter"
                :label="$t('send-message-to-reporter')" />
        </div>

        
        <FormControls
            :submit-text="$t('update-bug-report')"            
            @submit="$emit('submit', bugReport)"
            @cancel="$emit('close')"
        />
    </div>
</template>


<script setup lang="ts">
import type {BugReport} from 'resources/types/bug';
import {ref} from 'vue';
import {BUG_TYPES, BUG_SEVERITY, BUG_STATUS} from '/js/constants/bugConstants';
import {deepCopy} from '/js/helpers/copy';
import FormControls from '/js/components/global/FormControls.vue';

const props = defineProps<{form: BugReport}>();
defineEmits(['close', 'submit']);

const bugReport = ref<BugReport>(deepCopy(props.form));
</script>
