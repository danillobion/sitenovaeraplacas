<script setup>
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
  tipo_id: Number,
  permissoes: Object,
});

const form = useForm({
  tipo_id: null,
  permissao_id: null,
});

const vincularPermissao = (permissao_id) => {
  const formData = new FormData();
  formData.append("tipo_id", form.tipo_id);
  formData.append("permissao_id", permissao_id);

  axios.post(route('permissao.salvar'), formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
  .then(() => {
    alert('Permissão vinculada com sucesso!');
    window.location.href = route('permissao.editar', { tipo_id: form.tipo_id });
  })
  .catch(error => {
    console.error("Erro ao vincular a permissão ao tipo de usuário:", error);
    alert('Erro ao salvar usuário.');
  });
};

onMounted(() => {
  if(props.tipo_id){
    form.tipo_id = props.tipo_id;
  }
});
</script>

<template>
  <Head title="Tipo" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Editar permissões</h2>
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
                            <th scope="col" class="px-6 py-3">Nome e Descrição</th>
                            <th scope="col" class="px-6 py-3">Vinculado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="permissoes.length > 0" v-for="permissao in permissoes" :key="permissao.id"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                            <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                              <p>
                                {{ permissao.nome }}
                              </p>  
                              <p>
                                {{ permissao.descricao }}
                              </p>
                            </th>
                            <td class="px-6 py-4 flex inline-flex space-x-2">
                                <button v-if="permissao.vinculado"
                                    @click="vincularPermissao(permissao.id)"
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                >
                                  Desvincular
                                </button>

                                <button v-else
                                    @click="vincularPermissao(permissao.id)"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                                >
                                  Vincular
                                </button>
                            </td>
                        </tr>
                        <tr v-if="permissoes.length === 0"
                            class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                            <td class="px-6 py-4 text-center" colspan="4">
                                Nenhuma permissão cadastrada
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
