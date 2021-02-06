<template>
    <div>
        <budget-right-header>
            <template v-slot:left>
                <ub-button @click="back" variant="secondary" outline icon="chevronLeft" class="float-left" size="sm"></ub-button>
            </template>
            <template v-slot:right>
                <div class="relative inline-block text-left">
                    <div>
                        <ub-button @click="toggleBankMenu" @blur="toggleBankMenu" variant="secondary" outline size="sm">
                            <icon name="officeBuilding" size="5" class="-l-mr-1 ml-2"></icon>
                            <span class="hidden sm:inline ml-2">Accounts</span>
                            <icon name="chevronDown" class="-l-mr-1 ml-2"></icon>
                        </ub-button>
                    </div>
                    <transition-fade>
                        <div v-show="showBankMenu" class="origin-top-right absolute right-0 mt-2 w-56 z-50 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-200" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                            <div v-for="institution in bankInstitutions" :key="`inst-${institution.id}`">
                                <p class="text-sm font-medium text-gray-900 truncate px-2 py-1">
                                    <img class="inline w-6 h-6 rounded-full" :src="logo(institution)" :alt="institution.name"/>
                                    <span class="text-md">{{ institution.name }}</span>
                                </p>
                                <a v-for="account in institution.accounts" v-if="account.enabled" :key="`account-${account.id}`" @click="loadTransactions(institution, account)" href="#" class="group px-2 py-1 flex items-center py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
<!--                                    <icon v-if="viewActive('incomes')" name="check" class="float-left text-green-700 mr-2"></icon>-->
                                    <span>{{ account.name }}</span>
                                </a>
                            </div>
                        </div>
                    </transition-fade>
                </div>
            </template>
        </budget-right-header>
        <div class="bg-gray-100 border border-gray-300 rounded-md shadow-md p-2 grid grid-cols-2">
            <div class="col-span-2 text-lg text-center font-bold p-2">
                <div v-if="bankAccount">
                    <div>
                        <img class="inline w-8 h-8 rounded-full mr-2" :src="logo(bankInstitution)" :alt="bankInstitution.name"/>
                        {{ bankAccount.name }}
                    </div>
                </div>
                <div v-else>
                    No Account Selected
                </div>
            </div>
            <div class="col-span-2">
                <div v-if="bankAccount" class="overscroll-y-auto">
                    <transaction v-for="transaction in bankTransactions"
                                 :key="`trans-${transaction.id}`"
                                 :account="bankAccount"
                                 :transaction="transaction">
                    </transaction>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Modal from "@/components/ui/modal/Modal";
    import BudgetRightHeader from '@/views/dashboard/budget/BudgetRightHeader'
    import Draggable from 'vuedraggable'
    import TransitionFade from '@/components/transitions/TransitionFade'
    import Transaction from '@/views/dashboard/banks/Transaction'
    import DropZone from '@/components/ui/dragdrop/DropZone'
    import { mapActions, mapGetters } from 'vuex'

    export default {

        components: {
            Modal,
            BudgetRightHeader,
            Draggable,
            TransitionFade,
            Transaction,
            DropZone
        },

        data () {
            return {
                showBankMenu: false
            }
        },

        computed: {
            ...mapGetters(['settings', 'bankInstitutions', 'bankInstitution', 'bankAccount', 'bankTransactions']),

            dragOptions() {
                return {
                    animation: 200,
                    sort: false,
                    group: { name: 'entries', pull: 'clone', put: false, revertClone: true },
                    disabled: false,
                    dragClass: '',
                    chosenClass: '',
                    ghostClass: '',
                    swapThreshold: 0,
                    dragoverBubble: true
                };
            }
        },

        methods: {
            ...mapActions(['setBankInstitutions', 'setBankInstitution', 'setBankAccount', 'setBankTransactions']),

            toggleBankMenu () {
                this.showBankMenu = !this.showBankMenu
            },

            logo (institution) {
                if (institution.logo) {
                    return 'data:image/png;base64, ' + institution.logo
                }
            },

            async loadBankAccounts () {
                try {
                    const { data } = await this.$http.financialInstitutions('accounts')
                    console.log('loadBankAccounts', data)
                    await this.setBankInstitutions(data)
                } catch ({ error }) {
                    console.log('error', error)
                }
            },

            async loadTransactions(institution, account) {
                await this.setBankInstitution(institution)
                await this.setBankAccount(account)
                await this.$http.updateSettings({
                    institution: institution.id,
                    account: account.id
                })
                try {
                    const { data } = await this.$http.financialTransactions(institution.id, account.id, 'entries,entries.budget,income,income.budget')
                    await this.setBankTransactions(data)
                } catch ({ error }) {
                    console.log('error', error)
                }
            },

            async loadAccount(institutionId, accountId) {
                try {
                    const { data } = await this.$http.financialInstitution(
                        institutionId,
                        'accounts'
                    )
                    this.setBankInstitution(data)
                    for (let i = 0; i < data.accounts.length; i++) {
                        if (data.accounts[i].id === accountId) {
                            this.setBankAccount(data.accounts[i])
                            await this.loadTransactions(data, data.accounts[i])
                            break;
                        }
                    }
                } catch ({ error }) {
                    console.log('error', error)
                }
            },

            back () {
                this.$emit('done')
            }
        },

        mounted () {
            // Load bank accounts
            this.loadBankAccounts()
            // Load last viewed institution and account if it is set
            if (this.settings.institution && this.settings.account) {
                this.loadAccount(this.settings.institution, this.settings.account)
            }
        }
    }
</script>
