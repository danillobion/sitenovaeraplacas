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
  estampadora_id: Number,
});

const form = useForm({
  id: null,
  estampadora_id: null,
  nome: '',
  descricao: '',
  valor: '',
});

const produtoFields = [
  { id: 'nome', label: 'Nome', model: 'nome' },
  { id: 'descricao', label: 'Descrição', model: 'descricao' },
  { id: 'valor', label: 'Valor', model: 'valor' }
];

const submit = () => {
  form.post(route('produto.salvar'), {
    onFinish: () => form.reset(),
  });
};

onMounted(() => {
  if(props.produto){
    form.id = props.produto.id;
    form.nome = props.produto.nome;
    form.descricao = props.produto.descricao;
    form.valor = props.produto.valor;
  }
  form.estampadora_id = props.estampadora_id;
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

            <form @submit.prevent="submit">
              <h2 class="mb-4 text-lg">Informações</h2>

              <div class="mt-4 grid md:grid-cols-2 md:gap-6">
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
