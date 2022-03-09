<template>
    <div class="group">
        <p class="group-title" @click="toggleInfo">{{group.name}}</p>
        <template v-if="showInfo">
            <p class="group-description">{{group.description}}</p>
            <p class="are-you-admin">{{$t('your-rank')}}: {{yourRank}}</p>
            <p class="members">Members: </p>
            <div v-for="member in group.members" :key="member.id" class="member">
                <p class="member">{{member.username}} {{member.rank}}</p>
            </div>
            <b-button type="button" @click="leaveGroup">leave</b-button>
            <b-button type="button" @click="deleteGroup">delete</b-button>
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
        user: {
            type: Object,
            required: true,
        },
    },
    computed: {
        yourRank() {
            let rank = '';
            this.group.members.forEach(member => {
                if (member.id == this.user.id) rank = member.rank});
            return rank;
        },
    },
    data () {
        return {
            showInfo: false,
        }
    },
    methods: {
        leaveGroup() {
            this.$store.dispatch('groups/leaveGroup', this.group).then(() => this.$emit('reloadGroups'));
        },
        deleteGroup() {
            this.$store.dispatch('groups/deleteGroup', this.group).then(() => this.$emit('reloadGroups'));
        },
        toggleInfo() {
            this.showInfo = this.showInfo ? false : true;
        }
    }
}
</script>