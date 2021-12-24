<template>
    <div>
        <span class="card-title">{{ $t('achievements') }}</span>
        <div class="side-border bottom-border">
            <div v-if="achievements">
                <ul class="summary-list">
                    <li v-for="(achievement, index) in achievements" :key="index">
                        <span>{{achievement.name}}</span>
                        <p class="silent">
                            {{achievementSummary(achievement)}}
                        </p>
                    </li>
                </ul>
            </div>
            <div v-else>
                No achievements.
            </div>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        achievements: {
            type: Array,
            required: false,
        },
    },
    methods: {
        /**
         * @param {import('resources/types/achievement').Achievement} achievement
         * @return {string}
         */
        achievementSummary(achievement) {
            return achievement.description+' '+achievement.trigger_description+' '
            +this.$t('earned-on')+': '+achievement.pivot.earned;
        },
    },
}
</script>