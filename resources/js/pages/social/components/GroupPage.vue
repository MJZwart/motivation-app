<template>
    <div>
        <Loading v-if="loading || group == null" />
        <div v-else class="w-60-flex center p-1">
            <h2>{{group.name}}</h2>
            <div class="content-block">
                <p><b>{{ $t('admin')}}</b>: {{group.admin.username}}</p>
                <p><b>{{ $t('description')}}</b>: {{group.description}}</p>
                <p><b>{{ $t('founded')}}</b>: 
                    {{parseDateTime(group.time_created)}} ({{daysSince(group.time_created.toString())}})</p>
                <p>
                    {{group.is_public ? $t('public') : $t('private')}}
                    {{group.is_public && group.require_application ? ': ' + $t('requires-application') : ''}}
                </p>
            </div>
            <div v-if="group.is_member" class="content-block">
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
                </div>
                <div v-if="group.rank == 'member'">
                    <button type="button" class="m-1 box-shadow" @click="leaveGroup()">{{ $t('leave-group') }}</button>
                </div>
                <div v-if="!group.is_member">
                    <button 
                        v-if="!group.require_application" 
                        type="button" 
                        class="m-1 box-shadow" 
                        @click="joinGroup()">
                        {{$t('join-group')}}
                    </button>
                    <button 
                        v-if="group.require_application" 
                        type="button" 
                        class="m-1 box-shadow" 
                        :disabled="group.has_application"
                        @click="applyToGroup()">
                        {{ group.has_application ? $t('application-pending') : $t('apply-to-group')}}
                    </button>
                </div>
            </div>
            <div class="content-block">
                <p><b>{{ $t('members')}}</b>:</p>
                <div class="row">
                    <b class="col">{{ $t('username')}}</b>
                    <b class="col">{{ $t('rank')}}</b>
                    <b class="col">{{ $t('member-for')}}</b>
                </div>
                <div v-for="(member, index) in group.members" :key="index" class="row">
                    <span class="col">{{member.username}}</span>
                    <span class="col"><FaIcon :icon="member.rank == 'admin' ? 'angles-down' : 'angle-down'" />
                        {{member.rank}}</span>
                    <span class="col">{{daysSince(member.joined.toString())}}</span>
                </div>
            </div>
            <Modal class="xl" :show="showManageGroupModal" :footer="false" 
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
        </div>
    </div>
</template>

<script setup lang="ts">
import {onBeforeMount, ref, computed} from 'vue';
import ManageGroupModal from './ManageGroupModal.vue';
import ManageApplicationsModal from './ManageApplicationsModal.vue';
import InviteUsersModal from './InviteUsersModal.vue';
import {daysSince, parseDateTime} from '../../../services/dateService';
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

const loading = ref(true);
const group = computed((): GroupPage | null => groupStore.group);

async function joinGroup() {
    if (group.value === null) return;
    loading.value = true;
    await groupStore.joinGroup(group.value)
    loading.value = false;
}
async function applyToGroup() {
    if (group.value === null) return;
    loading.value = true;
    await groupStore.applyToGroup(group.value);
    loading.value = false;
}
async function deleteGroup() {
    if (group.value === null) return;
    if (confirm(t('delete-group-confirm', {group: group.value.name}))) {
        await groupStore.deleteGroup(group.value);
        router.push('/social#Groups');
    }
}
async function leaveGroup() {
    if (group.value === null) return;
    if (confirm(t('leave-group-confirm', {group: group.value.name}))) {
        loading.value = true;
        await groupStore.leaveGroup(group.value)
        loading.value = false;
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
</script>