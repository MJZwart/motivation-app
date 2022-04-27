<template>
    <div class="details">
        <div class="row">
            <div class="col-sm-2"><b>{{ $t('founded') }}:</b></div>
            <div class="col">{{group.time_created}}</div>
        </div>
        <div class="row">
            <div class="col-sm-2"><b>{{ $t('admin') }}:</b></div>
            <div class="col">
                <router-link :to="{ name: 'profile', params: { id: group.admin.id}}">
                    {{group.admin.username}}
                </router-link>
            </div>
        </div>
        <div v-if="isJoinGroupVisible" class="row">
            <div class="col">
                <button type="button" @click="joinGroup()">{{$t('join-group')}}</button>
            </div>
        </div>
        <div v-else class="row">
            <div v-if="isUserAdmin" class="col">
                <button type="button" @click="deleteGroup()">{{ $t('delete-group') }}</button>
            </div>

            <div v-else class="col">
                <button type="button" @click="leaveGroup()">{{ $t('leave-group') }}</button>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-sm-2"><b>{{group.members.length}} {{ $t('members') }}:</b></div>
            <div class="col">
                <div v-for="member in group.members" :key="member.id" class="row">
                    <div class="col-sm-3">{{member.username}}</div>
                    <div class="col-sm-2">{{member.rank}}</div>
                    <div class="col-sm-5">Joined: {{member.joined}}</div>
                </div>
            </div>
        </div>
        
        <b-modal :id='`manage-group-modal-${group.id}`' hide-footer hide-header>
            <ManageGroupModal :key="group.id" :index="index" @close="closeManageGroup" />
        </b-modal>

    </div>
</template>

<script setup>
import {computed} from 'vue';
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
const isUserAdmin = computed(() => {
    return props.group.admin.id == props.user.id;
});

async function joinGroup() {
    await groupStore.joinGroup(props.group)
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
</script>