<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <FriendRequests class="mb-2" />
            
            <FriendsCard id="friendships" :manage="true" :message="true" class="mb-2" />

            <SearchUser @reload="load" />
        </div>
    </div>
</template>


<script setup>
import FriendsCard from '/js/pages/dashboard/components/FriendsCard.vue';
import FriendRequests from '../components/FriendRequests.vue';
import SearchUser from '../components/SearchUser.vue';
import {onMounted, ref} from 'vue';
import {useFriendStore} from '/js/store/friendStore';

const friendStore = useFriendStore();

onMounted(() => {
    load();
});

const loading = ref(true);

async function load() {
    loading.value = true;
    await friendStore.getRequests();
    loading.value = false;
}
</script>