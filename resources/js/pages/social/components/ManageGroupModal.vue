<template>
    <div>
        <div v-if="group">
            <form @submit.prevent>
                <label for="edit-name-comp">Name</label>
                <Editable 
                    id="edit-name-comp" 
                    :key="group.name" 
                    class="ml-1 mb-2"
                    :item="group.name" 
                    :index="1" 
                    :name="'name'" 
                    @save="save" />
                
                <label for="edit-description-comp">Description</label>
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
        <!-- Manage users -->
        <div>
            <label for="">Members</label>
            <div v-for="member in group.members" :key="member.id" class="d-flex hover member-list">
                <!-- TODO make a table -->
                {{member.username}}
                <span v-if="member.id != user?.id" class="ml-auto">
                    <Tooltip :text="$t('send-message')">
                        <FaIcon 
                            icon="envelope"
                            class="icon small"
                            @click="sendMessage(member)" />
                    </Tooltip>
                    <Tooltip :text="$t('kick')">
                        <FaIcon 
                            :icon="['far', 'rectangle-xmark']"
                            class="icon small red"
                            @click="kick(member)" />
                    </Tooltip>
                    <Tooltip :text="$t('suspend')">
                        <FaIcon
                            icon="ban"
                            class="icon small red"
                            @click="suspend(member)" />
                    </Tooltip>

                </span>
            </div>
        </div>
        
        <Modal :show="showSendMessageModal" :header="false" class="m-override" @close="closeSendMessageModal">
            <SendMessage :user="userToMessage" @close="closeSendMessageModal" />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import {computed, ref, PropType} from 'vue';
import Editable from '/js/components/global/Editable.vue';
import SendMessage from '/js/pages/messages/components/SendMessage.vue';
import {Group, GroupUser} from 'resources/types/group';
import {useGroupStore} from '/js/store/groupStore';
import {useUserStore} from '/js/store/userStore';
import {useI18n} from 'vue-i18n'

const groupStore = useGroupStore();
const userStore = useUserStore();
const {t} = useI18n();

const props = defineProps({
    group: {
        type: Object as PropType<Group>,
        required: true,
    },
});
const user = computed(() => userStore.user);
const showSendMessageModal = ref(false);
const userToMessage = ref({});

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
}

function kick(user: GroupUser) {
    if (confirm(t('confirm-kick-from-group', {user: user.username})))
        groupStore.removeGroupMember(user, props.group);
        //TODO sending a whole user, not sure if that's necessary
}
function suspend(user: GroupUser) {
    if (confirm(t('confirm-suspend-from-group', {user: user.username})))
        groupStore.suspendGroupMember(user, props.group);
        //TODO sending a whole user, not sure if that's necessary
}
function sendMessage(user: GroupUser) {
    showSendMessageModal.value = true;
    userToMessage.value = user;
}
function closeSendMessageModal() {
    showSendMessageModal.value = false;
}
</script>