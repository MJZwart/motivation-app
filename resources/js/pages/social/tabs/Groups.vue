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
                    <Tutorial tutorial="groups" size="medium" />
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
            <GroupOverviewComponent v-for="(group, index) in filteredAllGroups" :key="index" :group="group" />
            <div v-if="filteredAllGroups.length === 0" >
                {{ $t('no-groups-found') }}
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import type {Group, NewGroup} from 'resources/types/group';
import CreateGroup from '../components/groups/CreateGroup.vue';
import {computed, ref, onMounted} from 'vue';
import {useGroupStore} from '/js/store/groupStore';
import {useRouter} from 'vue-router';
import {formModal} from '/js/components/modal/modalService';
import GroupOverviewComponent from '../components/groups/GroupOverviewComponent.vue';

const groupStore = useGroupStore();

const router = useRouter();

const loading = ref(true);
onMounted(() => {
    load();
});
async function load() {
    const data = await groupStore.fetchGroupsDashboard();
    myGroups.value = data.my;
    allGroups.value = data.all;
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

const myGroups = ref<Group[]>([]);
const allGroups = ref<Group[]>([]);
const chosen = ref('MY');
const chosenGroups = computed(() => {
    if (chosen.value == 'MY') return myGroups.value;
    if (chosen.value == 'ALL') return allGroups.value;
    return [];
});

function createGroup() {
    formModal({
        name: '',
        description: '',
        is_public: false,
        require_application: false},
    CreateGroup,
    submitGroup,
    'create-group',
    );
}

async function submitGroup(newGroup: NewGroup) {
    const data = await groupStore.createGroup(newGroup);
    router.push({path: `/group/${data.group_id}`});
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
