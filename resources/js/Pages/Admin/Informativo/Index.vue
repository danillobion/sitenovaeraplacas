<template>
  <AuthenticatedLayout>
    <Head title="Informativo" />

    <div class="max-w-4xl mx-auto mt-10 px-4">

      <!-- CARD PRINCIPAL -->
      <div
        class="bg-white/90 backdrop-blur-xl shadow-xl rounded-3xl border border-gray-100 p-8 relative overflow-hidden"
      >

        <!-- Design de fundo -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
          <div class="absolute -top-10 -right-10 w-40 h-40 bg-red-500 rounded-full blur-3xl"></div>
          <div class="absolute bottom-0 left-0 w-32 h-32 bg-red-700 rounded-full blur-3xl"></div>
        </div>

        <!-- Cabeçalho -->
        <div class="relative mb-8 flex justify-between items-center">
          <h1 class="text-3xl font-extrabold text-gray-800 tracking-tight flex items-center gap-3">
            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-lg text-sm font-bold">
              Marketing
            </span>
            Informativo
          </h1>

          <!-- STATUS -->
          <span
            class="px-4 py-1 text-sm font-semibold rounded-full shadow-sm"
            :class="informativo?.habilitado
              ? 'bg-green-100 text-green-700 border border-green-300'
              : 'bg-gray-100 text-gray-600 border border-gray-300'"
          >
            {{ informativo?.habilitado ? 'Ativo' : 'Desativado' }}
          </span>
        </div>

        <!-- Conteúdo -->
        <div class="relative space-y-6">

          <div>
            <p class="text-sm font-semibold text-gray-700 mb-1">Título</p>
            <p class="text-lg font-bold text-gray-900">
              {{ informativo?.titulo ?? 'Não configurado' }}
            </p>
          </div>

          <!-- Imagem -->
          <div v-if="informativo?.imagem" class="pt-4 flex justify-center">
            <img
              :src="`/storage/${informativo.imagem}`"
              class="w-72 rounded-2xl shadow-lg border border-gray-200 hover:scale-105 transition"
            />
          </div>

        </div>

        <!-- Botão editar -->
        <div class="relative mt-10 text-right">
          <button
            @click="editar"
            class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all"
          >
            Editar Informativo
          </button>
        </div>

      </div>
    </div>

  </AuthenticatedLayout>
</template>

<script setup>
import { router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  informativo: Object,
})

const editar = () => {
  router.get(route('informativo.editar'))
}
</script>
