import axios from "axios";
import { Group, GroupPage } from "resources/types/group";
import { ref } from "vue";

export const groupPage = ref<GroupPage | null>(null);

export const updateGroup = async (group: Group) => {
    const {data} = await axios.put(`/groups/edit/${group.id}`, group);
    groupPage.value = data.data.group;
}
export const fetchGroupRoles = async (groupId: number) => {
    const {data} = await axios.get(`/groups/roles/${groupId}`);
    return data.data;
}