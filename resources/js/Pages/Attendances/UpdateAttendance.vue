<script setup>
import { useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import Modal from "@/Components/Modal.vue";
import { SquarePen } from "lucide-vue-next";

const props = defineProps({
    attendance: Object,
});

const showModal = ref(false);

// 🔧 Convert "HH:mm:ss" → "HH:mm" for input
const toInputTime = (time) => {
    if (!time) return "";
    // Try to parse from ISO string
    const date = new Date(time);
    if (isNaN(date)) return "";
    const hours = String(date.getHours()).padStart(2, "0");
    const minutes = String(date.getMinutes()).padStart(2, "0");
    return `${hours}:${minutes}`;
};

const toLaravelTime = (time) => {
    if (!time) return null;
    // Just return the time in HH:mm format
    return time; // e.g., "14:30"
};
// ✅ Initialize form
const form = useForm({
    time_in: "",
    time_out: "",
    status: "",
});

// ✅ Sync prop to form
watch(
    () => props.attendance,
    (attendance) => {
        if (attendance) {
            form.time_in = toInputTime(attendance.time_in);
            form.time_out = toInputTime(attendance.time_out);
            form.status = attendance.status;
        }
    },
    { immediate: true }
);

const openModal = () => (showModal.value = true);

const closeModal = () => {
    if (!form.processing) {
        showModal.value = false;
        form.reset();
        form.clearErrors();
    }
};

// ✅ Submit handler with transformation
const submit = () => {
    form.transform((data) => ({
        ...data,
        time_in: toLaravelTime(data.time_in),
        time_out: toLaravelTime(data.time_out),
    })).put(route("attendances.update", props.attendance.id), {
        onSuccess: () => closeModal(),
    });
};
</script>

<template>
    <!-- Trigger -->
    <button
        @click="openModal"
        class="cursor-pointer inline-flex items-center px-3 py-2 border border-green-300 rounded-md text-sm font-medium text-green-700 bg-white hover:bg-green-50 transition"
    >
        <SquarePen />
    </button>

    <!-- Modal -->
    <Modal
        :show="showModal"
        title="Update Attendance"
        subtitle="Modify attendance details below"
        :processing="form.processing"
        @close="closeModal"
    >
        <!-- Form Fields -->
        <form @submit.prevent="submit" class="space-y-4">
            <!-- Time In -->
            <div>
                <label
                    for="time_in"
                    class="block mb-2 text-sm font-semibold text-gray-700 capitalize"
                    >Time In</label
                >
                <input
                    id="time_in"
                    type="time"
                    v-model="form.time_in"
                    class="w-full px-4 py-2 border rounded-2xl shadow-sm outline-none transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 border-gray-300"
                />
                <small
                    v-if="form.errors.time_in"
                    class="mt-1 block text-sm text-red-500"
                >
                    {{ form.errors.time_in }}
                </small>
            </div>

            <!-- Time Out -->
            <div>
                <label
                    for="time_out"
                    class="block mb-2 text-sm font-semibold text-gray-700 capitalize"
                    >Time Out</label
                >
                <input
                    id="time_out"
                    type="time"
                    v-model="form.time_out"
                    class="w-full px-4 py-2 border rounded-2xl shadow-sm outline-none transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 border-gray-300"
                />
                <small
                    v-if="form.errors.time_out"
                    class="mt-1 block text-sm text-red-500"
                >
                    {{ form.errors.time_out }}
                </small>
            </div>

            <!-- Status -->
            <div>
                <label
                    for="status"
                    class="block mb-2 text-sm font-semibold text-gray-700 capitalize"
                    >Status</label
                >
                <select
                    id="status"
                    v-model="form.status"
                    class="w-full px-4 py-2 border rounded-2xl shadow-sm outline-none transition-all duration-200 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 border-gray-300"
                >
                    <option value="" disabled>Select Status</option>
                    <option value="present">Present</option>
                    <option value="late">Late</option>
                    <option value="absent">Absent</option>
                </select>

                <small
                    v-if="form.errors.status"
                    class="mt-1 block text-sm text-red-500"
                >
                    {{ form.errors.status }}
                </small>
            </div>
        </form>

        <!-- Actions -->
        <template #actions>
            <button
                type="button"
                @click="closeModal"
                class="px-4 py-2.5 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 transition"
                :disabled="form.processing"
            >
                Cancel
            </button>

            <button
                type="submit"
                @click="submit"
                class="px-4 py-2.5 rounded-lg bg-green-600 text-white hover:bg-green-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="form.processing"
            >
                <span v-if="form.processing">Updating...</span>
                <span v-else>Update</span>
            </button>
        </template>
    </Modal>
</template>
