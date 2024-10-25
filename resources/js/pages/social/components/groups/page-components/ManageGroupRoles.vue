<template>
    <Loading v-if="loading" />
    <div v-else class="role-list">
        <table>
            <thead>
                <tr>
                    <th v-for="(field, index) in GROUP_ROLE_FIELDS" :key="index" :style="{width: field.width}">
                        {{ field.label ? $t(field.label) : ''}}
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="role in groupRoles" :key="role.id" class="role-row">
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
                        <div class="stacked-icons">
                            <Icon 
                                v-if="role.position > 2 && !role.member" 
                                :icon="ARROW_UP" 
                                class="icon medium" 
                                @click="rankUp(role)" />
                            <Icon 
                                v-if="role.position < (groupRoles.length - 1) && !role.owner" 
                                :icon="ARROW_DOWN" 
                                class="icon medium" 
                                @click="rankDown(role)" />
                        </div>
                    </td>
                    <td>
                        <SimpleCheckbox  v-if="!role.owner && !role.member"
                                         v-model="role.can_edit" />
                        <Icon v-else 
                              :icon="getRoleIcon(role.can_edit)"
                              class="icon non-clickable" 
                              :class="{green: role.can_edit, red: !role.can_edit}" />
                    </td>
                    <td>
                        <SimpleCheckbox  v-if="!role.owner && !role.member"
                                         v-model="role.can_manage_members"  />
                        <Icon v-else 
                              :icon="getRoleIcon(role.can_manage_members)" 
                              class="icon non-clickable" 
                              :class="{green: role.can_manage_members, red: !role.can_manage_members}" />
                    </td>
                    <td>
                        <SimpleCheckbox  v-if="!role.owner && !role.member"
                                         v-model="role.can_moderate_messages" />
                        <Icon v-else 
                              :icon="getRoleIcon(role.can_moderate_messages)"
                              class="icon non-clickable" 
                              :class="{green: role.can_moderate_messages, red: !role.can_moderate_messages}"/>
                    </td>
                    <td>
                        <Icon 
                            v-if="!role.owner && !role.member" 
                            :icon="TRASH" 
                            class="delete-icon red" 
                            @click="deleteRole(role)" 
                        />
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="d-flex m-1">
            <div v-if="newRoleOpen" class="new-role-inline">
                <input
                    id="name"
                    v-model="newRole"
                    type="text"
                    :placeholder="$t('new-role')"
                    class="w-10"
                    :class="{ invalid: hasError('name') }"
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
import SubmitButton from '/js/components/global/small/SubmitButton.vue';
import {useI18n} from 'vue-i18n';
import GroupRankIcon from './GroupRankIcon.vue';
import {Icon} from '@iconify/vue';
import {ARROW_UP, ARROW_DOWN, TRASH} from '/js/constants/iconConstants';
import {hasError} from '/js/services/errorService';
import { fetchGroupRoles, groupPage } from '/js/services/groupService';
import axios from 'axios';

const {t} = useI18n();

const loading = ref(true);
onMounted(async() => {
    loading.value = true;
    groupRoles.value = await fetchGroupRoles(props.groupId);
    loading.value = false;
});

const props = defineProps<{groupId: number}>();

const groupRoles = ref<Rank[]>([]);

async function updateName(roleId: number, role: {name: string}) {

    const {data} = await axios.put(`groups/roles/${props.groupId}/update/${roleId}/name`, role);
    groupPage.value = data.data.group;
    groupRoles.value = data.data.roles;
}
async function updateRoles() {
    const {data} = await axios.put(`groups/roles/${props.groupId}/update`, groupRoles.value);
    groupPage.value = data.data.group;
    groupRoles.value = data.data.roles;
}

function getRoleIcon(permission: boolean) {
    return permission ? 'material-symbols:check-small-rounded' : 'charm:cross';
}

const newRole = ref('');
const newRoleOpen = ref(false);

async function addRole() {
    const {data} = await axios.post(`groups/roles/${props.groupId}`, {name: newRole.value});
    groupPage.value = data.data.group;
    groupRoles.value = data.data.roles;
    newRoleOpen.value = false;
}

async function deleteRole(role: Rank) {
    if (confirm(t('delete-role-confirmation', {role: role.name}))) {
        const {data} = await axios.delete(`groups/roles/${props.groupId}/delete/${role.id}`);
        groupPage.value = data.data.group;
        groupRoles.value = data.data.roles;
    }
}

async function rankUp(role: Rank) {
    const {data} = await axios.put(`groups/roles/${props.groupId}/role/${role.id}/up`);
    groupRoles.value = data.data.roles;
}

async function rankDown(role: Rank) {
    const {data} = await axios.put(`groups/roles/${props.groupId}/role/${role.id}/down`);
    groupRoles.value = data.data.roles;
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
.stacked-icons {
    display: flex;
    flex-direction: column;
}
.role-row {
    border-bottom: 1px solid var(--border-color);
}
</style>