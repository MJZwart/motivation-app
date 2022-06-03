<template>
    <div class="d-flex">
        <div class="w-50-flex center">
            <h2>{{ $t('page-not-found') }}</h2>
            <p>{{ $t('if-mistake-submit-report')}} <router-link to="/bugreport">{{ $t('bug-report-lc') }}</router-link>.</p>
            <p>{{ $t('go-back') }} <router-link to="/">{{ $t('home-lc') }}</router-link>.</p>
        </div>
    </div>
</template>

<style lang="scss" scoped>
.center {
    text-align: center;
    h2 {
        font-weight: 300;
        font-size: 3.5rem;
    }
}
</style>