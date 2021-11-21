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
                    <b-button type="button" block @click="showNewTaskList">Create new task list</b-button>
                </div>
            </div>

            <div class="right-align">
                <character-summary class="summary-tab" :character="character" :userCharacter="true" v-if="character"></character-summary>

                <friends-summary class="summary-tab"></friends-summary>
            </div>
        </div>

        <b-modal id="new-task" hide-footer title="New task"><new-task :superTask="superTask" :taskList="taskList" @close="closeNewTask"></new-task></b-modal>
        
        <b-modal id="edit-task" hide-footer title="Edit task"><edit-task @close="closeEditTask" :task="taskToEdit"></edit-task></b-modal>
        <b-modal id="new-task-list" hide-footer title="New task list"><new-task-list @close="closeNewTaskList"></new-task-list></b-modal>
        <b-modal id="edit-task-list" hide-footer title="Edit task list"><edit-task-list @close="closeEditTaskList" :taskList="taskListToEdit"></edit-task-list></b-modal>
        <b-modal id="delete-task-list-confirm" hide-footer title="Delete task list"><delete-task-list-confirm @close="closeDeleteTaskList" :taskList="taskListToDelete"></delete-task-list-confirm></b-modal>
    
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
            superTask: null,
            taskList: null,
            taskToEdit: null,
            taskListToEdit: null,
            taskListToDelete: null,
        }
    },
    mounted(){
        this.$store.dispatch('taskList/getTaskLists', { root:true });
        this.$store.dispatch('character/getCharacter', { root:true });
        
    },
    methods: {
        showNewTask(superTask, taskList) {
            this.$store.dispatch('clearInformationBlock');
            this.superTask = superTask;
            this.taskList = taskList;
            this.$bvModal.show('new-task');
        },
        closeNewTask() {
            this.taskList = null;
            this.superTask = null;
            this.$bvModal.hide('new-task');
        },
        showEditTask(task){
            this.$store.dispatch('clearInformationBlock');
            this.taskToEdit = task;
            this.$bvModal.show('edit-task');
        },
        closeEditTask(){
            this.taskToEdit = null;
            this.$bvModal.hide('edit-task');
        },
        showNewTaskList() {
            this.$store.dispatch('clearInformationBlock');
            this.$bvModal.show('new-task-list');
        },
        closeNewTaskList() {
            this.$bvModal.hide('new-task-list');
        },
        showEditTaskList(taskList) {
            this.$store.dispatch('clearInformationBlock');
            this.taskListToEdit = taskList;
            this.$bvModal.show('edit-task-list');
        },
        closeEditTaskList() {
            this.taskListToEdit = null;
            this.$bvModal.hide('edit-task-list');
        },
        showDeleteTaskList(taskList) {
            this.$store.dispatch('clearInformationBlock');
            this.taskListToDelete = taskList;
            this.$bvModal.show('delete-task-list-confirm');
        },
        closeDeleteTaskList() {
            this.taskListToDelete = null;
            this.$bvModal.hide('delete-task-list-confirm');
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