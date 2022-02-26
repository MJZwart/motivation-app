<template>
    <div>
        <div class="groups">
            <div class="group-list">
                <template v-for="group in myGroups">
                    <group
                        :key="group.id"
                        :group="group"
                        class="group"
                        @reload="reload"/>
                </template>
            </div>
            <b-button type="button" @click="blurbMyGroups">blurb</b-button>
            <div class="create-group">
                <b-button type="button" @click="createGroup">{{$t('create-group')}}</b-button>
            </div>
        </div>

        <b-modal id="create-group" hide-footer :title="$t('create-group')">
            <create-group @close="closeCreateGroup" @reload="reload"/>
        </b-modal>
    </div>
</template>

<script>
import Group from './small/Group.vue'
import CreateGroup from './modals/CreateGroup.vue'
import {mapGetters} from 'vuex';
export default{
    components: {Group, CreateGroup},
    computed: {
        ...mapGetters({
            myGroups: 'groups/getMyGroups',
            allGroups: 'groups/getAllGroups',
        }),
    },
    mounted() {
        this.$store.dispatch('groups/fetchMyGroups');
        this.$store.dispatch('groups/fetchAllGroups');
    },
    data() {
        return {
            groupList: [
                {
                    id: 1,
                    name: "myGroup",
                    description: "this is my group",
                    members:
                        [{id: 1, name: "carl"},
                        {id: 2, name: "franz"}],
                    rank: 1,
                },
                {
                    id: 2,
                    name: "mySecondGroup",
                    description: "this is my second group",
                    members:
                        [{id: 2, name: "franz"},
                        {id: 3, name: "sissi"}],
                    rank: 2,
                },
            ]
        }
    },
    methods: {
        blurbMyGroups() {
            console.log(this.myGroups);
        },
        reload() {
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