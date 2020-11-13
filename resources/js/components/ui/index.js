import FButton from '@/components/ui/form/FButton'
import FInput from '@/components/ui/form/FInput'
import Icon from '@/components/ui/Icon'
import Card from '@/components/ui/Card'
import Alert from '@/components/ui/Alert'

const UiComponents = {
    install(Vue) {
        Vue.component('icon', Icon)
        Vue.component('f-button', FButton)
        Vue.component('f-input', FInput)
        Vue.component('card', Card)
        Vue.component('alert', Alert)
    }
}

export default UiComponents