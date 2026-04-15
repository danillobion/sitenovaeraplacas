<script setup>
import { computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import {
    Activity,
    FileSearch2,
    LayoutDashboard,
    MapPinned,
} from 'lucide-vue-next';

const props = defineProps({
    quantidade_consultas: { type: Number, default: 0 },
    quantidade_consultas_cep: { type: Number, default: 0 },
    quantidade_consultas_cnpj: { type: Number, default: 0 },
});

const totalConsultas = computed(() => props.quantidade_consultas ?? 0);

const calcularPercentual = (valor) => {
    if (!totalConsultas.value) {
        return 0;
    }

    return Math.round((valor / totalConsultas.value) * 100);
};

const metricas = computed(() => [
    {
        chave: 'total',
        titulo: 'Total de consultas',
        descricao: 'Visão consolidada das consultas registradas',
        valor: props.quantidade_consultas,
        percentual: totalConsultas.value > 0 ? 100 : 0,
        icone: LayoutDashboard,
        destaque: 'bg-red-600',
        fundo: 'bg-red-50',
        borda: 'border-red-100',
    },
    {
        chave: 'cep',
        titulo: 'Consultas por CEP',
        descricao: 'Buscas de endereço realizadas',
        valor: props.quantidade_consultas_cep,
        percentual: calcularPercentual(props.quantidade_consultas_cep),
        icone: MapPinned,
        destaque: 'bg-orange-500',
        fundo: 'bg-orange-50',
        borda: 'border-orange-100',
    },
    {
        chave: 'cnpj',
        titulo: 'Consultas por CNPJ',
        descricao: 'Validações cadastrais de empresas',
        valor: props.quantidade_consultas_cnpj,
        percentual: calcularPercentual(props.quantidade_consultas_cnpj),
        icone: FileSearch2,
        destaque: 'bg-slate-800',
        fundo: 'bg-slate-50',
        borda: 'border-slate-200',
    },
]);

const statusMensagem = computed(() => {
    if (!totalConsultas.value) {
        return 'Nenhuma consulta registrada no momento.';
    }

    return 'Painel atualizado com base no volume atual de consultas.';
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div>
                <h2>Dashboard</h2>
                <p>Resumo operacional com foco nas informações que mais importam.</p>
            </div>
        </template>

        <div class="grid gap-6 xl:grid-cols-[1.45fr,0.95fr]">
            <section class="rounded-[20px] border border-slate-200 bg-white p-7 shadow-sm">
                <div class="flex flex-col gap-6 border-b border-slate-100 pb-6 lg:flex-row lg:items-start lg:justify-between">
                    <div class="max-w-2xl">
                        <p class="text-xs font-bold uppercase tracking-[0.24em] text-red-400">
                            Panorama geral
                        </p>
                        <h3 class="mt-3 font-display text-[2rem] font-extrabold tracking-tight text-slate-900">
                            Dashboard de consultas
                        </h3>
                        <p class="mt-3 max-w-xl text-sm leading-6 text-slate-500">
                            {{ statusMensagem }}
                        </p>
                    </div>

                    <div class="inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-50 px-4 py-2 text-sm font-medium text-slate-600">
                        <Activity class="h-4 w-4 text-emerald-500" />
                        Atualizado agora
                    </div>
                </div>

                <div class="mt-6 grid gap-4 md:grid-cols-3">
                    <article
                        v-for="metrica in metricas"
                        :key="metrica.chave"
                        class="rounded-[18px] border p-5"
                        :class="[metrica.fundo, metrica.borda]"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div
                                class="flex h-11 w-11 items-center justify-center rounded-[14px] text-white"
                                :class="metrica.destaque"
                            >
                                <component :is="metrica.icone" class="h-5 w-5" />
                            </div>

                            <span class="rounded-full bg-white px-3 py-1 text-[11px] font-bold uppercase tracking-[0.2em] text-slate-500">
                                {{ metrica.percentual }}%
                            </span>
                        </div>

                        <div class="mt-6">
                            <p class="text-sm font-semibold text-slate-500">
                                {{ metrica.titulo }}
                            </p>
                            <p class="mt-2 font-display text-4xl font-extrabold tracking-tight text-slate-900">
                                {{ metrica.valor }}
                            </p>
                            <p class="mt-2 text-sm leading-6 text-slate-500">
                                {{ metrica.descricao }}
                            </p>
                        </div>
                    </article>
                </div>
            </section>

            <aside class="rounded-[20px] border border-slate-200 bg-white p-7 shadow-sm">
                <div class="flex items-center justify-between gap-3 border-b border-slate-100 pb-5">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.24em] text-red-400">
                            Distribuição
                        </p>
                        <h3 class="mt-2 font-display text-2xl font-extrabold tracking-tight text-slate-900">
                            Participação por tipo
                        </h3>
                    </div>

                    <div class="flex h-11 w-11 items-center justify-center rounded-[14px] bg-red-50 text-red-600">
                        <LayoutDashboard class="h-5 w-5" />
                    </div>
                </div>

                <div class="mt-6 space-y-5">
                    <div
                        v-for="metrica in metricas.filter((item) => item.chave !== 'total')"
                        :key="`${metrica.chave}-progress`"
                    >
                        <div class="flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-slate-800">
                                    {{ metrica.titulo }}
                                </p>
                                <p class="text-xs text-slate-500">
                                    {{ metrica.valor }} registros
                                </p>
                            </div>
                            <span class="text-sm font-bold text-slate-700">
                                {{ metrica.percentual }}%
                            </span>
                        </div>

                        <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                            <div
                                class="h-full rounded-full"
                                :class="metrica.destaque"
                                :style="{ width: `${metrica.percentual}%` }"
                            />
                        </div>
                    </div>
                </div>

                <div class="mt-8 rounded-[18px] border border-slate-200 bg-slate-50 p-5">
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-slate-400">
                        Leitura rápida
                    </p>
                    <p class="mt-3 text-sm leading-6 text-slate-600">
                        Use esse painel como ponto de entrada para acompanhar a operação sem excesso de informação visual.
                    </p>
                </div>
            </aside>
        </div>
    </AuthenticatedLayout>
</template>
