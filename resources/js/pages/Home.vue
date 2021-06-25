<template>
    <div>
        <div class="home-grid">
            <div class="task-lists">
                <template v-for="(list, index) in taskLists">
                    <task-list 
                        :key="index" 
                        :taskList="list" 
                        class="task-list border"
                        v-on:newTask="showNewTask"
                        v-on:editTask="showEditTask"></task-list>
                </template>
                <div class="task-list border">
                    <h3>Create new task list</h3>
                </div>
            </div>

            <div class="right-align">
                <div class="character-tab border">
                    <div class="character">
                        <h3>Character</h3>
                        <p>Dummy text</p>
                        <p>Dummy text</p>
                        <p>Dummy text</p>
                    </div>
                </div>

                <div class="friends-list border">
                    <div class="friends">
                        <h3>Friends</h3>
                        <p>Dummy text</p>
                        <p>Dummy text</p>
                        <p>Dummy text</p>
                    </div>
                </div>
            </div>
        </div>

        <new-task v-show="isNewTaskVisible" @close="closeNewTask" :superTask="superTask" :taskList="taskList" v-on:reload="getTasks"></new-task>
        <edit-task v-if="isEditTaskVisible" @close="closeEditTask" :task="taskToEdit" v-on:reload="getTasks"></edit-task>
        
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import NewTask from '../components/NewTask.vue';
import TaskList from '../components/TaskList.vue';
import EditTask from '../components/EditTask.vue';
export default {
    components: { TaskList, NewTask, EditTask},
    data(){
        return {
            isNewTaskVisible: false,
            isEditTaskVisible: false,
            superTask: null,
            taskList: null,
            taskToEdit: null,
        }
    },
    mounted(){
        this.getTasks();
    },
    methods: {
        getTasks(){
            this.$store.dispatch('getTaskLists');
        },
        showNewTask(superTask, taskList) {
            this.$store.dispatch('clearInformationBlock');
            this.superTask = superTask;
            this.taskList = taskList;
            this.isNewTaskVisible = true;
        },
        closeNewTask() {
            this.isNewTaskVisible = false;
        },
        showEditTask(task){
            this.$store.dispatch('clearInformationBlock');
            this.taskToEdit = task;
            this.isEditTaskVisible = true;
        },
        closeEditTask(){
            this.isEditTaskVisible = false;
        },
    },
    computed: {
        ...mapGetters({
            taskLists: 'getTaskLists',
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
        border: 2px solid teal;
        border-radius: 20px;
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
    }
    .character-tab, .friends-list{
        width:100%;
        height:fit-content;
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