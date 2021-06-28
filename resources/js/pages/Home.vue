<template>
    <div>
        <div class="home-grid">
            <div class="task-lists">
                <template v-for="(list, index) in taskLists">
                    <task-list :key="index" :taskList="list" class="task-list" v-on:newTask="showNewTask"></task-list>
                </template>
                <div class="task-list">
                    <h3>Create new task list</h3>
                </div>
            </div>

            <div class="right-align">
                <div class="character-tab">
                    <div class="character">
                        <h3>Character</h3>
                        <p>Dummy text</p>
                        <p>Dummy text</p>
                        <p>Dummy text</p>
                    </div>
                </div>

                <div class="friends-list">
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
        
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import NewTask from '../components/NewTask.vue';
import TaskList from '../components/TaskList.vue';
export default {
    components: { TaskList, NewTask},
    data(){
        return {
            isNewTaskVisible: false,
            superTask: null,
            taskList: null,
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
            this.superTask = superTask;
            this.taskList = taskList;
            this.isNewTaskVisible = true;
        },
        closeNewTask() {
            this.isNewTaskVisible = false;
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
    .character-tab, .friends-list{
        width:100%;
        height:fit-content;
    }

    .side-border {
        border-left: 1px solid teal;
        border-right: 1px solid teal;
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