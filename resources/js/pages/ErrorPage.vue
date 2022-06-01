<template>
    <div class="d-flex">
        <div class="w-50-flex center">
            <h2>Page not found</h2>
            <p>If you believe this is an error, please submit a <router-link to="/bugreport">bug report</router-link>.</p>
            <p>Go back <router-link to="/">home</router-link>.</p>
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