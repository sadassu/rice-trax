<script setup>
import { ref } from "vue";

import SideBar from "../../Layouts/SideBar.vue";
import SaleCards from "./SaleCards.vue";
import ActivityLogs from "./ActivityLogs.vue";
import TopSellingProduct from "./TopSellingProduct.vue";
import LowStockChart from "./LowStockChart.vue";
import SalesReports from "./SalesReports.vue";
import NotificationBar from "./NotificationBar.vue";
import LevelTrendsForecastChart from "./LevelTrendsForecastChart.vue";

defineOptions({ layout: SideBar });

const props = defineProps({
    forecastedSale: {
        type: Object,
        required: true,
    },
    totals: Object,
    topMostSaleProduct: Array,
    lowStockProducts: Array,
    recentLogs: Array,
    notifications: Array,
});

const tabs = [
    "Sales Reports",
    "Levels & Forecast",
    "Top Products",
    "Low Stock",
];
const activeTab = ref("Sales Reports");
</script>

<template>
    <div class="min-h-screen bg-gray-50 lg:p-6 p-2">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard</h1>
            <p class="text-gray-600">
                Monitor your sales, inventory, and system activities
            </p>
        </div>

        <!-- Sales Summary Cards -->
        <SaleCards :totals="totals" />

        <!-- Main Content: Charts (2/3) + Activity Logs (1/3) -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Left Side: Charts with Tabs -->
            <div class="xl:col-span-2 space-y-6">
                <!-- TAB BUTTONS -->
                <div class="flex space-x-1 bg-gray-100 p-1 rounded-lg">
                    <button
                        v-for="tab in tabs"
                        :key="tab"
                        @click="activeTab = tab"
                        :class="[
                            'px-4 py-2.5 rounded-md font-medium transition-all duration-200 flex-1',
                            activeTab === tab
                                ? 'bg-white text-blue-600 shadow-sm'
                                : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50',
                        ]"
                    >
                        {{ tab }}
                    </button>
                </div>

                <!-- TAB CONTENT -->
                <div class="bg-white p-4 rounded-lg shadow">
                    <SalesReports v-if="activeTab === 'Sales Reports'" />
                    <LevelTrendsForecastChart
                        v-if="activeTab === 'Levels & Forecast'"
                        :forecastedSale="forecastedSale"
                    />
                    <TopSellingProduct
                        v-if="activeTab === 'Top Products'"
                        :topMostSaleProducts="topMostSaleProduct"
                    />
                    <LowStockChart
                        v-if="activeTab === 'Low Stock'"
                        :lowStockProducts="lowStockProducts"
                    />
                </div>
            </div>

            <!-- Right Side: Activity Logs (1/3 width) -->
            <div class="xl:col-span-1 space-y-6">
                <NotificationBar :notifications="notifications" />
                <ActivityLogs :recent-logs="recentLogs" />

                <!-- You can add more components here -->
                <!-- Example: <AnotherComponent /> -->
            </div>
        </div>
    </div>
</template>
