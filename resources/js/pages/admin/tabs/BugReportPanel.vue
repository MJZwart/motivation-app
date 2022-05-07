<template>
    <div>
        <h3>{{ $t('bug-report-panel-title') }}</h3>

        <Table
            :items="bugReports"
            :fields="bugSortables"
            :sort="currentSort"
            :sortAsc="!currentSortDesc"
            :options="['table-hover', 'table-sm', 'table-responsive', 'table-striped']"
            class="font-sm">
            <template #severity="data">
                <span class="severity">{{ data.item.severity }}</span>
            </template>
            <template #status="data">
                {{ parseStatus(data.item.status) }}
            </template>
            <template #actions="data">
                <div style="min-width: 49px">
                    <FaIcon 
                        :icon="['far', 'pen-to-square']"
                        class="icon medium"
                        @click="editBugReport(data.item)" />
                    <FaIcon 
                        icon="envelope"
                        class="icon medium"
                        @click="sendMessageToBugReportAuthor(data.item.user_id)" />
                </div>
            </template>
        </Table>

        <Modal :show="showEditBugReportModal" :footer="false" :title="$t('edit-bug-report')" @close="closeEditBugReport">
            <EditBugReport :bugReport="bugReportToEdit" @close="closeEditBugReport"/>
        </Modal>
        <Modal 
            :show="showSendMessageModal" 
            :footer="false" 
            :header="false"
            @close="closeSendMessageToBugReportAuthor">
            <SendMessage :user="bugReportAuthor" @close="closeSendMessageToBugReportAuthor"/>
        </Modal>

    </div>
</template>


<script setup>
import Table from '/js/components/global/Table.vue';
import {BUG_SORTABLES, BUG_DEFAULTS, BUG_STATUS} from '/js/constants/bugConstants';
import {ref, computed} from 'vue';
import EditBugReport from './../components/EditBugReport.vue';
import SendMessage from '/js/pages/messages/components/SendMessage.vue';
import {useMainStore} from '/js/store/store';
import {useAdminStore} from '/js/store/adminStore';
const mainStore = useMainStore();
const adminStore = useAdminStore();

const bugReports = computed(() => adminStore.bugReports);

const currentSort = ref(BUG_DEFAULTS.currentSort);
const bugSortables = BUG_SORTABLES;
const currentSortDesc = ref(true);
const bugReportToEdit = ref(null);
const bugReportAuthor = ref(null);
const showEditBugReportModal = ref(false);
const showSendMessageModal = ref(false);

function sendMessageToBugReportAuthor(authorId) {
    mainStore.clearErrors();
    bugReportAuthor.value = {id: authorId};
    showSendMessageModal.value = true;
}
function closeSendMessageToBugReportAuthor() {
    showSendMessageModal.value = false;
}
function editBugReport(bugReport) {
    mainStore.clearErrors();
    bugReportToEdit.value = bugReport;
    showEditBugReportModal.value = true;
}
function closeEditBugReport() {
    showEditBugReportModal.value = false;
}
function parseStatus(status) {
    return BUG_STATUS.find(element => element.value == status).text;
}
</script>
