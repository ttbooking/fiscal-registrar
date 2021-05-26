export default [
    { path: '/', redirect: '/receipts' },

    {
        path: '/receipts',
        name: 'receipts',
        component: require('./screens/receipts').default,
    },

    {
        path: '/receipts/:id',
        name: 'receipt',
        component: require('./screens/receipt').default,
    }
];
