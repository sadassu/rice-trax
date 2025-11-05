<script setup>
import SideBar from "../../Layouts/SideBar.vue";
import { computed } from "vue";
import VueApexCharts from "vue3-apexcharts";
import SaleCards from "./SaleCards.vue";
import ActivityLogs from "./ActivityLogs.vue";
import { formatCurrency } from "../../utils/currencyFormat";
import TopSellingProduct from "./TopSellingProduct.vue";
import LowStockChart from "./LowStockChart.vue";
import { formatDate } from "../../utils/dateFormat";
import SalesReports from "./SalesReports.vue";
import NotificationBar from "./NotificationBar.vue";

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

// Line Chart Configuration
const lineChartOptions = computed(() => {
    // unified timeline (historical + forecast)
    const levelDates = props.forecastedSale?.levels?.map((l) => l.date) || [];
    const trendDates = props.forecastedSale?.trends?.map((t) => t.date) || [];
    const forecastDates =
        props.forecastedSale?.forecast?.map((f) => f.date) || [];

    const categories = [
        ...new Set([...levelDates, ...trendDates, ...forecastDates]),
    ];

    return {
        chart: {
            type: "line",
            height: 320,
            toolbar: { show: false },
            zoom: { enabled: false },
        },
        stroke: { curve: "smooth", width: 3, dashArray: [0, 0, 5] },
        colors: ["#14b8a6", "#f43f5e", "#3b82f6"],
        grid: { borderColor: "#f0f0f0", strokeDashArray: 3 },
        xaxis: {
            categories,
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: { show: false },
        },
        yaxis: {
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: {
                style: { colors: "#666", fontSize: "12px" },
                formatter: (val) => formatCurrency(val),
            },
        },
        tooltip: {
            theme: "light",
            y: { formatter: (val) => formatCurrency(val) },
        },
        markers: { size: 5, strokeWidth: 2, hover: { size: 7 } },
    };
});

// Align data with categories
const lineChartSeries = computed(() => {
    const categories = lineChartOptions.value.xaxis.categories;

    const levelsMap = Object.fromEntries(
        props.forecastedSale?.levels?.map((l) => [l.date, l.value]) || []
    );
    const trendsMap = Object.fromEntries(
        props.forecastedSale?.trends?.map((t) => [t.date, t.value]) || []
    );
    const forecastMap = Object.fromEntries(
        props.forecastedSale?.forecast?.map((f) => [f.date, f.value]) || []
    );

    return [
        {
            name: "Levels",
            data: categories.map((date) => levelsMap[date] ?? null),
        },
        {
            name: "Trends",
            data: categories.map((date) => trendsMap[date] ?? null),
        },
        {
            name: "Forecast",
            data: categories.map((date) => forecastMap[date] ?? null),
        },
    ];
});
</script>

<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                Dashboard
            </h1>
            <p class="text-gray-600">
                Monitor your sales, inventory, and system activities
            </p>
        </div>

        <!-- Sales Summary Cards -->
        <SaleCards :totals="totals" />

        <!-- Main Content: Charts (2/3) + Activity Logs (1/3) -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Left Side: Charts (2/3 width) -->
            <div class="xl:col-span-2 space-y-6">
                <SalesReports />
                <!-- Levels/Trends/Forecast Line Chart -->
                <div
                    class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100"
                >
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-semibold text-gray-900">
                            Levels, Trends & Forecast
                        </h3>
                        <div class="flex items-center space-x-3">
                            <div
                                class="flex items-center space-x-1 text-sm text-gray-600"
                            >
                                <div
                                    class="w-3 h-3 bg-lime-500 rounded-full"
                                ></div>
                                <span>Levels</span>
                            </div>
                            <div
                                class="flex items-center space-x-1 text-sm text-gray-600"
                            >
                                <div
                                    class="w-3 h-3 bg-rose-500 rounded-full"
                                ></div>
                                <span>Trends</span>
                            </div>
                            <div
                                class="flex items-center space-x-1 text-sm text-gray-600"
                            >
                                <div
                                    class="w-3 h-3 bg-blue-500 rounded-full"
                                ></div>
                                <span>Forecast</span>
                            </div>
                        </div>
                    </div>
                    <div class="h-80">
                        <VueApexCharts
                            type="line"
                            height="320"
                            :options="lineChartOptions"
                            :series="lineChartSeries"
                        />
                    </div>
                </div>

                <!-- Top Products Bar Chart -->
                <TopSellingProduct :topMostSaleProducts="topMostSaleProduct" />

                <!-- Low Stock Chart -->
                <LowStockChart :lowStockProducts="lowStockProducts" />
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
