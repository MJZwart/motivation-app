<template>
    <div>
        <form @submit.prevent="submitAchievement">
            <div class="form-group">
                <label for="name">{{$t('achievement-name')}}</label>
                <input 
                    id="name" 
                    v-model="achievement.name"
                    type="text" 
                    name="name" 
                    :placeholder="$t('name')"  />
                <base-form-error name="name" /> 
            </div>
            <div class="form-group">
                <label for="description">{{$t('achievement-description')}}</label>
                <input 
                    id="description" 
                    v-model="achievement.description"
                    type="text" 
                    name="description" 
                    :placeholder="$t('description')"  />
                <base-form-error name="description" /> 
            </div>
            <div class="form-group">
                <label for="type">{{$t('trigger-type')}}</label>
                <select 
                    id="type" 
                    v-model="achievement.trigger_type"
                    value-field="trigger_type"
                    text-field="trigger_type">
                    <option v-for="(option, index) in achievementTriggers" :key="index" :value="option.value">
                        {{option.text}}
                    </option>
                </select>
                <base-form-error name="trigger_type" /> 
            </div>
            <div class="form-group">
                <label for="trigger_amount">{{$t('trigger-amount')}}</label>
                <input 
                    id="trigger_amount" 
                    v-model="achievement.trigger_amount"
                    type="number" 
                    name="trigger_amount" 
                    :placeholder="$t('amount')"  />
                <base-form-error name="trigger_amount" /> 
            </div>
            <div class="form-group">
                <label for="trigger-description">{{$t('trigger-description')}}</label>
                <p v-if="achievement.trigger_type" id="trigger-description">{{triggerDescription}}</p>
            </div>
            <b-button type="submit" block>{{ $t('create-new-achievement') }}</b-button>
            <b-button type="button" block @click="close">{{ $t('cancel') }}</b-button>
        </form>
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
            /** @type {import('resources/types/achievement').Achievement} */
            achievement: {
                trigger_amount: 0,
            },
        }
    },
    methods: {
        submitAchievement() {
            this.achievement.trigger_amount = parseInt(this.achievement.trigger_amount);
            this.$store.dispatch('achievement/newAchievement', this.achievement).then(() => {
                this.close();
            });
        },
        close() {
            this.achievement = {trigger_amount: 0},
            this.$emit('close');
        },
    },
    computed: {
        ...mapGetters({
            achievementTriggers: 'achievement/getAchievementTriggers',
        }),
        /** Parses the achievement description from the type (eg Made {0} friends) and the amount */
        triggerDescription() {
            const plural = this.achievement.trigger_amount > 1 ? 's' : '';
            let desc = this.achievementTriggers.find(item => item.trigger_type === this.achievement.trigger_type);
            desc = desc.trigger_description.replace('%d', this.achievement.trigger_amount);
            return desc.replace('%s', plural);
        },
    },
}
</script>
