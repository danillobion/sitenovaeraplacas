<script setup>
import { ChevronLeft, ChevronRight, Images, Pause, Play } from 'lucide-vue-next';
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';

const buildGalleryImages = (names) =>
    names.map((name) => ({
        id: name,
        spotlight: `/images/gallery/spotlight/${name}.jpg`,
        thumb: `/images/gallery/thumbs/${name}.jpg`,
    }));

const eventCollections = [
    {
        id: 'caxias-do-sul-rs',
        eyebrow: 'Evento anterior',
        title: 'Nova Era em Caxias do Sul - RS',
        description: 'Bastidores, encontros e momentos da participação da equipe no evento realizado no Sul.',
        images: buildGalleryImages([
            'evento1',
            'evento2',
            'evento3',
            'evento4',
            'evento5',
            'evento6',
            'evento7',
            'evento8',
            'evento9',
            'evento10',
            'evento11',
        ]),
    },
    {
        id: 'teresina-pi',
        eyebrow: 'Novo evento',
        title: 'A Nova Era em Teresina - PI',
        description: 'Momentos marcantes, bastidores e a energia da equipe durante o evento realizado em Teresina.',
        images: buildGalleryImages([
            'eventonovo1',
            'eventonovo2',
            'eventonovo3',
            'eventonovo4',
            'eventonovo5',
            'eventonovo6',
            'eventonovo7',
            'eventonovo8',
            'eventonovo9',
            'eventonovo10',
            'eventonovo11',
            'eventonovo12',
            'eventonovo13',
            'eventonovo14',
            'eventonovo15',
            'eventonovo16',
            'eventonovo17',
            'eventonovo18',
            'eventonovo19',
            'eventonovo20',
            'eventonovo21',
            'eventonovo22',
            'eventonovo23',
            'eventonovo24',
            'eventonovo25',
            'eventonovo26',
        ]),
    },
];

const galleryRoot = ref(null);
const activeEventIndex = ref(1);
const activeIndex = ref(0);
const pausado = ref(false);
const estaVisivel = ref(false);
let autoplayId = null;
let visibilityObserver = null;

const activeEvent = computed(() => eventCollections[activeEventIndex.value]);
const images = computed(() => activeEvent.value.images);
const activeImage = computed(() => images.value[activeIndex.value]);
const thumbsVisiveis = computed(() => {
    const quantidade = Math.min(images.value.length, 8);

    return Array.from({ length: quantidade }, (_, offset) => {
        const index = (activeIndex.value + offset) % images.value.length;

        return {
            index,
            image: images.value[index],
        };
    });
});

const pararAutoplay = () => {
    window.clearInterval(autoplayId);
    autoplayId = null;
};

const iniciarAutoplay = () => {
    pararAutoplay();

    if (pausado.value || !estaVisivel.value || images.value.length < 2) {
        return;
    }

    autoplayId = window.setInterval(() => {
        activeIndex.value = (activeIndex.value + 1) % images.value.length;
    }, 3800);
};

const selecionarImagem = (index) => {
    activeIndex.value = index;
    iniciarAutoplay();
};

const selecionarEvento = (index) => {
    activeEventIndex.value = index;
};

const imagemAnterior = () => {
    activeIndex.value = (activeIndex.value - 1 + images.value.length) % images.value.length;
    iniciarAutoplay();
};

const proximaImagem = () => {
    activeIndex.value = (activeIndex.value + 1) % images.value.length;
    iniciarAutoplay();
};

const alternarAutoplay = () => {
    pausado.value = !pausado.value;
    iniciarAutoplay();
};

watch(activeEventIndex, () => {
    activeIndex.value = 0;
    iniciarAutoplay();
});

onMounted(() => {
    visibilityObserver = new IntersectionObserver(
        ([entry]) => {
            estaVisivel.value = entry.isIntersecting;
            iniciarAutoplay();
        },
        { threshold: 0.2 },
    );

    if (galleryRoot.value) {
        visibilityObserver.observe(galleryRoot.value);
    }
});

onBeforeUnmount(() => {
    pararAutoplay();
    visibilityObserver?.disconnect();
});
</script>

<template>
    <div ref="galleryRoot" class="mx-auto max-w-[1320px]">
        <div class="grid gap-8 lg:grid-cols-[0.72fr,1.28fr] lg:items-end">
            <div>
                <p class="text-[11px] font-extrabold uppercase tracking-[0.24em] text-red-600">
                    Eventos Nova Era
                </p>
                <h2 class="site-heading mt-4 text-3xl font-semibold leading-tight tracking-[-0.02em] text-slate-950 sm:text-4xl">
                    Registros que contam a nossa história.
                </h2>
            </div>
            <p class="max-w-xl text-sm leading-7 text-slate-500 lg:justify-self-end">
                Acompanhe os encontros, bastidores e momentos importantes da equipe. As imagens avançam automaticamente sem sobrecarregar a página.
            </p>
        </div>

        <div class="mt-12 grid gap-5 lg:grid-cols-[310px,minmax(0,1fr)]">
            <div class="space-y-3">
                <button
                    v-for="(eventItem, index) in eventCollections"
                    :key="eventItem.id"
                    type="button"
                    :class="index === activeEventIndex
                        ? 'border-red-300 bg-white shadow-[0_12px_30px_rgba(15,23,42,0.08)]'
                        : 'border-slate-200 bg-white/60 hover:bg-white'"
                    class="w-full rounded-lg border p-4 text-left transition"
                    @click="selecionarEvento(index)"
                >
                    <div class="flex items-start justify-between gap-3">
                        <div>
                            <p
                                :class="index === activeEventIndex ? 'text-red-600' : 'text-slate-400'"
                                class="text-[10px] font-bold uppercase tracking-[0.2em]"
                            >
                                {{ eventItem.eyebrow }}
                            </p>
                            <p class="mt-2 text-sm font-extrabold leading-5 text-slate-900">
                                {{ eventItem.title }}
                            </p>
                        </div>
                        <span class="inline-flex shrink-0 items-center gap-1 rounded-full bg-slate-100 px-2 py-1 text-[10px] font-bold text-slate-500">
                            <Images class="h-3 w-3" /> {{ eventItem.images.length }}
                        </span>
                    </div>
                </button>

                <div class="rounded-lg border border-slate-200 bg-white p-4">
                    <p class="text-xs leading-6 text-slate-600">{{ activeEvent.description }}</p>
                    <div class="mt-4 flex items-center justify-between border-t border-slate-100 pt-4">
                        <span class="text-[10px] font-bold uppercase tracking-[0.16em] text-slate-400">
                            {{ activeIndex + 1 }} de {{ images.length }}
                        </span>
                        <button
                            type="button"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-slate-200 text-slate-500 hover:bg-slate-50"
                            :aria-label="pausado ? 'Reproduzir galeria' : 'Pausar galeria'"
                            @click="alternarAutoplay"
                        >
                            <Play v-if="pausado" class="h-4 w-4 fill-current" />
                            <Pause v-else class="h-4 w-4 fill-current" />
                        </button>
                    </div>
                </div>
            </div>

            <div class="min-w-0 overflow-hidden rounded-lg border border-slate-200 bg-slate-950 p-3 shadow-[0_24px_60px_rgba(15,23,42,0.14)]">
                <div class="group relative aspect-[16/10] overflow-hidden rounded-md bg-slate-900">
                    <transition name="gallery-fade" mode="out-in">
                        <img
                            :key="activeImage.spotlight"
                            :src="activeImage.spotlight"
                            :alt="`${activeEvent.title}, imagem ${activeIndex + 1}`"
                            class="absolute inset-0 h-full w-full object-cover"
                            decoding="async"
                        />
                    </transition>

                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950/85 via-transparent to-slate-950/10" />
                    <div class="absolute inset-x-0 bottom-0 flex items-end justify-between gap-5 p-5 text-white sm:p-7">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-[0.24em] text-red-300">
                                {{ activeEvent.eyebrow }}
                            </p>
                            <h3 class="site-heading mt-2 text-lg font-semibold sm:text-xl">
                                {{ activeEvent.title }}
                            </h3>
                        </div>
                        <div class="hidden gap-2 sm:flex">
                            <button
                                type="button"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-white/20 bg-slate-950/30 text-white backdrop-blur hover:bg-white/15"
                                aria-label="Imagem anterior"
                                @click="imagemAnterior"
                            >
                                <ChevronLeft class="h-5 w-5" />
                            </button>
                            <button
                                type="button"
                                class="inline-flex h-10 w-10 items-center justify-center rounded-md border border-white/20 bg-slate-950/30 text-white backdrop-blur hover:bg-white/15"
                                aria-label="Próxima imagem"
                                @click="proximaImagem"
                            >
                                <ChevronRight class="h-5 w-5" />
                            </button>
                        </div>
                    </div>

                    <div v-if="!pausado" :key="`${activeEvent.id}-${activeIndex}`" class="gallery-progress absolute inset-x-0 bottom-0 h-1 bg-red-500" />
                </div>

                <div class="mt-3 grid grid-cols-4 gap-2 sm:grid-cols-8">
                    <button
                        v-for="thumb in thumbsVisiveis"
                        :key="`${activeEvent.id}-${thumb.image.id}`"
                        type="button"
                        :class="thumb.index === activeIndex
                            ? 'border-red-500 opacity-100'
                            : 'border-transparent opacity-55 hover:opacity-100'"
                        class="overflow-hidden rounded-md border-2 bg-slate-800 transition"
                        @click="selecionarImagem(thumb.index)"
                    >
                        <img
                            :src="thumb.image.thumb"
                            :alt="`${activeEvent.title}, miniatura ${thumb.index + 1}`"
                            class="aspect-square w-full object-cover"
                            loading="lazy"
                            decoding="async"
                        />
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.gallery-fade-enter-active,
.gallery-fade-leave-active {
    transition: opacity 420ms ease, transform 420ms ease;
}

.gallery-fade-enter-from {
    opacity: 0;
    transform: scale(1.025);
}

.gallery-fade-leave-to {
    opacity: 0;
    transform: scale(0.985);
}

.gallery-progress {
    transform-origin: left;
    animation: gallery-progress 3.8s linear forwards;
}

@keyframes gallery-progress {
    from {
        transform: scaleX(0);
    }

    to {
        transform: scaleX(1);
    }
}

@media (prefers-reduced-motion: reduce) {
    .gallery-progress {
        animation: none;
    }

    .gallery-fade-enter-active,
    .gallery-fade-leave-active {
        transition: none;
    }
}
</style>
