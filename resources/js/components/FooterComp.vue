<template>
    <div class="footer">
        <hr />
        <div class="row">
            <div class="col-8" />
            <div class="col">
                <p class="silent">{{ $t('footer-text') }}</p>
                <b-navbar-nav class="ml-auto">
                    <b-nav-item class="silent" to="/faq">{{ $t('faq') }}</b-nav-item>
                    <b-nav-item class="silent" href="https://github.com/MJZwart/motivation-app#roadmap" target="_blank">
                        {{ $t('roadmap') }}
                    </b-nav-item>
                    <b-nav-item class="silent" to="/feedback">{{ $t('feedback') }}</b-nav-item>
                </b-navbar-nav>
            </div>
        </div>
    </div>
</template>
