<template>
    <div class="content-block">
        <p><b>{{ $t('members')}}</b>:</p>
        <div class="row">
            <b class="col">{{ $t('username')}}</b>
            <b class="col">{{ $t('rank')}}</b>
            <b class="col">{{ $t('member-for')}}</b>
            <b v-if="group.is_admin" class="col">{{$t('actions')}}</b>
        </div>
        <div v-for="(member, index) in group.members" :key="index" class="row">
            <span class="col">{{member.username}}</span>
            <span class="col"><FaIcon :icon="member.rank == 'admin' ? 'angles-down' : 'angle-down'" />
                {{member.rank}}</span>
            <span class="col">{{daysSince(member.joined.toString())}}</span>
            <span v-if="group.is_admin" class="col">
                <span v-if="member.id != user?.id" class="ml-auto mr-3">
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
            </span>
        </div>
        
        <Modal :show="showSendMessageModal" :header="false" class="m-override" @close="closeSendMessageModal">
            <SendMessage v-if="userToMessage" :user="userToMessage" @close="closeSendMessageModal" />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import {computed, PropType, ref} from 'vue';
import {daysSince} from '/js/services/dateService';
import type {GroupPage, GroupUser} from 'resources/types/group';
import {useUserStore} from '/js/store/userStore';
import {useGroupStore} from '/js/store/groupStore';
import {useI18n} from 'vue-i18n';
import SendMessage from '/js/pages/messages/components/SendMessage.vue';

const userStore = useUserStore();
const groupStore = useGroupStore();
const {t} = useI18n();

const props = defineProps({
    group: {
        type: Object as PropType<GroupPage>,
        required: true,
    },
});

const user = computed(() => userStore.user);

const showSendMessageModal = ref(false);
const userToMessage = ref<GroupUser | null>(null);

function kick(user: GroupUser) {
    if (confirm(t('confirm-kick-from-group', {user: user.username})))
        groupStore.removeGroupMember(user.id, props.group.id);
}
function suspend(user: GroupUser) {
    if (confirm(t('confirm-suspend-from-group', {user: user.username})))
        groupStore.suspendGroupMember(user.id, props.group.id);
}
function sendMessage(user: GroupUser) {
    showSendMessageModal.value = true;
    userToMessage.value = user;
}
function closeSendMessageModal() {
    showSendMessageModal.value = false;
}
</script>