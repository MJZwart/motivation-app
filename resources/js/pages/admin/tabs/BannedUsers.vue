<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <h3>{{ $t('banned-users') }}</h3>

            <Table
                v-if="bannedUsers"
                :items="bannedUsers"
                :fields="bannedUsersFields"
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
                <template #banned_until="row">
                    <span v-if="row.item.early_release">
                        {{ parseDateTime(row.item.early_release) }}
                    </span>
                    <span v-else>
                        {{ parseDateTime(row.item.banned_until) }}
                    </span>
                    <span v-if="banEnded(row.item)">
                        <Tooltip :text="$t('ban-ended')">
                            <FaIcon icon="lock-open" class="icon small green" />
                        </Tooltip>
                    </span>
                </template>
                <template #log="row">
                    {{ row.item.ban_edit_log }}
                    {{ row.item.ban_edit_comment }}
                </template>
                <template #actions="row">
                    <div v-if="!banEnded(row.item)">
                        <Tooltip :text="$t('edit-ban')">
                            <FaIcon :icon="['far', 'pen-to-square']" class="icon" @click="editBan(row.item)" />
                        </Tooltip>
                    </div>
                </template>
            </Table>
            <Modal :show="showEditBanModal" :footer="false" :title="editBanModalTitle" @close="closeEditBanModal">
                <EditBan v-if="editBanUser" :userBan="editBanUser" @close="closeEditBanModal" />
            </Modal>
        </div>
    </div>
</template>

<script setup lang="ts">
import {computed, onMounted, ref} from 'vue';
import {DateTime} from 'luxon';
import Table from '/js/components/global/Table.vue';
import EditBan from '../components/EditBan.vue';
import {parseDateTime} from '/js/services/dateService';
import {BANNED_USERS_FIELDS} from '/js/constants/reportUserConstants';
import {useAdminStore} from '/js/store/adminStore';
import {useMainStore} from '/js/store/store';
import {BannedUser} from 'resources/types/user';
const adminStore = useAdminStore();
const mainStore = useMainStore();

onMounted(async () => {
    await adminStore.getBannedUsers();
    loading.value = false;
});

const loading = ref(true);

const bannedUsers = computed(() => adminStore.bannedUsers);
const bannedUsersFields = BANNED_USERS_FIELDS;

const showEditBanModal = ref(false);
const editBanUser = ref<BannedUser | null>(null);
const editBanModalTitle = computed(() => {
    if (!editBanUser.value) return;
    return `Edit ban of ${editBanUser.value.user.username}`;
});

function editBan(bannedUser: BannedUser) {
    mainStore.clearErrors();
    editBanUser.value = bannedUser;
    showEditBanModal.value = true;
}
function closeEditBanModal() {
    showEditBanModal.value = false;
}

function banEnded(bannedUser: BannedUser) {
    return (
        !!bannedUser.early_release ||
        DateTime.now() > DateTime.fromFormat(bannedUser.banned_until, 'yyyy-MM-dd, HH:mm:ss')
    );
}
</script>
