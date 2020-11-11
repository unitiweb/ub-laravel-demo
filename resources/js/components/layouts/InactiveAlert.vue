<template>
    <modal v-if="opened"
           title="Session is Expiring"
           type="info"
           confirm-label="Continue"
           cancel-label="Sign Out"
           @confirm="refreshTokens"
           @cancel="logout">
        <p class="text-center">Your online session will expire in</p>
        <h5 class="text-center">{{ expireDate }}</h5>
    </modal>
</template>

<script>
import moment from 'moment'
import Modal from '@/components/ui/modal/Modal'
import store from "@/store";
import {$http} from "@/scripts/http";

export default {
    components: {
        Modal
    },
    data() {
        return {
            timer: null,
            timeout: 5,
            notice: 120,
            opened: false,
            firstRun: true,
            ttl: 0,
            expireDate: ''
        }
    },
    methods: {
        // This prevents the modal from closing when user clicks the page outside the modal
        modelClosing() {
            this.opened = true
        },
        async refreshTokens() {
            const token = await store.getters.refreshToken
            const {data, site, settings, tokens} = await $http.refresh(token)
            await store.dispatch('login', {user: data, site, settings, tokens})
            this.reset()
            this.startTimer()
            this.opened = false
        },
        async logout() {
            this.reset()
            await this.$store.dispatch('logout', true)
            await this.$router.replace({ name: 'logout' })
            this.opened = false
        },
        lock() {
            this.reset()
            this.$store.dispatch('lock', true)
        },
        reset() {
            const config = this.$store.getters.tokensConfig
            const access = this.$store.getters.accessToken
            this.timeout = config.timeout
            this.notice = config.notice
            this.ttl = access.ttl
            this.firstRun = true
            this.opened = false
            this.expireDate = ''
            clearInterval(this.timer)
        },
        formatDuration() {
            const duration = moment.duration(this.ttl, 'seconds')
            const mins = moment.utc(duration.as('milliseconds')).format('m')
            const secs = moment.utc(duration.as('milliseconds')).format('s')
            let minsLabel = 'mins'
            if (mins === 1) minsLabel = 'min'
            return `${mins} ${minsLabel} ${secs} secs`
        },
        countdown() {
            this.timer = setInterval(() => {
                this.ttl -= this.timeout

                // Stop Interval and lock user when ttl reaches 0 or less
                if (this.ttl <= 0) {
                    clearInterval(this.timer)
                    this.lock()
                }

                // If the notice has been reached we open the dialog and start the countdown
                if (this.ttl <= this.notice) {
                    if (this.firstRun === true) {
                        this.opened = true
                        this.timeout = 1
                        this.firstRun = false
                        clearInterval(this.timer)
                        this.countdown()
                    }

                    // log out the last 10 seconds of the countdown
                    if (this.ttl <= 10) {
                        console.log('countdown ttl (last 10 seconds):', this.ttl)
                    }

                    // Format and update the expired string to be shown in the modal
                    this.expireDate = this.formatDuration()
                }
            }, this.timeout * 1000)
        },
        startTimer() {
            this.reset()
            this.countdown()
        }
    },
    mounted() {
        // Watch the "isLoggedIn" store variable so that if the user logs out the timer is stopped.
        this.$store.watch(state => state.auth.isLoggedIn, (value) => {
                if (value !== true) {
                    this.reset()
                }
            }
        )
        this.startTimer()
    }
}
</script>
