<script setup>
import { ref } from "vue";
import { formatDate } from "../../utils/dateFormat";
import axios from "axios";

// Lucide icon imports
import { BellOff } from "lucide-vue-next";
import Modal from "../../Components/Modal.vue";

defineProps({
    notifications: Array,
});

const loading = ref(false);

// Modal state
const modalShow = ref(false);
const modalTitle = ref("");
const modalSubtitle = ref("");
const modalDanger = ref(false);

const cleanupNotifications = async () => {
    loading.value = true;
    try {
        const response = await axios.get("/cron/cleanup-notifications");

        if (response.data.success) {
            modalTitle.value = "Success";
            modalSubtitle.value = response.data.message;
            modalDanger.value = false;
        } else {
            modalTitle.value = "Failed";
            modalSubtitle.value =
                response.data.message || "Something went wrong";
            modalDanger.value = true;
        }
    } catch (error) {
        console.error(error);
        modalTitle.value = "Failed";
        modalSubtitle.value = "Failed to cleanup notifications";
        modalDanger.value = true;
    } finally {
        modalShow.value = true;
        loading.value = false;
    }
};
</script>

<template>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-red-200">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold">Notifications</h3>
            <div class="flex items-center gap-2">
                <span
                    class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full"
                >
                    Latest {{ notifications?.length || 0 }}
                </span>
                <button
                    @click="cleanupNotifications"
                    :disabled="loading"
                    class="flex items-center gap-1 bg-red-100 text-red-700 text-xs px-3 py-1 rounded-full hover:bg-red-200 transition disabled:opacity-50"
                >
                    <BellOff class="w-4 h-4" />
                    <span>{{ loading ? "Cleaning..." : "Cleanup" }}</span>
                </button>
            </div>
        </div>

        <!-- Table view (desktop) -->
        <div
            class="overflow-x-auto hidden md:block"
            v-if="notifications?.length"
        >
            <table class="min-w-full">
                <thead>
                    <tr class="border-b border-red-100">
                        <th
                            class="text-left py-3 px-2 text-xs font-medium text-red-500 uppercase tracking-wider"
                        >
                            Title
                        </th>
                        <th
                            class="text-left py-3 px-2 text-xs font-medium text-red-500 uppercase tracking-wider"
                        >
                            Message
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-red-50">
                    <tr
                        v-for="notification in notifications"
                        :key="notification.id"
                        class="hover:bg-red-50 transition-colors"
                    >
                        <td class="py-3 px-2 text-sm text-red-900 font-medium">
                            {{ notification.title }}
                        </td>
                        <td class="py-3 px-2 text-sm text-red-600">
                            {{ notification.message }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Card view (mobile) -->
        <div class="space-y-4 md:hidden" v-if="notifications?.length">
            <div
                v-for="notification in notifications"
                :key="notification.id"
                class="p-4 border border-red-200 rounded-xl shadow-sm hover:bg-red-50 transition"
            >
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-red-900">
                        {{ notification.title }}
                    </span>
                </div>
                <div class="text-sm text-red-600">
                    {{ notification.message }}
                </div>
            </div>
        </div>

        <!-- Empty state -->
        <div v-else class="h-64 flex items-center justify-center text-red-300">
            <div class="text-center">
                <BellOff class="w-12 h-12 mx-auto mb-4 text-red-200" />
                <p class="text-sm text-red-400">No notifications</p>
            </div>
        </div>
    </div>

    <Modal
        :show="modalShow"
        :title="modalTitle"
        :subtitle="modalSubtitle"
        :danger="modalDanger"
        @close="modalShow = false"
    >
        <template #actions>
            <button
                class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition"
                @click="modalShow = false"
            >
                OK
            </button>
        </template>
    </Modal>
</template>
