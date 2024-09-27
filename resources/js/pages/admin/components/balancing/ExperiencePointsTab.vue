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
                <SimpleInput  
                    id="level" 
                    v-model="newLevel.level"
                    type="number" 
                    name="level" 
                    :label="$t('level')"
                    :placeholder="$t('level')"  />
                <SimpleInput  
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
        <Loading v-if="loading" />
        <div v-else class="col">
            <table class="table table-sm table-striped">
                <thead>
                    <tr>
                        <th>{{ $t('level') }}</th>
                        <th>{{ $t('points') }}</th>
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
import {onMounted, ref} from 'vue';
import {ExperiencePoint} from 'resources/types/admin';
import Loading from '/js/components/global/Loading.vue';
import {clearErrors} from '/js/services/errorService';
import axios from 'axios';

onMounted(async() => {
    const {data} = await axios.get('/admin/experience-points');
    experiencePoints.value = data.data;
    loading.value = false;
});

const experiencePoints = ref<Array<ExperiencePoint> | []>([]);
const newLevel = ref<ExperiencePoint>({level: 0, experience_points: 0});

const loading = ref(true);

async function updateExpPoints() {
    if (!experiencePoints.value) return;
    clearErrors();
    const {data} = await axios.put('/admin/experience-points', experiencePoints.value);
    experiencePoints.value = data.data.experience_points;
}

async function addNewLevel() {
    if (!newLevel.value) return;
    clearErrors();
    const {data} = await axios.post('/admin/experience-points', newLevel.value);
    experiencePoints.value = data.data.experience_points;
}
</script>