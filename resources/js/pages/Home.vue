<template>
    <div>
        <h1>{{ $t('home-welcome-to', [$t('app-name')]) }}</h1>
        <p>{{ $t('home-introduction') }}</p>
        <h3>{{ $t('home-abilities') }}</h3>
        <ul>
            <li>{{ $t('home-abilities-1') }}</li>
            <li>{{ $t('home-abilities-2') }}</li>
            <li>{{ $t('home-abilities-3') }}</li>
        </ul>
        <h3>{{ $t('home-and-more') }}</h3>
        <p>{{ $t('home-2') }}</p>

        <b-button block to="/login">Login now</b-button>
        <b-button block to="/register">Create a new account</b-button>
    </div>
</template>
