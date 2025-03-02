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
  <div class="min-h-screen flex items-center justify-center bg-white relative overflow-hidden">
    <!-- Background Balls -->
    <div class="absolute inset-0 z-0">
      <div class="absolute animate-ping bg-red-300 rounded-full w-24 h-24 opacity-40" style="top: 10%; left: 10%;"></div>
      <div class="absolute animate-ping bg-red-500 rounded-full w-16 h-16 opacity-30" style="top: 40%; left: 80%;"></div>
      <div class="absolute animate-ping bg-red-400 rounded-full w-20 h-20 opacity-50" style="top: 60%; left: 30%;"></div>
      <div class="absolute animate-ping bg-red-600 rounded-full w-18 h-18 opacity-60" style="top: 80%; left: 60%;"></div>
    </div>

    <!-- Main Content (Login Card + Image) -->
    <div class="flex items-center justify-center w-full max-w-screen-xl p-6 z-10">
      <!-- Login Card + Side Image Wrapper -->
      <div class="flex w-full max-w-6xl border-2 border-red-500 rounded-lg overflow-hidden">
        <!-- Login Card -->
        <div class="w-1/2 p-10 bg-white shadow-lg">
          <div class="mb-6 text-center">
            <img src="../../../../public/images/logo.jpg" alt="Logo" class="w-36 mx-auto mb-4" />
            <h2 class="text-2xl font-bold text-red-500">Bem-vindo de volta!</h2>
          </div>

          <!-- Login Form -->
          <form @submit.prevent="submit">
            <!-- Email -->
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

            <!-- Password -->
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

            <!-- Submit Button -->
            <button
              type="submit"
              class="w-full p-3 bg-red-500 text-white font-bold rounded-md transition duration-300 hover:bg-red-600 focus:outline-none transform hover:scale-105"
            >
              Entrar
            </button>
          </form>

          <!-- Footer Section -->
          <div class="mt-4 text-center">
            <p class="text-sm text-gray-500">Acesso somente para pessoas autorizadas</p>
          </div>
        </div>

        <!-- Side Image -->
        <div class="w-1/2 h-full">
          <img src="../../../../public/images/chip.png" alt="Side Image" class="w-full h-full object-cover" />
        </div>
      </div>
    </div>
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
    }
  }
};
</script>

<style scoped>
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
</style>