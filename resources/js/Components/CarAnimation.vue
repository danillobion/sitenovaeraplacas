<template>
    <div class="absolute bottom-0 left-0 w-full h-32 bg-white-200 overflow-hidden">
      <img 
        src="../../../public/images/carro-vermelho.png" 
        alt="Carro vermelho"
        ref="car"
        class="absolute bottom-4 left-0 w-32 md:w-96 lg:w-[600px]"
      />
    </div>
  </template>
  
  <script setup>
  import { onMounted, ref } from "vue";
  
  const car = ref(null);
  
  onMounted(() => {
    let position = -600; // Menor para começar dentro da tela, maior para começar fora 
    const speed = 2; // Velocidade do carro
  
    function moveCar() {
      if (position > window.innerWidth) {
        position = -600; // Reinicia quando sai da tela ( deixei o mesmo do início para dar continuidade )
      }
      position += speed;
      car.value.style.transform = `translateX(${position}px)`;
      requestAnimationFrame(moveCar);
    }
  
    moveCar();
  });
  </script>