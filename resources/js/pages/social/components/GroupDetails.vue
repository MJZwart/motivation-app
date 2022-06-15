<template>
    <div>
        <div class="details">
            <div class="row">
                <div class="col-8">
                    <div class="row mb-1">
                        <div class="col-2"><b>{{ $t('admin') }}: </b></div>
                        <div class="col-4">
                            <router-link :to="{ name: 'profile', params: { id: group.admin.id}}">
                                {{group.admin.username}}
                            </router-link>
                        </div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-2"><b>{{ $t('founded') }}: </b></div>
                        <div class="col-4">{{parseDateTime(group.time_created)}}</div>
                    </div>
                    <div class="row mb-1">
                        <div class="col-2"><b>{{ $t('members') }}:</b></div>
                        <div class="col-4">{{group.members.length}}</div>
                    </div>
                </div>
                <div class="col-3">
                    <div v-if="isJoinGroupVisible" class="row">
                        <button v-if="!group.require_application" type="button" @click="joinGroup()">{{$t('join-group')}}</button>
                        <button v-if="group.require_application" type="button" @click="applyToGroup()">
                            {{$t('apply-to-group')}}
                        </button>
                    </div>
                    <div v-else class="row">
                        <div v-if="isUserAdmin">
                            <button type="button" @click="deleteGroup()">{{ $t('delete-group') }}</button>
                            <button type="button" @click="manageGroup()">{{ $t('manage-group') }}</button>
                            <button type="button" @click="manageApplications()">{{ $t('manage-group-applications') }}</button>
                        </div>
                        <div v-else>
                            <button type="button" @click="leaveGroup()">{{ $t('leave-group') }}</button>
                        </div>
                    </div>
                </div>
            </div>
            <hr />
            <div class="row ml-0">
                <b class="mb-3">{{ $t('member-list') }}</b>
            </div>
            <div class="row">
                <div class="col">
                    <div v-for="member in group.members" :key="member.id" class="row">
                        <div class="col-2">{{member.username}}</div>
                        <div class="col-2">{{member.rank}}</div>
                        <div class="col-5">Joined: {{parseDateTime(member.joined, true)}}</div>
                    </div>
                </div>
            </div>
        </div>
        <Modal class="xl" :show="showManageGroupModal" :footer="false" 
               :title="group.name" @close="closeManageGroup">
            <ManageGroupModal :group="group" />
        </Modal>
        <Modal class="xl" :show="showManageApplicationsModal" :footer="false"
               :title="group.name" @close="closeManageApplications">
            <ManageApplicationsModal :group="group" @reloadGroups="emitReloadGroups()"/>    
        </Modal>
    </div>
</template>

<script setup>
import {computed, ref} from 'vue';
import ManageGroupModal from './ManageGroupModal.vue';
import ManageApplicationsModal from './ManageApplicationsModal.vue';
import {parseDateTime} from '/js/services/timezoneService';
import {useI18n} from 'vue-i18n'
const {t} = useI18n() // use as global scope
import {useGroupStore} from '/js/store/groupStore';
const groupStore = useGroupStore();

const props = defineProps({
    group: {
        type: Object,
        required: true,
    },
    user: {
        type: Object,
        required: true,
    },
});

const emit = defineEmits(['close', 'reloadGroups']);

const isJoinGroupVisible = computed(() => {
    let $isVisible = true;
    for (const member of props.group.members) {
        if (member.id == props.user.id) $isVisible = false;
    }
    return $isVisible;
});
const showManageGroupModal = ref(false);
const isUserAdmin = computed(() => {
    return props.group.admin.id == props.user.id;
});

async function joinGroup() {
    await groupStore.joinGroup(props.group)
    emit('reloadGroups');
    emit('close');
}

async function applyToGroup() {
    await groupStore.applyToGroup(props.group);
    emit('reloadGroups');
    emit('close');
}
async function deleteGroup() {
    if (confirm(t('delete-group-confirm', {group: props.group.name})))
        await groupStore.deleteGroup(props.group)
    emit('reloadGroups');
    emit('close');
}
async function leaveGroup() {
    if (confirm(t('leave-group-confirm', {group: props.group.name})))
        await groupStore.leaveGroup(props.group)
    emit('reloadGroups');
    emit('close');
}
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

function emitReloadGroups() {
    emit('reloadGroups');
}
</script>

<style lang="scss" scoped>
.details{
    margin: 0px 15px 0px 15px;
}
</style>