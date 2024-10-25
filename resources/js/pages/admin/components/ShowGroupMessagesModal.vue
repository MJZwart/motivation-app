<template>
    <div v-if="report">
        {{ $t('reported-user') }}: {{ reportedUser.username }}
        <Loading v-if="loading" />
        <div v-else>
            <Pagination v-if="groupMessages" :items="groupMessages">
                <template #items="items">
                    <div 
                        v-for="message in items" 
                        :key="message.id" 
                        class="group-message" 
                        :class="{'reported-message': reportedUser.id === message.user.id}">
                        <p>{{message.message}}</p>
                        <p class="silent d-flex">
                            {{message.user.username}} - {{parseDateTime(message.created_at)}}
                        </p>
                    </div>
                </template>
            </Pagination>
        </div>
    </div>
</template>

<script setup lang="ts">
import {UserToSuspend} from 'resources/types/user';
import {computed, onMounted, ref} from 'vue';
import {GroupMessage} from 'resources/types/group';
import Loading from '/js/components/global/Loading.vue';
import {parseDateTime} from '/js/services/dateService';
import Pagination from '/js/components/global/Pagination.vue';
import axios from 'axios';

const props = defineProps<{reportedUser: UserToSuspend, reportId: number}>();

const loading = ref(true);
const report = computed(() => props.reportedUser.reports.find(userReport => userReport.id === props.reportId));
const groupMessages = ref<GroupMessage[] | null>(null);

onMounted(async() => {
    if (!report.value || !report.value.group_id) return;
    const {data} = await axios.put(`/admin/groups/${report.value?.group_id}/messages/daterange`, {date: report.value.reported_date});
    groupMessages.value = data.data.messages;
    loading.value = false;
});
</script>

<style lang="scss" scoped>
.reported-message {
    background-color: var(--background-darker);
    border-radius: 0.5rem;
}
.group-message {
    padding: 0.05rem 0.5rem 0.05rem 0.5rem;
    border-radius: 0.25rem;
    p {
        margin: 0.8rem 0.5rem 0.8rem 0.5rem;
    }
    margin-bottom:0.25rem;
}
</style>