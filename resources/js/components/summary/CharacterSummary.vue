<template>
    <div>
        <div class="summary-card" v-if="character">
            <span class="card-title">{{character.name}}                 
                <b-icon-pencil-square v-if="userCharacter"
                    @click="showEditCharacter()"></b-icon-pencil-square>
            </span>
            <div class="bottom-border side-border card-content">
                <p>{{ $t('level') }}: {{character.level}}</p>
                <p>{{ $t('experience') }}: {{character.experience}}
                <b-progress class="level-bar" :value="character.experience" :max="experienceToLevel(character.level)"></b-progress>
                </p>
                <p>{{ $t('strength') }}: {{character.strength}}
                    <b-progress :value="character.strength_exp" :max="experienceToLevel(character.strength)"></b-progress>
                </p>
                <p>{{ $t('endurance') }}: {{character.endurance}}
                    <b-progress :value="character.endurance_exp" :max="experienceToLevel(character.endurance)"></b-progress>
                </p>
                <p>{{ $t('agility') }}: {{character.agility}}
                    <b-progress :value="character.agility_exp" :max="experienceToLevel(character.agility)"></b-progress>
                </p>
                <p>{{ $t('intelligence') }}: {{character.intelligence}}
                    <b-progress :value="character.intelligence_exp" :max="experienceToLevel(character.intelligence)"></b-progress>
                </p>
                <p>{{ $t('charisma') }}: {{character.charisma}}
                    <b-progress :value="character.charisma_exp" :max="experienceToLevel(character.agility)"></b-progress>
                </p>
            </div>
        </div>
        
        <b-modal id="edit-character-name" hide-footer :title="$t('edit-character-name')">
            <edit-character-name :character="characterToEdit" @close="closeEditCharacter"></edit-character-name>
        </b-modal>
    </div>
</template>


<script>
import EditCharacterName from '../modals/EditCharacterName.vue';
export default {
    props: {
        character: Object,
        userCharacter: Boolean,
    },
    components: {
        EditCharacterName,
    },
    data() {
        return {
            characterToEdit: null,
        }
    },
    methods: {
        experienceToLevel(level){
            const index = this.character.experienceTable.findIndex(item => item.level == level);
            if(index >= 0){
                return this.character.experienceTable[index].experience_points;
            }
        },
        showEditCharacter() {
            this.$store.dispatch('clearErrors');
            this.characterToEdit = this.character;
            this.$bvModal.show('edit-character-name');
        },
        closeEditCharacter(){
            this.characterToEdit = null;
            this.$bvModal.hide('edit-character-name');
        },
    },
}
</script>

<style lang="scss">
.progress{
    height:0.5rem;
}
.progress.level-bar{
    height:0.7rem;
    margin-bottom:3px;
}
</style>