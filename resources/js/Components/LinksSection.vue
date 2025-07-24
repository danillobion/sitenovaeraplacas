<template>
  <section class="py-12 px-6 md:px-12 bg-gray-50 rounded-2xl shadow-xl border border-red-500">
    <h2 class="text-3xl font-extrabold text-gray-800 text-center mb-8">
      Explore Mais
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
     <a
  v-for="link in links"
  :key="link.url"
  :href="link.url"
  target="_blank"
  class="relative overflow-hidden group flex flex-col items-center justify-center p-5 bg-white text-gray-800 font-semibold rounded-xl shadow-md border border-gray-300 transition-all duration-300 transform hover:scale-105 cursor-pointer"
>
  <!-- Retângulo vermelho que cobre o card no hover -->
  <span
    class="absolute bottom-0 left-0 w-0 h-0 bg-red-500 z-0 transition-all duration-500 ease-out group-hover:w-full group-hover:h-full"
  ></span>

  <!-- Conteúdo (fica na frente do fundo animado) -->
  <span class="relative z-10 flex flex-col items-center transition-colors duration-300 group-hover:text-white">
    <i :class="link.icon" class="text-3xl mb-2"></i>
    {{ link.label }}
  </span>
</a>

    </div>
  </section>
</template>

<script>
export default {
  props: {
    links: {
      type: Array,
      required: true,
    },
  },
};
</script>

<style scoped>
section {
  background: linear-gradient(135deg, #fe3f3f 0%, #f9fafb 100%);
}
</style>
