<template>
    <div v-if="rewardObj">
        <form @submit.prevent="updateRewardObj">
            <div class="form-group">
                <label for="name">{{parsedLabelName}}</label>
                <input 
                    id="name" 
                    v-model="editedRewardObj.name"
                    type="text" 
                    name="name" 
                    :placeholder="$t('name')"  />
                <base-form-error name="name" /> 
            </div>
            <button type="submit" class="block">{{ $t('update-reward-name') }}</button>
            <button type="button" class="block" @click="close">{{ $t('cancel') }}</button>
            <base-form-error name="error" /> 
        </form>
    </div>
</template>


<script>
import BaseFormError from '../BaseFormError.vue';
import {shallowRef} from 'vue';
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
        this.rewardObj ? this.editedRewardObj = shallowRef(this.rewardObj) : this.editedRewardObj = {};
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
