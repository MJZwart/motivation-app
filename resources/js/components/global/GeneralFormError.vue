<template>
    <div>
        <div v-for="(error, index) in errors" :key="index" class="d-block invalid-feedback">
            {{ error[0] }}
        </div>
    </div>
</template>

<script setup>
import {errors} from '/js/services/errorService';
</script>