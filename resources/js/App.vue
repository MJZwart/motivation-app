<template>
    <div>
        <navigation />
        <div class="content">
            <router-view />
        </div>
        <footer-comp />
    </div>
</template>

<script>
import Navigation from './components/Navigation.vue';
import FooterComp from './components/FooterComp.vue';

export default {
    components: {Navigation, FooterComp},
    
};
</script>