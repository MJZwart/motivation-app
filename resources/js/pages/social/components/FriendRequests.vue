<template>
    <div class="flex-container">
        <div class="flex-grow-1">
            <Summary id="incoming-friend-requests" :title="$t('incoming-friend-requests')">
                <ul class="no-list-style">
                    <div v-if="requests.incoming[0]">
                        <li v-for="(request, index) in requests.incoming" :key="index" class="incoming-request">
                            <Tooltip :text="$t('accept-friend-request')">
                                <Icon
                                    :icon="CHECK_SQUARE"
                                    class="accept-icon check-square-icon green"
                                    @click="acceptFriendRequest(request.friendship_id)"
                                />
                            </Tooltip>
                            <Tooltip :text="$t('deny-friend-request')">
                                <Icon
                                    :icon="CROSS_SQUARE"
                                    class="deny-icon cross-square-icon red"
                                    @click="denyFriendRequest(request.friendship_id)"
                                />
                            </Tooltip>
                            {{ request.friend }}
                        </li>
                    </div>

                    <p v-else>{{ $t('no-incoming-friend-requests') }}</p>
                </ul>
            </Summary>
        </div>
        <div class="flex-grow-1">
            <Summary id="outgoing-friend-requests" :title="$t('outgoing-friend-requests')">
                <ul class="no-list-style">
                    <div v-if="requests.outgoing[0]">
                        <li v-for="(request, index) in requests.outgoing" :key="index" class="outgoing-request">
                            <Tooltip :text="$t('cancel-friend-request')">
                                <Icon
                                    :icon="CROSS_SQUARE"
                                    class="cancel-icon cross-square-icon red"
                                    @click="removeFriendRequest(request.friendship_id)"
                                />
                            </Tooltip>
                            {{ request.friend }}
                        </li>
                    </div>

                    <p v-else>{{ $t('no-outgoing-friend-requests') }}</p>
                </ul>
            </Summary>
        </div>
    </div>
</template>

<script setup lang="ts">
import {computed} from 'vue';
import Summary from '/js/components/global/Summary.vue';
import {CHECK_SQUARE, CROSS_SQUARE} from '/js/constants/iconConstants';
import {useFriendStore} from '/js/store/friendStore';
const friendStore = useFriendStore();

const requests = computed(() => friendStore.requests);

function removeFriendRequest(requestId: number) {
    friendStore.removeRequest(requestId);
}
function denyFriendRequest(requestId: number) {
    friendStore.denyRequest(requestId);
}
function acceptFriendRequest(requestId: number) {
    friendStore.acceptRequest(requestId);
}
</script>
