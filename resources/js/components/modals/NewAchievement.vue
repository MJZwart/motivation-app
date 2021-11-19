<template>
    <div>
        <transition name="modal-fade">
            <div class="modal-backdrop">
                <div class="modal">
                    <information-block></information-block>
                    <div class="form-title">
                    <h3>New achievement</h3>
                    </div>
                    <form @submit.prevent="submitAchievement">
                        <div class="form-group">
                            <label for="name">Achievement name</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                placeholder="Name" 
                                v-model="achievement.name" />
                        </div>
                        <div class="form-group">
                            <label for="description">Achievement description</label>
                            <input 
                                type="text" 
                                id="description" 
                                name="description" 
                                placeholder="Description" 
                                v-model="achievement.description" />
                        </div>
                        <div class="form-group">
                            <label for="type">Achievement type</label>
                            <select 
                                    id="type" 
                                    v-model="achievement.trigger_type">
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
                                v-model="achievement.trigger_amount" />
                        </div>
                        <div class="form-group">
                            <label for="trigger-description">Trigger description</label>
                            <p v-if="achievement.trigger_type" id="trigger-description">{{triggerDescription}}</p>
                        </div>
                        <div class="form-group">
                            <b-button type="submit" block>Create new achievement</b-button>
                            <b-button type="button" block @click="close">Cancel</b-button>
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
    components: {
        InformationBlock,
    },
    data() {
        return {
            achievement: {
                trigger_amount: 0,
            },
        }
    },
    methods: {
        submitAchievement(){
            this.$store.dispatch('admin/newAchievement', this.achievement).then(response => {
                this.close();
            });

        },
        close(){
            this.achievement = {trigger_amount: 0,},
            this.$emit('close');
        }
    },
    computed: {
        ...mapGetters({
            achievementTriggers: 'achievement/getAchievementTriggers',
        }),
        triggerDescription() {
            if(this.achievement.trigger_type){
                const plural = this.achievement.trigger_amount > 1 ? 's' : '';
                let desc = this.achievementTriggers.find(item => item.trigger_type === this.achievement.trigger_type);
                desc = desc.trigger_description.replace('%d', this.achievement.trigger_amount);
                return desc.replace('%s', plural);
            }
            
        },
    },
    
}
</script>
