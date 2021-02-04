<template>
    <div>
        <group v-for="(group, index) in budget.groups"
               :key="`group-${index}`"
               :group="group"
               :active="isActive(group)"
               :active-row="activeRow"
               @modify-group="modifyGroup"
               @modify-entry="modifyEntry">
        </group>
        <group v-for="(group, index) in unassignedGroupEntries"
               :key="`unassigned-group-${index}`"
               :group="group"
               :active="isActive(group)"
               :active-row="activeRow"
               @modify-group="modifyGroup"
               @modify-entry="modifyEntry">
        </group>
    </div>
</template>

<script>
    import Group from '@/views/dashboard/budget/Group'
    import { mapGetters } from 'vuex'

    export default {

        components: {
            Group
        },

        props: {
            activeGroup: {
                type: [String, Number],
                default: null
            },
            activeRow: {
                type: [String, Number],
                default: null
            }
        },

        computed: {
            ...mapGetters(['budget']),

            budgetDate () {
                const year = this.$route.params.year
                const month = this.$route.params.month
                return `${year}-${month}-01`
            },

            unassignedGroupEntries () {
                const groups = []
                if (this.budget.unassignedGroupEntries && this.budget.unassignedGroupEntries.length >= 1) {
                    groups.push({
                        id: null,
                        unassigned: true,
                        name: 'Unassigned',
                        order: 10000,
                        entries: this.budget.unassignedGroupEntries
                    })
                }

                return groups
            }
        },

        methods: {

            modifyGroup (group) {
                this.$emit('modify-group', group)
            },

            async modifyEntry (entry) {
                this.$emit('modify-entry', entry)
            },

            isActive (group) {
                return group.id === this.activeGroup
            }

        }

    }

</script>
