<script setup>
import { computed } from "vue";
import VueApexCharts from "vue3-apexcharts";
import { formatCurrency } from "../../utils/currencyFormat";

const props = defineProps({
    forecastedSale: {
        type: Object,
        required: true,
    },
});

// Line Chart Configuration
const lineChartOptions = computed(() => {
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
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-900">
                Levels, Trends & Forecast
            </h3>
        </div>

        <ul class="text-gray-700 mb-4 text-sm list-disc list-inside space-y-1">
            <li><strong>Levels:</strong> Actual daily revenue.</li>
            <li><strong>Trends:</strong> Direction of sales over time.</li>
            <li>
                <strong>Forecast:</strong> Predicted sales using Holt’s Linear
                method.
            </li>
        </ul>

        <div class="h-80">
            <VueApexCharts
                type="line"
                height="320"
                :options="lineChartOptions"
                :series="lineChartSeries"
            />
        </div>
    </div>
</template>
