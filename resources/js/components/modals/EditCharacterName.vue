<template>
    <div>
        <transition name="modal-fade">
            <div class="modal-backdrop">
                <div class="modal">
                    <information-block></information-block>

                    <div class="form-title">
                    <h3>Edit character</h3>
                    </div>
                    <form @submit.prevent="updateCharacter">
                        <div class="form-group">
                            <label for="name">Character name</label>
                            <input 
                                type="text" 
                                id="name" 
                                name="name" 
                                placeholder="Name" 
                                v-model="editedCharacter.name" />
                        </div>
                        <div class="form-group">
                            <b-button type="submit" block>Update character name</b-button>
                            <b-button type="button" block @click="close">{{ $t('cancel') }}</b-button>
                        </div>
                    </form>
                </div>
            </div>
            
        </transition>
    </div>
</template>


<script>
import InformationBlock from '../InformationBlock.vue';
export default {
    components: {InformationBlock},
    props: {
        character: Object,
    },
    data() {
        return {
            editedCharacter: {},
        }
    },
    mounted(){
        this.character ? this.editedCharacter = this.character : this.editedCharacter = {};
    },
    methods: {
        updateCharacter(){
            var self = this;
            this.$store.dispatch('character/updateCharacter', this.editedCharacter).then(function(){
                self.close();
            });

        },
        close(){
            this.editedCharacter = {},
            this.$emit('close');
        }
    },
}
</script>
