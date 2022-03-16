<template>
    <b-row class="mb-2">
        <b-col>
            <span class="card-title">{{ $t('incoming-friend-requests') }}</span>
            <div class="side-border bottom-border">
                <ul class="summary-list">
                    <div v-if="requests.incoming[0]">
                        <li v-for="(request, index) in requests.incoming" :key="index">
                            <b-icon-check-square 
                                :id="'accept-friend-request-' + index" 
                                class="icon small green" 
                                @click="acceptFriendRequest(request.id)" />
                            <b-tooltip :target="'accept-friend-request-' + index">{{ $t('accept-friend-request') }}</b-tooltip>
                            <b-icon-x-square 
                                :id="'deny-friend-request-' + index" 
                                class="icon small red" 
                                @click="denyFriendRequest(request.id)" />
                            <b-tooltip :target="'deny-friend-request-' + index">{{ $t('deny-friend-request') }}</b-tooltip>
                            {{request.friend}}
                        </li>
                    </div>
                    
                    <p v-else>{{ $t('no-incoming-friend-requests') }}</p>
                </ul>
            </div>
        </b-col>
        <b-col>
            <span class="card-title">{{ $t('outgoing-friend-requests') }}</span>
            <div class="side-border bottom-border">
                <ul class="summary-list">
                    <div v-if="requests.outgoing[0]">
                        <li v-for="(request, index) in requests.outgoing" :key="index">
                            <b-icon-x-square 
                                :id="'cancel-friend-request-' + index" 
                                class="icon small red" 
                                @click="removeFriendRequest(request.id)" />
                            <b-tooltip :target="'cancel-friend-request-' + index">{{ $t('cancel-friend-request') }}</b-tooltip>
                            {{request.friend}}
                        </li>
                    </div>

                    <p v-else>{{ $t('no-outgoing-friend-requests') }}</p>
                </ul>
            </div>
        </b-col>
    </b-row>
</template>

<script>
import {mapGetters} from 'vuex';
export default {
    
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
        removeFriendRequest(requestId) {
            this.$store.dispatch('friend/removeRequest', requestId);
        },
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