<template>
    <div v-if="parsedDiagnostics" class="diagnostics">
        <p>{{parsedDiagnostics.description}}</p>
        <p><b>{{$t('height-width')}}: </b>{{parsedDiagnostics.windowHeight}}px -{{parsedDiagnostics.windowWidth}}px</p>
    </div>
</template>

<script setup lang="ts">
import {computed} from 'vue';
import {parseDiagnostics} from '/js/services/platformService';
const props = defineProps({
    diagnostics: {
        type: String,
        required: false,
    },
});
const parsedDiagnostics = computed(() => parseDiagnostics(props.diagnostics));
</script>

<style lang="scss" scoped>
.diagnostics p{
    margin: 0;
}
</style>