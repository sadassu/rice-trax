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
const isCartModalOpen = ref(false);

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

const toggleCartModal = () => {
    isCartModalOpen.value = !isCartModalOpen.value;
};

const closeCartModal = () => {
    isCartModalOpen.value = false;
};
</script>

<template>
    <div class="flex h-screen">
        <!-- Products Section - Full width on mobile, half on desktop -->
        <div class="w-full lg:w-19/20 flex flex-col">
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
                        class="bg-gray-50 rounded-lg shadow-md border border-gray-200 overflow-hidden transition-shadow hover:shadow-lg"
                    >
                        <!-- Product with Stock -->
                        <div v-if="product.total_kg_remaining > 0" class="p-6">
                            <div class="flex items-start justify-center mb-4">
                                <h3
                                    class="martian-mono-800 text-2xl font-semibold text-gray-800 truncate uppercase"
                                >
                                    {{ product.name }}
                                </h3>
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

        <!-- Cart Section - Desktop Only (hidden on mobile/tablet) -->
        <div
            class="hidden lg:flex w-1/2 border-l border-gray-300 flex-col h-screen"
        >
            <div class="flex-1 overflow-y-auto">
                <CartComponent />
                <LastTransactions :sales="sales" />
            </div>
        </div>

        <!-- Floating Cart Button - Mobile/Tablet Only -->
        <button
            @click="toggleCartModal"
            class="lg:hidden fixed top-30 right-6 bg-blue-600 text-white rounded-full p-4 shadow-lg hover:bg-blue-700 transition-all duration-300 z-40 flex items-center justify-center"
        >
            <svg
                class="w-6 h-6"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                ></path>
            </svg>
            <span
                v-if="posStore.cart.length > 0"
                class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold rounded-full h-6 w-6 flex items-center justify-center"
            >
                {{ posStore.cart.length }}
            </span>
        </button>

        <!-- Cart Modal - Mobile/Tablet Only -->
        <Transition
            enter-active-class="transition-opacity duration-300"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="transition-opacity duration-300"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="isCartModalOpen"
                class="lg:hidden fixed inset-0 bg-black/50 bg-opacity-50 z-50 flex items-end"
                @click="closeCartModal"
            >
                <Transition
                    enter-active-class="transition-transform duration-300"
                    enter-from-class="translate-y-full"
                    enter-to-class="translate-y-0"
                    leave-active-class="transition-transform duration-300"
                    leave-from-class="translate-y-0"
                    leave-to-class="translate-y-full"
                >
                    <div
                        v-if="isCartModalOpen"
                        class="bg-white w-full rounded-t-2xl shadow-xl max-h-[90vh] flex flex-col"
                        @click.stop
                    >
                        <!-- Modal Header -->
                        <div
                            class="flex items-center justify-between p-4 border-b border-gray-200"
                        >
                            <h2 class="text-xl font-bold text-gray-800">
                                Your Cart
                            </h2>
                            <button
                                @click="closeCartModal"
                                class="text-gray-500 hover:text-gray-700 transition-colors"
                            >
                                <svg
                                    class="w-6 h-6"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    ></path>
                                </svg>
                            </button>
                        </div>

                        <!-- Modal Content -->
                        <div class="flex-1 overflow-y-auto">
                            <CartComponent />
                            <LastTransactions :sales="sales" />
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </div>
</template>
