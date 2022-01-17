<template>
    <div>
        <div class="home-grid">
            <div class="task-lists">
                <template v-for="(list, index) in taskLists">
                    <task-list 
                        :key="index" 
                        :taskList="list" 
                        class="task-list"
                        v-on:newTask="showNewTask"
                        v-on:editTask="showEditTask"
                        v-on:editTaskList="showEditTaskList"
                        v-on:deleteTaskList="showDeleteTaskList" />
                </template>
                <div class="task-list">
                    <b-button type="button" block @click="showNewTaskList">{{ $t('create-new-task-list') }}</b-button>
                </div>
            </div>

            <div class="right-align">
                <reward-summary v-if="rewardObj" class="summary-tab" 
                                :reward="rewardObj" :userReward="true" :rewardType="rewardObj.rewardType" />

                <friends-summary class="summary-tab" />
            </div>
        </div>

        <b-modal id="new-task" hide-footer :title="$t('new-task')">
            <new-task :superTask="superTask" :taskList="taskList" @close="closeNewTask" />
        </b-modal>
        <b-modal id="edit-task" hide-footer :title="$t('edit-task')">
            <edit-task :task="taskToEdit"  @close="closeEditTask"/>
        </b-modal>
        <b-modal id="new-task-list" hide-footer :title="$t('new-task-list')">
            <new-task-list @close="closeNewTaskList" />
        </b-modal>
        <b-modal id="edit-task-list" hide-footer :title="$t('edit-task-list')">
            <edit-task-list :taskList="taskListToEdit" @close="closeEditTaskList" />
        </b-modal>
        <b-modal id="delete-task-list-confirm" hide-footer :title="$t('delete-task-list-confirm')">
            <delete-task-list-confirm :taskList="taskListToDelete" @close="closeDeleteTaskList" />
        </b-modal>
    
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import NewTask from '../components/modals/NewTask.vue';
import TaskList from '../components/small/TaskList.vue';
import EditTask from '../components/modals/EditTask.vue';
import NewTaskList from '../components/modals/NewTaskList.vue';
import EditTaskList from '../components/modals/EditTaskList.vue';
import DeleteTaskListConfirm from '../components/modals/DeleteTaskListConfirm.vue';
import RewardSummary from '../components/summary/RewardSummary.vue';
import FriendsSummary from '../components/summary/FriendsSummary.vue';
export default {
    components: { 
        TaskList, 
        NewTask, 
        EditTask, 
        NewTaskList, 
        EditTaskList, 
        DeleteTaskListConfirm, 
        RewardSummary,
        FriendsSummary},
    data() {
        return {
            /** @type {import('../../types/task').Task | null} */
            superTask: null,
            /** @type {import('../../types/task').Task | null} */
            taskToEdit: null,
            /** @type {import('../../types/task').TaskList | null} */
            taskList: null,
            /** @type {import('../../types/task').TaskList | null} */
            taskListToEdit: null,
            /** @type {import('../../types/task').TaskList | null} */
            taskListToDelete: null,
        }
    },
    mounted() {
        //Fetches all dashboard data and stores it in the store
        this.$store.dispatch('getDashboard');
    },
    methods: {
        /** Shows and hides the modal to create a new task. 
         * @param {import('../../types/task').Task} superTask 
         * @param {import('../../types/task').TaskList} taskList
         */
        showNewTask(superTask, taskList) {
            this.$store.dispatch('clearErrors');
            this.superTask = superTask;
            this.taskList = taskList;
            this.$bvModal.show('new-task');
        },
        closeNewTask() {
            this.$bvModal.hide('new-task');
        },

        /** Shows and hides the modal to edit a given task
         * @param {import('../../types/task').Task} task
         */
        showEditTask(task) {
            this.$store.dispatch('clearErrors');
            this.taskToEdit = task;
            this.$bvModal.show('edit-task');
        },
        closeEditTask() {
            this.$bvModal.hide('edit-task');
        },

        /** Shows and hides the modal to create a new task list */
        showNewTaskList() {
            this.$store.dispatch('clearErrors');
            this.$bvModal.show('new-task-list');
        },
        closeNewTaskList() {
            this.$bvModal.hide('new-task-list');
        },

        /** Shows and hides the modal to edit a given task list
         * @param {import('../../types/task').TaskList} taskList
         */
        showEditTaskList(taskList) {
            this.$store.dispatch('clearErrors');
            this.taskListToEdit = taskList;
            this.$bvModal.show('edit-task-list');
        },
        closeEditTaskList() {
            this.$bvModal.hide('edit-task-list');
        },

        /** Shows and hides the modal to confirm deleting a task list
         * @param {import('../../types/task').TaskList} taskList
         */
        showDeleteTaskList(taskList) {
            this.$store.dispatch('clearErrors');
            this.taskListToDelete = taskList;
            this.$bvModal.show('delete-task-list-confirm');
        },
        closeDeleteTaskList() {
            this.$bvModal.hide('delete-task-list-confirm');
        },
    },
    computed: {
        ...mapGetters({
            taskLists: 'taskList/getTaskLists',
            rewardObj: 'reward/getRewardObj',
        }),
    },
    
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