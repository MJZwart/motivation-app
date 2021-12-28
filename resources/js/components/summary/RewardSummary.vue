<template>
    <div>
        <div v-if="reward" class="summary-card">
            <span class="card-title">{{reward.name}}                 
                <b-icon-pencil-square v-if="userReward"
                                      @click="showEditReward()" />
            </span>
            <div class="bottom-border side-border card-content">
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
        </div>
        
        <!-- <b-modal id="edit-reward-name" hide-footer :title="$t('edit-reward-name')">
            <edit-reward-name :reward="rewardToEdit" @close="closeEditReward" />
        </b-modal> -->
    </div>
</template>


<script>
// import EditRewardName from '../modals/EditRewardName.vue';
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
        userReward: {
            type: Boolean,
            required: true,
        },
    },
    components: {
        // EditRewardName,
    },
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
        // showEditReward() {
        //     this.$store.dispatch('clearErrors');
        //     this.rewardToEdit = this.reward;
        //     this.$bvModal.show('edit-reward-name');
        // },
        // closeEditReward() {
        //     this.rewardToEdit = null;
        //     this.$bvModal.hide('edit-reward-name');
        // },
    },
}
</script>

<style lang="scss">
.progress{
    height:0.5rem;
}
.progress.level-bar{
    height:0.7rem;
    margin-bottom:3px;
}
</style>