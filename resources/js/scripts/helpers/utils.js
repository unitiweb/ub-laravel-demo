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
