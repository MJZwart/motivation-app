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
            </b-form-group>
            <b-form-group
                :label="$t('public-checkbox')"
                label-for="public-checkbox"
                :description="$t('group-public-checkbox-desc')">
                <b-form-checkbox
                    id="public-checkbox"
                    v-model="groupToCreate.is_public"
                    name="public-checkbox" />
            </b-form-group>
            <b-button type="submit" block>{{$t('create-group')}}</b-button>
            <b-button type="button" block @click="close">{{$t('cancel')}}</b-button>
        </b-form>
    </div>
</template>

<script>
export default {
    data() {
        return {
            groupToCreate: {
                name: "",
                description: "",
                is_public: false,
            }
        }
    },
    methods: {
        createGroup() {
            console.log(`group "${this.groupToCreate.name}" with desc: "${this.groupToCreate.description}" and publicity set to: ${this.groupToCreate.is_public} has totally been created`);
            this.$store.dispatch('groups/createGroup', this.groupToCreate).then(() => {
                this.$emit('reloadGroups');
                this.close();
            });
        },
        close() {
            this.groupToCreate = {};
            this.$emit('close');
        }
    },
}
</script>
