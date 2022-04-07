<template>
    <div class="details">
        <div class="row">
            <div class="col-sm-2"><b>{{ $t('founded') }}:</b></div>
            <div class="col">{{group.time_created}}</div>
        </div>
        <div class="row">
            <div class="col-sm-2"><b>{{ $t('admin') }}:</b></div>
            <div class="col">
                <router-link :to="{ name: 'profile', params: { id: group.admin.id}}">
                    {{group.admin.username}}
                </router-link>
            </div>
        </div>
        <div v-if="isJoinGroupVisible" class="row">
            <div class="col">
                <button type="button" @click="joinGroup()">{{$t('join-group')}}</button>
            </div>
        </div>
        <div v-else class="row">
            <div v-if="isUserAdmin" class="col">
                <button type="button" @click="deleteGroup()">{{ $t('delete-group') }}</button>
            </div>

            <div v-else class="col">
                <button type="button" @click="leaveGroup()">{{ $t('leave-group') }}</button>
            </div>
        </div>
        <hr />
        <div class="row">
            <div class="col-sm-2"><b>{{group.members.length}} {{ $t('members') }}:</b></div>
            <div class="col">
                <div v-for="member in group.members" :key="member.id" class="row">
                    <div class="col-sm-3">{{member.username}}</div>
                    <div class="col-sm-2">{{member.rank}}</div>
                    <div class="col-sm-5">Joined: {{member.joined}}</div>
                </div>
            </div>
        </div>
        
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
        isJoinGroupVisible() {
            let $isVisible = true;
            for (const member of this.group.members) {
                if (member.id == this.user.id) $isVisible = false;
            }
            return $isVisible;
        },
        isUserAdmin() {
            return this.group.admin.id == this.user.id;
        },
    },
    methods: {
        joinGroup() {
            this.$store.dispatch('groups/joinGroup', this.group).then(() => {
                this.$emit('reloadGroups');
                this.$emit('close');
            });
        },
        deleteGroup() {
            if (confirm(this.$t('delete-group-confirm', {group: this.group.name})))
                this.$store.dispatch('groups/deleteGroup', this.group).then(() => {
                    this.$emit('reloadGroups');
                    this.$emit('close');
                });
        },
        leaveGroup() {
            if (confirm(this.$t('leave-group-confirm', {group: this.group.name})))
                this.$store.dispatch('groups/leaveGroup', this.group).then(() => {
                    this.$emit('reloadGroups');
                    this.$emit('close');
                });
        },
    },
}
</script>