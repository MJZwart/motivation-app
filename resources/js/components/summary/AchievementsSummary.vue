<template>
    <div v-if="achievements">
        <span class="card-title">{{ $t('achievements') }}</span>
        <div class="side-border bottom-border">
            <ul class="summary-list">
                <li v-for="(achievement, index) in achievements" :key="index">
                    <span>{{achievement.name}}</span>
                    <p class="silent">{{achievement.description}} {{achievement.trigger_description}} {{ $t('earned-on') }}: {{achievement.pivot.earned}}</p>
                </li>
            </ul>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        achievements: Array,
    },
}
</script>