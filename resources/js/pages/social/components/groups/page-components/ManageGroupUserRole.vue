<template>
    <div>
        <div class="form-group">
            <select v-if="rankId" id="role-select" v-model="rankId" name="role">
                <option v-for="role in groupRoles" :key="role.id" :value="role.id" :disabled="role.owner">
                    {{role.name}}
                </option>
            </select>
        </div>
        <SubmitButton @click="emit('promote', rankId)">{{$t('update-role')}}</SubmitButton>
    </div>
</template>

<script setup lang="ts">
import SubmitButton from '/js/components/global/small/SubmitButton.vue';
import {GroupUser, Rank} from 'resources/types/group';
import {onMounted, ref} from 'vue';

const props = defineProps<{groupUser: GroupUser, groupRoles: Rank[]}>();

const emit = defineEmits(['promote']);

const rankId = ref();

onMounted(() => {
    rankId.value = props.groupUser.rank.id;
})
</script>