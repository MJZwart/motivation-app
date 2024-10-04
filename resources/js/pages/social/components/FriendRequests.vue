<template>
    <div class="flex-container">
        <div class="flex-grow-1">
            <ContentBlock id="incoming-friend-requests" title="incoming-friend-requests" tutorial>
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
            </ContentBlock>
        </div>
        <div class="flex-grow-1">
            <ContentBlock id="outgoing-friend-requests" title="outgoing-friend-requests" tutorial>
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
            </ContentBlock>
        </div>
    </div>
</template>

<script setup lang="ts">
import {CHECK_SQUARE, CROSS_SQUARE} from '/js/constants/iconConstants';
import { acceptRequest, denyRequest, removeRequest, requests } from '/js/services/friendService';

function removeFriendRequest(requestId: number) {
    removeRequest(requestId);
}
function denyFriendRequest(requestId: number) {
    denyRequest(requestId);
}
function acceptFriendRequest(requestId: number) {
    acceptRequest(requestId);
}
</script>
