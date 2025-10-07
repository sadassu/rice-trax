<template>
    <div
        v-if="links && links.length > 0"
        class="bg-gray-50 px-6 py-3 border-t border-gray-200 mt-4 rounded-lg"
    >
        <div class="flex items-center justify-between flex-wrap gap-3">
            <!-- Results info -->
            <div class="text-sm text-gray-700">
                {{ from }}–{{ to }} of {{ total }} results
            </div>

            <!-- Pagination links -->
            <div class="flex gap-1 flex-wrap items-center">
                <Link
                    v-for="link in links"
                    :key="link.label"
                    :href="link.url || '#'"
                    preserve-scroll
                    class="flex items-center gap-1 px-3 py-1 text-sm rounded-md transition-all"
                    :class="{
                        'bg-lime-200 text-lime-400 cursor-not-allowed': !link.url,
                        'bg-lime-900 text-white': link.active,
                        'bg-lime-400 text-lime-700 hover:bg-lime-200':
                            link.url && !link.active,
                    }"
                >
                    <!-- Render icons for previous / next -->
                    <template v-if="link.label === 'pagination.previous'">
                        <ChevronLeft class="w-4 h-4" />
                    </template>
                    <template v-else-if="link.label === 'pagination.next'">
                        <ChevronRight class="w-4 h-4" />
                    </template>
                    <template v-else v-html="link.label"></template>
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ChevronLeft, ChevronRight } from "lucide-vue-next";

defineProps({
    links: { type: Array, required: true },
    from: { type: Number, required: true },
    to: { type: Number, required: true },
    total: { type: Number, required: true },
});
</script>
