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
