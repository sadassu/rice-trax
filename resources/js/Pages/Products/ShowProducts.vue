<script setup>
import { ref, watch } from "vue";
import SideBar from "../../Layouts/SideBar.vue";
import CreateProduct from "./CreateProduct.vue";
import UpdateProduct from "./UpdateProduct.vue";
import DeleteProduct from "./DeleteProduct.vue";
import PaginationLinks from "../../Components/PaginationLinks.vue";
import { router, usePage, Link } from "@inertiajs/vue3";
import { debounce } from "lodash";
import { formatCurrency } from "../../utils/currencyFormat";
import { formatDate } from "../../utils/dateFormat";
import { formatSack } from "../../utils/helper";
import { isSafeRowClick } from "../../utils/eventHelper";
import { PackageSearch } from "lucide-vue-next";

defineOptions({ layout: SideBar });

const page = usePage();

const props = defineProps({
    products: Object,
    searchTerm: String,
});

const search = ref(props.searchTerm);

const createdProductId = ref(null);

// 🔍 Watch for flash.created_at from Laravel
watch(
    () => page.props.flash?.created_product_id,
    (newVal) => {
        if (newVal) {
            createdProductId.value = newVal;

            // Remove highlight after 5 seconds
            setTimeout(() => {
                createdProductId.value = null;
            }, 5000);
        }
    },
    { immediate: true }
);

// 🔄 Debounced search
watch(
    search,
    debounce(
        (q) => router.get("/products", { search: q }, { preserveState: true }),
        500
    )
);

function onRowClick(event, id) {
    if (!isSafeRowClick(event)) return;
    router.visit(route("products.show", id));
}
</script>

<template>
    <Head :title="` | Products`" />

    <div class="p-4 sm:p-6">
        <!-- Mobile Header Section -->
        <div class="mb-6 md:hidden">
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4"
            >
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Products</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Manage your product inventory
                    </p>
                </div>
                <div>
                    <CreateProduct />
                </div>
            </div>

            <!-- Mobile Search Bar -->
            <div class="flex flex-col gap-3">
                <div class="text-sm text-gray-600">
                    <span class="font-medium">{{ products.total }}</span>
                    products
                </div>
                <input
                    type="search"
                    placeholder="Search products..."
                    v-model="search"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                />
            </div>
        </div>

        <!-- Desktop Table -->
        <div
            class="hidden md:block bg-white rounded-lg border border-gray-200 overflow-hidden shadow-sm"
        >
            <!-- Desktop Header inside table -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between gap-4 mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">
                            Products
                        </h1>
                        <p class="text-gray-600 mt-1">
                            Manage your product inventory
                        </p>
                    </div>
                </div>

                <!-- Desktop Search and Actions Bar -->
                <div class="flex items-center justify-between gap-4">
                    <div class="text-sm text-gray-600">
                        <span class="font-medium">{{ products.total }}</span>
                        products
                    </div>
                    <div class="flex items-center gap-3">
                        <input
                            type="search"
                            placeholder="Search products..."
                            v-model="search"
                            class="w-64 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm"
                        />
                        <CreateProduct />
                    </div>
                </div>
            </div>
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
                        :class="{
                            'border-2 border-lime-500 bg-lime-50 transition-all duration-500':
                                product.id === createdProductId,
                        }"
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
                                    class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition-colors"
                                >
                                    <PackageSearch />
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
                :class="{
                    'border-2 border-lime-500 bg-lime-50 transition-all duration-500':
                        product.id === createdProductId,
                }"
            >
                <div class="flex items-center gap-3 mb-3">
                    <div
                        class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center text-sm font-medium"
                    >
                        {{ product.name.charAt(0).toUpperCase() }}
                    </div>
                    <div class="flex-1">
                        <h2 class="font-semibold text-gray-900 capitalize">
                            {{ product.name }}
                        </h2>
                        <p class="text-sm text-gray-500">
                            {{ formatCurrency(product.price_per_kilo) }}/kg
                        </p>
                    </div>
                </div>
                <div
                    class="grid grid-cols-2 gap-2 text-sm text-gray-700 mb-3 bg-gray-50 rounded-lg p-3"
                >
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Stock</p>
                        <p class="font-medium">
                            {{ product.batches_sum_kg_remaining ?? 0 }} kg
                        </p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-0.5">Sacks</p>
                        <p class="font-medium">
                            {{
                                formatSack(product.batches_sum_kg_remaining) ??
                                0
                            }}
                        </p>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <UpdateProduct :product="product" />
                    <DeleteProduct :product="product" />
                    <Link
                        :href="
                            route('product-batches.product.show', product.id)
                        "
                        class="px-3 py-1 text-sm bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md transition-colors"
                    >
                        Batches
                    </Link>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            <PaginationLinks
                :links="products.links"
                :from="products.from"
                :to="products.to"
                :total="products.total"
            />
        </div>
    </div>
</template>
