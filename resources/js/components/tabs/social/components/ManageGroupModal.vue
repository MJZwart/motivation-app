<template>
    <div>
        <h5>{{ group.name }}</h5>
        <div v-if="groupToEdit">
            <b-form @submit.prevent="editGroup">
                <Editable :item="groupToEdit.name" :index="groupToEdit.id" :name="'name'" @save="save" />
                <!-- <b-form-group
                    :label="$t('name')"
                    label-for="name"
                    :description="$t('group-name-desc')">
                    <b-form-input
                        id="name"
                        v-model="groupToEdit.name"
                        name="name"
                        :placeholder="$t('name')" />
                    <base-form-error name="name" /> 
                </b-form-group> -->
                
                <EditableTextarea 
                    :item="groupToEdit.description" 
                    :index="groupToEdit.id" 
                    :name="'description'" 
                    :rows="3" 
                    @save="save" />
                <!-- <b-form-group
                    :label="$t('group-desc')"
                    label-for="description"
                    :description="$t('group-description-desc')">
                    <b-form-textarea
                        id="description"
                        v-model="groupToEdit.description"
                        name="description"
                        :placeholder="$t('description')" />
                    <base-form-error name="description" /> 
                </b-form-group> -->
                <b-form-group
                    :label="$t('group-public-checkbox')"
                    label-for="public-checkbox"
                    :description="$t('group-public-checkbox-desc')">
                    <b-form-checkbox
                        id="public-checkbox"
                        v-model="groupToEdit.is_public"
                        name="public-checkbox" />
                    <base-form-error name="public-checkbox" /> 
                </b-form-group>
            </b-form>
            <!-- Change name -->
        </div>
        <div>
            <!-- Change desc -->
        </div>
        <div>
            <b>Members</b>
            <p v-for="member in group.members" :key="member.id">
                {{member.username}}
            </p>
            <!-- Manage users -->
        </div>
    </div>
</template>

<script>
import BaseFormError from '../../../BaseFormError.vue';
import Editable from '../../../small/Editable.vue';
import EditableTextarea from '../../../small/EditableTextarea.vue';
export default {
    components: {BaseFormError, Editable, EditableTextarea},
    props: {
        group: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            groupToEdit: null,
        }
    },
    mounted() {
        console.log(this.group);
        this.groupToEdit = this.group;
    },
    methods: {
        save(item) {
            item.id = this.group.id;
            this.$store.dispatch('groups/updateGroup', item).then(() => {
                console.log(this.group);
                this.groupToEdit = this.group;
            });
        },
    },
}
</script>