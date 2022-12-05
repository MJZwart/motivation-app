<template>
    <div>
        <div class="content-block">
            <p><b>{{ $t('your-rank')}}</b>: 
                <FaIcon :icon="group.rank == 'admin' ? 'angles-down' : 'angle-down'" />
                {{group.rank}}
            </p>
            <p><b>{{ $t('joined')}}</b>: {{parseDateTime(group.joined)}} ({{daysSince(group.joined)}})</p>
        </div>
        <div class="d-flex m-2">
            <div v-if="group.rank == 'admin'">
                <button type="button" class="m-1 box-shadow" @click="deleteGroup()">{{ $t('delete-group') }}</button>
                <button type="button" class="m-1 box-shadow" @click="manageGroup()">{{ $t('manage-group') }}</button>
                <button type="button" class="m-1 box-shadow" @click="manageApplications()">
                    {{ $t('manage-group-applications') }}
                </button>
                <button type="button" class="m-1 box-shadow" @click="inviteUsers()">{{ $t('invite-users') }}</button>
                <button type="button" class="m-1 box-shadow" @click="showBlocklist()">{{$t('blocklist')}}</button>
            </div>
        </div>
        <Modal class="xl" :show="showManageGroupModal" 
               :title="group.name" @close="closeManageGroup">
            <ManageGroupModal :group="group" />
        </Modal>
        <Modal class="xl" :show="showManageApplicationsModal" :footer="false"
               :title="group.name" @close="closeManageApplications">
            <ManageApplicationsModal :group="group" />    
        </Modal>
        <Modal class="xl" :show="showInviteUsersModal" :footer="false"
               :title="$t('invite-users-to', {group: group.name})" @close="closeInviteUsersModal">
            <InviteUsersModal :group="group" />
        </Modal>
        <Modal class="l" :show="showBlocklistModal" :footer="false"
               :title="$t('blocklist')" @close="closeBlocklistModal">
            <Blocklist :group-id="group.id" />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import {onBeforeMount, ref, PropType} from 'vue';
import ManageGroupModal from '../ManageGroupModal.vue';
import ManageApplicationsModal from '../ManageApplicationsModal.vue';
import InviteUsersModal from '../InviteUsersModal.vue';
import Blocklist from '../Blocklist.vue';
import {daysSince, parseDateTime} from '/js/services/dateService';
import {useGroupStore} from '/js/store/groupStore';
import {useRoute, useRouter} from 'vue-router';
import {useI18n} from 'vue-i18n';
import {GroupPage} from 'resources/types/group';

const groupStore = useGroupStore();
const route = useRoute();
const router = useRouter();
const {t} = useI18n();

onBeforeMount(async() => {
    await groupStore.fetchGroup(parseInt(String(route.params.id)));
    loading.value = false;
});

const props = defineProps({
    group: {
        type: Object as PropType<GroupPage>,
        required: true,
    },
});

const loading = ref(true);

async function deleteGroup() {
    if (props.group === null) return;
    if (confirm(t('delete-group-confirm', {group: props.group.name}))) {
        await groupStore.deleteGroup(props.group.id);
        router.push('/social#Groups');
    }
}

const showManageGroupModal = ref(false);
function manageGroup() {
    showManageGroupModal.value = true;
}
function closeManageGroup() {
    showManageGroupModal.value = false;
}

const showManageApplicationsModal = ref(false);
function manageApplications() {
    showManageApplicationsModal.value = true;
}
function closeManageApplications() {
    showManageApplicationsModal.value = false;
}

const showInviteUsersModal = ref(false);
function inviteUsers() {
    showInviteUsersModal.value = true;
}
function closeInviteUsersModal() {
    showInviteUsersModal.value = false;
}

const showBlocklistModal = ref(false);
function showBlocklist() {
    showBlocklistModal.value = true;
}
function closeBlocklistModal() {
    showBlocklistModal.value = false;
}
</script>