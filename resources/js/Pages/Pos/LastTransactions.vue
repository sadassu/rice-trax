<script setup>
import { router } from "@inertiajs/vue3";

defineProps({
    sales: {
        type: Array,
        required: true,
    },
});

// Dummy download function (replace with actual receipt download logic)
const downloadReceipt = (saleId) => {
    router.get(route("receipt", saleId));
};
</script>

<template>
    <div class="m-3 bg-white border border-gray-300 rounded-lg">
        <table
            class="w-full border-collapse border border-gray-200 text-sm text-gray-700"
        >
            <thead>
                <tr>
                    <th class="border border-gray-200 px-3 py-2 text-left">
                        Date
                    </th>
                    <th class="border border-gray-200 px-3 py-2 text-left">
                        Total
                    </th>
                    <th class="border border-gray-200 px-3 py-2 text-center">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="sale in sales"
                    :key="sale.id"
                    class="hover:bg-gray-50"
                >
                    <td class="border border-gray-200 px-3 py-2">
                        {{ new Date(sale.sale_date).toLocaleDateString() }}
                    </td>
                    <td class="border border-gray-200 px-3 py-2">
                        ₱{{ Number(sale.total_price).toFixed(2) }}
                    </td>

                    <td class="border border-gray-200 px-3 py-2 text-center">
                        <button
                            @click="downloadReceipt(sale.id)"
                            class="p-2 rounded-lg hover:bg-gray-200"
                            title="Download Receipt"
                        >
                            <!-- SVG Download Icon -->
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                                class="w-5 h-5 text-gray-600"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5m0 0l5-5m-5 5V4"
                                />
                            </svg>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
