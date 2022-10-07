<template>
    <div class="language-options"> 
        <div v-for="lang in langs" :key="lang.key" class="language-option" :class="{'active': currentLang === lang.key}">
            <span 
                :class="['clickable', 'fi', lang.flag]" 
                @click="changeLang(lang.key)" 
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import {currentLang, changeLang, langs} from '/js/services/languageService';
</script>

<style scoped lang="scss">
@import '../../../assets/scss/variables';
.language-options {
    display: flex;
    padding-bottom: 1rem;
    .active {
        background-color: white;
        padding: 0 0.5rem 0 0.5rem;
        box-shadow: $basic-shadow;
        border-radius: 1rem;
        margin: 0 !important;
    }
    .language-option {
        margin: 0 0.5rem 0 0.5rem;
    }
}
.language-options.large .fi {
    padding: 2rem;
}
.language-options.small {
    .fi {
        padding: 0.2rem;
    }
    .active {
        border-radius: 0.5rem;
    }
}
</style>