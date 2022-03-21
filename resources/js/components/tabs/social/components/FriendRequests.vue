<template>
    <b-row class="mb-2">
        <b-col>
            <Summary :title="$t('incoming-friend-requests')">
                <ul class="no-list-style">
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
            </Summary>
        </b-col>
        <b-col>
            <Summary :title="$t('outgoing-friend-requests')">
                <ul class="no-list-style">
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
            </Summary>
        </b-col>
    </b-row>
</template>

<script>
import {mapGetters} from 'vuex';
import Summary from '../../../summary/Summary.vue';
export default {
    components: {
        Summary,
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