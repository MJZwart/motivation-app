<template>
    <Loading v-if="loading" />
    <div v-if="blockedUsers[0]">
        <h5>{{$t('blocked-users')}}</h5>
        <ul class="no-list-style">
            <li v-for="user in blockedUsers" :key="user.id" class="d-flex">
                {{user.username}}
                <span class="ml-auto mr-3">
                    <Tooltip :text="$t('unblock')">
                        <Icon
                            :icon="UNLOCK"
                            class="unlock-icon green"
                            @click="unblock(user.id)" />
                    </Tooltip>
                </span>
            </li>
        </ul>
    </div>
    <div v-else>
        {{$t('no-blocked-users')}}
    </div>
</template>

<script setup lang="ts">
import Loading from '/js/components/global/Loading.vue';
import {onMounted, ref} from 'vue';
import {UNLOCK} from '/js/constants/iconConstants';
import axios from 'axios';

export type GroupBlockedUser = {
    id: number;
    username: string;
    suspended: Date;
}

const loading = ref(true);

const props = defineProps<{groupId: number}>();

defineEmits(['close']);

const blockedUsers = ref<GroupBlockedUser[]>([]);

onMounted(async() => {
    const {data} = await axios.get(`/groups/blocked/${props.groupId}`);
    blockedUsers.value = data.blockedUsers;
    loading.value = false;
});

async function unblock(userId: number) {
    const {data} = await axios.post(`/groups/unblock/${props.groupId}`, {userId: userId});
    blockedUsers.value = data.data.blockedUsers;
}
</script>