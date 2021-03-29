export default [
    { path: '/', redirect: '/receipt' },

    {
        path: '/receipt',
        name: 'receipt',
        component: require('./screens/receipt').default,
    }
];
