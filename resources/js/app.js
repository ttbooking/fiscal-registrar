import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: true
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
