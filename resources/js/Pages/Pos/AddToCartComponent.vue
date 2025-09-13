<script setup>
import { ref } from "vue";
import { usePosStore } from "../../stores/pos";

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const posStore = usePosStore();
const kilograms = ref(1);

const addToCartButton = () => {
    const kg = parseFloat(kilograms.value) || 0;
    if (kg > 0 && kg <= props.product.total_kg_remaining) {
        posStore.addToCart(props.product, kg);
        // Reset to 1 after adding
        kilograms.value = 1;
    } else if (kg <= 0) {
        console.log("Please enter a valid amount");
    } else {
        console.log("Amount exceeds available stock");
    }
};

// Quick set function
const setKilograms = (value) => {
    kilograms.value = value;
};
</script>

<template>
    <div class="flex flex-col gap-2 items-center justify-center mt-4">
        <!-- Input and Add to Cart -->
        <div class="flex gap-3 items-center">
            <!-- Input with KG suffix -->
            <div class="flex gap-2 items-center">
                <input
                    type="number"
                    v-model.number="kilograms"
                    min="0.1"
                    step="0.1"
                    placeholder="1.0"
                    class="w-40 px-6 py-2 text-lg text-center border border-gray-300 rounded-lg no-spin"
                />
                <span class="text-gray-600 text-lg font-medium">kg</span>
            </div>

            <!-- Add to Cart button -->
            <button
                @click="addToCartButton"
                :disabled="
                    !kilograms ||
                    kilograms <= 0 ||
                    kilograms > product.total_kg_remaining
                "
                class="px-6 py-3 text-lg font-semibold text-white bg-blue-600 rounded-lg disabled:bg-gray-400 disabled:cursor-not-allowed hover:bg-blue-700 disabled:hover:bg-gray-400 flex items-center gap-2"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="2"
                    stroke="currentColor"
                    class="w-6 h-6"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"
                    />
                </svg>
            </button>
        </div>

        <!-- Quick select buttons -->
        <div class="flex gap-3">
            <button
                @click="setKilograms(0)"
                class="px-4 py-2 text-base bg-gray-200 rounded-lg hover:bg-gray-300"
            >
                0
            </button>
            <button
                @click="setKilograms(0.25)"
                class="px-4 py-2 text-base bg-gray-200 rounded-lg hover:bg-gray-300"
            >
                1/4
            </button>
            <button
                @click="setKilograms(0.5)"
                class="px-4 py-2 text-base bg-gray-200 rounded-lg hover:bg-gray-300"
            >
                1/2
            </button>
            <button
                @click="setKilograms(0.75)"
                class="px-4 py-2 text-base bg-gray-200 rounded-lg hover:bg-gray-300"
            >
                3/4
            </button>
            <button
                @click="setKilograms(1)"
                class="px-4 py-2 text-base bg-gray-200 rounded-lg hover:bg-gray-300"
            >
                1
            </button>
        </div>
    </div>
</template>
