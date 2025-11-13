<script setup>
import { ref } from "vue";
import Modal from "./Modal.vue";

const showModal = ref(true);

const closeModal = () => {
    showModal.value = false;
};
</script>

<template>
    <!-- Modal -->
    <Modal
        :show="showModal"
        title="Welcome!"
        :subtitle="`${$page.props.auth.user.name}`"
        icon="info"
        @close="closeModal"
    >
        <template #icon>
            <svg
                class="w-6 h-6 text-blue-600"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z"
                />
            </svg>
        </template>

        <!-- Default slot -->
        <p class="text-gray-700 text-center">
            You’re currently logged in as
            <strong class="uppercase">{{ $page.props.auth.user.role }}</strong
            >.
        </p>

        <!-- Actions slot -->
        <template #actions>
            <button
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700"
                @click="closeModal"
            >
                Continue
            </button>
        </template>
    </Modal>
</template>
