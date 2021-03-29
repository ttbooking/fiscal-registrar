export default [
    { path: '/', redirect: '/receipt' },

    {
        path: '/receipt/:id',
        name: 'receipt',
        component: require('./screens/receipt').default,
    }
];
