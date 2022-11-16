<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <h3>{{ $t('suspended-users') }}</h3>

            <Table
                v-if="suspendedUsers"
                :items="suspendedUsers"
                :fields="suspendedUsersFields"
                :options="['table-striped', 'page-wide', 'body-borders']"
            >
                <template #created_at="row">
                    {{ parseDateTime(row.item.created_at) }}
                </template>
                <template #user="row">
                    <router-link :to="{name: 'profile', params: {id: row.item.user.id}}">
                        {{ row.item.user.username }}
                    </router-link>
                </template>
                <template #admin="row">
                    <router-link :to="{name: 'profile', params: {id: row.item.admin.id}}">
                        {{ row.item.admin.username }}
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
                            <FaIcon icon="lock-open" class="icon small green" />
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
                            <FaIcon :icon="['far', 'pen-to-square']" class="icon" @click="editSuspension(row.item)" />
                        </Tooltip>
                    </div>
                </template>
            </Table>
            <Modal :show="showEditSuspensionModal" :title="editSuspensionModalTitle" @close="closeEditSuspensionModal">
                <EditSuspension 
                    v-if="editSuspensionUser" 
                    :userSuspension="editSuspensionUser" 
                    @close="closeEditSuspensionModal" />
            </Modal>
        </div>
    </div>
</template>

<script setup lang="ts">
import {computed, onMounted, ref} from 'vue';
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
const adminStore = useAdminStore();
const mainStore = useMainStore();
const {t} = useI18n();

onMounted(async () => {
    await adminStore.getSuspendedUsers();
    loading.value = false;
});

const loading = ref(true);

const suspendedUsers = computed(() => adminStore.suspendedUsers);
const suspendedUsersFields = SUSPENDED_USERS_FIELDS;

const showEditSuspensionModal = ref(false);
const editSuspensionUser = ref<SuspendedUser | null>(null);
const editSuspensionModalTitle = computed(() => {
    if (!editSuspensionUser.value) return;
    return t('edit-suspension-of', {user: editSuspensionUser.value.user.username});
});

function editSuspension(suspendedUser: SuspendedUser) {
    mainStore.clearErrors();
    editSuspensionUser.value = suspendedUser;
    showEditSuspensionModal.value = true;
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