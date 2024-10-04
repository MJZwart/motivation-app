<template>
    <div class="m-1">
        <!-- The search bar -->
        <form class="navbar-search mb-3 mt-3" @submit.prevent>
            <input 
                id="search-by-username" 
                v-model="data.userSearch" 
                class="mr-2"
                type="search" 
                :placeholder="$t('search-user')" 
                aria-label="Search user" />
            <SubmitButton class="mb-0" @click="startSearch">{{ $t('search') }}</SubmitButton>
        </form>

        <!-- The search results -->
        <div v-if="searchResults && searchResults[0]">
            <h3>{{ $t('search-results') }}:</h3>
            <Table
                :items="searchResults"
                :fields="searchResultsFields"
                :options="['table-striped', 'table-hover', 'page-wide']"
            >
                <template #username="row">
                    <router-link :to="{name: 'profile', params: {id: row.item.id}}">
                        {{ row.item.username }} 
                    </router-link> <span v-if="row.item.guest" class="silent">{{ $t('guest-account') }}</span>
                </template>
                <template #actions="row">
                    <span v-if="!row.item.guest">
                        <Tooltip :text="$t('send-message')">
                            <Icon :icon="MAIL" class="mail-icon" @click="sendMessage(row.item)" />
                        </Tooltip>
                        <span v-if="!isConnection(row.item.id)">
                            <Tooltip :text="$t('send-friend-request')">
                                <Icon :icon="FRIEND" class="friend-icon" @click="sendFriendRequest(row.item.id)" />
                            </Tooltip>
                        </span>
                    </span>
                </template>
            </Table>
        </div>
        <div v-else>
            <h3>{{$t('no-results')}}</h3>
        </div>
    </div>
</template>

<script setup lang="ts">
import Table from '/js/components/global/Table.vue';
import {ref} from 'vue';
import {SEARCH_RESULTS_FIELDS} from '/js/constants/userConstants.js';
import type {StrippedUser} from 'resources/types/user';
import {FRIEND, MAIL} from '/js/constants/iconConstants';
import {sendMessageModal} from '/js/components/modal/modalService';
import { friends, requests, sendRequest } from '/js/services/friendService';
import { searchUser } from '/js/services/userService';

const emit = defineEmits(['reload']);

const data = ref({
    userSearch: '',
});
const searchResultsFields = SEARCH_RESULTS_FIELDS;

const searchResults = ref<StrippedUser[] | null>(null);
/** Searches for a user by their username, case-insensitive and includes all that contains the search params */
async function startSearch() {
    searchResults.value = await searchUser(data.value);
}
/** Checks if a given user (by id) is already friends with the logged in user or a request is already sent */
function isConnection(id: number) {
    const ids = [];
    if (requests.value) {
        ids.push(...requests.value.outgoing.map(request => request.id));
        ids.push(...requests.value.incoming.map(request => request.id));
    }
    ids.push(...friends.value.map(friend => friend.id));
    return ids.includes(id);
}
async function sendFriendRequest(id: string) {
    await sendRequest(id);
    emit('reload');
}
function sendMessage(user: StrippedUser) {
    sendMessageModal(user.username, user.id);
}
</script>

<style>
.navbar-search {
    display: flex;
    flex-direction: row;
}
</style>
