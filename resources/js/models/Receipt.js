import { Model } from "vue-api-query";

export default class Receipt extends Model {
    baseURL() {
        return FiscalRegistrar.basePath + "/api/v1";
    }

    request(config) {
        return this.$http.request(config);
    }

    resource() {
        return "receipts";
    }

    async register() {
        const response = await this.request({ method: "POST", url: this.endpoint() + "/register" });
        this.state = 1;
        return this;
    }

    async sync() {
        const response = await this.request({ method: "GET", url: this.endpoint() + "/report" });
        this.result = response.data;
        this.state = 2;
        return this;
    }
}
