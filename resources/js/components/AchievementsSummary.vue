<template>
    <div>
        <span class="frame-title">Achievements</span>
        <div class="side-border bottom-border">
            <ul class="summary-list">
                <li v-for="(value, index) in achievements" :key="index">
                    <span>{{value.name}}</span>
                    <p class="silent">{{value.description}} Earned on: {{value.pivot.earned}}</p>
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


<style>

</style>