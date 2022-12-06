import Vue from "vue";
import BootstrapVue from "bootstrap-vue";
import * as VueDeepSet from "vue-deepset";
import merge from "deepmerge";
import qs from "qs";
import Base from "./base";
import { Model } from "vue-api-query";
import Routes from "./routes";
import VueRouter from "vue-router";
import LaravelVuePagination from "laravel-vue-pagination";
import "bootstrap";
import "../sass/app.scss";

Vue.use(BootstrapVue);
Vue.use(VueDeepSet);
Vue.use(VueRouter);

Vue.prototype.$http = window.axios.create();
Model.$http = window.axios;

window.merge = merge;
window.qs = qs;

window.FiscalRegistrar.basePath = "/" + window.FiscalRegistrar.path;

let routerBasePath = window.FiscalRegistrar.basePath + "/";

if (window.FiscalRegistrar.path === "" || window.FiscalRegistrar.path === "/") {
    routerBasePath = "/";
    window.FiscalRegistrar.basePath = "";
}

const router = new VueRouter({
    routes: Routes,
    mode: "history",
    base: routerBasePath,
    parseQuery(query) {
        return window.qs.parse(query);
    },
    stringifyQuery(query) {
        let result = window.qs.stringify(query, { encode: false });
        return result ? "?" + result : "";
    },
});

Vue.component("pagination", LaravelVuePagination);

Vue.mixin(Base);

new Vue({ el: "#fiscal-registrar", router });
