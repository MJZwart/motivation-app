<template>
    <div>
        <div v-if="requests">
            <div v-if="requests.incoming[0]">
                <span class="frame-title">Incoming friend requests</span>
                <div class="side-border bottom-border">
                    <ul class="summary-list">
                        <li v-for="request in requests.incoming" :key="request.id">
                            <b-icon-check-square class="icon-small" @click="acceptFriendRequest(request.id)"></b-icon-check-square>
                            <b-icon-x-square class="icon-small icon-red" @click="denyFriendRequest(request.id)"></b-icon-x-square>
                            {{request.friend}}
                        </li>
                    </ul>
                </div>
                <br />
            </div>

            <div v-if="requests.outgoing[0]">
                <span class="frame-title">Outgoing friend requests</span>
                <div class="side-border bottom-border">
                    <ul class="summary-list">
                        <li v-for="request in requests.outgoing" :key="request.id">
                            <b-icon-x-square class="icon-small icon-red" @click="removeFriendRequest(request.id)"></b-icon-x-square>
                            {{request.friend}}
                        </li>
                    </ul>
                </div>
            </div>
            <br />
        </div>


        <friends-summary></friends-summary>

    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import FriendsSummary from '../components/summary/FriendsSummary.vue';
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
    },
    methods: {
        removeFriendRequest(requestId){

        },
        denyFriendRequest(requestId){
            this.$store.dispatch('friend/denyRequest', requestId);
        },
        acceptFriendRequest(requestId){
            this.$store.dispatch('friend/acceptRequest', requestId);
        },
    }

//TODO test if this is already happening
    //If anything changes in the friends, you need to update the User in the store, as well as localStorage.
}
</script>


<style>
.icon-red{
    color: darkred;
}
</style>