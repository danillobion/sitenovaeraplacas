<script setup>
import { computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import {
    BellRing,
    CheckCircle2,
    Image as ImageIcon,
    PencilLine,
} from 'lucide-vue-next';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    informativo: Object,
});

const tituloInformativo = computed(() => {
    const titulo = props.informativo?.titulo?.trim();

    return titulo || 'Nenhum informativo configurado';
});

const possuiImagem = computed(() => Boolean(props.informativo?.imagem));

const imagemUrl = computed(() =>
    possuiImagem.value ? `/storage/${props.informativo.imagem}` : null,
);

const statusInformativo = computed(() =>
    props.informativo?.habilitado ? 'Ativo' : 'Desativado',
);

const editar = () => {
    router.get(route('informativo.editar'));
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Informativo" />

        <template #header>
            <div>
                <h2>Informativo</h2>
                <p>Gerencie o comunicado exibido na tela inicial com uma visão mais clara de status, imagem e publicação.</p>
            </div>
        </template>

        <div class="grid gap-6 xl:grid-cols-[1.08fr,0.92fr]">
            <section class="rounded-[20px] border border-slate-200 bg-white p-7 shadow-sm">
                <div class="flex flex-col gap-5 border-b border-slate-100 pb-6 lg:flex-row lg:items-start lg:justify-between">
                    <div class="max-w-2xl">
                        <p class="text-xs font-bold uppercase tracking-[0.24em] text-red-500">
                            Conteúdo institucional
                        </p>
                        <h3 class="mt-3 font-display text-[2rem] font-extrabold tracking-tight text-slate-900">
                            Informativo da landing page.
                        </h3>
                        <p class="mt-3 text-sm leading-6 text-slate-500">
                            Visualize rapidamente o título, o status e a imagem que serão exibidos ao público.
                        </p>
                    </div>

                    <span
                        :class="props.informativo?.habilitado
                            ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
                            : 'border-slate-200 bg-slate-100 text-slate-600'"
                        class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-semibold"
                    >
                        <CheckCircle2 v-if="props.informativo?.habilitado" class="h-4 w-4" />
                        <BellRing v-else class="h-4 w-4" />
                        {{ statusInformativo }}
                    </span>
                </div>

                <div class="mt-6 space-y-5">
                    <div class="rounded-[18px] border border-slate-200 bg-slate-50 p-5">
                        <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-400">
                            Título
                        </p>
                        <h4 class="mt-2 text-xl font-bold tracking-tight text-slate-900">
                            {{ tituloInformativo }}
                        </h4>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div class="rounded-[18px] border border-slate-200 bg-slate-50 p-5">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-red-50 text-red-600">
                                    <BellRing class="h-5 w-5" />
                                </div>
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-400">
                                        Status
                                    </p>
                                    <p class="mt-1 text-sm font-semibold text-slate-800">
                                        {{ statusInformativo }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-[18px] border border-slate-200 bg-slate-50 p-5">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 text-blue-600">
                                    <ImageIcon class="h-5 w-5" />
                                </div>
                                <div>
                                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-400">
                                        Imagem
                                    </p>
                                    <p class="mt-1 text-sm font-semibold text-slate-800">
                                        {{ possuiImagem ? 'Configurada' : 'Opcional' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex flex-col gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-sm leading-6 text-slate-500">
                        Ajuste o conteúdo sempre que quiser atualizar o aviso da página inicial.
                    </p>

                    <button
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                        @click="editar"
                    >
                        <PencilLine class="h-4 w-4" />
                        Editar informativo
                    </button>
                </div>
            </section>

            <aside class="rounded-[20px] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.24em] text-slate-400">
                            Preview público
                        </p>
                        <h3 class="mt-2 font-display text-2xl font-extrabold tracking-tight text-slate-900">
                            Como aparece no site
                        </h3>
                    </div>

                    <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600">
                        Landing page
                    </span>
                </div>

                <div class="mt-5 rounded-[20px] border border-slate-200 bg-[#f6f3f0] p-4">
                    <div class="rounded-[18px] border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.24em] text-red-500">
                            <BellRing class="h-4 w-4" />
                            Informativo
                        </div>

                        <h4 class="mt-3 text-lg font-bold tracking-tight text-slate-900">
                            {{ tituloInformativo }}
                        </h4>

                        <div
                            v-if="imagemUrl"
                            class="mt-4 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50"
                        >
                            <img
                                :src="imagemUrl"
                                alt="Preview do informativo"
                                class="h-56 w-full object-cover"
                            />
                        </div>

                        <div
                            v-else
                            class="mt-4 flex h-56 items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-6 text-center text-sm leading-6 text-slate-400"
                        >
                            Nenhuma imagem configurada.
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </AuthenticatedLayout>
</template>
