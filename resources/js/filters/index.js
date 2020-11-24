import { currency } from "@/scripts/helpers/utils";

const Currency = function (value) {
    if (!value) value = 0
    return currency(value)
}

const Filters = {
    install(Vue) {
        Vue.filter('currency', Currency)
    }
}

export default Filters
