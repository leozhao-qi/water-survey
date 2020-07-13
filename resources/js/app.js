require('./bootstrap');

import 'core-js/es/object/assign'
import 'core-js/es/symbol/iterator'

import './helpers/interceptors'

window.Vue = require('vue')
window.events = new Vue()

import error from './mixins/errors'
import authUser from './mixins/authUser'
import getUrlBase from './mixins/urlBase'

Vue.mixin(error)
Vue.mixin(authUser)
Vue.mixin(getUrlBase)

import Toasted from 'vue-toasted'
Vue.use(Toasted, {
    duration: 3000,
    position: 'top-center'
})

window.events.$on('errors-general', error => {
    Vue.toasted.error(error)
})

import VTooltip from 'v-tooltip'
Vue.use(VTooltip)

import store from './store'

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const app = new Vue({
    el: '#app',
    store
});
