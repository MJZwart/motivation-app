<template>
    <div>
        <Loading v-if="loading" />

        <h3>{{ $t('reported-users')}}</h3>

        <SortableOverviewTable
            v-if="sortedReportedUsers"
            :items="sortedReportedUsers"
            :fields="REPORTED_USER_OVERVIEW_FIELDS"
            class="striped"
            click-to-extend>

            <template #username="item">
                <h5>{{item.item.username}}</h5>
            </template>
            <template #banned_until="item">
                {{item.item.banned ? item.item.banned[0].banned_until_time : 'Never'}}
            </template>
            <template #actions="item">
                <Tooltip v-if="!currentlyBanned(item.item)" :text="$t('suspend-user')">
                    <FaIcon 
                        icon="ban" 
                        class="icon red"
                        @click.stop.prevent="suspendUser(item.item)" />                        
                </Tooltip>
                <Tooltip :text="$t('message-reported-user')">
                    <FaIcon 
                        icon="envelope"
                        class="icon"
                        @click.stop.prevent="sendMessageToReportedUser(item.item)" />
                </Tooltip>
            </template>
            <template #extend="item">
                <ReportedUserDetails :user="item.item" />
            </template>
        </SortableOverviewTable>

        <Modal
            :show="showSuspendUserModal"
            :footer="false"
            :title="suspendUserTitle"
            @close="closeSuspendUserModal">
            <SuspendUserModal 
                v-if="suspendedUser !== null"
                :userId="suspendedUser.id" 
                @close="closeSuspendUserModal" />
        </Modal>
        <Modal 
            :show="showSendMessageModal" 
            :footer="false" 
            :header="false" 
            @close="closeSendMessageToReportedUser">
            <SendMessage v-if="userToMessage !== null" :user="userToMessage" @close="closeSendMessageToReportedUser"/>
        </Modal>
    </div>
</template>

<script setup lang="ts">
/* eslint-disable max-lines */
import {ref, computed, onMounted} from 'vue';
import SuspendUserModal from './../components/SuspendUserModal.vue';
import SendMessage from '/js/pages/messages/components/SendMessage.vue';
import SortableOverviewTable from '/js/components/global/SortableOverviewTable.vue';
import ReportedUserDetails from '../components/ReportedUsersDetails.vue';
import {sortValues} from '/js/services/sortService';
import {DateTime} from 'luxon';
import {useAdminStore} from '/js/store/adminStore';
import {useMainStore} from '/js/store/store';
import {REPORTED_USER_OVERVIEW_FIELDS} from '/js/constants/reportUserConstants';
import type {User, UserToBan} from 'resources/types/user';
const adminStore = useAdminStore();
const mainStore = useMainStore();

onMounted(async() => {
    await adminStore.getReportedUsers();
    loading.value = false;
});

const loading = ref(true);

const reportedUsers = computed(() => adminStore.reportedUsers);
const currentSort = ref('last_report_date');
const currentSortDir = ref('desc');

const sortedReportedUsers = computed(() => {
    if (reportedUsers.value === null) return;
    return sortValues(reportedUsers.value, currentSort.value, currentSortDir.value);
});

/** Opens the modal to message a user that has been reported */
const userToMessage = ref<User | null>(null);
const showSendMessageModal = ref(false);
function sendMessageToReportedUser(user: User) {
    mainStore.clearErrors();
    userToMessage.value = user;
    showSendMessageModal.value = true;
}
function closeSendMessageToReportedUser() {
    userToMessage.value = null;
    showSendMessageModal.value = false;
}

/** Opens the modal to suspend a user account */
const suspendedUser = ref<UserToBan | null>(null);
const suspendUserTitle = computed(() => {
    const username = suspendedUser.value ? suspendedUser.value.username: '';
    return `Suspend user ${username}`;
});
const showSuspendUserModal = ref(false);
function suspendUser(user: UserToBan) {
    mainStore.clearErrors();
    suspendedUser.value = user;
    showSuspendUserModal.value = true;
}
function closeSuspendUserModal() {
    showSuspendUserModal.value = false;
}

function currentlyBanned(user: UserToBan) {
    return !!user.banned_until && DateTime.now() < DateTime.fromFormat(user.banned_until, 'yyyy-MM-dd HH:mm:ss')
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