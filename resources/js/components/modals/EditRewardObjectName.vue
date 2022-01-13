<template>
    <div v-if="rewardObj">
        <b-form @submit.prevent="updateRewardObj">
            <b-form-group
                :label="parsedLabelName" 
                label-for="name">
                <b-form-input 
                    id="name" 
                    v-model="editedRewardObj.name"
                    type="text" 
                    name="name" 
                    :placeholder="$t('name')"  />
                <base-form-error name="name" /> 
            </b-form-group>
            <b-button type="submit" block>{{ $t('update-reward-name') }}</b-button>
            <b-button type="button" block @click="close">{{ $t('cancel') }}</b-button>
            <base-form-error name="error" /> 
        </b-form>
    </div>
</template>


<script>
import BaseFormError from '../BaseFormError.vue';
import Vue from 'vue';
export default {
    components: {BaseFormError},
    props: {
        rewardObj: {
            type: Object,
            required: true,
        },
        type: {
            type: String,
            required: true,
        },
    },
    data() {
        return {
            editedRewardObj: {},
        }
    },
    mounted() {
        this.rewardObj ? this.editedRewardObj = Vue.util.extend({}, this.rewardObj) : this.editedRewardObj = {};
    },
    methods: {
        updateRewardObj() {
            var self = this;
            this.editedRewardObj.type = this.type;
            this.$store.dispatch('reward/updateRewardObjName', this.editedRewardObj).then(function() {
                self.close();
            });
        },
        close() {
            this.editedRewardObj = {},
            this.$emit('close');
        },
    },
    computed: {
        parsedLabelName() {
            if (this.type == 'CHARACTER') {
                return this.$t('character-name');
            } else if (this.type == 'VILLAGE') {
                return this.$t('village-name');
            } else {
                return null;
            }
        },
    },
}
</script>
