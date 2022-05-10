<template>
    <div>
        <div v-if="group">
            <form @submit.prevent="editGroup">
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
                    :rows="3" 
                    @save="save" />

                <button class="m-1" @click="togglePublic">
                    {{group.is_public ? 'Set to private' : 'Make public' }}
                </button>
                <button v-if="group.is_public" class="m-1" @click="toggleRequireApproval">
                    {{group.requires_approval ? 'Do not require approval' : 'Require approval' }}
                </button>
                <!-- TODO turn this into a 'turn off button' -->
            </form>
        </div>
        <!-- Manage users -->
        <div>
            <label for="">Members</label>
            <div v-for="member in group.members" :key="member.id" class="d-flex hover">
                <!-- TODO make a table -->
                {{member.username}}
                <span v-if="member.id != user.id" class="ml-auto">
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

                </span>
            </div>
        </div>
        
        <Modal :show="showSendMessageModal" :footer="false" :header="false" class="m-override" @close="closeSendMessageModal">
            <SendMessage :user="userToMessage" @close="closeSendMessageModal" />
        </Modal>
    </div>
</template>

<script setup>
import {computed, ref} from 'vue';
import Editable from '/js/components/global/Editable.vue';
import SendMessage from '/js/pages/messages/components/SendMessage.vue';
import {useGroupStore} from '/js/store/groupStore';
const groupStore = useGroupStore();
import {useUserStore} from '/js/store/userStore';
const userStore = useUserStore();
import {useI18n} from 'vue-i18n'
const {t} = useI18n();

const props = defineProps({
    group: {
        type: Object,
        required: true,
    },
});
const user = computed(() => userStore.user);
const showSendMessageModal = ref(false);
const userToMessage = ref({});

function save(item) {
    item.id = props.group.id;
    groupStore.updateGroup(item);
}
function togglePublic() {
    const group = {};
    group.is_public = !props.group.is_public;
    group.id = props.group.id;
    groupStore.updateGroup(group);
}

function toggleRequireApproval() {
    const group = {};
    group.require_approval = !props.group.require_approval;
    group.id = props.group.id;
    groupStore.updateGroup(group);
}

function kick(user) {
    if (confirm(t('confirm-kick-from-group', {user: user.username})))
        groupStore.removeGroupMember({id: user.id}, props.group);
}
function sendMessage(user) {
    showSendMessageModal.value = true;
    userToMessage.value = user;
}
function closeSendMessageModal() {
    showSendMessageModal.value = false;
}
</script>