<template>
    <div>
        <h3>{{ $t('bug-report-panel-title') }}</h3>

        <b-table
            :items="bugReports"
            :fields="bugSortables"
            :sort-by.sync="currentSort"
            :sort-desc.sync="currentSortDesc"
            hover small responsive
            class="font-sm">
            <template #cell(severity)="data">
                <span class="severity">{{ data.item.severity }}</span>
            </template>
            <template #cell(status)="data">
                {{ parseStatus(data.item.status) }}
            </template>
            <template #cell(actions)="data">
                <b-icon-pencil-square 
                    class="icon medium"
                    @click="editBugReport(data.item)" /> 
                <b-icon-envelope class="icon medium" @click="sendMessageToBugReportAuthor(data.item.user_id)" /> 
            </template>
        </b-table>

        <BModal :show="showEditBugReportModal" :footer="false" :title="$t('edit-bug-report')" @close="closeEditBugReport">
            <edit-bug-report :bugReport="bugReportToEdit" @close="closeEditBugReport"/>
        </BModal>
        <BModal 
            :show="showSendMessageModal" 
            :footer="false" 
            :title="$t('send-message-to-bug-report-author')" 
            @close="closeSendMessageToBugReportAuthor">
            <send-message :user="bugReportAuthor" @close="closeSendMessageToBugReportAuthor"/>
        </BModal>

    </div>
</template>


<script>
import {BUG_SORTABLES, BUG_DEFAULTS, BUG_STATUS} from '../../../constants/bugConstants';
import {mapGetters} from 'vuex';
import EditBugReport from '../../modals/EditBugReport.vue';
import SendMessage from '../../modals/SendMessage.vue';
import BModal from '../../bootstrap/BModal.vue';
export default {
    components: {
        EditBugReport,
        SendMessage,
        BModal,
    },
    computed: {
        ...mapGetters({
            bugReports: 'bugReport/getBugReports',
        }),
    },
    data() {
        return {
            currentSort: BUG_DEFAULTS.currentSort,
            bugSortables: BUG_SORTABLES,
            currentSortDesc: true,
            bugReportToEdit: null,
            bugReportAuthor: null,
            showEditBugReportModal: false,
            showSendMessageModal: false,
        }
    },    
    methods: {
        sendMessageToBugReportAuthor(authorId) {
            this.$store.dispatch('clearErrors');
            this.bugReportAuthor = {id: authorId};
            this.showSendMessageModal = true;
        },
        closeSendMessageToBugReportAuthor() {
            this.showSendMessageModal = false;
        },
        editBugReport(bugReport) {
            this.$store.dispatch('clearErrors');
            this.bugReportToEdit = bugReport;
            this.showEditBugReportModal = true;
        },
        closeEditBugReport() {
            this.showEditBugReportModal = false;
        },
        parseStatus(status) {
            return BUG_STATUS.find(element => element.value == status).text;
        },
    },
}
</script>
