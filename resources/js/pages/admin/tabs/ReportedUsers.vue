<template>
    <div>
        <h3>{{ $t('reported-users') }}</h3>
        
        <Table
            :items="reportedUsers"
            :fields="reportedUserFields"
            :options="['table-striped', 'page-wide']"
        >
            <template #details="row">
                <Tooltip :text="$t('show-details')">
                    <FaIcon 
                        icon="magnifying-glass" 
                        class="icon"
                        @click="showReportedUserDetails(row)">
                        {{ $t('show-details') }}
                    </FaIcon>                        
                </Tooltip>
            </template>
        </Table>
        <Modal 
            :show="showReportedUserDetailsModal" 
            :footer="false" 
            :title="reportedUserDetailsTitle" 
            class="l"
            @close="closeReportedUserDetails">
            <ReportedUserDetails :user="reportedUserDetails" :index="reportedUserIndex" />
        </Modal>
    </div>
</template>

<script setup>
import Table from '/js/components/global/Table.vue';
import {ref, computed} from 'vue';
import ReportedUserDetails from './../components/ReportedUserDetails.vue';
import {REPORTED_USER_FIELDS} from '/js/constants/reportUserConstants.js';
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