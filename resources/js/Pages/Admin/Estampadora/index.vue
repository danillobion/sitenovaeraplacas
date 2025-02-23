<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head,Link,useForm } from '@inertiajs/vue3';

const props = defineProps({
    estampadoras: Object,
});

const form = useForm({});
const deletarEstampadora = (estampadora_id) => {
    if (confirm("Tem certeza que deseja excluir esta estampadora?")) {
        form.delete(route('estampadora.deletar', { estampadora_id: estampadora_id }));
    }
};
</script>

<template>
    <Head title="Estampadoras" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Estampadoras</h2>
                <a href="/estampadoras/edit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Adicionar</a>
            </div>
        </template>

        <div class="py-1">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800"
                >
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <!-- tabela -->
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            Imagem
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Nome
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            CNPJ
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Ações
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="estampadoras" v-for="estampadora in estampadoras" :key="estampadora.id"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            <img :src="`/${estampadora.imagem}`" alt="Imagem da Estampadora" class="mt-4 w-10 h-10 object-cover rounded-lg">
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ estampadora.nome }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ estampadora.cnpj }}
                                        </td>
                                        <td class="px-6 py-4 flex inline-flex space-x-2">
                                            <Link
                                                :href="route('estampadora.editar', { id: estampadora.id})"
                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                            >
                                                Editar
                                            </Link>
                                            <button
                                                @click="deletarEstampadora(estampadora.id)"
                                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                            >
                                                Deletar
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="estampadoras && estampadoras.length == 0"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                                        <td class="px-6 py-4 text-center" colspan="4">
                                            Nenhuma estampadora cadastrada
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- fim tabela -->
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
