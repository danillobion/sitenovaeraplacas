<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    quantidade_consultas: {
        type: Number,
        default: 0,
    },
    quantidade_consultas_cep: {
        type: Number,
        default: 0,
    },
    quantidade_consultas_cnpj: {
        type: Number,
        default: 0,
    },
});

// Defina as variáveis para armazenar os totais
const totalConsultas = ref(100); // Exemplo de total de consultas
const totalConsultasCep = ref(40); // Exemplo de total de consultas por CEP
const totalConsultasCnpj = ref(60); // Exemplo de total de consultas por CNPJ
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-1">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                    <div class="p-0 text-gray-900 dark:text-gray-100">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Card Total de Consultas -->
                            <div class="bg-white p-4 rounded-lg shadow-md dark:bg-gray-700">
                                <h3 class="text-xl font-semibold text-gray-500 dark:text-gray-200">
                                    Total de Consultas
                                </h3>
                                <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">
                                    {{ quantidade_consultas }}
                                </p>
                            </div>

                            <!-- Card Total de Consultas por CEP -->
                            <div class="bg-white p-4 rounded-lg shadow-md dark:bg-gray-700">
                                <h3 class="text-xl font-semibold text-gray-500 dark:text-gray-200">
                                    Total de Consultas por CEP
                                </h3>
                                <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">
                                    {{ quantidade_consultas_cep }}
                                </p>
                            </div>

                            <!-- Card Total de Consultas por CNPJ -->
                            <div class="bg-white p-4 rounded-lg shadow-md dark:bg-gray-700">
                                <h3 class="text-xl font-semibold text-gray-500 dark:text-gray-200">
                                    Total de Consultas por CNPJ
                                </h3>
                                <p class="text-3xl font-bold text-gray-800 dark:text-gray-100">
                                    {{ quantidade_consultas_cnpj }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </AuthenticatedLayout>
</template>

