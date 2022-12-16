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
                        <Editable 
                            :item="role.name" 
                            :index="role.id"
                            inline
                            name="name" 
                            @save="updateName(role.id, $event)" />
                    </td>
                    <td>
                        <input 
                            type="checkbox" 
                            :v-model="role.can_edit" 
                            :checked="role.can_edit" 
                            :disabled="role.owner || role.member" />
                    </td>
                    <td>
                        <input 
                            type="checkbox" 
                            :v-model="role.can_manage_members" 
                            :checked="role.can_manage_members" 
                            :disabled="role.owner || role.member" />
                    </td>
                    <td>
                        <input 
                            type="checkbox" 
                            :v-model="role.can_moderate_messages" 
                            :checked="role.can_moderate_messages" 
                            :disabled="role.owner || role.member" />
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- <template v-for="(role, index) in groupRoles" :key="index">
            <p class="role">
                {{role.name}}
            </p>
        </template> -->
    </div>
</template>

<script setup lang="ts">
import Editable from '/js/components/global/Editable.vue';
import type {Rank} from 'resources/types/group';
import {onMounted, ref} from 'vue';
import {GROUP_ROLE_FIELDS} from '/js/constants/groupConstants';
import {useGroupStore} from '/js/store/groupStore';
const groupStore = useGroupStore();

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
</script>

<style lang="scss" scoped>
.role-table-field{
    width: 25%;
}
</style>