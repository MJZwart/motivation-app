<template>
    <Loading v-if="loading" />
    <div v-else class="role-list">
        <table>
            <thead>
                <tr>
                    <th v-for="(field, index) in GROUP_ROLE_FIELDS" :key="index" :style="{width: field.width}">
                        {{$t(field.label)}}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="role in groupRoles" :key="role.id">
                    <td>
                        <div class="d-flex role-name">
                            <GroupRankIcon :rank="role" />
                            <Editable 
                                :item="role.name" 
                                :index="role.id"
                                inline
                                name="name" 
                                @save="updateName(role.id, $event)" />
                        </div>
                        <span v-if="role.member" class="silent">{{$t('default-role')}}</span>
                    </td>
                    <td>
                        <input 
                            v-if="!role.owner && !role.member"
                            v-model="role.can_edit" 
                            type="checkbox"  />
                        <Icon v-else 
                              :icon="getRoleIcon(role.can_edit)"
                              class="icon non-clickable" 
                              :class="{green: role.can_edit, red: !role.can_edit}" />
                    </td>
                    <td>
                        <input 
                            v-if="!role.owner && !role.member" 
                            v-model="role.can_manage_members" 
                            type="checkbox" />
                        <Icon v-else 
                              :icon="getRoleIcon(role.can_manage_members)" 
                              class="icon non-clickable" 
                              :class="{green: role.can_manage_members, red: !role.can_manage_members}" />
                    </td>
                    <td>
                        <input 
                            v-if="!role.owner && !role.member" 
                            v-model="role.can_moderate_messages" 
                            type="checkbox" />
                        <Icon v-else 
                              :icon="getRoleIcon(role.can_moderate_messages)"
                              class="icon non-clickable" 
                              :class="{green: role.can_moderate_messages, red: !role.can_moderate_messages}"/>
                    </td>
                    <td>
                        <FaIcon 
                            v-if="!role.owner && !role.member" 
                            icon="trash" 
                            class="icon small red" 
                            @click="deleteRole(role)" 
                        />
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="d-flex m-1">
            <div v-if="newRoleOpen" class="new-role-inline">
                <input
                    id="new-role-name"
                    v-model="newRole"
                    type="text"
                    :placeholder="$t('new-role')"
                />
                <SubmitButton @click="addRole">
                    {{$t('add')}}
                </SubmitButton>
                <button @click="newRoleOpen = false">{{$t('cancel')}}</button>
            </div>
            <button v-else @click="newRoleOpen = true">{{$t('add-role')}}</button>
            <SubmitButton class="ml-auto" @click="updateRoles" />
        </div>
    </div>
</template>

<script setup lang="ts">
import Editable from '/js/components/global/Editable.vue';
import type {Rank} from 'resources/types/group';
import {onMounted, ref} from 'vue';
import {GROUP_ROLE_FIELDS} from '/js/constants/groupConstants';
import {useGroupStore} from '/js/store/groupStore';
import SubmitButton from '/js/components/global/small/SubmitButton.vue';
import {useI18n} from 'vue-i18n';
import GroupRankIcon from './GroupRankIcon.vue';
import {Icon} from '@iconify/vue';
const groupStore = useGroupStore();
const {t} = useI18n();

const loading = ref(true);
onMounted(async() => {
    loading.value = true;
    groupRoles.value = await groupStore.fetchRoles(props.groupId);
    loading.value = false;
});

const props = defineProps<{groupId: number}>();

const groupRoles = ref<Rank[]>([]);

async function updateName(roleId: number, role: {name: string}) {
    groupRoles.value = await groupStore.updateRoleName(props.groupId, roleId, role);
}
async function updateRoles() {
    groupRoles.value = await groupStore.updateRoles(props.groupId, groupRoles.value);
}

function getRoleIcon(permission: boolean) {
    return permission ? 'material-symbols:check-small-rounded' : 'charm:cross';
}

const newRole = ref('');
const newRoleOpen = ref(false);

async function addRole() {
    groupRoles.value = await groupStore.createRole(props.groupId, {name: newRole.value});
    newRoleOpen.value = false;
}

async function deleteRole(role: Rank) {
    if (confirm(t('delete-role-confirmation', {role: role.name}))) 
        groupRoles.value = await groupStore.deleteRole(props.groupId, role.id);
}
</script>

<style lang="scss" scoped>
.role-table-field{
    width: 25%;
}
.new-role-inline {
    display: flex;
    input {
        width: 45%;
        margin: 0.2rem;
    }
    button {
        margin: 0.2rem;
    }
}
</style>