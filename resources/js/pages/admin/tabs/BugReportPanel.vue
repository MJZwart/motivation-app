<template>
    <div>
        <h3>{{ $t('bug-report-panel-title') }}</h3>
        

        <SortableOverviewTable
            v-if="bugReports"
            :items="bugReports"
            :fields="newBugReportFields"
        >
            <template #title="data">
                <h5>{{data.item.title}}</h5>
            </template>
            
            <template #diagnostics="data">
                <Diagnostics :diagnostics="data.item.diagnostics" />
            </template>

            <template #user="data">
                <b>{{ $t('reported-by') }}</b>: 
                <router-link v-if="data.item.user" :to="{name: 'profile', params: {id: data.item.user.id}}">
                    {{ data.item.user.username }}
                </router-link>
                <span v-else>{{ $t('no-user') }}</span>
            </template>

            <template #status="data">
                {{ parseStatus(data.item.status) }}
            </template>
            <template #actions="data">
                <div style="min-width: 49px">
                    <Icon :icon="EDIT" class="edit-icon" @click="editBugReport(data.item)" />
                    <Icon 
                        v-if="data.item.user" 
                        :icon="MAIL" 
                        class="mail-icon" 
                        @click="sendMessageToBugReportAuthor(data.item.user)" 
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
import {StrippedUser} from 'resources/types/user';
import {BugReport} from 'resources/types/bug';
import {useI18n} from 'vue-i18n';
import {EDIT, MAIL} from '/js/constants/iconConstants';
import SortableOverviewTable from '/js/components/global/SortableOverviewTable.vue';
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

function sendMessageToBugReportAuthor(author: StrippedUser) {
    mainStore.clearErrors();
    bugReportAuthor.value = author;
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
