<template>
  <div>
    <!-- Botão flutuante com balão -->
    <div class="fixed bottom-20 right-6 flex items-center space-x-4 z-50">

      <!-- Balão de fala -->
      <div
        v-if="!isOpen"
        class="relative bg-white border border-red-500 text-black px-4 py-3 rounded-xl shadow-xl max-w-xs"
      >
        <p class="text-base leading-snug">
          👋 Olá, eu sou o assistente virtual da <b>Nova Era Placas</b>.<br />
          <span class="font-semibold">Em que posso te ajudar?</span>
        </p>
        <!-- Setinha -->
        <div
          class="absolute -right-2 bottom-9 w-4 h-4 bg-white border-t border-r border-red-500 rotate-45"
        ></div>
      </div>

      <!-- Botão de chat com efeito pulsante -->
  <button
    @click="toggleChat"
    class="relative bg-red-600 hover:bg-red-700 text-white p-5 rounded-full shadow-lg flex items-center justify-center transition transform hover:scale-105"
  >
    <i v-if="!isOpen" class="fas fa-comment-dots text-2xl"></i>
    <i v-else class="fas fa-times text-2xl"></i>

    <!-- Animação de pulso -->
    <span
      class="absolute inset-0 rounded-full bg-red-500 opacity-50 animate-ping z-[-1]"
    ></span>
  </button>
    </div>

    <!-- Chat -->
    <transition name="slide">
      <div
        v-if="isOpen"
        class="fixed bottom-40 right-6 w-96 h-[600px] bg-white rounded-xl shadow-2xl border border-red-500 flex flex-col z-50"
      >
        <!-- Cabeçalho -->
        <div class="bg-red-600 text-white px-4 py-3 rounded-t-xl">
          <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold">Atendente Virtual</h2>
            <button @click="toggleChat" class="text-white text-2xl">
              &times;
            </button>
          </div>
          
          <!-- Status Online -->
          <div class="flex items-center mt-1">
            <div class="relative flex items-center">
              <!-- Efeito pulsante -->
              <span
                class="absolute inline-flex h-3 w-3 rounded-full bg-green-400 opacity-75 animate-ping"
              ></span>
              <!-- Bolinha sólida -->
              <span
                class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-500"
              ></span>
            </div>
            <span class="ml-2 text-sm">Online</span>
          </div>
        </div>

        <!-- Corpo -->
        <div ref="chatBody" class="flex-1 overflow-y-auto p-4 space-y-4 text-lg">
          <div
            v-for="(msg, index) in messages"
            :key="index"
            :class="msg.type === 'bot' ? 'flex items-start' : 'flex justify-end'"
          >
            <img
              v-if="msg.type === 'bot'"
              src="/images/bot.png"
              class="w-9 h-9 rounded-full mr-2"
              alt="Bot"
            />
            <div
              :class="msg.type === 'bot' ? 'bg-gray-100 text-black' : 'bg-red-600 text-white'"
              class="px-4 py-3 rounded-xl max-w-[80%] text-base"
            >
              <div v-if="msg.html" v-html="msg.html"></div>
              <div v-else>{{ msg.text }}</div>
              <div
                class="text-xs mt-1 text-right"
                :class="msg.type === 'bot' ? 'text-gray-500' : 'text-white'"
              >
                {{ msg.time }}
              </div>
            </div>
          </div>

          <!-- Digitando -->
          <div v-if="isTyping" class="flex items-start">
            <img src="/images/bot.png" class="w-9 h-9 rounded-full mr-2 opacity-50" />
            <div class="bg-gray-100 text-black px-4 py-2 rounded-xl max-w-[80%] italic text-sm">
              Digitando<span class="typing-dots">...</span>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script setup>
import { ref, watch, nextTick } from 'vue';

// Estado
const isOpen = ref(false);
const isTyping = ref(false);
const messages = ref([]);
const chatBody = ref(null);

// Horário
const getTime = () => {
  const date = new Date();
  return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};

// Fluxo
const chatFlow = {
  start: {
    text: 'Olá! 👋 Bem-vindo à Nova Era Placas. Como podemos te ajudar hoje? 🚗✨',
    options: [
      { label: 'Sobre as placas Mercosul', next: 'infoPlacas' },
      { label: 'Tirar dúvidas', next: 'duvidas' },
      { label: 'Falar com um atendente', next: 'atendente' },
    ],
  },
  infoPlacas: {
    text: 'Aqui estão as opções de placas que oferecemos na Nova Era: 📋',
    options: [
      { label: 'Unidade Placa Mercosul', next: 'placaMercosulNormal' },
      { label: 'Unidade Mercosul reduzidas', next: 'placaMercosulReducida' },
      { label: 'Par de placas Mercosul ', next: 'parPlacasNormais' },
      { label: 'Par de placas Mercosul reduzidas', next: 'parPlacasReduzidas' },
      { label: 'Placas pretas', next: 'placasPretas' },
      { label: 'Suporte para placas de carro', next: 'suporteCarro' },
      { label: 'Suporte para placas de moto', next: 'suporteMoto' },
      { label: 'Voltar ao início', next: 'start' },
    ],
  },
  placaMercosulNormal: {
    text: '📌 Placa Mercosul padrão: modelo oficial, alta qualidade e durabilidade, ideal para veículos novos.',
    options: [
      { label: 'Mais opções de placas', next: 'infoPlacas' },
      { label: 'Falar com atendente', next: 'atendente' },
    ],
  },
  placaMercosulReducida: {
    text: '📌 Placa Mercosul reduzida: tamanho compacto, perfeita para veículos com espaço limitado.',
    options: [
      { label: 'Mais opções de placas', next: 'infoPlacas' },
      { label: 'Falar com atendente', next: 'atendente' },
    ],
  },
  parPlacasNormais: {
    text: '📌 Par de placas Mercosul: Duas placas para seu veículo, fabricadas com rigor de qualidade.',
    options: [
      { label: 'Mais opções de placas', next: 'infoPlacas' },
      { label: 'Falar com atendente', next: 'atendente' },
    ],
  },
  parPlacasReduzidas: {
    text: '📌 Par de placas Mercosul reduzidas: conjunto de duas placas compactas, para veículos especiais.',
    options: [
      { label: 'Mais opções de placas', next: 'infoPlacas' },
      { label: 'Falar com atendente', next: 'atendente' },
    ],
  },
  placasPretas: {
    text: '🖤 Placas pretas: indicadas para veículos antigos e colecionáveis, com design diferenciado.',
    options: [
      { label: 'Mais opções de placas', next: 'infoPlacas' },
      { label: 'Falar com atendente', next: 'atendente' },
    ],
  },
  suporteCarro: {
    text: '🚗 Suporte para placas de carro: acessórios resistentes para fixação segura e elegante.',
    options: [
      { label: 'Mais opções de placas', next: 'infoPlacas' },
      { label: 'Falar com atendente', next: 'atendente' },
    ],
  },
  suporteMoto: {
    text: '🏍️ Suporte para placas de moto: fixação prática e segura para sua motocicleta.',
    options: [
      { label: 'Mais opções de placas', next: 'infoPlacas' },
      { label: 'Falar com atendente', next: 'atendente' },
    ],
  },

  duvidas: {
    text: '❓ Sobre qual assunto deseja tirar sua dúvida? Escolha uma das opções abaixo:',
    options: [
      { label: 'O documento é liberado na mesma hora?', next: 'duvida1' },
      { label: 'Qual o prazo do emplacamento?', next: 'duvida2' },
      { label: 'Quais os documentos necessários?', next: 'duvida3' },
      { label: 'Quais os métodos de pagamento?', next: 'duvida4' },
      { label: 'Posso agendar o emplacamento?', next: 'duvida5' },
      { label: 'posso fazer a instalação?', next: 'duvida6' },
      { label: 'As placas são resistentes?', next: 'duvida7' },
      { label: 'Fazemos placas para motos?', next: 'duvida8' },
      { label: 'Vocês oferecem garantia?', next: 'duvida9' },
      { label: 'Voltar ao início', next: 'start' },
    ],
  },

  duvida1: {
    text: '📝 Sim, o documento é liberado após a finalização do procedimento!',
    options: [
      { label: 'Voltar ao menu de dúvidas', next: 'duvidas' },
      { label: 'Falar com atendente', next: 'atendente' },
    ],
  },
  duvida2: {
    text: '⏳ O prazo médio do emplacamento é de 1 a 2 dias úteis após confirmação do pagamento.',
    options: [
      { label: 'Voltar ao menu de dúvidas', next: 'duvidas' },
      { label: 'Falar com atendente', next: 'atendente' },
    ],
  },
  duvida3: {
    text: '📄 Você precisará da autorização de estampagem para a confecção das placas (a).',
    options: [
      { label: 'Voltar ao menu de dúvidas', next: 'duvidas' },
      { label: 'Falar com atendente', next: 'atendente' },
    ],
  },
  duvida4: {
    text: 'Aceitamos boleto e pix para facilitar sua compra.',
    options: [
      { label: 'Voltar ao menu de dúvidas', next: 'duvidas' },
      { label: 'Falar com atendente', next: 'atendente' },
    ],
  },
  duvida5: {
    text: ' Sim, pode agendar o dia do emplacamento do veículo',
    options: [
      { label: 'Voltar ao menu de dúvidas', next: 'duvidas' },
      { label: 'Falar com atendente', next: 'atendente' },
    ],
  },
  duvida6: {
    text: '🔧Não. A instalação deve ser feita por um profissional credenciado, pois o DETRAN exige validações de segurança antes de emitir o CRLV.',
    options: [
      { label: 'Voltar ao menu de dúvidas', next: 'duvidas' },
      { label: 'Falar com atendente', next: 'atendente' },
    ],
  },
  duvida7: {
    text: '💪 Nossas placas são produzidas com material resistente para garantir durabilidade e qualidade.',
    options: [
      { label: 'Voltar ao menu de dúvidas', next: 'duvidas' },
      { label: 'Falar com atendente', next: 'atendente' },
    ],
  },
  duvida8: {
    text: '🏍️ Sim! Produzimos placas e fornecemos suportes específicos para motos.',
    options: [
      { label: 'Voltar ao menu de dúvidas', next: 'duvidas' },
      { label: 'Falar com atendente', next: 'atendente' },
    ],
  },
  duvida9: {
    text: '🛡️ Oferecemos 5 anos de garantia!',
    options: [
      { label: 'Voltar ao menu de dúvidas', next: 'duvidas' },
      { label: 'Falar com atendente', next: 'atendente' },
    ],
  },

  atendente: {
    text: '📲 Para falar com um atendente humano, clique no link abaixo:',
    link: {
      label: '👉 Falar com Atendente',
      url: 'https://api.whatsapp.com/send/?phone=559884209099&text&type=phone_number&app_absent=0',
    },
    options: [
      { label: 'Voltar ao início', next: 'start' },
    ],
  },
};

// Estado atual
const currentNodeKey = ref('start');
const currentNode = ref(chatFlow[currentNodeKey.value]);

// Ações
const toggleChat = () => {
  isOpen.value = !isOpen.value;
  if (isOpen.value && messages.value.length === 0) {
    startChat();
  }
};

const startChat = () => {
  messages.value = [];
  currentNodeKey.value = 'start';
  currentNode.value = chatFlow.start;
  sendBotMessage(currentNode.value);
};

const sendBotMessage = (node) => {
  const time = getTime();
  let html = `<div>${node.text}</div>`;

  if (node.link) {
    html += `<div class="mt-2">
      <a href="${node.link.url}" target="_blank" class="text-red-600 underline font-semibold">${node.link.label}</a>
    </div>`;
  }

  if (node.options) {
    html += `<div class="flex flex-wrap gap-2 mt-3">`;
    node.options.forEach((opt) => {
      html += `<button
        class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-xl text-sm"
        onclick="document.dispatchEvent(new CustomEvent('option-click', { detail: '${opt.label}' }))"
      >
        ${opt.label}
      </button>`;
    });
    html += `</div>`;
  }

  messages.value.push({ type: 'bot', html, time });
  scrollToBottom();
};

const addUserMessage = (text) => {
  messages.value.push({ type: 'user', text, time: getTime() });
  scrollToBottom();
};

const handleOption = (option) => {
  addUserMessage(option.label);
  isTyping.value = true;
  scrollToBottom();

  setTimeout(() => {
    currentNodeKey.value = option.next;
    currentNode.value = chatFlow[currentNodeKey.value];

    sendBotMessage(currentNode.value);
    isTyping.value = false;
    scrollToBottom();
  }, 1000);
};

// Scroll automático
const scrollToBottom = () => {
  nextTick(() => {
    if (chatBody.value) {
      chatBody.value.scrollTop = chatBody.value.scrollHeight;
    }
  });
};

watch(messages, () => scrollToBottom());

// Escuta clique de botão dentro do HTML
document.addEventListener('option-click', (e) => {
  const label = e.detail;
  const option = currentNode.value.options.find((o) => o.label === label);
  if (option) handleOption(option);
});
</script>

<style scoped>
/* Animação */
.slide-enter-active,
.slide-leave-active {
  transition: all 0.3s ease;
}
.slide-enter-from,
.slide-leave-to {
  transform: translateY(20px);
  opacity: 0;
}

/* Animação "digitando..." */
.typing-dots::after {
  content: '.';
  animation: blink 1.5s infinite;
  margin-left: 2px;
}
.typing-dots::before {
  content: '.';
  animation: blink 1.5s infinite 0.3s;
  margin-left: 2px;
}
.typing-dots {
  animation: blink 1.5s infinite 0.6s;
}

@keyframes blink {
  0%, 20% { opacity: 0; }
  50% { opacity: 1; }
  100% { opacity: 0; }
}
</style>
