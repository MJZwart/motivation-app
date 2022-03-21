<template>
    <div v-if="reward">
        <!-- This is only here to make it easier to do user story #275 -->
        <b-modal id="edit-reward-name" hide-footer :title="$t('edit-reward-name')">
            <edit-reward-object-name :rewardObj="rewardToEdit" :type="rewardType" @close="closeEditReward" />
        </b-modal>
    </div>
</template>


<script>
import EditRewardObjectName from '../modals/EditRewardObjectName.vue';
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
    components: {
        EditRewardObjectName,
    },
    data() {
        return {
            /** @type {import('resources/types/reward').Reward} */
            rewardToEdit: null,
        }
    },
    methods: {
        showEditReward() {
            this.$store.dispatch('clearErrors');
            this.rewardToEdit = this.reward;
            this.$bvModal.show('edit-reward-name');
        },
        closeEditReward() {
            this.rewardToEdit = null;
            this.$bvModal.hide('edit-reward-name');
        },
    },
}
</script>