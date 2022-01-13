<template>
    <div>
        <span class="card-title">Friends</span>
        <div class="side-border bottom-border">
            <ul class="summary-list">
                <li v-for="(friend, index) in user.friends" :key="index">
                    <b-icon-person-x-fill class="icon-small" @click="removeFriend(friend)" />
                    <!-- <b-icon-envelope class="icon-small"></b-icon-envelope>  -->
                    <router-link :to="{ name: 'profile', params: { id: friend.friend_id}}">
                        {{friend.friend}}
                    </router-link>
                </li>
            </ul>
        </div>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
export default {
    methods: {
        removeFriend(friend) {
            if (confirm('Are you sure you wish to remove ' + friend.friend + ' as friend?')) {
                this.$store.dispatch('friend/removeFriend', friend.id);
            }
        },
    },
    computed: {
        ...mapGetters({
            user: 'user/getUser',
        }),
    },
}
</script>