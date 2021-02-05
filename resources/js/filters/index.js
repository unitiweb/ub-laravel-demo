import { currency } from "@/scripts/helpers/utils";

const currencyFilter = function (value, includeSymbol = true) {
    if (!value) value = 0
    return currency(value, includeSymbol)
}

const transactionAmountFilter = function (value) {
    let number = value
    if (value >= 0) {
        number = '-' + '$' + Math.abs(value).toFixed(2)
    } else {
        number = '+' + '$' + Math.abs(value).toFixed(2)
    }

    return number
}

const Filters = {
    install(Vue) {
        Vue.filter('currency', currencyFilter)
        Vue.filter('transactionAmount', transactionAmountFilter)
    }
}

export default Filters
