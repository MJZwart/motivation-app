<template>
    <div>
        <span>
            <b-button type="button" @click="showMyGroups">my groups</b-button>
            <b-button type="button" @click="showAllGroups">all groups</b-button>
        </span>
        <template v-if="activeTab == 'MY_GROUPS'" class="groups">
            <div class="group-list">
                <template v-for="group in myGroups">
                    <my-group
                        :key="group.id"
                        :group="group"
                        class="group"
                        @reloadGroups="reloadGroups"/>
                </template>
            </div>
            <div class="create-group">
                <b-button type="button" @click="createGroup">{{$t('create-group')}}</b-button>
            </div>
        </template>
        <template v-if="activeTab == 'ALL_GROUPS'" class="groups">
            <div class="search">
                <input type="text" v-model="search" :placeholder="$t('group-search-placeholder')"/>
            </div>
            <div class="group-list">
                <template v-for="group in filteredAllGroups">
                    <all-group
                        :key="group.id"
                        :group="group"
                        class="group"
                        @reloadGroups="reloadGroups"/>
                </template>
            </div>
        </template>

        <b-modal id="create-group" hide-footer :title="$t('create-group')">
            <create-group @close="closeCreateGroup" @reloadGroups="reloadGroups"/>
        </b-modal>
    </div>
</template>

<script>
import MyGroup from './small/MyGroup.vue'
import AllGroup from './small/AllGroup.vue'
import CreateGroup from './modals/CreateGroup.vue'
import {mapGetters} from 'vuex';
export default{
    components: {MyGroup, AllGroup, CreateGroup},
    computed: {
        ...mapGetters({
            myGroups: 'groups/getMyGroups',
            allGroups: 'groups/getAllGroups',
        }),
        filteredAllGroups() {
            return this.allGroups.filter(group => group.name.toLowerCase().includes(this.search.toLowerCase()));
        },
    },
    mounted() {
        this.$store.dispatch('groups/fetchMyGroups');
        this.$store.dispatch('groups/fetchAllGroups');
    },
    data() {
        return {
            activeTab: 'MY_GROUPS',
            search: '',
        }
    },
    methods: {
        showMyGroups() {
            this.activeTab = 'MY_GROUPS';
        },
        showAllGroups() {
            this.activeTab = 'ALL_GROUPS';
        },
        reloadGroups() {
            this.reloadAllGroups();
            this.reloadMyGroups();
        },
        reloadAllGroups() {
            this.$store.dispatch('groups/fetchAllGroups');
        },
        reloadMyGroups() {
            this.$store.dispatch('groups/fetchMyGroups');
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