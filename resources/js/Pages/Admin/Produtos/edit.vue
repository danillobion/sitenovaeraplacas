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
  produto: Object,
});

const form = useForm({
  id: null,
  nome: '',
  descricao: '',
  valor: '',
  imagem: null,
  exibir_valor: false
});

const imagePreview = ref(null);

const produtoFields = [
  { id: 'nome', label: 'Nome', model: 'nome' },
  { id: 'descricao', label: 'Descrição', model: 'descricao' },
  { id: 'valor', label: 'Valor', model: 'valor' }
];

const submit = () => {
  const formData = new FormData();

  if(form.id != undefined){
    formData.append("id",props.produto.id);
  }
  formData.append("nome", form.nome);
  formData.append("descricao", form.descricao);
  formData.append("valor", form.valor);
  formData.append("exibir_valor", form.exibir_valor);

  if (form.imagem) {
    formData.append("imagem", form.imagem);
  } else {
    console.warn("Nenhuma imagem foi selecionada.");
  }

  axios.post(route('produto.salvar'), formData, {
    headers: {
      'Content-Type': 'multipart/form-data'
    }
  })
  .then(() => {
    alert('Produto salvo com sucesso!');
    window.location.href = route('produtos.index');
  })
  .catch(error => {
    console.error("Erro ao salvar produto:", error);
    alert('Erro ao salvar produto.');
  });
};


const handleImageUpload = (event) => {
  const file = event.target.files[0];

  if (file) {
    form.imagem = file;
    imagePreview.value = URL.createObjectURL(file);
  }
};

onMounted(() => {
  if(props.produto){
    form.id = props.produto.id;
    form.nome = props.produto.nome;
    form.descricao = props.produto.descricao;
    form.valor = props.produto.valor;
    form.imagem = props.produto.imagem;
    form.exibir_valor = props.produto.exibir_valor == 1 ? true : false;
  }
  if (props.produto.imagem) {
    imagePreview.value = `/${props.produto.imagem}`;
  }
});
</script>

<template>
  <Head title="Editar" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Editar produto</h2>
    </template>

    <div class="py-1">
      <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
          <div class="p-6 text-gray-900 dark:text-gray-100">

            <form @submit.prevent="submit" enctype="multipart/form-data">
              <h2 class="mb-4 text-lg">Informações</h2>
              <img v-if="imagePreview" :src="imagePreview || `/${form.imagem}`" 
                  alt="Imagem do Produto" 
                  class="mt-4 w-40 h-40 object-cover rounded-lg shadow-md">              
                  
              <div class="mt-4 grid md:grid-cols-2 md:gap-6">
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
                <div v-for="field in produtoFields" :key="field.id">
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
                <div class="flex mt-7">
                  <input
                    id="exibir_valor"
                    type="checkbox"
                    class="mr-1 rounded-md border-gray-300 dark:border-gray-700 dark:focus:border-indigo-600"
                    v-model="form.exibir_valor"
                  />
                  <InputLabel for="exibir_valor" value="Exibir o valor na tela?" />
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
