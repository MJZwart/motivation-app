<template>
    <div>
        <div v-if="character" class="summary-card">
            <span class="card-title">{{character.name}}                 
                <b-icon-pencil-square v-if="userCharacter"
                                      @click="showEditCharacter()" />
            </span>
            <div class="bottom-border side-border card-content">
                <p>{{ $t('level') }}: {{character.level}}</p>
                <p>{{ $t('experience') }}: {{character.experience}}
                    <b-progress class="level-bar" :value="character.experience" :max="experienceToLevel(character.level)" />
                </p>
                <p>{{ $t('strength') }}: {{character.strength}}
                    <b-progress :value="character.strength_exp" :max="experienceToLevel(character.strength)" />
                </p>
                <p>{{ $t('endurance') }}: {{character.endurance}}
                    <b-progress :value="character.endurance_exp" :max="experienceToLevel(character.endurance)" />
                </p>
                <p>{{ $t('agility') }}: {{character.agility}}
                    <b-progress :value="character.agility_exp" :max="experienceToLevel(character.agility)" />
                </p>
                <p>{{ $t('intelligence') }}: {{character.intelligence}}
                    <b-progress :value="character.intelligence_exp" :max="experienceToLevel(character.intelligence)" />
                </p>
                <p>{{ $t('charisma') }}: {{character.charisma}}
                    <b-progress :value="character.charisma_exp" :max="experienceToLevel(character.agility)" />
                </p>
            </div>
        </div>
        
        <b-modal id="edit-character-name" hide-footer :title="$t('edit-character-name')">
            <edit-character-name :character="characterToEdit" @close="closeEditCharacter" />
        </b-modal>
    </div>
</template>


<script>
import EditCharacterName from '../modals/EditCharacterName.vue';
export default {
    props: {
        character: {
            /** @type {import('resources/types/character').Character} */
            type: Object,
            required: true,
        },
        userCharacter: {
            type: Boolean,
            required: true,
        },
    },
    components: {
        EditCharacterName,
    },
    data() {
        return {
            /** @type {import('resources/types/character').Character} */
            characterToEdit: null,
        }
    },
    methods: {
        /**
         * Calculates the experience needed to level up, in order to display the bar correctly
         * There must be a better way than this
         * @param {Number} level
         */
        experienceToLevel(level) {
            const index = this.character.experienceTable.findIndex(item => item.level == level);
            if (index >= 0) {
                return this.character.experienceTable[index].experience_points;
            }
        },
        showEditCharacter() {
            this.$store.dispatch('clearErrors');
            this.characterToEdit = this.character;
            this.$bvModal.show('edit-character-name');
        },
        closeEditCharacter() {
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