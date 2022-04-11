<template>
    <div>
        <form @submit.prevent="createGroup">
            <div class="form-group">
                <label for="name">{{$t('name')}}</label>
                <input
                    id="name"
                    v-model="groupToCreate.name"
                    name="name"
                    :placeholder="$t('name')" />
                <small class="form-text text-muted">{{$t('group-name-desc')}}</small>
                <base-form-error name="name" /> 
            </div>
            <div class="form-group">
                <label for="description">{{$t('group-desc')}}</label>
                <textarea
                    id="description"
                    v-model="groupToCreate.description"
                    name="description"
                    :placeholder="$t('description')" />
                <small class="form-text text-muted">{{$t('group-description-desc')}}</small>
                <base-form-error name="description" /> 
            </div>
            <div class="form-group">
                <input
                    id="public-checkbox"
                    v-model="groupToCreate.is_public"
                    type="checkbox"
                    name="public-checkbox" />
                <label for="public-checkbox" class="option-label">{{$t('group-public-checkbox')}}</label>
                <small class="form-text text-muted">{{$t('group-public-checkbox-desc')}}</small>
                <base-form-error name="public-checkbox" /> 
            </div>
            <button type="submit" class="block">{{$t('create-group')}}</button>
            <button type="button" class="block" @click="close">{{$t('cancel')}}</button>
        </form>
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
