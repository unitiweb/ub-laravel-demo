require('@/bootstrap');
import '@/pollyfills'

window.Vue = require('vue');
import App from '@/App.vue'
import store from '@/store'
import router from '@/router'

// Tailwind Settings
import VueTailwind from 'vue-tailwind'
import VueTailwindTheme from '@/theme'
window.Vue.use(VueTailwind, VueTailwindTheme)

import UiComponents from "@/components/ui";
window.Vue.use(UiComponents)

import Filters from "@/filters";
window.Vue.use(Filters)

import Croppa from 'vue-croppa'
import 'vue-croppa/dist/vue-croppa.css'
window.Vue.use(Croppa)

import Vuelidate from 'vuelidate'
window.Vue.use(Vuelidate)

import Http from '@/scripts/http'
window.Vue.use(Http)

new Vue({
    el: '#app',
    store,
    router,
    render: h => h(App)
})
