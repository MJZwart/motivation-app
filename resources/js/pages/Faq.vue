

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
import {questions} from '../../lang/components/faq/en';
</script>

<style>
.question-block{
    margin-bottom:1rem;
}
</style>