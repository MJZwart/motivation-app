<template>
    <div>
        <h3>this is a placeholder title</h3>

        <b-table
            :items="reportedUsers"
            :fields="reportedUserFields"
            class="reported-user-table"
            striped
        >
            <template #cell(details)="row">
                <b-button @click="row.toggleDetails">placeholder details</b-button>
            </template>
            <template #row-details="row">
                <ReportedUserDetails :user="row.item" />
            </template>
        </b-table>
    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import ReportedUserDetails from '../../../components/small/ReportedUserDetails.vue';
import {REPORTED_USER_FIELDS} from '../../../constants/reportedUserConstants.js';
export default {
    components: {
        ReportedUserDetails,
    },
    computed: {
        ...mapGetters({
            reportedUsers: 'admin/getReportedUsers',
        }),
    },
    data() {
        return {
            reportedUserFields: REPORTED_USER_FIELDS,
        }
    },
}
</script>
