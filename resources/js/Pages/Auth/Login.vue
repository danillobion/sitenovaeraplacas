<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
  <div class="min-h-screen flex flex-col bg-gray-100 pt-16">
    <!-- Navbar -->
    <nav class="fixed w-full top-0 z-50 backdrop-blur-md shadow-lg h-16 bg-white">
      <div class="container mx-auto flex justify-between items-center py-4 px-6">
        <div class="logo-container flex justify-center items-center">
          <img src="/images/logo.jpg" alt="Logo da Empresa" class="w-16 md:w-20 h-auto" />
        </div>
        <ul class="hidden md:flex space-x-12 text-lg font-bold">
          <li>
            <button
              @click="redirectToHome"
              class="hidden md:block bg-red-500 text-white py-2 px-4 rounded-lg hover:bg-red-700 flex items-center space-x-2">
              Voltar ao início
            </button>
          </li>
        </ul>
      </div>
    </nav>

    <!-- Main Content -->
    <div class="flex-grow flex items-center justify-center z-10">
      <div class="flex flex-col md:flex-row w-full max-w-lg md:max-w-4xl border-2 border-red-500 rounded-lg overflow-hidden shadow-lg">
        <!-- Login Card -->
        <div class="w-full md:w-1/2 p-6 sm:p-8 md:p-10 bg-white">
          <div class="mb-6 text-center">
            <img src="/images/logo.jpg" alt="Logo" class="w-28 sm:w-36 mx-auto mb-4" />
            <h2 class="text-xl sm:text-2xl font-bold text-red-500">Bem-vindo de volta!</h2>
          </div>

          <form @submit.prevent="submit">
            <div class="mb-4">
              <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
              <input
                type="email"
                id="email"
                v-model="form.email"
                class="mt-2 w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500"
                required
                autofocus
                autocomplete="username"
              />
              <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mb-6">
              <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
              <input
                type="password"
                id="password"
                v-model="form.password"
                class="mt-2 w-full p-3 border border-gray-300 rounded-md focus:ring-2 focus:ring-red-500"
                required
                autocomplete="current-password"
              />
              <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <button
              type="submit"
              class="w-full p-3 bg-red-500 text-white font-bold rounded-md transition duration-300 hover:bg-red-600 focus:outline-none transform hover:scale-105"
            >
              Entrar
            </button>
          </form>

          <div class="mt-4 text-center">
            <p class="text-sm text-gray-500">Acesso somente para pessoas autorizadas</p>
          </div>
        </div>

        <!-- Side Image -->
        <div class="w-full md:w-1/2 hidden md:block">
          <img src="/images/chip.png" alt="Side Image" class="w-full h-full object-cover" />
        </div>
      </div>
    </div>

    <!-- Footer -->
    <footer class="bg-red-600 text-white py-6 w-full">
      <div class="container mx-auto flex flex-col md:flex-row justify-between items-center px-6">
        <p class="text-sm text-center md:text-left">
          © {{ new Date().getFullYear() }} Nova Era Placas - Todos os direitos reservados.
        </p>
        <div class="flex space-x-4 mt-4 md:mt-0">
          <a href="https://www.instagram.com/novaera.placass/" target="_blank" class="hover:text-gray-300">
            <i class="fab fa-instagram text-2xl"></i>
          </a>
          <a href="https://www.facebook.com/people/Nova-Era-Placas/61559921292385/" target="_blank" class="hover:text-gray-300">
            <i class="fab fa-facebook text-2xl"></i>
          </a>
          <a href="https://wa.me/559884209099" target="_blank" class="hover:text-gray-300">
            <i class="fab fa-whatsapp text-2xl"></i>
          </a>
          <a href="mailto:novaeraplacas@yahoo.com" class="hover:text-gray-300">
            <i class="fas fa-envelope text-2xl"></i>
          </a>
        </div>
      </div>
    </footer>
  </div>
</template>



<script>
export default {
  data() {
    return {
      email: '',
      password: '',
    };
  },
  methods: {
    submitLogin() {
      // Aqui você pode fazer a chamada de login via API Laravel
      console.log('Email:', this.email);
      console.log('Password:', this.password);
    },
    scrollToSection(event, sectionId) {
      event.preventDefault();
      const section = document.getElementById(sectionId);
      if (section) {
        section.scrollIntoView({ behavior: 'smooth' });
      }
    },
    redirectToHome() {
    window.location.href = '/';
  }
  }
};

</script>

<style scoped>

@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css");

/* Estilos personalizados para as bolinhas flutuantes */
@keyframes ping {
  0% {
    transform: scale(0);
    opacity: 1;
  }
  75% {
    transform: scale(1);
    opacity: 0.4;
  }
  100% {
    transform: scale(1.5);
    opacity: 0;
  }
}

/* Adiciona animação de ping nas bolinhas */
.animate-ping {
  animation: ping 4s infinite;
}

body, html {
  margin: 0;
  padding: 0;
  height: 100%;
  overflow: hidden;
}

/* Garantir que o conteúdo ocupe 100% da altura da tela */
.h-screen {
  height: 100vh; /* 100% da altura da tela */
}

footer {
  position: relative;
  bottom: 0;
  width: 100%;
}

/* Ajustes responsivos para mobile e tablet */
@media (max-width: 768px) {
  .md\:w-1\/2 {
    width: 100%;
  }

  .md\:hidden {
    display: block;
  }

  .mt-24 {
    margin-top: 2rem;
  }

  footer {
    margin-top: 2rem;
  }
}
</style>