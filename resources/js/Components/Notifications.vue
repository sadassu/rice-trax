<script setup>
import { ref } from "vue";
import Modal from "./Modal.vue";
import NotificationBar from "../Pages/Dashboard/NotificationBar.vue";
import { Bell } from "lucide-vue-next";

const showModal = ref(false);

const openModal = () => {
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
};
</script>

<template>
    <button
        class="px-3 py-1 bg-lime-600 text-white rounded-b-xl hover:bg-lime-900 cursor-pointer"
        @click="openModal"
    >
        <Bell />
    </button>

    <Modal
        :show="showModal"
        title="Notifications"
        subtitle="Latest system alerts"
        @close="closeModal"
    >
        <NotificationBar />

        <template #actions>
            <button
                class="px-4 py-2 bg-gray-500 text-white rounded-xl hover:bg-gray-600"
                @click="closeModal"
            >
                Close
            </button>
        </template>
    </Modal>
</template>
