<template>
    <div>
        <h3>{{ $t('bug-report-panel-title') }}</h3>
        
        <Loading v-if="!bugReports[0]" />
        <SortableOverviewTable
            v-else
            :items="bugReports"
            :fields="newBugReportFields"
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
                    <Icon :icon="EDIT" class="edit-icon" @click="editBugReport(row.item)" />
                    <Icon 
                        v-if="row.item.user_id" 
                        :icon="MAIL" 
                        class="mail-icon" 
                        @click="sendMessageToBugReportAuthor(row.item.user_id, row.item.username)" 
                    />
                </div>
            </template>
        </SortableOverviewTable>

        <Modal
            :show="showEditBugReportModal"
            :footer="false"
            :title="$t('edit-bug-report')"
            @close="closeEditBugReport"
        >
            <EditBugReport 
                v-if="bugReportToEdit" 
                :bugReport="bugReportToEdit" 
                @close="closeEditBugReport" 
                @submit="submitUpdateBugReport" />
        </Modal>
        <Modal :show="showSendMessageModal" :header="false" @close="closeSendMessageToBugReportAuthor">
            <SendMessage v-if="bugReportAuthor" :user="bugReportAuthor" @close="closeSendMessageToBugReportAuthor" />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import {BUG_REPORT_OVERVIEW_FIELDS, BUG_STATUS} from '/js/constants/bugConstants';
import {onMounted, ref} from 'vue';
import EditBugReport from './../components/EditBugReport.vue';
import SendMessage from '/js/pages/messages/components/SendMessage.vue';
import Diagnostics from '/js/components/global/small/Diagnostics.vue';
import {useMainStore} from '/js/store/store';
import {useAdminStore} from '/js/store/adminStore';
import {BugReport} from 'resources/types/bug';
import {useI18n} from 'vue-i18n';
import {EDIT, MAIL} from '/js/constants/iconConstants';
import SortableOverviewTable from '/js/components/global/SortableOverviewTable.vue';
import {StrippedUser} from 'resources/types/user';
const mainStore = useMainStore();
const adminStore = useAdminStore();
const {t} = useI18n();

onMounted(async() => {
    bugReports.value = await adminStore.getBugReports();
});

const bugReports = ref<BugReport[]>([]);

const newBugReportFields = BUG_REPORT_OVERVIEW_FIELDS;
const bugReportToEdit = ref<BugReport | null>(null);
const bugReportAuthor = ref<StrippedUser | null>(null);
const showEditBugReportModal = ref(false);
const showSendMessageModal = ref(false);

function sendMessageToBugReportAuthor(user_id: number, username: string) {
    mainStore.clearErrors();
    bugReportAuthor.value = {username: username, id: user_id};
    showSendMessageModal.value = true;
}
function closeSendMessageToBugReportAuthor() {
    showSendMessageModal.value = false;
}
function editBugReport(bugReport: BugReport) {
    mainStore.clearErrors();
    bugReportToEdit.value = bugReport;
    showEditBugReportModal.value = true;
}
function closeEditBugReport() {
    showEditBugReportModal.value = false;
}
async function submitUpdateBugReport(updatedBugReport: BugReport) {
    bugReports.value = await adminStore.updateBugReport(updatedBugReport);
}
function parseStatus(status: number) {
    const statusElement = BUG_STATUS.find(element => element.value == status);
    return statusElement ? t(statusElement.text) : '';
}
</script>
