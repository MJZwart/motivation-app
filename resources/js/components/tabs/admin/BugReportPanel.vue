<template>
    <div>
        <h3>{{ $t('bug-report-panel-title') }}</h3>

        <BTable
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
                <FaIcon 
                    :icon="['far', 'pen-to-square']"
                    class="icon medium"
                    @click="editBugReport(data.item)" />
                <FaIcon 
                    icon="envelope"
                    class="icon medium"
                    @click="sendMessageToBugReportAuthor(data.item.user_id)" />
            </template>
        </BTable>

        <BModal :show="showEditBugReportModal" :footer="false" :title="$t('edit-bug-report')" @close="closeEditBugReport">
            <EditBugReport :bugReport="bugReportToEdit" @close="closeEditBugReport"/>
        </BModal>
        <BModal 
            :show="showSendMessageModal" 
            :footer="false" 
            :header="false"
            @close="closeSendMessageToBugReportAuthor">
            <SendMessage :user="bugReportAuthor" @close="closeSendMessageToBugReportAuthor"/>
        </BModal>

    </div>
</template>


<script setup>
import BTable from '../../bootstrap/BTable.vue';
import {BUG_SORTABLES, BUG_DEFAULTS, BUG_STATUS} from '../../../constants/bugConstants';
import {ref, computed} from 'vue';
import EditBugReport from '../../modals/EditBugReport.vue';
import SendMessage from '../../modals/SendMessage.vue';
import BModal from '../../bootstrap/BModal.vue';
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
