<template>
    <div>
        <div v-if="firstModal">
            <transition name="modal-fade">
                <div class="modal-backdrop">
                    <div class="modal">
                        <information-block></information-block>

                        <div class="form-title">
                        <h3>Welcome!</h3>
                        <p class="silent">Hold up, you're not quite done yet.</p>
                        </div>
                        <form @submit.prevent="confirmDisplayName">
                            <div class="form-group">
                                <label for="name">Full display name (required)</label>
                                <p class="silent">This will be the name other players can find you on and will be displayed on your public profile.</p>
                                <input 
                                    type="text" 
                                    id="full_display_name" 
                                    name="full_display_name" 
                                    placeholder="Display name" 
                                    v-model="user.full_display_name" />
                            </div>
                            <div class="form-group">
                                <button type="submit" class="long-button">Next</button>
                            </div>
                        </form>
                    </div>
                </div>
            </transition>
        </div>
        <div v-if="secondModal">
            <transition name="modal-fade">
                <div class="modal-backdrop">
                    <div class="modal">
                        <information-block></information-block>

                        <div class="form-title">
                        <h3>Just a little more</h3>
                        <p class="silent">To get you started, you can pick a few of these example tasks to directly put into your task lists.</p>
                        </div>
                        <form @submit.prevent="confirmExampleTasks">
                            <div class="form-group">
                                <label for="name">Example tasks</label>
                                <div class="examples-slot">
                                    <b-form-checkbox-group v-model="user.tasks">
                                    <div v-for="task in exampleTasks" :key="task.id" @click="toggleTask(task.id)">
                                        <b-form-checkbox :value="task.id">
                                            <p class="task-title flex label-override">
                                                {{task.name}}
                                            </p>
                                            <p class="task-description label-override">{{task.description}}</p>
                                        </b-form-checkbox>
                                    </div>
                                    </b-form-checkbox-group>
                                </div>
                                
                            </div>
                            <div class="form-group">
                                <button type="submit" class="long-button">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script>
import InformationBlock from '../components/InformationBlock.vue';
import { mapGetters } from 'vuex';
    export default {
        mounted () {
            this.$store.dispatch('task/fetchExampleTasks');
        },
        components: {
            InformationBlock,
        },
        data() {
            return {
                user: {
                    tasks: [],
                },
                firstModal: true,
                secondModal: false,
            }
        },
        methods: {
            confirmDisplayName() {
                this.firstModal = false;
                this.secondModal = true;
            },
            confirmExampleTasks() {
                this.$store.dispatch('user/confirmRegister', user);
                //TODO
            },
        },
        computed: {
            ...mapGetters({
                exampleTasks: 'task/getExampleTasks',
            }),
        },
    }
</script>

<style>
.examples-slot{
    height:500px;
    overflow-y:scroll;
}
.label-override{
    text-transform:none;
    letter-spacing:0;
}
</style>