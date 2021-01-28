import { request } from '@/scripts/http/utils'
import sha256 from '@/scripts/helpers/sha256'
import store from '@/store'

/**
 * Profile and Settings
 */

export default {
    // Update the user's profile details
    updateProfile: (profile) => request('patch', ['profile'], profile),

    // Change the user's password
    updatePassword: (passwords) => {
        passwords.original = sha256(passwords.original)
        passwords.password = sha256(passwords.password)
        return request('patch', ['profile', 'password'], passwords)
    },

    // Change the user's email address
    updateEmail: (email) => request('patch', ['profile', 'email'], { email }),

    // Update user specific settings
    updateSettings: async (settings) => {
        try {
            const response = await request('patch', ['profile', 'settings'], settings)
            // Send the new settings to the api
            await store.dispatch('updateSettings', response.data)
            return response
        } catch ({ error }) {
            console.log('error', error)
        }
    },

    // Update user specific site
    updateSite: (site) => request('patch', ['profile', 'site'], site),

    // Upload an image in a base64 format
    uploadAvatar: (image) => request('post', ['profile', 'avatar'], { image })
}
