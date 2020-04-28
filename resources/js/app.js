require('./bootstrap');

import './helpers/interceptors'

window.Vue = require('vue')
window.events = new Vue()

import error from './mixins/errors'
Vue.mixin(error)

import Toasted from 'vue-toasted'
Vue.use(Toasted, {
    duration: 3000,
    position: 'top-center'
})

import store from './store'

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const app = new Vue({
    el: '#app',
    store
});
