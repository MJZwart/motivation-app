<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <div class="home-grid">
                <div class="task-lists">
                    <template v-for="(list, index) in taskLists" :key="index">
                        <TaskList :taskList="list" class="task-list" />
                    </template>
                    <div class="task-list-button border-1">
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

                    <TemplatesButton />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import type {NewTaskList} from 'resources/types/task';
import TaskList from './components/TaskList.vue';
import RewardCard from './components/reward/RewardCard.vue';
import FriendsCard from '/js/pages/social/components/FriendsCard.vue';
import {onBeforeMount, ref, computed} from 'vue';
import {useTaskStore} from '/js/store/taskStore';
import {useRewardStore} from '/js/store/rewardStore';
import {useUserStore} from '/js/store/userStore';
import {formModal} from '/js/components/modal/modalService';
import {getNewTaskList} from './taskService';
import CreateEditTaskList from './components/CreateEditTaskList.vue';
import TemplatesButton from './components/template/TemplatesButton.vue';

const userStore = useUserStore();
const taskStore = useTaskStore();
const rewardStore = useRewardStore();

const loading = ref(true);

const taskLists = computed(() => taskStore.taskLists);
const rewardObj = computed(() => rewardStore.rewardObj);

onBeforeMount(async () => {
    //Fetches all dashboard data and stores it in the store
    await userStore.getDashboard();
    taskStore.getTemplates();
    loading.value = false;
});

/** Shows and hides the modal to create a new task list */
function showNewTaskList() {
    formModal(
        getNewTaskList(), 
        CreateEditTaskList, 
        submitNewTaskList, 
        'new-task-list',
    );
}
async function submitNewTaskList(newTaskList: NewTaskList) {
    await taskStore.storeTaskList(newTaskList);
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
.task-list-button {
    flex:49%;
    margin: calc(0.25rem - 1px);
}
.new-task-list-button {
    height: 46px;
}
.border-1 {
    border: 1px solid transparent;
}
</style>
