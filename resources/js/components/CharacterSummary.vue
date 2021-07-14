<template>
    <div>
        <div class="character" v-if="character">
            <span class="frame-title">{{character.name}}                 
                <b-icon-pencil-square 
                    class="icon-small flex-end"
                    @click="showEditCharacter()"></b-icon-pencil-square>
            </span>
            <div class="side-border bottom-border grid-2 small-text">
                <p>Level: {{character.level}}</p>
                <p>Experience: {{character.experience}}
                <b-progress :value="character.experience" :max="experienceToLevel(character.level)"></b-progress>
                </p>
                <div class="left">
                    <p>Strength: {{character.strength}}
                        <b-progress :value="character.strength_exp" :max="experienceToLevel(character.strength)"></b-progress>
                    </p>
                    <p>Endurance: {{character.endurance}}
                        <b-progress :value="character.endurance_exp" :max="experienceToLevel(character.endurance)"></b-progress>
                    </p>
                    <p>Agility: {{character.agility}}
                        <b-progress :value="character.agility_exp" :max="experienceToLevel(character.agility)"></b-progress>
                    </p>
                </div>
                
                <div class="right">
                    <p>Intelligence: {{character.intelligence}}
                        <b-progress :value="character.intelligence_exp" :max="experienceToLevel(character.intelligence)"></b-progress>
                    </p>
                    <p>Charisma: {{character.charisma}}
                        <b-progress :value="character.charisma_exp" :max="experienceToLevel(character.agility)"></b-progress>
                    </p>
                </div>
            </div>
        </div>
        <edit-character-name v-if="isEditCharacterVisible" :character="characterToEdit"></edit-character-name>
    </div>
</template>


<script>
import {mapGetters} from 'vuex';
import EditCharacterName from './EditCharacterName.vue';
export default {
    components: {
        EditCharacterName,
    },
    data() {
        return {
            isEditCharacterVisible: false,
            characterToEdit: null,
        }
    },
    mounted(){
        this.$store.dispatch('character/getCharacter');
    },
    methods: {
        experienceToLevel(level){
            const index = this.character.experienceTable.findIndex(item => item.level == level);
            if(index >= 0){
                return this.character.experienceTable[index].experience_points;
            }
        },
        showEditCharacter() {
            this.$store.dispatch('clearInformationBlock');
            this.characterToEdit = this.character;
            this.isEditCharacterVisible = true;
        },
        closeEditCharacter(){
            this.characterToEdit = null;
            this.isEditCharacterVisible = false;
        },
    },
    computed: {
        ...mapGetters({
            character: 'character/getCharacter',
        }),
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
.progress-bar{
    height: 4px;
    background-color: teal;
}
.progress{
    background-color: #e9ecef;
    border-radius:2px;
    width:90%;
}
</style>