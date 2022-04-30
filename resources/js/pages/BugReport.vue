<template>
    <div class="w-60 center">
        <h2>{{ $t('submit-bug-report') }}</h2>

        <form @submit.prevent="submitBugReport">
            <div class="form-group">
                <label for="title">{{$t('title')}}</label>
                <input 
                    id="title" 
                    v-model="bugReport.title" 
                    type="text" 
                    name="title" 
                    :placeholder="$t('title')" />
                <BaseFormError name="title" />
            </div>
            <div class="form-group">
                <label for="page">{{$t('page')}}</label>
                <input 
                    id="page" 
                    v-model="bugReport.page" 
                    type="text" 
                    name="page" 
                    :placeholder="$t('page')" />
                <small class="form-text text-muted">{{$t('page-desc')}}</small>
                <BaseFormError name="page" />
            </div>
            <div class="form-group">
                <label for="type">{{$t('type')}}</label>
                <select
                    id="type"
                    v-model="bugReport.type"
                    name="type">
                    <option v-for="(option, index) in bugTypes" :key="index" :value="option.value">{{option.text}}</option>
                </select>
                <small class="form-text text-muted">{{$t('bug-type-desc')}}</small>
                <BaseFormError name="type" />
            </div>
            <div class="form-group">
                <label for="severity">{{$t('severity')}}</label>
                <select
                    id="severity"
                    v-model="bugReport.severity"
                    name="severity">
                    <option v-for="(option, index) in bugSeverity" :key="index" :value="option.value">{{option.text}}</option>
                </select>
                <small class="form-text text-muted">{{$t('bug-severity-desc')}}</small>
                <BaseFormError name="severity" />
            </div>
            <div class="form-group">
                <label for="image-link">{{$t('image-link')}}</label>
                <input 
                    id="image-link" 
                    v-model="bugReport.image_link" 
                    type="text" 
                    name="image_link" 
                    :placeholder="$t('image-link')" />
                <small class="form-text text-muted">{{$t('bug-image-link-desc')}}</small>
                <base-form-error name="image_link" />
            </div>
            <div class="form-group">
                <label for="comment">{{$t('comment')}}</label>
                <textarea 
                    id="comment" 
                    v-model="bugReport.comment"
                    type="text" 
                    name="comment" 
                    rows=3
                    :placeholder="$t('comment')" />
                <small class="form-text text-muted">{{$t('bug-comment-desc')}}</small>
                <base-form-error name="comment" />
            </div>
            <button type="submit" class="block">{{ $t('submit-bug-report') }}</button>
        </form> 
    </div>
</template>


<script setup>
import {BUG_TYPES, BUG_SEVERITY} from '/js/constants/bugConstants';
import {reactive} from 'vue';
import {useMainStore} from '/js/store/store';
const mainStore = useMainStore();
const initialData = {
    title: '',
    page: '',
    type: 'OTHER',
    severity: 1,
    image_link: '',
    comment: '',
};

const bugReport = reactive({...initialData});
const bugTypes = BUG_TYPES;
const bugSeverity = BUG_SEVERITY;

async function submitBugReport() {
    await mainStore.storeBugReport(bugReport);
    resetForm();
}

function resetForm() {
    Object.assign(bugReport, initialData);
}
</script>
