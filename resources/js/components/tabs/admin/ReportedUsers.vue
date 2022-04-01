<template>
    <div>
        <h3>this is a placeholder title</h3>
        
        <BTable
            :items="reportedUsers"
            :fields="reportedUserFields"
            :options="['table-striped']"
        >
            <template #details="row">
                <b-button @click="showReportedUserDetails(row)">placeholder details</b-button>
            </template>
        </BTable>
        <BModal 
            :show="showReportedUserDetailsModal" 
            :footer="false" 
            :title="reportedUserDetailsTitle" 
            class="l"
            @close="closeReportedUserDetails">
            <ReportedUserDetails :user="reportedUserDetails" :index="reportedUserIndex" />
            <!-- Ugly af -->
        </BModal>
    </div>
</template>

<script>
import BTable from '../../bootstrap/BTable.vue';
import BModal from '../../bootstrap/BModal.vue';
import {mapGetters} from 'vuex';
import ReportedUserDetails from './components/ReportedUserDetails.vue';
import {REPORTED_USER_FIELDS} from '../../../constants/reportedUserConstants.js';
export default {
    components: {
        ReportedUserDetails, BTable, BModal,
    },
    data() {
        return {
            reportedUserDetails: null,
            reportedUserIndex: null,
            reportedUserFields: REPORTED_USER_FIELDS,
            reportedUserDetailsTitle: null,
            showReportedUserDetailsModal: false,
        }
    },
    computed: {
        ...mapGetters({
            reportedUsers: 'admin/getReportedUsers',
        }),
    },
    methods: {
        showReportedUserDetails({item, index}) {
            this.reportedUserDetails = item;
            this.reportedUserIndex = index;
            this.reportedUserDetailsTitle = item.username;
            this.showReportedUserDetailsModal = true;
        },
        closeReportedUserDetails() {
            this.showReportedUserDetailsModal = false;
        },
    },
}
</script>
