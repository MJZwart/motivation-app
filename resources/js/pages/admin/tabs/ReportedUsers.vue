<template>
    <div>
        <h3>{{ $t('reported-users') }}</h3>
        
        <Table
            :items="reportedUsers"
            :fields="reportedUserFields"
            :options="['table-striped', 'page-wide']"
        >
            <template #actions="row">
                <Tooltip :text="$t('show-details')">
                    <FaIcon 
                        icon="magnifying-glass" 
                        class="icon"
                        @click="showReportedUserDetails(row.item)">
                        {{ $t('show-details') }}
                    </FaIcon>                        
                </Tooltip>
                <Tooltip v-if="!currentlyBanned(row.item)" :text="$t('suspend-user')">
                    <FaIcon 
                        icon="ban" 
                        class="icon red"
                        @click="suspendUser(row.item)" />                        
                </Tooltip>
            </template>
            <template #banned="row">
                <div v-if="row.item.banned">
                    <div v-for="(banned, index) in row.item.banned" :key="index" class="banned-details">
                        <hr v-if="index > 0" />
                        <p>{{banned.created_at}} ({{banned.time_since}})</p>
                        <p>{{$t('banned-by')}}: {{banned.admin.username}}</p>
                        <p>{{$t('reason')}}: {{banned.reason}}</p>
                        <p>{{banned.days}} {{$t('days')}}</p>
                        <p>{{$t('banned-until')}}: {{banned.banned_until}} ({{banned.banned_until_time}})</p>
                    </div>
                </div>
            </template>
        </Table>
        <Modal 
            :show="showReportedUserDetailsModal" 
            :footer="false" 
            :title="reportedUserDetailsTitle" 
            class="l"
            @close="closeReportedUserDetails">
            <ReportedUserDetails :user="reportedUserDetails" />
        </Modal>
        <Modal
            :show="showSuspendUserModal"
            :footer="false"
            :title="suspendUserTitle"
            @close="closeSuspendUserModal">
            <SuspendUserModal 
                :userId="suspendedUser.id" 
                @close="closeSuspendUserModal" />
        </Modal>
    </div>
</template>

<script setup>
import Table from '/js/components/global/Table.vue';
import {ref, computed} from 'vue';
import ReportedUserDetails from './../components/ReportedUserDetails.vue';
import SuspendUserModal from './../components/SuspendUserModal.vue';
import {DateTime} from 'luxon';
import {REPORTED_USER_FIELDS} from '/js/constants/reportUserConstants.js';
import {useAdminStore} from '/js/store/adminStore';
const adminStore = useAdminStore();
import {useMainStore} from '/js/store/store';
const mainStore = useMainStore();

const reportedUserDetails = ref(null);
const reportedUserFields = ref(REPORTED_USER_FIELDS);
const reportedUserDetailsTitle = computed(() => {
    const username = reportedUserDetails.value ? reportedUserDetails.value.username : '';
    return `Reported user ${username}`;
});

const suspendedUser = ref(null);
const suspendUserTitle = computed(() => {
    const username = suspendedUser.value ? suspendedUser.value.username: '';
    return `Suspend user ${username}`;
});

const showReportedUserDetailsModal = ref(false);
const showSuspendUserModal = ref(false);

const reportedUsers = computed(() => adminStore.reportedUsers);

/** Opens the modal to show details on the user report */
function showReportedUserDetails(item) {
    reportedUserDetails.value = item;
    showReportedUserDetailsModal.value = true;
}
function closeReportedUserDetails() {
    showReportedUserDetailsModal.value = false;
}

/** Opens the modal to suspend a user account */
function suspendUser(item) {
    mainStore.clearErrors();
    suspendedUser.value = item;
    showSuspendUserModal.value = true;
}
function closeSuspendUserModal() {
    showSuspendUserModal.value = false;
}

function currentlyBanned(user) {
    return !!user.banned_until && DateTime.now() < DateTime.fromFormat(user.banned_until, 'yyyy-MM-dd HH:mm:ss')
}
</script>

<style lang="scss" scoped>
.banned-details {
    p{
        margin: 0.1rem;
    }
}
</style>