<template>
    <div>
        <div v-if="group">
            <form @submit.prevent="editGroup">
                <label for="edit-name-comp">Name</label>
                <Editable 
                    id="edit-name-comp" 
                    :key="group.name" 
                    class="ml-1 mb-2"
                    :item="group.name" 
                    :index="1" 
                    :name="'name'" 
                    @save="save" />
                
                <label for="edit-description-comp">Description</label>
                <Editable 
                    id="edit-description-comp"
                    :key="group.description"
                    class="ml-1 mb-2"
                    :item="group.description" 
                    :index="2" 
                    :name="'description'" 
                    :type="'textarea'"
                    :rows="3" 
                    @save="save" />

                <button class="m-1" @click="togglePublic">
                    {{group.is_public ? 'Set to private' : 'Make public' }}
                </button>
                <!-- TODO turn this into a 'turn off button' -->
            </form>
        </div>
        <!-- Manage users -->
        <div>
            <label for="">Members</label>
            <div v-for="member in group.members" :key="member.id" class="d-flex hover">
                <!-- TODO make a table -->
                {{member.username}}
                <span class="ml-auto">
                    <Tooltip :text="$t('send-message')">
                        <FaIcon 
                            icon="envelope"
                            class="icon small"
                            @click="sendMessage" />
                    </Tooltip>
                    <Tooltip :text="$t('kick')">
                        <FaIcon 
                            :icon="['far', 'rectangle-xmark']"
                            class="icon small red"
                            @click="kick" />
                    </Tooltip>

                </span>
            </div>
        </div>
    </div>
</template>

<script setup>
import Editable from '../../../small/Editable.vue';
import {useGroupStore} from '/js/store/groupStore';
const groupStore = useGroupStore();

const props = defineProps({
    group: {
        type: Object,
        required: true,
    },
});
function save(item) {
    item.id = props.group.id;
    groupStore.updateGroup(item);
}
function togglePublic() {
    const group = {};
    group.is_public = !props.group.is_public;
    group.id = props.group.id;
    groupStore.updateGroup(group);
}

function kick() {
    console.log('kick');
}
function sendMessage() {
    console.log('message');
}
</script>