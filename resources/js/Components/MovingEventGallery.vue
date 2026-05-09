<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { ArrowUpRight, Sparkles } from 'lucide-vue-next';

const buildGalleryImages = (names) => {
    return names.map((name) => ({
        id: name,
        spotlight: `/images/gallery/spotlight/${name}.jpg`,
        thumb: `/images/gallery/thumbs/${name}.jpg`,
    }));
};

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
        title: 'A nova era Teresina - PI',
        description: 'Momentos marcantes, bastidores e a energia contagiante da equipe durante o evento em Teresina.',
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

const activeEventIndex = ref(1);
const activeIndex = ref(0);
let autoplayId = null;

const activeEvent = computed(() => eventCollections[activeEventIndex.value] ?? eventCollections[0]);
const images = computed(() => activeEvent.value.images);
const activeImage = computed(() => images.value[activeIndex.value] ?? images.value[0] ?? null);
const repeatedForward = computed(() => [...images.value, ...images.value]);
const repeatedReverse = computed(() => {
    const reversed = [...images.value].reverse();

    return [...reversed, ...reversed];
});

const cardsEmDestaque = computed(() => {
    const limit = Math.min(3, images.value.length);

    return Array.from({ length: limit }, (_, offset) => {
        const index = (activeIndex.value + offset) % images.value.length;

        return {
            index,
            image: images.value[index],
            label: `Imagem ${String(index + 1).padStart(2, '0')}`,
        };
    });
});

const iniciarAutoplay = () => {
    window.clearInterval(autoplayId);

    autoplayId = window.setInterval(() => {
        activeIndex.value = (activeIndex.value + 1) % images.value.length;
    }, 3400);
};

const resetarAutoplay = () => {
    iniciarAutoplay();
};

const selecionarImagem = (index) => {
    activeIndex.value = index;
    resetarAutoplay();
};

const selecionarEvento = (index) => {
    activeEventIndex.value = index;
};

watch(activeEventIndex, () => {
    activeIndex.value = 0;
    resetarAutoplay();
});

onMounted(() => {
    iniciarAutoplay();
});

onBeforeUnmount(() => {
    window.clearInterval(autoplayId);
});
</script>

<template>
    <section
        class="relative overflow-hidden rounded-[34px] border border-slate-200 bg-[#fffaf7] px-6 py-8 shadow-[0_24px_80px_rgba(15,23,42,0.08)] lg:px-8"
    >
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(232,75,67,0.12),_transparent_42%),radial-gradient(circle_at_bottom_right,_rgba(59,130,246,0.14),_transparent_38%)]" />

        <div class="relative grid gap-8 lg:grid-cols-[0.92fr,1.08fr] lg:items-center">
            <div class="max-w-xl">
                <div class="inline-flex items-center gap-2 rounded-full border border-red-100 bg-white px-4 py-2 text-xs font-bold uppercase tracking-[0.26em] text-red-500">
                    <Sparkles class="h-4 w-4" />
                    Eventos
                </div>

                <h3 class="mt-5 font-display text-4xl font-extrabold tracking-tight text-slate-900">
                    Eventos que a Nova Era esteve presente
                </h3>

                <p class="mt-4 text-sm leading-7 text-slate-600">
                    {{ activeEvent.description }}
                </p>

                <div class="mt-6 grid gap-3">
                    <button
                        v-for="(eventItem, index) in eventCollections"
                        :key="eventItem.id"
                        type="button"
                        :class="index === activeEventIndex
                            ? 'border-red-200 bg-white shadow-lg ring-2 ring-red-100'
                            : 'border-slate-200 bg-white/80 hover:border-red-200 hover:bg-white'"
                        class="flex items-center justify-between gap-4 rounded-[22px] border px-4 py-4 text-left transition duration-300"
                        @click="selecionarEvento(index)"
                    >
                        <div class="min-w-0">
                            <p
                                :class="index === activeEventIndex ? 'text-red-500' : 'text-slate-400'"
                                class="text-[11px] font-bold uppercase tracking-[0.24em]"
                            >
                                {{ eventItem.eyebrow }}
                            </p>
                            <p class="mt-1 truncate text-sm font-bold text-slate-900">
                                {{ eventItem.title }}
                            </p>
                        </div>

                        <span
                            :class="index === activeEventIndex ? 'bg-red-50 text-red-600' : 'bg-slate-100 text-slate-500'"
                            class="inline-flex shrink-0 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-[0.18em]"
                        >
                            {{ eventItem.images.length }} fotos
                        </span>
                    </button>
                </div>

                <div class="mt-8 grid gap-3 sm:grid-cols-3">
                    <button
                        v-for="card in cardsEmDestaque"
                        :key="`${activeEvent.id}-${card.label}`"
                        type="button"
                        class="group rounded-[22px] border border-slate-200 bg-white p-3 text-left shadow-sm transition duration-300 hover:-translate-y-1 hover:border-red-200 hover:shadow-lg"
                        @click="selecionarImagem(card.index)"
                    >
                        <div class="overflow-hidden rounded-[16px]">
                            <img
                                :src="card.image.thumb"
                                :alt="`${activeEvent.title} - ${card.label}`"
                                class="h-28 w-full object-cover transition duration-500 group-hover:scale-105"
                                loading="lazy"
                                decoding="async"
                            />
                        </div>

                        <div class="mt-3 flex items-center justify-between gap-3">
                            <span class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500">
                                {{ card.label }}
                            </span>
                            <ArrowUpRight class="h-4 w-4 text-slate-400 transition group-hover:text-red-500" />
                        </div>
                    </button>
                </div>
            </div>

            <div class="relative overflow-hidden rounded-[28px] border border-slate-200 bg-slate-950 p-3 shadow-[0_30px_80px_rgba(15,23,42,0.2)]">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(255,255,255,0.12),_transparent_34%),radial-gradient(circle_at_bottom_left,_rgba(232,75,67,0.22),_transparent_36%)]" />

                <transition name="spotlight-fade" mode="out-in">
                    <div
                        v-if="activeImage"
                        :key="activeImage.spotlight"
                        class="relative aspect-[4/3] overflow-hidden rounded-[22px]"
                    >
                        <img
                            :src="activeImage.spotlight"
                            :alt="activeEvent.title"
                            class="h-full w-full object-cover"
                            decoding="async"
                            fetchpriority="high"
                        />

                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/15 to-transparent" />

                        <div class="absolute inset-x-0 bottom-0 p-6 text-white">
                            <p class="text-[11px] font-bold uppercase tracking-[0.3em] text-white/60">
                                {{ activeEvent.eyebrow }}
                            </p>

                            <div class="mt-3 flex flex-wrap items-end justify-between gap-4">
                                <div>
                                    <h4 class="text-2xl font-black tracking-tight">
                                        {{ activeEvent.title }}
                                    </h4>
                                    <p class="mt-2 text-sm text-white/70">
                                        Imagem {{ activeIndex + 1 }} de {{ images.length }}
                                    </p>
                                </div>

                                <span class="inline-flex rounded-full border border-white/15 bg-white/10 px-3 py-1.5 text-[11px] font-bold uppercase tracking-[0.24em] text-white/80">
                                    Galeria viva
                                </span>
                            </div>
                        </div>
                    </div>
                </transition>
            </div>
        </div>

        <div class="relative mt-10 space-y-4">
            <div class="gallery-marquee">
                <div class="gallery-track">
                    <button
                        v-for="(image, index) in repeatedForward"
                        :key="`forward-${activeEvent.id}-${index}`"
                        type="button"
                        class="gallery-card"
                        :class="{ 'is-active': index % images.length === activeIndex }"
                        @click="selecionarImagem(index % images.length)"
                    >
                        <img
                            :src="image.thumb"
                            :alt="`${activeEvent.title} ${index + 1}`"
                            class="h-36 w-full object-cover"
                            loading="lazy"
                            decoding="async"
                        />
                    </button>
                </div>
            </div>

            <div class="gallery-marquee">
                <div class="gallery-track gallery-track-reverse">
                    <button
                        v-for="(image, index) in repeatedReverse"
                        :key="`reverse-${activeEvent.id}-${index}`"
                        type="button"
                        class="gallery-card gallery-card-sm"
                        :class="{ 'is-active': index % images.length === activeIndex }"
                        @click="selecionarImagem((images.length - 1 - (index % images.length) + images.length) % images.length)"
                    >
                        <img
                            :src="image.thumb"
                            :alt="`${activeEvent.title} alternativo ${index + 1}`"
                            class="h-28 w-full object-cover"
                            loading="lazy"
                            decoding="async"
                        />
                    </button>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.gallery-marquee {
    overflow: hidden;
    -webkit-mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
    mask-image: linear-gradient(to right, transparent, black 8%, black 92%, transparent);
}

.gallery-track {
    display: flex;
    width: max-content;
    gap: 1rem;
    animation: marquee-left 42s linear infinite;
    will-change: transform;
}

.gallery-track-reverse {
    animation-name: marquee-right;
    animation-duration: 48s;
}

.gallery-marquee:hover .gallery-track {
    animation-play-state: paused;
}

.gallery-card {
    width: 15rem;
    flex: none;
    overflow: hidden;
    border-radius: 1.35rem;
    border: 1px solid rgba(148, 163, 184, 0.22);
    background: rgba(255, 255, 255, 0.88);
    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
    transition:
        transform 300ms ease,
        border-color 300ms ease,
        box-shadow 300ms ease;
}

.gallery-card-sm {
    width: 13rem;
}

.gallery-card:hover,
.gallery-card.is-active {
    transform: translateY(-6px);
    border-color: rgba(232, 75, 67, 0.35);
    box-shadow: 0 20px 42px rgba(232, 75, 67, 0.16);
}

.spotlight-fade-enter-active,
.spotlight-fade-leave-active {
    transition:
        opacity 420ms ease,
        transform 420ms ease;
}

.spotlight-fade-enter-from,
.spotlight-fade-leave-to {
    opacity: 0;
    transform: scale(0.97);
}

@keyframes marquee-left {
    from {
        transform: translate3d(0, 0, 0);
    }

    to {
        transform: translate3d(-50%, 0, 0);
    }
}

@keyframes marquee-right {
    from {
        transform: translate3d(-50%, 0, 0);
    }

    to {
        transform: translate3d(0, 0, 0);
    }
}

@media (prefers-reduced-motion: reduce) {
    .gallery-track {
        animation: none;
    }

    .spotlight-fade-enter-active,
    .spotlight-fade-leave-active,
    .gallery-card {
        transition: none;
    }
}
</style>
