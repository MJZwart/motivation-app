<template>
    <div class="footer">
        <hr />
        <b-row>
            <b-col cols="8" />
            <b-col>
                <p class="silent">{{ $t('footer-text') }}</p>
                <b-navbar-nav class="ml-auto">
                    <b-nav-item class="silent" to="/faq">{{ $t('faq') }}</b-nav-item>
                    <b-nav-item class="silent" href="https://github.com/MJZwart/motivation-app#roadmap" target="_blank">
                        {{ $t('roadmap') }}
                    </b-nav-item>
                </b-navbar-nav>
            </b-col>
        </b-row>
    </div>
</template>
