<template>
    <ContentBlock title="achievements" :tutorial="tutorial">
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
    </ContentBlock>
</template>


<script setup lang="ts">
import {parseDateTime} from '/js/services/dateService';
import {useI18n} from 'vue-i18n';
import {PropType} from 'vue';
import {parseAchievementTriggerDesc} from '/js/services/achievementService';
import type {Achievement} from 'resources/types/achievement';

const {t} = useI18n();

defineProps({
    achievements: {
        type: Array as PropType<Array<Achievement>>,
        required: false,
    },
    tutorial: {
        type: Boolean,
        required: false,
        default: true,
    },
});

function achievementSummary(achievement: Achievement): string {
    const date = parseDateTime(achievement.earned, true);
    const description = achievement.description ? achievement.description+' ' : '';
    return description + parseAchievementTriggerDesc(achievement) + ' '
    + t('earned-on')+': '+ date;
}
</script>