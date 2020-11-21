import { request, sha256 } from '@/scripts/http/utils';
import store from "@/store";

/**
 * Auth related requests
 */

export default {
    // Log in the user and get back the access and refresh tokens along with the user details
    login: (email, password) => {
        password = sha256(password)
        return request('post', ['auth', 'login'], { email, password })
    },

    // Register a new user
    register: (register) => {
        register.user.password = sha256(register.user.password)
        return request('post', ['auth', 'register'], register)
    },

    // Refresh the access token. Send it the refresh token and a new refresh and access token will be returned
    refresh: async (refreshToken) => {
        return request('post', ['auth', 'refresh'], { refreshToken })
    },

    tryRefresh: (method, url, params, relations) => {

    },

    // Check to see if the email is available
    emailAvailable: (email) => {
        return request('post', ['auth', 'register', 'email-available'], { email })
    },

    // Send the email verification code to validate the user's email
    verifyEmail: (code) => {
        return request('post', ['auth', 'tokens', 'verify-email'], { code })
    }
}
