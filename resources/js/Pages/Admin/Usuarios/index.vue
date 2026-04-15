<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';

const props = defineProps({
    usuarios: Array,
});
</script>

<template>
    <Head title="Usuários" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2>Usuários</h2>
                    <p>Cadastre pessoas, perfis e acessos sem misturar a ação principal com o título.</p>
                </div>

                <Link
                    :href="route('usuario.editar', { id: null })"
                    class="inline-flex items-center justify-center gap-2 self-start rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                >
                    <Plus class="h-4 w-4" />
                    Adicionar usuário
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
                                        <th scope="col" class="px-6 py-3">E-mail</th>
                                        <th scope="col" class="px-6 py-3">Tipo de usuário</th>
                                        <th scope="col" class="px-6 py-3">Nº total Requisições</th>
                                        <th scope="col" class="px-6 py-3">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="usuarios.length > 0" v-for="usuario in usuarios" :key="usuario.id"
                                        class="bg-white border-b border-gray-200">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            {{ usuario.nome }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ usuario.email }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <p>
                                                {{ usuario.tipo.nome }}
                                            </p>
                                            <p class="text-gray-400">
                                                {{ usuario.tipo.descricao }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ usuario.quantidade_total_requisicoes }}
                                        </td>
                                        <td class="px-6 py-4 flex inline-flex space-x-2">
                                            <Link
                                                :href="route('usuario.editar', { id: usuario.id })"
                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                            >
                                                Editar
                                            </Link>
                                        </td>
                                    </tr>
                                    <tr v-if="usuarios.length === 0"
                                        class="bg-white border-b border-gray-200">
                                        <td class="px-6 py-4 text-center" colspan="4">
                                            Nenhum usuário cadastrado
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
