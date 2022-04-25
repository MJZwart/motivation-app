<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <span class="d-flex">
                <button type="button" class="m-1" @click="showMyGroups">{{ $t('my-groups') }}</button>
                <button type="button" class="m-1" @click="showAllGroups">{{ $t('all-groups') }}</button>
                <input v-model="search" class="m-1 filter-input" type="text" :placeholder="$t('group-search-placeholder')"/>
                <button type="button" class="m-1 ml-auto" @click="createGroup">{{$t('create-group')}}</button>
            </span>
            <BTable
                :items="filteredAllGroups"
                :fields="groupFields"
                :options="['table-striped']">
                <template #details="row">
                    <button class="primary" @click="showGroupsDetails(row.item)">{{ $t('show-details') }}</button>
                </template>
                <template #empty>
                    {{ $t('no-groups-found') }}
                </template>
            </BTable>
            <Modal :show="showCreateGroupModal" :footer="false" :title="$t('create-group')" @close="closeCreateGroup">
                <CreateGroup @close="closeCreateGroup" @reloadGroups="load"/>
            </Modal>
            <Modal class="xl" :show="showGroupDetailsModal" :footer="false" 
                   :title="groupDetailsTitle" @close="closeGroupDetails">
                <GroupDetails :group="groupDetailsItem" :user="user" @close="closeGroupDetails" @reloadGroups="load" />
            </Modal>
        </div>
    </div>
</template>

<script setup>
import BTable from '../../bootstrap/BTable.vue';
import CreateGroup from './components/CreateGroup.vue'
import GroupDetails from './components/GroupDetails.vue';
import {computed, ref, onMounted, watch} from 'vue';
import Loading from '../../Loading.vue';
import {ALL_GROUP_FIELDS, MY_GROUP_FIELDS} from '../../../constants/groupConstants.js';
import {useGroupStore} from '@/store/groupStore';
import {useUserStore} from '@/store/userStore';
import {useMainStore} from '@/store/store';

const groupStore = useGroupStore();
const userStore = useUserStore();
const mainStore = useMainStore();

const myGroups = computed(() => groupStore.myGroups);
const allGroups = computed(() => groupStore.allGroups);
const user = computed(() => userStore.user);
const filteredAllGroups = computed(() => {
    return chosenGroups.value.filter(group =>
        group.name.toLowerCase().includes(search.value.toLowerCase()));
});
onMounted(() => {
    load();
});
const search = ref('');
const chosenGroups = ref({});
const loading = ref(true);
const groupFields = ref({});
const chosen = ref('');
const showCreateGroupModal = ref(false);
const showGroupDetailsModal = ref(false);
const groupDetailsItem = ref({});
const groupDetailsTitle = ref('');

async function load() {
    await groupStore.fetchGroupsDashboard();
    chosenGroups.value = myGroups.value;
    groupFields.value = MY_GROUP_FIELDS;
    chosen.value = 'MY';
    loading.value = false;
}
function showMyGroups() {
    chosenGroups.value = myGroups.value;
    groupFields.value = MY_GROUP_FIELDS;
    chosen.value = 'MY';
}
function showAllGroups() {
    chosenGroups.value = allGroups.value;
    groupFields.value = ALL_GROUP_FIELDS;
    chosen.value = 'ALL';
}
function createGroup() {
    mainStore.clearErrors();
    showCreateGroupModal.value = true;
}
function closeCreateGroup() {
    showCreateGroupModal.value = false;
}
function showGroupsDetails(group) {
    groupDetailsItem.value = group;
    groupDetailsTitle.value = group.name;
    showGroupDetailsModal.value = true;
}
function closeGroupDetails() {
    showGroupDetailsModal.value = false;
}
watch(
    () => groupStore.myGroups,
    () => {
        if (chosen.value == 'MY')
            chosenGroups.value = myGroups.value;
        else if (chosen.value == 'ALL')
            chosenGroups.value = allGroups.value;
        reloadGroupDetailsItem();
    },
);
function reloadGroupDetailsItem() { 
    if (Object.keys(chosenGroups.value).length === 0) return;
    const currentItem = groupDetailsItem.value;
    const reloadedGroup = chosenGroups.value.find(item => item.id == currentItem.id);
    groupDetailsItem.value = reloadedGroup;
    groupDetailsTitle.value = reloadedGroup.name;
}
</script>

<style lang="scss" scoped>
@import '../../../../assets/scss/variables';
.groups-table {

    .details {
        padding: 0.6rem;
        border: 1px solid $grey;
        border-radius: 4px;
        background-color:white;
    }
}
.filter-input{
    width: 50%;
}
</style>