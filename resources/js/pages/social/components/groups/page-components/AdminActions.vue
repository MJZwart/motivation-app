<template>
    <div>
        <div class="content-block">
            <h4>{{ $t('manage-group')}}</h4>
            <form @submit.prevent>
                <label for="edit-name-comp">{{$t('name')}}</label>
                <Editable 
                    id="edit-name-comp" 
                    :key="group.name" 
                    class="ml-1 mb-2"
                    :item="group.name" 
                    :index="1" 
                    :name="'name'" 
                    @save="save" />
                
                <label for="edit-description-comp">{{$t('description')}}</label>
                <Editable 
                    id="edit-description-comp"
                    :key="group.description"
                    class="ml-1 mb-2"
                    :item="group.description" 
                    :index="2" 
                    name="description" 
                    type="textarea"
                    :rows=3
                    @save="save" />

                <button class="m-1" @click="togglePublic">
                    {{group.is_public ? 'Set to private' : 'Make public' }}
                </button>
                <button v-if="group.is_public" class="m-1" @click="toggleRequireApplication()">
                    {{group.require_application ? 'Do not require application' : 'Require application' }}
                </button>
                <!-- TODO turn this into a 'turn off button' -->
            </form>
        </div>
        <div v-if="group.require_application" class="content-block">
            <h4>{{ $t('manage-group-applications') }}</h4>
            <Loading v-if="loading" />
            <template v-for="application in applications" :key="application.id">
                <p>
                    {{`${application.username}, applied on: ${application.applied_at}`}}
                    <button class="m-1" @click="acceptApplication(application.id)">
                        {{ $t('accept-group-application') }}
                    </button>
                    <button class="m-1" @click="rejectApplication(application.id)">
                        {{ $t('reject-group-application') }}
                    </button>
                    <button class="m-1" @click="suspendApplication(application.id)">
                        {{ $t('suspend-group-application') }}
                    </button>
                </p>
            </template>
            <div v-if="!loading && applications && applications.length < 1">
                {{$t('no-applications')}}
            </div>
        </div>
        <div class="d-flex m-2">
            <div v-if="group.rank == 'admin'">
                <button type="button" class="m-1 box-shadow" @click="deleteGroup()">{{ $t('delete-group') }}</button>
                <button type="button" class="m-1 box-shadow" @click="inviteUsers()">{{ $t('invite-users') }}</button>
                <button type="button" class="m-1 box-shadow" @click="showBlocklist()">{{$t('blocklist')}}</button>
            </div>
        </div>
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
import InviteUsersModal from '../modals/InviteUsersModal.vue';
import Blocklist from '../modals/Blocklist.vue';
import {useGroupStore} from '/js/store/groupStore';
import {useRouter} from 'vue-router';
import {useI18n} from 'vue-i18n';
import {Application, Group, GroupPage} from 'resources/types/group';
import Editable from '/js/components/global/Editable.vue';

const groupStore = useGroupStore();
const router = useRouter();
const {t} = useI18n();

onBeforeMount(() => {
    if (props.group.require_application) loadApplications();
});

async function loadApplications() {
    loading.value = true;
    applications.value = await groupStore.fetchApplications(props.group.id);
    loading.value = false;
}

const props = defineProps({
    group: {
        type: Object as PropType<GroupPage>,
        required: true,
    },
});

const applications = ref<Array<Application> | null>(null);

const loading = ref(true);

async function deleteGroup() {
    if (props.group === null) return;
    if (confirm(t('delete-group-confirm', {group: props.group.name}))) {
        await groupStore.deleteGroup(props.group.id);
        router.push('/social#Groups');
    }
}

/**
 * Manage group settings
 */
function save(group: Group) {
    group.id = props.group.id;
    groupStore.updateGroup(group);
}
function togglePublic() {
    const group = {} as Group;
    group.is_public = !props.group.is_public;
    group.id = props.group.id;
    groupStore.updateGroup(group);
}

function toggleRequireApplication() {
    const group = {} as Group;
    group.require_application = !props.group.require_application;
    group.id = props.group.id;
    groupStore.updateGroup(group);
    if (group.require_application) loadApplications();
}

/**
 * Manage applications
 */
async function rejectApplication(applicationId: number) {
    await groupStore.rejectApplication(applicationId, props.group.id);
    loadApplications();
}

async function acceptApplication(applicationId: number) {
    await groupStore.acceptApplication(applicationId, props.group.id);
    loadApplications();
}

async function suspendApplication(applicationId: number) {
    await groupStore.suspendApplication(applicationId, props.group.id);
    loadApplications();
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