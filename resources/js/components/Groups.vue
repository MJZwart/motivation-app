<template>
    <div>
        <div class="groups">
            <div class="group-list">
                <template v-for="group in groupList">
                    <group
                        :key="group.id"
                        :group="group"
                        class="group"
                        @reload="reload"/>
                </template>
            </div>
            <div class="create-group">
                <b-button type="button" @click="createGroup">{{$t('create-group')}}</b-button>
            </div>
        </div>

        <b-modal id="create-group" hide-footer :title="$t('create-group')">
            <create-group @close="closeCreateGroup" />
        </b-modal>
    </div>
</template>

<script>
import Group from './small/Group.vue'
import CreateGroup from './modals/CreateGroup.vue'
export default{
    components: {Group, CreateGroup},
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
        reload() {
            console.log("placeholder for reloading the groups");
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