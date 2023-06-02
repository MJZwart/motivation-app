<template>
    <div>
        <h3>{{ $t('reported-users')}}</h3>

        <Loading v-if="loading" />

        <SortableOverviewTable
            v-if="sortedReportedUsers && sortedReportedUsers[0]"
            :items="sortedReportedUsers"
            :fields="REPORTED_USER_OVERVIEW_FIELDS"
            class="striped"
            click-to-extend>

            <template #username="row">
                <h5>{{row.item.username}}</h5>
            </template>
            <template #suspended_until="row">
                {{row.item.suspended ? row.item.suspended[0].suspended_until_time : 'Never'}}
            </template>
            <template #actions="row">
                <Tooltip v-if="!currentlySuspended(row.item)" :text="$t('suspend-user')">
                    <Icon 
                        :icon="BAN" 
                        class="ban-icon red"
                        @click.stop.prevent="suspendUser(row.item.id)" />                        
                </Tooltip>
                <Tooltip :text="$t('message-reported-user')">
                    <Icon 
                        :icon="MAIL"
                        class="mail-icon"
                        @click.stop.prevent="sendMessageToReportedUser(row.item)" />
                </Tooltip>
            </template>
            <template #extend="row">
                <ReportedUserDetails :user="row.item" @close-report="closeReport" />
            </template>
        </SortableOverviewTable>
    </div>
</template>

<script setup lang="ts">
import type {NewSuspension, User, UserToSuspend} from 'resources/types/user';
import type {ReportedUser} from 'resources/types/admin';
import {ref, computed, onMounted} from 'vue';
import SuspendUserModal from './../components/SuspendUserModal.vue';
import SortableOverviewTable from '/js/components/global/SortableOverviewTable.vue';
import ReportedUserDetails from '../components/ReportedUsersDetails.vue';
import {sortValues} from '/js/services/sortService';
import {DateTime} from 'luxon';
import {useAdminStore} from '/js/store/adminStore';
import {REPORTED_USER_OVERVIEW_FIELDS} from '/js/constants/reportUserConstants';
import {BAN, MAIL} from '/js/constants/iconConstants';
import {formModal, sendMessageModal} from '/js/components/modal/modalService';
import {getNewSuspension} from '/js/helpers/newInstance';

const adminStore = useAdminStore();

onMounted(async() => {
    reportedUsers.value = await adminStore.getReportedUsers();
    loading.value = false;
});

const loading = ref(true);

const reportedUsers = ref<ReportedUser[]>([]);
const currentSort = ref('last_report_date');
const currentSortDir = ref('desc');

const sortedReportedUsers = computed(() => {
    if (reportedUsers.value === null) return;
    return sortValues(reportedUsers.value, currentSort.value, currentSortDir.value);
});

/** Opens the modal to message a user that has been reported */
function sendMessageToReportedUser(user: User) {
    sendMessageModal(user.username, user.id);
}

/** Opens the modal to suspend a user account */
function suspendUser(userId: number) {
    formModal(getNewSuspension(userId), SuspendUserModal, submitSuspendUser, 'suspend-user');
}
async function submitSuspendUser(userSuspension: NewSuspension) {
    reportedUsers.value = await adminStore.suspendUser(userSuspension);
}

async function closeReport(report: ReportedUser) {
    reportedUsers.value = await adminStore.closeReport(report);
}

function currentlySuspended(user: UserToSuspend) {
    return !!user.suspended_until && DateTime.now() < DateTime.fromFormat(user.suspended_until, 'yyyy-MM-dd HH:mm:ss')
}
</script>

<style lang="scss" scoped>

.detailed-table{
    border: 1px solid grey;
    box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.25);
    margin-bottom: 10px;
    .row{
        background-color: white;
        margin-left: 0;
        padding: 0.3rem;
        width: 100%;
        .col, .col-1{
            padding-right: 5px;
            padding-left: 5px;
            flex-grow: 2;
            .body {
                display: block;
            }
        }
    }
}
.sub-details { 
    max-height: 0px;
    transition: max-height 2s;
    overflow: hidden;
}
.expand-button {
    padding: 0.2rem;
    cursor: pointer;
    font-size: 1.5rem;
}
.header-row .col {
    flex-grow: 2;
}
.col.fg-1{
    flex-grow: 1 !important;
}

</style>