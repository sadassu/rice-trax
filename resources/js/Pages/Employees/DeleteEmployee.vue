<script setup>
import { router } from "@inertiajs/vue3";
import { ref } from "vue";
import Modal from "@/Components/Modal.vue";

const props = defineProps({
    employee: Object,
});

const showModal = ref(false);
const isDeleting = ref(false);

const openModal = () => (showModal.value = true);
const closeModal = () => {
    if (!isDeleting.value) showModal.value = false;
};

const deleteEmployee = (id) => {
    isDeleting.value = true;
    router.delete(route("employees.destroy", id), {
        preserveScroll: true,
        onSuccess: () => {
            closeModal();
            isDeleting.value = false;
        },
        onError: () => (isDeleting.value = false),
    });
};
</script>

<template>
    <button
        @click="openModal"
        class="cursor-pointer inline-flex items-center gap-2 px-4 py-2.5 border border-red-200 rounded-xl text-sm font-medium text-red-600 bg-white hover:bg-red-50 transition-all duration-200"
        :disabled="isDeleting"
    >
        <svg
            class="w-4 h-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0
                00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
            />
        </svg>
        Delete
    </button>

    <Modal
        :show="showModal"
        :danger="true"
        title="Delete Employee"
        subtitle="This action cannot be undone"
        :processing="isDeleting"
        @close="closeModal"
    >
        <template #default>
            <p class="text-gray-600 leading-relaxed mb-3">
                Are you sure you want to delete this employee?
            </p>
            <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                <p class="text-sm text-red-700">
                    <strong>Warning:</strong> All associated data (attendances)
                    will be permanently removed.
                </p>
            </div>
        </template>
        <template #actions>
            <button
                @click="closeModal"
                :disabled="isDeleting"
                class="px-4 py-2.5 rounded-xl border border-gray-200 text-gray-700 bg-white hover:bg-gray-50 transition-all duration-200 disabled:opacity-50"
            >
                Cancel
            </button>

            <button
                @click="deleteEmployee(employee.id)"
                :disabled="isDeleting"
                class="px-4 py-2.5 rounded-xl bg-red-600 text-white font-medium hover:bg-red-700 transition-all duration-200 disabled:opacity-50 flex items-center gap-2"
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
                        d="M4 12a8 8 0 018-8V0C5.373
                           0 0 5.373 0 12h4zm2 5.291A7.962
                           7.962 0 014 12H0c0 3.042 1.135
                           5.824 3 7.938l3-2.647z"
                    />
                </svg>
                <span>{{
                    isDeleting ? "Deleting..." : "Delete Employee"
                }}</span>
            </button>
        </template>
    </Modal>
</template>
