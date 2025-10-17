<script setup>
import axios from "axios";
import { ref } from "vue";
import Modal from "@/Components/Modal.vue";

const showModal = ref(false);
const form = ref({
    start_date: "",
    end_date: "",
});

const errors = ref({}); // <— store validation errors manually
const processing = ref(false); // <— handle loading state

const openModal = () => (showModal.value = true);
const closeModal = () => (showModal.value = false);

const submitForm = async () => {
    processing.value = true;
    errors.value = {};

    try {
        const response = await axios.post(route("sales.export"), form.value, {
            responseType: "blob",
        });

        const blob = new Blob([response.data]);
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement("a");
        link.href = url;

        // ✅ Get filename from Laravel
        const contentDisposition = response.headers["content-disposition"];
        let fileName = "sales.xlsx";
        if (contentDisposition) {
            const match = contentDisposition.match(/filename="?([^"]+)"?/);
            if (match?.[1]) fileName = match[1];
        }

        link.setAttribute("download", fileName);
        document.body.appendChild(link);
        link.click();
        window.URL.revokeObjectURL(url);

        closeModal();
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            console.error(error);
        }
    } finally {
        processing.value = false;
    }
};
</script>

<template>
    <button
        @click="openModal"
        class="cursor-pointer inline-flex items-center px-3 py-2 border border-green-300 rounded-md text-sm font-medium text-green-700 bg-white hover:bg-green-50 transition"
    >
        Export Excel File
    </button>

    <Modal
        :show="showModal"
        title="Export Sales"
        subtitle="Select a date range to export sales to Excel"
        :processing="processing"
        @close="closeModal"
    >
        <form @submit.prevent="submitForm" class="space-y-4">
            <div>
                <label
                    for="start_date"
                    class="block text-sm font-medium text-gray-700"
                    >Start Date</label
                >
                <input
                    v-model="form.start_date"
                    type="date"
                    id="start_date"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"
                    required
                />
                <div v-if="errors.start_date" class="text-red-500 text-sm mt-1">
                    {{ errors.start_date[0] }}
                </div>
            </div>

            <div>
                <label
                    for="end_date"
                    class="block text-sm font-medium text-gray-700"
                    >End Date</label
                >
                <input
                    v-model="form.end_date"
                    type="date"
                    id="end_date"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500 sm:text-sm"
                    required
                />
                <div v-if="errors.end_date" class="text-red-500 text-sm mt-1">
                    {{ errors.end_date[0] }}
                </div>
            </div>

            <div class="flex justify-end space-x-2 mt-4">
                <button
                    type="button"
                    @click="closeModal"
                    class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-100"
                >
                    Cancel
                </button>

                <button
                    type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                    :disabled="processing"
                >
                    {{ processing ? "Exporting..." : "Export" }}
                </button>
            </div>
        </form>
    </Modal>
</template>
