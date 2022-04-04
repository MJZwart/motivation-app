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
                    <option v-for="(option, index) in bugTypes" :key="index" :value="option.value">{{option.text}}</option>
                </select>
                <small class="form-text text-muted">{{$t('bug-type-desc')}}</small>
                <base-form-error name="type" /> 
            </div>
            <div class="form-group">
                <label for="severity">{{$t('severity')}}</label>
                <select
                    id="severity" 
                    v-model="bugReportToEdit.severity"
                    name="severity" 
                    :placeholder="bugReportToEdit.severity">
                    <option v-for="(option, index) in bugSeverity" :key="index" :value="option.value">{{option.text}}</option>
                </select>
                <small class="form-text text-muted">{{$t('bug-severity-desc')}}</small>
                <base-form-error name="severity" /> 
            </div>
            <div class="form-group">
                <label for="admin-comment">{{$t('admin-comment')}}</label>
                <input 
                    id="admin-comment" 
                    v-model="bugReportToEdit.admin_comment"
                    type="text" 
                    name="admin_comment" 
                    :placeholder="bugReportToEdit.admin_comment" />
                <small class="form-text text-muted">{{$t('bug-admin-comment-desc')}}</small>
                <base-form-error name="admin_comment" /> 
            </div>
            <div class="form-group">
                <label for="status">{{$t('status')}}</label>
                <select
                    id="status" 
                    v-model="bugReportToEdit.status"
                    type="text" 
                    name="status" 
                    :placeholder="bugReportToEdit.status">
                    <option v-for="(option, index) in bugStatus" :key="index" :value="option.value">{{option.text}}</option>
                </select>
                <small class="form-text text-muted">{{$t('bug-status-desc')}}</small>
                <base-form-error name="status" /> 
            </div>
            <b-button type="submit" block>{{$t('update-bug-report')}}</b-button>
            <b-button type="button" block @click="close">{{$t('cancel')}}</b-button>
        </form>
    </div>
</template>


<script>
import BaseFormError from '../BaseFormError.vue';
import Vue from 'vue';
import {BUG_TYPES, BUG_SEVERITY, BUG_STATUS} from '../../constants/bugConstants';

export default {
    components: {BaseFormError},
    props: {
        bugReport: {
            type: Object,
            required: true,
        },
    },
    mounted() {
        if (this.bugReport) {
            this.bugReportToEdit = Vue.util.extend({}, this.bugReport);
        }
    },
    data() {
        return {
            bugReportToEdit: {},
            bugTypes: BUG_TYPES,
            bugSeverity: BUG_SEVERITY,
            bugStatus: BUG_STATUS,
            message: {
                message: 'Your bug report has been resolved!',
            },
        }
    },
    methods: {
        updateBugReport() {
            this.$store.dispatch('bugReport/updateBugReport', this.bugReportToEdit).then(() => {
                if (this.bugReportToEdit.status == 3) {
                    this.message.recipient_id = this.bugReportToEdit.user_id;
                    this.$store.dispatch('message/sendMessage', this.message);
                }
                this.close();
            })
        },
        close() {
            this.bugReportToEdit = {};
            this.$emit('close');
        },
    },
}
</script>
