<template>
    <div>
        <h5>{{ myGroups[index].name }}</h5>
        <div v-if="myGroups[index]">
            <b-form @submit.prevent="editGroup">
                <Editable :key="myGroups[index].name" :item="myGroups[index].name" :index="1" :name="'name'" @save="save" />
                
                <EditableTextarea 
                    :key="myGroups[index].description"
                    :item="myGroups[index].description" 
                    :index="2" 
                    :name="'description'" 
                    :rows="3" 
                    @save="save" />

                <b-button @click="togglePublic">{{myGroups[index].is_public ? 'Set to private' : 'Make public' }}</b-button>
                <!-- TODO turn this into a 'turn off button' -->
            </b-form>
        </div>
        <!-- Manage users -->
        <div>
            <b>Members</b>
            <div v-for="member in myGroups[index].members" :key="member.id" class="d-flex hover">
                <!-- TODO make a table -->
                {{member.username}}
                <span class="ml-auto">
                    <b-icon-envelope
                        class="small icon" />
                    <b-icon-x
                        class="small icon red" />

                </span>
            </div>
        </div>
    </div>
</template>

<script>
import Editable from '../../../small/Editable.vue';
import EditableTextarea from '../../../small/EditableTextarea.vue';
import {mapGetters} from 'vuex';
export default {
    components: {Editable, EditableTextarea},
    props: {
        index: {
            type: Number,
            required: true,
        },
    },
    computed: {
        ...mapGetters({
            myGroups: 'groups/getMyGroups',
        }),
    },
    methods: {
        save(item) {
            item.id = this.myGroups[this.index].id;
            this.$store.dispatch('groups/updateGroup', item);
        },
        togglePublic() {
            const group = {};
            group.is_public = !this.myGroups[this.index].is_public;
            group.id = this.myGroups[this.index].id;
            this.$store.dispatch('groups/updateGroup', group);
        },
    },
}
</script>