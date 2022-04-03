<template>
    <div>
        <b-form @submit.prevent="createGroup">
            <div class="form-group">
                <label for="name">{{$t('name')}}</label>
                <input
                    id="name"
                    v-model="groupToCreate.name"
                    class="form-control"
                    name="name"
                    :placeholder="$t('name')" />
                <small class="form-text text-muted">{{$t('group-name-desc')}}</small>
                <base-form-error name="name" /> 
            </div>
            <div class="form-group">
                <label for="description">{{$t('group-desc')}}</label>
                <b-form-textarea
                    id="description"
                    v-model="groupToCreate.description"
                    name="description"
                    :placeholder="$t('description')" />
                <small class="form-text text-muted">{{$t('group-description-desc')}}</small>
                <base-form-error name="description" /> 
            </div>
            <div class="form-group">
                <label for="public-checkbox">{{$t('group-public-checkbox')}}</label>
                <b-form-checkbox
                    id="public-checkbox"
                    v-model="groupToCreate.is_public"
                    name="public-checkbox" />
                <small class="form-text text-muted">{{$t('group-public-checkbox-desc')}}</small>
                <base-form-error name="public-checkbox" /> 
            </div>
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
