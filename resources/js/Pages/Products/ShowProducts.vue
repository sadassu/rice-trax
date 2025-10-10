<script setup>
import { ref, watch } from "vue";
import SideBar from "../../Layouts/SideBar.vue";
import CreateProduct from "./CreateProduct.vue";
import UpdateProduct from "./UpdateProduct.vue";
import { router } from "@inertiajs/vue3";
import { debounce } from "lodash";
import DeleteProduct from "./DeleteProduct.vue";
import { formatCurrency } from "../../utils/currencyFormat";
import { formatDate } from "../../utils/dateFormat";
import { formatSack } from "../../utils/helper";
import PaginationLinks from "../../Components/PaginationLinks.vue";
import { isSafeRowClick } from "../../utils/eventHelper";

defineOptions({ layout: SideBar });

const props = defineProps({
    products: Object,
    searchTerm: String,
});

const search = ref(props.searchTerm);

function onRowClick(event, id) {
    if (!isSafeRowClick(event)) return;
    router.visit(route("products.show", id));
}

watch(
    search,
    debounce(
        (q) => router.get("/products", { search: q }, { preserveState: true }),
        500
    )
);
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
                            Sack
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
                        @click="onRowClick($event, product.id)"
                        class="hover:bg-gray-50 cursor-pointer transition-colors"
                    >
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center text-sm font-medium"
                                >
                                    {{ product.name.charAt(0).toUpperCase() }}
                                </div>
                                <span
                                    class="font-medium text-gray-900 capitalize"
                                >
                                    {{ product.name }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-900">
                            {{ formatCurrency(product.price_per_kilo) }}
                        </td>
                        <td class="px-6 py-4 text-gray-900">
                            {{ product.batches_sum_kg_remaining ?? 0 }} kg
                        </td>
                        <td class="px-6 py-4 text-gray-900">
                            {{
                                formatSack(product.batches_sum_kg_remaining) ??
                                0
                            }}
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
                            ₱{{ formatCurrency(product.price_per_kilo) }}/kg
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
                        {{ formatDate(product.updated_at) }}
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
        <PaginationLinks
            :links="products.links"
            :from="products.from"
            :to="products.to"
            :total="products.total"
        />
    </div>
</template>
