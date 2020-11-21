import { request } from '@/scripts/http/utils';

/**
 * Group Requests
 */

export default {
    // get groups
    getGroups: (relations) => {
        return request('get', ['groups'], {}, relations)
    },

    // get a group
    getGroup: (groupId, relations) => {
        return request('get', ['groups', groupId], {}, relations)
    },

    // Create a new group
    addGroup: (group, relations) => {
        return request('post', ['groups'], group, relations)
    },

    // Update a group
    updateGroup: (groupId, group, relations) => {
        return request('patch', ['groups', groupId], group, relations)
    },

    // Delete group
    deleteGroup: (groupId, relations) => {
        return request('delete', ['groups', groupId], {}, relations)
    }
}
