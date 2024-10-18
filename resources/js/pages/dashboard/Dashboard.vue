<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <div class="home-grid">
                <div class="task-lists">
                    <template v-for="(list, index) in taskLists" :key="index">
                        <TaskListComp :taskList="list" class="task-list" />
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

                    <FriendsCard v-if="!isGuest" :message="true" />

                    <TemplatesButton />
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import type {NewTaskList} from 'resources/types/task';
import TaskListComp from './components/TaskList.vue';
import RewardCard from './components/reward/RewardCard.vue';
import FriendsCard from '/js/pages/social/components/FriendsCard.vue';
import {onBeforeMount, ref, computed} from 'vue';
import {useTaskStore} from '/js/store/taskStore';
import {useRewardStore} from '/js/store/rewardStore';
import {formModal} from '/js/components/modal/modalService';
import {fetchDashboard, getNewTaskList, taskLists, tasks} from '/js/services/taskService';
import CreateEditTaskList from './components/CreateEditTaskList.vue';
import TemplatesButton from './components/template/TemplatesButton.vue';
import { isGuest } from '/js/services/userService';

const taskStore = useTaskStore();
const rewardStore = useRewardStore();

const loading = ref(true);

const rewardObj = computed(() => rewardStore.rewardObj);

onBeforeMount(async () => {
    fetchDashboard();
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
}
.new-task-list-button {
    height: 46px;
}
.border-1 {
    border: 1px solid transparent;
}
</style>
