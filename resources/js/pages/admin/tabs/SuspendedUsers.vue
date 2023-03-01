<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <h3>{{ $t('suspended-users') }}</h3>

            <button @click="showUnsuspended = !showUnsuspended">
                {{ showUnsuspended ? $t('hide-unsuspended') : $t('show-unsuspended') }}
            </button>
            <Table
                v-if="filteredSuspendedUsers"
                :items="filteredSuspendedUsers"
                :fields="suspendedUsersFields"
                :options="['table-striped', 'page-wide', 'body-borders', 'table-hover']"
                sort="created_at" 
                :sortAsc="false"
            >
                <template #created_at="row">
                    <span v-if="suspensionEnded(row.item)">
                        <Tooltip :text="$t('suspension-ended')">
                            <Icon :icon="UNLOCK" class="unlock-icon small green" />
                        </Tooltip>
                    </span>
                    {{ parseDateTime(row.item.created_at) }}
                </template>
                <template #username="row">
                    <router-link :to="{name: 'profile', params: {id: row.item.user_id}}">
                        {{ row.item.username }}
                    </router-link>
                </template>
                <template #admin_username="row">
                    <router-link :to="{name: 'profile', params: {id: row.item.admin_id}}">
                        {{ row.item.admin_username }}
                    </router-link>
                </template>
                <template #suspended_until="row">
                    <span v-if="row.item.early_release">
                        {{ parseDateTime(row.item.early_release) }}
                    </span>
                    <span v-else>
                        {{ parseDateTime(row.item.suspended_until) }}
                    </span>
                    <span v-if="suspensionEnded(row.item)">
                        <Tooltip :text="$t('suspension-ended')">
                            <Icon :icon="UNLOCK" class="unlock-icon small green" />
                        </Tooltip>
                    </span>
                </template>
                <template #log="row">
                    <div 
                        v-for="(log, index) in parseJsonIntoLog(row.item.suspension_edit_log)" 
                        :key="index" 
                        class="suspension-logs"
                    >
                        <span><b>{{ $t('date') }}: </b> {{log.date}}</span>
                        <span><b>{{ $t('log') }}: </b> {{log.log}}</span>
                        <span><b>{{ $t('comment') }}: </b> {{log.comment}}</span>
                    </div>
                </template>
                <template #actions="row">
                    <div v-if="!suspensionEnded(row.item)">
                        <Tooltip :text="$t('edit-suspension')">
                            <Icon :icon="EDIT" class="edit-icon" @click="editSuspension(row.item)" />
                        </Tooltip>
                    </div>
                </template>
            </Table>
            <Modal :show="showEditSuspensionModal" :title="editSuspensionModalTitle" @close="closeEditSuspensionModal">
                <EditSuspension 
                    v-if="editSuspensionUser" 
                    :userSuspension="editSuspensionUser" 
                    @close="closeEditSuspensionModal"
                    @submit="submitEditSuspension" />
            </Modal>
        </div>
    </div>
</template>

<script setup lang="ts">
import {computed, onActivated, ref} from 'vue';
import {DateTime} from 'luxon';
import Table from '/js/components/global/Table.vue';
import EditSuspension from '../components/EditSuspension.vue';
import {parseDateTime} from '/js/services/dateService';
import {SUSPENDED_USERS_FIELDS} from '/js/constants/reportUserConstants';
import {useAdminStore} from '/js/store/adminStore';
import {useMainStore} from '/js/store/store';
import {SuspendedUser} from 'resources/types/user';
import {useI18n} from 'vue-i18n';
import type {SuspensionLog} from 'resources/types/user';
import {EDIT, UNLOCK} from '/js/constants/iconConstants';
const adminStore = useAdminStore();
const mainStore = useMainStore();
const {t} = useI18n();

onActivated(async () => {
    suspendedUsers.value = await adminStore.getSuspendedUsers();
    loading.value = false;
});

const loading = ref(true);

const suspendedUsers = ref<SuspendedUser[]>([]);
const filteredSuspendedUsers = computed(() => {
    if (!suspendedUsers.value[0]) return;
    if (showUnsuspended.value) return suspendedUsers.value;
    return suspendedUsers.value.filter(user => !user.past);
})
const suspendedUsersFields = SUSPENDED_USERS_FIELDS;

const showUnsuspended = ref(false);

const showEditSuspensionModal = ref(false);
const editSuspensionUser = ref<SuspendedUser | null>(null);
const editSuspensionModalTitle = computed(() => {
    if (!editSuspensionUser.value) return;
    return t('edit-suspension-of', {user: editSuspensionUser.value.username});
});

function editSuspension(suspendedUser: SuspendedUser) {
    mainStore.clearErrors();
    editSuspensionUser.value = suspendedUser;
    showEditSuspensionModal.value = true;
}
async function submitEditSuspension(suspendedUser: SuspendedUser) {
    suspendedUsers.value = await adminStore.editSuspension(suspendedUser);
    closeEditSuspensionModal();
}
function closeEditSuspensionModal() {
    showEditSuspensionModal.value = false;
}

function suspensionEnded(suspendedUser: SuspendedUser) {
    return (
        !!suspendedUser.early_release ||
        DateTime.now() > DateTime.fromFormat(suspendedUser.suspended_until, 'yyyy-MM-dd, HH:mm:ss')
    );
}
function parseJsonIntoLog(jsonString: string): SuspensionLog[] {
    if (!jsonString) return [];
    const changesArr = JSON.parse(jsonString);
    return changesArr;
}
</script>

<style lang="scss">
.suspension-logs {
    margin-bottom: 0.5rem;
    span {
        display: block;
    }
}
</style>