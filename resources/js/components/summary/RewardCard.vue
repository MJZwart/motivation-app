<template>
    <div v-if="reward">
        <Summary :title='reward.name'>
            <div class="compact">
                <p>{{ $t('level') }}: {{reward.level}}</p>
                <p>{{ $t('experience') }}: {{reward.experience}}
                    <b-progress class="level-bar" :value="reward.experience" :max="experienceToLevel(reward.level)" />
                </p>
                <p>{{ rewardType == 'VILLAGE' ? $t('economy') : $t('strength') }}: {{reward.a}}
                    <b-progress :value="reward.a_exp" :max="experienceToLevel(reward.a)" />
                </p>
                <p>{{ rewardType == 'VILLAGE' ? $t('labour') : $t('endurance') }}: {{reward.b}}
                    <b-progress :value="reward.b_exp" :max="experienceToLevel(reward.b)" />
                </p>
                <p>{{ rewardType == 'VILLAGE' ? $t('craft') : $t('agility') }}: {{reward.c}}
                    <b-progress :value="reward.c_exp" :max="experienceToLevel(reward.c)" />
                </p>
                <p>{{ rewardType == 'VILLAGE' ? $t('art') : $t('intelligence') }}: {{reward.d}}
                    <b-progress :value="reward.d_exp" :max="experienceToLevel(reward.d)" />
                </p>
                <p>{{ rewardType == 'VILLAGE' ? $t('community') : $t('charisma') }}: {{reward.e}}
                    <b-progress :value="reward.e_exp" :max="experienceToLevel(reward.e)" />
                </p>
            </div>
        </Summary>
    </div>
</template>


<script>
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
    components: {Summary},
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
            const index = this.reward.experienceTable.findIndex(item => item.level == level);
            if (index >= 0) {
                return this.reward.experienceTable[index].experience_points;
            }
        },
    },
}
</script>

<style lang="scss">
.progress{
    height:0.5rem !important;
}
.progress.level-bar{
    height:0.7rem !important;
    margin-bottom:3px !important;
}
.compact > p {
    margin-bottom: 0.5rem;
}
</style>