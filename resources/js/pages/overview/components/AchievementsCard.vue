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


<script setup lang="ts">
import Summary from '/js/components/global/Summary.vue';
import {parseDateTime} from '/js/services/dateService';
import {useI18n} from 'vue-i18n'
import {Achievement} from 'resources/types/achievement';
import {PropType} from 'vue';
const {t} = useI18n() // use as global scope
defineProps({
    achievements: {
        type: Array as PropType<Array<Achievement>>,
        required: false,
    },
});

function achievementSummary(achievement: Achievement): string {
    const date = parseDateTime(achievement.earned, true);
    const description = achievement.description ? achievement.description+' ' : '';
    return description + achievement.trigger_description + ' '
    + t('earned-on')+': '+ date;
}
</script>