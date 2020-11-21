import config from '@/config';
import store from '@/store';
import router from '@/router';
import axios from 'axios';
import sha256Util from '@/scripts/helpers/sha256';

/**
 * Sha256 utility
 *
 * @return sha256
 */
export const sha256 = sha256Util

/**
 * Create the request url
 *
 * @param url The url
 * @param urlQuery The query string object
 * @param relations The relations to include
 *
 * @returns string
 */
export const makeUrl = (url, urlQuery, relations) => {
    // If the url is an array then create url string
    if (Array.isArray(url)) {
        url = '/' + url.join('/')
    }

    url = config.API_API_URL + url

    const query = []
    if (urlQuery) {
        for (const key in urlQuery) {
            const value = urlQuery[key]
            query.push(`${key}=${value}`)
        }
    }

    if (relations) {
        query.push('with=' + relations)
    }

    if (query.length >= 1) {
        return url + '?' + query.join('&')
    }

    return url
}

/**
 * Make a request to the api
 *
 * @param method
 * @param url
 * @param params
 * @param relations
 *
 * @returns Promise
 */
const makeRequest = (method, url, params, relations) => {
    const options = {
        method: method,
        headers: {
            'content-type': 'application/json'
        },
    }

    // Add the access token if it exists
    if (store.getters.accessToken.token) {
        options.headers.Authorization = `Bearer ${store.getters.accessToken.token}`
    }

    // For a get request add params to url query otherwise add to request body
    if (method.toLowerCase() === 'get') {
        options.url = makeUrl(url, params, relations)
    } else {
        options.url = makeUrl(url, null, relations)
        options.data = params
    }

    // Make the request
    return axios(options)
}

/**
 * Make a request to the api
 *
 * @param method The request method
 * @param url The request url
 * @param params The data for the request (if any)
 * @param relations Any "with" relations to pass to url
 *
 * @returns Promise
 */
export const request = (method, url, params, relations) => {
    return new Promise((resolve, reject) => {
        // Build the options object
        makeRequest(method, url, params, relations)
            .then(async ({ data }) => {
                resolve(data)
            }).catch(async (error) => {
                if (error.response.data.error.code === 403) {
                    // Since we got a 403 the user's session must have expired
                    location.href = router.resolve({ name: 'logout' }).href
                } else if (error.response) {
                    // The request was made and the server responded with a status code
                    // that falls out of the range of 2xx
                    reject(error.response.data)
                } else if (error.request) {
                    // The request was made but no response was received
                    // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                    // http.ClientRequest in node.js
                    reject(error.request)
                } else {
                    // Something happened in setting up the request that triggered an Error
                    reject(error.message)
                }
            })
    })
}
