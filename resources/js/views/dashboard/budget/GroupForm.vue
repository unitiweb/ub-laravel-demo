<template>
    <div class="p-2">
        <div v-if="group" class="grid grid-cols-2 gap-6">
            <div class="col-span-1">
                <ub-button v-if="group.id" @click="deleteGroup" outline variant="danger" icon="trash" class="float-left" size="sm"></ub-button>
            </div>
            <div class="col-span-1 text-right">
                <ub-button variant="secondary" @click="cancel" outline size="sm">Cancel</ub-button>
                <ub-button @click="save" size="sm">Save</ub-button>
            </div>
            <div class="col-span-2">
                <f-input label="Group Name"
                         placeholder="entry name"
                         v-model="group.name">
                </f-input>
            </div>
        </div>
        <modal v-if="showDelete" variant="danger" title="Are you sure?" confirm-label="Yes, Delete!" cancel-label="Oops! No" @confirm="deleteConfirmed" @cancel="deleteCanceled">
            Do you really want to delete this group? It can't be undone.
        </modal>
    </div>
</template>

<script>
    import Modal from "@/components/ui/modal/Modal";

    export default {

        components: {
            Modal
        },

        props: {
            group: {
                type: Object,
                default: null
            }
        },

        data () {
            return {
                showDelete: false
            }
        },

        computed: {
            year () {
                return this.$route.params.year
            },
            month () {
                return this.$route.params.month
            },
            budgetMonth () {
                return `${this.year}-${this.month}-01`
            }
        },

        methods: {
            async save () {
                this.$store.commit('loading', true)
                try {
                    if (this.group.id) {
                        await this.$http.updateBudgetGroup(this.budgetMonth, this.group.id, {
                            name: this.group.name
                        })
                    } else {
                        await this.$http.addBudgetGroup(this.budgetMonth, {
                            name: this.group.name
                        })
                    }
                    this.$store.commit('loading', false)
                    this.$emit('done', true)
                } catch ({ error }) {
                    console.log('error', error)
                    this.$store.commit('loading', false)
                    this.$emit('done', true)
                }
            },
            cancel () {
                this.$emit('done', false)
            },
            deleteGroup () {
                this.showDelete = true
            },
            async deleteConfirmed () {
                try {
                    await this.$http.deleteBudgetGroup(this.budgetMonth, this.group.id)
                    this.$emit('done', true)
                    this.showDelete = false
                } catch ({ error }) {
                    this.$emit('done', true)
                    this.showDelete = false
                }
            },
            deleteCanceled () {
                this.showDelete = false
            }

        }

    }
</script>
