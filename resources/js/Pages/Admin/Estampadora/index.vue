<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head,Link,useForm } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import { confirmDeleteAlert } from '@/lib/sweetAlert';

const props = defineProps({
    estampadoras: Object,
});

const form = useForm({});
const deletarEstampadora = async (estampadora_id) => {
    const confirmado = await confirmDeleteAlert({
        title: 'Excluir estampadora?',
        text: 'Essa estampadora será removida do cadastro.',
        confirmButtonText: 'Sim, excluir',
    });

    if (confirmado) {
        form.delete(route('estampadora.deletar', { estampadora_id: estampadora_id }));
    }
};
</script>

<template>
    <Head title="Estampadoras" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2>Estampadoras</h2>
                    <p>Gerencie as unidades e mantenha a operação organizada em um só lugar.</p>
                </div>

                <Link
                    :href="route('estampadora.editar', { id: null })"
                    class="inline-flex items-center justify-center gap-2 self-start rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                >
                    <Plus class="h-4 w-4" />
                    Adicionar estampadora
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
                                        class="bg-white border-b border-gray-200">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
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
                                        class="bg-white border-b border-gray-200">
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
