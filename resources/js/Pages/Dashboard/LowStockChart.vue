<script setup>
const props = defineProps({
    lowStockProducts: {
        type: Array,
        default: () => [], // [{ name: "Rice", total_remaining: 20 }, ...]
    },
});

const getStockStatus = (kg) => {
    if (kg < 50) return { level: "critical", label: "Critical" };
    if (kg >= 50 && kg <= 100) return { level: "warning", label: "Low" };
    return { level: "normal", label: "Normal" };
};

const getStatusStyles = (kg) => {
    if (kg < 50) return "bg-red-50 border-l-4 border-red-500 hover:bg-red-100";
    if (kg >= 50 && kg <= 100)
        return "bg-amber-50 border-l-4 border-amber-500 hover:bg-amber-100";
    return "bg-gray-50 border-l-4 border-gray-300 hover:bg-gray-100";
};

const getBadgeStyles = (kg) => {
    if (kg < 50) return "bg-red-100 text-red-700 border border-red-200";
    if (kg >= 50 && kg <= 100)
        return "bg-amber-100 text-amber-700 border border-amber-200";
    return "bg-gray-100 text-gray-700 border border-gray-200";
};

const criticalCount = props.lowStockProducts.filter(
    (p) => p.total_remaining < 50
).length;
const warningCount = props.lowStockProducts.filter(
    (p) => p.total_remaining >= 50 && p.total_remaining <= 100
).length;
</script>

<template>
    <div
        class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden"
    >
        <!-- Header with Stats -->
        <div class="bg-gradient-to-r from-lime-500 to-lime-800 p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold mb-1">Inventory Alert</h2>
                    <p class="text-red-50 text-sm">
                        Products requiring attention
                    </p>
                </div>
                <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3">
                    <svg
                        class="w-8 h-8"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                        />
                    </svg>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-2 gap-4 mt-6">
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3">
                    <div class="text-3xl font-bold">{{ criticalCount }}</div>
                    <div class="text-red-50 text-sm">Critical Stock</div>
                </div>
                <div class="bg-white/10 backdrop-blur-sm rounded-lg p-3">
                    <div class="text-3xl font-bold">{{ warningCount }}</div>
                    <div class="text-red-50 text-sm">Low Stock</div>
                </div>
            </div>
        </div>

        <!-- Products List -->
        <div class="p-6 max-h-96 overflow-y-auto">
            <div v-if="lowStockProducts.length === 0" class="text-center py-12">
                <svg
                    class="w-16 h-16 mx-auto text-gray-300 mb-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                    />
                </svg>
                <p class="text-gray-500">No low stock products</p>
            </div>

            <div v-else class="space-y-3">
                <div
                    v-for="(item, i) in lowStockProducts"
                    :key="i"
                    :class="getStatusStyles(item.total_remaining)"
                    class="rounded-lg p-4 transition-all duration-200"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4 flex-1">
                            <!-- Warning Icon -->
                            <svg
                                v-if="item.total_remaining < 50"
                                class="w-5 h-5 flex-shrink-0 text-red-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"
                                />
                            </svg>
                            <!-- Trending Down Icon -->
                            <svg
                                v-else-if="item.total_remaining <= 100"
                                class="w-5 h-5 flex-shrink-0 text-amber-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"
                                />
                            </svg>
                            <!-- Package Icon -->
                            <svg
                                v-else
                                class="w-5 h-5 flex-shrink-0 text-gray-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                />
                            </svg>

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
                                <div class="text-2xl font-bold text-gray-900">
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
                                item.total_remaining < 50
                                    ? 'bg-red-500'
                                    : item.total_remaining <= 100
                                    ? 'bg-amber-500'
                                    : 'bg-green-500'
                            "
                            :style="{
                                width: `${Math.min(
                                    (item.total_remaining / 150) * 100,
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
