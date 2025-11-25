<script setup>
import { ref } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    batch: Object,
});

const showModal = ref(false);
const isDeleting = ref(false);

const openModal = () => {
    showModal.value = true;
};

const closeModal = () => {
    if (!isDeleting.value) {
        showModal.value = false;
    }
};

const deleteBatch = async (id) => {
    isDeleting.value = true;

    router.delete(route("product-batches.destroy", id), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
            isDeleting.value = false;
        },
        onError: () => {
            isDeleting.value = false;
        },
    });
};

// Close modal on escape key
const handleEscape = (e) => {
    if (e.key === "Escape" && !isDeleting.value) {
        closeModal();
    }
};
</script>

<template>
    <button
        @click="openModal"
        class="group inline-flex items-center gap-2 px-4 py-2.5 border border-red-200 rounded-xl text-sm font-medium text-red-600 bg-white hover:bg-red-50 hover:border-red-300 focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-300 transition-all duration-200 shadow-sm hover:shadow"
        :disabled="isDeleting"
    >
        <svg
            class="w-4 h-4 transition-transform duration-200 group-hover:scale-110"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
            />
        </svg>
    </button>

    <!-- Modal Backdrop -->
    <Teleport to="body">
        <Transition
            enter-active-class="duration-300 ease-out"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-active-class="duration-200 ease-in"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="showModal"
                class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
                @click="closeModal"
                @keydown="handleEscape"
                tabindex="0"
            >
                <!-- Modal Content -->
                <Transition
                    enter-active-class="duration-300 ease-out"
                    enter-from-class="opacity-0 scale-95 translate-y-4"
                    enter-to-class="opacity-100 scale-100 translate-y-0"
                    leave-active-class="duration-200 ease-in"
                    leave-from-class="opacity-100 scale-100 translate-y-0"
                    leave-to-class="opacity-0 scale-95 translate-y-4"
                >
                    <div
                        v-if="showModal"
                        class="bg-white rounded-2xl shadow-2xl p-6 w-full max-w-md mx-auto"
                        @click.stop
                    >
                        <!-- Header -->
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-full flex items-center justify-center"
                            >
                                <svg
                                    class="w-6 h-6 text-red-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"
                                    />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">
                                    Delete Product Batch
                                </h3>
                                <p class="text-sm text-gray-500 mt-1">
                                    This action cannot be undone
                                </p>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="mb-6">
                            <p class="text-gray-600 leading-relaxed">
                                Are you sure you want to delete this product
                                batch? All associated data will be permanently
                                removed from the system.
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-3 justify-end">
                            <button
                                @click="closeModal"
                                :disabled="isDeleting"
                                class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-200 transition-all duration-200 font-medium disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Cancel
                            </button>
                            <button
                                @click="deleteBatch(batch.id)"
                                :disabled="isDeleting"
                                class="px-4 py-2.5 rounded-xl bg-red-600 text-white font-medium hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500/20 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2 min-w-[100px] justify-center"
                            >
                                <svg
                                    v-if="isDeleting"
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
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                    />
                                </svg>
                                <span>{{
                                    isDeleting ? "Deleting..." : "Delete"
                                }}</span>
                            </button>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
