<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    produtos: Array,
    estampadora_id: Number,
    estampadora_nome: String,
});

const form = useForm({});

const deletarProduto = (id) => {
    if (confirm("Tem certeza que deseja excluir este produto?")) {
        form.delete(route('estampadora.produtos.deletar', { id: id, estampadora_id: props.estampadora_id }));
    }
};
</script>

<template>
    <Head title="Produtos" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <div>
                    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Produtos</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Estampadora: {{ props.estampadora_nome }}</p>
                </div>
                <Link
                    :href="route('estampadora.produtos.editar', { id: null, estampadora_id: props.estampadora_id })"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                >
                    Adicionar
                </Link>
            </div>
        </template>

        <div class="py-1">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <!-- tabela -->
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Nome</th>
                                        <th scope="col" class="px-6 py-3">Descrição</th>
                                        <th scope="col" class="px-6 py-3">Valor (R$)</th>
                                        <th scope="col" class="px-6 py-3">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="produtos.length > 0" v-for="produto in produtos" :key="produto.id"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ produto.nome }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ produto.descricao }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ produto.valor }}
                                        </td>
                                        <td class="px-6 py-4 flex inline-flex space-x-2">
                                            <Link
                                                :href="route('estampadora.produtos.editar', { id: produto.id, estampadora_id: props.estampadora_id })"
                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                            >
                                                Editar
                                            </Link>
                                            <button
                                                @click="deletarProduto(produto.id)"
                                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                            >
                                                Deletar
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="produtos.length === 0"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                                        <td class="px-6 py-4 text-center" colspan="4">
                                            Nenhum produto cadastrado
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
