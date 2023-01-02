<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <div class="tabs tabs-horizontal d-flex">
                <button
                    type="button"
                    class="tab-item m-1"
                    :class="{'active-tab': chosen == 'MY'}"
                    @click="chosen = 'MY'"
                >
                    {{ $t('my-groups') }}
                </button>
                <button
                    type="button"
                    class="tab-item m-1"
                    :class="{'active-tab': chosen == 'ALL'}"
                    @click="chosen = 'ALL'"
                >
                    {{ $t('all-groups') }}
                </button>
                <span class="ml-auto">
                    <Tutorial tutorial="Groups" colorVariant="primary" size="medium" />
                </span>
            </div>
            <div class="group-filters mt-1">
                <h5>{{$t('filter-by')}}:</h5>
                <div class="form-group mb-1">
                    <input
                        id="filter-group-by-name"
                        v-model="groupFilter.name"
                        class="mb-1 filter-input"
                        type="text"
                        :placeholder="$t('group-search-name-placeholder')"
                    />
                </div>
                <div class="form-group mb-1">
                    <input
                        id="filter-group-by-name"
                        v-model="groupFilter.desc"
                        class="mb-1 filter-input"
                        type="text"
                        :placeholder="$t('group-search-description-placeholder')"
                    />
                </div>
                
                <SimpleFormCheckbox 
                    id="public-groups-filter" 
                    v-model="groupFilter.noApplicationReq" 
                    name="require_application" 
                    class="mb-0"
                    :label="$t('free-to-join-only')" />
            </div>
            <div class="mt-2 mb-1">
                <h3 v-if="chosen == 'MY'">{{ $t('my-groups') }}</h3>
                <h3 v-if="chosen == 'ALL'">{{ $t('all-groups') }}</h3>
                <button type="button" class="m-1 ml-auto" @click="createGroup">{{ $t('create-group') }}</button>
            </div>
            <SortableOverviewTable 
                v-if="filteredAllGroups.length > 0" 
                :items="filteredAllGroups" 
                :fields="groupFields"
                class="striped"
                click-to-extend
            >
                <template #nameField="item">
                    <h5>{{item.item.name}}</h5>
                    <span v-if="item.item.is_member" class="silent">
                        <GroupRankIcon v-if="item.item.rank" :rank="item.item.rank" />
                        {{$t('your-group')}}
                    </span>
                </template>
                <template #joined="item">
                    <b>{{$t('member-since')}}:</b> {{ item.item.joined ? parseDateTime(item.item.joined) : '' }}
                </template>
                <template #details="item">
                    <Tooltip :text="$t('view')">
                        <FaIcon icon="magnifying-glass" class="icon" @click="showGroupsDetails(item.item.id)" />
                    </Tooltip>
                </template>
                <template #is_public="item">
                    <b>{{item.item.is_public ? $t('public') : $t('private')}}</b>
                </template>
                <template #rank="item">
                    <b>{{$t('rank')}}: </b>
                    <GroupRankIcon :rank="item.item.rank" />
                    {{item.item.rank?.name}}
                </template>

                <template #require_application="item">
                    <b>{{item.item.require_application ? $t('requires-application') : $t('free-to-join')}}</b>
                </template>
                <template #members="item">
                    <b>{{$t('members')}}:</b> {{item.item.members}}
                </template>
            </SortableOverviewTable>
            <div v-else>
                {{ $t('no-groups-found') }}
            </div>
            <Modal :show="showCreateGroupModal" :title="$t('create-group')" @close="closeCreateGroup">
                <CreateGroup @close="closeCreateGroup" @reloadGroups="load" />
            </Modal>
        </div>
    </div>
</template>

<script setup lang="ts">
import SortableOverviewTable from '/js/components/global/SortableOverviewTable.vue';
import CreateGroup from '../components/groups/CreateGroup.vue';
import {computed, ref, onMounted} from 'vue';
import {parseDateTime} from '/js/services/dateService';
import {ALL_GROUP_FIELDS_OVERVIEW, MY_GROUP_FIELDS_OVERVIEW} from '/js/constants/groupConstants';
import {useGroupStore} from '/js/store/groupStore';
import {useMainStore} from '/js/store/store';
import {useRouter} from 'vue-router';
import type {Group} from 'resources/types/group';
import GroupRankIcon from '../components/groups/page-components/GroupRankIcon.vue';

const groupStore = useGroupStore();
const mainStore = useMainStore();

const router = useRouter();

const groupFields = computed(() => {
    if (chosen.value == 'MY') return MY_GROUP_FIELDS_OVERVIEW;
    return ALL_GROUP_FIELDS_OVERVIEW;
});

const loading = ref(true);
onMounted(() => {
    load();
});
async function load() {
    await groupStore.fetchGroupsDashboard();
    loading.value = false;
}

const filteredAllGroups = computed(() => {
    return chosenGroups.value.filter(filterGroups);
});

const groupFilter = ref({
    noApplicationReq: false,
    name: '',
    desc: '',
});

function filterGroups(group: Group) {
    if (!group.name.toLowerCase().includes(groupFilter.value.name.toLowerCase())) return false;
    if (groupFilter.value.noApplicationReq && group.require_application) return false;
    if (!group.description.toLowerCase().includes(groupFilter.value.desc.toLowerCase())) return false;
    return true;
}

const myGroups = computed(() => groupStore.myGroups);
const allGroups = computed(() => groupStore.allGroups);
const chosen = ref('MY');
const chosenGroups = computed(() => {
    if (chosen.value == 'MY') return myGroups.value;
    if (chosen.value == 'ALL') return allGroups.value;
    return [];
});

const showCreateGroupModal = ref(false);
function createGroup() {
    mainStore.clearErrors();
    showCreateGroupModal.value = true;
}
function closeCreateGroup() {
    showCreateGroupModal.value = false;
}

function showGroupsDetails(groupId: number) {
    router.push({path: `/group/${groupId}`});
}
</script>

<style lang="scss" scoped>
.groups-table {
    .details {
        padding: 0.6rem;
        border: 1px solid var(--grey);
        border-radius: 4px;
        background-color: white;
    }
}
.group-filters {
    padding: 0.5rem;
    border-radius: 0.5rem;
}
</style>
