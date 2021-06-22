<template>
    <div>
        <navigation></navigation>
        <div class="content">
            <information-block></information-block>
            <router-view></router-view>
        </div>
        <footer-comp></footer-comp>
    </div>
</template>

<script>
import Navigation from './components/Navigation.vue';
import FooterComp from './components/FooterComp.vue';
import InformationBlock from './components/InformationBlock.vue';

export default {
    components: { Navigation, FooterComp, InformationBlock },
    
};
</script>