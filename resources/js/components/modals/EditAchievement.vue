<template>
    <div>
        <transition name="modal-fade">
            <div class="modal-backdrop">
                <div class="modal">
                    <information-block></information-block>
                    <div class="form-title">
                    <h3>Edit achievement</h3>
                    </div>
                    <form @submit.prevent="updateAchievement">
                        <div class="form-group">
                            <label for="name">Achievement name</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                placeholder="Name" 
                                v-model="achievementToEdit.name" />
                        </div>
                        <div class="form-group">
                            <label for="description">Achievement description</label>
                            <input 
                                type="text" 
                                id="description" 
                                name="description" 
                                placeholder="Description" 
                                v-model="achievementToEdit.description" />
                        </div>
                        <div class="form-group">
                            <label for="type">Achievement type</label>
                            <select 
                                    id="type" 
                                    v-model="achievementToEdit.trigger_type">
                                    <option v-for="option in achievementTriggers" :value="option.trigger_type" :key="option.key">{{option.trigger_type}}</option>
                                </select>
                        </div>
                        <div class="form-group">
                            <label for="amount">Trigger amount</label>
                            <input 
                                type="number" 
                                id="amount" 
                                name="amount" 
                                placeholder="Amount" 
                                v-model="achievementToEdit.trigger_amount" />
                        </div>
                        <div class="form-group">
                            <label for="trigger-description">Trigger description</label>
                            <p v-if="achievementToEdit.trigger_type" id="trigger-description">{{triggerDescription}}</p>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="long-button">Edit achievement</button>
                            <button type="button" class="long-button" @click="close">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            
        </transition>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import InformationBlock from '../InformationBlock.vue';
export default {
    props: {
        achievement: Object,
    },
    mounted() {
        this.achievementToEdit = this.achievement;
        //console.log(this.achievementTriggers.find(item => item.trigger_type == 'TASKS_MADE'));
        //this.achievementToEdit.trigger_type = this.triggerDescription;
    },
    components: {
        InformationBlock,
    },
    data() {
        return {
            achievementToEdit: {
                
            },
        }
    },
    methods: {
        updateAchievement(){
            this.$store.dispatch('admin/editAchievement', this.achievementToEdit).then(response => {
                this.close();
            });

        },
        close(){
            this.achievementToEdit = {},
            this.$emit('close');
        }
    },
    computed: {
        ...mapGetters({
            achievementTriggers: 'achievement/getAchievementTriggers',
        }),
        triggerDescription() {
            if(this.achievementToEdit.trigger_type){
                const plural = this.achievement.trigger_amount > 1 ? 's' : '';
                let desc = this.achievementTriggers.find(item => item.trigger_type === this.achievement.trigger_type);
                desc = desc.trigger_description.replace('%d', this.achievement.trigger_amount);
                return desc.replace('%s', plural);
            }
        },
    },
    
}
</script>
