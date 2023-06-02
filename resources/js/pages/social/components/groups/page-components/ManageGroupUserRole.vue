<template>
    <div>
        <div class="form-group">
            <select v-if="rankId" id="role-select" v-model="rankId" name="role">
                <option v-for="role in groupRoles" :key="role.id" :value="role.id" :disabled="role.owner">
                    {{role.name}}
                </option>
            </select>
        </div>
        <SubmitButton @click="emit('submit', rankId)">{{$t('update-role')}}</SubmitButton>
    </div>
</template>

<script setup lang="ts">
import SubmitButton from '/js/components/global/small/SubmitButton.vue';
import {GroupUser, Rank} from 'resources/types/group';
import {onMounted, ref} from 'vue';
import {deepCopy} from '/js/helpers/copy';

const props = defineProps<{form: {groupUser: GroupUser, groupRoles: Rank[]}}>();

const groupUser = ref(deepCopy(props.form.groupUser));
const groupRoles = ref(deepCopy(props.form.groupRoles));

const emit = defineEmits(['submit', 'close']);

const rankId = ref();

onMounted(() => {
    rankId.value = groupUser.value.rank.id;
})
</script>