<script setup>
import SideBar from "../../Layouts/SideBar.vue";
import { ref, computed, onMounted } from "vue";
import VueApexCharts from "vue3-apexcharts";
import { formatCurrency } from "../../utils/currencyFormat";
import { formatDate } from "../../utils/dateFormat";
import { formatSack } from "../../utils/helper";
import { Link } from "@inertiajs/vue3";

defineOptions({ layout: SideBar });

const props = defineProps({
    product: Object,
    dailySales: Object,
    monthlySales: Object,
    totalKgRemaining: Number,
    totalInventoryValue: Number,
    salesTable: Array,
});

// Chart options for daily sales
const dailyChartOptions = ref({
    chart: {
        type: "line",
        height: 350,
        toolbar: {
            show: true,
        },
        animations: {
            enabled: true,
            easing: "easeinout",
            speed: 800,
        },
    },
    title: {
        text: "Daily Sales Trend",
        align: "left",
        style: {
            fontSize: "18px",
            fontWeight: "bold",
            color: "#374151",
        },
    },
    xaxis: {
        type: "datetime",
        title: {
            text: "Date",
            style: {
                color: "#6B7280",
            },
        },
        labels: {
            style: {
                colors: "#6B7280",
            },
        },
    },
    yaxis: {
        title: {
            text: "Sales Amount",
            style: {
                color: "#6B7280",
            },
        },
        labels: {
            formatter: function (value) {
                return formatCurrency(value);
            },
            style: {
                colors: "#6B7280",
            },
        },
    },
    tooltip: {
        y: {
            formatter: function (value) {
                return formatCurrency(value);
            },
        },
    },
    stroke: {
        curve: "smooth",
        width: 3,
    },
    colors: ["#3B82F6"],
    grid: {
        borderColor: "#E5E7EB",
        strokeDashArray: 4,
    },
    tooltip: {
        y: {
            formatter: function (value) {
                return formatCurrency(value);
            },
        },
    },
});

// Chart options for monthly sales
const monthlyChartOptions = ref({
    chart: {
        type: "bar",
        height: 350,
        toolbar: {
            show: true,
        },
        animations: {
            enabled: true,
            easing: "easeinout",
            speed: 800,
        },
    },
    title: {
        text: "Monthly Sales Overview",
        align: "left",
        style: {
            fontSize: "18px",
            fontWeight: "bold",
            color: "#374151",
        },
    },
    xaxis: {
        categories: [],
        title: {
            text: "Month",
            style: {
                color: "#6B7280",
            },
        },
        labels: {
            style: {
                colors: "#6B7280",
            },
        },
    },
    yaxis: {
        title: {
            text: "Sales Amount",
            style: {
                color: "#6B7280",
            },
        },
        labels: {
            formatter: function (value) {
                return formatCurrency(value);
            },
            style: {
                colors: "#6B7280",
            },
        },
    },
    tooltip: {
        y: {
            formatter: function (value) {
                return formatCurrency(value);
            },
        },
    },
    plotOptions: {
        bar: {
            borderRadius: 4,
            columnWidth: "60%",
        },
    },
    colors: ["#10B981"],
    grid: {
        borderColor: "#E5E7EB",
        strokeDashArray: 4,
    },
    tooltip: {
        y: {
            formatter: function (value) {
                return formatCurrency(value);
            },
        },
    },
});

// Computed properties for chart data
const dailySalesData = computed(() => {
    if (!props.dailySales) return [];

    const data = Object.entries(props.dailySales).map(([date, amount]) => ({
        x: new Date(date).getTime(),
        y: parseFloat(amount),
    }));

    return [
        {
            name: "Daily Sales",
            data: data.sort((a, b) => a.x - b.x),
        },
    ];
});

const monthlySalesData = computed(() => {
    if (!props.monthlySales) return [];

    const categories = Object.keys(props.monthlySales).sort();
    const data = categories.map((month) =>
        parseFloat(props.monthlySales[month])
    );

    // Update chart categories
    monthlyChartOptions.value.xaxis.categories = categories.map((month) => {
        const [year, monthNum] = month.split("-");
        return new Date(year, monthNum - 1).toLocaleDateString("en-US", {
            year: "numeric",
            month: "short",
        });
    });

    return [
        {
            name: "Monthly Sales",
            data: data,
        },
    ];
});

// Calculate summary statistics
const totalSales = computed(() => {
    if (!props.dailySales) return 0;
    return Object.values(props.dailySales).reduce(
        (sum, amount) => sum + parseFloat(amount),
        0
    );
});

const averageDailySales = computed(() => {
    if (!props.dailySales || Object.keys(props.dailySales).length === 0)
        return 0;
    return totalSales.value / Object.keys(props.dailySales).length;
});

const bestSellingDay = computed(() => {
    if (!props.dailySales) return null;
    const entries = Object.entries(props.dailySales);
    if (entries.length === 0) return null;

    const best = entries.reduce((max, current) =>
        parseFloat(current[1]) > parseFloat(max[1]) ? current : max
    );

    return {
        date: new Date(best[0]).toLocaleDateString("en-US", {
            weekday: "long",
            year: "numeric",
            month: "long",
            day: "numeric",
        }),
        amount: parseFloat(best[1]),
    };
});
</script>

<template>
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div
                class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8"
            >
                <div
                    class="flex flex-col lg:flex-row lg:items-center lg:justify-between"
                >
                    <div class="flex-1">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">
                            {{ product.name }}
                        </h1>
                        <p
                            class="text-gray-600 mb-4"
                            v-if="product.description"
                        >
                            {{ product.description }}
                        </p>
                        <div class="flex flex-wrap gap-4 text-sm">
                            <span
                                class="bg-green-100 text-green-800 px-3 py-1 rounded-full"
                            >
                                Price:
                                {{ formatCurrency(product.price_per_kilo) }}
                            </span>
                            <span
                                class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full"
                            >
                                Stock:
                                {{ totalKgRemaining || 0 }} Kg /
                                {{ formatSack(totalKgRemaining) }} sack
                            </span>
                            <Link
                                :href="
                                    route(
                                        'product-batches.product.show',
                                        product.id
                                    )
                                "
                                class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md"
                            >
                                Batches
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sales Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 p-6"
                >
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-blue-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">
                                Total Sales
                            </p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ formatCurrency(totalSales) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 p-6"
                >
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-blue-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">
                                Total Inventory Value
                            </p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ formatCurrency(totalInventoryValue) }}
                            </p>
                        </div>
                    </div>
                </div>
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 p-6"
                >
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-green-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">
                                Average Daily Sales
                            </p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ formatCurrency(averageDailySales) }}
                            </p>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 p-6"
                >
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div
                                class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center"
                            >
                                <svg
                                    class="w-5 h-5 text-yellow-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">
                                Best Day
                            </p>
                            <p
                                class="text-lg font-bold text-gray-900"
                                v-if="bestSellingDay"
                            >
                                {{ formatCurrency(bestSellingDay.amount) }}
                            </p>
                            <p
                                class="text-xs text-gray-500"
                                v-if="bestSellingDay"
                            >
                                {{ bestSellingDay.date }}
                            </p>
                            <p class="text-lg font-bold text-gray-400" v-else>
                                No data
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                <!-- Daily Sales Chart -->
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 p-6"
                >
                    <div v-if="dailySalesData.length > 0">
                        <VueApexCharts
                            type="line"
                            height="350"
                            :options="dailyChartOptions"
                            :series="dailySalesData"
                        />
                    </div>
                    <div v-else class="flex items-center justify-center h-96">
                        <div class="text-center">
                            <svg
                                class="w-16 h-16 text-gray-300 mx-auto mb-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                ></path>
                            </svg>
                            <p class="text-gray-500">
                                No daily sales data available
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Monthly Sales Chart -->
                <div
                    class="bg-white rounded-lg shadow-sm border border-gray-200 p-6"
                >
                    <div v-if="monthlySalesData.length > 0">
                        <VueApexCharts
                            type="bar"
                            height="350"
                            :options="monthlyChartOptions"
                            :series="monthlySalesData"
                        />
                    </div>
                    <div v-else class="flex items-center justify-center h-96">
                        <div class="text-center">
                            <svg
                                class="w-16 h-16 text-gray-300 mx-auto mb-4"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                ></path>
                            </svg>
                            <p class="text-gray-500">
                                No monthly sales data available
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Info Section -->
            <div
                class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6"
            >
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Product Details
                </h3>
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6"
                >
                    <div>
                        <p class="text-sm text-gray-500">Created Date</p>
                        <p class="font-medium text-gray-900">
                            {{
                                product.created_at
                                    ? formatDate(product.created_at)
                                    : "N/A"
                            }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Last Updated</p>
                        <p class="font-medium text-gray-900">
                            {{
                                product.updated_at
                                    ? formatDate(product.updated_at)
                                    : "N/A"
                            }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Sales History Table -->
            <div
                class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6"
            >
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    Sales History
                </h3>

                <div
                    v-if="salesTable && salesTable.length > 0"
                    class="overflow-x-auto"
                >
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    scope="col"
                                    class="px-4 py-3 text-left font-semibold text-gray-600"
                                >
                                    Date
                                </th>
                                <th
                                    scope="col"
                                    class="px-4 py-3 text-left font-semibold text-gray-600"
                                >
                                    Kilograms Sold
                                </th>
                                <th
                                    scope="col"
                                    class="px-4 py-3 text-left font-semibold text-gray-600"
                                >
                                    Price per Kilo
                                </th>
                                <th
                                    scope="col"
                                    class="px-4 py-3 text-left font-semibold text-gray-600"
                                >
                                    Total Price
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr
                                v-for="sale in salesTable"
                                :key="sale.id"
                                class="hover:bg-gray-50 transition"
                            >
                                <td class="px-4 py-2 text-gray-800">
                                    {{ formatDate(sale.sale_date) }}
                                </td>
                                <td class="px-4 py-2 text-gray-800">
                                    {{ sale.kilograms }} kg
                                </td>
                                <td class="px-4 py-2 text-gray-800">
                                    {{ formatCurrency(sale.price_per_kilo) }}
                                </td>
                                <td
                                    class="px-4 py-2 font-semibold text-gray-900"
                                >
                                    {{ formatCurrency(sale.total_price) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div v-else class="text-center py-12 text-gray-500">
                    No sales data available for this product.
                </div>
            </div>
        </div>
    </div>
</template>
