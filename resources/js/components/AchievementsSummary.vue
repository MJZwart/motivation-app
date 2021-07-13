<template>
    <div>
        <span class="frame-title">Achievements</span>
        <div class="side-border bottom-border">
            <ul class="friends-list">
                <li v-for="(value, index) in achievements" :key="index">
                    {{value.name}}
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