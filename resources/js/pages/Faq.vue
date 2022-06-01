

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
/* eslint-disable max-len */
// eslint-disable-next-line max-lines-per-function
const questions = [
    // {
    //     question: 'So what is this site?', 
    //     answer: 'Hi',
    // },
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
        answer: 'Of course! We\'re open to any and all feedback and tips on how we can improve. Think new features, tips on design, quality of life improvement, accessibility improvements etc.. Please share ideas you have through our Feedback form or in the #suggestions channel in our Discord. We could also use some help regarding translations, so if you\'re proficient in English and another language you wish to translate into, please contact us through our Discord.',
    },
    {
        question: 'I found a bug, what do I do?', 
        answer: 'You can use our bug report page to report on any and all bugs you find. If you encounter a big problem you need help with, you can also contact us on Discord. When filling in a bug report, please provide us with as much detail as possible to help us fix the problem quicker. We need to be able to reproduce the bug in order to fix it, so we might ask some more questions before fixing it.',
    },
    {
        question: 'Can I hunt for bugs and exploits?', 
        answer: 'Or course! This project is open-source, so the source code is up on Github for all to see. Obviously we make sure not to put any passwords or exploitable data online, so if you find anything that shouldn\'t be there, please let us know ASAP. If you manage to break into the site or otherwise break the site, please let us know how you did it and we\'ll do our best to fix it as quickly as we can. Obviously don\'t go trolling on us and try to bring the site down altogether.',
    },
    {
        question: 'Is this site still being worked on?', 
        answer: 'Absolutely, we have lots of plans and ideas to work out. Keep in mind this is a hobby-project, we can only work on it in our spare time, so some features might take a while before showing up on the site.',
    },
    {
        question: 'It\'s not very pretty.', 
        answer: 'Okay, I\'m not very good at designing, give me a break. If you have any ideas on how this site can improve, you\'re very welcome to contact us on our Discord with suggestions.',
    },
    {
        question: 'Where can I find this Discord of yours?', 
        answer: 'The link to our public Discord will always be at the bottom of the page in the footer.',
    },
    {
        question: '', 
        answer: '',
    },
    {
        question: 'What do I do if I have any more questions?', 
        answer: 'You can either contact us through Discord or use our Feedback page, which you can find in the footer at the bottom of the page.',
    },
    {
        question: '', 
        answer: '',
    },
];
</script>

<style>
.question-block{
    margin-bottom:1rem;
}
</style>