<script setup>
import { ref } from "vue";
import { usePosStore } from "../../stores/pos";
import { useForm } from "@inertiajs/vue3";

const posStore = usePosStore();
const amountPaid = ref(0);
const priceOverride = ref(null);
const isPriceOverrideMode = ref(false);

const form = useForm({
    cart: [],
    total: 0,
    amount_paid: 0,
    price_override: false,
});

// Format price for display
const formatPrice = (price) => {
    return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "PHP",
    }).format(price);
};

// Format weight for display
const formatWeight = (kg) => {
    return `${kg}kg`;
};

// Calculate cart total (sum of all items)
const cartItemsTotal = () => {
    return posStore.cart.reduce((sum, item) => sum + item.total_price, 0);
};

// Get final total (either cart total or price override)
const getFinalTotal = () => {
    if (
        isPriceOverrideMode.value &&
        priceOverride.value !== null &&
        priceOverride.value >= 0
    ) {
        return priceOverride.value;
    }
    return cartItemsTotal();
};

// Calculate change
const calculateChange = () => {
    return Math.max(0, amountPaid.value - getFinalTotal());
};

// Set amount paid from preset buttons
const setAmountPaid = (amount) => {
    amountPaid.value = amount;
};

// Add to current amount paid
const addToAmountPaid = (amount) => {
    amountPaid.value += amount;
};

// Set exact amount (same as final total)
const setExactAmount = () => {
    amountPaid.value = getFinalTotal();
};

// Toggle price override mode
const togglePriceOverrideMode = () => {
    isPriceOverrideMode.value = !isPriceOverrideMode.value;
    if (!isPriceOverrideMode.value) {
        priceOverride.value = null;
    } else {
        // Set default to current cart total when enabling override mode
        priceOverride.value = cartItemsTotal();
    }
};

// Reset everything when clearing cart
const clearAll = () => {
    posStore.clearCart();
    amountPaid.value = 0;
    priceOverride.value = null;
    isPriceOverrideMode.value = false;
};

const submit = () => {
    form.cart = posStore.cart;
    form.total = getFinalTotal();
    form.amount_paid = amountPaid.value;
    form.price_override = isPriceOverrideMode.value;

    form.post(route("checkout"), {
        onSuccess: () => {
            clearAll();
        },
    });
};
</script>

<template>
    <div class="m-3 bg-white border border-gray-300 rounded-lg">
        <!-- Header -->
        <div class="border-b border-gray-200 p-4">
            <h2 class="text-lg font-medium text-gray-900">
                Cart ({{ posStore.cart.length }})
            </h2>
        </div>

        <!-- Content -->
        <div class="p-4">
            <!-- Empty State -->
            <div v-if="posStore.cart.length === 0" class="text-center py-8">
                <p class="text-gray-500">Your cart is empty</p>
            </div>

            <!-- Items -->
            <div v-else class="space-y-3">
                <div
                    v-for="item in posStore.cart"
                    :key="item.id"
                    class="flex items-center justify-between p-3 border border-green-400 bg-green-100 rounded"
                >
                    <div>
                        <div class="font-bold uppercase text-lg text-gray-900">
                            {{ item.name }}
                        </div>
                        <div class="text-sm text-slate-800">
                            {{ formatWeight(item.kilograms) }} •
                            {{ formatPrice(item.total_price) }}
                        </div>
                    </div>
                    <button
                        @click="posStore.removeFromCart(item.id)"
                        class="text-red-600 cursor-pointer hover:text-red-800 p-1 text-2xl font-bold"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="size-6"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M6 18 18 6M6 6l12 12"
                            />
                        </svg>
                    </button>
                </div>

                <!-- Total Section -->
                <div class="border-t border-gray-200 pt-3 mt-4">
                    <!-- Price Override Section -->
                    <div
                        class="mb-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg"
                    >
                        <div class="flex items-center justify-between mb-3">
                            <label
                                class="block text-sm font-medium text-gray-700"
                            >
                                Price Override
                            </label>
                            <button
                                @click="togglePriceOverrideMode"
                                :class="{
                                    'bg-yellow-500 text-white':
                                        isPriceOverrideMode,
                                    'bg-gray-200 text-gray-700':
                                        !isPriceOverrideMode,
                                }"
                                class="px-3 py-1 rounded text-xs font-medium transition-colors"
                            >
                                {{ isPriceOverrideMode ? "ON" : "OFF" }}
                            </button>
                        </div>

                        <div v-if="isPriceOverrideMode" class="space-y-2">
                            <input
                                v-model.number="priceOverride"
                                type="number"
                                step="0.01"
                                min="0"
                                class="w-full p-2 border border-yellow-300 rounded text-center text-lg font-medium"
                                placeholder="Enter custom total"
                            />
                            <p class="text-xs text-gray-600">
                                Original cart total:
                                {{ formatPrice(cartItemsTotal()) }}
                            </p>
                        </div>
                    </div>

                    <!-- Total Display -->
                    <div class="flex justify-between items-center mb-4">
                        <span class="font-medium text-gray-900">
                            Total:
                            <span
                                v-if="isPriceOverrideMode"
                                class="text-xs text-yellow-600"
                                >(Price Override)</span
                            >
                        </span>
                        <span
                            class="font-bold text-lg"
                            :class="{
                                'text-yellow-600': isPriceOverrideMode,
                                'text-gray-900': !isPriceOverrideMode,
                            }"
                        >
                            {{ formatPrice(getFinalTotal()) }}
                        </span>
                    </div>

                    <!-- Amount Paid Section -->
                    <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                        <label
                            class="block text-sm font-medium text-gray-700 mb-2"
                        >
                            Amount Paid
                        </label>

                        <!-- Custom Input -->
                        <input
                            v-model.number="amountPaid"
                            type="number"
                            step="0.01"
                            min="0"
                            class="w-full p-2 border border-gray-300 rounded mb-3 text-center text-lg font-medium"
                            placeholder="0.00"
                        />

                        <!-- Preset Amount Buttons -->
                        <div class="grid grid-cols-3 gap-2 mb-3">
                            <button
                                v-for="amount in [50, 100, 200, 500, 1000]"
                                :key="amount"
                                @click="setAmountPaid(amount)"
                                class="py-2 px-3 bg-white border border-gray-300 rounded text-sm font-medium hover:bg-gray-100 transition-colors"
                            >
                                ₱{{ amount }}
                            </button>
                        </div>

                        <!-- Exact Amount Button -->
                        <div class="mb-3">
                            <button
                                @click="setExactAmount"
                                class="w-full py-2 px-3 bg-green-100 border border-green-300 rounded text-sm font-medium hover:bg-green-200 transition-colors text-green-800"
                            >
                                Exact Amount ({{
                                    formatPrice(getFinalTotal())
                                }})
                            </button>
                        </div>

                        <!-- Change Display -->
                        <div
                            v-if="amountPaid > 0"
                            class="flex justify-between items-center p-2 bg-white rounded border"
                        >
                            <span class="text-sm font-medium text-gray-700"
                                >Change:</span
                            >
                            <span
                                class="font-bold text-lg"
                                :class="{
                                    'text-green-600':
                                        amountPaid >= getFinalTotal(),
                                    'text-red-600':
                                        amountPaid < getFinalTotal(),
                                }"
                            >
                                {{ formatPrice(calculateChange()) }}
                            </span>
                        </div>

                        <!-- Insufficient Payment Warning -->
                        <div
                            v-if="
                                amountPaid > 0 && amountPaid < getFinalTotal()
                            "
                            class="mt-2 p-2 bg-red-50 border border-red-200 rounded"
                        >
                            <p class="text-xs text-red-700">
                                Insufficient payment: Need
                                {{ formatPrice(getFinalTotal() - amountPaid) }}
                                more
                            </p>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="space-y-2">
                        <button
                            :disabled="amountPaid < getFinalTotal()"
                            :class="{
                                'bg-blue-600 hover:bg-blue-700 text-white':
                                    amountPaid >= getFinalTotal(),
                                'bg-gray-300 text-gray-500 cursor-not-allowed':
                                    amountPaid < getFinalTotal(),
                            }"
                            class="w-full py-2 px-4 rounded transition-colors"
                            @click="submit"
                        >
                            Checkout
                            <span v-if="isPriceOverrideMode" class="text-xs"
                                >(Price Override)</span
                            >
                        </button>
                        <button
                            @click="clearAll"
                            class="w-full py-2 px-4 border border-gray-300 text-gray-700 rounded hover:bg-gray-50"
                        >
                            Clear Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
