<template>
    <Loading v-if="loading" />
    <div v-else>
        <ContentBlock title="blocklist" tutorial>
            <ul class="no-list-style">
                <div v-if="blocklist[0]">
                    <li v-for="(blockedUser, index) in blocklist" :key="index" class="mb-1 ml-0 d-flex flex-wrap">
                        <Tooltip :text="$t('unblock')">
                            <Icon :icon="UNLOCK" class="unlock-icon green" @click="unblock(blockedUser)" />
                        </Tooltip>
                        <router-link
                            :to="{name: 'profile', params: {id: blockedUser.blocked_user_id}}"
                            class="flex-1"
                        >
                            {{ blockedUser.blocked_user }}
                        </router-link>
                        <span class="flex-2">
                            {{ $t('blocked-on') }}: {{ parseDateTime(blockedUser.created_at) }}
                        </span>
                    </li>
                </div>

                <p v-else>{{ $t('no-blocked-users') }}</p>
            </ul>
        </ContentBlock>
    </div>
</template>

<script setup lang="ts">
import type {Blocked} from 'resources/types/user';
import {onMounted, ref} from 'vue';
import {parseDateTime} from '/js/services/dateService';
import {useUserStore} from '/js/store/userStore';
import {UNLOCK} from '/js/constants/iconConstants';
import ConfirmUnblock from '../components/ConfirmUnblock.vue';
import {formModal} from '/js/components/modal/modalService';

const userStore = useUserStore();

onMounted(() => {
    load();
});

const loading = ref(true);

const blocklist = ref<Blocked[] | []>([]);
async function load() {
    loading.value = true;
    blocklist.value = await userStore.getBlocklist();
    loading.value = false;
}

async function unblock(blockedUser: Blocked) {
    // @ts-ignore Modal shenanigans
    formModal(blockedUser, ConfirmUnblock, submitUnblock, 'confirm-unblock');
}
async function submitUnblock({blockedUser, restoreMessages}: {blockedUser: Blocked, restoreMessages: boolean}) {
    await userStore.unblockUser(blockedUser.id, {'restoreMessages': restoreMessages});
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
