export default [
    { path: '/', redirect: '/receipts' },

    {
        path: '/receipts',
        name: 'receipts',
        component: require('./screens/receipts').default,
    },

    {
        path: '/receipt/new',
        name: 'new-receipt',
        component: require('./screens/receipt').default,
    },

    {
        path: '/receipt/from/:id',
        name: 'new-receipt-from-existing',
        component: require('./screens/receipt').default,
    },

    {
        path: '/receipt/:id',
        name: 'receipt',
        component: require('./screens/receipt').default,
    },
]
