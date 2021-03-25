import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: window.FiscalRegistrar.pusher.key,
    cluster: window.FiscalRegistrar.pusher.options.cluster ?? 'eu',
    forceTLS: window.FiscalRegistrar.pusher.options.useTLS ?? true
});

Echo.channel('fiscal-registrar')
    .listen('fiscal-registrar.registering', (e) => {
        console.log('Receipt registering:');
        console.log(e);
    })
    .listen('fiscal-registrar.registered', (e) => {
        console.log('Receipt registered:');
        console.log(e);
    });
