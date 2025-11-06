<template>
  <AuthenticatedLayout>
    <Head title="Configurar Informativo" />

    <!-- ALERTA PERSONALIZADO FIXO -->
    <transition name="fade">
      <div
        v-if="alerta"
        class="fixed top-4 right-4 z-50 bg-green-500/90 text-white font-semibold px-6 py-3 rounded-xl shadow-lg backdrop-blur-md flex items-center gap-3 animate-slide-in"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
          <path d="M5 13l4 4L19 7" />
        </svg>
        <span>Informativo salvo com sucesso!</span>
      </div>
    </transition>

    <div class="max-w-3xl mx-auto mt-10">
      <div class="bg-white/90 backdrop-blur-xl rounded-2xl shadow-lg p-8 border border-gray-100">

        <!-- Cabeçalho -->
        <div class="flex items-center justify-between mb-8">
          <h1 class="text-3xl font-extrabold text-gray-800 flex items-center gap-2">
             Marketing NovaEra
          </h1>
          <span
            class="text-xs font-semibold px-3 py-1 rounded-full"
            :class="form.habilitado ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'"
          >
            {{ form.habilitado ? 'Ativo' : 'Desativado' }}
          </span>
        </div>

        <!-- FORM -->
        <form @submit.prevent="salvar" class="space-y-6">

          <!-- Título -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Título</label>
            <input
              v-model="form.titulo"
              type="text"
              placeholder="Ex: Promoção de Outubro!"
              class="w-full border border-gray-300 focus:border-red-500 focus:ring-2 focus:ring-red-200 rounded-xl p-3"
            />
          </div>

          <!-- Imagem -->
          <div>
            <label class="block text-sm font-semibold text-gray-700 mb-2">Imagem (opcional)</label>
            <input type="file" accept="image/*" @change="onFileChange"
                   class="block w-full text-sm text-gray-500
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-xl file:border-0
                          file:text-sm file:font-semibold
                          file:bg-red-600 file:text-white
                          hover:file:bg-red-700 transition" />

            <div v-if="preview" class="mt-4 flex justify-center">
              <img :src="preview" class="w-60 h-auto rounded-2xl shadow-md border border-gray-200" />
            </div>
          </div>

          <!-- Habilitar -->
          <div class="flex items-center gap-3 mt-4">
            <label class="relative inline-flex items-center cursor-pointer">
              <input type="checkbox" v-model="form.habilitado" class="sr-only peer" />
              <div
                class="w-11 h-6 bg-gray-300 rounded-full peer
                       peer-checked:bg-green-500
                       after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                       after:bg-white after:border-gray-300 after:border
                       after:rounded-full after:h-5 after:w-5
                       after:transition-all peer-checked:after:translate-x-full"
              ></div>
            </label>
            <span class="text-gray-700 font-medium">Habilitar informativo na tela inicial</span>
          </div>

          <!-- Botão Salvar -->
          <div class="pt-6 text-right">
            <button
              type="submit"
              class="bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold px-6 py-3 rounded-xl shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5"
            >
              Salvar
            </button>
          </div>

        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  informativo: Object,
})

// Form padrão do sistema
const form = useForm({
  titulo: props.informativo?.titulo || '',
  habilitado: !!props.informativo?.habilitado,
  imagem: null,
})

// Preview inicial
const preview = ref(props.informativo?.imagem ? `/storage/${props.informativo.imagem}` : null)

// Alerta
const alerta = ref(false)

// Atualiza preview
const onFileChange = (e) => {
  const file = e.target.files[0]
  if (file) {
    form.imagem = file
    preview.value = URL.createObjectURL(file)
  }
}

// Salvar (nova rota padronizada)
const salvar = () => {
  form.post(route('informativo.salvar'), {
    forceFormData: true,
    onSuccess: () => {
      alerta.value = true
      setTimeout(() => (alerta.value = false), 3000)
    },
  })
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active { transition: opacity 0.4s ease; }
.fade-enter-from,
.fade-leave-to { opacity: 0; }

@keyframes slide-in {
  0% { transform: translateX(100%); opacity: 0; }
  100% { transform: translateX(0); opacity: 1; }
}
.animate-slide-in { animation: slide-in 0.4s ease forwards; }
</style>
