<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <span class="d-flex">
                <b-button type="button" class="m-1" @click="showMyGroups">{{ $t('my-groups') }}</b-button>
                <b-button type="button" class="m-1" @click="showAllGroups">{{ $t('all-groups') }}</b-button>
                <b-input v-model="search" class="m-1 filter-input" type="text" :placeholder="$t('group-search-placeholder')"/>
                <b-button type="button" class="m-1 ml-auto" @click="createGroup">{{$t('create-group')}}</b-button>
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
            <BModal :show="showCreateGroupModal" :footer="false" :title="$t('create-group')" @close="closeCreateGroup">
                <CreateGroup @close="closeCreateGroup" @reloadGroups="load"/>
            </BModal>
            <BModal :show="showGroupDetailsModal" :footer="false" :title="groupDetailsTitle" @close="closeGroupDetails">
                <GroupDetails :group="groupDetailsItem" :user="user" @close="closeGroupDetails" @reloadGroups="load" />
            </BModal>
        </div>
    </div>
</template>

<script>
import BTable from '../../bootstrap/BTable.vue';
import CreateGroup from '../../modals/CreateGroup.vue'
import GroupDetails from '../../small/GroupDetails.vue';
import {mapGetters} from 'vuex';
import Loading from '../../Loading.vue';
import {ALL_GROUP_FIELDS, MY_GROUP_FIELDS} from '../../../constants/groupConstants.js';
import BModal from '../../bootstrap/BModal.vue';
export default {
    components: {CreateGroup, GroupDetails, Loading, BModal, BTable},
    computed: {
        ...mapGetters({
            myGroups: 'groups/getMyGroups',
            allGroups: 'groups/getAllGroups',
            user: 'user/getUser',
        }),
        filteredAllGroups() {
            return this.chosenGroups.filter(group =>
                group.name.toLowerCase().includes(this.search.toLowerCase()));
        },
    },
    mounted() {
        this.load();
    },
    data() {
        return {
            search: '',
            groupToShow: null,
            chosenGroups: null,
            loading: true,
            groupFields: null,
            chosen: '',
            showCreateGroupModal: false,
            showGroupDetailsModal: false,
            groupDetailsItem: null,
            groupDetailsTitle: null,
        }
    },
    methods: {
        load() {
            this.$store.dispatch('groups/fetchGroupsDashboard').then(() => {
                this.chosenGroups = this.myGroups;
                this.groupFields = MY_GROUP_FIELDS;
                this.chosen = 'MY';
                this.loading = false;
            });
        },
        showMyGroups() {
            this.chosenGroups = this.myGroups;
            this.groupFields = MY_GROUP_FIELDS;
            this.chosen = 'MY';
        },
        showAllGroups() {
            this.chosenGroups = this.allGroups;
            this.groupFields = ALL_GROUP_FIELDS;
            this.chosen = 'ALL';
        },
        createGroup() {
            this.$store.dispatch('clearErrors');
            this.showCreateGroupModal = true;
        },
        closeCreateGroup() {
            this.showCreateGroupModal = false;
        },
        showGroupsDetails(group) {
            this.groupDetailsItem = group;
            this.groupDetailsTitle = group.name;
            this.showGroupDetailsModal = true;
        },
        closeGroupDetails() {
            this.showGroupDetailsModal = false;
        },
    },
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