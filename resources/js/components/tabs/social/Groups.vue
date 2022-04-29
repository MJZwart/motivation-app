<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <span class="d-flex">
                <button type="button" class="m-1" @click="chosen = 'MY'">{{ $t('my-groups') }}</button>
                <button type="button" class="m-1" @click="chosen = 'ALL'">{{ $t('all-groups') }}</button>
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
import {computed, ref, onMounted} from 'vue';
import Loading from '../../Loading.vue';
import {ALL_GROUP_FIELDS, MY_GROUP_FIELDS} from '../../../constants/groupConstants.js';
import {useGroupStore} from '@/store/groupStore';
import {useUserStore} from '@/store/userStore';
import {useMainStore} from '@/store/store';

const groupStore = useGroupStore();
const userStore = useUserStore();
const mainStore = useMainStore();

const user = computed(() => userStore.user);
const groupFields = computed(() => {
    if (chosen.value == 'MY') return MY_GROUP_FIELDS;
    if (chosen.value == 'ALL') return ALL_GROUP_FIELDS;
    return {};
});
ref({});

const loading = ref(true);
onMounted(() => {
    load();
});
async function load() {
    await groupStore.fetchGroupsDashboard();
    loading.value = false;
}

const search = ref('');
const filteredAllGroups = computed(() => {
    return chosenGroups.value.filter(group =>
        group.name.toLowerCase().includes(search.value.toLowerCase()));
});

const myGroups = computed(() => groupStore.myGroups);
const allGroups = computed(() => groupStore.allGroups);
const chosen = ref('MY');
const chosenGroups = computed(() => {
    if (chosen.value == 'MY') return myGroups.value;
    if (chosen.value == 'ALL') return allGroups.value;
    return {};
});

const showCreateGroupModal = ref(false);
function createGroup() {
    mainStore.clearErrors();
    showCreateGroupModal.value = true;
}
function closeCreateGroup() {
    showCreateGroupModal.value = false;
}

const groupDetailsItemId = ref(null);
const groupDetailsItem = computed(() => {
    if (!groupDetailsItemId.value) return ref({});
    if (!chosenGroups.value) return ref({});
    return chosenGroups.value.find(item => item.id == groupDetailsItemId.value);
});
const groupDetailsTitle = computed(() => {
    if (!groupDetailsItem.value) return '';
    return groupDetailsItem.value.name;
});
const showGroupDetailsModal = ref(false);
function showGroupsDetails(group) {
    groupDetailsItemId.value = group.id;
    showGroupDetailsModal.value = true;
}
function closeGroupDetails() {
    showGroupDetailsModal.value = false;
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