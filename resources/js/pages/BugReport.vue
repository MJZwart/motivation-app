<template>
    <div class="w-60 center">
        <h2>Submit a bug report.</h2>

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
                label-for="page">
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
                label-for="type">
                <b-form-select
                    id="type"
                    v-model="bugReport.type">
                    name="type"
                    <option disabled value="">Please select type</option>
                    <option value="1">Design</option>
                    <option value="2">Language</option>
                    <option value="3">Functionality</option>
                    <option value="4">Other</option>
                </b-form-select>
                <base-form-error name="type" />
            </b-form-group>
            <b-form-group
                :label="$t('severity')"
                label-for="severity">
                <b-form-select
                    id="severity"
                    v-model="bugReport.severity">
                    name="severity"
                    <option disabled value="">Please select severity</option>
                    <option value="1">low</option>
                    <option value="2">medium</option>
                    <option value="3">high</option>
                    <option value="4">severe</option>
                    <option value="5">critical</option>
                </b-form-select>
                <base-form-error name="severity" />
            </b-form-group>
            <b-form-group
                :label="$t('image-link')"
                label-for="image-link">
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
                label-for="comment">
                <b-form-input 
                    id="comment" 
                    v-model="bugReport.comment"
                    type="text" 
                    name="comment" 
                    placeholder="Comment" />
                <base-form-error name="comment" />
            </b-form-group>
            <b-button type="submit" block>{{ $t('submit-bug-report') }}</b-button>
        </b-form> 
    </div>
</template>


<script>
import BaseFormError from '../components/BaseFormError.vue';
export default {
    components: {BaseFormError},
    data() {
        return {
            bugReport: {
                title: '',
                page: '',
                type: '',
                severity: '',
                image_link: '',
                comment: '',
            },
        }
    },
    methods: {
        submitBugReport() {
            this.$store.dispatch('bugReport/storeBugReport', this.bugReport);
        },
    },
    
}
</script>
