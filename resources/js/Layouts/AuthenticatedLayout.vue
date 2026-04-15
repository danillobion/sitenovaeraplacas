<script setup>
import { computed, nextTick, onMounted, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import {
    BellRing,
    ClipboardList,
    Factory,
    FileSearch2,
    LayoutDashboard,
    LogOut,
    MapPinned,
    Menu,
    Package,
    PanelLeftClose,
    PanelLeftOpen,
    ShieldCheck,
    Truck,
    UserCircle2,
    Users,
    X,
} from 'lucide-vue-next';

const props = defineProps({
    contentWidth: {
        type: String,
        default: 'default',
    },
});

const showingNavigationDropdown = ref(false);
const sidebarCollapsed = ref(false);
const sidebarNav = ref(null);
const SIDEBAR_SCROLL_KEY = 'novaera-sidebar-scroll';

const page = usePage();
const auth = computed(() => page.props.auth ?? {});
const currentUser = computed(() => auth.value.user ?? {});
const permissionNames = computed(() => auth.value.permissions ?? []);
const showSidebarText = computed(
    () => !sidebarCollapsed.value || showingNavigationDropdown.value,
);

const contentWidthClass = computed(() => {
    if (props.contentWidth === 'wide') {
        return 'max-w-[1600px]';
    }

    if (props.contentWidth === 'full') {
        return 'max-w-none';
    }

    return 'max-w-7xl';
});

const mainPaddingClass = computed(() =>
    props.contentWidth === 'wide' || props.contentWidth === 'full'
        ? 'px-4 py-6 sm:px-5 lg:px-6 xl:px-8'
        : 'px-4 py-6 sm:px-6 lg:px-8',
);

const userHasPermission = (permission) => {
    if (!permission) {
        return true;
    }

    return permissionNames.value.includes(permission);
};

const initials = computed(() => {
    const nome = currentUser.value?.nome ?? 'Nova Era';

    return nome
        .split(' ')
        .filter(Boolean)
        .slice(0, 2)
        .map((parte) => parte[0]?.toUpperCase())
        .join('');
});

const navigationSections = computed(() => {
    const sections = [
        {
            label: 'Visão geral',
            items: [
                {
                    label: 'Dashboard',
                    description: 'Resumo do sistema',
                    routeName: 'dashboard',
                    matches: ['dashboard'],
                    icon: LayoutDashboard,
                    permission: null,
                },
            ],
        },
        {
            label: 'Cadastros',
            items: [
                {
                    label: 'Estampadoras',
                    description: 'Parceiros e operação',
                    routeName: 'estampadora.index',
                    matches: ['estampadora.*'],
                    icon: Factory,
                    permission: 'tela-listagem-estampadoras',
                },
                {
                    label: 'Produtos',
                    description: 'Catálogo comercial',
                    routeName: 'produtos.index',
                    matches: ['produtos.*', 'produto.*'],
                    icon: Package,
                    permission: 'tela-listagem-produtos',
                },
                {
                    label: 'Usuários',
                    description: 'Equipe e acessos',
                    routeName: 'usuarios.index',
                    matches: ['usuarios.*', 'usuario.*'],
                    icon: Users,
                    permission: 'tela-listagem-usuarios',
                },
                {
                    label: 'Tipos de usuários',
                    description: 'Perfis e permissões',
                    routeName: 'tipos.index',
                    matches: ['tipos.*', 'tipo.*', 'permissao.*'],
                    icon: ShieldCheck,
                    permission: 'tela-tipos-usuarios',
                },
            ],
        },
        {
            label: 'Consultas',
            items: [
                {
                    label: 'CEP',
                    description: 'Endereço e localização',
                    routeName: 'consultar.cep.index',
                    matches: ['consultar.cep.*'],
                    icon: MapPinned,
                    permission: 'tela-consultar-cep',
                },
                {
                    label: 'CNPJ',
                    description: 'Consulta empresarial',
                    routeName: 'consultar.cnpj.index',
                    matches: ['consultar.cnpj.index', 'tela.consultar.cnpj', 'consultar.cnpj'],
                    icon: FileSearch2,
                    permission: 'tela-consultar-cnpj',
                },
                {
                    label: 'SENATRAN',
                    description: 'Serviços integrados',
                    routeName: 'senatran.index',
                    matches: ['senatran.*'],
                    icon: ClipboardList,
                    permission: 'tela-senatran',
                },
                {
                    label: 'Encomenda',
                    description: 'Status de pedidos',
                    routeName: 'encomenda.index',
                    matches: ['encomenda.*'],
                    icon: Truck,
                    permission: 'tela-encomenda',
                },
            ],
        },
        {
            label: 'Conteúdo',
            items: [
                {
                    label: 'Informativo',
                    description: 'Comunicados ativos',
                    routeName: 'informativos.index',
                    matches: ['informativos.*', 'informativo.*'],
                    icon: BellRing,
                    permission: 'tela-informativo',
                },
            ],
        },
    ];

    return sections
        .map((section) => ({
            ...section,
            items: section.items.filter((item) => userHasPermission(item.permission)),
        }))
        .filter((section) => section.items.length > 0);
});

const flatNavigation = computed(() =>
    navigationSections.value.flatMap((section) => section.items),
);

const activeItem = computed(() => {
    return (
        flatNavigation.value.find((item) =>
            item.matches.some((routeName) => route().current(routeName)),
        ) ?? flatNavigation.value[0]
    );
});

const isActive = (item) => item.matches.some((routeName) => route().current(routeName));

const saveSidebarScroll = () => {
    if (!sidebarNav.value) {
        return;
    }

    sessionStorage.setItem(SIDEBAR_SCROLL_KEY, String(sidebarNav.value.scrollTop));
};

onMounted(async () => {
    await nextTick();

    const savedScroll = sessionStorage.getItem(SIDEBAR_SCROLL_KEY);

    if (sidebarNav.value && savedScroll !== null) {
        sidebarNav.value.scrollTop = Number(savedScroll);
    }
});
</script>

<template>
    <div class="min-h-screen bg-[#f6f3f0] text-slate-800">
        <div class="flex min-h-screen">
            <div
                v-if="showingNavigationDropdown"
                class="fixed inset-0 z-30 bg-slate-950/30 lg:hidden"
                @click="showingNavigationDropdown = false"
            />

            <aside
                :class="[
                    showingNavigationDropdown ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
                    sidebarCollapsed ? 'lg:w-[5.75rem]' : 'lg:w-[19rem]',
                ]"
                class="fixed inset-y-0 left-0 z-40 flex w-[19rem] flex-col border-r border-slate-200 bg-white transition-all duration-300"
            >
                <div class="flex items-center justify-between border-b border-slate-200 px-4 py-4">
                    <div class="flex items-center gap-3">
                        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white">
                            <img
                                src="/images/logo.jpg"
                                alt="Nova Era Placas"
                                class="h-11 w-[4.5rem] object-cover"
                            />
                        </div>

                        <div v-if="showSidebarText" class="min-w-0">
                            <p class="font-display truncate text-sm font-extrabold uppercase tracking-[0.18em] text-red-600">
                                Nova Era
                            </p>
                            <p class="truncate text-xs text-slate-500">
                                Painel administrativo
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        <button
                            class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition hover:border-red-200 hover:text-red-600 lg:hidden"
                            @click="showingNavigationDropdown = false"
                        >
                            <X class="h-5 w-5" />
                        </button>

                        <button
                            class="hidden h-10 w-10 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition hover:border-red-200 hover:text-red-600 lg:inline-flex"
                            @click="sidebarCollapsed = !sidebarCollapsed"
                        >
                            <PanelLeftOpen v-if="sidebarCollapsed" class="h-5 w-5" />
                            <PanelLeftClose v-else class="h-5 w-5" />
                        </button>
                    </div>
                </div>

                <div class="border-b border-slate-200 px-4 py-4">
                    <div
                        v-if="showSidebarText"
                        class="rounded-2xl border border-red-100 bg-red-50 px-3 py-3"
                    >
                        <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-red-400">
                            Area atual
                        </p>
                        <p class="mt-1 text-sm font-semibold text-slate-800">
                            {{ activeItem?.label ?? 'Painel' }}
                        </p>
                    </div>
                </div>

                <nav
                    ref="sidebarNav"
                    class="flex-1 space-y-6 overflow-y-auto px-3 py-4"
                    @scroll="saveSidebarScroll"
                >
                    <div
                        v-for="section in navigationSections"
                        :key="section.label"
                        class="space-y-2"
                    >
                        <div
                            v-if="showSidebarText"
                            class="px-3 text-[11px] font-bold uppercase tracking-[0.24em] text-slate-400"
                        >
                            {{ section.label }}
                        </div>

                        <Link
                            v-for="item in section.items"
                            :key="item.routeName"
                            :href="route(item.routeName)"
                            :class="[
                                isActive(item)
                                    ? 'border-red-200 bg-red-50 text-red-700'
                                    : 'border-transparent text-slate-600 hover:border-slate-200 hover:bg-slate-50 hover:text-slate-900',
                                sidebarCollapsed && !showingNavigationDropdown ? 'justify-center px-0' : 'px-3',
                            ]"
                            class="group flex items-center gap-3 rounded-xl border py-3 transition-all duration-200"
                            @click="showingNavigationDropdown = false"
                        >
                            <div
                                :class="isActive(item) ? 'bg-red-600 text-white' : 'bg-white text-slate-500 ring-1 ring-slate-200 group-hover:text-red-600'"
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg transition"
                            >
                                <component :is="item.icon" class="h-[18px] w-[18px]" />
                            </div>

                            <div
                                v-if="showSidebarText"
                                class="min-w-0 flex-1"
                            >
                                <p class="truncate text-sm font-semibold">
                                    {{ item.label }}
                                </p>
                                <p class="truncate text-xs text-slate-400">
                                    {{ item.description }}
                                </p>
                            </div>
                        </Link>
                    </div>
                </nav>
            </aside>

            <div
                :class="sidebarCollapsed ? 'lg:pl-[5.75rem]' : 'lg:pl-[19rem]'"
                class="flex min-h-screen min-w-0 flex-1 flex-col transition-all duration-300"
            >
                <header class="sticky top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur">
                    <div class="flex items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                        <div class="flex min-w-0 items-center gap-3">
                            <button
                                class="inline-flex h-10 w-10 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-600 transition hover:border-red-200 hover:text-red-600 lg:hidden"
                                @click="showingNavigationDropdown = true"
                            >
                                <Menu class="h-5 w-5" />
                            </button>

                            <div class="min-w-0">
                                <p class="text-[11px] font-bold uppercase tracking-[0.28em] text-red-400">
                                    Nova Era Placas
                                </p>
                                <p class="truncate text-sm font-semibold text-slate-600">
                                    {{ activeItem?.description ?? 'Painel administrativo' }}
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="hidden items-center gap-2 rounded-full border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-500 lg:inline-flex">
                                <span class="inline-flex h-2.5 w-2.5 rounded-full bg-emerald-500" />
                                Sistema ativo
                            </div>

                            <div class="flex items-center gap-3 rounded-[18px] border border-slate-200 bg-white px-3 py-3 shadow-sm">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-red-600 text-sm font-black tracking-[0.14em] text-white">
                                    {{ initials }}
                                </div>

                                <div class="hidden min-w-0 sm:block">
                                    <p class="truncate text-sm font-bold text-slate-800">
                                        {{ currentUser.nome }}
                                    </p>
                                    <p class="truncate text-xs text-slate-500">
                                        {{ currentUser.email }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-2 border-l border-slate-200 pl-3">
                                    <Link
                                        :href="route('profile.edit')"
                                        class="inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-3 text-sm font-semibold text-slate-700 transition hover:border-red-200 hover:text-red-600"
                                    >
                                        <UserCircle2 class="h-4 w-4" />
                                        <span class="hidden sm:inline">Perfil</span>
                                    </Link>

                                    <Link
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                        class="inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-3 text-sm font-semibold text-slate-700 transition hover:border-red-200 hover:text-red-600"
                                    >
                                        <LogOut class="h-4 w-4" />
                                        <span class="hidden sm:inline">Sair</span>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="flex-1" :class="mainPaddingClass">
                    <div class="mx-auto w-full" :class="contentWidthClass">
                        <div class="mb-8 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                            <div class="min-w-0">
                                <div
                                    v-if="$slots.header"
                                    class="space-y-2 [&>div>h2]:font-display [&>div>h2]:text-[2rem] [&>div>h2]:font-extrabold [&>div>h2]:tracking-tight [&>div>h2]:text-slate-900 [&>div>p]:max-w-2xl [&>div>p]:text-sm [&>div>p]:leading-6 [&>div>p]:text-slate-500 [&>h2]:font-display [&>h2]:text-[2rem] [&>h2]:font-extrabold [&>h2]:tracking-tight [&>h2]:text-slate-900 [&>p]:max-w-2xl [&>p]:text-sm [&>p]:leading-6 [&>p]:text-slate-500"
                                >
                                    <slot name="header" />
                                </div>

                                <template v-else>
                                    <h1 class="font-display text-[2rem] font-extrabold tracking-tight text-slate-900">
                                        {{ activeItem?.label ?? 'Painel administrativo' }}
                                    </h1>
                                    <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                                        {{ activeItem?.description ?? 'Controle completo das rotinas internas em uma interface mais objetiva.' }}
                                    </p>
                                </template>
                            </div>

                            
                        </div>

                        <slot />
                    </div>
                </main>
            </div>
        </div>
    </div>
</template>
