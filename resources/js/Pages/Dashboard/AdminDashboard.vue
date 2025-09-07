<script setup>
import SideBar from "../../Layouts/SideBar.vue";
import { ref, computed, onMounted, nextTick } from "vue";
import VueApexCharts from "vue3-apexcharts";
import SaleCards from "./SaleCards.vue";
import ActivityLogs from "./ActivityLogs.vue";
import { formatDate } from "../../utils/dateFormat";
import { formatCurrency } from "../../utils/currencyFormat";
import TopSellingProduct from "./TopSellingProduct.vue";
import LowStockChart from "./LowStockChart.vue";

defineOptions({ layout: SideBar });

const props = defineProps({
    forecastedSale: Object,
    totals: Object,
    topMostSaleProduct: Array,
    topFewSaleProduct: Array,
    lowStockProducts: Array,
    recentLogs: Array,
});

// Line Chart Configuration for Forecasted Sales
const lineChartOptions = computed(() => ({
    chart: {
        type: "line",
        height: 320,
        toolbar: { show: false },
        zoom: { enabled: false },
    },
    stroke: {
        curve: "smooth",
        width: 3,
    },
    colors: ["#14b8a6"],
    grid: {
        borderColor: "#f0f0f0",
        strokeDashArray: 3,
    },
    xaxis: {
        categories: props.forecastedSale?.labels || [],
        axisBorder: { show: false },
        axisTicks: { show: false },
        labels: {
            style: { colors: "#666", fontSize: "12px" },
        },
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
        y: {
            formatter: (val) => formatCurrency(val),
        },
    },
    markers: {
        size: 6,
        colors: ["#0d9488"],
        strokeColors: "#14b8a6",
        strokeWidth: 2,
        hover: { size: 8 },
    },
}));

const lineChartSeries = computed(() => [
    {
        name: "Sales",
        data: props.forecastedSale?.data || [],
    },
]);

const barChartSeries = computed(() => [
    {
        name: "Sales (kg)",
        data:
            props.topMostSaleProduct?.map(
                (product) =>
                    product.total_quantity ||
                    product.kilograms ||
                    product.quantity
            ) || [],
    },
]);

// Low Stock Bar Chart Configuration
</script>

<template>
    <div class="min-h-screen bg-gray-50 p-6">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">
                Admin Dashboard
            </h1>
            <p class="text-gray-600">
                Monitor your sales, inventory, and system activities
            </p>
        </div>

        <!-- Sales Summary Cards -->
        <SaleCards :totals="totals" />

        <!-- Charts Row -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mb-8">
            <!-- Forecasted Sales Line Chart -->
            <div
                class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100"
            >
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Sales Forecast
                    </h3>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-teal-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">Sales Trend</span>
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
        </div>

        <!-- Low Stock and Recent Logs Row -->
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 h-[300px]">
            <!-- Low Stock Products Chart -->
            <LowStockChart
                :lowStockProducts="lowStockProducts"
                class="h-full"
            />

            <!-- Recent System Logs -->
            <ActivityLogs :recent-logs="recentLogs" class="h-full" />
        </div>
    </div>
</template>

