import { currency } from "@/scripts/helpers/utils";

const Currency = function (value, includeSymbol = true) {
    if (!value) value = 0
    return currency(value, includeSymbol)
}

const Filters = {
    install(Vue) {
        Vue.filter('currency', Currency)
    }
}

export default Filters
