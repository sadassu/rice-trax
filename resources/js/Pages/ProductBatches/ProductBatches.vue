<script setup>
import SideBar from "../../Layouts/SideBar.vue";
import { Link } from "@inertiajs/vue3";

defineOptions({ layout: SideBar });

const props = defineProps({
    products: Object,
    productBatches: Object,
});

// Get batch count for a product
const getBatchCount = (productId) => {
    if (!props.productBatches) return 0;
    return Object.values(props.productBatches).filter(
        (batch) => batch.product_id === productId
    ).length;
};
</script>

<template>
    <div class="p-6 bg-gray-50 min-h-screen">
        <!-- Header Section -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                Product Batches
            </h1>
            <p class="text-gray-600">
                Manage and view batches for all products
            </p>
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
                v-for="product in products"
                :key="product.id"
                class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 border border-gray-200 overflow-hidden"
            >
                <div class="p-6">
                    <!-- Product Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3
                                class="text-xl font-semibold text-gray-900 mb-2"
                            >
                                {{ product.name }}
                            </h3>
                            <p
                                v-if="product.category"
                                class="text-sm font-medium text-blue-600 uppercase tracking-wide"
                            >
                                {{ product.category }}
                            </p>
                        </div>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 ml-3"
                        >
                            {{ getBatchCount(product.id) }} batches
                        </span>
                    </div>

                    <!-- Product Description -->
                    <p
                        v-if="product.description"
                        class="text-gray-600 text-sm mb-6 leading-relaxed"
                    >
                        {{ product.description }}
                    </p>

                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div
            v-if="!products || Object.keys(products).length === 0"
            class="text-center py-16"
        >
            <div
                class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4"
            >
                <svg
                    class="w-12 h-12 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2M4 13h2m0 0V9a2 2 0 012-2h2m0 0V6a2 2 0 012-2h2.01"
                    />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">
                No Products Available
            </h3>
            <p class="text-gray-500 max-w-sm mx-auto">
                There are currently no products to display. Add some products to
                get started with batch management.
            </p>
        </div>
    </div>
</template>
