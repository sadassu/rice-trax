<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import Modal from "@/Components/Modal.vue";
import TextInput from "@/Components/TextInput.vue";

const props = defineProps({
    product: Object,
});

const showModal = ref(false);

const openModal = () => (showModal.value = true);
const closeModal = () => {
    showModal.value = false;
    form.reset();
    form.clearErrors();
};

const form = useForm({
    name: props.product.name,
    price_per_kilo: props.product.price_per_kilo,
});

const submit = () => {
    form.put(route("products.update", props.product.id), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
    });
};
</script>

<template>
    <!-- Trigger Button -->
    <button
        @click="openModal"
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
    </button>

    <!-- Modal -->
    <Modal
        :show="showModal"
        title="Update Product"
        :subtitle="`Editing ${props.product.name}`"
        :processing="form.processing"
        @close="closeModal"
    >
        <template #default>
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
                    name="Price per kilo"
                    label="Price per Kilo"
                    placeholder="0.00"
                    v-model="form.price_per_kilo"
                    step="0.01"
                    :message="form.errors.price_per_kilo"
                />
            </form>
        </template>

        <template #actions>
            <button
                type="button"
                @click="closeModal"
                class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200 disabled:opacity-50"
            >
                Cancel
            </button>
            <button
                type="submit"
                @click="submit"
                :disabled="form.processing"
                class="px-4 py-2.5 rounded-xl bg-green-600 text-white font-medium hover:bg-green-700 transition-all duration-200 disabled:opacity-50 flex items-center gap-2"
            >
                <svg
                    v-if="form.processing"
                    class="animate-spin w-4 h-4"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <circle
                        class="opacity-25"
                        cx="12"
                        cy="12"
                        r="10"
                        stroke="currentColor"
                        stroke-width="4"
                    />
                    <path
                        class="opacity-75"
                        fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373
                           0 0 5.373 0 12h4zm2 5.291A7.962
                           7.962 0 014 12H0c0 3.042 1.135
                           5.824 3 7.938l3-2.647z"
                    />
                </svg>
                <span>{{
                    form.processing ? "Updating..." : "Update Product"
                }}</span>
            </button>
        </template>
    </Modal>
</template>
