<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <div class="home-grid">
                <div class="task-lists">
                    <template v-for="(list, index) in taskLists" :key="index">
                        <TaskList :taskList="list" class="task-list" />
                    </template>
                    <div class="task-list border-1">
                        <button type="button" class="block new-task-list-button" @click="showNewTaskList">
                            {{ $t('create-new-task-list') }}
                        </button>
                    </div>
                </div>

                <div class="right-align">
                    <RewardCard
                        v-if="rewardObj"
                        class="summary-tab"
                        :reward="rewardObj"
                        :userReward="true"
                        :rewardType="rewardObj.rewardType"
                    />

                    <FriendsCard :message="true" />
                </div>
            </div>

            <Modal :show="showNewTaskListModal" :footer="false" :title="$t('new-task-list')" @close="closeNewTaskList">
                <NewTaskList @close="closeNewTaskList" />
            </Modal>
        </div>
    </div>
</template>

<script setup lang="ts">
import TaskList from './components/TaskList.vue';
import NewTaskList from './components/NewTaskList.vue';
import RewardCard from './components/RewardCard.vue';
import FriendsCard from './components/FriendsCard.vue';
import {useMainStore} from '/js/store/store';
import {onBeforeMount, ref, computed} from 'vue';
import {useTaskStore} from '/js/store/taskStore';
import {useRewardStore} from '/js/store/rewardStore';

const mainStore = useMainStore();
const taskStore = useTaskStore();
const rewardStore = useRewardStore();

const loading = ref(true);

const taskLists = computed(() => taskStore.taskLists);
const rewardObj = computed(() => rewardStore.rewardObj);

onBeforeMount(async () => {
    //Fetches all dashboard data and stores it in the store
    await mainStore.getDashboard();
    loading.value = false;
});

const showNewTaskListModal = ref(false);

/** Shows and hides the modal to create a new task list */
function showNewTaskList() {
    mainStore.clearErrors();
    showNewTaskListModal.value = true;
}
function closeNewTaskList() {
    showNewTaskListModal.value = false;
}
</script>

<style lang="scss">
.home-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.right-align {
    flex: 24%;
    display: flex;
    flex-wrap: wrap;
    flex-direction: column;
    gap: 10px;
}
.new-task-list-button {
    height: 46px;
}
.border-1 {
    border: 1px solid transparent;
}
</style>
