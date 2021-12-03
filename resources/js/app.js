import Vue from 'vue'
import BootstrapVue from 'bootstrap-vue'
import * as VueDeepSet from 'vue-deepset'
import Base from './base'
import Echo from 'laravel-echo'
import axios from 'axios'
import { Model } from 'vue-api-query'
import Routes from './routes'
import VueRouter from 'vue-router'
import 'bootstrap'

let token = document.head.querySelector('meta[name="csrf-token"]')

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content
}

Vue.use(BootstrapVue)
Vue.use(VueDeepSet)
Vue.use(VueRouter)

Vue.prototype.$http = axios.create()
Model.$http = axios

window.qs = require('qs')
window.merge = require('deepmerge')

window.Pusher = require('pusher-js')
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: window.FiscalRegistrar.pusher.key,
    cluster: window.FiscalRegistrar.pusher.cluster ?? 'eu',
    forceTLS: window.FiscalRegistrar.pusher.useTLS ?? true,
})

window.FiscalRegistrar.basePath = '/' + window.FiscalRegistrar.path

let routerBasePath = window.FiscalRegistrar.basePath + '/'

if (window.FiscalRegistrar.path === '' || window.FiscalRegistrar.path === '/') {
    routerBasePath = '/'
    window.FiscalRegistrar.basePath = ''
}

const router = new VueRouter({
    routes: Routes,
    mode: 'history',
    base: routerBasePath,
    parseQuery(query) {
        return window.qs.parse(query)
    },
    stringifyQuery(query) {
        let result = window.qs.stringify(query, { encode: false })
        return result ? '?' + result : ''
    },
})

Vue.component('pagination', require('laravel-vue-pagination'))

Vue.mixin(Base)

new Vue({ el: '#fiscal-registrar', router })
