<template>
    <div class="w-50-flex center">
        <h2>{{ $t('submit-bug-report') }}</h2>

        <form @submit.prevent="submitBugReport">
            <SimpleInput
                id="title"
                v-model="bugReport.title"
                type="text"
                name="title"
                :label="$t('title')"
                :placeholder="$t('title')"
            />
            <SimpleInput
                id="page"
                v-model="bugReport.page"
                type="text"
                name="page"
                :label="$t('page')"
                :placeholder="$t('page')"
            />
            <small class="form-text text-muted">{{ $t('page-desc') }}</small>
            <div class="form-group">
                <label for="type">{{ $t('type') }}</label>
                <select id="type" v-model="bugReport.type" name="type">
                    <option v-for="(option, index) in bugTypes" :key="index" :value="option.value">
                        {{ $t(option.text) }}
                    </option>
                </select>
                <small class="form-text text-muted">{{ $t('bug-type-desc') }}</small>
                <BaseFormError name="type" />
            </div>
            <div class="form-group">
                <label for="severity">{{ $t('severity') }}</label>
                <select id="severity" v-model="bugReport.severity" name="severity">
                    <option v-for="(option, index) in bugSeverity" :key="index" :value="option.value">
                        {{ $t(option.text) }}
                    </option>
                </select>
                <small class="form-text text-muted">{{ $t('bug-severity-desc') }}</small>
                <BaseFormError name="severity" />
            </div>
            <SimpleInput
                id="image-link"
                v-model="bugReport.image_link"
                type="text"
                name="image_link"
                :label="$t('image-link')"
                :placeholder="$t('image-link')"
            />
            <small class="form-text text-muted">{{ $t('bug-image-link-desc') }}</small>
            <SimpleTextarea
                id="comment"
                v-model="bugReport.comment"
                type="text"
                name="comment"
                :rows="3"
                :label="$t('comment')"
                :placeholder="$t('comment')"
            />
            <small class="form-text text-muted mb-3">{{ $t('bug-comment-desc') }}</small>
            <SimpleFormCheckbox 
                id="diagnostics" 
                v-model="bugReport.diagnostics_approval" 
                :label="$t('send-diagnostics-information')" />
            <small class="form-text text-muted mb-3">{{$t('send-diagnostics-information-explanation')}}</small>
            <SubmitButton class="block">{{ $t('submit-bug-report') }}</SubmitButton>
        </form>
    </div>
</template>

<script setup lang="ts">
import {BUG_TYPES, BUG_SEVERITY} from '/js/constants/bugConstants';
import {ref} from 'vue';
import {useMainStore} from '/js/store/store';
import {NewBugReport} from 'resources/types/bug';
import {getDiagnostics} from '/js/services/platformService';
import {clearErrors} from '../services/errorService';

const mainStore = useMainStore();
const emptyBugReport = {
    title: '',
    page: '',
    type: 'OTHER',
    severity: 1,
    image_link: '',
    comment: '',
    diagnostics_approval: false,
};

const bugReport = ref<NewBugReport>({...emptyBugReport});
const bugTypes = BUG_TYPES;
const bugSeverity = BUG_SEVERITY;

async function submitBugReport() {
    if (bugReport.value.diagnostics_approval)
        bugReport.value.diagnostics = getDiagnostics();
    clearErrors();
    await mainStore.storeBugReport(bugReport.value);
    resetForm();
}

function resetForm() {
    Object.assign(bugReport.value, emptyBugReport);
}
</script>
