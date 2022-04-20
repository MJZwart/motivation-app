<template>
    <div>
        <Loading v-if="loading" />
        <div v-else>
            <div class="home-grid">
                <div class="task-lists">
                    <template v-for="(list, index) in taskLists" :key="index">
                        <TaskList 
                            :taskList="list" 
                            class="task-list"
                            v-on:editTaskList="showEditTaskList"
                            v-on:deleteTaskList="showDeleteTaskList" />
                    </template>
                    <div class="task-list">
                        <button type="button" class="block" @click="showNewTaskList">{{ $t('create-new-task-list') }}</button>
                    </div>
                </div>

                <div class="right-align">
                    <RewardCard v-if="rewardObj" class="summary-tab" 
                                :reward="rewardObj" :userReward="true" :rewardType="rewardObj.rewardType" />

                    <FriendsCard :message="true" :manage="false" />
                </div>
            </div>

            <BModal :show="showNewTaskListModal" :footer="false" :title="$t('new-task-list')" @close="closeNewTaskList">
                <new-task-list @close="closeNewTaskList" />
            </BModal>
            <BModal :show="showEditTaskListModal" :footer="false" :title="$t('edit-task-list')" @close="closeEditTaskList">
                <edit-task-list :taskList="taskListToEdit" @close="closeEditTaskList" />
            </BModal>
            <BModal 
                :show="showDeleteTaskListConfirmModal" 
                :footer="false" 
                :title="$t('delete-task-list-confirm')" 
                @close="closeDeleteTaskList">
                <DeleteTaskListConfirm :taskList="taskListToDelete" @close="closeDeleteTaskList" />
            </BModal>
        
        </div>
    </div>
</template>


<script setup>
import TaskList from '../components/small/TaskList.vue';
import NewTaskList from '../components/modals/NewTaskList.vue';
import EditTaskList from '../components/modals/EditTaskList.vue';
import DeleteTaskListConfirm from '../components/modals/DeleteTaskListConfirm.vue';
import RewardCard from '../components/summary/RewardCard.vue';
import FriendsCard from '../components/summary/FriendsCard.vue';
import Loading from '../components/Loading.vue';
import BModal from '../components/bootstrap/BModal.vue';
import {useMainStore} from '@/store/store';
import {onBeforeMount, ref, computed} from 'vue';
import {useTaskStore} from '@/store/taskStore';
import {useRewardStore} from '@/store/rewardStore';

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

/** @type {import('../../types/task').TaskList | null} */
const taskListToEdit = ref();
/** @type {import('../../types/task').TaskList | null} */
const taskListToDelete = ref();
// /** @type {import('../../types/task').TaskList | null} */
// const taskList = reactive({});

const showNewTaskListModal = ref(false);
const showEditTaskListModal = ref(false);
const showDeleteTaskListConfirmModal = ref(false);

/** Shows and hides the modal to create a new task list */
function showNewTaskList() {
    mainStore.clearErrors();
    showNewTaskListModal.value = true;
}
function closeNewTaskList() {
    showNewTaskListModal.value = false;
}

/** Shows and hides the modal to edit a given task list
 * @param {import('../../types/task').TaskList} taskList
 */
function showEditTaskList(taskList) {
    mainStore.clearErrors();
    taskListToEdit.value = taskList;
    showEditTaskListModal.value = true;
}
function closeEditTaskList() {
    showEditTaskListModal.value = false;
}

/** Shows and hides the modal to confirm deleting a task list
 * @param {import('../../types/task').TaskList} taskList
 */
function showDeleteTaskList(taskList) {
    mainStore.clearErrors();
    taskListToDelete.value = taskList;
    showDeleteTaskListConfirmModal.value = true;
}
function closeDeleteTaskList() {
    showDeleteTaskListConfirmModal.value = false;
}
    
</script>

<style lang="scss">
.home-grid {
    display: flex;
    flex-wrap: wrap;
    gap:10px;
}
.right-align {
    flex:24%;
    display: flex;
    flex-wrap: wrap;
    flex-direction: column;
    gap:10px;
}
</style>