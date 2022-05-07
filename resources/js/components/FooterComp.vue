<template>
    <div class="footer">
        <p class="silent">{{ $t('footer-text') }}</p>
        <div class="footer-text">
            <router-link class="silent mb-1" to="/faq">{{ $t('faq') }}</router-link>
            <a class="silent mb-1" href="https://github.com/MJZwart/motivation-app#roadmap" target="_blank">
                {{ $t('roadmap') }}
            </a>
            <router-link class="silent mb-1" to="/bugreport">{{ $t('report-bug') }}</router-link>
            <router-link class="silent mb-1" to="/feedback">{{ $t('feedback') }}</router-link>
        </div>
    </div>
</template>