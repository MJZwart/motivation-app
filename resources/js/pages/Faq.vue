<template>
    <div>
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

<script>
export default {
    data() {
        return {
            questions: [
                {
                    question: 'Hiya', 
                    answer: 'Hi',
                },
                {
                    question: 'Are there any ads?', 
                    answer: 'No, I hate ads.',
                },
                {
                    question: 'Will there be ads in the future?', 
                    answer: 'No, I hate ads. With a passion.',
                },
                {
                    question: 'How will you keep this site running?', 
                    answer: '¯\\_(ツ)_/¯',
                },
                {
                    question: 'Can I help out?', 
                    answer: 'Of course!',
                },
                {
                    question: '', 
                    answer: '',
                },
            ],
        }
    },
}
</script>

<style>
.question-block{
    margin-bottom:1rem;
}
</style>