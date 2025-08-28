<script setup>
import { useForm } from "@inertiajs/vue3";
import TextInput from "../../Components/TextInput.vue";
import { ref } from "vue";

const showModal = ref(false);

const props = defineProps({
    product: Object,
});

const openModal = () => {
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    form.reset();
    form.clearErrors();
};

const submit = () => {
    form.put(route("products.update", props.product.id), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
    });
};

const form = useForm({
    name: props.product.name,
    price_per_kilo: props.product.price_per_kilo,
});
</script>

<template>
    <button
        @click="openModal"
        preserve-scroll
        class="cursor-pointer inline-flex items-center px-3 py-2 border border-green-300 rounded-md text-sm font-medium text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200"
    >
        <svg
            class="w-4 h-4 mr-1"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"
            ></path>
        </svg>
        Edit
    </button>

    <transition name="fade">
        <div
            v-if="showModal"
            class="fixed inset-0 bg-black/60 flex items-center justify-center z-50"
        >
            <div
                class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md transform transition-all scale-95 hover:scale-100"
            >
                <h2 class="text-xl font-semibold text-gray-800 mb-4">
                    Update product {{ $props.product.name }}
                </h2>
                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Product Name -->
                    <TextInput
                        name="name"
                        label="Product Name"
                        placeholder="Enter product name"
                        v-model="form.name"
                        :message="form.errors.name"
                    />

                    <!-- Price per kilo -->
                    <TextInput
                        name="price per kilo"
                        label="Price per Kilo"
                        placeholder="0.00"
                        v-model="form.price_per_kilo"
                        step="0.01"
                        :message="form.errors.price_per_kilo"
                    />

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-2">
                        <button
                            type="button"
                            @click="closeModal"
                            class="px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition cursor-pointer"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 rounded-lg bg-green-600 text-white font-medium hover:bg-green-700 transition cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing">Updating...</span>
                            <span v-else>Update</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </transition>
</template>
