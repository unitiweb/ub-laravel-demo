<template>
    <div class="p-2">
        <div v-if="group" class="grid grid-cols-2 gap-6">
            <div class="col-span-1">
                <ub-button v-if="group.id" @click="deleteGroup" outline variant="danger" class="float-left">delete</ub-button>
            </div>
            <div class="col-span-1 text-right">
                <ub-button variant="secondary" @click="cancel" outline>Cancel</ub-button>
                <ub-button @click="save">Save</ub-button>
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

    methods: {
        async save () {
            this.$store.commit('loading', true)
            try {
                if (this.group.id) {
                    await this.$http.updateGroup(this.group.id, {
                        name: this.group.name
                    })
                } else {
                    await this.$http.addGroup({
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
                await this.$http.deleteGroup(this.group.id)
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
