<template>
    <div>
        <b-form @submit.prevent="submitAchievement">
            <b-form-group
                :label="$t('achievement-name')" 
                label-for="name">
                <b-form-input 
                    type="text" 
                    id="name" 
                    name="name" 
                    :placeholder="$t('name')" 
                    v-model="achievement.name" />
            </b-form-group>
            <b-form-group
                :label="$t('achievement-description')" 
                label-for="description">
                <b-form-input 
                    type="text" 
                    id="description" 
                    name="description" 
                    :placeholder="$t('description')" 
                    v-model="achievement.description" />
            </b-form-group>
            <b-form-group
                :label="$t('achievement-type')" 
                label-for="type">
                <b-select 
                        id="type" 
                        v-model="achievement.trigger_type"
                        :options="achievementTriggers"
                        value-field="trigger_type"
                        text-field="trigger_type">
                    </b-select>
            </b-form-group>
            <b-form-group
                :label="$t('trigger-amount')" 
                label-for="amount">
                <b-form-input 
                    type="number" 
                    id="amount" 
                    name="amount" 
                    :placeholder="$t('amount')" 
                    v-model="achievement.trigger_amount" />
            </b-form-group>
            <b-form-group
                :label="$t('trigger-description')" 
                label-for="trigger-description">
                <p v-if="achievement.trigger_type" id="trigger-description">{{triggerDescription}}</p>
            </b-form-group>
            <b-button type="submit" block>{{ $t('create-new-achievement') }}</b-button>
            <b-button type="button" block @click="close">{{ $t('cancel') }}</b-button>
        </b-form>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import BaseFormError from '../BaseFormError.vue';
export default {
    components: {
        BaseFormError,
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
