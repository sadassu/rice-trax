<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import TextInput from "../../Components/TextInput.vue";

const showModal = ref(false);

const props = defineProps({
    batch: Object,
});

const form = useForm({
    product_id: props.batch.product_id,
    kg_remaining: props.batch.kg_remaining,
    price_per_sack: props.batch.price_per_sack,
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
    form.put(route("product-batches.update", props.batch.id), {
        onSuccess: () => closeModal(),
    });
};
</script>

<template>
    <button
        @click="openModal"
        preserve-scroll
        class="inline-flex items-center px-3 py-2 border border-green-300 rounded-md text-sm font-medium text-green-700 bg-white hover:bg-green-50 focus:outline-none focus:ring-2 focus:ring-green-500 transition-all duration-200"
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

    <transition name="fade">
        <div
            v-if="showModal"
            class="fixed inset-0 bg-black/60 flex items-center justify-center z-50"
        >
            <h2 class="text-xl font-semibold text-gray-800 mb-2">
                Update Product Batch
            </h2>
            <div
                class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-md transform transition-all scale-95 hover:scale-100"
            >
                <form @submit.prevent="submit" class="space-y-4">
                    <TextInput
                        class="hidden"
                        name="product_id"
                        v-model="form.product_id"
                        :message="form.errors.product_id"
                    />
                    <TextInput
                        name="Kilograms"
                        placeholder="Kilogram (Number only)"
                        v-model="form.kg_remaining"
                        :message="form.errors.kg_remaining"
                    />
                    <TextInput
                        name="Price per Sack"
                        placeholder="Price per Sack (Number Only)"
                        v-model="form.price_per_sack"
                        :message="form.errors.price_per_sack"
                    />
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
