<template>
    <div>
        <h3>{{ $t('banned-users') }}</h3>

        <Table
            :items="bannedUsers"
            :fields="bannedUsersFields"
            :options="['table-striped', 'page-wide']"
        >
            <template #user="row">
                {{row.item.user.username}}
            </template>
            <template #admin="row">
                {{row.item.admin.username}}
            </template>
            <template #actions="row">
                <div v-if="!row.item.past">
                    <!-- <Tooltip :text="$t('unban')">
                        <FaIcon 
                            icon="lock-open" 
                            class="icon green"
                            @click="unban(row.item.id)" />  
                    </Tooltip> -->
                    <Tooltip :text="$t('edit-ban')">
                        <FaIcon 
                            :icon="['far', 'pen-to-square']"
                            class="icon"
                            @click="editBan(row.item)" />  
                    </Tooltip>
                </div>
            </template>
        </Table>
        <Modal 
            :show="showEditBanModal"
            :footer="false"
            :title="editBanModalTitle"
            @close="closeEditBanModal">
            <EditBan :userBan="editBanUser" />
        </Modal>
    </div>
</template>

<script setup>
import {computed, onMounted, ref} from 'vue';
import Table from '/js/components/global/Table.vue';
import EditBan from '../components/EditBan.vue';
import {BANNED_USERS_FIELDS} from '/js/constants/reportUserConstants';
// import {useI18n} from 'vue-i18n';
// const {t} = useI18n();
import {useAdminStore} from '/js/store/adminStore';
const adminStore = useAdminStore();

onMounted(async() => {
    bannedUsers.value = await adminStore.getBannedUsers();
})

const bannedUsers = ref([]);
const bannedUsersFields = BANNED_USERS_FIELDS;

const showEditBanModal = ref(false);
const editBanModalTitle = computed(() => {
    if (!editBanUser.value) return;
    return `Edit ban of ${editBanUser.value.user.username}`;
});
const editBanUser = ref(null);

// function unban(itemId) {
//     // if (confirm(t('end-ban-confirm'))) 
//     //     adminStore.unban(itemId);
//     editBanUser.value = bannedUser;
//     showEditBanModal.value = true;
// }

function editBan(bannedUser) {
    editBanUser.value = bannedUser;
    showEditBanModal.value = true;
}
function closeEditBanModal() {
    showEditBanModal.value = false;
}

</script>