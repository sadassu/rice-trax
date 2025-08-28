import { defineStore } from "pinia";

export const usePosStore = defineStore("pos", {
    state: () => ({
        cart: [],
    }),
    actions: {
        addToCart(product, kilograms) {
            if (kilograms <= 0) {
                return { success: false };
            }

            const existing = this.cart.find((item) => item.id === product.id);

            if (existing) {
                if (
                    existing.kilograms + kilograms >
                    product.total_kg_remaining
                ) {
                    return { success: false };
                }
                existing.kilograms += kilograms;
                existing.total_price =
                    existing.kilograms * existing.price_per_kilo;
            } else {
                if (kilograms > product.total_kg_remaining) {
                    return { success: false };
                }
                this.cart.push({
                    id: product.id,
                    name: product.name,
                    price_per_kilo: product.price_per_kilo,
                    kilograms,
                    total_price: kilograms * product.price_per_kilo,
                });
            }

            return { success: true };
        },
        clearCart() {
            this.cart = [];
        },
        removeFromCart(id) {
            this.cart = this.cart.filter((item) => item.id !== id);
        },
    },
    persist: true,
});
