require('./bootstrap');

import './helpers/interceptors'

window.Vue = require('vue')
window.events = new Vue()

import error from './mixins/errors'
import authUser from './mixins/authUser'
Vue.mixin(error)
Vue.mixin(authUser)

import Toasted from 'vue-toasted'
Vue.use(Toasted, {
    duration: 3000,
    position: 'top-center'
})

window.events.$on('errors-general', error => {
    Vue.toasted.error(error)
})

import store from './store'

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const app = new Vue({
    el: '#app',
    store
});
