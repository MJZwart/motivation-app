<template>
    <div v-if="achievements">
        <span class="card-title">{{ $t('achievements') }}</span>
        <div class="side-border bottom-border">
            <ul class="summary-list">
                <li v-for="(achievement, index) in achievements" :key="index">
                    <span>{{achievement.name}}</span>
                    <p class="silent">
                        {{achievementSummary}}
                    </p>
                </li>
            </ul>
        </div>
    </div>
</template>


<script>
import {t} from 'vue-i18n';
export default {
    props: {
        achievements: {
            type: Array,
            required: true,
        },
    },
    computed: {
        achievementSummary() {
            return this.achievement.description+' '+this.achievement.trigger_description+' '
            +t('earned-on')+': '+this.achievement.pivot.earned;
        },
    },
}
</script>