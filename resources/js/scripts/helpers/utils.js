/**
 * Format a number with the given number of decimal places
 *
 * @param number The number to format
 * @param decimals The number of decimals
 *
 * @return {number}
 */
export const formatNumber = (number, decimals = 2) => {
  return Number(Math.round(number + 'e' + decimals ) +'e-' + decimals);
}

/**
 * Clean a numeric string so only 0-9 and first decimal are left after cleaning
 *
 * @param value The number to be cleaned
 *
 * @returns {string}
 */
export const cleanNumber = (value) => {
    if (value) {
        value = value.toString()
        return value
            .replace(/[^0-9.]/g, '')
            .replace('.', 'x')
            .replace(/\./g,'')
            .replace('x','.')
    }
}

/**
 * Take in a value and clean it to match currency without the symbol
 *
 * @param value
 *
 * @returns string
 */
export const cleanCurrency = (value) => {
    return Number.isInteger(value) ? value.toFixed(2) : value
}

/**
 * Format a currency string
 *
 * @param value
 * @param includeSymbol
 *
 * @returns string
 */
export const currency = (value, includeSymbol = true) => {
    const options = {
        currency: 'USD',
        minimumFractionDigits: 2
    }

    if (includeSymbol) {
        options.style = 'currency'
    }

    const formatter = new Intl.NumberFormat('en-US', options)

    return formatter.format(value)
}

/**
 * Calculate balances
 *
 * @param entries
 * @param amount
 *
 * @returns {balances}
 */
export const calculateBalances = (entries, amount) => {
    const balances = {
        expenses: 0,
        outstanding: 0,
        leftOver: 0
    }
    for (let ii = 0; ii < entries.length; ii++) {
        const entry = entries[ii]
        balances.expenses = balances.expenses + entry.amount
        if (!entry.cleared) {
            balances.outstanding = balances.outstanding + entry.amount
        }
    }
    balances.leftOver = amount - balances.expenses

    return balances
}

export const updateObject = (obj1, obj2) => {
    for (const [key, value] of Object.entries(obj2)) {
        obj1[key] = value
    }
}
