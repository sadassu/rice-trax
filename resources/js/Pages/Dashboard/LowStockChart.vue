<script setup>
import { Package, AlertTriangle, TrendingDown, XCircle } from "lucide-vue-next";

const props = defineProps({
    lowStockProducts: {
        type: Array,
        default: () => [], // [{ name: "Rice", total_remaining: 20 }, ...]
    },
});

// Stock status labels
const getStockStatus = (kg) => {
    if (kg <= 0) return { level: "out", label: "Out of Stock" };
    if (kg < 50) return { level: "low", label: "Low Stock" };
    return { level: "in", label: "In Stock" };
};

// Background & border color styles
const getStatusStyles = (kg) => {
    if (kg <= 0) return "bg-red-50 border-l-4 border-red-500 hover:bg-red-100";
    if (kg < 50)
        return "bg-amber-50 border-l-4 border-amber-500 hover:bg-amber-100";
    return "bg-green-50 border-l-4 border-green-500 hover:bg-green-100";
};

// Badge color styles
const getBadgeStyles = (kg) => {
    if (kg <= 0) return "bg-red-100 text-red-700 border border-red-200";
    if (kg < 50) return "bg-amber-100 text-amber-700 border border-amber-200";
    return "bg-green-100 text-green-700 border border-green-200";
};

// Count summaries
const outOfStockCount = props.lowStockProducts.filter(
    (p) => p.total_remaining <= 0
).length;

const lowStockCount = props.lowStockProducts.filter(
    (p) => p.total_remaining > 0 && p.total_remaining < 50
).length;

const inStockCount = props.lowStockProducts.filter(
    (p) => p.total_remaining >= 50
).length;

const getTotalSacks = (kg) => {
    return Math.floor(kg / 25);
};
</script>

<template>
    <div
        class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
    >
        <!-- Header -->
        <div
            class="bg-gradient-to-r from-lime-500 to-lime-800 p-4 sm:p-6 text-white"
        >
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold mb-1">
                        Inventory Status
                    </h2>
                    <p class="text-red-50 text-xs sm:text-sm">
                        Products requiring attention
                    </p>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-lg p-2 sm:p-3">
                    <Package class="w-6 h-6 sm:w-8 sm:h-8 text-white" />
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-2 sm:gap-4 mt-4 sm:mt-6">
                <div
                    class="bg-violet-500/50 backdrop-blur-sm rounded-lg p-2 sm:p-3"
                >
                    <div class="text-xl sm:text-3xl font-bold">
                        {{ outOfStockCount }}
                    </div>
                    <div class="text-red-50 text-xs sm:text-sm">
                        Out of Stock
                    </div>
                </div>
                <div
                    class="bg-violet-500/50 backdrop-blur-sm rounded-lg p-2 sm:p-3"
                >
                    <div class="text-xl sm:text-3xl font-bold">
                        {{ lowStockCount }}
                    </div>
                    <div class="text-red-50 text-xs sm:text-sm">Low Stock</div>
                </div>
                <div
                    class="bg-violet-500/50 backdrop-blur-sm rounded-lg p-2 sm:p-3"
                >
                    <div class="text-xl sm:text-3xl font-bold">
                        {{ inStockCount }}
                    </div>
                    <div class="text-red-50 text-xs sm:text-sm">In Stock</div>
                </div>
            </div>
        </div>

        <!-- Product List -->
        <div class="p-4 sm:p-6 max-h-96 overflow-y-auto">
            <div v-if="lowStockProducts.length === 0" class="text-center py-12">
                <Package
                    class="w-12 h-12 sm:w-16 sm:h-16 mx-auto text-gray-300 mb-4"
                />
                <p class="text-sm sm:text-base text-gray-500">
                    No low stock products
                </p>
            </div>

            <div v-else class="space-y-3">
                <div
                    v-for="(item, i) in lowStockProducts"
                    :key="i"
                    :class="getStatusStyles(item.total_remaining)"
                    class="rounded-lg p-3 sm:p-4 transition-all duration-200"
                >
                    <!-- Mobile Layout (< 640px) -->
                    <div class="sm:hidden">
                        <div class="flex items-start justify-between mb-3">
                            <div
                                class="flex items-start space-x-3 flex-1 min-w-0"
                            >
                                <XCircle
                                    v-if="item.total_remaining === 0"
                                    class="w-5 h-5 text-gray-600 flex-shrink-0 mt-0.5"
                                />
                                <AlertTriangle
                                    v-else-if="item.total_remaining < 50"
                                    class="w-5 h-5 text-red-500 flex-shrink-0 mt-0.5"
                                />
                                <TrendingDown
                                    v-else-if="item.total_remaining <= 100"
                                    class="w-5 h-5 text-amber-500 flex-shrink-0 mt-0.5"
                                />
                                <Package
                                    v-else
                                    class="w-5 h-5 text-gray-500 flex-shrink-0 mt-0.5"
                                />

                                <div class="flex-1 min-w-0">
                                    <h3
                                        class="font-semibold text-gray-900 text-sm truncate"
                                    >
                                        {{ item.name }}
                                    </h3>
                                    <p class="text-xs text-gray-500">
                                        Product inventory
                                    </p>
                                </div>
                            </div>

                            <span
                                :class="getBadgeStyles(item.total_remaining)"
                                class="px-2 py-1 rounded-full text-xs font-semibold whitespace-nowrap ml-2 flex-shrink-0"
                            >
                                {{ getStockStatus(item.total_remaining).label }}
                            </span>
                        </div>

                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div class="text-center bg-white/50 rounded-lg p-2">
                                <div
                                    class="text-xl font-bold"
                                    :class="
                                        getTotalSacks(item.total_remaining) ===
                                        0
                                            ? 'text-gray-500'
                                            : 'text-gray-900'
                                    "
                                >
                                    {{ getTotalSacks(item.total_remaining) }}
                                </div>
                                <div class="text-xs text-gray-500">Sacks</div>
                            </div>
                            <div class="text-center bg-white/50 rounded-lg p-2">
                                <div
                                    class="text-xl font-bold"
                                    :class="
                                        item.total_remaining === 0
                                            ? 'text-gray-500'
                                            : 'text-gray-900'
                                    "
                                >
                                    {{ item.total_remaining }}
                                </div>
                                <div class="text-xs text-gray-500">kg</div>
                            </div>
                        </div>
                    </div>

                    <!-- Desktop Layout (>= 640px) -->
                    <div class="hidden sm:flex items-center justify-between">
                        <div class="flex items-center space-x-4 flex-1">
                            <XCircle
                                v-if="item.total_remaining === 0"
                                class="w-5 h-5 text-gray-600"
                            />
                            <AlertTriangle
                                v-else-if="item.total_remaining < 50"
                                class="w-5 h-5 text-red-500"
                            />
                            <TrendingDown
                                v-else-if="item.total_remaining <= 100"
                                class="w-5 h-5 text-amber-500"
                            />
                            <Package v-else class="w-5 h-5 text-gray-500" />

                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-900">
                                    {{ item.name }}
                                </h3>
                                <p class="text-sm text-gray-500">
                                    Product inventory status
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <div class="text-right mr-4">
                                <div
                                    class="text-2xl font-bold"
                                    :class="
                                        getTotalSacks(item.total_remaining) ===
                                        0
                                            ? 'text-gray-500'
                                            : 'text-gray-900'
                                    "
                                >
                                    {{ getTotalSacks(item.total_remaining) }}
                                </div>
                                <div class="text-xs text-gray-500">Sack</div>
                            </div>
                        </div>

                        <div class="flex items-center space-x-4">
                            <div class="text-right mr-4">
                                <div
                                    class="text-2xl font-bold"
                                    :class="
                                        item.total_remaining === 0
                                            ? 'text-gray-500'
                                            : 'text-gray-900'
                                    "
                                >
                                    {{ item.total_remaining }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    kilograms
                                </div>
                            </div>
                            <span
                                :class="getBadgeStyles(item.total_remaining)"
                                class="px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap"
                            >
                                {{ getStockStatus(item.total_remaining).label }}
                            </span>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div
                        class="mt-3 bg-gray-200 rounded-full h-2 overflow-hidden"
                    >
                        <div
                            class="h-full rounded-full transition-all duration-300"
                            :class="
                                item.total_remaining === 0
                                    ? 'bg-red-500'
                                    : item.total_remaining < 50
                                    ? 'bg-amber-500'
                                    : item.total_remaining <= 100
                                    ? 'bg-amber-500'
                                    : 'bg-green-500'
                            "
                            :style="{
                                width:
                                    item.total_remaining === 0
                                        ? '0%'
                                        : `${Math.min(
                                              (item.total_remaining / 150) *
                                                  100,
                                              100
                                          )}%`,
                            }"
                        ></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
