<script setup>
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import axios from 'axios'; // Importa Axios para a requisição HTTP

const props = defineProps({
  estampadora: Object,
  endereco: Object,
});

const form = useForm({
  id: '',
  nome: '',
  cnpj: '',
  cep: '',
  uf: '',
  cidade: '',
  bairro: '',
  logradouro: '',
  numero: '',
  complemento: '',
  latitude: 0,
  longitude: 0,
});

const enderecoFields = [
  { id: 'cep', label: 'CEP', model: 'cep' },
  { id: 'uf', label: 'UF', model: 'uf' },
  { id: 'cidade', label: 'Cidade', model: 'cidade' },
  { id: 'bairro', label: 'Bairro', model: 'bairro' },
  { id: 'logradouro', label: 'Logradouro', model: 'logradouro' },
  { id: 'numero', label: 'Número', model: 'numero' },
  { id: 'complemento', label: 'Complemento', model: 'complemento' },
  { id: 'latitude', label: 'Latitude', model: 'latitude' },
  { id: 'longitude', label: 'Longitude', model: 'longitude' },
];

const submit = () => {
  form.post(route('estampadora.salvar'), {
    onFinish: () => form.reset(),
  });
};

// Método para buscar CEP na API do ViaCEP
const consultarCep = async () => {
  const cep = form.cep.replace(/\D/g, ''); // Remove caracteres não numéricos

  if (cep.length !== 8) {
    alert("CEP inválido! Digite um CEP com 8 dígitos.");
    return;
  }

  try {
    const response = await axios.get(route('endereco.cep', cep));

    if (response.data.error) {
      alert(response.data.error);
      return;
    }

    form.cep = response.data.cep;
    form.uf = response.data.uf;
    form.cidade = response.data.cidade;
    form.bairro = response.data.bairro;
    form.logradouro = response.data.logradouro;
    form.complemento = response.data.complemento || '';
  } catch (error) {
    alert("Erro ao buscar o CEP. Tente novamente.");
    console.error(error);
  }
};


onMounted(() => {
  const { estampadora, endereco } = props;
  
  form.id = estampadora.id;
  form.nome = estampadora.nome;
  form.cnpj = estampadora.cnpj;
  
  form.cep = endereco.cep;
  form.uf = endereco.uf;
  form.cidade = endereco.cidade;
  form.bairro = endereco.bairro;
  form.logradouro = endereco.logradouro;
  form.numero = endereco.numero;
  form.complemento = endereco.complemento;
  form.latitude = endereco.latitude;
  form.longitude = endereco.longitude;
});
</script>

<template>
  <Head title="Editar" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Editar estampadora</h2>
    </template>

    <div class="py-1">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
          <div class="p-6 text-gray-900 dark:text-gray-100">

            <form @submit.prevent="submit">
              <h2 class="mb-4 text-lg">Informações</h2>

              <div class="grid md:grid-cols-2 md:gap-6">
                <div>
                  <InputLabel for="nome" value="Nome da estampadora" />
                  <TextInput
                    id="nome"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.nome"
                    required
                    autofocus
                    autocomplete="name"
                  />
                  <InputError class="mt-2" :message="form.errors.nome" />
                </div>
                <div>
                  <InputLabel for="cnpj" value="CNPJ" />
                  <TextInput
                    id="cnpj"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.cnpj"
                    required
                    autocomplete="cnpj"
                  />
                  <InputError class="mt-2" :message="form.errors.cnpj" />
                </div>
              </div>

              <h2 class="mt-4 text-lg">Endereço</h2>

              <div class="mt-4 grid md:grid-cols-2 md:gap-6">
                <!-- Campo CEP com Botão ao Lado -->
                <div class="flex items-center space-x-2">
                  <div class="w-full">
                    <InputLabel for="cep" value="CEP" />
                    <TextInput
                      id="cep"
                      type="text"
                      class="mt-1 block w-full"
                      v-model="form.cep"
                      required
                      autocomplete="cep"
                    />
                    <InputError class="mt-2" :message="form.errors.cep" />
                  </div>
                  <PrimaryButton @click.prevent="consultarCep" class="mt-6">
                    Localizar
                  </PrimaryButton>
                </div>

                <!-- Outros Campos do Endereço -->
                <div v-for="field in enderecoFields.slice(1)" :key="field.id">
                  <InputLabel :for="field.id" :value="field.label" />
                  <TextInput
                    :id="field.id"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form[field.model]"
                    required
                    :autocomplete="field.id"
                  />
                  <InputError class="mt-2" :message="form.errors[field.model]" />
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
