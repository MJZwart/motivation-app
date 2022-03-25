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
            <b-table
                :items="filteredAllGroups"
                :fields="groupFields"
                class="groups-table"
                striped
                show-empty
            >
                <template #empty>
                    {{ $t('no-groups-found') }}
                </template>
                <template #cell(details)="row">
                    <b-button @click="row.toggleDetails">{{ $t('show-details') }}</b-button>
                </template>
                <template #row-details="row">
                    <GroupDetails :group="row.item" :user="user" @reloadGroups="load" />
                </template>
            </b-table>
            <b-modal id="create-group" hide-footer :title="$t('create-group')">
                <CreateGroup @close="closeCreateGroup" @reloadGroups="load"/>
            </b-modal>
        </div>
    </div>
</template>

<script>
import CreateGroup from '../../modals/CreateGroup.vue'
import GroupDetails from '../../small/GroupDetails.vue';
import {mapGetters} from 'vuex';
import Loading from '../../Loading.vue';
import {ALL_GROUP_FIELDS, MY_GROUP_FIELDS} from '../../../constants/groupConstants.js';
export default {
    components: {CreateGroup, GroupDetails, Loading},
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
            this.$bvModal.show('create-group');
        },
        closeCreateGroup() {
            this.$bvModal.hide('create-group');
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