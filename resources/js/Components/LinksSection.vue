<script setup>
import {
    ArrowUpRight,
    BadgeCheck,
    Building2,
    Car,
    FileText,
    Gavel,
    MapPin,
    Search,
    Ticket,
    WalletCards,
} from 'lucide-vue-next';

defineProps({
    links: {
        type: Array,
        required: true,
    },
});

const iconMap = {
    badge: BadgeCheck,
    building: Building2,
    car: Car,
    file: FileText,
    gavel: Gavel,
    map: MapPin,
    search: Search,
    ticket: Ticket,
    wallet: WalletCards,
};
</script>

<template>
    <div class="mx-auto max-w-[1320px]">
        <div class="grid gap-8 lg:grid-cols-[0.7fr,1.3fr] lg:items-end">
            <div>
                <p class="text-[11px] font-extrabold uppercase tracking-[0.24em] text-red-600">
                    Acesso rápido
                </p>
                <h2 class="site-heading mt-4 text-3xl font-semibold leading-tight tracking-[-0.02em] text-slate-950 sm:text-4xl">
                    Serviços e links úteis.
                </h2>
            </div>
            <p class="max-w-xl text-sm leading-7 text-slate-500 lg:justify-self-end">
                Acesse páginas oficiais para consultar normas, credenciados, multas e informações sobre emplacamento.
            </p>
        </div>

        <div class="mt-12 grid border-l border-t border-slate-200 sm:grid-cols-2 lg:grid-cols-3">
            <a
                v-for="link in links"
                :key="link.url"
                :href="link.url"
                target="_blank"
                rel="noopener noreferrer"
                class="group flex min-h-[150px] items-start gap-4 border-b border-r border-slate-200 bg-white p-5 transition hover:bg-slate-50"
            >
                <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-red-50 text-red-600 transition group-hover:bg-red-600 group-hover:text-white">
                    <component :is="iconMap[link.icon] ?? ArrowUpRight" class="h-5 w-5" />
                </span>
                <span class="min-w-0 flex-1">
                    <span class="flex items-start justify-between gap-3">
                        <strong class="site-heading text-sm font-semibold leading-5 text-slate-900">
                            {{ link.label }}
                        </strong>
                        <ArrowUpRight class="h-4 w-4 shrink-0 text-slate-300 transition group-hover:text-red-500" />
                    </span>
                    <span class="mt-2 block text-xs leading-5 text-slate-500">
                        {{ link.description }}
                    </span>
                </span>
            </a>
        </div>
    </div>
</template>
