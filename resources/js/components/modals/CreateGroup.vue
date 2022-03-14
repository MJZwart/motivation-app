<template>
    <div>
        <b-form @submit.prevent="createGroup">
            <b-form-group
                :label="$t('name')"
                label-for="name"
                :description="$t('group-name-desc')">
                <b-form-input
                    id="name"
                    v-model="groupToCreate.name"
                    name="name"
                    :placeholder="$t('name')" />
                <base-form-error name="name" /> 
            </b-form-group>
            <b-form-group
                :label="$t('group-desc')"
                label-for="description"
                :description="$t('group-description-desc')">
                <b-form-textarea
                    id="description"
                    v-model="groupToCreate.description"
                    name="description"
                    :placeholder="$t('description')" />
                <base-form-error name="description" /> 
            </b-form-group>
            <b-form-group
                :label="$t('group-public-checkbox')"
                label-for="public-checkbox"
                :description="$t('group-public-checkbox-desc')">
                <b-form-checkbox
                    id="public-checkbox"
                    v-model="groupToCreate.is_public"
                    name="public-checkbox" />
                <base-form-error name="public-checkbox" /> 
            </b-form-group>
            <b-button type="submit" block>{{$t('create-group')}}</b-button>
            <b-button type="button" block @click="close">{{$t('cancel')}}</b-button>
        </b-form>
    </div>
</template>

<script>
import BaseFormError from '../BaseFormError.vue';
export default {
    components: {BaseFormError},
    data() {
        return {
            groupToCreate: {
                name: '',
                description: '',
                is_public: false,
            },
        }
    },
    methods: {
        createGroup() {
            this.$store.dispatch('groups/createGroup', this.groupToCreate).then(() => {
                this.$emit('reloadGroups');
                this.close();
            });
        },
        close() {
            this.groupToCreate = {};
            this.$emit('close');
        },
    },
}
</script>
