<template>
    <h2>Achievements</h2>
</template>


<script>
export default {
    mounted() {
        this.$store.dispatch('admin/checkAdmin');
    },
    
}
</script>


<style>

</style>