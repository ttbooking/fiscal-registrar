import Vue from 'vue';
import axios from 'axios';
import Routes from './routes';
import VueRouter from 'vue-router';
import 'bootstrap';

let token = document.head.querySelector('meta[name="csrf-token"]');

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

Vue.use(VueRouter);

Vue.component('pagination', require('laravel-vue-pagination'));

Vue.prototype.$http = axios.create();

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
