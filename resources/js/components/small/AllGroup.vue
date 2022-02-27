<template>
    <div class="group">
        <p class="group-title" @click="toggleInfo">{{group.name}}</p>
        <template v-if="showInfo">
            <p class="group-description">{{group.description}}</p>
            <p class="are-you-admin">Are you an admin: {{areYouAdmin}}</p>
            <p class="members">Members: </p>
            <div v-for="member in group.members" :key="member.id" class="member">
                <p class="member">{{member.username}} <template v-if="member.is_admin">admin</template></p>
            </div>
            <b-button type="button" @click="joinGroup">join</b-button>
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
    },
    data () {
        return {
            areYouAdmin: false,
            showInfo: false,
        }
    },
    methods: {
        joinGroup() {
            this.$store.dispatch('groups/joinGroup', this.group).then(() => this.$emit('reloadGroups'));
        },
        toggleInfo() {
            this.showInfo = this.showInfo ? false : true;
        }
    }
}
</script>