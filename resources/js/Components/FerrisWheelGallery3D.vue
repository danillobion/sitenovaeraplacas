<template>
  <section class="w-full py-20 bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 text-white">
    <h2 class="text-2xl md:text-3xl font-bold text-center mb-12 px-4">
      Galeria de Eventos Nova Era Placas
      <span class="text-yellow-400 font-bold">Caxias do Sul - RS</span>
    </h2>

    <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-center gap-12">

      <!-- === Coverflow 3D === -->
      <div class="w-full md:w-1/2 flex justify-center items-center overflow-hidden">
        <div class="relative w-full h-64 sm:h-80 md:h-96 perspective">
          <div
            v-for="(img, i) in images"
            :key="i"
            class="absolute top-1/2 left-1/2 transform transition-all duration-500 cursor-pointer rounded-lg shadow-2xl"
            :style="coverflowStyle(i)"
            @click="selectImage(i)"
          >
            <img
              :src="img"
              :alt="'Imagem ' + (i + 1)"
              class="w-24 sm:w-32 md:w-48 h-24 sm:h-32 md:h-48 object-cover rounded-lg"
            />
          </div>
        </div>
      </div>

      <!-- === Preview maior === -->
      <div v-if="activeIndex !== null" class="w-full md:w-1/2 flex justify-center items-center mt-6 md:mt-0">
        <div class="relative w-full flex justify-center">
          <img
            :src="images[activeIndex]"
            class="w-full max-w-xs sm:max-w-sm md:max-w-md lg:max-w-lg object-contain rounded-xl shadow-3xl transition-transform duration-300 hover:scale-105 z-50"
          />
        </div>
      </div>

    </div>
  </section>
</template>

<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue'

const images = ref([
  '/images/evento1.jpg',
  '/images/evento2.jpg',
  '/images/evento3.jpg',
  '/images/evento4.jpg',
  '/images/evento5.jpg',
  '/images/evento6.jpg',
  '/images/evento7.jpg',
  '/images/evento8.jpg',
  '/images/evento9.jpg',
  '/images/evento10.jpg',
  '/images/evento11.jpg',
])

const activeIndex = ref(0)
let interval = null
const rotationDelay = 4000

function selectImage(i) {
  activeIndex.value = i
  resetInterval()
}

function coverflowStyle(i) {
  const total = images.value.length
  let offset = i - activeIndex.value
  if (offset > total / 2) offset -= total
  if (offset < -total / 2) offset += total

  const absOffset = Math.abs(offset)
  const scale = offset === 0 ? 1.3 : 1 - absOffset * 0.15
  const rotateY = offset * 30
  const translateX = offset * 90
  const translateZ = -absOffset * 40
  const zIndex = offset === 0 ? 1000 : 100 - absOffset

  return {
    transform: `translateX(${translateX}px) translateZ(${translateZ}px) rotateY(${rotateY}deg) scale(${scale})`,
    zIndex: zIndex,
  }
}

function startInterval() {
  interval = setInterval(() => {
    activeIndex.value = (activeIndex.value + 1) % images.value.length
  }, rotationDelay)
}

function resetInterval() {
  clearInterval(interval)
  startInterval()
}

onMounted(() => startInterval())
onBeforeUnmount(() => clearInterval(interval))
</script>

<style scoped>
.perspective {
  perspective: 1200px;
}
</style>
