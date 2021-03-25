import Echo from 'laravel-echo';

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
    });
