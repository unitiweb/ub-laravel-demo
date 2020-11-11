import { request } from '@/scripts/http/utils'
import sha256 from '@/scripts/helpers/sha256'

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
        passwords.retype = sha256(passwords.retype)
        return request('patch', ['profile', 'password'], passwords)
    },

    // Change the user's email address
    updateEmail: (email) => request('patch', ['profile', 'email'], { email }),

    // Update user specific settings
    updateSettings: (settings) => request('patch', ['profile', 'settings'], settings),

    // Upload an image in a base64 format
    uploadAvatar: (image) => request('post', ['profile', 'avatar'], { image })
}
