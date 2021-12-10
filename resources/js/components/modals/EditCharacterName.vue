<template>
    <div v-if="character">
        <b-form @submit.prevent="updateCharacter">
            <b-form-group
                :label="$t('character-name')" 
                label-for="name">
                <b-form-input 
                    id="name" 
                    v-model="editedCharacter.name"
                    type="text" 
                    name="name" 
                    :placeholder="$t('name')"  />
                <base-form-error name="name" /> 
            </b-form-group>
            <b-button type="submit" block>{{ $t('update-character-name') }}</b-button>
            <b-button type="button" block @click="close">{{ $t('cancel') }}</b-button>
            <base-form-error name="error" /> 
        </b-form>
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
    mounted() {
        this.character ? this.editedCharacter = this.character : this.editedCharacter = {};
    },
    methods: {
        updateCharacter() {
            var self = this;
            this.$store.dispatch('character/updateCharacter', this.editedCharacter).then(function() {
                self.close();
            });
        },
        close() {
            this.editedCharacter = {},
            this.$emit('close');
        },
    },
}
</script>
