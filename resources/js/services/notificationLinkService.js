import axios from 'axios';
import {useFriendStore} from '/js/store/friendStore';

/**
 * Handles all notification links. Checks what the url type is (eg 'friend')
 * and handles accordingly. All other types are handled as direct back-end link
 * 
 * @param {string} apiType
 * @param {string} url
 */
export function handleNotificationLink(apiType, url) {
    const urlType = url.split('/')[1];
    switch (urlType) {
        case 'friend':
            handleFriendRequests(url);
            break;
        default:
            handleOtherLinks(apiType, url);
            break;
    }
}

/**
 * Handles the friend requests. It takes the type of action (accept/deny) out of
 * the url, as well as the request ID, and uses the friendStore to handle the action.
 * This way the friends are still sent back to the store, updated.
 * 
 * @param {string} url
 */
function handleFriendRequests(url) {
    const action = url.split('/')[4];
    const requestId = parseInt(url.split('/')[3]);
    const friendStore = useFriendStore();
    if (action == 'accept')
        friendStore.acceptRequest(requestId);
    else if (action == 'deny')
        friendStore.denyRequest(requestId);

}

/**
 * Handles all other links as direct back-end calls as per their api type.
 * 
 * @param {string} apiType
 * @param {string} url
 */
function handleOtherLinks(apiType, url) {
    if (apiType == 'POST')
        axios.post(url);
    else if (apiType == 'PUT')
        axios.put(url);
    else if (apiType == 'DELETE')
        axios.delete(url);
}


