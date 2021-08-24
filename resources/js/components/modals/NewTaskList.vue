<template>
    <div>
        <transition name="modal-fade">
            <div class="modal-backdrop">
                <div class="modal">
                    <information-block></information-block>
                    <div class="form-title">
                    <h3>New task list</h3>
                    </div>
                    <form @submit.prevent="submitTaskList">
                        <div class="form-group">
                            <label for="name">Task list name</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                placeholder="Name" 
                                v-model="taskList.name" />
                        </div>
                        <div class="form-group">
                            <button type="submit" class="long-button">Create new task list</button>
                            <button type="button" class="long-button" @click="close">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            
        </transition>
    </div>
</template>


<script>
import InformationBlock from './InformationBlock.vue';
export default {
    components: {
        InformationBlock,
    },
    data() {
        return {
            taskList: {},
        }
    },
    methods: {
        submitTaskList(){
            var self = this;
            this.$store.dispatch('taskList/storeTaskList', this.taskList).then(function(){
                self.close();
            });

        },
        close(){
            this.taskList = {},
            this.$emit('close');
        }
    },
}
</script>
