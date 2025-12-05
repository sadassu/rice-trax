<script setup>
import SideBar from "../../Layouts/SideBar.vue";
import { Link, router } from "@inertiajs/vue3";
import { Archive, Search, TrendingUp, Wallet } from "lucide-vue-next";
import { ref, watch, computed } from "vue";
import PaginationLinks from "../../Components/PaginationLinks.vue";

defineOptions({ layout: SideBar });

const props = defineProps({
    products: Object,
    searchTerm: String,
    totalInventoryValue: Number,
    totalRecentInvestment: Number,
});

const search = ref(props.searchTerm || "");

// Calculate totals
const totalInventoryValue = computed(() => {
    return props.products.data
        .reduce((sum, product) => {
            return sum + parseFloat(product.inventory_value || 0);
        }, 0)
        .toFixed(2);
});

const totalRecentInvestment = computed(() => {
    return props.products.data
        .reduce((sum, product) => {
            return sum + parseFloat(product.recent_investment || 0);
        }, 0)
        .toFixed(2);
});

// Debounced search
let searchTimeout;
watch(search, (value) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        router.get(
            route("product-batches.index"),
            { search: value },
            {
                preserveState: true,
                preserveScroll: true,
            }
        );
    }, 300);
});
</script>

<template>
    <div class="min-h-screen bg-gray-50 p-4 sm:p-6 lg:p-8">
        <!-- Header Section -->
        <div class="mb-6 sm:mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
                Product Batches
            </h1>
            <p class="text-sm sm:text-base text-gray-600">
                Manage and view batches for all products
            </p>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Total Inventory Value Card -->
            <div
                class="bg-gradient-to-r from-lime-500 to-lime-600 rounded-2xl p-6 text-white shadow-lg"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p
                            class="text-lime-100 text-sm font-medium uppercase tracking-wide"
                        >
                            Total Inventory
                        </p>
                        <p class="text-3xl font-bold mt-2">
                            ₱ {{ props.totalInventoryValue }}
                        </p>
                        <p class="text-xs mt-1 opacity-80">
                            Total worth of all products currently in stock based
                            on remaining quantities and purchase prices
                        </p>
                    </div>
                    <div class="rounded-full p-3">
                        <Wallet class="w-8 h-8 text-white" />
                    </div>
                </div>
            </div>

            <!-- Recent Investment Card -->
            <div
                class="bg-gradient-to-r from-lime-500 to-lime-600 rounded-2xl p-6 text-white shadow-lg"
            >
                <div class="flex items-center justify-between">
                    <div>
                        <p
                            class="text-lime-100 text-sm font-medium uppercase tracking-wide"
                        >
                            Recent Investment
                        </p>
                        <p class="text-3xl font-bold mt-2">
                            ₱ {{ props.totalRecentInvestment }}
                        </p>
                        <p class="text-xs mt-1 opacity-80">
                            Total amount invested in purchasing new product
                            batches over the past 30 days
                        </p>
                    </div>
                    <div class="rounded-full p-3">
                        <TrendingUp class="w-8 h-8 text-white" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Search Bar -->
        <div class="mb-6">
            <div class="relative max-w-md">
                <input
                    v-model="search"
                    type="text"
                    placeholder="Search products..."
                    class="w-full px-4 py-2.5 pl-10 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <Search class="absolute left-3 top-3 w-5 h-5 text-gray-400" />
            </div>
        </div>

        <!-- Products Grid -->
        <div v-if="products.data && products.data.length > 0" class="space-y-6">
            <div
                class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6"
            >
                <Link
                    v-for="product in products.data"
                    :key="product.id"
                    :href="route('product-batches.product.show', product.id)"
                    class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow border border-gray-200 overflow-hidden block"
                >
                    <div class="p-4 sm:p-6">
                        <!-- Product Header -->
                        <div
                            class="flex items-start justify-between mb-3 sm:mb-4 gap-2"
                        >
                            <div class="flex-1 min-w-0">
                                <h3
                                    class="text-lg sm:text-xl font-semibold text-gray-900 mb-1 sm:mb-2 truncate"
                                >
                                    {{ product.name }}
                                </h3>
                            </div>
                            <span
                                class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs sm:text-sm font-medium bg-blue-100 text-blue-800 whitespace-nowrap flex-shrink-0"
                            >
                                {{ product.batches.length }} batch{{
                                    product.batches.length !== 1 ? "es" : ""
                                }}
                            </span>
                        </div>

                        <!-- Stock Information -->
                        <div
                            class="pt-4 border-t border-gray-100 flex items-center justify-between"
                        >
                            <div>
                                <p class="text-xs text-gray-500 mb-1">
                                    Total Stock
                                </p>
                                <p
                                    class="text-lg sm:text-xl font-bold text-gray-900"
                                >
                                    {{ product.batches_sum_kg_remaining || 0 }}
                                    kg
                                    <span
                                        class="text-sm sm:text-base font-normal text-gray-600"
                                    >
                                        ({{
                                            Math.floor(
                                                (product.batches_sum_kg_remaining ||
                                                    0) / 25
                                            )
                                        }}
                                        sack)
                                    </span>
                                </p>
                            </div>
                            <div
                                :class="[
                                    'px-3 py-1 rounded-full text-xs sm:text-sm font-medium',
                                    product.batches_sum_kg_remaining === 0
                                        ? 'bg-red-100 text-red-800'
                                        : product.batches_sum_kg_remaining < 50
                                        ? 'bg-amber-100 text-amber-800'
                                        : 'bg-green-100 text-green-800',
                                ]"
                            >
                                {{
                                    product.batches_sum_kg_remaining === 0
                                        ? "Out of Stock"
                                        : product.batches_sum_kg_remaining < 50
                                        ? "Low Stock"
                                        : "In Stock"
                                }}
                            </div>
                        </div>
                    </div>
                </Link>
            </div>

            <div class="mt-6">
                <PaginationLinks
                    :links="products.links"
                    :from="products.from"
                    :to="products.to"
                    :total="products.total"
                />
            </div>
        </div>

        <!-- Empty State -->
        <div v-else class="text-center py-12 sm:py-16">
            <div
                class="mx-auto w-20 h-20 sm:w-24 sm:h-24 bg-gray-100 rounded-full flex items-center justify-center mb-4"
            >
                <Archive class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400" />
            </div>
            <h3 class="text-base sm:text-lg font-medium text-gray-900 mb-2">
                {{ searchTerm ? "No Products Found" : "No Products Available" }}
            </h3>
            <p class="text-sm sm:text-base text-gray-500 max-w-sm mx-auto px-4">
                {{
                    searchTerm
                        ? `No products match "${searchTerm}". Try a different search term.`
                        : "There are currently no products to display. Add some products to get started with batch management."
                }}
            </p>
            <button
                v-if="searchTerm"
                @click="search = ''"
                class="mt-4 px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition-colors"
            >
                Clear Search
            </button>
        </div>
    </div>
</template>
