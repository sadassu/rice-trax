<script setup>
import { ref } from "vue";
import { usePosStore } from "../../stores/pos";
import { useForm } from "@inertiajs/vue3";

const posStore = usePosStore();
const amountPaid = ref(0);

const form = useForm({
    cart: [],
    total: 0,
    amount_paid: 0,
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

// Calculate total
const cartTotal = () => {
    return posStore.cart.reduce((sum, item) => sum + item.total_price, 0);
};

// Calculate change
const calculateChange = () => {
    return Math.max(0, amountPaid.value - cartTotal());
};

// Set amount paid from preset buttons
const setAmountPaid = (amount) => {
    amountPaid.value = amount;
};

// Add to current amount paid
const addToAmountPaid = (amount) => {
    amountPaid.value += amount;
};

// Set exact amount (same as cart total)
const setExactAmount = () => {
    amountPaid.value = cartTotal();
};

const submit = () => {
    form.cart = posStore.cart;
    form.total = cartTotal();
    form.amount_paid = amountPaid.value;

    form.post(route("checkout"), {
        onSuccess: () => {
            posStore.clearCart();
            amountPaid.value = 0;
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
                    class="flex items-center justify-between p-3 border border-gray-200 rounded"
                >
                    <div>
                        <div class="font-medium text-gray-900">
                            {{ item.name }}
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ formatWeight(item.kilograms) }} •
                            {{ formatPrice(item.total_price) }}
                        </div>
                    </div>
                    <button
                        @click="posStore.removeFromCart(item.id)"
                        class="text-red-600 hover:text-red-800 p-1 text-2xl font-bold"
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

                <!-- Total -->
                <div class="border-t border-gray-200 pt-3 mt-4">
                    <div class="flex justify-between items-center mb-4">
                        <span class="font-medium text-gray-900">Total:</span>
                        <span class="font-bold text-lg">{{
                            formatPrice(cartTotal())
                        }}</span>
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
                                Exact Amount ({{ formatPrice(cartTotal()) }})
                            </button>
                        </div>

                        <!-- Add Amount Buttons -->
                        <div class="mb-3">
                            <p class="text-xs text-gray-600 mb-2">
                                Add to current amount:
                            </p>
                            <div class="grid grid-cols-3 gap-2">
                                <button
                                    v-for="amount in [20, 50, 100]"
                                    :key="`add-${amount}`"
                                    @click="addToAmountPaid(amount)"
                                    class="py-1 px-2 bg-blue-100 border border-blue-200 rounded text-xs font-medium hover:bg-blue-200 transition-colors"
                                >
                                    +₱{{ amount }}
                                </button>
                            </div>
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
                                    'text-green-600': amountPaid >= cartTotal(),
                                    'text-red-600': amountPaid < cartTotal(),
                                }"
                            >
                                {{ formatPrice(calculateChange()) }}
                            </span>
                        </div>

                        <!-- Insufficient Payment Warning -->
                        <div
                            v-if="amountPaid > 0 && amountPaid < cartTotal()"
                            class="mt-2 p-2 bg-red-50 border border-red-200 rounded"
                        >
                            <p class="text-xs text-red-700">
                                Insufficient payment: Need
                                {{ formatPrice(cartTotal() - amountPaid) }} more
                            </p>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="space-y-2">
                        <button
                            :disabled="amountPaid < cartTotal()"
                            :class="{
                                'bg-blue-600 hover:bg-blue-700 text-white':
                                    amountPaid >= cartTotal(),
                                'bg-gray-300 text-gray-500 cursor-not-allowed':
                                    amountPaid < cartTotal(),
                            }"
                            class="w-full py-2 px-4 rounded transition-colors"
                            @click="submit"
                        >
                            Checkout
                        </button>
                        <button
                            @click="
                                posStore.clearCart();
                                amountPaid = 0;
                            "
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
