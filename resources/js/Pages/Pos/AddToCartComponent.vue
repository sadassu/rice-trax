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
        <div class="flex gap-2 items-center">
            <input
                type="number"
                v-model.number="kilograms"
                min="0.1"
                step="0.1"
                placeholder="1.0"
                class="w-32 px-5 py-1 text-center border border-gray-300 rounded text-sm no-spin"
            />
            <button
                @click="addToCartButton"
                :disabled="
                    !kilograms ||
                    kilograms <= 0 ||
                    kilograms > product.total_kg_remaining
                "
                class="px-3 py-1 text-sm text-white bg-blue-600 rounded disabled:bg-gray-400 disabled:cursor-not-allowed hover:bg-blue-700 disabled:hover:bg-gray-400"
            >
                Add to Cart
            </button>
        </div>

        <!-- Quick select buttons -->
        <div class="flex gap-2">
            <button
                @click="setKilograms(0)"
                class="px-2 py-1 text-xs bg-gray-200 rounded hover:bg-gray-300"
            >
                0
            </button>
            <button
                @click="setKilograms(0.25)"
                class="px-2 py-1 text-xs bg-gray-200 rounded hover:bg-gray-300"
            >
                1/4
            </button>
            <button
                @click="setKilograms(0.5)"
                class="px-2 py-1 text-xs bg-gray-200 rounded hover:bg-gray-300"
            >
                1/2
            </button>
            <button
                @click="setKilograms(0.75)"
                class="px-2 py-1 text-xs bg-gray-200 rounded hover:bg-gray-300"
            >
                3/4
            </button>
            <button
                @click="setKilograms(1)"
                class="px-2 py-1 text-xs bg-gray-200 rounded hover:bg-gray-300"
            >
                1
            </button>
        </div>
    </div>
</template>
