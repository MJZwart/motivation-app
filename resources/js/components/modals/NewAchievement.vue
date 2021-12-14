<template>
    <div>
        <b-form @submit.prevent="submitAchievement">
            <b-form-group
                :label="$t('achievement-name')" 
                label-for="name">
                <b-form-input 
                    id="name" 
                    v-model="achievement.name"
                    type="text" 
                    name="name" 
                    :placeholder="$t('name')"  />
                <base-form-error name="name" /> 
            </b-form-group>
            <b-form-group
                :label="$t('achievement-description')" 
                label-for="description">
                <b-form-input 
                    id="description" 
                    v-model="achievement.description"
                    type="text" 
                    name="description" 
                    :placeholder="$t('description')"  />
                <base-form-error name="description" /> 
            </b-form-group>
            <b-form-group
                :label="$t('trigger-type')" 
                label-for="type">
                <b-select 
                    id="type" 
                    v-model="achievement.trigger_type"
                    :options="achievementTriggers"
                    value-field="trigger_type"
                    text-field="trigger_type" />
                <base-form-error name="trigger_type" /> 
            </b-form-group>
            <b-form-group
                :label="$t('trigger-amount')" 
                label-for="trigger_amount">
                <b-form-input 
                    id="trigger_amount" 
                    v-model="achievement.trigger_amount"
                    type="number" 
                    name="trigger_amount" 
                    :placeholder="$t('amount')"  />
                <base-form-error name="trigger_amount" /> 
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
            /** @type {import('resources/types/achievement').Achievement} */
            achievement: {
                trigger_amount: 0,
            },
        }
    },
    methods: {
        submitAchievement() {
            this.$store.dispatch('admin/newAchievement', this.achievement).then(() => {
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
