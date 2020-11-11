<template>

    <layout>
        <template v-slot:header>
            <div class="float-left">
                Budget
            </div>
            <div class="float-right">
                <button>Testing</button>
            </div>
            <div class="clearfix"></div>
        </template>
        <div>
            <h1>My Budget</h1>
            <div class="border" style="height: 700px;">
                <modal v-if="show" title="Are you sure?" type="danger" cancel-label="No" confirm-label="Sure, Go ahead" @cancel="cancel" @confirm="confirm">
                    Are you sure you want to deactivate your account? All of your data will be permanently removed. This action cannot be undone.
                </modal>

                <form-button @click="open">Open</form-button>
            </div>
        </div>

    </layout>

</template>

<script>
    import Layout from "@/components/layouts/Layout";
    import PageHeader from "@/components/layouts/PageHeader";
    import Modal from '@/components/ui/modal/Modal'
    import FormButton from "@/components/ui/form/FormButton";
    import Spinner from "@/components/ui/Spinner";

    export default {

        components: {
            Layout,
            PageHeader,
            FormButton,
            Modal,
            Spinner
        },

        data () {
            return {
                show: false
            }
        },

        methods: {
            open () {
                this.show = !this.show
                // this.$store.commit('loading', true)
                console.log('show', this.show)
            },
            cancel () {
                this.show = false
                this.$store.commit('loading', false)
                console.log('cancel')
            },
            confirm () {
                this.show = false
                this.$store.commit('loading', false)
                console.log('success')
            }
        }

    }
</script>
