<template>
    <div class="footer">
        <hr />
        <div class="row">
            <div class="col-8" />
            <div class="col">
                <p class="silent">{{ $t('footer-text') }}</p>
                <div class="flex-col">
                    <router-link class="silent mb-1" to="/faq">{{ $t('faq') }}</router-link>
                    <a class="silent mb-1" href="https://github.com/MJZwart/motivation-app#roadmap" target="_blank">
                        {{ $t('roadmap') }}
                    </a>
                    <router-link class="silent mb-1" to="/feedback">{{ $t('feedback') }}</router-link>
                </div>
            </div>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.flex-col{
    flex-direction: column;
    display: flex;
}
</style>