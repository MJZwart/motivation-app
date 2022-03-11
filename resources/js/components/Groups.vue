<template>
    <div>
        <span>
            <b-button type="button" @click="showMyGroups">my groups</b-button>
            <b-button type="button" @click="showAllGroups">all groups</b-button>
        </span>
        <template v-if="activeTab == 'MY_GROUPS'">
            <div class="group-list">
                <template v-for="group in myGroups">
                    <my-group
                        :key="group.id"
                        :group="group"
                        :user="user"
                        class="group"
                        @reloadGroups="reloadGroups"/>
                </template>
            </div>
            <div class="create-group">
                <b-button type="button" @click="createGroup">{{$t('create-group')}}</b-button>
            </div>
        </template>
        <template v-if="activeTab == 'ALL_GROUPS'">
            <div class="search">
                <input v-model="search" type="text" :placeholder="$t('group-search-placeholder')"/>
            </div>
            <div class="group-list">
                <template v-for="group in filteredAllGroups">
                    <div :key="group.name" @click="showAllGroup(group)">
                        <p :key="group.name">{{group.name}}</p>
                        <p :key="group.description">{{group.description}}</p>
                    </div>
                </template>
            </div>
        </template>

        <b-modal id="show-all-group" hide-footer :title="$t('group-information')">
            <show-all-group :group="groupToShow" @close="closeShowAllGroup" @reloadGroups="reloadGroups"/>
        </b-modal>
        <b-modal id="create-group" hide-footer :title="$t('create-group')">
            <create-group @close="closeCreateGroup" @reloadGroups="reloadGroups"/>
        </b-modal>
    </div>
</template>

<script>
import MyGroup from './small/MyGroup.vue'
import ShowAllGroup from './modals/ShowAllGroup.vue'
import CreateGroup from './modals/CreateGroup.vue'
import {mapGetters} from 'vuex';
export default {
    components: {MyGroup, ShowAllGroup, CreateGroup},
    computed: {
        ...mapGetters({
            myGroups: 'groups/getMyGroups',
            allGroups: 'groups/getAllGroups',
            user: 'user/getUser',
        }),
        filteredAllGroups() {
            return this.allGroups.filter(group =>
                group.name.toLowerCase().includes(this.search.toLowerCase()));
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
            groupToShow: null,
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
        showAllGroup(group) {
            this.$store.dispatch('clearErrors');
            this.groupToShow = group;
            this.$bvModal.show('show-all-group');
        },
        closeShowAllGroup() {
            this.$bvModal.hide('show-all-group');
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