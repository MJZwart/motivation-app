<template>
    <div v-if="bugReportToEdit">
        <b-form @submit.prevent="updateBugReport">
            <b-form-group
                :label="$t('type')"
                label-for="type"
                :description="$t('bug-type-desc')">
                <b-form-select
                    id="type" 
                    v-model="bugReportToEdit.type" 
                    name="type"
                    :options="bugTypes"
                    :placeholder="bugReportToEdit.type" />
                <base-form-error name="type" /> 
            </b-form-group>
            <b-form-group
                :label="$t('severity')"
                label-for="severity"
                :description="$t('bug-severity-desc')">
                <b-form-select
                    id="severity" 
                    v-model="bugReportToEdit.severity"
                    name="severity" 
                    :options="bugSeverity"
                    :placeholder="bugReportToEdit.severity" />
                <base-form-error name="severity" /> 
            </b-form-group>
            <b-form-group
                :label="$t('admin-comment')"
                label-for="admin-comment"
                :description="$t('bug-admin-comment-desc')">
                <b-form-input 
                    id="admin-comment" 
                    v-model="bugReportToEdit.admin_comment"
                    type="text" 
                    name="admin_comment" 
                    :placeholder="bugReportToEdit.admin_comment" />
                <base-form-error name="admin_comment" /> 
            </b-form-group>
            <b-form-group
                :label="$t('status')"
                label-for="status"
                :description="$t('bug-status-desc')">
                <b-form-select
                    id="status" 
                    v-model="bugReportToEdit.status"
                    type="text" 
                    name="status" 
                    :options="bugStatus"
                    :placeholder="bugReportToEdit.status" />
                <base-form-error name="status" /> 
            </b-form-group>
            <b-button type="submit" block>{{$t('update-bug-report')}}</b-button>
            <b-button type="button" block @click="close">{{$t('cancel')}}</b-button>
        </b-form>
    </div>
</template>


<script>
import BaseFormError from '../BaseFormError.vue'
import Vue from 'vue';
import {BUG_TYPES, BUG_SEVERITY, BUG_STATUS} from '../../constants/bugConstants';

export default {
    components: {
        BaseFormError,
    },
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
