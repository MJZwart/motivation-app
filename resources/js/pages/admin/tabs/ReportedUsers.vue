<template>
    <div>
        <h3>this is a placeholder title</h3>
        
        <Table
            :items="reportedUsers"
            :fields="reportedUserFields"
            :options="['table-striped']"
        >
            <template #details="row">
                <button @click="showReportedUserDetails(row)">placeholder details</button>
            </template>
        </Table>
        <Modal 
            :show="showReportedUserDetailsModal" 
            :footer="false" 
            :title="reportedUserDetailsTitle" 
            class="l"
            @close="closeReportedUserDetails">
            <ReportedUserDetails :user="reportedUserDetails" :index="reportedUserIndex" />
            <!-- Ugly af -->
        </Modal>
    </div>
</template>

<script setup>
import Table from '/js/components/global/Table.vue';
import {ref, computed} from 'vue';
import ReportedUserDetails from './../components/ReportedUserDetails.vue';
import {REPORTED_USER_FIELDS} from '/js/constants/reportedUserConstants.js';
import {useAdminStore} from '/js/store/adminStore';
const adminStore = useAdminStore();

const reportedUserDetails = ref(null);
const reportedUserIndex = ref(null);
const reportedUserFields = ref(REPORTED_USER_FIELDS);
const reportedUserDetailsTitle = ref(null);
const showReportedUserDetailsModal = ref(false);

const reportedUsers = computed(() => adminStore.reportedUsers);

function showReportedUserDetails({item, index}) {
    reportedUserDetails.value = item;
    reportedUserIndex.value = index;
    reportedUserDetailsTitle.value = item.username;
    showReportedUserDetailsModal.value = true;
}
function closeReportedUserDetails() {
    showReportedUserDetailsModal.value = false;
}
</script>