<template>
    <div>
        <transition name="modal-fade">
            <div class="modal-backdrop">
                <div class="modal">
                    <information-block></information-block>

                    <div class="form-title">
                    <h3>Edit task list</h3>
                    </div>
                    <form @submit.prevent="updateTaskList">
                        <div class="form-group">
                            <label for="name">Task list name</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                placeholder="Name" 
                                v-model="editedTaskList.name" />
                        </div>
                        <div class="form-group">
                            <b-button type="submit" block>Update task list</b-button>
                            <b-button type="button" block @click="close">Cancel</b-button>
                        </div>
                    </form>
                </div>
            </div>
            
        </transition>
    </div>
</template>


<script>
import InformationBlock from '../InformationBlock.vue';
export default {
    components: {InformationBlock},
    props: {
        taskList: Object,
    },
    data() {
        return {
            editedTaskList: {},
        }
    },
    mounted(){
        this.taskList ? this.editedTaskList = this.taskList : this.editedTaskList = {};
    },
    methods: {
        updateTaskList(){
            var self = this;
            this.$store.dispatch('taskList/updateTaskList', this.editedTaskList).then(function(){
                self.close();
            });

        },
        close(){
            this.editedTaskList = {},
            this.$emit('close');
        }
    },
}
</script>
