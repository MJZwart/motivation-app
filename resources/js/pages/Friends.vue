<template>
    <div>
        <div v-if="requests">
            <div v-if="requests.incoming[0]">
                <span class="card-title">{{ $t('incoming-friend-requests') }}</span>
                <div class="side-border bottom-border">
                    <ul class="summary-list">
                        <li v-for="request in requests.incoming" :key="request.id">
                            <b-icon-check-square class="icon small" @click="acceptFriendRequest(request.id)" />
                            <b-icon-x-square class="icon small icon-red" @click="denyFriendRequest(request.id)" />
                            {{request.friend}}
                        </li>
                    </ul>
                </div>
                <br />
            </div>

            <div v-if="requests.outgoing[0]">
                <span class="card-title">{{ $t('outgoing-friend-requests') }}</span>
                <div class="side-border bottom-border">
                    <ul class="summary-list">
                        <li v-for="request in requests.outgoing" :key="request.id">
                            <b-icon-x-square class="icon small red" @click="removeFriendRequest(request.id)" />
                            {{request.friend}}
                        </li>
                    </ul>
                </div>
            </div>
            <br />
        </div>


        <friends-summary />

    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import FriendsSummary from '../components/summary/FriendsSummary.vue';
export default {
    components: {
        FriendsSummary,
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
        /**
         * @param {Number} requestId
         */
        // removeFriendRequest(requestId) {
            
        // },
        /**
         * @param {Number} requestId
         */
        denyFriendRequest(requestId) {
            this.$store.dispatch('friend/denyRequest', requestId);
        },
        /**
         * @param {Number} requestId
         */
        acceptFriendRequest(requestId) {
            this.$store.dispatch('friend/acceptRequest', requestId);
        },
    },
}
</script>