<template>
    <div>
        <template v-for="group in groups">
            <group
                :key="group.id"
                :group="group"
                class="group" />
        </template>
    </div>
</template>

<script>
import Group from './Group.vue'
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
            ]
        }
    },
}
</script>