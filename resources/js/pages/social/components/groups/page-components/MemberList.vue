<template>
    <div class="content-block">
        <p><b>{{ $t('members')}}</b>:</p>
        <div class="row">
            <b class="col">{{ $t('username')}}</b>
            <b class="col">{{ $t('rank')}}</b>
            <b class="col">{{ $t('member-for')}}</b>
            <b v-if="group.rank.can_manage_members" class="col">{{$t('actions')}}</b>
        </div>
        <div v-for="(member, index) in group.members" :key="index" class="row">
            <span class="col">{{member.username}}</span>
            <span class="col">
                <GroupRankIcon :rank="member.rank" />
                {{member.rank.name}}</span>
            <span class="col">{{daysSince(member.joined.toString())}}</span>
            <span v-if="group.rank.can_manage_members" class="col">
                <span v-if="member.user_id != user?.id" class="ml-auto mr-3">
                    <Tooltip :text="$t('send-message')">
                        <Icon 
                            :icon="MAIL"
                            class="mail-icon"
                            @click="sendMessage(member)" />
                    </Tooltip>
                    <Tooltip :text="$t('kick')">
                        <Icon 
                            :icon="CROSS_SQUARE"
                            class="kick-icon cross-square-icon red"
                            @click="kick(member)" />
                    </Tooltip>
                    <Tooltip :text="$t('suspend')">
                        <Icon
                            :icon="BAN"
                            class="ban-icon red"
                            @click="suspend(member)" />
                    </Tooltip>
                    <Tooltip v-if="canManageMemberRank(member)" :text="$t('manage-rank')">
                        <Icon icon="mdi:rank" class="iconify small promote-icon" @click="openPromoteModal(member)" />
                    </Tooltip>

                </span>
            </span>
        </div>
        
        <Modal :show="showSendMessageModal" :header="false" class="m-override" @close="closeSendMessageModal">
            <SendMessage v-if="userToMessage" :user="userToMessage" @close="closeSendMessageModal" />
        </Modal>
        <Modal :show="promoteModalOpen" :title="$t('manage-rank')" @close="promoteModalOpen = false">
            <ManageGroupUserRole 
                v-if="memberToManage" 
                :groupUser="memberToManage" 
                :groupRoles="allRolesAvailable"
                @promote="promote" />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import {computed, onMounted, PropType, ref} from 'vue';
import {daysSince} from '/js/services/dateService';
import type {GroupPage, GroupUser, Rank} from 'resources/types/group';
import {useUserStore} from '/js/store/userStore';
import {useGroupStore} from '/js/store/groupStore';
import {useI18n} from 'vue-i18n';
import SendMessage from '/js/pages/messages/components/SendMessage.vue';
import GroupRankIcon from './GroupRankIcon.vue';
import {Icon} from '@iconify/vue';
import ManageGroupUserRole from './ManageGroupUserRole.vue';
import {BAN, CROSS_SQUARE, MAIL} from '/js/constants/iconConstants';

const userStore = useUserStore();
const groupStore = useGroupStore();
const {t} = useI18n();

const props = defineProps({
    group: {
        type: Object as PropType<GroupPage>,
        required: true,
    },
});

onMounted(async() => {
    if (props.group.rank.can_manage_members)
        allRoles.value = await groupStore.fetchRoles(props.group.id);
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

const allRoles = ref<Rank[]>([]);
const allRolesAvailable = computed(() => allRoles.value.filter(role => role.position > props.group.rank.position));

const promoteModalOpen = ref(false);
const memberToManage = ref<GroupUser | null>(null);

function canManageMemberRank(member: GroupUser) {
    if (member.rank.owner || member.rank.position < props.group.rank.position) return false;
    if (member.rank.id == props.group.rank.id) return false;
    return true;
}
function openPromoteModal(member: GroupUser) {
    memberToManage.value = member;
    promoteModalOpen.value = true;
}
async function promote(rankId: number) {
    if (memberToManage.value) {
        await groupStore.updateGroupUserRole(props.group.id, memberToManage.value?.id, rankId);
        promoteModalOpen.value = false;
        memberToManage.value = null;
    }
}
</script>