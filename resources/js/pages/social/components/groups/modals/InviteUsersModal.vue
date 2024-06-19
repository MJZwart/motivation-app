<template>
    <Loading v-if="loading" />
    <div v-else>
        <div id="invite-users-box">
            <h4>{{ $t('search-by-username') }}</h4>
            <!-- The search bar -->
            <form class="flex-row mb-3" @submit.prevent>
                <input
                    id="search-users"
                    v-model="searchData.userSearch"
                    type="search"
                    :placeholder="$t('search-user')"
                    aria-label="Search user"
                    class="w-80 mr-2"
                />
                <SubmitButton @click="searchUser" class="w-15">{{ $t('search') }}</SubmitButton>
            </form>

            <!-- The search results -->
            <div v-if="filteredSearchResults && filteredSearchResults[0]" class="invite-users-search-results">
                <h5>{{ $t('search-results') }}:</h5>
                <Table
                    :items="filteredSearchResults"
                    :fields="searchResultsFields"
                    :options="['table-sm', 'table-striped', 'table-hover']"
                >
                    <template #username="row">
                        <router-link :to="{name: 'profile', params: {id: row.item.id}}">
                            {{ row.item.username }}
                        </router-link>
                    </template>
                    <template #actions="row">
                        <span
                            :class="{disabled: canNotInvite(row.item.id)}"
                            class="clickable"
                            @click="inviteUser(row.item.id)"
                        >
                            {{ $t('invite') }}
                        </span>
                    </template>
                </Table>
            </div>
        </div>
        <div v-if="friends !== null && friends.length > 0">
            <hr />
            <div class="invite-friends-box">
                <h4 class="block">{{ $t('invite-friends') }}</h4>
                <div class="invite-friends-content">
                    <template v-for="(friend, index) in friends" :key="index">
                        <span
                            class="clickable mr-3"
                            :class="{disabled: canNotInvite(friend.id)}"
                            @click="inviteUser(friend.id)"
                        >
                            {{ $t('invite') }}
                        </span>
                        <span>{{ friend.username }}</span>
                        <br />
                    </template>
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

const props = defineProps<{group: GroupPage}>();

onMounted(async () => {
    loading.value = true;
    await friendStore.getFriends();
    if (props.group !== null) {
        groupMemberIds.value = props.group.members.map(member => member.id);
    }
    loading.value = false;
});
defineEmits(['close']);

const friends = computed<Array<Friend> | null>(() => friendStore.friends);
const groupMemberIds = ref<Array<number> | null>(null);

const searchData = ref<UserSearch>({
    userSearch: '',
});

const filteredSearchResults = computed(() => {
    if (!searchResults.value) return null;
    return searchResults.value.filter(user => !user.guest);
})
const searchResults = ref<Array<User> | null>(null);

/** Searches for a user by their username, case-insensitive and includes all that contains the search params */
async function searchUser() {
    searchResults.value = await userStore.searchUser(searchData.value);
}

async function inviteUser(userId: number) {
    if (props.group === null || canNotInvite(userId)) return;
    loading.value = true;
    const invite = {
        user_id: userId,
        group_id: props.group.id,
    };
    await groupStore.inviteUser(invite);
    groupMemberIds.value = props.group.members.map(member => member.id);
    loading.value = false;
}

/** Checks if the user has already been invited. */
function hasInvite(userId: number) {
    if (props.group === null || props.group.invites === null || props.group.invites.length === 0) return false;
    return props.group.invites.includes(userId);
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

.invite-friends-box {
    display: flex;
    flex-direction: column;
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
}
.invite-friends-action.disabled,
span.disabled {
    text-decoration: line-through;
    cursor: default !important;
}
</style>
