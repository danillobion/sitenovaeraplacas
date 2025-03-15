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
  usuario: Object,
  tipos: Object,
});

const form = useForm({
  id: null,
  nome: '',
  email: '',
  tipo_id: '',
});

const submit = () => {
  const formData = new FormData();

  if(form.id != undefined){
    formData.append("id",props.usuario.id);
  }
  formData.append("nome", form.nome);
  formData.append("email", form.email);
  formData.append("tipo_id", form.tipo_id);

  axios.post(route('usuario.salvar'), formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
  .then(() => {
    alert('Usuário salvo com sucesso!');
    window.location.href = route('usuarios.index');
  })
  .catch(error => {
    console.error("Erro ao salvar usuário:", error);
    alert('Erro ao salvar usuário.');
  });
};

onMounted(() => {
  if(props.usuario){
    form.id = props.usuario.id;
    form.nome = props.usuario.nome;
    form.email = props.usuario.email;
    if(props.usuario.tipo_id){
      form.tipo_id = props.usuario.tipo_id;
    }
  }
});
</script>

<template>
  <Head title="Editar" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Editar usuário</h2>
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
                  placeholder="Digite o nome do usuário"
                  />
                  <InputError class="mt-2" />
                </div>
                <div class="mt-4">
                  <InputLabel for="email" value="E-mail" />
                  <TextInput
                  id="email"
                  type="email"
                  class="mt-1 block w-full"
                  v-model="form.email"
                  required
                  autocomplete="email"
                  placeholder="Digite o e-mail do usuário"
                  />
                  <InputError class="mt-2" />
                </div>
                <div class="mt-4">
                  <InputLabel for="tipo_id" value="Tipo" />
                  <select id="tipo_id"
                    class="mt-1 w-full block rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600"
                    v-model="form.tipo_id"
                  >
                    <option value="">Selecione o tipo de usuário</option>
                    <option v-for="tipo in tipos" :key="tipo.id" :value="tipo.id">{{ tipo.nome }}</option>
                  </select>
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
