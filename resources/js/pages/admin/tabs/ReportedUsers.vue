<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <h3>{{ $t('reported-users') }}</h3>
            
            <template v-for="(user, index) in reportedUsers" :key="index">
                {{user}}
                <div class="detailed-table" @click="showDetails($event)">
                    <div class="row top-row">
                        <div class="col">
                            <span class="header">Username</span>
                            {{user.username}}
                        </div>
                        <div class="col">
                            <span class="header"># of Reports</span>
                            {{user.report_amount}}
                        </div>
                        <div class="col">
                            <span class="header">Last Report</span>
                            {{user.last_report_date}}
                        </div>
                        <div class="col">
                            <span class="header">Banned</span>
                            {{user.banned ? user.banned[0].banned_until : '-'}}
                            <!-- TODO turn this into a yes or no with the details below -->
                        </div>
                        <div class="ml-auto col-1">
                            <span class="header">Actions</span>
                            <Tooltip :text="$t('suspend-user')">
                                <FaIcon 
                                    icon="ban" 
                                    class="icon red"
                                    @click="suspendUser(row.item)" />                        
                            </Tooltip>
                            <Tooltip :text="$t('message-reported-user')">
                                <FaIcon 
                                    icon="envelope"
                                    class="icon"
                                    @click="sendMessageToReportedUser()" />
                            </Tooltip>
                        </div>
                    </div>
                    <!-- <div v-if="showDetails($event)" /> -->
                </div>
                <!-- <div class="detailed-table">
                    <div class="row top-row">
                        <div v-for="(field, index) in reportedUserFields" :key="index" class="col">
                            <span class="header">{{field.label}}</span>
                            <slot v-bind="{item, index}" :name="field.key">
                                {{item[field.key]}}
                            </slot>
                        </div>
                    </div>
                    <div class="row bottom-row">
                        <div v-for="(field, index) in reportedUserDetailsFields" :key="index" class="col">
                            <span class="header">{{field.label}}</span>
                            <slot v-bind="{item, index}" :name="field.key">
                                <span v-for="(detail, idx) in item[detailKey]" 
                                      :key="idx" class="body">
                                    {{detail[field.key]}}
                                </span>
                            </slot>
                        </div>
                    </div>
                </div> -->
                <!-- <div class="table-like-item">
                    <TableLikeComponent 
                        :item="user" 
                        :topRowFields="reportedUserFields" 
                        :bottomRowFields="reportedUserDetailsFields"
                        detailKey="reports">
                        <template #conversation="row"> 
                            <span v-for="(item, idx) in row.item.reports" :key="idx" class="body">
                                <div v-if="item.conversation">
                                    {{ $t('yes')}}
                                    <Tooltip :text="$t('show-conversation')">
                                        <FaIcon 
                                            icon="magnifying-glass" 
                                            class="icon"
                                            @click="showConversation(item.conversation)" />
                                    </Tooltip>
                                </div>
                                <div v-else>
                                    {{ $t('no')}}
                                </div>
                            </span>
                        </template>
                        <template #actions="row">
                            <Tooltip :text="$t('show-details')">
                                <FaIcon 
                                    icon="magnifying-glass" 
                                    class="icon"
                                    @click="showReportedUserDetails(row.item)">
                                    {{ $t('show-details') }}
                                </FaIcon>                        
                            </Tooltip>
                            <Tooltip :text="$t('suspend-user')">
                                <FaIcon 
                                    icon="ban" 
                                    class="icon red"
                                    @click="suspendUser(row.item)" />                        
                            </Tooltip>
                            <Tooltip :text="$t('message-reported-user')">
                                <FaIcon 
                                    icon="envelope"
                                    class="icon"
                                    @click="sendMessageToReportedUser()" />
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
                    </TableLikeComponent>
                </div> -->
            </template>
            <Modal :show="showConversationModal"
                   :footer="false" 
                   :title="$t('see-conversation-reporter')" 
                   @close="closeShowConversation">
                <ShowConversationModal :conversationId="conversationToShow" @close="closeShowConversation"/>
            </Modal>

            <!-- <Table
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
                    <Tooltip :text="$t('suspend-user')">
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
            </Table> -->
            <!-- <Modal 
                :show="showReportedUserDetailsModal" 
                :footer="false" 
                :title="reportedUserDetailsTitle" 
                class="l"
                @close="closeReportedUserDetails">
                <ReportedUserDetails :user="reportedUserDetails" />
            </Modal> -->
            <Modal
                :show="showSuspendUserModal"
                :footer="false"
                :title="suspendUserTitle"
                @close="closeSuspendUserModal">
                <SuspendUserModal 
                    :userId="suspendedUser.id" 
                    @close="closeSuspendUserModal" />
            </Modal>
            <Modal 
                :show="showSendMessageModal" 
                :footer="false" 
                :title="$t('send-message')" 
                @close="closeSendMessageToReportedUser">
                <SendMessage :user="userToMessage" @close="closeSendMessageToReportedUser"/>
            </Modal>
        </div>
    </div>
</template>

<script setup>
// import Table from '/js/components/global/Table.vue';
import TableLikeComponent from '/js/components/global/TableLikeComponent.vue';
import {ref, computed, onMounted} from 'vue';
// import ReportedUserDetails from './../components/ReportedUserDetails.vue';
import SuspendUserModal from './../components/SuspendUserModal.vue';
import {REPORTED_USER_FIELDS, REPORTED_USER_DETAILS_FIELDS} from '/js/constants/reportUserConstants.js';
import ShowConversationModal from '../components/ShowConversationModal.vue';
import SendMessage from '/js/pages/messages/components/SendMessage.vue';
import {useAdminStore} from '/js/store/adminStore';
const adminStore = useAdminStore();
import {useMainStore} from '/js/store/store';
const mainStore = useMainStore();

onMounted(async() => {
    await adminStore.getReportedUsers();
    loading.value = false;
});

const loading = ref(true);
const showConversationModal = ref(false);
const conversationToShow = ref(null);
const userToMessage = ref(null);

// const reportedUserDetails = ref(null);
const reportedUserFields = ref(REPORTED_USER_FIELDS);
const reportedUserDetailsFields = ref(REPORTED_USER_DETAILS_FIELDS);
// const reportedUserDetailsTitle = computed(() => {
//     const username = reportedUserDetails.value ? reportedUserDetails.value.username : '';
//     return `Reported user ${username}`;
// });

const suspendedUser = ref(null);
const suspendUserTitle = computed(() => {
    const username = suspendedUser.value ? suspendedUser.value.username: '';
    return `Suspend user ${username}`;
});

// const showReportedUserDetailsModal = ref(false);
const showSuspendUserModal = ref(false);

const reportedUsers = computed(() => adminStore.reportedUsers);


function showConversation(conversationId) {
    mainStore.clearErrors();
    conversationToShow.value = conversationId;
    showConversationModal.value = true;
}
function closeShowConversation() {
    conversationToShow.value = null;
    showConversationModal.value = false;
}
const showSendMessageModal = ref(false);

function sendMessageToReportedUser() {
    mainStore.clearErrors();
    showSendMessageModal.value = true;
}
function closeSendMessageToReportedUser() {
    showSendMessageModal.value = false;
}

/** Opens the modal to show details on the user report */
// function showReportedUserDetails(item) {
//     reportedUserDetails.value = item;
//     showReportedUserDetailsModal.value = true;
// }
// function closeReportedUserDetails() {
//     showReportedUserDetailsModal.value = false;
// }

/** Opens the modal to suspend a user account */
function suspendUser(item) {
    mainStore.clearErrors();
    suspendedUser.value = item;
    showSuspendUserModal.value = true;
}
function closeSuspendUserModal() {
    showSuspendUserModal.value = false;
}

function showDetails(event) {
    console.log(event);
}
</script>

<style lang="scss" scoped>
.banned-details {
    p{
        margin: 0.1rem;
    }
}
.table-like-item {
    margin-bottom: 0.5rem;
}
// .table-like-item:nth-of-type(2n+1) {
//     background-color: rgba(0, 0, 0, .05);
// }

.detailed-table{
    border: 1px solid grey;
    cursor: pointer;
    .row{
        background-color: white;
        box-shadow: 5px 5px 5px rgba(0, 0, 0, 0.25);
        margin-left: 0;
        padding: 0.3rem;
        .col, .col-1{
            padding-right: 5px;
            padding-left: 5px;
            .header {
                font-weight:600;
                display: block;
            }
            .body {
                display: block;
            }
        }
    }
}
</style>