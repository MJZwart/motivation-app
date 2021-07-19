<template>
    <div>
        <div v-if="requests">
            <div v-if="requests.incoming[0]">
                <span class="frame-title">Incoming friend requests</span>
                <div class="side-border bottom-border">
                    <ul class="summary-list">
                        <li v-for="request in requests.incoming" :key="request.id">{{request.friend}}</li>
                    </ul>
                </div>
            </div>

            <br />

            <div v-if="requests.outgoing[0]">
                <span class="frame-title">Outgoing friend requests</span>
                <div class="side-border bottom-border">
                    <ul class="summary-list">
                        <li v-for="request in requests.outgoing" :key="request.id">{{request.friend}}</li>
                    </ul>
                </div>
            </div>
        </div>

        <br />

        <friends-summary></friends-summary>

    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import FriendsSummary from '../components/FriendsSummary.vue';
export default {
    components: {
        FriendsSummary
    },
    mounted() {
        this.$store.dispatch('friend/getRequests');
    },
    data() {
        return {
            outgoingRequests: true,
            incomingRequests: true,
        }
    },

    computed: {
        ...mapGetters({
            requests: 'friend/getRequests',
        }),
    }

    //If anything changes in the friends, you need to update the User in the store, as well as localStorage.
}
</script>


<style>

</style>