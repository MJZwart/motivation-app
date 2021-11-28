<template>
    <div v-if="character">
        <form @submit.prevent="updateCharacter">
            <b-form-group
                label="Character name" 
                label-for="name">
                <b-form-input 
                    type="text" 
                    id="name" 
                    name="name" 
                    placeholder="Name" 
                    v-model="editedCharacter.name" />
                <base-form-error name="name" /> 
            </b-form-group>
            <b-button type="submit" block>Update character name</b-button>
            <b-button type="button" block @click="close">{{ $t('cancel') }}</b-button>
            <base-form-error name="error" /> 
        </form>
    </div>
</template>


<script>
import BaseFormError from '../BaseFormError.vue';
export default {
    components: {BaseFormError},
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
