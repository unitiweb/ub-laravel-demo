// const TInput = {
//     classes: 'border-2 block w-full rounded text-gray-800',
//     // Optional variants
//     variants: {
//         // ...
//     },
//     // Optional fixedClasses
//     // fixedClasses: '',
// }
//
// const TButton = {
//     classes: 'rounded-lg border block inline-flex items-center justify-center',
//     variants: {
//         secondary: 'rounded-lg border block inline-flex items-center justify-center bg-purple-500 border-purple-500 hover:bg-purple-600 hover:border-purple-600',
//     },
//     // Optional fixedClasses
//     // fixedClasses: 'transform ease-in-out duration-100',
// }

export default {
    TButton: {
        fixedClasses: 'inline-flex items-center font-medium border-transparent border-2 rounded-md shadow-md transition focus:outline-none focus:shadow-outline ease-in-out duration-150',
        variants: {
            primary: 'text-white bg-blue-600 border-blue-700 hover:bg-blue-500 focus:border-blue-700 active:bg-blue-700',
            secondary: 'text-white bg-gray-500 border-gray-600 hover:bg-gray-400 focus:border-gray-600 active:bg-gray-400',
            success: 'text-white bg-green-600 border-green-700 hover:bg-green-500 focus:border-green-700 active:bg-green-700',
            info: 'text-white bg-teal-600 border-teal-700 hover:bg-teal-500 focus:border-teal-700 active:bg-teal-700',
            warning: 'text-white bg-orange-500 border-orange-600 hover:bg-orange-400 focus:border-orange-600 active:bg-orange-600',
            danger: 'text-white bg-red-500 border-red-600 hover:bg-red-400 focus:border-red-600 active:bg-red-600',
            outline: 'text-gray-700 bg-gray-100 border-gray-500 hover:bg-gray-200 focus:border-gray-500 active:bg-gray-700'
        }
    },
    TInput: {
        classes: 'bg-white border-2 rounded-md border-gray-300 py-2 px-4 block w-full leading-normal focus:outline-none focus:shadow-outline',
        variants: {
            success: 'text-green-600 bg-green-100 focus:outline-none focus:shadow-outline border border-green-300 rounded py-2 px-4 block w-full leading-normal placeholder-green-300',
            error: 'text-red-600 bg-red-100 focus:outline-none focus:shadow-outline border border-red-300 rounded py-2 px-4 block w-full leading-normal placeholder-red-300'
        }
    },
    TInputGroup: {
        fixedClasses: {
            wrapper: 'rounded-md mb-4',
            label: 'block text-gray-600 uppercase tracking-wide text-xs font-bold mb-1 pl-2',
            body: 'shadow-md',
            feedback: 'text-sm',
            description: 'text-sm'
        },
        classes: {
            wrapper: '',
            label: '',
            body: '',
            feedback: 'text-gray-500',
            description: 'text-gray-500'
        },
        variants: {
            success: {
                label: 'text-green-500',
                feedback: 'text-green-500 pl-2'
            },
            error: {
                label: 'text-red-500',
                feedback: 'text-red-500 pl-2'
            }
        }
    },
    TCard: {
        fixedClasses: {
            wrapper: 'rounded-lg shadow-lg m-2 border-2 border-gray-300 max-w-lg mx-auto',
            body: 'bg-white rounded-md text-gray-700 p-4',
            header: 'p-4 border-b rounded-t-lg',
            footer: 'p-4 border-t rounded-b-lg'
        },
        classes: {
            wrapper: '',
            body: '',
            header: 'bg-gray-200',
            footer: 'bg-gray-200'
        },
        variants: {
            danger: {
                wrapper: 'bg-red-100',
                header: 'border-red-200 text-red-700',
                footer: 'border-red-200 bg-red-100 text-red-700'
            },
            clean: {
                wrapper: '',
                footer: 'bg-gray-100',
                body: 'p-4 text-sm text-gray-700'
            }
        }
    },
    TAlert: {
        fixedClasses: {
            wrapper: 'rounded p-4 m-2 flex text-sm shadow-md shadow-red border-l-4',
            body: 'flex-grow',
            close: 'ml-4 rounded',
            closeIcon: 'h-5 w-5 fill-current'
        },
        classes: {
            wrapper: 'bg-blue-100 border-blue-500',
            body: 'text-blue-700',
            close: 'text-blue-700 hover:text-blue-500 hover:bg-blue-200',
            closeIcon: 'h-5 w-5 fill-current'
        },
        variants: {
            danger: {
                wrapper: 'bg-red-100 border border-red-400',
                body: 'text-red-700',
                close: 'text-red-700 hover:text-red-500 hover:bg-red-200'
            },
            success: {
                wrapper: 'bg-green-100 border border-green-400',
                body: 'text-green-700',
                close: 'text-green-700 hover:text-green-500 hover:bg-green-200'
            },
            warning: {
                wrapper: 'bg-orange-100 border border-orange-400',
                body: 'text-orange-700',
                close: 'text-orange-700 hover:text-orange-500 hover:bg-orange-200'
            },
            clean: {
                wrapper: 'bg-gray-100 border border-gray-300',
                body: 'text-gray-700',
                close: 'text-gray-700 hover:text-gray-500 hover:bg-gray-200'
            }
        }
    },
    TTable: {
        classes: {
            table: 'shadow-md border border-gray-300 min-w-full divide-y divide-gray-200',
            tbody: 'bg-white divide-y divide-gray-200',
            td: 'px-6 py-4 whitespace-no-wrap text-sm leading-5 text-gray-700',
            theadTh: 'px-6 py-3 border-b border-gray-300 bg-gray-200 text-left text-xs leading-4 font-medium text-gray-700 uppercase tracking-wider'
        },
        variants: {
            thin: {
                td: 'p-1 whitespace-no-wrap text-sm leading-4 text-gray-700',
                theadTh: 'p-1 border-b border-gray-200 bg-gray-100 text-left text-xs leading-4 font-medium text-gray-700 uppercase tracking-wider'
            }
        }
    },
    TCheckbox: {
        fixedClasses: 'form-checkbox transition duration-150 ease-in-out',
        classes: {
            label: 'text-sm uppercase mx-2 text-gray-700',
            input: 'form-checkbox transition duration-150 ease-in-out',
            inputWrapper: 'inline-flex',
            wrapper: 'flex items-center',
        },
        variants: {
            error: 'text-red-500 shadow-md shadow-red-500',
            success: 'text-green-500 ',
            orange: 'text-orange-500 '
        }
    },
    TRichSelect: {
        fixedClasses: {
            wrapper: 'relative',
            buttonWrapper: 'inline-block relative w-full',
            selectButton: 'w-full flex text-left justify-between items-center',
            selectButtonLabel: 'block truncate',
            selectButtonPlaceholder: 'block truncate',
            selectButtonIcon: 'fill-current flex-shrink-0 ml-1 h-4 w-4',
            selectButtonClearButton: 'flex flex-shrink-0 items-center justify-center absolute right-0 top-0 m-2 h-6 w-6',
            selectButtonClearIcon: 'fill-current h-3 w-3',
            dropdown: 'absolute w-full z-10',
            dropdownFeedback: '',
            loadingMoreResults: '',
            optionsList: 'overflow-auto',
            searchWrapper: 'inline-block w-full',
            searchBox: 'inline-block w-full',
            optgroup: '',
            option: '',
            highlightedOption: '',
            selectedOption: '',
            selectedHighlightedOption: '',
            optionContent: '',
            optionLabel: 'truncate block',
            selectedIcon: 'fill-current h-4 w-4',
            enterClass: '',
            enterActiveClass: '',
            enterToClass: '',
            leaveClass: '',
            leaveActiveClass: '',
            leaveToClass: ''
        },
        classes: {
            wrapper: '',
            buttonWrapper: '',
            selectButton: 'bg-white border rounded p-2 focus:outline-none focus:shadow-outline',
            selectButtonLabel: '',
            selectButtonPlaceholder: 'text-gray-500',
            selectButtonIcon: '',
            selectButtonClearButton: 'hover:bg-gray-200 text-gray-500 rounded',
            selectButtonClearIcon: '',
            dropdown: 'rounded bg-white shadow',
            dropdownFeedback: 'text-sm text-gray-500',
            loadingMoreResults: 'text-sm text-gray-500',
            optionsList: '',
            searchWrapper: 'bg-white p-2',
            searchBox: 'p-2 bg-gray-200 text-sm rounded border focus:outline-none focus:shadow-outline',
            optgroup: 'text-gray-500 uppercase text-xs py-1 px-2 font-semibold',
            option: '',
            highlightedOption: 'bg-gray-300',
            selectedOption: 'font-semibold bg-gray-100',
            selectedHighlightedOption: 'bg-gray-300 font-semibold',
            optionContent: 'flex justify-between items-center p-2 cursor-pointer',
            optionLabel: 'truncate block',
            selectedIcon: '',
            enterClass: '',
            enterActiveClass: 'opacity-0 transition ease-out duration-100',
            enterToClass: 'opacity-100',
            leaveClass: 'transition ease-in opacity-100',
            leaveActiveClass: '',
            leaveToClass: 'opacity-0 duration-75'
        },
        variants: {
            danger: {
                selectButton: 'border-red-500 text-red-500 bg-red-100 border rounded p-2 focus:outline-none focus:shadow-outline',
                selectButtonPlaceholder: 'text-red-400',
                selectButtonClearButton: 'hover:bg-red-200 text-red-500 rounded'
            },
            success: {
                selectButton: 'border-green-500 text-green-500 bg-green-100 border rounded p-2 focus:outline-none focus:shadow-outline',
                selectButtonPlaceholder: 'text-green-400',
                selectButtonClearButton: 'hover:bg-green-200 text-green-500 rounded'
            }
        }
    }
}
