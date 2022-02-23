<template>
    <div class="group">
        <p class="group-title" @click="toggleInfo">{{group.name}}</p>
        <template v-if="showInfo">
            <p class="group-description">{{group.description}}</p>
            <div v-for="member in group.members" :key="member.id" class="member">
                <p class="member">{{member.name}}</p>
            </div>
            <b-button type="button" @click="leaveGroup">leave</b-button>
        </template>
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
    data () {
        return {
            showInfo: false,
        }
    },
    methods: {
        leaveGroup() {
            console.log(`leaving group: ${this.group.name}`);
            this.$emit('reload');
        },
        toggleInfo() {
            this.showInfo = this.showInfo ? false : true;
        }
    }
}
</script>