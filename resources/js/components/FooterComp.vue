<template>
    <div id="footer">
        <div class="footer-text-left">
            <p class="silent">{{ $t('footer-text', [version]) }}</p>
            <p class="silent">{{ $t('footer-disclaimer') }}</p>
            <a class="silent mb-1" :href="$t('discord-link')" target="_blank">
                <DiscordLogo />
            </a>
            <ChangeLanguage class="small" />
        </div>
        <div class="footer-links-group">
            <div class="footer-links">
                <router-link class="silent mb-1" to="/faq">{{ $t('faq') }}</router-link>
                <router-link class="silent mb-1" to="/bugreport">{{ $t('report-bug') }}</router-link>
                <router-link class="silent mb-1" to="/feedback">{{ $t('feedback') }}</router-link>
            </div>
            <div class="footer-links">
                <router-link class="silent mb-1" to="/privacy">{{ $t('privacy-policy') }}</router-link>
                <router-link class="silent mb-1" to="/tos">{{ $t('tos') }}</router-link>
                <router-link class="silent mb-1" to="/changelog">{{ $t('changelog') }}</router-link>
                <a class="silent mb-1" href="https://github.com/MJZwart/motivation-app#roadmap" target="_blank">
                    {{ $t('roadmap') }}
                </a>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import {version} from '../../../package.json';
import ChangeLanguage from '/js/components/global/ChangeLanguage.vue';
import DiscordLogo from '/js/components/global/small/DiscordLogo.vue';
</script>

<style lang="scss" scoped>
#footer {
    background-color: var(--background-darker);
    margin-top: auto;
    width: 100%;
    height: fit-content;
    display: flex;
    a{
        width: fit-content;
    }
    .footer-links-group{
        margin-top: 1rem;
        margin-bottom: auto;
        position: relative;
        display: flex;
        flex-direction: row;
        min-width: 8rem;
        .footer-links{
            display: flex;
            flex-direction: column;
            margin-right: 2rem;
            width: fit-content;
        }
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
@media(max-width: 800px) {
    #footer {
        .footer-links-group{
        flex-direction: column;
    }
}
}
</style>