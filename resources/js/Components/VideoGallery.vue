<script setup>
import { ArrowUpRight, ChevronLeft, ChevronRight, Play, Video } from 'lucide-vue-next';
import { computed, ref } from 'vue';

const videos = [
    {
        id: '8_FPRSKYb2A',
        title: 'Nova Era Placas',
        description: 'Conheça a estrutura, a experiência e o compromisso da Nova Era.',
    },
    {
        id: 'P2aDG0vUY4g',
        title: 'Atendimento Automatizado',
        description: 'Tecnologia para tornar o atendimento mais rápido e organizado.',
    },
    {
        id: 'H2hYe1UToDM',
        title: 'Segurança e Qualidade',
        description: 'Processos pensados para proteger cada etapa do emplacamento.',
    },
    {
        id: '-1TxSz1iOQA',
        title: 'Onde Estamos?',
        description: 'Uma rede de unidades preparada para atender você.',
    },
    {
        id: '_9tjph-zce0',
        title: 'Mais do que uma placa',
        description: 'Atendimento próximo, procedência e confiança.',
    },
];

const activeIndex = ref(0);
const activeVideo = computed(() => videos[activeIndex.value]);

const selecionarAnterior = () => {
    activeIndex.value = (activeIndex.value - 1 + videos.length) % videos.length;
};

const selecionarProximo = () => {
    activeIndex.value = (activeIndex.value + 1) % videos.length;
};
</script>

<template>
    <div class="mx-auto max-w-[1320px]">
        <div class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
            <div class="max-w-2xl">
                <p class="text-[11px] font-extrabold uppercase tracking-[0.24em] text-red-600">
                    Conteúdo Nova Era
                </p>
                <h2 class="site-heading mt-4 text-3xl font-semibold leading-tight tracking-[-0.02em] text-slate-950 sm:text-4xl">
                    Informação que aproxima e orienta.
                </h2>
            </div>
            <p class="max-w-md text-sm leading-7 text-slate-500">
                Assista aos conteúdos sobre nossa empresa, processos, segurança e unidades.
            </p>
        </div>

        <div class="mt-12 grid overflow-hidden rounded-lg border border-slate-200 bg-slate-950 shadow-[0_24px_60px_rgba(15,23,42,0.13)] lg:grid-cols-[1.45fr,0.55fr]">
            <div class="relative min-w-0 border-b border-white/10 lg:border-b-0 lg:border-r">
                <div class="aspect-video bg-black">
                    <iframe
                        :key="activeVideo.id"
                        :src="`https://www.youtube-nocookie.com/embed/${activeVideo.id}`"
                        :title="activeVideo.title"
                        class="h-full w-full"
                        loading="lazy"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                        allowfullscreen
                    ></iframe>
                </div>

                <div class="flex flex-col gap-4 border-t border-white/10 px-5 py-5 text-white sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-[0.2em] text-red-400">
                            Vídeo {{ activeIndex + 1 }} de {{ videos.length }}
                        </p>
                        <h3 class="site-heading mt-1 text-lg font-semibold">{{ activeVideo.title }}</h3>
                        <p class="mt-1 text-xs leading-5 text-white/55">{{ activeVideo.description }}</p>
                    </div>

                    <div class="flex shrink-0 gap-2">
                        <button
                            type="button"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-white/15 text-white/70 hover:bg-white/10 hover:text-white"
                            aria-label="Vídeo anterior"
                            @click="selecionarAnterior"
                        >
                            <ChevronLeft class="h-5 w-5" />
                        </button>
                        <button
                            type="button"
                            class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-white/15 text-white/70 hover:bg-white/10 hover:text-white"
                            aria-label="Próximo vídeo"
                            @click="selecionarProximo"
                        >
                            <ChevronRight class="h-5 w-5" />
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-[#111827] p-3">
                <div class="flex items-center gap-2 px-2 py-3 text-white/45">
                    <Video class="h-4 w-4" />
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em]">Selecione um vídeo</span>
                </div>

                <div class="grid gap-2">
                    <button
                        v-for="(video, index) in videos"
                        :key="video.id"
                        type="button"
                        :class="index === activeIndex
                            ? 'border-red-500/50 bg-red-500/12 text-white'
                            : 'border-transparent text-white/58 hover:bg-white/5 hover:text-white'"
                        class="group flex items-center gap-3 rounded-md border p-2 text-left transition"
                        @click="activeIndex = index"
                    >
                        <div class="relative h-16 w-24 shrink-0 overflow-hidden rounded-md bg-slate-800">
                            <img
                                :src="`https://i.ytimg.com/vi/${video.id}/mqdefault.jpg`"
                                :alt="video.title"
                                class="h-full w-full object-cover opacity-80 transition group-hover:opacity-100"
                                loading="lazy"
                                decoding="async"
                            />
                            <span class="absolute inset-0 flex items-center justify-center bg-slate-950/25">
                                <Play class="h-5 w-5 fill-current" />
                            </span>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="line-clamp-2 text-xs font-bold leading-5">{{ video.title }}</p>
                            <p class="mt-1 text-[10px] font-semibold uppercase tracking-[0.14em] text-white/35">
                                {{ String(index + 1).padStart(2, '0') }} / {{ String(videos.length).padStart(2, '0') }}
                            </p>
                        </div>
                        <ArrowUpRight class="h-4 w-4 shrink-0 opacity-30" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
