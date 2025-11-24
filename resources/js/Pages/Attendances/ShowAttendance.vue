<script setup>
import { ref, onMounted, computed } from "vue";
import { Calendar } from "lucide-vue-next";

const props = defineProps({
    employee: Object,
});

const showModal = ref(false);
const currentDate = ref(new Date());

const attendanceRecords = computed(() => {
    if (!props.employee?.attendances) return [];

    return props.employee.attendances.map((a) => ({
        date: a.date.split("T")[0],
        present: a.status === "present",
    }));
});

const openModal = () => {
    showModal.value = true;
    document.addEventListener("keydown", handleEscape);
};

const closeModal = () => {
    showModal.value = false;
    document.removeEventListener("keydown", handleEscape);
};

const handleEscape = (e) => {
    if (e.key === "Escape") closeModal();
};

const currentMonth = computed(() => currentDate.value.getMonth());
const currentYear = computed(() => currentDate.value.getFullYear());

const monthNames = [
    "January",
    "February",
    "March",
    "April",
    "May",
    "June",
    "July",
    "August",
    "September",
    "October",
    "November",
    "December",
];

const daysInMonth = computed(() => {
    return new Date(currentYear.value, currentMonth.value + 1, 0).getDate();
});

const firstDayOfMonth = computed(() => {
    return new Date(currentYear.value, currentMonth.value, 1).getDay();
});

const calendarDays = computed(() => {
    const days = [];
    const total = daysInMonth.value;
    const first = firstDayOfMonth.value;

    for (let i = 0; i < first; i++) days.push(null);
    for (let d = 1; d <= total; d++) days.push(d);

    return days;
});

const getAttendanceStatus = (day) => {
    if (!day) return null;

    const dateStr = `${currentYear.value}-${String(
        currentMonth.value + 1
    ).padStart(2, "0")}-${String(day).padStart(2, "0")}`;

    const record = attendanceRecords.value.find((r) => r.date === dateStr);
    return record ? record.present : null;
};

const isToday = (day) => {
    const t = new Date();
    return (
        day === t.getDate() &&
        currentMonth.value === t.getMonth() &&
        currentYear.value === t.getFullYear()
    );
};

const previousMonth = () => {
    if (currentMonth.value === 0) {
        currentDate.value = new Date(currentYear.value - 1, 11, 1);
    } else {
        currentDate.value = new Date(
            currentYear.value,
            currentMonth.value - 1,
            1
        );
    }
};

const nextMonth = () => {
    if (currentMonth.value === 11) {
        currentDate.value = new Date(currentYear.value + 1, 0, 1);
    } else {
        currentDate.value = new Date(
            currentYear.value,
            currentMonth.value + 1,
            1
        );
    }
};

const goToToday = () => {
    currentDate.value = new Date();
};
</script>

<template>
    <button
        @click="openModal"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors duration-200"
    >
        <Calendar />
    </button>

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
                @click.self="closeModal"
                tabindex="0"
            >
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
                        class="bg-white rounded-2xl shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto"
                    >
                        <!-- Header -->
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2
                                        class="text-2xl font-bold text-gray-900"
                                    >
                                        Attendance Calendar
                                    </h2>
                                    <p
                                        class="text-gray-600 mt-1"
                                        v-if="employee"
                                    >
                                        {{ employee.name }}
                                    </p>
                                </div>

                                <button
                                    @click="closeModal"
                                    class="text-gray-400 hover:text-gray-600"
                                >
                                    <svg
                                        class="w-6 h-6"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Calendar -->
                        <div class="p-6">
                            <!-- Month Header -->
                            <div class="flex items-center justify-between mb-6">
                                <button
                                    @click="previousMonth"
                                    class="p-2 hover:bg-gray-100 rounded-lg"
                                >
                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 19l-7-7 7-7"
                                        />
                                    </svg>
                                </button>

                                <div class="flex items-center gap-4">
                                    <h3 class="text-xl font-semibold">
                                        {{ monthNames[currentMonth] }}
                                        {{ currentYear }}
                                    </h3>
                                    <button
                                        @click="goToToday"
                                        class="text-sm bg-blue-100 text-blue-700 px-3 py-1 rounded-md hover:bg-blue-200"
                                    >
                                        Today
                                    </button>
                                </div>

                                <button
                                    @click="nextMonth"
                                    class="p-2 hover:bg-gray-100 rounded-lg"
                                >
                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 5l7 7-7 7"
                                        />
                                    </svg>
                                </button>
                            </div>

                            <!-- Week Header -->
                            <div class="grid grid-cols-7 gap-1 mb-2">
                                <div
                                    v-for="d in [
                                        'Sun',
                                        'Mon',
                                        'Tue',
                                        'Wed',
                                        'Thu',
                                        'Fri',
                                        'Sat',
                                    ]"
                                    :key="d"
                                    class="h-10 flex items-center justify-center text-sm font-medium text-gray-500 bg-gray-50 rounded-lg"
                                >
                                    {{ d }}
                                </div>
                            </div>

                            <!-- Calendar Days -->
                            <div class="grid grid-cols-7 gap-1">
                                <div
                                    v-for="(day, index) in calendarDays"
                                    :key="index"
                                    class="h-12 flex items-center justify-center"
                                >
                                    <div
                                        v-if="day"
                                        class="w-10 h-10 flex items-center justify-center rounded-lg relative transition-all"
                                        :class="{
                                            'bg-green-100 text-green-800 border-2 border-green-300':
                                                getAttendanceStatus(day),

                                            'bg-gray-50 text-gray-700 hover:bg-gray-100':
                                                getAttendanceStatus(day) ===
                                                null,

                                            'ring-2 ring-blue-500 ring-offset-1':
                                                isToday(day),
                                        }"
                                    >
                                        {{ day }}

                                        <div
                                            v-if="getAttendanceStatus(day)"
                                            class="absolute -top-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-white"
                                        ></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Legend -->
                            <div
                                class="mt-6 flex items-center justify-center gap-6"
                            >
                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-4 h-4 bg-green-500 rounded-full"
                                    ></div>
                                    <span class="text-sm text-gray-600"
                                        >Present</span
                                    >
                                </div>

                                <div class="flex items-center gap-2">
                                    <div
                                        class="w-4 h-4 bg-gray-300 rounded-full"
                                    ></div>
                                    <span class="text-sm text-gray-600"
                                        >No Record</span
                                    >
                                </div>
                            </div>

                            <!-- Stats -->
                            <div class="mt-6 grid grid-cols-3 gap-4">
                                <div
                                    class="bg-green-50 p-4 rounded-lg text-center"
                                >
                                    <div
                                        class="text-2xl font-bold text-green-700"
                                    >
                                        {{
                                            attendanceRecords.filter(
                                                (r) => r.present
                                            ).length
                                        }}
                                    </div>
                                    <div class="text-sm text-green-600">
                                        Days Present
                                    </div>
                                </div>

                                <div
                                    class="bg-blue-50 p-4 rounded-lg text-center"
                                >
                                    <div
                                        class="text-2xl font-bold text-blue-700"
                                    >
                                        {{
                                            Math.round(
                                                (attendanceRecords.filter(
                                                    (r) => r.present
                                                ).length /
                                                    attendanceRecords.length) *
                                                    100
                                            )
                                        }}%
                                    </div>
                                    <div class="text-sm text-blue-600">
                                        Attendance Rate
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </Transition>
            </div>
        </Transition>
    </Teleport>
</template>
