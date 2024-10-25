<template>
    <div class="content-block">
        <p><b>{{ $t('members')}}</b>:</p>
        <div class="row">
            <b class="col">{{ $t('username')}}</b>
            <b class="col">{{ $t('rank')}}</b>
            <b class="col">{{ $t('member-for')}}</b>
            <b class="col">{{ $t('exp-contribution') }}</b>
            <b v-if="group.rank.can_manage_members" class="col">{{$t('actions')}}</b>
        </div>
        <div v-for="(member, index) in group.members" :key="index" class="row">
            <span class="col">{{member.username}}</span>
            <span class="col">
                <GroupRankIcon :rank="member.rank" />
                {{member.rank.name}}</span>
            <span class="col">{{daysSince(member.joined.toString())}}</span>
            <span class="col">{{ parseBigNumbers(member.exp_gained) }}</span>
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
                        <Icon :icon="PROMOTE" class="iconify small promote-icon" @click="openPromoteModal(member)" />
                    </Tooltip>
                </span>
            </span>
        </div>
    </div>
</template>

<script setup lang="ts">
import {computed, onMounted, PropType, ref} from 'vue';
import {daysSince} from '/js/services/dateService';
import type {GroupPage, GroupUser, Rank} from 'resources/types/group';
import {useI18n} from 'vue-i18n';
import GroupRankIcon from './GroupRankIcon.vue';
import {Icon} from '@iconify/vue';
import ManageGroupUserRole from './ManageGroupUserRole.vue';
import {BAN, CROSS_SQUARE, MAIL, PROMOTE} from '/js/constants/iconConstants';
import {formModal, sendMessageModal} from '/js/components/modal/modalService';
import {parseBigNumbers} from '/js/services/numberService';
import { user } from '/js/services/userService';
import axios from 'axios';
import { fetchGroupRoles, groupPage } from '/js/services/groupService';

const {t} = useI18n();

const props = defineProps({
    group: {
        type: Object as PropType<GroupPage>,
        required: true,
    },
});

onMounted(async() => {
    if (props.group.rank.can_manage_members)
        allRoles.value = await fetchGroupRoles(props.group.id);
});

async function kick(user: GroupUser) {
    if (confirm(t('confirm-kick-from-group', {user: user.username}))) {
        const {data} = await axios.post(`/groups/kick/${props.group.id}`, {id: user.id});
        groupPage.value = data.data.group;
    }
}
async function suspend(user: GroupUser) {
    if (confirm(t('confirm-suspend-from-group', {user: user.username}))) {
        const {data} = await axios.post(`/groups/suspend/${props.group.id}`, {id: user.user_id});
        groupPage.value = data.data.group;
    }
}
function sendMessage(user: GroupUser) {
    sendMessageModal(user.username, user.user_id);
}

const allRoles = ref<Rank[]>([]);
const allRolesAvailable = computed(() => allRoles.value.filter(role => role.position > props.group.rank.position));

const memberToManage = ref<GroupUser | null>();

function canManageMemberRank(member: GroupUser) {
    if (member.rank.owner || member.rank.position < props.group.rank.position) return false;
    if (member.rank.id == props.group.rank.id) return false;
    return true;
}
function openPromoteModal(member: GroupUser) {
    memberToManage.value = member;
    // @ts-ignore Due to the problems with modal this needs to be ignored.
    formModal({groupUser: member, groupRoles: allRolesAvailable}, ManageGroupUserRole, promote, 'manage-rank');
}
async function promote(roleId: number) {
    if (memberToManage.value) {
        const {data} = await axios.put(`groups/roles/${props.group.id}/user/${memberToManage.value?.id}/role/${roleId}`);
        groupPage.value = data.data.group;
        memberToManage.value = null;
    }
}
</script>