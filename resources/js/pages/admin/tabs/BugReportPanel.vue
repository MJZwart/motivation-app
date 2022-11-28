<template>
    <div>
        <h3>{{ $t('bug-report-panel-title') }}</h3>

        <Table
            v-if="bugReports"
            :items="bugReports"
            :fields="bugReportFields"
            :sort="currentSort"
            :sortAsc="!currentSortDesc"
            :options="['table-hover', 'table-sm', 'table-responsive', 'table-striped']"
            class="font-sm"
        >
            <template #time_created="data">
                {{ parseDateTime(data.item.time_created) }}
            </template>
            <template #severity="data">
                <span class="severity">{{ data.item.severity }}</span>
            </template>
            <template #status="data">
                {{ parseStatus(data.item.status) }}
            </template>
            <template #user="data">
                <router-link v-if="data.item.user" :to="{name: 'profile', params: {id: data.item.user.id}}">
                    {{ data.item.user.username }}
                </router-link>
                <span v-else>{{ $t('no-user') }}</span>
            </template>
            <template #diagnostics="data">
                <Diagnostics :diagnostics="data.item.diagnostics" />
            </template>
            <template #actions="data">
                <div style="min-width: 49px">
                    <FaIcon :icon="['far', 'pen-to-square']" class="icon medium" @click="editBugReport(data.item)" />
                    <FaIcon 
                        v-if="data.item.user" 
                        icon="envelope" 
                        class="icon medium" 
                        @click="sendMessageToBugReportAuthor(data.item.user)" 
                    />
                </div>
            </template>
        </Table>

        <Modal
            :show="showEditBugReportModal"
            :footer="false"
            :title="$t('edit-bug-report')"
            @close="closeEditBugReport"
        >
            <EditBugReport v-if="bugReportToEdit" :bugReport="bugReportToEdit" @close="closeEditBugReport" />
        </Modal>
        <Modal :show="showSendMessageModal" :header="false" @close="closeSendMessageToBugReportAuthor">
            <SendMessage v-if="bugReportAuthor" :user="bugReportAuthor" @close="closeSendMessageToBugReportAuthor" />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import {BUG_REPORT_OVERVIEW_FIELDS, BUG_DEFAULTS, BUG_STATUS} from '/js/constants/bugConstants';
import {ref, computed} from 'vue';
import Table from '/js/components/global/Table.vue';
import EditBugReport from './../components/EditBugReport.vue';
import SendMessage from '/js/pages/messages/components/SendMessage.vue';
import Diagnostics from '/js/components/global/small/Diagnostics.vue';
import {parseDateTime} from '/js/services/dateService';
import {useMainStore} from '/js/store/store';
import {useAdminStore} from '/js/store/adminStore';
import {StrippedUser} from 'resources/types/user';
import {BugReport} from 'resources/types/bug';
import {useI18n} from 'vue-i18n';
const mainStore = useMainStore();
const adminStore = useAdminStore();
const {t} = useI18n();

const bugReports = computed(() => adminStore.bugReports);

const currentSort = ref(BUG_DEFAULTS.currentSort);
const bugReportFields = BUG_REPORT_OVERVIEW_FIELDS;
const currentSortDesc = ref(true);
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
function parseStatus(status: number) {
    const statusElement = BUG_STATUS.find(element => element.value == status);
    return statusElement ? t(statusElement.text) : '';
}
</script>
