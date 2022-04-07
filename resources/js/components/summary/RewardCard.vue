<template>
    <div v-if="reward">
        <Summary :title='reward.name'>
            <div class="compact">
                <p>{{ $t('level') }}: {{reward.level}}</p>
                <p>{{ $t('experience') }}: {{reward.experience}}
                    <ProgressBar class="level-bar" :value="reward.experience" :max="experienceToLevel(reward.level)" />
                </p>
                <p>{{ rewardType == 'VILLAGE' ? $t('economy') : $t('strength') }}: {{reward.a}}
                    <ProgressBar :value="reward.a_exp" :max="experienceToLevel(reward.a)" />
                </p>
                <p>{{ rewardType == 'VILLAGE' ? $t('labour') : $t('endurance') }}: {{reward.b}}
                    <ProgressBar :value="reward.b_exp" :max="experienceToLevel(reward.b)" />
                </p>
                <p>{{ rewardType == 'VILLAGE' ? $t('craft') : $t('agility') }}: {{reward.c}}
                    <ProgressBar :value="reward.c_exp" :max="experienceToLevel(reward.c)" />
                </p>
                <p>{{ rewardType == 'VILLAGE' ? $t('art') : $t('intelligence') }}: {{reward.d}}
                    <ProgressBar :value="reward.d_exp" :max="experienceToLevel(reward.d)" />
                </p>
                <p>{{ rewardType == 'VILLAGE' ? $t('community') : $t('charisma') }}: {{reward.e}}
                    <ProgressBar :value="reward.e_exp" :max="experienceToLevel(reward.e)" />
                </p>
            </div>
        </Summary>
    </div>
</template>


<script>
import ProgressBar from '../bootstrap/ProgressBar.vue';
import Summary from './Summary.vue';
export default {
    props: {
        reward: {
            /** @type {import('resources/types/reward').Reward} */
            type: Object,
            required: true,
        },
        rewardType: {
            type: String,
            required: true,
        },
    },
    components: {Summary, ProgressBar},
    data() {
        return {
            /** @type {import('resources/types/reward').Reward} */
            rewardToEdit: null,
        }
    },
    methods: {
        /**
         * Calculates the experience needed to level up, in order to display the bar correctly
         * There must be a better way than this
         * @param {Number} level
         */
        experienceToLevel(level) {
            const index = this.reward.exp_to_level.findIndex(item => item.level == level);
            if (index >= 0) {
                return this.reward.exp_to_level[index].experience_points;
            }
        },
    },
}
</script>

<style lang="scss">
.level-bar{
    height:0.7rem !important;
    margin-bottom:3px !important;
}
.compact > p {
    margin-bottom: 0.5rem;
}
</style>