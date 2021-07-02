<template>
    <div>
        <div class="character">
            <span class="frame-title">{{character.name}}</span>
            <div class="side-border bottom-border grid-2 small-text">
                <p>Level: {{character.level}}</p>
                <p>Experience: {{character.experience}}</p>
                <div class="left">
                    <p>Strength: {{character.strength}}</p>
                    <p>Endurance: {{character.endurance}}</p>
                    <p>Agility: {{character.agility}}</p>
                </div>
                
                <div class="right">
                    <p>Intelligence: {{character.intelligence}}</p>
                    <p>Charisma: {{character.charisma}}</p>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        character: Object
    },
    data() {
        return {
            
        }
    },
    
}
</script>



<style>
.grid-2{
    display:grid;
}
.grid-2 .left{
    grid-column-start: 1;
}
.grid-2 .right{
    grid-column-start: 2;
}
.small-text{
    font-size:12px;
}
</style>