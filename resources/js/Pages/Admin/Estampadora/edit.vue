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
  id: null,
  nome: '',
  telefone: '',
  horario_funcionamento: '',
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
  imagem: null,
});

const imagePreview = ref(null);

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
  const formData = new FormData();
  
  Object.keys(form).forEach(key => {
    if (form[key] !== null) {
      formData.append(key, form[key]);
    }
  });

  axios.post(route('estampadora.salvar'), formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
  .then(() => {
    alert('Estampadora salva com sucesso!');
    window.location.href = route('estampadora.index');
    form.reset();
  })
  .catch(error => {
    console.error(error);
    alert('Erro ao salvar estampadora.');
  });
};

// CEP
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
// CNPJ
const consultarCNPJ = async () => {
  const cnpj = form.cnpj.replace(/\D/g, '');

  if (cnpj.length !== 14) {
    alert("CNPJ inválido! Digite um CNPJ com 14 dígitos.");
    return;
  }

  try {
    const response = await axios.get(route('consultar.cnpj', cnpj));

    if (response.data.error) {
      alert(response.data.error);
      return;
    }

    form.nome = response.data.nome;
    form.telefone = response.data.telefone1;
    form.cep = response.data.cep;
    form.uf = response.data.uf;
    form.cidade = response.data.cidade;
    form.bairro = response.data.bairro;
    form.logradouro = response.data.logradouro;
    form.numero = response.data.numero;
    form.complemento = response.data.complemento || '';

  } catch (error) {
    alert("Erro ao buscar o CNPJ. Tente novamente.");
    console.error(error);
  }
};
const formatCNPJ = (value) => {
  return value
    .replace(/\D/g, '') // Remove caracteres não numéricos
    .replace(/^(\d{2})(\d)/, '$1.$2')
    .replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
    .replace(/\.(\d{3})(\d)/, '.$1/$2')
    .replace(/(\d{4})(\d)/, '$1-$2')
    .slice(0, 18); // Limita o tamanho para 18 caracteres
};

const handleCNPJInput = (event) => {
  form.cnpj = formatCNPJ(event.target.value);
};

const handleImageUpload = (event) => {
  const file = event.target.files[0];

  if (file) {
    form.imagem = file;
    imagePreview.value = URL.createObjectURL(file);
  }
};

onMounted(() => {
  const { estampadora, endereco } = props;

  if (estampadora.imagem) {
    imagePreview.value = `/${estampadora.imagem}`;
  }
  
  form.id = estampadora.id;
  form.nome = estampadora.nome;
  form.cnpj = estampadora.cnpj;
  form.telefone = estampadora.telefone;
  form.horario_funcionamento = estampadora.horario_funcionamento;
  form.imagem = estampadora.imagem;
  
  form.cep = endereco.cep;
  form.uf = endereco.uf;
  form.cidade = endereco.cidade;
  form.bairro = endereco.bairro;
  form.logradouro = endereco.logradouro;
  form.numero = endereco.numero;
  form.complemento = endereco.complemento;
  form.latitude = endereco.latitude;
  form.longitude = endereco.longitude;

  handleCNPJInput({ target: { value: form.cnpj } });
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
            <form @submit.prevent="submit" enctype="multipart/form-data">
              <h2 class="mb-4 text-lg">Informações</h2>
                <img v-if="imagePreview" :src="imagePreview || `/${form.imagem}`" 
                  alt="Imagem da Estampadora" 
                  class="mt-4 w-40 h-40 object-cover rounded-lg shadow-md">              
                  
              <div class="grid md:grid-cols-2 md:gap-6">
                <div class="mt-4">
                  <InputLabel for="imagem" value="Imagem da Estampadora" />
                  <input
                    id="imagem"
                    type="file"
                    class="mt-1 block w-full"
                    @change="handleImageUpload"
                    accept="image/*"
                  />
                  <InputError class="mt-2" :message="form.errors.imagem" />
                </div>
                <div></div>
                <!-- Campo CNPJ com Botão ao Lado -->
                <div class="flex items-center space-x-2">
                  <div class="w-full">
                    <InputLabel for="cnpj" value="CNPJ" />
                    <TextInput
                      id="cnpj"
                      type="text"
                      class="mt-1 block w-full"
                      v-model="form.cnpj"
                      required
                      autocomplete="cnpj"
                      @input="handleCNPJInput"
                    />
                    <InputError class="mt-2" :message="form.errors.cnpj" />
                  </div>
                  <PrimaryButton @click.prevent="consultarCNPJ" class="mt-6">
                    Localizar
                  </PrimaryButton>
                </div>
                <div>
                  <InputLabel for="nome" value="Razão social" />
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

              <h2 class="mt-4 text-lg">Contato</h2>
              <div class="mt-4 grid md:grid-cols-2 md:gap-6">
                <div>
                  <InputLabel for="telefone" value="Telefone" />
                  <TextInput
                    id="telefone"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.telefone"
                    required
                    autofocus
                    autocomplete="telefone"
                  />
                  <InputError class="mt-2" :message="form.errors.telefone" />
                </div>
              </div>

              <h2 class="mt-4 text-lg">Horários</h2>
              <div class="mt-4 grid md:grid-cols-2 md:gap-6">
                <div>
                  <InputLabel for="horario_funcionamento" value="Horário de funcionamento" />
                  <textarea
                    id="horario_funcionamento"
                    type="text"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600"
                    rows="3"
                    v-model="form.horario_funcionamento"
                    required
                    autofocus
                    autocomplete="horario_funcionamento"
                    placeholder="Digite o horario de funcionamento. Exemplo: Segunda a sexta das 8h às 18h e sábado das 8h às 12h"
                  />
                  <InputError class="mt-2" :message="form.errors.horario_funcionamento" />
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
