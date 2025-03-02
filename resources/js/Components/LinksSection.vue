<template>
    <section class="bg-white py-10 px-5 md:px-10 rounded-2xl shadow-lg border border-red-500">
      <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Explore Mais</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        <a
          v-for="link in links"
          :key="link.url"
          :href="link.url"
          target="_blank"
          class="flex items-center justify-center p-4 bg-red-500 text-white font-semibold rounded-lg shadow-md hover:bg-red-600 transition-all duration-300 transform hover:scale-105"
        >
          {{ link.label }}
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
    background: linear-gradient(135deg, rgba(255,255,255,1) 0%, rgba(255,0,0,0.1) 100%);
  }
  </style>
  