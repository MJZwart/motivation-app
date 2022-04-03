<template>
    <div>
        <!-- The search bar -->
        <form class="navbar-search mb-3">
            <input 
                v-model="data.userSearch" 
                class="form-control"
                type="search" 
                :placeholder="$t('search-user')" 
                aria-label="Search user" />
            <b-button type="submit" @click="searchUser">{{ $t('search') }}</b-button>
        </form>

        <!-- The search results -->
        <div v-if="searchResults && searchResults[0]">
            <h3>{{ $t('search-results') }}:</h3>
            <BTable
                :items="searchResults"
                :fields="searchResultsFields"
                :options="['table-sm', 'table-striped', 'table-hover']">
                <template #username="item">
                    <router-link :to="{ name: 'profile', params: { id: item.item.id}}">{{item.item.username}}</router-link>
                </template>
                <template #actions="item">
                    <b-icon-envelope 
                        :id="'send-message-to-user-' + item.index" 
                        class="icon small" 
                        @click="sendMessage(item.item)" /> 
                    <b-tooltip :target="'send-message-to-user-' + item.index">{{ $t('send-message') }}</b-tooltip>
                    <span v-if="!isConnection(item.item.id)">
                        <b-icon-person-plus-fill 
                            :id="'send-friend-request-' + item.index"
                            class="icon small" 
                            @click="sendFriendRequest(item.item.id)" />
                        <b-tooltip :target="'send-friend-request-' + item.index">{{ $t('send-friend-request') }}</b-tooltip>
                    </span>
                </template>
            </BTable>
        </div>
        <BModal :show="showSendMessageModal" :footer="false" :header="false" @close="closeSendMessageModal">
            <SendMessage :user="userToMessage" @close="closeSendMessageModal" />
        </BModal>
    </div>
</template>


<script>
import BTable from '../../../bootstrap/BTable.vue';
import {mapGetters} from 'vuex';
import {SEARCH_RESULTS_FIELDS} from '../../../../constants/userConstants.js';
import SendMessage from '../../../modals/SendMessage.vue';
import BModal from '../../../bootstrap/BModal.vue';
export default {
    components: {
        SendMessage, BModal, BTable,
    },
    data() {
        return {
            data: {
                userSearch: '',
            },
            searchResultsFields: SEARCH_RESULTS_FIELDS,
            userToMessage: null,
            showSendMessageModal: false,
        }
    },
    methods: {
        /** Searches for a user by their username, case-insensitive and includes all that contains the search params */
        searchUser() {
            this.$store.dispatch('user/searchUser', this.data);
        },
        /** Checks if a given user (by id) is already friends with the logged in user or a request is already sent */
        isConnection(id) {
            const ids = [];
            ids.push(...this.requests.outgoing.map(request => request.id));
            ids.push(...this.requests.incoming.map(request => request.id));
            ids.push(...this.user.friends.map(friend => friend.id));
            return ids.includes(id);
        },
        sendFriendRequest(id) {
            this.$store.dispatch('friend/sendRequest', id).then(() => {
                this.$emit('reload');
            });
        },
        sendMessage(user) {
            this.userToMessage = user;
            this.showSendMessageModal= true;
        },
        closeSendMessageModal() {
            this.showSendMessageModal = false;
        },
    },
    computed: {
        ...mapGetters({
            searchResults: 'user/getSearchResults',
            user: 'user/getUser',
            requests: 'friend/getRequests',
        }),
    },
}
</script>


<style>
.navbar-search {
    display:flex;
    flex-direction:row;
}
</style>