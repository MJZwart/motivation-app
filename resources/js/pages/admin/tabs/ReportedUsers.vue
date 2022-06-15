<template>
    <div>
        <Loading v-if="loading" />
        <div v-else class="reported-users-overview">
            <h3>{{ $t('reported-users') }}</h3>
            
            <div class="header-row row">
                <span class="col fg-1 header clickable" @click="setSort('username')">
                    {{ $t('username') }}
                    <FaIcon 
                        icon="sort"  />
                </span>
                <span class="fg-1 col header clickable" @click="setSort('report_amount')">
                    {{ $t('number-of-reports') }}
                    <FaIcon 
                        icon="sort"  />
                </span>
                <span class="col header clickable" @click="setSort('last_report_date')">
                    {{ $t('last-report') }}
                    <FaIcon 
                        icon="sort"  />
                </span>
                <span class="col header clickable" @click="setSort('banned_until')">
                    {{ $t('banned-until') }}
                    <FaIcon 
                        icon="sort"  />
                </span>
                <span class="fg-1 col header">
                    {{ $t('actions') }}
                </span>
            </div>

            <template v-for="(user, index) in sortedReportedUsers" :key="index">
                <div class="detailed-table">
                    <div class="row top-row">
                        <div class="col fg-1">
                            {{user.username}}
                        </div>
                        <div class="col fg-1">
                            <FaIcon 
                                class="expand-button"
                                icon="caret-down" 
                                @click="showReportDetails(index)" />
                            {{user.report_amount}}
                        </div>
                        <div class="col">
                            {{parseDateTime(user.last_report_date)}}
                        </div>
                        <div class="col">
                            <FaIcon 
                                v-if="user.banned"
                                class="expand-button"
                                icon="caret-down" 
                                @click="showBannedDetails(index)" />
                            {{user.banned ? user.banned[0].banned_until_time : 'Never'}}
                        </div>
                        <div class="fg-1 col">
                            <Tooltip v-if="!currentlyBanned(user)" :text="$t('suspend-user')">
                                <FaIcon 
                                    icon="ban" 
                                    class="icon red"
                                    @click="suspendUser(user)" />                        
                            </Tooltip>
                            <Tooltip :text="$t('message-reported-user')">
                                <FaIcon 
                                    icon="envelope"
                                    class="icon"
                                    @click="sendMessageToReportedUser(user)" />
                            </Tooltip>
                        </div>
                    </div>
                    <!-- Banned overview -->
                    <div 
                        v-if="user.banned"
                        :ref="el => { bannedDivs[index] = el }" class="sub-details row" 
                        :style="{'max-height': '0px', transition: 'max-height 2s'}">
                        <h5>{{ $t('bans') }}</h5>
                        <div class="row">
                            <span class="col header">{{ $t('start-ban') }}</span>
                            <span class="col header">{{ $t('end-ban') }}</span>
                            <span class="col header">{{ $t('reason') }}</span>
                            <span class="col fg-1 header">{{ $t('days') }}</span>
                            <span class="col fg-1 header">{{ $t('banned-by') }}</span>
                        </div>
                        <template v-for="(banned, index) in user.banned" :key="index">
                            <div class="row">
                                <div class="col">
                                    {{parseDateTime(banned.created_at)}} ({{banned.time_since}})
                                </div>
                                <div class="col">
                                    {{banned.early_release ? 
                                        parseDateTime(banned.early_release) : parseDateTime(banned.banned_until)}} 
                                    ({{banned.banned_until_time}})
                                </div>
                                <div class="col">
                                    {{banned.reason}}
                                </div>
                                <div class="col fg-1">
                                    {{banned.days}}
                                </div>
                                <div class="col fg-1">
                                    {{banned.admin.username}}
                                </div>
                            </div>
                        </template>
                    </div>
                    <!-- Reports overview -->
                    <div 
                        :ref="el => { reportDivs[index] = el }" class="sub-details row" 
                        :style="{'max-height': '0px'}">
                        <h5>{{ $t('reports') }}</h5>
                        <div class="row">
                            <span class="col fg-1 header">{{ $t('reason') }}</span>
                            <span class="col header">{{ $t('comment') }}</span>
                            <span class="col header">{{ $t('date') }}</span>
                            <span class="col header">{{ $t('reported-by') }}</span>
                            <span class="col fg-1 header">{{ $t('conversation') }}</span>
                        </div>
                        <template v-for="(report, index) in user.reports" :key="index">
                            <div class="row">
                                <div class="col fg-1">
                                    {{parseReason(report.reason)}}
                                </div>
                                <div class="col">
                                    {{report.comment}}
                                </div>
                                <div class="col">
                                    {{parseDateTime(report.reported_date)}}
                                </div>
                                <div class="col">
                                    {{report.reported_by_name}}
                                </div>
                                <div class="col fg-1">
                                    <div v-if="report.conversation">
                                        {{ $t('yes')}}
                                        <Tooltip :text="$t('show-conversation')">
                                            <FaIcon 
                                                icon="magnifying-glass" 
                                                class="icon"
                                                @click="showConversation(report.conversation)" />
                                        </Tooltip>
                                    </div>
                                    <div v-else>
                                        {{ $t('no')}}
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
            <Modal :show="showConversationModal"
                   :footer="false" 
                   :title="$t('see-conversation-reporter')" 
                   @close="closeShowConversation">
                <ShowConversationModal :conversationId="conversationToShow" @close="closeShowConversation"/>
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
/* eslint-disable max-lines */
import {ref, computed, onMounted, onBeforeUpdate} from 'vue';
import SuspendUserModal from './../components/SuspendUserModal.vue';
import ShowConversationModal from '../components/ShowConversationModal.vue';
import SendMessage from '/js/pages/messages/components/SendMessage.vue';
import {parseDateTime} from '/js/services/dateService';
import {sortValues} from '/js/services/sortService';
import {DateTime} from 'luxon';
import {useAdminStore} from '/js/store/adminStore';
const adminStore = useAdminStore();
import {useMainStore} from '/js/store/store';
const mainStore = useMainStore();

onBeforeUpdate(() => {
    reportDivs.value = [];
    bannedDivs.value = [];
});

onMounted(async() => {
    await adminStore.getReportedUsers();
    loading.value = false;
});

const loading = ref(true);

const reportedUsers = computed(() => adminStore.reportedUsers);
const currentSort = ref('last_report_date');
const currentSortDir = ref('asc');

const sortedReportedUsers = computed(() => {
    return sortValues(reportedUsers.value, currentSort.value, currentSortDir.value);
});

function setSort(key) {
    if (currentSort.value == key) toggleSortDir();
    else currentSort.value = key;
}
function toggleSortDir() {
    if (currentSortDir.value == 'asc') currentSortDir.value = 'desc';
    else currentSortDir.value = 'asc';
}

function parseReason(reason) {
    let string = reason.replaceAll('_', ' ').toLowerCase();
    return string.charAt(0).toUpperCase() + string.slice(1);
}
/** Opens the modal to see a conversation between reported user and reporter */
const conversationToShow = ref(null);
const showConversationModal = ref(false);
function showConversation(conversationId) {
    mainStore.clearErrors();
    conversationToShow.value = conversationId;
    showConversationModal.value = true;
}
function closeShowConversation() {
    conversationToShow.value = null;
    showConversationModal.value = false;
}

/** Opens the modal to message a user that has been reported */
const userToMessage = ref(null);
const showSendMessageModal = ref(false);
function sendMessageToReportedUser() {
    mainStore.clearErrors();
    showSendMessageModal.value = true;
}
function closeSendMessageToReportedUser() {
    showSendMessageModal.value = false;
}

/** Opens the modal to suspend a user account */
const suspendedUser = ref(null);
const suspendUserTitle = computed(() => {
    const username = suspendedUser.value ? suspendedUser.value.username: '';
    return `Suspend user ${username}`;
});
const showSuspendUserModal = ref(false);
function suspendUser(item) {
    mainStore.clearErrors();
    suspendedUser.value = item;
    showSuspendUserModal.value = true;
}
function closeSuspendUserModal() {
    showSuspendUserModal.value = false;
}

const reportDivs = ref([]);
const bannedDivs = ref([]);

function showReportDetails(index) {
    if (reportDivs.value[index].style.maxHeight == '0px') reportDivs.value[index].style.maxHeight = 'max-content';
    else reportDivs.value[index].style.maxHeight = '0px';
}
function showBannedDetails(index) {
    if (bannedDivs.value[index].style.maxHeight == '0px') bannedDivs.value[index].style.maxHeight = 'max-content';
    else bannedDivs.value[index].style.maxHeight = '0px';
}
function currentlyBanned(user) {
    return !!user.banned_until && DateTime.now() < DateTime.fromFormat(user.banned_until, 'yyyy-MM-dd HH:mm:ss')
}
</script>

<style lang="scss" scoped>
@import '../../../../assets/scss/variables';
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
.header {
    font-family: $bold-font;
    display: block;
}
</style>