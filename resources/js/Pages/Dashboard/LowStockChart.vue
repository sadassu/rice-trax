<script setup>
import { ref } from "vue";

const props = defineProps({
    lowStockProducts: Array, // [{ name: "Rice", total_remaining: 20 }, ...]
});

const series = [
    {
        name: "Remaining (kg)",
        data: props.lowStockProducts.map((item) => ({
            x: item.name, // product name
            y: item.total_remaining, // kg remaining
        })),
    },
];

const chartOptions = ref({
    chart: {
        type: "bar",
        height: 400,
    },
    plotOptions: {
        bar: {
            horizontal: true,
            barHeight: "60%",
            dataLabels: {
                position: "right", // show data on right side
            },
        },
    },
    xaxis: {
        title: {
            text: "Kilograms Remaining",
        },
        min: 0,
        labels: {
            show: true,
        },
    },
    yaxis: {
        labels: {
            show: false, // ❌ hide product names on left
        },
    },
    dataLabels: {
        enabled: true,
        formatter: (val, opts) => {
            const productName =
                opts.w.config.series[opts.seriesIndex].data[opts.dataPointIndex]
                    .x;
            return `${productName}: ${val} kg`; // ✅ product name beside kg
        },
        style: {
            colors: ["#000"], // make sure it’s visible
        },
    },
    tooltip: {
        y: {
            formatter: (val) => val + " kg",
        },
    },
});
</script>

<template>
    <div class="p-4 bg-white rounded-lg shadow">
        <h2 class="text-lg font-bold mb-4">Low Stock Kilograms </h2>
        <apexchart
            type="bar"
            height="400"
            :options="chartOptions"
            :series="series"
        />
    </div>
</template>
