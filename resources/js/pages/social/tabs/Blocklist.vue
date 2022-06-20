<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <Summary :title="$t('blocklist')">
                <ul class="no-list-style">
                    <div v-if="blocklist[0]">
                        <li v-for="(blockedUser, index) in blocklist" :key="index" class="mb-1 ml-0 d-flex flex-wrap">
                            <Tooltip :text="$t('unblock')">
                                <FaIcon 
                                    icon="lock-open"
                                    class="icon green"
                                    @click="unblock(blockedUser.id)" />
                            </Tooltip>
                            <router-link :to="{ name: 'profile', params: { id: blockedUser.blocked_user_id}}" class="flex-1">
                                {{blockedUser.blocked_user}}
                            </router-link>
                            <span class="flex-2">
                                {{$t('blocked-on')}}: {{blockedUser.created_at}}
                            </span>
                        </li>
                    </div>
                    
                    <p v-else>{{ $t('no-blocked-users') }}</p>
                </ul>
            </Summary>
        </div>
    </div>
</template>

<script setup>
import Summary from '/js/components/global/Summary.vue';
import {onMounted, ref} from 'vue';
import {useUserStore} from '/js/store/userStore';

const userStore = useUserStore();

onMounted(() => {
    load();
});

const loading = ref(true);

const blocklist = ref([]);
async function load() {
    loading.value = true;
    blocklist.value = await userStore.getBlocklist();
    loading.value = false;
}

async function unblock(blocklistId) {
    await userStore.unblockUser(blocklistId);
    load();
}
</script>

<style lang="scss">
.flex-1 {
    flex-grow: 1;
}
.flex-2 {
    flex-grow: 2;
}
</style>