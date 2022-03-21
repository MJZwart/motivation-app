<template>
    <Summary :title="$t('achievements')">
        <div v-if="achievements && achievements[0]">
            <ul class="no-list-style">
                <li v-for="(achievement, index) in achievements" :key="index">
                    <span>{{achievement.name}}</span>
                    <p class="silent">
                        {{achievementSummary(achievement)}}
                    </p>
                </li>
            </ul>
        </div>
        <div v-else>
            {{ $t('no-achievements') }}
        </div>  
    </Summary>
</template>


<script>
import Summary from './Summary.vue';
export default {
    components: {
        Summary,
    },
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