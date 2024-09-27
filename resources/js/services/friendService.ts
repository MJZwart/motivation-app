import { computed, ref } from "vue";
import { user } from "./userService";
import axios from "axios";
import { Friend, FriendRequest, FriendRequests } from "resources/types/friend";

export const friends = computed<Friend[]>(() => user.value?.friends ?? []);
export const requests = ref<FriendRequests>();

export const sendRequest = async(friendId: string) => {
    await axios.post('/friend/request/' + friendId);
};

export const getRequests = async() => {
    const {data} = await axios.get('/friend/requests/all');
    requests.value = data;
};
