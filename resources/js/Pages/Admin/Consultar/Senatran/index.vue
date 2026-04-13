<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import { AlertCircle, ExternalLink, Search, ShieldCheck } from 'lucide-vue-next';
import { showErrorAlert } from '@/lib/sweetAlert';

const placa = ref('');
const serial = ref('');
const erro = ref('');

const placaFormatada = computed(() =>
    placa.value
        .replace(/[^a-zA-Z0-9]/g, '')
        .toUpperCase()
        .slice(0, 7),
);

const serialFormatado = computed(() =>
    serial.value
        .trim()
        .toUpperCase(),
);

const atualizarPlaca = (event) => {
    placa.value = event.target.value
        .replace(/[^a-zA-Z0-9]/g, '')
        .toUpperCase()
        .slice(0, 7);
};

const atualizarSerial = (event) => {
    serial.value = event.target.value.toUpperCase();
};

const pesquisar = () => {
    if (!placaFormatada.value || !serialFormatado.value) {
        const mensagem = 'Preencha a placa e o serial para consultar no portal da SENATRAN.';
        erro.value = mensagem;
        showErrorAlert(mensagem);
        return;
    }

    erro.value = '';

    const url = `https://portalservicos.senatran.serpro.gov.br/#/veiculos/placa-veicular/detalhes/${encodeURIComponent(serialFormatado.value)}/${encodeURIComponent(placaFormatada.value)}`;
    window.open(url, '_blank', 'noopener,noreferrer');
};
</script>

<template>
    <Head title="Consultar SENATRAN" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2>SENATRAN</h2>
                <p>Consulta externa de placa veicular com uma interface mais clara e orientada para a tarefa.</p>
            </div>
        </template>

        <div class="grid gap-6 xl:grid-cols-[1.35fr,0.9fr]">
            <section class="rounded-[20px] border border-slate-200 bg-white p-7 shadow-sm">
                <div class="flex flex-col gap-5 border-b border-slate-100 pb-6 lg:flex-row lg:items-start lg:justify-between">
                    <div class="max-w-2xl">
                        <p class="text-xs font-bold uppercase tracking-[0.24em] text-blue-500">
                            Consulta de veículos
                        </p>
                        <h3 class="mt-3 font-display text-[2rem] font-extrabold tracking-tight text-slate-900">
                            Consulta direta no SENATRAN.
                        </h3>
                        <p class="mt-3 text-sm leading-6 text-slate-500">
                            Informe a placa e o serial do veículo para abrir a consulta oficial em uma nova aba, com menos ruído visual e mais foco na operação.
                        </p>
                    </div>

                    <div class="inline-flex items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700">
                        <ShieldCheck class="h-4 w-4" />
                        Portal oficial
                    </div>
                </div>

                <div class="mt-6 grid gap-5">
                    <div class="grid gap-5 md:grid-cols-2">
                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">
                                Placa
                            </span>
                            <div class="relative">
                                <Search class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-blue-500" />
                                <input
                                    :value="placa"
                                    type="text"
                                    placeholder="ABC1234"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm font-medium uppercase text-slate-900 outline-none transition focus:border-blue-300 focus:bg-white focus:ring-4 focus:ring-blue-100"
                                    @input="atualizarPlaca"
                                />
                            </div>
                            <p class="mt-2 text-xs text-slate-400">
                                A placa será normalizada automaticamente.
                            </p>
                        </label>

                        <label class="block">
                            <span class="mb-2 block text-sm font-semibold text-slate-700">
                                Serial
                            </span>
                            <div class="relative">
                                <Search class="absolute left-4 top-1/2 h-4 w-4 -translate-y-1/2 text-blue-500" />
                                <input
                                    :value="serial"
                                    type="text"
                                    placeholder="Informe o serial"
                                    class="w-full rounded-xl border border-slate-200 bg-slate-50 py-3 pl-11 pr-4 text-sm font-medium uppercase text-slate-900 outline-none transition focus:border-blue-300 focus:bg-white focus:ring-4 focus:ring-blue-100"
                                    @input="atualizarSerial"
                                />
                            </div>
                            <p class="mt-2 text-xs text-slate-400">
                                O serial será enviado exatamente para a consulta externa.
                            </p>
                        </label>
                    </div>

                    <div
                        v-if="erro"
                        class="flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
                    >
                        <AlertCircle class="mt-0.5 h-4 w-4 shrink-0" />
                        <span>{{ erro }}</span>
                    </div>

                    <div class="flex flex-col gap-3 border-t border-slate-100 pt-5 sm:flex-row sm:items-center sm:justify-between">
                        <div class="text-sm text-slate-500">
                            <span class="font-semibold text-slate-700">Preview:</span>
                            placa <span class="font-mono text-slate-800">{{ placaFormatada || '---' }}</span>
                            /
                            serial <span class="font-mono text-slate-800">{{ serialFormatado || '---' }}</span>
                        </div>

                        <button
                            @click="pesquisar"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700"
                        >
                            <ExternalLink class="h-4 w-4" />
                            Consultar na SENATRAN
                        </button>
                    </div>
                </div>
            </section>

            <aside class="rounded-[20px] border border-slate-200 bg-white p-7 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-slate-100 text-slate-700">
                        <ShieldCheck class="h-5 w-5" />
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.24em] text-slate-400">
                            Como usar
                        </p>
                        <h3 class="mt-1 font-display text-2xl font-extrabold tracking-tight text-slate-900">
                            Passo a passo
                        </h3>
                    </div>
                </div>

                <div class="mt-6 space-y-4">
                    <div class="rounded-[16px] border border-slate-200 bg-slate-50 p-4">
                        <p class="text-sm font-semibold text-slate-800">1. Informe a placa</p>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Digite a placa do veículo sem se preocupar com formatação. O campo organiza automaticamente.
                        </p>
                    </div>

                    <div class="rounded-[16px] border border-slate-200 bg-slate-50 p-4">
                        <p class="text-sm font-semibold text-slate-800">2. Preencha o serial</p>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Use o serial exato informado pelo processo ou documento que acompanha a consulta.
                        </p>
                    </div>

                    <div class="rounded-[16px] border border-blue-100 bg-blue-50 p-4">
                        <p class="text-sm font-semibold text-blue-800">3. Abra o portal oficial</p>
                        <p class="mt-2 text-sm leading-6 text-blue-700/80">
                            Ao clicar no botão, a pesquisa será aberta em uma nova aba no ambiente oficial da SENATRAN.
                        </p>
                    </div>
                </div>
            </aside>
        </div>
    </AuthenticatedLayout>
</template>
