import { computed, ref } from "vue";
import { updateUserFriends, user } from "./userService";
import axios from "axios";
import { Friend, FriendRequests } from "resources/types/friend";
import { errorToast } from "./toastService";

export const friends = computed<Friend[]>(() => user.value?.friends ?? []);
export const requests = ref<FriendRequests>({
    incoming: [],
    outgoing: [],
});

export const sendRequest = async(friendId: string) => {
    await axios.post('/friend/request/' + friendId);
};

export const getRequests = async() => {
    const {data} = await axios.get('/friend/requests/all');
    requests.value = data;
};

export const acceptRequest = async(requestId: number) => {
    try {
        const {data} = await axios.post('/friend/request/' + requestId + '/accept');
        updateUserFriends(data.data.friends);
        requests.value = data.data.requests;
        return true;
    } catch (e) {
        return handleError(e);
    }
}
export const denyRequest = async(requestId: number)  => {
    try {
        const {data} = await axios.post('/friend/request/' + requestId + '/deny');
        requests.value = data.data.requests;
        return true;
    } catch (e) {
        return handleError(e);
    }
}
export const removeRequest = async(requestId: number) => {
    try {
        const {data} = await axios.delete('/friend/request/' + requestId);
        requests.value = data.data.requests;
        return true;
    } catch (e) {
        return handleError(e);
    }
}
export const removeFriend = async(friendId: number) => {
    try {
        const {data} = await axios.delete('/friend/remove/' + friendId);
        updateUserFriends(data.data.friends);
        return true;
    } catch (e) {
        return handleError(e);
    }
}

/**
 * I've added this error handler with the try-catch blocks because either user can
 * make a change to the friendship. If one user deletes the friendship while the other
 * user is about to make a change to it, the back-end will throw a 404 and not send
 * a proper error response. Reloading the page should always fix this problem, although
 * this leaves 500's and other issues unchecked.
 */
const handleError = (e: unknown) => {
    //@ts-ignore
    if (e.response.data.data.error === 'FRIEND_DELETED') {
        return true;
    } else {
        errorToast('Something went wrong, please reload the page.');
        return false;
    }
}