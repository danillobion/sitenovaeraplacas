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
  tipo: Object,
});

const form = useForm({
  id: null,
  nome: '',
  descricao: '',
});

const submit = () => {
  const formData = new FormData();

  if(form.id != undefined){
    formData.append("id",props.tipo.id);
  }
  formData.append("nome", form.nome);
  formData.append("descricao", form.descricao);

  axios.post(route('tipo.salvar'), formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
  .then(() => {
    alert('Tipo salvo com sucesso!');
    window.location.href = route('tipos.index');
  })
  .catch(error => {
    console.error("Erro ao salvar o tipo:", error);
    alert('Erro ao salvar usuário.');
  });
};

onMounted(() => {
  if(props.tipo){
    form.id = props.tipo.id;
    form.nome = props.tipo.nome;
    form.descricao = props.tipo.descricao;
  }
});
</script>

<template>
  <Head title="Tipo" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Editar tipo</h2>
    </template>

    <div class="py-1">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
          <div class="p-6 text-gray-900 dark:text-gray-100">
            <form @submit.prevent="submit" enctype="multipart/form-data">
              <h2 class="mb-4 text-lg">Informações</h2>          
              <div class="mt-4 grid md:grid-cols-2 md:gap-6">
                <div class="mt-4">
                  <InputLabel for="nome" value="Nome" />
                  <TextInput
                  id="nome"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.nome"
                  required
                  autocomplete="name"
                  placeholder="Digite o nome do tipo de perfil do usuário"
                  />
                  <InputError class="mt-2" />
                </div>
                <div class="mt-4">
                  <InputLabel for="descricao" value="Descrição" />
                  <TextInput
                  id="descricao"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.descricao"
                  required
                  autocomplete="email"
                  placeholder="Faça uma descrição do tipo de perfil do usuário"
                  />
                  <InputError class="mt-2" />
                </div>
              </div>

              <div class="mt-4 flex items-center justify-end">
                <PrimaryButton
                  class="ms-4"
                  :class="{ 'opacity-25': form.processing }"
                  :disabled="form.processing"
                >
                  Salvar
                </PrimaryButton>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
