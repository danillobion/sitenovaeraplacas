<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    usuarios: Array,
});

const form = useForm({});

const deletarUsuario = (produto_id) => {
    if (confirm("Tem certeza que deseja excluir este usuários?")) {
        form.delete(route('usuario.deletar', { usuario_id: usuario_id }));
    }
};
</script>

<template>
    <Head title="Usuários" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Usuários</h2>
                <Link
                    :href="route('usuario.editar', { id: null })"
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
                                        <th scope="col" class="px-6 py-3">E-mail</th>
                                        <th scope="col" class="px-6 py-3">Tipo de usuário</th>
                                        <th scope="col" class="px-6 py-3">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="usuarios.length > 0" v-for="usuario in usuarios" :key="usuario.id"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                            {{ usuario.nome }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ usuario.email }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <p>
                                                {{ usuario.tipo.nome }}
                                            </p>
                                            <p class="text-gray-400 dark:text-white">
                                                {{ usuario.tipo.descricao }}
                                            </p>
                                        </td>
                                        <td class="px-6 py-4 flex inline-flex space-x-2">
                                            <Link
                                                :href="route('usuario.editar', { id: usuario.id })"
                                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                            >
                                                Editar
                                            </Link>
                                            <button
                                                @click="deletarUsuario(usuario.id)"
                                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                            >
                                                Deletar
                                            </button>
                                        </td>
                                    </tr>
                                    <tr v-if="usuarios.length === 0"
                                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
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
