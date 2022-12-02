<template>
    <Loading v-if="loading" />
    <div v-if="blockedUsers[0]">
        <h5>{{$t('blocked-users')}}</h5>
        <ul class="no-list-style">
            <li v-for="user in blockedUsers" :key="user.id" class="d-flex">
                {{user.username}}
                <span class="ml-auto mr-3">
                    <Tooltip :text="$t('unblock')">
                        <FaIcon
                            icon="lock-open"
                            class="icon small green"
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
import {useGroupStore} from '/js/store/groupStore';

export type GroupBlockedUser = {
    id: number;
    username: string;
    suspended: Date;
}

const groupStore = useGroupStore();
const loading = ref(true);

const props = defineProps({
    groupId: {
        type: Number,
        required: true,
    }, 
});

const blockedUsers = ref<GroupBlockedUser[]>([]);

onMounted(async() => {
    blockedUsers.value = await groupStore.getBlockedUsers(props.groupId);
    loading.value = false;
});

async function unblock(userId: number) {
    blockedUsers.value = await groupStore.unblockUser(props.groupId, userId);
}
</script>