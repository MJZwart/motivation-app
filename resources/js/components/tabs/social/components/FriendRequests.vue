<template>
    <div class="row">
        <div class="col">
            <Summary :title="$t('incoming-friend-requests')">
                <ul class="no-list-style">
                    <div v-if="requests.incoming[0]">
                        <li v-for="(request, index) in requests.incoming" :key="index">
                            <Tooltip :text="$t('accept-friend-request')">
                                <b-icon-check-square 
                                    :id="'accept-friend-request-' + index" 
                                    class="icon small green" 
                                    @click="acceptFriendRequest(request.friendship_id)" />
                            </Tooltip>
                            <Tooltip :text="$t('deny-friend-request')">
                                <b-icon-x-square 
                                    :id="'deny-friend-request-' + index" 
                                    class="icon small red" 
                                    @click="denyFriendRequest(request.friendship_id)" />
                            </Tooltip>
                            {{request.friend}}
                        </li>
                    </div>
                    
                    <p v-else>{{ $t('no-incoming-friend-requests') }}</p>
                </ul>
            </Summary>
        </div>
        <div class="col">
            <Summary :title="$t('outgoing-friend-requests')">
                <ul class="no-list-style">
                    <div v-if="requests.outgoing[0]">
                        <li v-for="(request, index) in requests.outgoing" :key="index">
                            <Tooltip :text="$t('cancel-friend-request')">
                                <b-icon-x-square 
                                    :id="'cancel-friend-request-' + index" 
                                    class="icon small red" 
                                    @click="removeFriendRequest(request.friendship_id)" />
                            </Tooltip>
                            {{request.friend}}
                        </li>
                    </div>

                    <p v-else>{{ $t('no-outgoing-friend-requests') }}</p>
                </ul>
            </Summary>
        </div>
    </div>
</template>

<script>
import Tooltip from '../../../bootstrap/Tooltip.vue';
import {mapGetters} from 'vuex';
import Summary from '../../../summary/Summary.vue';
export default {
    components: {
        Summary, Tooltip,
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