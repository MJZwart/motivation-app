<template>
    <div>
        <h3>this is a placeholder title</h3>
        
        <BTable
            :items="reportedUsers"
            :fields="reportedUserFields"
            :options="['striped']"
        >
            <template #details="row">
                <b-button @click="showReportedUserDetails(row)">placeholder details</b-button>
            </template>
        </BTable>
        <b-modal id="show-reported-user-details" size="lg" hide-footer :title="reportedUserDetailsTitle">
            <ReportedUserDetails :user="reportedUserDetails" :index="reportedUserIndex" />
        </b-modal>
    </div>
</template>

<script>
import BTable from '../../bootstrap/BTable.vue';
import {mapGetters} from 'vuex';
import ReportedUserDetails from './components/ReportedUserDetails.vue';
import {REPORTED_USER_FIELDS} from '../../../constants/reportedUserConstants.js';
export default {
    components: {
        ReportedUserDetails, BTable,
    },
    data() {
        return {
            reportedUserDetails: null,
            reportedUserIndex: null,
            reportedUserFields: REPORTED_USER_FIELDS,
            reportedUserDetailsTitle: null,
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
            this.$bvModal.show('show-reported-user-details');
        },
    },
}
</script>
