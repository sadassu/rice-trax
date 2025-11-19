<script setup>
import { ref, onMounted, watch } from "vue";
import VueApexCharts from "vue3-apexcharts";
import axios from "axios";
import { formatCurrency } from "../../utils/currencyFormat";

const chartOptions = ref({
    chart: {
        type: "line",
        height: 320,
        toolbar: { show: false },
        zoom: { enabled: false },
    },
    stroke: { curve: "smooth", width: 3 },
    colors: ["#10b981"],
    grid: { borderColor: "#f0f0f0", strokeDashArray: 3 },
    xaxis: {
        categories: [],
        axisBorder: { show: false },
        axisTicks: { show: false },
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
});

const series = ref([{ name: "Sales", data: [] }]);

const filter = ref("daily");
const year = ref(new Date().getFullYear());
const month = ref(new Date().getMonth() + 1);

async function fetchReport() {
    try {
        const { data } = await axios.get("/sales-report", {
            params: {
                filter: filter.value,
                year: year.value,
                month: month.value,
            },
        });

        // Labels for x-axis
        const labels = data.report.map((item) => {
            if (data.filter === "daily") {
                const date = new Date(item.period);
                return date.getDate(); // show day number only
            }
            if (data.filter === "monthly") {
                const months = [
                    "Jan",
                    "Feb",
                    "Mar",
                    "Apr",
                    "May",
                    "Jun",
                    "Jul",
                    "Aug",
                    "Sep",
                    "Oct",
                    "Nov",
                    "Dec",
                ];
                return months[item.period - 1];
            }
            if (data.filter === "yearly") return item.period;
        });

        const values = data.report.map((item) => parseFloat(item.total_sales));

        chartOptions.value = {
            ...chartOptions.value,
            xaxis: {
                categories: labels,
                axisBorder: { show: false },
                axisTicks: { show: false },
            },
        };
        series.value = [{ name: "Sales", data: values }];
    } catch (err) {
        console.error("Error fetching report:", err);
    }
}

onMounted(fetchReport);
watch([filter, year, month], fetchReport);
</script>

<template>
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-900">Sales Reports</h3>

            <!-- Filters -->
            <div class="flex items-center space-x-3">
                <select
                    v-model="filter"
                    class="border rounded-lg px-3 py-1 text-sm"
                >
                    <option value="daily">Daily</option>
                    <option value="monthly">Monthly</option>
                    <option value="yearly">Yearly</option>
                </select>
                <input
                    type="number"
                    v-model="year"
                    min="2000"
                    :max="new Date().getFullYear()"
                    class="border rounded-lg px-3 py-1 text-sm w-24"
                />
                <input
                    v-if="filter === 'daily'"
                    type="number"
                    v-model="month"
                    class="border rounded-lg px-3 py-1 text-sm w-20"
                    min="1"
                    max="12"
                />
            </div>
        </div>

        <!-- Chart -->
        <div class="h-80">
            <VueApexCharts
                type="line"
                height="320"
                :options="chartOptions"
                :series="series"
            />
        </div>
    </div>
</template>
