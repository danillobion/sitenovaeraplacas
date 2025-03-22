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
        <div class="flex flex-col items-center justify-center min-h-screen p-6 bg-gray-50 overflow-hidden">
            <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md border border-gray-200 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-red-400 to-transparent opacity-10"></div>
                <h2 class="text-3xl font-bold mb-6 text-center text-gray-900 uppercase tracking-wide">
                     Consultar Placa SENATRAN
                </h2>

                <div class="relative">
                    <input
                        v-model="placa"
                        type="text"
                        placeholder="Digite a placa"
                        class="w-full p-3 pl-12 border border-gray-300 rounded-lg bg-gray-50 text-gray-900 focus:ring-2 focus:ring-red-400 focus:outline-none shadow-sm"
                    />
                    <Search class="absolute left-4 top-3 text-red-500" size="20" />
                </div>

                <div class="relative mt-4">
                    <input
                        v-model="serial"
                        type="text"
                        placeholder="Digite o serial"
                        class="w-full p-3 pl-12 border border-gray-300 rounded-lg bg-gray-50 text-gray-900 focus:ring-2 focus:ring-red-400 focus:outline-none shadow-sm"
                    />
                    <Search class="absolute left-4 top-3 text-red-500" size="20" />
                </div>

                <p v-if="erro" class="text-red-500 text-sm flex items-center mt-3 bg-red-100 p-2 rounded-lg">
                    <AlertCircle class="mr-2 text-red-600" size="18" /> {{ erro }}
                </p>

                <button
                    @click="pesquisar"
                    class="w-full bg-red-500 hover:bg-red-600 text-white font-semibold py-3 rounded-lg mt-6 transition-all duration-300 transform hover:scale-105 shadow-md"
                >
                    Pesquisar
                </button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
/* Esconder barra de rolagem globalmente */
body {
    overflow: hidden;
}
</style>