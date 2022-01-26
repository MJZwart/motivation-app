<template>
    <div class="w-60 center">
        <h2>{{ $t('submit-bug-report') }}</h2>

        <b-form @submit.prevent="submitBugReport">
            <b-form-group
                :label="$t('title')"
                label-for="title">
                <b-form-input 
                    id="title" 
                    v-model="bugReport.title" 
                    type="text" 
                    name="title" 
                    :placeholder="$t('title')" />
                <base-form-error name="title" />
            </b-form-group>
            <b-form-group
                :label="$t('page')"
                label-for="page"
                :description="$t('page-desc')">
                <b-form-input 
                    id="page" 
                    v-model="bugReport.page" 
                    type="text" 
                    name="page" 
                    :placeholder="$t('page')" />
                <base-form-error name="page" />
            </b-form-group>
            <b-form-group
                :label="$t('type')"
                label-for="type"
                :description="$t('bug-type-desc')">
                <b-form-select
                    id="type"
                    v-model="bugReport.type"
                    name="type" 
                    :options="bugTypes" />
                <base-form-error name="type" />
            </b-form-group>
            <b-form-group
                :label="$t('severity')"
                label-for="severity"
                :description="$t('bug-severity-desc')">
                <b-form-select
                    id="severity"
                    v-model="bugReport.severity"
                    name="severity"
                    :options="bugSeverity" />
                <base-form-error name="severity" />
            </b-form-group>
            <b-form-group
                :label="$t('image-link')"
                label-for="image-link"
                :description="$t('bug-image-link-desc')">
                <b-form-input 
                    id="image-link" 
                    v-model="bugReport.image_link" 
                    type="text" 
                    name="image-link" 
                    :placeholder="$t('image-link')" />
                <base-form-error name="image_link" />
            </b-form-group>
            <b-form-group
                :label="$t('comment')"
                label-for="comment"
                :description="$t('bug-comment-desc')">
                <b-form-textarea 
                    id="comment" 
                    v-model="bugReport.comment"
                    type="text" 
                    name="comment" 
                    rows=3
                    :placeholder="$t('comment')" />
                <base-form-error name="comment" />
            </b-form-group>
            <b-button type="submit" block>{{ $t('submit-bug-report') }}</b-button>
        </b-form> 
    </div>
</template>


<script>
import BaseFormError from '../components/BaseFormError.vue';
import {BUG_TYPES, BUG_SEVERITY} from '../constants/bugConstants';

export default {
    components: {BaseFormError},
    data() {
        return {
            bugReport: {
                title: '',
                page: '',
                type: 'Other',
                severity: 1,
                image_link: '',
                comment: '',
            },
            bugTypes: BUG_TYPES,
            bugSeverity: BUG_SEVERITY,
        }
    },
    methods: {
        submitBugReport() {
            this.$store.dispatch('bugReport/storeBugReport', this.bugReport);
        },
    },
    
}
</script>
