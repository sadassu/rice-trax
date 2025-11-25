<script setup>
import PaginationLinks from "../../Components/PaginationLinks.vue";
import SideBar from "../../Layouts/SideBar.vue";
import { formatSack } from "../../utils/helper";
import CreateBatch from "./CreateBatch.vue";
import DeleteBatch from "./DeleteBatch.vue";
import UpdateBatch from "./UpdateBatch.vue";
import { Package, Calendar } from "lucide-vue-next";

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
    <div class="p-4 md:p-6 bg-gray-50 min-h-screen">
        <!-- Header Section -->
        <div class="mb-6 md:mb-8">
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4"
            >
                <div>
                    <h1
                        class="text-2xl md:text-3xl font-bold text-gray-900 mb-2"
                    >
                        {{ product.name }} - Batches
                    </h1>
                    <p class="text-sm md:text-base text-gray-600">
                        Manage batches for {{ product.name }}
                    </p>
                </div>
                <CreateBatch :product_id="product.id" />
            </div>
        </div>

        <!-- Desktop Table View (hidden on mobile) -->
        <div
            v-if="productBatches && productBatches.length"
            class="hidden lg:block bg-white rounded-lg shadow-sm overflow-hidden"
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
                                Remaining Sack
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
                                    <Package
                                        class="w-4 h-4 text-gray-400 mr-2"
                                    />
                                    <span
                                        class="text-sm text-gray-900 font-medium"
                                    >
                                        {{ batch.kg_remaining }} kg
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span
                                        class="text-sm text-gray-900 font-medium"
                                    >
                                        {{ formatSack(batch.kg_remaining) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
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

        <!-- Mobile Card View (visible on mobile) -->
        <div
            v-if="productBatches && productBatches.length"
            class="lg:hidden space-y-4"
        >
            <div
                v-for="batch in productBatches"
                :key="batch.id"
                class="bg-white rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow duration-200"
            >
                <!-- Batch Number Header -->
                <div
                    class="flex items-center justify-between mb-4 pb-3 border-b border-gray-200"
                >
                    <div class="flex items-center space-x-2">
                        <Package class="w-5 h-5 text-blue-600" />
                        <h3 class="text-lg font-semibold text-gray-900">
                            {{ batch.batch_number }}
                        </h3>
                    </div>
                    <div class="flex items-center space-x-2">
                        <UpdateBatch :batch="batch" />
                        <DeleteBatch :batch="batch" />
                    </div>
                </div>

                <!-- Batch Details -->
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500"
                            >Remaining Kilogram</span
                        >
                        <span class="text-sm font-semibold text-gray-900">
                            {{ batch.kg_remaining }} kg
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500"
                            >Remaining Sack</span
                        >
                        <span class="text-sm font-semibold text-gray-900">
                            {{ formatSack(batch.kg_remaining) }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-500">Price of Sack</span>
                        <span class="text-sm font-semibold text-gray-900">
                            ₱{{
                                parseFloat(
                                    batch.price_per_sack
                                ).toLocaleString()
                            }}
                        </span>
                    </div>

                    <div
                        class="flex items-center justify-between pt-2 border-t border-gray-100"
                    >
                        <div
                            class="flex items-center space-x-1 text-xs text-gray-500"
                        >
                            <Calendar class="w-3 h-3" />
                            <span>Updated</span>
                        </div>
                        <span class="text-xs text-gray-600">
                            {{ getDate(batch.updated_at) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Mobile Summary Card -->
            <div class="bg-blue-50 rounded-lg p-4 border border-blue-100">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-blue-900"
                        >Total Batches</span
                    >
                    <span class="text-lg font-bold text-blue-900">
                        {{ productBatches.length }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm font-medium text-blue-900"
                        >Total Remaining</span
                    >
                    <span class="text-lg font-bold text-blue-900">
                        {{
                            productBatches.reduce(
                                (total, batch) =>
                                    total + parseFloat(batch.kg_remaining || 0),
                                0
                            )
                        }}
                        kg
                    </span>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div
            v-else
            class="bg-white rounded-lg shadow-sm p-8 md:p-12 text-center"
        >
            <div
                class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4"
            >
                <Package class="w-8 h-8 text-gray-400" />
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">
                No Batches Found
            </h3>
            <p class="text-sm md:text-base text-gray-600 mb-6 max-w-md mx-auto">
                There are no product batches for
                <span class="font-medium">{{ product.name }}</span
                >. Create your first batch to get started.
            </p>
            <CreateBatch :product_id="product.id" />
        </div>

        <PaginationLinks
            :links="productBatches.links"
            :from="productBatches.from"
            :to="productBatches.to"
            :total="productBatches.total"
        />
    </div>
</template>
