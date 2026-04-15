<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';

const props = defineProps({
    tipos: Array,
});
</script>

<template>
    <Head title="Tipos" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2>Tipos de usuários</h2>
                    <p>Organize os perfis do sistema e mantenha as permissões bem estruturadas.</p>
                </div>

                <Link
                    :href="route('tipo.editar', { id: null })"
                    class="inline-flex items-center justify-center gap-2 self-start rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                >
                    <Plus class="h-4 w-4" />
                    Adicionar tipo
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
                                        <th scope="col" class="px-6 py-3">Nome</th>
                                        <th scope="col" class="px-6 py-3">Descrição</th>
                                        <th scope="col" class="px-6 py-3">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="tipos.length > 0" v-for="tipo in tipos" :key="tipo.id"
                                        class="bg-white border-b border-gray-200">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            {{ tipo.nome }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ tipo.descricao }}
                                        </td>
                                        <td class="px-6 py-4 flex inline-flex space-x-2">
                                            <Link
                                                :href="route('tipo.editar', { id: tipo.id })"
                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                            >
                                                Editar
                                            </Link>
                                            <Link
                                                :href="route('permissao.editar', { tipo_id: tipo.id })"
                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                            >
                                                Editar permissões
                                            </Link>
                                        </td>
                                    </tr>
                                    <tr v-if="tipos.length === 0"
                                        class="bg-white border-b border-gray-200">
                                        <td class="px-6 py-4 text-center" colspan="4">
                                            Nenhum tipo cadastrado
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
