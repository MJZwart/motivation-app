

<template>
    <div class="w-50-flex center">
        <h2>F.A.Q.</h2>
        <div>
            <div v-for="(question, index) in questions" :key="index">
                <div class="question-block">
                    <b>{{question.question}}</b>
                    <p>{{question.answer}}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {computed} from 'vue';
import {enQuestions} from '../../lang/components/faq/en';
import {nlQuestions} from '../../lang/components/faq/nl';
import {currentLang} from '/js/services/languageService';

const languages = {
    'en': enQuestions,
    'nl': nlQuestions,
};

const questions = computed(() => {
    return languages[currentLang.value];
})
</script>

<style>
.question-block{
    margin-bottom:1rem;
}
</style>