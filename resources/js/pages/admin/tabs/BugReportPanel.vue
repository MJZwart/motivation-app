<template>
    <div>
        <h3>{{ $t('bug-report-panel-title') }}</h3>
        <SubmitButton class="submit-button-override" @click="toggleClosed()">
            {{ showClosed ? 'Hide closed' : 'Show closed' }}
        </SubmitButton>
        <h5 v-if="showClosed">{{ $t('showing-closed-bug-reports') }}</h5>
        
        <Loading v-if="loading" />
        <SortableOverviewTable
            v-else
            :items="bugReports"
            :fields="BUG_REPORT_OVERVIEW_FIELDS"
        >
            <template #title="row">
                <h5>{{row.item.title}}</h5>
            </template>
            
            <template #diagnostics="row">
                <Diagnostics :diagnostics="row.item.diagnostics" />
            </template>

            <template #username="row">
                <b>{{ $t('reported-by') }}</b>: 
                <router-link v-if="row.item.user_id" :to="{name: 'profile', params: {id: row.item.user_id}}">
                    {{ row.item.username }}
                </router-link>
                <span v-else>{{ $t('no-user') }}</span>
            </template>

            <template #status="row">
                {{ parseStatus(row.item.status) }}
            </template>
            <template #actions="row">
                <div style="min-width: 49px">
                    <template v-if="row.item.deleted_at === null">
                        <Icon :icon="EDIT" class="edit-icon" @click="editBugReport(row.item)" />
                        <Icon 
                            v-if="row.item.user_id" 
                            :icon="MAIL" 
                            class="mail-icon" 
                            @click="sendMessageToBugReportAuthor(row.item.user_id, row.item.username)" 
                        />
                        <Icon :icon="TRASH" class="trash-icon red" @click="closeBugReport(row.item)" />
                    </template>
                    <template v-else>
                        <b>{{ $t('closed-on') }}: </b>{{ parseDateTime(row.item.deleted_at) }}
                        <Icon :icon="RESTORE_TRASH" class="restore-trash-icon green" @click="restoreBugReport(row.item)" />
                    </template>
                </div>
            </template>
        </SortableOverviewTable>
    </div>
</template>

<script setup lang="ts">
import type {BugReport} from 'resources/types/bug';
import {BUG_REPORT_OVERVIEW_FIELDS, BUG_STATUS} from '/js/constants/bugConstants';
import {onMounted, ref} from 'vue';
import EditBugReport from './../components/EditBugReport.vue';
import Diagnostics from '/js/components/global/small/Diagnostics.vue';
import {useI18n} from 'vue-i18n';
import {EDIT, MAIL, TRASH, RESTORE_TRASH} from '/js/constants/iconConstants';
import SortableOverviewTable from '/js/components/global/SortableOverviewTable.vue';
import CloseBugReportModal from '../components/CloseBugReportModal.vue';
import {parseDateTime} from '/js/services/dateService';
import {formModal, sendMessageModal} from '/js/components/modal/modalService';
import axios from 'axios';

const {t} = useI18n();

onMounted(async() => {
    bugReports.value = await fetchBugReports();
    loading.value = false;
});

const loading = ref(true);

const bugReports = ref<BugReport[]>([]);

const showClosed = ref(false);

// * Message bug report author
function sendMessageToBugReportAuthor(user_id: number, username: string) {
    sendMessageModal(username, user_id);
}

// * Edit Bug Report
function editBugReport(bugReport: BugReport) {
    formModal(bugReport, EditBugReport, submitEditBugReport, 'edit-bug-report');
}
async function submitEditBugReport(updatedBugReport: BugReport) {
    const {data} = await axios.put('/admin/bugreport/' + updatedBugReport.id, updatedBugReport);
    bugReports.value = data.data.bugReports;
}

// * Close bug report
function closeBugReport(bugReport: BugReport) {
    formModal(bugReport, CloseBugReportModal, submitCloseBugReport, 'confirm-close-bug-report');
}
async function submitCloseBugReport(bugReport: BugReport) {
    const {data} = await axios.put(`/admin/bugreport/delete/${bugReport.id}`, bugReport);
    bugReports.value = data.data.bugReports;
}

function parseStatus(status: number) {
    const statusElement = BUG_STATUS.find(element => element.value == status);
    return statusElement ? t(statusElement.text) : '';
}

async function toggleClosed() {
    showClosed.value = !showClosed.value;
    if (showClosed.value) {
        const {data} = await axios.get('/admin/bugreport/closed');
        bugReports.value = data.data;
    }
    else bugReports.value = await fetchBugReports();
}
async function restoreBugReport(bugReport: BugReport) {
    const {data} = await axios.put(`/admin/bugreport/restore/${bugReport.id}`);
    bugReports.value = data.data.bugReports;
}

async function fetchBugReports(): Promise<BugReport[]> {
    const {data} = await axios.get('/admin/bugreport');
    return data.data;
}
</script>
