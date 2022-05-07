<template>
    <div>
        <!-- The search bar -->
        <form class="navbar-search mb-3" @submit.prevent>
            <input 
                v-model="data.userSearch" 
                type="search" 
                :placeholder="$t('search-user')" 
                aria-label="Search user" />
            <button type="submit" @click="searchUser">{{ $t('search') }}</button>
        </form>

        <!-- The search results -->
        <div v-if="searchResults && searchResults[0]">
            <h3>{{ $t('search-results') }}:</h3>
            <Table
                :items="searchResults"
                :fields="searchResultsFields"
                :options="['table-sm', 'table-striped', 'table-hover']">
                <template #username="item">
                    <router-link :to="{ name: 'profile', params: { id: item.item.id}}">{{item.item.username}}</router-link>
                </template>
                <template #actions="item">
                    <Tooltip :text="$t('send-message')">
                        <FaIcon 
                            icon="envelope"
                            class="icon small"
                            @click="sendMessage(item.item)" />
                    </Tooltip>
                    <span v-if="!isConnection(item.item.id)">
                        <Tooltip :text="$t('send-friend-request')">
                            <FaIcon 
                                icon="user-plus"
                                class="icon small"
                                @click="sendFriendRequest(item.item.id)" />
                        </Tooltip>
                    </span>
                </template>
            </Table>
        </div>
        <Modal :show="showSendMessageModal" :footer="false" :header="false" @close="closeSendMessageModal">
            <SendMessage :user="userToMessage" @close="closeSendMessageModal" />
        </Modal>
    </div>
</template>


<script setup>
import Table from '/js/components/global/Table.vue';
import {ref, computed} from 'vue';
import {SEARCH_RESULTS_FIELDS} from '/js/constants/userConstants.js';
import SendMessage from '/js/pages/messages/components/SendMessage.vue';
import {useUserStore} from '/js/store/userStore';
import {useFriendStore} from '/js/store/friendStore';
const userStore = useUserStore();
const friendStore = useFriendStore();

const emit = defineEmits(['reload']);

const data = ref({
    userSearch: '',
});
const searchResultsFields = SEARCH_RESULTS_FIELDS;
const userToMessage = ref({});
const showSendMessageModal = ref(false);
            
/** Searches for a user by their username, case-insensitive and includes all that contains the search params */
function searchUser() {
    userStore.searchUser(data.value);
}
/** Checks if a given user (by id) is already friends with the logged in user or a request is already sent */
function isConnection(id) {
    const ids = [];
    if (requests.value) {
        ids.push(...requests.value.outgoing.map(request => request.id));
        ids.push(...requests.value.incoming.map(request => request.id));
    }
    ids.push(...user.value.friends.map(friend => friend.id));
    return ids.includes(id);
}
async function sendFriendRequest(id) {
    await friendStore.sendRequest(id);
    emit('reload');
}
function sendMessage(user) {
    userToMessage.value = user;
    showSendMessageModal.value = true;
}
function closeSendMessageModal() {
    showSendMessageModal.value = false;
}

const searchResults = computed(() => userStore.searchResults);
const user = computed(() => userStore.user);
const requests = computed(() => friendStore.requests);
</script>


<style>
.navbar-search {
    display:flex;
    flex-direction:row;
}
</style>