<script setup>
import { CheckCheck, MessageCircle, Send, X } from 'lucide-vue-next';
import { computed, nextTick, onBeforeUnmount, ref } from 'vue';

const isOpen = ref(false);
const isTyping = ref(false);
const messages = ref([]);
const chatBody = ref(null);
const mensagemDigitada = ref('');
const currentNodeKey = ref('start');
let responseTimer = null;

const chatFlow = {
    start: {
        text: 'Olá! Bem-vindo à Nova Era Placas. Como podemos ajudar você hoje?',
        options: [
            { label: 'Conhecer as placas Mercosul', next: 'infoPlacas' },
            { label: 'Tirar uma dúvida', next: 'duvidas' },
            { label: 'Falar com um atendente', next: 'atendente' },
        ],
    },
    infoPlacas: {
        text: 'Temos opções para carros, motos, veículos especiais e colecionadores. Qual produto você procura?',
        options: [
            { label: 'Placa Mercosul para carro', next: 'placaMercosulNormal' },
            { label: 'Placa Mercosul reduzida', next: 'placaMercosulReducida' },
            { label: 'Par de placas Mercosul', next: 'parPlacasNormais' },
            { label: 'Par de placas reduzidas', next: 'parPlacasReduzidas' },
            { label: 'Placas pretas', next: 'placasPretas' },
            { label: 'Suporte para carro', next: 'suporteCarro' },
            { label: 'Suporte para moto', next: 'suporteMoto' },
            { label: 'Voltar ao início', next: 'start' },
        ],
    },
    placaMercosulNormal: {
        text: 'A placa Mercosul padrão segue o modelo oficial e é produzida com materiais de alta qualidade e durabilidade.',
        options: [
            { label: 'Ver outros produtos', next: 'infoPlacas' },
            { label: 'Falar com um atendente', next: 'atendente' },
        ],
    },
    placaMercosulReducida: {
        text: 'A placa Mercosul reduzida possui formato compacto para veículos que têm espaço limitado para instalação.',
        options: [
            { label: 'Ver outros produtos', next: 'infoPlacas' },
            { label: 'Falar com um atendente', next: 'atendente' },
        ],
    },
    parPlacasNormais: {
        text: 'O par Mercosul inclui as placas dianteira e traseira, fabricadas conforme as exigências e padrões de qualidade.',
        options: [
            { label: 'Ver outros produtos', next: 'infoPlacas' },
            { label: 'Falar com um atendente', next: 'atendente' },
        ],
    },
    parPlacasReduzidas: {
        text: 'O par reduzido reúne duas placas compactas, indicado para veículos que comportam esse formato específico.',
        options: [
            { label: 'Ver outros produtos', next: 'infoPlacas' },
            { label: 'Falar com um atendente', next: 'atendente' },
        ],
    },
    placasPretas: {
        text: 'As placas pretas são destinadas a veículos antigos, de coleção ou enquadrados nas condições previstas pela regulamentação.',
        options: [
            { label: 'Ver outros produtos', next: 'infoPlacas' },
            { label: 'Falar com um atendente', next: 'atendente' },
        ],
    },
    suporteCarro: {
        text: 'Temos suportes resistentes para uma fixação segura e um acabamento adequado em automóveis.',
        options: [
            { label: 'Ver outros produtos', next: 'infoPlacas' },
            { label: 'Falar com um atendente', next: 'atendente' },
        ],
    },
    suporteMoto: {
        text: 'Também oferecemos suportes próprios para motocicletas, com instalação prática e segura.',
        options: [
            { label: 'Ver outros produtos', next: 'infoPlacas' },
            { label: 'Falar com um atendente', next: 'atendente' },
        ],
    },
    duvidas: {
        text: 'Claro. Selecione abaixo o assunto sobre o qual você deseja saber mais.',
        options: [
            { label: 'Liberação do documento', next: 'duvida1' },
            { label: 'Prazo do emplacamento', next: 'duvida2' },
            { label: 'Documentos necessários', next: 'duvida3' },
            { label: 'Formas de pagamento', next: 'duvida4' },
            { label: 'Agendamento', next: 'duvida5' },
            { label: 'Instalação da placa', next: 'duvida6' },
            { label: 'Resistência das placas', next: 'duvida7' },
            { label: 'Placas para motos', next: 'duvida8' },
            { label: 'Garantia', next: 'duvida9' },
            { label: 'Voltar ao início', next: 'start' },
        ],
    },
    duvida1: {
        text: 'O documento é liberado após a finalização e validação do procedimento de emplacamento.',
        options: [
            { label: 'Ver outras dúvidas', next: 'duvidas' },
            { label: 'Falar com um atendente', next: 'atendente' },
        ],
    },
    duvida2: {
        text: 'O prazo pode variar conforme a autorização, o pagamento e as validações necessárias para o veículo.',
        options: [
            { label: 'Ver outras dúvidas', next: 'duvidas' },
            { label: 'Falar com um atendente', next: 'atendente' },
        ],
    },
    duvida3: {
        text: 'É necessário apresentar a autorização de estampagem e os documentos solicitados para o procedimento.',
        options: [
            { label: 'Ver outras dúvidas', next: 'duvidas' },
            { label: 'Falar com um atendente', next: 'atendente' },
        ],
    },
    duvida4: {
        text: 'Aceitamos cartão de débito, crédito, boleto bancário e PIX. Os pagamentos precisam ser rastreáveis.',
        options: [
            { label: 'Ver outras dúvidas', next: 'duvidas' },
            { label: 'Falar com um atendente', next: 'atendente' },
        ],
    },
    duvida5: {
        text: 'Sim. Entre em contato com a unidade mais próxima para verificar os horários disponíveis para atendimento.',
        options: [
            { label: 'Ver outras dúvidas', next: 'duvidas' },
            { label: 'Falar com um atendente', next: 'atendente' },
        ],
    },
    duvida6: {
        text: 'A instalação deve ser realizada por um profissional credenciado, pois o procedimento exige validações de segurança.',
        options: [
            { label: 'Ver outras dúvidas', next: 'duvidas' },
            { label: 'Falar com um atendente', next: 'atendente' },
        ],
    },
    duvida7: {
        text: 'Sim. Nossas placas são produzidas com materiais selecionados para oferecer resistência, qualidade e durabilidade.',
        options: [
            { label: 'Ver outras dúvidas', next: 'duvidas' },
            { label: 'Falar com um atendente', next: 'atendente' },
        ],
    },
    duvida8: {
        text: 'Sim. Produzimos placas e fornecemos suportes específicos para motocicletas.',
        options: [
            { label: 'Ver outras dúvidas', next: 'duvidas' },
            { label: 'Falar com um atendente', next: 'atendente' },
        ],
    },
    duvida9: {
        text: 'Nossos produtos possuem garantia. Um atendente pode confirmar as condições aplicáveis ao produto escolhido.',
        options: [
            { label: 'Ver outras dúvidas', next: 'duvidas' },
            { label: 'Falar com um atendente', next: 'atendente' },
        ],
    },
    atendente: {
        text: 'Vou direcionar você para nossa equipe. Toque no botão abaixo para iniciar o atendimento pelo WhatsApp.',
        link: {
            label: 'Conversar no WhatsApp',
            url: 'https://api.whatsapp.com/send/?phone=559884209099&text&type=phone_number&app_absent=0',
        },
        options: [{ label: 'Voltar ao início', next: 'start' }],
    },
};

const currentNode = computed(() => chatFlow[currentNodeKey.value] ?? chatFlow.start);
const quickOptions = computed(() => (isTyping.value ? [] : currentNode.value.options ?? []));

const getTime = () =>
    new Date().toLocaleTimeString('pt-BR', {
        hour: '2-digit',
        minute: '2-digit',
    });

const scrollToBottom = () => {
    nextTick(() => {
        if (chatBody.value) {
            chatBody.value.scrollTo({
                top: chatBody.value.scrollHeight,
                behavior: 'smooth',
            });
        }
    });
};

const addBotMessage = (node) => {
    messages.value.push({
        type: 'bot',
        text: node.text,
        link: node.link ?? null,
        time: getTime(),
    });
    scrollToBottom();
};

const addUserMessage = (text) => {
    messages.value.push({
        type: 'user',
        text,
        time: getTime(),
    });
    scrollToBottom();
};

const startChat = () => {
    currentNodeKey.value = 'start';
    messages.value = [];
    addBotMessage(chatFlow.start);
};

const toggleChat = () => {
    isOpen.value = !isOpen.value;

    if (isOpen.value && messages.value.length === 0) {
        startChat();
    }
};

const responderDepois = (nodeKey) => {
    window.clearTimeout(responseTimer);
    isTyping.value = true;
    scrollToBottom();

    responseTimer = window.setTimeout(() => {
        currentNodeKey.value = nodeKey;
        addBotMessage(chatFlow[nodeKey] ?? chatFlow.start);
        isTyping.value = false;
        scrollToBottom();
    }, 650);
};

const handleOption = (option) => {
    if (isTyping.value) {
        return;
    }

    addUserMessage(option.label);
    responderDepois(option.next);
};

const enviarMensagemDigitada = () => {
    const texto = mensagemDigitada.value.trim();

    if (!texto || isTyping.value) {
        return;
    }

    addUserMessage(texto);
    mensagemDigitada.value = '';
    window.clearTimeout(responseTimer);
    isTyping.value = true;
    scrollToBottom();

    responseTimer = window.setTimeout(() => {
        currentNodeKey.value = 'atendente';
        addBotMessage({
            text: 'Recebi sua mensagem. Para oferecer uma orientação precisa, nossa equipe pode continuar o atendimento pelo WhatsApp.',
            link: chatFlow.atendente.link,
        });
        isTyping.value = false;
        scrollToBottom();
    }, 700);
};

onBeforeUnmount(() => {
    window.clearTimeout(responseTimer);
});
</script>

<template>
    <div>
        <div class="fixed bottom-5 right-4 z-50 flex items-center gap-3 sm:right-6">
            <transition name="launcher-label">
                <div
                    v-if="!isOpen"
                    class="launcher-label relative hidden border border-slate-200 bg-white px-3.5 py-2.5 shadow-[0_10px_30px_rgba(15,23,42,0.14)] lg:block"
                >
                    <p class="text-xs font-extrabold text-slate-900">Olá! Posso ajudar?</p>
                    <p class="mt-0.5 text-[10px] text-slate-500">Fale com a Nova Era.</p>
                </div>
            </transition>

            <button
                type="button"
                class="relative flex h-14 w-14 items-center justify-center rounded-full bg-red-600 text-white shadow-[0_12px_28px_rgba(220,38,38,0.35)] transition hover:-translate-y-0.5 hover:bg-red-700"
                :aria-label="isOpen ? 'Fechar atendimento' : 'Abrir atendimento'"
                @click="toggleChat"
            >
                <X v-if="isOpen" class="h-6 w-6" />
                <MessageCircle v-else class="h-6 w-6" />
                <span
                    v-if="!isOpen"
                    class="absolute right-0 top-0 h-3.5 w-3.5 rounded-full border-[3px] border-white bg-emerald-500"
                ></span>
            </button>
        </div>

        <transition name="chat-window">
            <section
                v-if="isOpen"
                class="chat-window-shell fixed z-50 flex flex-col overflow-hidden rounded-lg border border-slate-200 bg-white"
                aria-label="Atendimento virtual Nova Era"
            >
                <header class="chat-header relative overflow-hidden border-b border-red-800 px-4 py-3.5 text-white">
                    <div class="absolute inset-0 chat-header-grid opacity-20"></div>
                    <div class="relative flex items-center justify-between gap-4">
                        <div class="flex min-w-0 items-center gap-3">
                            <div class="relative shrink-0">
                                <img
                                    src="/images/bot.png"
                                    alt="Assistente Nova Era"
                                    class="h-11 w-11 rounded-full border-2 border-white/70 bg-white object-cover"
                                />
                                <span class="absolute bottom-0 right-0 h-3 w-3 rounded-full border-2 border-red-600 bg-emerald-400"></span>
                            </div>
                            <div class="min-w-0">
                                <h2 class="truncate font-display text-base font-bold">Nova Era Placas</h2>
                                <p class="mt-0.5 flex items-center gap-1.5 text-[11px] font-medium text-white/75">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-300"></span>
                                    Assistente virtual online
                                </p>
                            </div>
                        </div>

                        <button
                            type="button"
                            class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-md border border-white/20 text-white/85 transition hover:bg-white/10 hover:text-white"
                            aria-label="Fechar atendimento"
                            @click="toggleChat"
                        >
                            <X class="h-5 w-5" />
                        </button>
                    </div>
                </header>

                <div ref="chatBody" class="chat-surface flex-1 overflow-y-auto px-3.5 py-4">
                    <div class="mb-5 flex items-center gap-3">
                        <span class="h-px flex-1 bg-slate-200"></span>
                        <span class="rounded-full border border-slate-200 bg-white px-3 py-1 text-[9px] font-bold uppercase tracking-[0.16em] text-slate-400">
                            Hoje
                        </span>
                        <span class="h-px flex-1 bg-slate-200"></span>
                    </div>

                    <div class="space-y-3.5">
                        <div
                            v-for="(msg, index) in messages"
                            :key="`${msg.time}-${index}`"
                            :class="msg.type === 'bot' ? 'items-end' : 'justify-end'"
                            class="flex gap-2"
                        >
                            <img
                                v-if="msg.type === 'bot'"
                                src="/images/bot.png"
                                alt=""
                                class="h-7 w-7 shrink-0 rounded-full border border-slate-200 bg-white object-cover"
                            />

                            <div
                                :class="msg.type === 'bot'
                                    ? 'chat-bubble-bot border border-slate-200 bg-white text-slate-800'
                                    : 'chat-bubble-user bg-red-600 text-white'"
                                class="max-w-[82%] px-3.5 py-2.5 shadow-sm"
                            >
                                <p class="whitespace-pre-line text-[13px] leading-[1.55]">
                                    {{ msg.text }}
                                </p>

                                <a
                                    v-if="msg.link"
                                    :href="msg.link.url"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="mt-3 flex items-center justify-center gap-2 rounded-md bg-emerald-600 px-3 py-2.5 text-xs font-extrabold text-white transition hover:bg-emerald-700"
                                >
                                    <MessageCircle class="h-4 w-4" />
                                    {{ msg.link.label }}
                                </a>

                                <div
                                    :class="msg.type === 'bot' ? 'text-slate-400' : 'text-white/70'"
                                    class="mt-1.5 flex items-center justify-end gap-1 text-[9px]"
                                >
                                    {{ msg.time }}
                                    <CheckCheck v-if="msg.type === 'user'" class="h-3 w-3" />
                                </div>
                            </div>
                        </div>

                        <div v-if="isTyping" class="flex items-end gap-2">
                            <img
                                src="/images/bot.png"
                                alt=""
                                class="h-7 w-7 shrink-0 rounded-full border border-slate-200 bg-white object-cover"
                            />
                            <div class="chat-bubble-bot flex items-center gap-1 border border-slate-200 bg-white px-4 py-3 shadow-sm" aria-label="Assistente digitando">
                                <span class="typing-dot"></span>
                                <span class="typing-dot"></span>
                                <span class="typing-dot"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-if="quickOptions.length" class="border-t border-slate-200 bg-white px-3.5 py-3">
                    <p class="mb-2 text-[9px] font-extrabold uppercase tracking-[0.18em] text-slate-400">
                        Respostas rápidas
                    </p>
                    <div class="flex max-h-28 flex-wrap gap-1.5 overflow-y-auto pr-1">
                        <button
                            v-for="option in quickOptions"
                            :key="`${currentNodeKey}-${option.label}`"
                            type="button"
                            class="rounded-full border border-red-200 bg-red-50 px-3 py-1.5 text-left text-[11px] font-bold text-red-700 transition hover:border-red-600 hover:bg-red-600 hover:text-white"
                            @click="handleOption(option)"
                        >
                            {{ option.label }}
                        </button>
                    </div>
                </div>

                <form class="border-t border-slate-200 bg-white p-3" @submit.prevent="enviarMensagemDigitada">
                    <div class="flex items-center gap-2 rounded-lg border border-slate-300 bg-slate-50 p-1.5 focus-within:border-red-400 focus-within:bg-white focus-within:ring-2 focus-within:ring-red-100">
                        <input
                            v-model="mensagemDigitada"
                            type="text"
                            placeholder="Escreva sua mensagem..."
                            class="min-w-0 flex-1 border-0 bg-transparent px-2 py-2 text-sm text-slate-800 placeholder:text-slate-400 focus:ring-0"
                            :disabled="isTyping"
                            aria-label="Mensagem"
                        />
                        <button
                            type="submit"
                            class="inline-flex h-9 w-9 shrink-0 items-center justify-center rounded-md bg-red-600 text-white transition hover:bg-red-700 disabled:cursor-not-allowed disabled:bg-slate-300"
                            :disabled="!mensagemDigitada.trim() || isTyping"
                            aria-label="Enviar mensagem"
                        >
                            <Send class="h-4 w-4" />
                        </button>
                    </div>
                    <p class="mt-2 text-center text-[9px] font-medium text-slate-400">
                        Atendimento automático da Nova Era Placas
                    </p>
                </form>
            </section>
        </transition>
    </div>
</template>

<style scoped>
.launcher-label::after {
    content: '';
    position: absolute;
    right: -6px;
    top: 50%;
    width: 11px;
    height: 11px;
    border-top: 1px solid #e2e8f0;
    border-right: 1px solid #e2e8f0;
    background: white;
    transform: translateY(-50%) rotate(45deg);
}

.chat-header-grid {
    background-image:
        linear-gradient(rgba(255, 255, 255, 0.2) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.2) 1px, transparent 1px);
    background-size: 22px 22px;
}

.chat-header {
    background: linear-gradient(115deg, #991b1b 0%, #dc2626 100%);
}

.chat-window-shell {
    right: 0.75rem;
    bottom: 6rem;
    width: calc(100vw - 1.5rem);
    max-width: 410px;
    height: min(680px, calc(100dvh - 7rem));
    box-shadow: 0 28px 80px rgba(15, 23, 42, 0.28);
}

.chat-surface {
    background-color: #f4f6f8;
    background-image:
        radial-gradient(circle at 20% 10%, rgba(220, 38, 38, 0.04), transparent 13rem),
        linear-gradient(rgba(148, 163, 184, 0.055) 1px, transparent 1px),
        linear-gradient(90deg, rgba(148, 163, 184, 0.055) 1px, transparent 1px);
    background-size: auto, 28px 28px, 28px 28px;
}

.chat-bubble-bot {
    border-radius: 0.25rem 0.875rem 0.875rem 0.875rem;
}

.chat-bubble-user {
    border-radius: 0.875rem 0.25rem 0.875rem 0.875rem;
}

.typing-dot {
    display: block;
    width: 0.38rem;
    height: 0.38rem;
    border-radius: 999px;
    background: #94a3b8;
    animation: typing-bounce 1.2s infinite ease-in-out;
}

.typing-dot:nth-child(2) {
    animation-delay: 150ms;
}

.typing-dot:nth-child(3) {
    animation-delay: 300ms;
}

.chat-window-enter-active,
.chat-window-leave-active {
    transform-origin: bottom right;
    transition: opacity 220ms ease, transform 260ms cubic-bezier(0.16, 1, 0.3, 1);
}

.chat-window-enter-from,
.chat-window-leave-to {
    opacity: 0;
    transform: translateY(18px) scale(0.96);
}

.launcher-label-enter-active,
.launcher-label-leave-active {
    transition: opacity 180ms ease, transform 180ms ease;
}

.launcher-label-enter-from,
.launcher-label-leave-to {
    opacity: 0;
    transform: translateX(8px);
}

@keyframes typing-bounce {
    0%,
    60%,
    100% {
        transform: translateY(0);
        opacity: 0.45;
    }

    30% {
        transform: translateY(-3px);
        opacity: 1;
    }
}

@media (prefers-reduced-motion: reduce) {
    .typing-dot,
    .chat-window-enter-active,
    .chat-window-leave-active,
    .launcher-label-enter-active,
    .launcher-label-leave-active {
        animation: none;
        transition: none;
    }
}

@media (min-width: 640px) {
    .chat-window-shell {
        right: 1.5rem;
    }
}
</style>
