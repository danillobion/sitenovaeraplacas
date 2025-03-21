<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { BarChart, FileText, MapPin } from 'lucide-vue-next';

const props = defineProps({
    quantidade_consultas: { type: Number, default: 0 },
    quantidade_consultas_cep: { type: Number, default: 0 },
    quantidade_consultas_cnpj: { type: Number, default: 0 },
});

// Paleta de cores para os cards
const COLORS = {
    total: 'bg-blue-500',
    cep: 'bg-green-500',
    cnpj: 'bg-yellow-500',
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                Dashboard
            </h2>
        </template>

        <div class="py-6">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    <!-- Card Total de Consultas -->
                    <div class="bg-white p-6 rounded-lg shadow-md dark:bg-gray-700 flex items-center">
                        <div :class="[COLORS.total, 'p-3 text-white rounded-lg']">
                            <BarChart class="w-10 h-10" />
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-500 dark:text-gray-200">Total de Consultas</h3>
                            <p class="text-4xl font-bold text-gray-800 dark:text-gray-100">
                                {{ quantidade_consultas }}
                            </p>
                        </div>
                    </div>

                    <!-- Card Total de Consultas por CEP -->
                    <div class="bg-white p-6 rounded-lg shadow-md dark:bg-gray-700 flex items-center">
                        <div :class="[COLORS.cep, 'p-3 text-white rounded-lg']">
                            <MapPin class="w-10 h-10" />
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-500 dark:text-gray-200">Consultas por CEP</h3>
                            <p class="text-4xl font-bold text-gray-800 dark:text-gray-100">
                                {{ quantidade_consultas_cep }}
                            </p>
                        </div>
                    </div>

                    <!-- Card Total de Consultas por CNPJ -->
                    <div class="bg-white p-6 rounded-lg shadow-md dark:bg-gray-700 flex items-center">
                        <div :class="[COLORS.cnpj, 'p-3 text-white rounded-lg']">
                            <FileText class="w-10 h-10" />
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-500 dark:text-gray-200">Consultas por CNPJ</h3>
                            <p class="text-4xl font-bold text-gray-800 dark:text-gray-100">
                                {{ quantidade_consultas_cnpj }}
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
