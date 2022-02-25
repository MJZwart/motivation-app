<template>
    <div class="group">
        <p class="group-title" @click="toggleInfo">{{group.name}}</p>
        <template v-if="showInfo">
            <p class="group-description">{{group.description}}</p>
            <p class="are-you-admin">Are you an admin: {{areYouAdmin}}</p>
            <p class="members">Members: </p>
            <div v-for="member in group.members" :key="member.id" class="member">
                <p class="member">{{member.username}}</p>
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
    mounted() {
        this.areYouAdmin = this.areYouAdminFunc();
    },
    data () {
        return {
            areYouAdmin: false,
            showInfo: false,
        }
    },
    methods: {
        areYouAdminFunc() {
            let bool = this.group.pivot.is_admin;
            if (bool) return 'yes';
            return 'no';
        },
        leaveGroup() {
            console.log(`leaving group: ${this.group.name}`);
            this.$store.dispatch(`/groups/${group.id}`, this.group).then(() => this.$emit('reload'));
        },
        toggleInfo() {
            this.showInfo = this.showInfo ? false : true;
        }
    }
}
</script>