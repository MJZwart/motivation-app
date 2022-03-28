<template>
    <div class="details">
        <b-row>
            <b-col sm="2"><b>{{ $t('founded') }}:</b></b-col>
            <b-col>{{group.time_created}}</b-col>
        </b-row>
        <b-row>
            <b-col sm="2"><b>{{ $t('admin') }}:</b></b-col>
            <b-col>
                <router-link :to="{ name: 'profile', params: { id: group.admin.id}}">
                    {{group.admin.username}}
                </router-link>
            </b-col>
        </b-row>
        <b-row v-if="isJoinGroupVisible">
            <b-col>
                <b-button type="button" @click="joinGroup()">{{$t('join-group')}}</b-button>
            </b-col>
        </b-row>
        <b-row v-else>
            <b-col v-if="isUserAdmin">
                <b-button @click="deleteGroup()">{{ $t('delete-group') }}</b-button>
                <b-button class="ml-1" @click="manageGroup()">{{ $t('manage-group') }}</b-button>
            </b-col>

            <b-col v-else>
                <b-button type="button" @click="leaveGroup()">{{ $t('leave-group') }}</b-button>
            </b-col>
        </b-row>
        <hr />
        <b-row>
            <b-col sm="2"><b>{{group.members.length}} {{ $t('members') }}:</b></b-col>
            <b-col>
                <b-row v-for="member in group.members" :key="member.id">
                    <b-col sm="3">{{member.username}}</b-col>
                    <b-col sm="2">{{member.rank}}</b-col>
                    <b-col sm="3">Joined: {{member.joined}}</b-col>
                </b-row>
            </b-col>
        </b-row>
        
        <b-modal :id='`manage-group-modal-${group.id}`' hide-footer hide-header>
            <ManageGroupModal :key="group.id" :index="index" @close="closeManageGroup" />
        </b-modal>

    </div>
</template>

<script>
import {mapGetters} from 'vuex';
import ManageGroupModal from './ManageGroupModal.vue';
export default {
    components: {ManageGroupModal},
    props: {
        group: {
            type: Object,
            required: true,
        },
        user: {
            type: Object,
            required: true,
        },
        index: {
            type: Number,
            required: true,
        },
    },
    computed: {
        ...mapGetters({
            myGroups: 'groups/getMyGroups',
        }),
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
    // data() {
    //     return {
    //         group: this.myGroups[index],
    //     }
    // },
    methods: {
        joinGroup() {
            this.$store.dispatch('groups/joinGroup', this.group).then(() => {
                this.$emit('reloadGroups');
            });
        },
        deleteGroup() {
            if (confirm(this.$t('delete-group-confirm', {group: this.group.name})))
                this.$store.dispatch('groups/deleteGroup', this.group).then(() => this.$emit('reloadGroups'));
        },
        leaveGroup() {
            if (confirm(this.$t('leave-group-confirm', {group: this.group.name})))
                this.$store.dispatch('groups/leaveGroup', this.group).then(() => this.$emit('reloadGroups'));
        },
        manageGroup() {
            this.$bvModal.show(`manage-group-modal-${this.group.id}`);
        },
        closeManageGroup() {
            this.$bvModal.hide(`manage-group-modal-${this.group.id}`);
        },
    },
}
</script>