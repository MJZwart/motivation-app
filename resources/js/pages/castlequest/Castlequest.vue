<template>
    <div class="castlequest-app">
        <!-- Help, I can see a footer and a nav bar in the source code, what is this?
        Well, this is an extremely janky solution that I cooked up 10 minutes before I get off from work, that's what.
        Don't worry about it. -->
        <div class="content">
            <header>
                <div class="header">
                    <h1 class="gradient">Castlequest</h1>
                </div>
            </header>
            <main>
                <div class="active-quest">{{ activeQuest }}</div>
                <button class="random-quest-btn btn" @click="getRandomQuest">Generate new quest</button>
            </main>
            <footer>
                <div class="footer">
                    Took a picture of your quest? Share them with the hashtag
                    <span class="gradient">#castlequest</span> on social media!
                </div>
            </footer>
        </div>
    </div>
</template>
  
<script setup lang="ts">
import {ref, onMounted} from 'vue'
import {quests} from './quests'

const activeQuest = ref<string | null>(null)

const getRandomQuest = () => {
    const randomNumber = Math.floor(Math.random() * quests.length)
    activeQuest.value = quests[randomNumber]
}

onMounted(() => {
    getRandomQuest()
})
</script>
  
<style scoped lang="scss">
.castlequest-app {
    position: absolute;
    background-color: #181818;
    min-height: 100vh;
    z-index: 9999999;
    top: 0;
    bottom: 0;
    left: 0;
    right: 0;
    color: rgba(235, 235, 235, 0.64) !important;
    line-height: 1.6;
    font-size: 15px;
    text-rendering: optimizeLegibility;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
    display: flex;
}

.gradient {
    background-image: linear-gradient(90deg, #55cdfc, #f7a8b8);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
}

main {
    margin-bottom: 4rem;
    align-items: center;
    display: flex;
    flex-direction: column;
}

.footer {
    font-size: 1.3rem;
    font-weight: 300;
}

.content {
    margin: auto;
    padding: 2rem;
    font-weight: normal;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.active-quest {
    margin: 2rem 0;
    font-size: 2rem;
    max-width: 650px;
    text-align: center;
}

.header h1 {
    font-size: 60px;
    letter-spacing: -4.5px;
    font-weight: 700;
}

h1 {
    font-weight: 500;
    font-size: 2.6rem;
    position: relative;
    top: -10px;
}

h3 {
    font-size: 1.2rem;
}

.btn {
    background-image: linear-gradient(90deg, #55cdfc, #f7a8b8);
    border-radius: 8px;
    border-style: none;
    box-sizing: border-box;
    color: #000;
    cursor: pointer;
    font-size: 20px;
    font-weight: 700;
    height: 4rem;
    padding: 0 4.5rem;
    text-align: center;
    text-shadow: rgba(0, 0, 0, 0.25) 0 3px 8px;
    transition: all .5s;
}

.btn:hover {
    box-shadow: #f7a8b8 0 1px 15px;
    transition-duration: .1s;
}

@media (min-width: 768px) {
    .btn-36 {
        padding: 0 2.6rem;
    }
}
*,
*::before,
*::after {
    box-sizing: border-box;
    margin: 0;
    font-weight: normal;
}
</style>