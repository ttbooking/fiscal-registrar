export default [
    { path: "/", redirect: "/receipts" },

    {
        path: "/receipts",
        name: "receipts",
        component: () => import("./screens/receipts.vue"),
    },

    {
        path: "/receipt/new",
        name: "new-receipt",
        component: () => import("./screens/receipt.vue"),
    },

    {
        path: "/receipt/from/:id",
        name: "new-receipt-from-existing",
        component: () => import("./screens/receipt.vue"),
    },

    {
        path: "/receipt/:id",
        name: "receipt",
        component: () => import("./screens/receipt.vue"),
    },
];
