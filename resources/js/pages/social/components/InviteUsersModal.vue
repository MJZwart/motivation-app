<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <div v-if="friends !== null && friends.length > 0" class="invite-friends-box">
                <h4 class="block">{{ $t('invite-friends') }}</h4>
                <div class="invite-friends-content">
                    <template v-for="(friend, index) in friends" :key="index">
                        <span 
                            class="invite-friends-action clickable" 
                            :class="{disabled: canNotInvite(friend.id)}" 
                            @click="inviteUser(friend.id)">
                            {{ $t('invite') }}
                        </span>
                        <span class="invite-friends-username">{{friend.username}}</span>
                        <br />
                    </template>
                </div>
            </div>
            <div id="invite-users-box">
                
                <h4>{{ $t('search-by-username') }}</h4>
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
                <div v-if="searchResults && searchResults[0]" class="invite-users-search-results">
                    <h5>{{ $t('search-results') }}:</h5>
                    <Table
                        :items="searchResults"
                        :fields="searchResultsFields"
                        :options="['table-sm', 'table-striped', 'table-hover']">
                        <template #username="item">
                            <router-link :to="{ name: 'profile', params: { id: item.item.id}}">
                                {{item.item.username}}
                            </router-link>
                        </template>
                        <template #actions="item">
                            <span 
                                :class="{disabled: canNotInvite(item.item.id)}"
                                class="clickable" @click="inviteUser(item.item.id)">{{ $t('invite') }}</span>
                        </template>
                    </Table>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {GroupPage} from 'resources/types/group';
import {User} from 'resources/types/user';
import {ref, computed, onMounted} from 'vue';
import {SEARCH_RESULTS_FIELDS} from '/js/constants/userConstants.js';
import {useGroupStore} from '/js/store/groupStore';
import {useUserStore} from '/js/store/userStore';
import Table from '/js/components/global/Table.vue';
import {UserSearch} from 'resources/types/global';
import {useFriendStore} from '/js/store/friendStore';
import {Friend} from 'resources/types/friend';

const groupStore = useGroupStore();
const userStore = useUserStore();
const friendStore = useFriendStore();
const searchResultsFields = SEARCH_RESULTS_FIELDS;

const loading = ref(true);

onMounted(async() => {
    loading.value = true;
    await friendStore.getFriends();
    if (group.value !== null) {
        groupMemberIds.value = group.value.members.map(member => member.id);
    }
    loading.value = false;
});

const group = computed<GroupPage | null>(() => groupStore.group);
const friends = computed<Array<Friend> | null>(() => friendStore.friends);
const groupMemberIds = ref<Array<number> | null>(null);

const searchData = ref<UserSearch>({
    userSearch: '',
});
const searchResults = ref<Array<User> | null>(null);

/** Searches for a user by their username, case-insensitive and includes all that contains the search params */
async function searchUser() {
    searchResults.value = await userStore.searchUser(searchData.value);
}

async function inviteUser(userId: number) {
    if (group.value === null || canNotInvite(userId)) return;
    loading.value = true;
    const invite = {
        user_id: userId,
        group_id: group.value.id,
    }
    await groupStore.inviteUser(invite);
    groupMemberIds.value = group.value.members.map(member => member.id);
    loading.value = false;
}

/** Checks if the user has already been invited. */
function hasInvite(userId: number) {
    if (group.value === null || group.value.invites === null || group.value.invites.length === 0) return false;
    return group.value.invites.includes(userId);
}

/** Checks if the user is already a member of the group */
function isMember(userId: number) {
    if (groupMemberIds.value === null) return false;
    return groupMemberIds.value.includes(userId);
}

/** 
 * Checks if the user is either already a member or has already been invited. If either are true, the
 * user cannot be invited.
 */
function canNotInvite(userId: number) {
    return hasInvite(userId) || isMember(userId);
}
</script>

<style lang="scss" scoped>
@import '../../../../assets/scss/variables';
.invite-friends-box {
    display: flex;
    flex-direction: column;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    padding: 0.5rem;
    margin-bottom: 1rem;
    max-height: 200px;
    overflow-y: auto;
}

// .invite-friends-content {

// }
.invite-users-search-results {
    max-height: 400px;
    overflow-y: auto;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
}
.invite-friends-action {
    margin-right: 1rem;
}
.invite-friends-action.disabled, span.disabled {
    text-decoration: line-through;
}
</style>