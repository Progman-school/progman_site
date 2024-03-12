import { defineStore } from 'pinia';
import mixins from "../mixins";

export const useProductStorage = defineStore('preloadedContent', {
    state: () => ({
        products: [],
    }),
    actions: {
        async getProductList() {
            await mixins.methods.getAPI(
                'get_all_product',
                null,
                (response) => this.products = response.data
            )
        },

        async getProductBy(paramName, paramValue) {
            let product = this.products.find(product => product['paramName'] === paramValue) ?? null
            if (product) {
                return product
            }
            await mixins.methods.getAPI(
                `get_product_by/${paramName}/${paramValue}`,
                null,
                (response) => {product = response.data}
            )
            this.products.push(product)
            return product;
        },

        async getProductByName(name) {
            let product = null
            await this.getProductBy('name', name).then(
                (response) => product = response
            )
            return product
        }
    },

});
