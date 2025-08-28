<script setup>
import { reactive, ref, watch } from "vue";
import SideBar from "../../Layouts/SideBar.vue";
import { router } from "@inertiajs/vue3";

import { debounce } from "lodash";
import { usePosStore } from "../../stores/pos";
import AddToCartComponent from "./AddToCartComponent.vue";
import CartComponent from "./CartComponent.vue";
import LastTransactions from "./LastTransactions.vue";

defineOptions({
    layout: SideBar,
});

const props = defineProps({
    products: Object,
    sales: Object,
    searchTerm: String,
});

const posStore = usePosStore();

const search = ref(props.searchTerm);

watch(
    search,
    debounce(
        (q) => router.get("/pos", { search: q }, { preserveState: true }),
        200
    )
);

const formatDecimal = (decimal) => {
    return parseFloat(decimal).toFixed(2);
};
</script>

<template>
    <div class="flex h-screen">
        <!-- Products Section - Left Half -->
        <div class="w-1/2 flex flex-col">
            <div class="px-4 py-6">
                <!-- Header -->
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">
                        POINT OF SALE
                    </h1>
                    <p class="text-gray-600">
                        All products with their current stock levels
                    </p>
                </div>

                <!-- Search -->
                <div class="flex">
                    <div class="w-full">
                        <input
                            type="search"
                            placeholder="Search"
                            v-model="search"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        />
                    </div>
                </div>
            </div>

            <!-- Scrollable Products Area -->
            <div
                class="flex-1 overflow-y-auto px-4 py-6 border-t border-gray-300"
            >
                <!-- Products Grid -->
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                    <div
                        v-for="product in products.data"
                        :key="product.id"
                        class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden transition-shadow hover:shadow-lg"
                    >
                        <!-- Product with Stock -->
                        <div v-if="product.total_kg_remaining > 0" class="p-6">
                            <div class="flex items-start justify-between mb-4">
                                <h3
                                    class="text-xl font-semibold text-gray-800 truncate uppercase"
                                >
                                    {{ product.name }}
                                </h3>
                                <span
                                    class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full"
                                >
                                    In Stock
                                </span>
                            </div>

                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-sm font-medium text-gray-600"
                                        >Price per kilo:</span
                                    >
                                    <span
                                        class="text-lg font-bold text-blue-600"
                                    >
                                        ₱{{
                                            formatDecimal(
                                                product.price_per_kilo
                                            )
                                        }}
                                    </span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <span
                                        class="text-sm font-medium text-gray-600"
                                        >Stock remaining:</span
                                    >
                                    <span
                                        class="text-lg font-semibold text-green-600"
                                    >
                                        {{
                                            formatDecimal(
                                                product.total_kg_remaining
                                            )
                                        }}
                                        kg
                                    </span>
                                </div>
                            </div>

                            <!-- Stock Level Indicator -->
                            <div class="mt-4">
                                <div
                                    class="flex justify-between text-xs text-gray-500 mb-1"
                                >
                                    <span>Stock Level</span>
                                    <span>{{
                                        product.total_kg_remaining <= 10
                                            ? "Low Stock"
                                            : "Good Stock"
                                    }}</span>
                                </div>
                                <div
                                    class="w-full bg-gray-200 rounded-full h-2"
                                >
                                    <div
                                        :class="[
                                            'h-2 rounded-full transition-all duration-300',
                                            product.total_kg_remaining <= 5
                                                ? 'bg-red-500'
                                                : product.total_kg_remaining <=
                                                  10
                                                ? 'bg-yellow-500'
                                                : 'bg-green-500',
                                        ]"
                                        :style="{
                                            width:
                                                Math.min(
                                                    (product.total_kg_remaining /
                                                        50) *
                                                        100,
                                                    100
                                                ) + '%',
                                        }"
                                    ></div>
                                </div>
                            </div>

                            <AddToCartComponent :product="product" />
                        </div>

                        <!-- Product Out of Stock -->
                        <div v-else class="p-6 bg-gray-50">
                            <div class="flex items-start justify-between mb-4">
                                <h3
                                    class="text-xl font-semibold text-gray-500 truncate"
                                >
                                    {{ product.name }}
                                </h3>
                                <span
                                    class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full"
                                >
                                    Out of Stock
                                </span>
                            </div>

                            <div class="flex items-center justify-center py-8">
                                <div class="text-center">
                                    <svg
                                        class="mx-auto h-12 w-12 text-gray-400 mb-3"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="1"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                        ></path>
                                    </svg>
                                    <p
                                        class="text-sm text-gray-500 font-medium"
                                    >
                                        This product doesn't have stock
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">
                                        Restock needed
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State (if no products) -->
                <div
                    v-if="!products.data || products.data.length === 0"
                    class="text-center py-12"
                >
                    <svg
                        class="mx-auto h-24 w-24 text-gray-400 mb-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                        ></path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">
                        No products found
                    </h3>
                    <p class="text-gray-500">
                        There are no products in your inventory yet.
                    </p>
                </div>
            </div>
        </div>

        <!-- Cart Section - Right Half -->
        <div class="w-1/2 border-l border-gray-300 flex flex-col h-screen">
            <div class="flex-1 overflow-y-auto">
                <CartComponent />
                <LastTransactions :sales="sales" />
            </div>
        </div>
    </div>
</template>
