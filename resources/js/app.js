require('@/bootstrap');
import '@/pollyfills'

window.Vue = require('vue');
import App from '@/App.vue'
import store from '@/store'
import router from '@/router'

// import VIcon from 'vue-tailwind-icons';
// window.Vue.use(VIcon, { set: 'outline' })

import Http from '@/scripts/http'
window.Vue.use(Http)

new Vue({
    el: '#app',
    store,
    router,
    render: h => h(App)
})
