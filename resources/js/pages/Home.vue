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
                        v-on:deleteTaskList="showDeleteTaskList"></task-list>
                </template>
                <div class="task-list">
                    <button type="button" class="long-button" @click="showNewTaskList">Create new task list</button>
                </div>
            </div>

            <div class="right-align">
                <character-summary class="summary-tab" :character="character" :userCharacter="true" v-if="character"></character-summary>

                <friends-summary class="summary-tab"></friends-summary>
            </div>
        </div>

        <new-task v-show="isNewTaskVisible" @close="closeNewTask" :superTask="superTask" :taskList="taskList"></new-task>
        <edit-task v-if="isEditTaskVisible" @close="closeEditTask" :task="taskToEdit"></edit-task>
        <new-task-list v-show="isNewTaskListVisible" @close="closeNewTaskList"></new-task-list>
        <edit-task-list v-if="isEditTaskListVisible" @close="closeEditTaskList" :taskList="taskListToEdit"></edit-task-list>

        <delete-task-list-confirm v-if="isDeleteTaskListVisible" @close="closeDeleteTaskList" :taskList="taskListToDelete"></delete-task-list-confirm>
        
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
import CharacterSummary from '../components/summary/CharacterSummary.vue';
import FriendsSummary from '../components/summary/FriendsSummary.vue';
export default {
    components: { TaskList, NewTask, EditTask, NewTaskList, EditTaskList, DeleteTaskListConfirm, CharacterSummary, FriendsSummary},
    data(){
        return {
            isNewTaskListVisible: false,
            isNewTaskVisible: false,
            isEditTaskVisible: false,
            isEditTaskListVisible: false,
            isDeleteTaskListVisible: false,
            superTask: null,
            taskList: null,
            taskToEdit: null,
            taskListToEdit: null,
            taskListToDelete: null,
        }
    },
    mounted(){
        this.$store.dispatch('taskList/getTaskLists', { root:true });
        this.$store.dispatch('character/fetchCharacter', { root:true });
    },
    methods: {
        showNewTask(superTask, taskList) {
            this.$store.dispatch('clearInformationBlock');
            this.superTask = superTask;
            this.taskList = taskList;
            this.isNewTaskVisible = true;
        },
        closeNewTask() {
            this.taskList = null;
            this.superTask = null;
            this.isNewTaskVisible = false;
        },
        showEditTask(task){
            this.$store.dispatch('clearInformationBlock');
            this.taskToEdit = task;
            this.isEditTaskVisible = true;
        },
        closeEditTask(){
            this.taskToEdit = null;
            this.isEditTaskVisible = false;
        },
        showNewTaskList() {
            this.$store.dispatch('clearInformationBlock');
            this.isNewTaskListVisible = true;
        },
        closeNewTaskList() {
            this.isNewTaskListVisible = false;
        },
        showEditTaskList(taskList) {
            this.$store.dispatch('clearInformationBlock');
            this.taskListToEdit = taskList;
            this.isEditTaskListVisible = true;
        },
        closeEditTaskList() {
            this.taskListToEdit = null;
            this.isEditTaskListVisible = false;
        },
        showDeleteTaskList(taskList) {
            this.$store.dispatch('clearInformationBlock');
            this.taskListToDelete = taskList;
            this.isDeleteTaskListVisible = true;
        },
        closeDeleteTaskList() {
            this.taskListToDelete = null;
            this.isDeleteTaskListVisible = false;
        },
    },
    computed: {
        ...mapGetters({
            taskLists: 'taskList/getTaskLists',
            character: 'character/getCharacter',
        }),
    },
    
}
</script>


<style>
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
    .border{
        border: 1px solid teal;
        border-radius: 6px;
    }
    .task-lists{
        flex:74%;
        display: flex;
        flex-wrap: wrap;
        gap:10px;
    }
    .task-list{
        flex: 49%;
        height: fit-content;
        border-bottom-left-radius: 6px;
        border-bottom-right-radius: 6px;
    }
    .summary-tab, .summary-list{
        width:100%;
        height:fit-content;
    }

    .side-border {
        border-left: 1px solid teal;
        border-right: 1px solid teal;
    }
    .bottom-border {
        border-bottom: 1px solid teal;
        border-bottom-left-radius: 6px;
        border-bottom-right-radius: 6px;
    }
    @media (max-width:1000px){
        .task-list{
            flex: 100%;
        }
    }
    
    @media (max-width:730px){
        .task-lists{
            flex:100%;
        }
    }
</style>