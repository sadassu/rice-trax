<script setup>
import { ref, watch, computed } from "vue";
import { usePosStore } from "../../stores/pos";

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const posStore = usePosStore();
const kilograms = ref(1);
const sacks = ref(0);
const inputMode = ref("kg"); // 'kg' or 'sacks'
const showFeedback = ref("");
const feedbackTimeout = ref(null);

// Computed property for validation
const isValidAmount = computed(() => {
    const kg = parseFloat(kilograms.value) || 0;
    return kg > 0 && kg <= props.product.total_kg_remaining;
});

const remainingStock = computed(() => {
    return props.product.total_kg_remaining || 0;
});

// Show feedback message
const showFeedbackMessage = (message, type = "info") => {
    showFeedback.value = { message, type };
    if (feedbackTimeout.value) clearTimeout(feedbackTimeout.value);
    feedbackTimeout.value = setTimeout(() => {
        showFeedback.value = "";
    }, 3000);
};

// Watch sacks input and convert to kilograms
watch(sacks, (newVal) => {
    if (inputMode.value === "sacks") {
        const numSacks = parseFloat(newVal) || 0;
        kilograms.value = numSacks * 25;
    }
});

// Watch kilograms input and update sacks if in kg mode
watch(kilograms, (newVal) => {
    if (inputMode.value === "kg") {
        const kg = parseFloat(newVal) || 0;
        if (kg % 25 === 0) {
            sacks.value = kg / 25;
        } else {
            sacks.value = Math.floor(kg / 25); // Show partial sacks
        }
    }
});

// Switch input mode
const switchMode = (mode) => {
    inputMode.value = mode;
    if (mode === "sacks") {
        // Round to nearest 25kg when switching to sacks
        const currentKg = parseFloat(kilograms.value) || 0;
        const nearestSacks = Math.round(currentKg / 25);
        sacks.value = nearestSacks;
        kilograms.value = nearestSacks * 25;
    }
};

const addToCartButton = () => {
    const kg = parseFloat(kilograms.value) || 0;

    if (kg <= 0) {
        showFeedbackMessage(
            "Please enter a valid amount greater than 0",
            "error"
        );
        return;
    }

    if (kg > remainingStock.value) {
        showFeedbackMessage(
            `Amount exceeds available stock (${remainingStock.value} kg remaining)`,
            "error"
        );
        return;
    }

    posStore.addToCart(props.product, kg);
    showFeedbackMessage(`Added ${kg} kg to cart successfully!`, "success");

    // Reset after adding
    kilograms.value = 1;
    sacks.value = 0;
};

const setKilograms = (value) => {
    kilograms.value = value;
    inputMode.value = "kg";
};

// Add max available stock button
const setMaxStock = () => {
    kilograms.value = remainingStock.value;
    inputMode.value = "kg";
};

// Increment/decrement functions
const increment = () => {
    if (inputMode.value === "sacks") {
        sacks.value = Math.max(0, (parseFloat(sacks.value) || 0) + 1);
    } else {
        kilograms.value = Math.max(
            0,
            (parseFloat(kilograms.value) || 0) + 0.25
        );
    }
};

const decrement = () => {
    if (inputMode.value === "sacks") {
        sacks.value = Math.max(0, (parseFloat(sacks.value) || 0) - 1);
    } else {
        kilograms.value = Math.max(
            0,
            (parseFloat(kilograms.value) || 0) - 0.25
        );
    }
};
</script>

<template>
    <div
        class="flex flex-col gap-4 items-center justify-center mt-4 p-4 bg-gray-50 rounded-lg"
    >
        <!-- Mode Toggle -->
        <div class="flex bg-white rounded-lg p-1 shadow-sm border">
            <button
                @click="switchMode('kg')"
                :class="[
                    'px-4 py-2 rounded-md text-sm font-medium transition-all',
                    inputMode === 'kg'
                        ? 'bg-blue-600 text-white shadow-sm'
                        : 'text-gray-600 hover:text-gray-900',
                ]"
            >
                Kilograms
            </button>
            <button
                @click="switchMode('sacks')"
                :class="[
                    'px-4 py-2 rounded-md text-sm font-medium transition-all',
                    inputMode === 'sacks'
                        ? 'bg-blue-600 text-white shadow-sm'
                        : 'text-gray-600 hover:text-gray-900',
                ]"
            >
                Sacks (25kg)
            </button>
        </div>

        <!-- Main Input Section -->
        <div class="flex gap-3 items-center">
            <!-- Decrement Button -->
            <button
                @click="decrement"
                class="w-10 h-10 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-xl font-bold text-gray-600"
                :disabled="(inputMode === 'kg' ? kilograms : sacks) <= 0"
            >
                -
            </button>

            <!-- Primary Input (based on mode) -->
            <div class="flex gap-2 items-center">
                <input
                    v-if="inputMode === 'kg'"
                    type="number"
                    v-model.number="kilograms"
                    min="0.1"
                    step="0.1"
                    placeholder="1.0"
                    class="w-32 px-4 py-3 text-xl text-center border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                />
                <input
                    v-else
                    type="number"
                    v-model.number="sacks"
                    min="0"
                    step="1"
                    placeholder="0"
                    class="w-32 px-4 py-3 text-xl text-center border-2 border-gray-300 rounded-lg focus:border-blue-500 focus:outline-none"
                />
                <span class="text-gray-600 text-lg font-medium min-w-[60px]">
                    {{ inputMode === "kg" ? "kg" : "sacks" }}
                </span>
            </div>

            <!-- Increment Button -->
            <button
                @click="increment"
                class="w-10 h-10 rounded-full bg-gray-200 hover:bg-gray-300 flex items-center justify-center text-xl font-bold text-gray-600"
            >
                +
            </button>
        </div>

        <!-- Secondary Display -->
        <div class="text-sm text-gray-500 text-center">
            <span v-if="inputMode === 'kg' && sacks > 0">
                = {{ sacks }} sack(s)
                {{ kilograms % 25 !== 0 ? `+ ${kilograms % 25} kg` : "" }}
            </span>
            <span v-else-if="inputMode === 'sacks'">
                = {{ kilograms }} kg
            </span>
        </div>

        <!-- Quick Select Buttons -->
        <div class="flex flex-wrap gap-2 justify-center">
            <button
                @click="setKilograms(0.25)"
                class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shadow-sm"
            >
                ¼ kg
            </button>
            <button
                @click="setKilograms(0.5)"
                class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shadow-sm"
            >
                ½ kg
            </button>
            <button
                @click="setKilograms(1)"
                class="px-3 py-2 text-sm bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shadow-sm"
            >
                1 kg
            </button>

            <button
                @click="setMaxStock"
                class="px-3 py-2 text-sm bg-orange-100 text-orange-700 border border-orange-300 rounded-lg hover:bg-orange-200 shadow-sm"
                v-if="remainingStock > 0"
            >
                Max ({{ remainingStock }} kg)
            </button>
        </div>

        <!-- Stock Info -->
        <div class="text-sm text-gray-600 text-center">
            <span class="font-medium">Available:</span> {{ remainingStock }} kg
            <span v-if="remainingStock >= 25" class="ml-2">
                ({{ Math.floor(remainingStock / 25) }} full sacks)
            </span>
        </div>

        <!-- Feedback Message -->
        <div
            v-if="showFeedback"
            :class="[
                'px-4 py-2 rounded-lg text-sm font-medium text-center transition-all',
                showFeedback.type === 'success'
                    ? 'bg-green-100 text-green-800 border border-green-200'
                    : '',
                showFeedback.type === 'error'
                    ? 'bg-red-100 text-red-800 border border-red-200'
                    : '',
                showFeedback.type === 'info'
                    ? 'bg-blue-100 text-blue-800 border border-blue-200'
                    : '',
            ]"
        >
            {{ showFeedback.message }}
        </div>

        <!-- Add to Cart Button -->
        <button
            @click="addToCartButton"
            :disabled="!isValidAmount"
            :class="[
                'px-8 py-3 text-lg font-semibold rounded-lg flex items-center gap-2 transition-all transform hover:scale-105',
                isValidAmount
                    ? 'text-white bg-blue-600 hover:bg-blue-700 shadow-lg'
                    : 'text-gray-400 bg-gray-200 cursor-not-allowed',
            ]"
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
            Add to Cart
            <span v-if="kilograms > 0" class="text-sm opacity-90">
                ({{ kilograms }} kg)
            </span>
        </button>
    </div>
</template>
