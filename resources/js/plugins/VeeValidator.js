import Vue from 'vue';
import { ValidationProvider, ValidationObserver, extend } from 'vee-validate';
import { required, email, max, min } from 'vee-validate/dist/rules';

// Add a rule.
extend('required', required);
extend('email', email);
extend('max', max);
extend('min', min);

const VeeValidator = {
    install(Vue) {
        Vue.component('validation', ValidationProvider)
        Vue.component('validation-observer', ValidationObserver)
        Vue.prototype.$validator = ValidationProvider
    }
}

export default VeeValidator
