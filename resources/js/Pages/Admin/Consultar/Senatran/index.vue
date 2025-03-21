<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Search, AlertCircle } from 'lucide-vue-next';

const placa = ref('');
const serial = ref('');
const erro = ref('');

const pesquisar = () => {
    if (!placa.value || !serial.value) {
        erro.value = "Por favor, preencha todos os campos!";
        return;
    }

    erro.value = ""; // Limpa o erro
    const url = `https://portalservicos.senatran.serpro.gov.br/#/veiculos/placa-veicular/detalhes/${serial.value}/${placa.value}`;
    window.open(url, "_blank");
};
</script>

<template>
    <Head title="Consultar SENATRAN" />

    <AuthenticatedLayout>
        <div class="flex flex-col items-center justify-center min-h-screen p-6 bg-gray-100 dark:bg-gray-900">
            <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg w-full max-w-md">
                <h2 class="text-2xl font-semibold mb-6 text-center text-gray-900 dark:text-gray-100">
                     Consultar Placa SENATRAN
                </h2>

                <div class="relative">
                    <input
                        v-model="placa"
                        type="text"
                        placeholder="Digite a placa"
                        class="w-full p-3 pl-10 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                    />
                    <Search class="absolute left-3 top-3 text-gray-500 dark:text-gray-300" size="18" />
                </div>

                <div class="relative mt-4">
                    <input
                        v-model="serial"
                        type="text"
                        placeholder="Digite o serial"
                        class="w-full p-3 pl-10 border border-gray-300 dark:border-gray-700 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-400 focus:outline-none"
                    />
                    <Search class="absolute left-3 top-3 text-gray-500 dark:text-gray-300" size="18" />
                </div>

                <p v-if="erro" class="text-red-500 text-sm flex items-center mt-2">
                    <AlertCircle class="mr-2" size="18" /> {{ erro }}
                </p>

                <button
                    @click="pesquisar"
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-medium py-3 rounded-lg mt-6 transition-all duration-300 transform hover:scale-105"
                >
                    Pesquisar 
                </button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
