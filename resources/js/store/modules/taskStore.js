import axios from "axios";

export default {

    namespaced: true,
    action: {
        

        //Tasks
        storeTask: ({ commit }, task) => {
            return axios.post('/tasks', task).then(function (response) {
                commit('setResponseMessage', response.data.message);
                commit('setStatus', 'success');
                commit('taskList/setTaskLists', response.data.data);
                return Promise.resolve();
            });
        },
        updateTask: ({ commit }, task) => {
            return axios.put('/tasks/' + task.id, task).then(function (response) {
                commit('setResponseMessage', response.data.message);
                commit('setStatus', 'success');
                commit('taskList/setTaskLists', response.data.data);
                return Promise.resolve();
            });
        },
        deleteTask: ({ commit }, task) =>{
            axios.delete('/tasks/' + task.id, task).then(function(response) {
                commit('setResponseMessage', response.data.message);
                commit('setStatus', 'success');
                commit('taskList/setTaskLists', response.data.data);
            });
        },
        completeTask: ({commit}, task) => {
            axios.put('/tasks/complete/' + task.id).then(function(response){
                commit('setResponseMessage', response.data.message);
                commit('setStatus', 'success');
                commit('taskList/setTaskLists', response.data.data);
                commit('setCharacter', response.data.character);
            });
        },
    },
}