<template>
    <div>
        <navigation></navigation>
        <router-view></router-view>
        <footer-comp></footer-comp>
    </div>
</template>
<script>
import Navigation from './components/Navigation.vue';
import FooterComp from './components/FooterComp.vue';

export default {
    components: { Navigation, FooterComp },
    
};
</script>
