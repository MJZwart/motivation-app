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


<script setup>
import Summary from '/js/components/global/Summary.vue';
import {useI18n} from 'vue-i18n'
const {t} = useI18n() // use as global scope
defineProps({
    achievements: {
        type: Array,
        required: false,
    },
});
/**
 * @param {import('resources/types/achievement').Achievement} achievement
 * @return {string}
 */
function achievementSummary(achievement) {
    return achievement.description+' '+achievement.trigger_description+' '
    + t('earned-on')+': '+achievement.pivot.earned;
}
</script>