<template>
    <div>
        <template v-for="group in groups">
            <group
                :key="group.id"
                :group="group"
                class="group"
                @reload="reload"/>
        </template>
    </div>
</template>

<script>
import Group from './small/Group.vue'
export default{
    components: {Group},
    data() {
        return {
            groups: [
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
        }
    }
}
</script>