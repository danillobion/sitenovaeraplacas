<template>
  <section class="py-12 px-6 md:px-12 rounded-3xl shadow-2xl animate-gradient-x">
    <h2 class="text-3xl md:text-4xl font-extrabold text-white text-center mb-10">
      Explore Mais
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
      <a
        v-for="link in links"
        :key="link.url"
        :href="link.url"
        target="_blank"
        class="relative overflow-hidden group flex flex-col items-center justify-center p-6 bg-white text-gray-800 font-semibold rounded-2xl shadow-lg border border-gray-300 transition-all duration-500 transform hover:scale-105 cursor-pointer"
      >
        <!-- Fundo líquido animado -->
        <span
          class="absolute inset-0 bg-red-500 rounded-2xl transform scale-y-0 origin-bottom transition-transform duration-500 ease-in-out group-hover:scale-y-100"
        ></span>

        <!-- Conteúdo na frente -->
        <span class="relative z-10 flex flex-col items-center transition-colors duration-300 group-hover:text-white">
          <i :class="link.icon" class="text-4xl md:text-5xl mb-3"></i>
          <span class="text-lg md:text-xl font-bold">{{ link.label }}</span>
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
/* Gradiente animado de fundo */
@keyframes gradient-x {
  0% { background-position: 0% 50%; }
  50% { background-position: 100% 50%; }
  100% { background-position: 0% 50%; }
}

.animate-gradient-x {
  background: linear-gradient(135deg, #ff416c, #ff4b2b, #f9d423, #4facfe);
  background-size: 200% 200%;
  animation: gradient-x 12s ease infinite;
}
</style>
