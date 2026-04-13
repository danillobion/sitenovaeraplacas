<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { confirmDeleteAlert } from '@/lib/sweetAlert';

const props = defineProps({
    produtos: Array,
});

const form = useForm({});

const deletarProduto = async (produto_id) => {
    const confirmado = await confirmDeleteAlert({
        title: 'Excluir produto?',
        text: 'Esse produto será removido do catálogo.',
        confirmButtonText: 'Sim, excluir',
    });

    if (confirmado) {
        form.delete(route('produto.deletar', { produto_id: produto_id }));
    }
};
</script>

<template>
    <Head title="Produtos" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2>Produtos</h2>
                    <p>Atualize o catálogo com mais clareza e destaque os itens disponíveis.</p>
                </div>

                <Link
                    :href="route('produto.editar', { id: null })"
                    class="inline-flex items-center justify-center gap-2 self-start rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                >
                    <Plus class="h-4 w-4" />
                    Adicionar produto
                </Link>
            </div>
        </template>

        <div class="py-1">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- tabela -->
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-left rtl:text-right text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Imagem</th>
                                        <th scope="col" class="px-6 py-3">Nome</th>
                                        <th scope="col" class="px-6 py-3">Descrição</th>
                                        <th scope="col" class="px-6 py-3">Valor (R$)</th>
                                        <th scope="col" class="px-6 py-3">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="produtos.length > 0" v-for="produto in produtos" :key="produto.id"
                                        class="bg-white border-b border-gray-200">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            <img :src="`/${produto.imagem}`" alt="Imagem do produto" class="mt-4 w-10 h-10 object-cover rounded-lg">
                                        </th>
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
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
                                                :href="route('produto.editar', { id: produto.id })"
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
                                        class="bg-white border-b border-gray-200">
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
