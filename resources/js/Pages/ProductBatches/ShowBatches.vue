<script setup>
import SideBar from "../../Layouts/SideBar.vue";
import CreateBatch from "./CreateBatch.vue";
import DeleteBatch from "./DeleteBatch.vue";
import UpdateBatch from "./UpdateBatch.vue";

defineOptions({ layout: SideBar });

defineProps({
    product: Object,
    productBatches: Array,
});

const getDate = (date) =>
    new Date(date).toLocaleDateString("en-us", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });
</script>

<template>
    <div class="p-6 bg-gray-50 min-h-screen">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        {{ product.name }} - Batches
                    </h1>
                    <p class="text-gray-600">
                        Manage batches for {{ product.name }}
                    </p>
                </div>
                <CreateBatch :product_id="product.id" />
            </div>
        </div>

        <!-- Batches Table -->
        <div
            v-if="productBatches && productBatches.length"
            class="bg-white rounded-lg shadow-sm overflow-hidden"
        >
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th
                                scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Batch Number
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Remaining Kilogram
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Price of Sack
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Updated At
                            </th>
                            <th
                                scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider"
                            >
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <tr
                            v-for="batch in productBatches"
                            :key="batch.id"
                            class="hover:bg-gray-50 transition-colors duration-200"
                        >
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div
                                        class="text-sm font-medium text-gray-900"
                                    >
                                        {{ batch.batch_number }}
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <svg
                                        class="w-4 h-4 text-gray-400 mr-2"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M8 5a2 2 0 012-2h4a2 2 0 012 2v1H8V5z"
                                        />
                                    </svg>
                                    <span
                                        class="text-sm text-gray-900 font-medium"
                                    >
                                        {{ batch.kg_remaining }} kg
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <svg
                                        class="w-4 h-4 text-gray-400 mr-2"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"
                                        />
                                    </svg>
                                    <span
                                        class="text-sm text-gray-900 font-medium"
                                    >
                                        ₱{{
                                            parseFloat(
                                                batch.price_per_sack
                                            ).toLocaleString()
                                        }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <svg
                                        class="w-4 h-4 text-gray-400 mr-2"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4l6 6-6 6"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M21 12H3"
                                        />
                                    </svg>
                                    <span class="text-sm text-gray-500">
                                        {{ getDate(batch.updated_at) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div
                                    class="flex items-center justify-end space-x-2"
                                >
                                    <UpdateBatch :batch="batch" />
                                    <DeleteBatch :batch="batch" />
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Table Footer -->
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-700">
                        Total batches:
                        <span class="font-medium">{{
                            productBatches.length
                        }}</span>
                    </p>
                    <div
                        class="flex items-center space-x-4 text-sm text-gray-500"
                    >
                        <div class="flex items-center">
                            <span>Total remaining: </span>
                            <span class="ml-1 font-medium text-gray-900">
                                {{
                                    productBatches.reduce(
                                        (total, batch) =>
                                            total +
                                            parseFloat(batch.kg_remaining || 0),
                                        0
                                    )
                                }}
                                kg
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else class="bg-white rounded-lg shadow-sm p-12 text-center">
            <div
                class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4"
            >
                <svg
                    class="w-8 h-8 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2M4 13h2m0 0V9a2 2 0 012-2h2m0 0V6a2 2 0 012-2h2.01"
                    />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">
                No Batches Found
            </h3>
            <p class="text-gray-600 mb-6 max-w-md mx-auto">
                There are no product batches for
                <span class="font-medium">{{ product.name }}</span
                >. Create your first batch to get started.
            </p>
            <CreateBatch :product_id="product.id" />
        </div>
    </div>
</template>
