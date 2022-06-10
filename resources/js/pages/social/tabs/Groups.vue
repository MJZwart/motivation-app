<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <div class="tabs tabs-horizontal d-flex">
                <button type="button" class="tab-item m-1" :class="{'active-tab': chosen == 'MY'}" @click="chosen = 'MY'">
                    {{ $t('my-groups') }}
                </button>
                <button type="button" class="tab-item m-1" :class="{'active-tab': chosen == 'ALL'}" @click="chosen = 'ALL'">
                    {{ $t('all-groups') }}
                </button>
                <span class="ml-auto">
                    <Tutorial tutorial="Groups" colorVariant="primary" size="medium" />
                </span>
            </div>
            <input 
                v-model="search" 
                class="m-1 filter-input" 
                type="text" 
                :placeholder="$t('group-search-placeholder')"/>
            <div class="mt-2 mb-1">
                <h3 v-if="chosen == 'MY'">Your groups</h3>
                <h3 v-if="chosen == 'ALL'">All groups</h3>
                <button type="button" class="m-1 ml-auto" @click="createGroup">{{$t('create-group')}}</button>
            </div>
            <Table
                :items="filteredAllGroups"
                :fields="groupFields"
                :options="['table-striped']">
                <template #details="row">
                    <button class="primary" @click="showGroupsDetails(row.item)">{{ $t('show-details') }}</button>
                </template>
                <template #empty>
                    {{ $t('no-groups-found') }}
                </template>
            </Table>
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
import Table from '/js/components/global/Table.vue';
import CreateGroup from '../components/CreateGroup.vue'
import GroupDetails from '../components/GroupDetails.vue';
import {computed, ref, onMounted} from 'vue';
import {ALL_GROUP_FIELDS, MY_GROUP_FIELDS} from '/js/constants/groupConstants.js';
import {useGroupStore} from '/js/store/groupStore';
import {useUserStore} from '/js/store/userStore';
import {useMainStore} from '/js/store/store';

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
</style>