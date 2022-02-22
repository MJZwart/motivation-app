<template>
    <div>
        <p class="group-title">{{group.name}}</p>
        <p class="group-description">{{group.description}}</p>
        <div v-for="member in group.members" :key="member.id" class="member">
            <p class="member">{{member.name}}</p>
        </div>
        <button type="button" @click="leaveGroup">leave</button>
    </div>
</template>

<script>
export default {
    props: {
        group: {
            type: Object,
            required: true,
        },
    },
    methods: {
        leaveGroup() {
            console.log(`leaving group: ${this.group.name}`);
            this.$emit('reload');
        }
    }
}
</script>