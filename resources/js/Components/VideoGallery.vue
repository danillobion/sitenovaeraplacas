<template>
  <section class="w-full py-16 bg-gradient-to-b from-gray-200 to-white">
    <div class="max-w-7xl mx-auto px-4">
      <!-- Título -->
      <h2 class="text-3xl font-bold text-center text-black-700 mb-10">
        Galeria de Vídeos
      </h2>

      <!-- Container da galeria -->
      <div
        ref="scrollContainer"
        class="flex space-x-6 overflow-x-auto scroll-smooth scrollbar-hide py-4 px-2"
      >
        <div
          v-for="(video, index) in videos"
          :key="index"
          class="min-w-[500px] md:min-w-[600px] bg-white rounded-xl shadow-lg p-6 hover:scale-105 transition-transform duration-300 ease-in-out"
        >
          <!-- iframe do YouTube -->
          <iframe
            :src="video.src"
            frameborder="0"
            allowfullscreen
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
            class="w-full h-[300px] md:h-[350px] rounded-md"
          ></iframe>
          
          <p class="mt-4 text-center text-gray-700 font-medium text-lg">
            {{ video.description }}
          </p>
        </div>
      </div>

      <!-- Botões de navegação -->
      <div class="flex justify-center mt-6 gap-4">
        <!-- Botão Esquerda -->
        <button
          @click="scrollLeft"
          class="bg-red-600 hover:bg-red-700 text-white p-3 rounded-full shadow-lg transition flex items-center justify-center"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M13.707 15.707a1 1 0 01-1.414 0L5.586 10l6.707-5.707a1 1 0 111.414 1.414L8.414 10l5.293 4.293a1 1 0 010 1.414z" />
          </svg>
        </button>

        <!-- Botão Direita -->
        <button
          @click="scrollRight"
          class="bg-red-600 hover:bg-red-700 text-white p-3 rounded-full shadow-lg transition flex items-center justify-center"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
            <path d="M6.293 4.293a1 1 0 011.414 0L14.414 10l-6.707 5.707a1 1 0 01-1.414-1.414L11.586 10 6.293 5.707a1 1 0 010-1.414z" />
          </svg>
        </button>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  name: "VideoGallery",
  data() {
    return {
        videos: [
    {
      src: "https://www.youtube.com/embed/8_FPRSKYb2A",
      description: "Nova Era Placas"
    },
    {
      src: "https://www.youtube.com/embed/P2aDG0vUY4g",
      description: "Atendimento Automatizado"
    },
    {
      src: "https://www.youtube.com/embed/H2hYe1UToDM",
      description: "Segurança e Qualidade"
    },
    {
      src: "https://www.youtube.com/embed/-1TxSz1iOQA",
      description: "Onde Estamos?"
    },
    {
      src: "https://www.youtube.com/embed/_9tjph-zce0",
      description: "Mais do que uma placa"
    }
  ]
    };
  },
  methods: {
    scrollLeft() {
      this.$refs.scrollContainer.scrollBy({ left: -400, behavior: "smooth" });
    },
    scrollRight() {
      this.$refs.scrollContainer.scrollBy({ left: 400, behavior: "smooth" });
    },
  },
};
</script>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
