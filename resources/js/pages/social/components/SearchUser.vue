<template>
    <div class="m-1">
        <!-- The search bar -->
        <form class="navbar-search mb-3 mt-2" @submit.prevent>
            <input 
                id="search-by-username" 
                v-model="data.userSearch" 
                class="mr-2"
                type="search" 
                :placeholder="$t('search-user')" 
                aria-label="Search user" />
            <SubmitButton class="mb-0" @click="searchUser">{{ $t('search') }}</SubmitButton>
        </form>

        <!-- The search results -->
        <div v-if="searchResults && searchResults[0]">
            <h3>{{ $t('search-results') }}:</h3>
            <Table
                :items="searchResults"
                :fields="searchResultsFields"
                :options="['table-striped', 'table-hover', 'page-wide']"
            >
                <template #username="item">
                    <router-link :to="{name: 'profile', params: {id: item.item.id}}">
                        {{ item.item.username }}
                    </router-link>
                </template>
                <template #actions="item">
                    <Tooltip :text="$t('send-message')">
                        <FaIcon icon="envelope" class="icon small" @click="sendMessage(item.item)" />
                    </Tooltip>
                    <span v-if="!isConnection(item.item.id)">
                        <Tooltip :text="$t('send-friend-request')">
                            <FaIcon icon="user-plus" class="icon small" @click="sendFriendRequest(item.item.id)" />
                        </Tooltip>
                    </span>
                </template>
            </Table>
        </div>
        <div v-else>
            <h3>{{$t('no-results')}}</h3>
        </div>
        <Modal :show="showSendMessageModal" :header="false" @close="closeSendMessageModal">
            <SendMessage v-if="userToMessage" :user="userToMessage" @close="closeSendMessageModal" />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import Table from '/js/components/global/Table.vue';
import {ref, computed} from 'vue';
import {SEARCH_RESULTS_FIELDS} from '/js/constants/userConstants.js';
import SendMessage from '/js/pages/messages/components/SendMessage.vue';
import {useUserStore} from '/js/store/userStore';
import {useFriendStore} from '/js/store/friendStore';
import type {StrippedUser} from 'resources/types/user';
const userStore = useUserStore();
const friendStore = useFriendStore();

const emit = defineEmits(['reload']);

const data = ref({
    userSearch: '',
});
const searchResultsFields = SEARCH_RESULTS_FIELDS;
const userToMessage = ref<StrippedUser | null>(null);
const showSendMessageModal = ref(false);

const searchResults = ref<StrippedUser[] | null>(null);
/** Searches for a user by their username, case-insensitive and includes all that contains the search params */
async function searchUser() {
    searchResults.value = await userStore.searchUser(data.value);
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
    await friendStore.sendRequest(id);
    emit('reload');
}
function sendMessage(user: StrippedUser) {
    userToMessage.value = user;
    showSendMessageModal.value = true;
}
function closeSendMessageModal() {
    showSendMessageModal.value = false;
}

const friends = computed(() => friendStore.friends);
const requests = computed(() => friendStore.requests);
</script>

<style>
.navbar-search {
    display: flex;
    flex-direction: row;
}
</style>
