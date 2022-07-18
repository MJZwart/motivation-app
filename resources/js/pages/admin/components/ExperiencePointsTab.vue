<template>
    <div class="row">
        <div class="col">
            <!-- 
                Button to update the changed experience points in the table
                -->
            <button class="mt-4" @click="updateExpPoints">{{ $t('update-exp-points') }}</button>
            <GeneralFormError />
            <hr />
            <!-- 
                Fields to add a new level
                -->
            <div class="m-1">
                <h5>{{$t('add-new-level')}}</h5>
                <Input  
                    id="level" 
                    v-model="newLevel.level"
                    type="number" 
                    name="level" 
                    :label="$t('level')"
                    :placeholder="$t('level')"  />
                <Input  
                    id="points" 
                    v-model="newLevel.experience_points"
                    type="number" 
                    name="points" 
                    :label="$t('points')"
                    :placeholder="$t('points')"  />
                <button @click="addNewLevel">{{ $t('add-level') }}</button>
            </div>
        </div>
        <!-- 
            The Experience points table
            -->
        <div class="col">
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th>Level</th>
                        <th>Points</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(level, index) in experiencePoints" :key="index">
                        <td>{{level.level}}</td>
                        <td>
                            <input v-model="experiencePoints[index].experience_points" />
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup lang="ts">
import GeneralFormError from '/js/components/global/GeneralFormError.vue';
import {shallowRef, onMounted, computed, ref} from 'vue';
import {ExperiencePoint} from 'resources/types/admin';
import {useAdminStore} from '/js/store/adminStore';
import {useMainStore} from '/js/store/store';
const adminStore = useAdminStore();
const mainStore = useMainStore();

onMounted(() => {
    experiencePoints.value = shallowRef(experience_points.value).value;
    loading.value = false;
});

const experiencePoints = ref<Array<ExperiencePoint> | []>([]);
const newLevel = ref<ExperiencePoint>({level: 0, experience_points: 0});

const experience_points = computed(() => adminStore.experiencePoints);

const loading = ref(true);

function updateExpPoints() {
    if (!experiencePoints.value) return;
    clearErrors();
    adminStore.updateExpPoints(experiencePoints.value);
}

async function addNewLevel() {
    if (!newLevel.value) return;
    clearErrors();
    await adminStore.addNewLevel(newLevel.value);
    experiencePoints.value = shallowRef(experience_points).value;
}
function clearErrors() {
    mainStore.clearErrors();
}
</script>