import UbButton from '@/components/ui/form/UbButton'
import FInput from '@/components/ui/form/FInput'
import FCard from '@/components/ui/form/FCard'
import Icon from '@/components/ui/Icon'
import Card from '@/components/ui/Card'
import Alert from '@/components/ui/Alert'
import FCheckbox from '@/components/ui/form/FCheckbox'
import UbSelect from '@/components/ui/form/UbSelect'
import UbSelectOption from '@/components/ui/form/UbSelectOption'
import UbSelectDivider from '@/components/ui/form/UbSelectDivider'
import FDatepicker from '@/components/ui/form/FDatepicker'
import FTransition from '@/components/ui/form/FTransition'
import UbBadge from '@/components/ui/UbBadge'
import UbDropdown from '@/components/ui/UbDropdown'

const UiComponents = {
    install(Vue) {
        Vue.component('icon', Icon)
        Vue.component('ub-button', UbButton)
        Vue.component('f-input', FInput)
        Vue.component('f-card', FCard)
        Vue.component('card', Card)
        Vue.component('alert', Alert)
        Vue.component('f-checkbox', FCheckbox)
        Vue.component('ub-select', UbSelect)
        Vue.component('ub-select-option', UbSelectOption)
        Vue.component('ub-select-divider', UbSelectDivider)
        Vue.component('f-datepicker', FDatepicker)
        Vue.component('f-transition', FTransition)
        Vue.component('ub-badge', UbBadge)
        Vue.component('ub-dropdown', UbDropdown)
    }
}

export default UiComponents
