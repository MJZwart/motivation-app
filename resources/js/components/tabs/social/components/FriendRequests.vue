<template>
    <div class="row">
        <div class="col">
            <Summary :title="$t('incoming-friend-requests')">
                <ul class="no-list-style">
                    <div v-if="requests.incoming[0]">
                        <li v-for="(request, index) in requests.incoming" :key="index">
                            <Tooltip :text="$t('accept-friend-request')">
                                <FaIcon 
                                    :icon="['far', 'square-check']"
                                    class="icon small green"
                                    @click="acceptFriendRequest(request.friendship_id)" />
                            </Tooltip>
                            <Tooltip :text="$t('deny-friend-request')">
                                <FaIcon 
                                    :icon="['far', 'rectangle-xmark']"
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
                                <FaIcon 
                                    :icon="['far', 'rectangle-xmark']"
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

<script setup>
import Tooltip from '../../../bootstrap/Tooltip.vue';
import {computed} from 'vue';
import Summary from '../../../summary/Summary.vue';
import {useFriendStore} from '@/store/friendStore';
const friendStore = useFriendStore();

const requests = computed(() => friendStore.requests);

/**
 * @param {Number} requestId
 */
function removeFriendRequest(requestId) {
    useFriendStore.removeRequest(requestId);
}
/**
 * @param {Number} requestId
 */
function denyFriendRequest(requestId) {
    useFriendStore.denyRequest(requestId);
}
/**
 * @param {Number} requestId
 */
function acceptFriendRequest(requestId) {
    useFriendStore.acceptRequest(requestId);
}
</script>