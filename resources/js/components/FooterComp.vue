<template>
    <div id="footer">
        <div class="footer-text-left">
            <p class="silent">{{ $t('footer-text', [version]) }}</p>
            <p class="silent">{{ $t('footer-disclaimer') }}</p>
            <a class="silent mb-1" :href="$t('discord-link')" target="_blank">
                {{ $t('discord-link') }}
            </a>
            <ChangeLanguage class="small" />
        </div>
        <div class="footer-text">
            <router-link class="silent mb-1" to="/faq">{{ $t('faq') }}</router-link>
            <a class="silent mb-1" href="https://github.com/MJZwart/motivation-app#roadmap" target="_blank">
                {{ $t('roadmap') }}
            </a>
            <router-link class="silent mb-1" to="/bugreport">{{ $t('report-bug') }}</router-link>
            <router-link class="silent mb-1" to="/feedback">{{ $t('feedback') }}</router-link>
            <router-link class="silent mb-1" to="/privacy">{{ $t('privacy-policy') }}</router-link>
            <router-link class="silent mb-1" to="/tos">{{ $t('tos') }}</router-link>
        </div>
    </div>
</template>

<script setup lang="ts">
import {version} from '../../../package.json';
import ChangeLanguage from '/js/components/global/ChangeLanguage.vue';
</script>

<style lang="scss" scoped>
@import '../../assets/scss/variables.scss';
#footer {
    background-color: $background-darker;
    margin-top: auto;
    width: 100%;
    height: fit-content;
    display: flex;
    a{
        width: fit-content;
    }
    .footer-text{
        margin-top: auto;
        margin-bottom: auto;
        margin-right: 2rem;
        position: relative;
        display: flex;
        flex-direction: column;
    }
    .footer-text-left {
        position: relative;
        margin: auto 1rem auto 1rem;
        min-width: 6rem;
        p{
            margin: 0.5rem auto;
        }
    }
}
</style>