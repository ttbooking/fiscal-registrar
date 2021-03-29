import Echo from 'laravel-echo';
import Vue from 'vue';
import axios from 'axios';
import Routes from './routes';
import VueRouter from 'vue-router';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: window.FiscalRegistrar.pusher.key,
    cluster: window.FiscalRegistrar.pusher.options.cluster ?? 'eu',
    forceTLS: window.FiscalRegistrar.pusher.options.useTLS ?? true
});

window.Echo.channel('fiscal-registrar')
    .listen('.receipt.registering', (e) => {
        console.log('Receipt registering:');
        console.log(e);
    })
    .listen('.receipt.registered', (e) => {
        console.log('Receipt registered:');
        console.log(e);
    })
    .listen('.receipt.processed', (e) => {
        console.log('Receipt processed:');
        console.log(e);
    });

let token = document.head.querySelector('meta[name="csrf-token"]');

axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}

Vue.use(VueRouter);

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
