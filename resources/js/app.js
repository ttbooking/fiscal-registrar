import Vue from 'vue';
import BootstrapVue from 'bootstrap-vue';
import * as VueDeepSet from 'vue-deepset';
import axios from 'axios';
import moment from 'moment';
import Routes from './routes';
import VueRouter from 'vue-router';
import 'bootstrap';

let token = document.head.querySelector('meta[name="csrf-token"]');

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

Vue.use(BootstrapVue);
Vue.use(VueDeepSet);
Vue.use(VueRouter);

Vue.component('pagination', require('laravel-vue-pagination'));

Vue.prototype.$http = axios.create();
Vue.prototype.$moment = moment;

window.FiscalRegistrar.basePath = '/' + window.FiscalRegistrar.path;

let routerBasePath = window.FiscalRegistrar.basePath + '/';

if (window.FiscalRegistrar.path === '' || window.FiscalRegistrar.path === '/') {
    routerBasePath = '/';
    window.FiscalRegistrar.basePath = '';
}

const router = new VueRouter({
    routes: Routes,
    mode: 'history',
    base: routerBasePath,
});

new Vue({
    el: '#fiscal-registrar',

    router,

    data: {
        message: 'Welcome to Fiscal Registrar home page!'
    }
});
