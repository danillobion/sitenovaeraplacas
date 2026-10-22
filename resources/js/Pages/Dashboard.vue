<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import {
    Activity,
    ArrowUpRight,
    BarChart3,
    Building2,
    FileSearch2,
    Gauge,
    MapPinned,
} from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps({
    quantidade_consultas: { type: Number, default: 0 },
    quantidade_consultas_cep: { type: Number, default: 0 },
    quantidade_consultas_cnpj: { type: Number, default: 0 },
});

const page = usePage();
const nomeUsuario = computed(() => page.props.auth?.user?.nome ?? 'Usuário');
const primeiroNome = computed(() => nomeUsuario.value.split(' ').filter(Boolean)[0] ?? 'Usuário');
const totalConsultas = computed(() => Number(props.quantidade_consultas ?? 0));

const calcularPercentual = (valor) => {
    if (!totalConsultas.value) {
        return 0;
    }

    return Math.round((Number(valor ?? 0) / totalConsultas.value) * 100);
};

const metricas = computed(() => [
    {
        chave: 'total',
        titulo: 'Total de consultas',
        descricao: 'Registros processados pelo sistema',
        valor: totalConsultas.value,
        icone: Gauge,
        corIcone: 'bg-red-600 text-white',
        corDetalhe: 'bg-red-600',
    },
    {
        chave: 'cep',
        titulo: 'Consultas de CEP',
        descricao: 'Endereços localizados',
        valor: Number(props.quantidade_consultas_cep ?? 0),
        icone: MapPinned,
        corIcone: 'bg-amber-50 text-amber-600',
        corDetalhe: 'bg-amber-500',
    },
    {
        chave: 'cnpj',
        titulo: 'Consultas de CNPJ',
        descricao: 'Empresas consultadas',
        valor: Number(props.quantidade_consultas_cnpj ?? 0),
        icone: FileSearch2,
        corIcone: 'bg-blue-50 text-blue-600',
        corDetalhe: 'bg-blue-600',
    },
]);

const distribuicao = computed(() => [
    {
        chave: 'cep',
        titulo: 'CEP',
        descricao: 'Consultas de endereço',
        valor: Number(props.quantidade_consultas_cep ?? 0),
        percentual: calcularPercentual(props.quantidade_consultas_cep),
        barra: 'bg-amber-500',
    },
    {
        chave: 'cnpj',
        titulo: 'CNPJ',
        descricao: 'Consultas empresariais',
        valor: Number(props.quantidade_consultas_cnpj ?? 0),
        percentual: calcularPercentual(props.quantidade_consultas_cnpj),
        barra: 'bg-blue-600',
    },
]);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout content-width="wide">
        <template #header>
            <div>
                <h2>Visão geral</h2>
                <p>Acompanhe os principais números da operação em uma leitura simples.</p>
            </div>
        </template>

        <div class="space-y-5">
            <section class="relative overflow-hidden rounded-lg border border-slate-200 bg-white px-6 py-6 shadow-sm sm:px-7">
                <div class="pointer-events-none absolute inset-y-0 right-0 hidden w-[38%] lg:block">
                    <div class="absolute inset-0 bg-gradient-to-l from-red-50/80 to-transparent" />
                    <div class="dashboard-lines absolute inset-0 opacity-60" />
                </div>

                <div class="relative flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <div class="flex items-center gap-2 text-[11px] font-bold uppercase tracking-[0.2em] text-red-500">
                            <Activity class="h-4 w-4" />
                            Operação em andamento
                        </div>
                        <h3 class="mt-3 font-display text-2xl font-extrabold tracking-tight text-slate-950 sm:text-[1.7rem]">
                            Olá, {{ primeiroNome }}.
                        </h3>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                            Este é o resumo atualizado das consultas realizadas na plataforma Nova Era.
                        </p>
                    </div>

                    <div class="flex shrink-0 items-center gap-3 border-t border-slate-100 pt-4 sm:border-l sm:border-t-0 sm:pl-6 sm:pt-0">
                        <span class="relative flex h-3 w-3">
                            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-40" />
                            <span class="relative inline-flex h-3 w-3 rounded-full bg-emerald-500" />
                        </span>
                        <div>
                            <p class="text-xs font-bold text-slate-700">Sistema disponível</p>
                            <p class="mt-0.5 text-[11px] text-slate-400">Dados atualizados agora</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="grid gap-4 md:grid-cols-3">
                <article
                    v-for="metrica in metricas"
                    :key="metrica.chave"
                    class="group relative overflow-hidden rounded-lg border border-slate-200 bg-white p-5 shadow-sm transition duration-200 hover:-translate-y-0.5 hover:border-slate-300 hover:shadow-md"
                >
                    <span class="absolute inset-x-0 top-0 h-0.5" :class="metrica.corDetalhe" />

                    <div class="flex items-start justify-between gap-4">
                        <div class="flex h-10 w-10 items-center justify-center rounded-md" :class="metrica.corIcone">
                            <component :is="metrica.icone" class="h-5 w-5" />
                        </div>
                        <ArrowUpRight class="h-4 w-4 text-slate-300 transition group-hover:text-red-500" />
                    </div>

                    <p class="mt-5 font-display text-3xl font-extrabold tracking-tight text-slate-950">
                        {{ metrica.valor.toLocaleString('pt-BR') }}
                    </p>
                    <p class="mt-1 text-sm font-bold text-slate-700">
                        {{ metrica.titulo }}
                    </p>
                    <p class="mt-1 text-xs leading-5 text-slate-400">
                        {{ metrica.descricao }}
                    </p>
                </article>
            </section>

            <section class="grid gap-5 lg:grid-cols-[1.45fr,0.75fr]">
                <article class="rounded-lg border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-5">
                        <div>
                            <p class="text-[11px] font-bold uppercase tracking-[0.2em] text-slate-400">
                                Distribuição
                            </p>
                            <h3 class="mt-2 font-display text-lg font-extrabold tracking-tight text-slate-900">
                                Volume por tipo de consulta
                            </h3>
                        </div>
                        <div class="flex h-9 w-9 items-center justify-center rounded-md bg-slate-100 text-slate-500">
                            <BarChart3 class="h-[18px] w-[18px]" />
                        </div>
                    </div>

                    <div class="mt-6 space-y-6">
                        <div v-for="item in distribuicao" :key="item.chave">
                            <div class="flex items-end justify-between gap-4">
                                <div>
                                    <p class="text-sm font-bold text-slate-700">{{ item.titulo }}</p>
                                    <p class="mt-0.5 text-xs text-slate-400">{{ item.descricao }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-extrabold text-slate-800">{{ item.percentual }}%</p>
                                    <p class="text-[11px] text-slate-400">{{ item.valor }} registros</p>
                                </div>
                            </div>
                            <div class="mt-3 h-2 overflow-hidden rounded-full bg-slate-100">
                                <div
                                    class="h-full rounded-full transition-all duration-700"
                                    :class="item.barra"
                                    :style="{ width: `${item.percentual}%` }"
                                />
                            </div>
                        </div>
                    </div>
                </article>

                <aside class="relative overflow-hidden rounded-lg border border-red-100 bg-red-50 p-6">
                    <div class="absolute -bottom-16 -right-16 h-44 w-44 rounded-full border-[28px] border-white/40" />

                    <div class="relative">
                        <div class="flex h-10 w-10 items-center justify-center rounded-md bg-red-600 text-white shadow-sm">
                            <Building2 class="h-5 w-5" />
                        </div>
                        <p class="mt-6 text-[11px] font-bold uppercase tracking-[0.2em] text-red-500">
                            Central Nova Era
                        </p>
                        <h3 class="mt-2 font-display text-xl font-extrabold leading-7 tracking-tight text-slate-900">
                            Informação objetiva para decisões mais rápidas.
                        </h3>
                        <p class="mt-3 text-sm leading-6 text-slate-600">
                            Use o menu lateral para acessar cadastros, consultas e conteúdos administrativos.
                        </p>
                    </div>
                </aside>
            </section>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.dashboard-lines {
    background-image:
        linear-gradient(rgba(220, 38, 38, 0.055) 1px, transparent 1px),
        linear-gradient(90deg, rgba(220, 38, 38, 0.055) 1px, transparent 1px);
    background-size: 28px 28px;
}
</style>
