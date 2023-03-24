<template>
    <Loading v-if="loading" />
    <div v-else>
        <Summary :title="$t('blocklist')">
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
        </Summary>
        <Modal :show="showUnblockUserModal" :title="$t('confirm-unblock')" @close="closeUnblockUserModal()">
            <ConfirmUnblockModal v-if="userToUnblock" :blocked-user="userToUnblock" @close="closeUnblockUserModal($event)" />
        </Modal>
    </div>
</template>

<script setup lang="ts">
import Summary from '/js/components/global/Summary.vue';
import {onMounted, ref} from 'vue';
import {parseDateTime} from '/js/services/dateService';
import {useUserStore} from '/js/store/userStore';
import type {Blocked} from 'resources/types/user';
import {UNLOCK} from '/js/constants/iconConstants';
import ConfirmUnblockModal from '../components/ConfirmUnblock.vue';

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

const userToUnblock = ref<Blocked | null>(null);
const showUnblockUserModal = ref(false);

async function unblock(blocklist: Blocked) {
    userToUnblock.value = blocklist;
    showUnblockUserModal.value = true;
}
function closeUnblockUserModal(reload = false) {
    userToUnblock.value = null;
    showUnblockUserModal.value = false;
    if (reload) load();
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
