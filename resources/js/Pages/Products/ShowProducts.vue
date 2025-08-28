<script setup>
import { ref, watch } from "vue";
import SideBar from "../../Layouts/SideBar.vue";
import CreateProduct from "./CreateProduct.vue";
import UpdateProduct from "./UpdateProduct.vue";
import { router } from "@inertiajs/vue3";
import { debounce } from "lodash";
import DeleteProduct from "./DeleteProduct.vue";

defineOptions({ layout: SideBar });

const props = defineProps({
    products: Object,
    searchTerm: String,
});

const search = ref(props.searchTerm);

watch(
    search,
    debounce(
        (q) => router.get("/products", { search: q }, { preserveState: true }),
        500
    )
);

// Formate the date from database
const getDate = (date) =>
    new Date(date).toLocaleDateString("en-us", {
        year: "numeric",
        month: "long",
        day: "numeric",
    });

// Format price to always show 2 decimal places
const formatPrice = (price) => {
    return parseFloat(price).toFixed(2);
};
</script>

<template>
    <Head :title="` | Products`" />

    <div class="p-6">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-center flex-wrap gap-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Products</h1>
                <p class="text-gray-600">Manage your product inventory</p>
            </div>
            <CreateProduct />
        </div>

        <!-- Search Bar -->
        <div class="mb-6 flex justify-between items-center flex-wrap gap-3">
            <div class="text-sm text-gray-600">
                {{ products.total }} products
            </div>
            <input
                type="search"
                placeholder="Search products..."
                v-model="search"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full sm:w-auto"
            />
        </div>

        <!-- Desktop Table -->
        <div
            class="hidden md:block bg-white rounded-lg border border-gray-200 overflow-hidden"
        >
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th
                            class="px-6 py-3 text-left text-sm font-medium text-gray-900"
                        >
                            Product Name
                        </th>
                        <th
                            class="px-6 py-3 text-left text-sm font-medium text-gray-900"
                        >
                            Price/Kg
                        </th>
                        <th
                            class="px-6 py-3 text-left text-sm font-medium text-gray-900"
                        >
                            Stock
                        </th>
                        <th
                            class="px-6 py-3 text-left text-sm font-medium text-gray-900"
                        >
                            Last Updated
                        </th>
                        <th
                            class="px-6 py-3 text-center text-sm font-medium text-gray-900"
                        >
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr
                        v-for="product in products.data"
                        :key="product.id"
                        class="hover:bg-gray-50"
                    >
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center text-sm font-medium"
                                >
                                    {{ product.name.charAt(0).toUpperCase() }}
                                </div>
                                <span class="font-medium text-gray-900">
                                    {{ product.name }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-900">
                            ₱{{ formatPrice(product.price_per_kilo) }}
                        </td>
                        <td class="px-6 py-4 text-gray-900">
                            {{ product.batches_sum_kg_remaining ?? 0 }} kg
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ getDate(product.updated_at) }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <UpdateProduct :product="product" />
                                <DeleteProduct :product="product" />
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
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards -->
        <div class="space-y-4 md:hidden">
            <div
                v-for="product in products.data"
                :key="product.id"
                class="bg-white rounded-lg border border-gray-200 p-4 shadow-sm"
            >
                <div class="flex items-center gap-3 mb-3">
                    <div
                        class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center text-sm font-medium"
                    >
                        {{ product.name.charAt(0).toUpperCase() }}
                    </div>
                    <div>
                        <h2 class="font-semibold text-gray-900">
                            {{ product.name }}
                        </h2>
                        <p class="text-sm text-gray-500">
                            ₱{{ formatPrice(product.price_per_kilo) }}/kg
                        </p>
                    </div>
                </div>
                <div class="text-sm text-gray-700 mb-2">
                    <p>
                        <span class="font-medium">Stock:</span>
                        {{ product.batches_sum_kg_remaining ?? 0 }} kg
                    </p>
                    <p>
                        <span class="font-medium">Updated:</span>
                        {{ getDate(product.updated_at) }}
                    </p>
                </div>
                <div class="flex flex-wrap gap-2 mt-3">
                    <UpdateProduct :product="product" />
                    <DeleteProduct :product="product" />
                    <Link
                        :href="
                            route('product-batches.product.show', product.id)
                        "
                        class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md"
                    >
                        Batches
                    </Link>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div
            class="bg-gray-50 px-6 py-3 border-t border-gray-200 mt-4 rounded-lg"
        >
            <div class="flex items-center justify-between flex-wrap gap-3">
                <div class="text-sm text-gray-700">
                    {{ products.from }}-{{ products.to }} of
                    {{ products.total }} results
                </div>
                <div class="flex gap-1 flex-wrap">
                    <Link
                        v-for="link in products.links"
                        :key="link.label"
                        :href="link.url || '#'"
                        v-html="link.label"
                        preserve-scroll
                        class="px-3 py-1 text-sm rounded-md"
                        :class="{
                            'text-gray-400 cursor-not-allowed': !link.url,
                            'bg-gray-900 text-white': link.active,
                            'text-gray-700 hover:bg-gray-200':
                                link.url && !link.active,
                        }"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
