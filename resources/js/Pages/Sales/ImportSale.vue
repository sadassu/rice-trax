<script setup>
import axios from "axios";
import { ref } from "vue";
import Modal from "@/Components/Modal.vue";
import { FileDown } from "lucide-vue-next";

const showModal = ref(false);
const processing = ref(false);
const errors = ref({});
const file = ref(null);

// ✅ open/close modal
const openModal = () => (showModal.value = true);
const closeModal = () => {
    showModal.value = false;
    file.value = null;
    errors.value = {};
};

// ✅ handle file input change
const handleFileChange = (event) => {
    file.value = event.target.files[0];
};

// ✅ submit import
const submitForm = async () => {
    if (!file.value) {
        errors.value = { file: ["Please select an Excel file."] };
        return;
    }

    processing.value = true;
    errors.value = {};

    const formData = new FormData();
    formData.append("file", file.value);

    try {
        await axios.post(route("sales.import"), formData, {
            headers: { "Content-Type": "multipart/form-data" },
        });

        closeModal();
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            console.error(error);
            alert("❌ Import failed. Check console for details.");
        }
    } finally {
        processing.value = false;
    }
};
</script>

<template>
    <!-- Trigger button -->
    <button
        @click="openModal"
        class="cursor-pointer inline-flex items-center px-3 py-2 border border-blue-300 rounded-md text-sm font-medium text-blue-700 bg-white hover:bg-blue-50 transition"
    >
        <FileDown />
    </button>

    <!-- Modal -->
    <Modal
        :show="showModal"
        title="Import Sales"
        subtitle="Upload an Excel file (.xlsx or .csv) to import sales data."
        :processing="processing"
        @close="closeModal"
    >
        <form @submit.prevent="submitForm" class="space-y-4">
            <div>
                <label
                    for="file"
                    class="block text-sm font-medium text-gray-700"
                >
                    Excel File
                </label>

                <input
                    type="file"
                    id="file"
                    accept=".xlsx,.csv"
                    @change="handleFileChange"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                    required
                />

                <div v-if="errors.file" class="text-red-500 text-sm mt-1">
                    {{ errors.file[0] }}
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
                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
                    :disabled="processing"
                >
                    {{ processing ? "Importing..." : "Import" }}
                </button>
            </div>
        </form>
    </Modal>
</template>
