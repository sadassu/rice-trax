<script setup>
import { ref } from "vue";
import { formatCurrency } from "../../utils/currencyFormat";

const props = defineProps({
    topMostSaleProducts: Array,
});

// Extract data for chart
const categories = props.topMostSaleProducts.map((item) => item.name);

const series = [
    {
        name: "Total Sales (₱)",
        data: props.topMostSaleProducts.map((item) => item.total_sales),
    },
    {
        name: "Total Kilograms",
        data: props.topMostSaleProducts.map((item) => item.total_kilograms),
    },
];

const chartOptions = ref({
    chart: {
        type: "bar",
        height: 400,
        foreColor: "#000000",
    },
    colors: ["#10B981", "#14B8A6"], // green & lime
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: "50%",
        },
    },
    xaxis: {
        categories: categories,
        labels: {
            style: {
                colors: "#000000",
            },
        },
    },
    yaxis: {
        labels: {
            style: {
                colors: "#000000",
            },
        },
    },
    dataLabels: {
        enabled: true,
        style: {
            colors: ["#000000"],
        },
        formatter: (val, opts) => {
            if (opts.seriesIndex === 0) {
                return formatCurrency(val);
            }
            return val;
        },
    },
    tooltip: {
        style: {
            fontSize: "13px",
            color: "#000000",
        },
        y: {
            formatter: (val, opts) => {
                if (opts.seriesIndex === 0) {
                    return formatCurrency(val);
                }
                return val + " kg";
            },
        },
    },
});
</script>

<template>
    <div class="p-4 bg-white rounded-lg shadow text-black">
        <h2 class="text-lg font-bold mb-4">Product Sales</h2>
        <apexchart
            type="bar"
            height="400"
            :options="chartOptions"
            :series="series"
        />
    </div>
</template>
