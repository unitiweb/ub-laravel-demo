<template>
    <ub-button @click="linkOpen" icon-left="officeBuilding" variant="secondary" outline size="sm">
        <slot/>
    </ub-button>
</template>

<script>
    export default {

        props: {
            onSuccess: Function,
            onExit: {
                type: Function,
                required: false
            },
            onEvent: {
                type: Function,
                required: false
            }
        },

        data() {
            return {
                plaid: null,
                linkToken: null
            }
        },

        methods: {

            linkOpen() {
                this.$store.commit('loading', true)
                this.linkToken = '';
                this.$http.financialLinkToken().then(({data}) => {
                    this.openPlaid(data);
                    this.$store.commit('loading', false)
                }).catch(({ error }) => {
                    this.$store.commit('loading', false)
                })
            },

            openPlaid(data) {
                this.plaid.create({
                    token: data.token,
                    env: data.environment,
                    onSuccess: (publicToken, metadata) => {
                        this.$http.financialPublicToken(publicToken).then(({data}) => {
                            const account1 = {}
                            const account2 = {}
                            if (this.onSuccess) {
                                this.onSuccess([account1, account2])
                            }
                            this.$emit('success')
                            this.$emit('refresh')
                        }).catch(({error}) => {
                            this.$emit('error', error)
                        })
                    },
                    onLoad: () => {
                        if (this.onLoad) this.onLoad()
                    },
                    onExit: (err, metadata) => {
                        this.$emit('refresh')
                        if (this.onExit) this.onExit(err, metadata)
                    },
                    onEvent: (eventName, metadata) => {
                        if (this.onEvent) this.onEvent(eventName, metadata)
                    },
                    receivedRedirectUri: null,
                }).open()
            }
        },

        mounted() {
            let linkScript = document.createElement('script')
            linkScript.async = true
            linkScript.setAttribute('src', 'https://cdn.plaid.com/link/v2/stable/link-initialize.js')
            document.head.appendChild(linkScript)
            linkScript.onload = () => {
                this.plaid = window.Plaid
            }
        }
    }
</script>
