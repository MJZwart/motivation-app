<template>
    <div>
        <div v-if="user.friends.length > 0" id="invite-friends-box">
            <h4>Invite friends</h4>
            <div id="invite-friends-content">
                <template v-for="(friend, index) in user.friends" :key="index">
                    <span class="col-2">{{friend.name}}</span>
                    <span class="col-1">Invite</span>
                </template>
            </div>
        </div>
        <div id="invite-users-box">
            
            <h4>Invite users</h4>
            <!-- The search bar -->
            <form class="navbar-search mb-3" @submit.prevent>
                <input 
                    v-model="searchData.userSearch" 
                    type="search" 
                    :placeholder="$t('search-user')" 
                    aria-label="Search user" />
                <button type="submit" @click="searchUser">{{ $t('search') }}</button>
            </form>

            <!-- The search results -->
            <div v-if="searchResults && searchResults[0]">
                <h5>{{ $t('search-results') }}:</h5>
                <Table
                    :items="searchResults"
                    :fields="searchResultsFields"
                    :options="['table-sm', 'table-striped', 'table-hover']">
                    <template #username="item">
                        <router-link :to="{ name: 'profile', params: { id: item.item.id}}">{{item.item.username}}</router-link>
                    </template>
                    <template #actions="item">
                        <span 
                            v-if="!hasInvite(item.item.id)"
                            class="clickable" @click="inviteUser(item.item.id)">Invite</span>
                    </template>
                </Table>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {GroupPage} from 'resources/types/group';
import {User} from 'resources/types/user';
import {ref, computed} from 'vue';
import {SEARCH_RESULTS_FIELDS} from '/js/constants/userConstants.js';
import {useGroupStore} from '/js/store/groupStore';
import {useUserStore} from '/js/store/userStore';
import Table from '/js/components/global/Table.vue';
import {UserSearch} from 'resources/types/global';

const groupStore = useGroupStore();
const userStore = useUserStore();
const searchResultsFields = SEARCH_RESULTS_FIELDS;

const group = computed<GroupPage | null>(() => groupStore.group);
const user = computed<User>(() => userStore.user);

const searchData = ref<UserSearch>({
    userSearch: '',
});
const searchResults = ref<Array<User> | null>(null);

/** Searches for a user by their username, case-insensitive and includes all that contains the search params */
async function searchUser() {
    searchResults.value = await userStore.searchUser(searchData.value);
}

async function inviteUser(userId: number) {
    if (group.value === null) return;
    const invite = {
        user_id: userId,
        group_id: group.value.id,
    }
    await groupStore.inviteUser(invite);
}

/** Checks if the user has already been invited. */
function hasInvite(userId: number) {
    if (group.value === null || group.value.invites === null || group.value.invites.length === 0) return false;
    return group.value.invites.includes(userId);
}
</script>