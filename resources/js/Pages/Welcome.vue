<script setup>
import ChatButton from '@/Components/ChatButton.vue';
import LinksSection from '@/Components/LinksSection.vue';
import MovingEventGallery from '@/Components/MovingEventGallery.vue';
import VideoGallery from '@/Components/VideoGallery.vue';
import { Head } from '@inertiajs/vue3';
import {
    ArrowRight,
    Award,
    Building2,
    Check,
    ChevronDown,
    Clock3,
    Facebook,
    Instagram,
    LockKeyhole,
    Mail,
    MapPin,
    Menu,
    MessageCircle,
    Phone,
    Search,
    ShieldCheck,
    Sparkles,
    X,
    Zap,
} from 'lucide-vue-next';
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';

const props = defineProps({
    estampadoras: {
        type: Array,
        default: () => [],
    },
    produtos: {
        type: Array,
        default: () => [],
    },
    informativoAtivo: {
        type: Object,
        default: null,
    },
});

const menuAberto = ref(false);
const paginaNoTopo = ref(true);
const faqAberta = ref(0);
const buscaLoja = ref('');
const showInformativo = ref(
    Boolean(props.informativoAtivo?.habilitado && props.informativoAtivo?.titulo),
);
let sectionObserver = null;

const navItems = [
    { label: 'Início', id: 'home' },
    { label: 'Empresa', id: 'sobre' },
    { label: 'Produtos', id: 'produtos' },
    { label: 'Unidades', id: 'lojas' },
    { label: 'Dúvidas', id: 'duvidas' },
    { label: 'Conteúdo', id: 'midia' },
];

const indicadores = [
    { value: '25+', label: 'anos de experiência', icon: Clock3 },
    { value: 'ISO 9001', label: 'qualidade certificada', icon: Award },
    { value: 'Mercosul', label: 'padrão e conformidade', icon: ShieldCheck },
    { value: 'Ágil', label: 'atendimento especializado', icon: Zap },
];

const diferenciais = [
    'Matéria-prima de alto padrão e procedência.',
    'Equipe preparada para um atendimento rápido e seguro.',
    'Produção alinhada às normas vigentes do setor.',
];

const faqs = [
    {
        question: 'Como realizar a troca das placas antigas para o padrão Mercosul?',
        answer: 'Primeiro, faça a vistoria em uma empresa credenciada pelo DETRAN/MA. Depois, solicite a mudança apresentando os documentos exigidos. Com a guia de emplacamento, procure uma de nossas unidades para realizar a estampagem e a instalação.',
    },
    {
        question: 'Perdi uma das placas. Como solicito outra?',
        answer: 'Faça a vistoria em uma empresa credenciada e solicite a substituição no DETRAN. Após o pagamento da taxa e a emissão da guia, procure uma de nossas lojas para a confecção e instalação da nova placa.',
    },
    {
        question: 'Como funciona o primeiro emplacamento?',
        answer: 'O primeiro emplacamento deve ser iniciado pelo site de emplacamento digital. Tenha em mãos a nota fiscal do veículo e os documentos do proprietário para realizar as etapas de validação.',
    },
    {
        question: 'Posso instalar a placa em casa?',
        answer: 'Não. A instalação deve ser realizada por um profissional credenciado, pois o processo exige validações de segurança antes da emissão do CRLV.',
    },
    {
        question: 'Apenas o proprietário pode solicitar uma nova placa?',
        answer: 'Não necessariamente. O proprietário pode ser representado por procuração, desde que o documento atenda às exigências vigentes e descreva corretamente o veículo e o serviço solicitado.',
    },
    {
        question: 'Ainda é possível obter placas cinzas?',
        answer: 'Não. A fabricação e a estampagem das placas antigas foram encerradas com a adoção do padrão Mercosul. Veículos com placas cinzas em bom estado só precisam substituí-las nos casos previstos pela regulamentação.',
    },
    {
        question: 'A placa é entregue na hora?',
        answer: 'A estampagem e a instalação são rápidas, mas dependem da emissão da Guia de Emplacamento e das autorizações necessárias pelo DETRAN/MA.',
    },
    {
        question: 'Quais formas de pagamento são aceitas?',
        answer: 'Aceitamos cartão de débito, crédito, boleto bancário e PIX. Os pagamentos precisam ser rastreáveis, conforme as regras aplicáveis ao setor.',
    },
];

const linksUteis = [
    {
        label: 'Emplacamento Digital',
        description: 'Inicie seu processo de emplacamento.',
        url: 'https://www.detran.ma.gov.br/inicio/paginas/Pagina.xhtml?id=23611',
        icon: 'car',
    },
    {
        label: 'Portarias',
        description: 'Consulte normas e regulamentações.',
        url: 'https://www.detran.ma.gov.br/inicio/paginas/Portarias.xhtml',
        icon: 'file',
    },
    {
        label: 'Parcelamento de débitos',
        description: 'Veja opções oficiais de parcelamento.',
        url: 'https://www.detran.ma.gov.br/inicio/paginas/Pagina.xhtml?id=22878',
        icon: 'wallet',
    },
    {
        label: 'Vistoria',
        description: 'Informações sobre vistoria veicular.',
        url: 'https://www.detran.ma.gov.br/inicio/paginas/Pagina.xhtml?id=23581',
        icon: 'search',
    },
    {
        label: 'Credenciados',
        description: 'Consulte empresas credenciadas.',
        url: 'https://www.detran.ma.gov.br/inicio/paginas/Credenciado.xhtml',
        icon: 'badge',
    },
    {
        label: 'Ciretrans',
        description: 'Localize uma unidade de atendimento.',
        url: 'https://www.detran.ma.gov.br/inicio/paginas/ListarCiretrans.xhtml',
        icon: 'building',
    },
    {
        label: 'Multas PRF',
        description: 'Acesse a consulta oficial da PRF.',
        url: 'https://nadaconsta.prf.gov.br/nada_consta/index.jsf',
        icon: 'ticket',
    },
    {
        label: 'Multas DNIT',
        description: 'Consulte infrações no portal do DNIT.',
        url: 'https://servicos.dnit.gov.br/multas/',
        icon: 'gavel',
    },
    {
        label: 'CEP dos Municípios',
        description: 'Pesquise endereços nos Correios.',
        url: 'https://buscacepinter.correios.com.br/app/endereco/index.php',
        icon: 'map',
    },
];

const normalizarTexto = (value) =>
    String(value ?? '')
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase();

const lojasFiltradas = computed(() => {
    const termo = normalizarTexto(buscaLoja.value.trim());

    if (!termo) {
        return props.estampadoras;
    }

    return props.estampadoras.filter((loja) => {
        const endereco = loja.endereco ?? {};
        const conteudo = [
            loja.nome,
            endereco.cidade,
            endereco.bairro,
            endereco.uf,
            endereco.logradouro,
        ]
            .map(normalizarTexto)
            .join(' ');

        return conteudo.includes(termo);
    });
});

const formatarNumero = (numero) => String(numero ?? '').replace(/\D/g, '');

const enderecoLoja = (loja) => {
    const endereco = loja?.endereco ?? {};

    return [
        endereco.logradouro,
        endereco.numero,
        endereco.bairro,
        [endereco.cidade, endereco.uf].filter(Boolean).join(' - '),
    ]
        .filter(Boolean)
        .join(', ');
};

const mapsUrl = (loja) => {
    const endereco = loja?.endereco ?? {};

    if (endereco.latitude && endereco.longitude) {
        return `https://www.google.com/maps?q=${endereco.latitude},${endereco.longitude}`;
    }

    return `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(enderecoLoja(loja))}`;
};

const scrollToSection = (sectionId) => {
    menuAberto.value = false;
    document.getElementById(sectionId)?.scrollIntoView({
        behavior: 'smooth',
        block: 'start',
    });
};

const handleScroll = () => {
    paginaNoTopo.value = window.scrollY < 24;
};

const handleKeydown = (event) => {
    if (event.key === 'Escape') {
        menuAberto.value = false;
        showInformativo.value = false;
    }
};

onMounted(() => {
    handleScroll();
    window.addEventListener('scroll', handleScroll, { passive: true });
    window.addEventListener('keydown', handleKeydown);

    sectionObserver = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    sectionObserver?.unobserve(entry.target);
                }
            });
        },
        {
            threshold: 0.12,
            rootMargin: '0px 0px -8% 0px',
        },
    );

    document.querySelectorAll('[data-reveal]').forEach((element) => {
        sectionObserver?.observe(element);
    });
});

onBeforeUnmount(() => {
    window.removeEventListener('scroll', handleScroll);
    window.removeEventListener('keydown', handleKeydown);
    sectionObserver?.disconnect();
});
</script>

<template>
    <Head title="Início">
        <meta
            name="description"
            content="Placas veiculares Mercosul com qualidade, segurança e atendimento especializado. Encontre a unidade Nova Era mais próxima."
        />
    </Head>

    <div class="landing-page min-h-screen overflow-x-hidden bg-[#f7f7f5] text-slate-900">
        <header
            :class="paginaNoTopo
                ? 'border-transparent bg-slate-950/10 text-white'
                : 'border-slate-200 bg-white/95 text-slate-900 shadow-[0_8px_30px_rgba(15,23,42,0.08)]'"
            class="fixed inset-x-0 top-0 z-50 border-b transition-all duration-300 backdrop-blur-xl"
        >
            <div class="mx-auto flex h-[76px] max-w-[1480px] items-center justify-between gap-6 px-5 lg:px-8">
                <button
                    type="button"
                    class="flex shrink-0 items-center gap-3 text-left"
                    aria-label="Voltar ao início"
                    @click="scrollToSection('home')"
                >
                    <img
                        src="/images/logo.jpg"
                        alt="Nova Era Placas"
                        class="h-12 w-auto rounded-md border border-white/20 bg-white object-contain shadow-sm"
                    />
                    <span class="hidden xl:block">
                        <strong class="block font-sans text-sm tracking-[0.16em]">NOVA ERA</strong>
                        <span
                            :class="paginaNoTopo ? 'text-white/65' : 'text-slate-500'"
                            class="block text-[10px] font-semibold uppercase tracking-[0.18em]"
                        >
                            Placas veiculares
                        </span>
                    </span>
                </button>

                <nav class="hidden items-center gap-1 lg:flex" aria-label="Navegação principal">
                    <button
                        v-for="item in navItems"
                        :key="item.id"
                        type="button"
                        :class="paginaNoTopo
                            ? 'text-white/80 hover:bg-white/10 hover:text-white'
                            : 'text-slate-600 hover:bg-slate-100 hover:text-slate-950'"
                        class="rounded-md px-3 py-2 text-sm font-bold transition"
                        @click="scrollToSection(item.id)"
                    >
                        {{ item.label }}
                    </button>
                </nav>

                <div class="flex items-center gap-2">
                    <a
                        href="/login"
                        class="hidden items-center gap-2 rounded-md bg-red-600 px-4 py-2.5 text-sm font-bold text-white shadow-[0_8px_20px_rgba(220,38,38,0.25)] transition hover:bg-red-700 sm:inline-flex"
                    >
                        <LockKeyhole class="h-4 w-4" />
                        Área Restrita
                    </a>

                    <button
                        type="button"
                        :class="paginaNoTopo
                            ? 'border-white/20 bg-white/10 text-white'
                            : 'border-slate-200 bg-white text-slate-700'"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-md border lg:hidden"
                        :aria-expanded="menuAberto"
                        aria-label="Abrir menu"
                        @click="menuAberto = !menuAberto"
                    >
                        <X v-if="menuAberto" class="h-5 w-5" />
                        <Menu v-else class="h-5 w-5" />
                    </button>
                </div>
            </div>

            <transition name="mobile-menu">
                <div
                    v-if="menuAberto"
                    class="border-t border-slate-200 bg-white px-5 py-4 text-slate-900 shadow-xl lg:hidden"
                >
                    <nav class="mx-auto grid max-w-[1480px] gap-1" aria-label="Navegação mobile">
                        <button
                            v-for="item in navItems"
                            :key="`mobile-${item.id}`"
                            type="button"
                            class="rounded-md px-3 py-3 text-left text-sm font-bold hover:bg-slate-100"
                            @click="scrollToSection(item.id)"
                        >
                            {{ item.label }}
                        </button>
                        <a
                            href="/login"
                            class="mt-2 inline-flex items-center justify-center gap-2 rounded-md bg-red-600 px-4 py-3 text-sm font-bold text-white sm:hidden"
                        >
                            <LockKeyhole class="h-4 w-4" />
                            Área Restrita
                        </a>
                    </nav>
                </div>
            </transition>
        </header>

        <div
            v-if="showInformativo && informativoAtivo"
            class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-950/65 p-4 backdrop-blur-sm"
            role="dialog"
            aria-modal="true"
            :aria-label="informativoAtivo.titulo"
            @click.self="showInformativo = false"
        >
            <div class="relative w-full max-w-2xl overflow-hidden rounded-lg border border-slate-200 bg-white shadow-2xl">
                <div class="flex items-start justify-between gap-4 border-b border-slate-200 px-5 py-4">
                    <div class="border-l-2 border-red-500 pl-3">
                        <p class="text-[10px] font-bold uppercase tracking-[0.22em] text-red-600">
                            Comunicado
                        </p>
                        <h2 class="mt-1 font-sans text-lg font-bold text-slate-900">
                            {{ informativoAtivo.titulo }}
                        </h2>
                    </div>
                    <button
                        type="button"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-md border border-slate-200 text-slate-500 hover:bg-slate-50"
                        aria-label="Fechar comunicado"
                        @click="showInformativo = false"
                    >
                        <X class="h-5 w-5" />
                    </button>
                </div>
                <img
                    v-if="informativoAtivo.imagem"
                    :src="`/storage/${informativoAtivo.imagem}`"
                    :alt="informativoAtivo.titulo"
                    class="max-h-[70vh] w-full object-contain"
                />
            </div>
        </div>

        <main>
            <section
                id="home"
                class="relative flex min-h-[760px] scroll-mt-20 items-center overflow-hidden bg-slate-950 pt-28 text-white"
            >
                <img
                    src="/images/background.jpg"
                    alt="Veículo com placa padrão Mercosul"
                    class="absolute inset-0 h-full w-full object-cover object-center"
                    fetchpriority="high"
                />
                <div class="absolute inset-0 bg-[linear-gradient(90deg,rgba(9,14,27,0.95)_0%,rgba(9,14,27,0.82)_42%,rgba(9,14,27,0.28)_78%,rgba(9,14,27,0.54)_100%)]" />
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_78%_24%,rgba(220,38,38,0.28),transparent_28%)]" />
                <div class="absolute inset-0 hero-grid opacity-20" />

                <div class="relative mx-auto w-full max-w-[1480px] px-5 pb-32 lg:px-8">
                    <div class="max-w-3xl" data-reveal>
                        <div class="inline-flex items-center gap-2 border-l-2 border-red-500 pl-3 text-xs font-bold uppercase tracking-[0.25em] text-white/75">
                            <Sparkles class="h-4 w-4 text-red-400" />
                            Experiência, segurança e procedência
                        </div>

                        <h1 class="site-heading mt-7 text-4xl font-bold leading-[1.04] tracking-[-0.025em] sm:text-5xl lg:text-6xl">
                            Placa Mercosul<br />
                            <span class="text-red-500">é na Nova Era.</span>
                        </h1>

                        <p class="mt-6 max-w-2xl text-base font-medium leading-8 text-white/70 sm:text-lg">
                            Atendimento especializado, produção de alto padrão e uma rede preparada
                            para cuidar do seu emplacamento com rapidez e segurança.
                        </p>

                        <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                            <a
                                href="https://wa.me/559884209099"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="inline-flex items-center justify-center gap-2 rounded-md bg-red-600 px-6 py-3.5 text-sm font-extrabold text-white shadow-[0_14px_30px_rgba(220,38,38,0.32)] transition hover:-translate-y-0.5 hover:bg-red-700"
                            >
                                <MessageCircle class="h-5 w-5" />
                                Falar pelo WhatsApp
                            </a>
                            <button
                                type="button"
                                class="inline-flex items-center justify-center gap-2 rounded-md border border-white/25 bg-white/10 px-6 py-3.5 text-sm font-extrabold text-white backdrop-blur-sm transition hover:bg-white/20"
                                @click="scrollToSection('lojas')"
                            >
                                <MapPin class="h-5 w-5" />
                                Encontrar uma unidade
                            </button>
                        </div>

                        <div class="mt-10 flex flex-wrap gap-x-6 gap-y-3 text-sm font-semibold text-white/75">
                            <span class="inline-flex items-center gap-2">
                                <Check class="h-4 w-4 text-emerald-400" /> Melhor qualidade
                            </span>
                            <span class="inline-flex items-center gap-2">
                                <Check class="h-4 w-4 text-emerald-400" /> Mais agilidade
                            </span>
                            <span class="inline-flex items-center gap-2">
                                <Check class="h-4 w-4 text-emerald-400" /> Maior segurança
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="relative z-10 -mt-20 px-5 lg:px-8" aria-label="Indicadores da Nova Era">
                <div
                    class="mx-auto grid max-w-[1320px] overflow-hidden rounded-lg border border-slate-200 bg-white shadow-[0_24px_70px_rgba(15,23,42,0.14)] sm:grid-cols-2 lg:grid-cols-4"
                    data-reveal
                >
                    <div
                        v-for="indicador in indicadores"
                        :key="indicador.label"
                        class="flex items-center gap-4 border-b border-slate-200 px-5 py-5 last:border-b-0 sm:[&:nth-child(odd)]:border-r lg:border-b-0 lg:border-r lg:last:border-r-0"
                    >
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-red-50 text-red-600">
                            <component :is="indicador.icon" class="h-5 w-5" />
                        </div>
                        <div>
                            <strong class="block font-sans text-lg font-extrabold text-slate-950">
                                {{ indicador.value }}
                            </strong>
                            <span class="text-xs font-semibold text-slate-500">
                                {{ indicador.label }}
                            </span>
                        </div>
                    </div>
                </div>
            </section>

            <section id="sobre" class="scroll-mt-20 px-5 py-24 lg:px-8 lg:py-32">
                <div class="mx-auto grid max-w-[1320px] gap-14 lg:grid-cols-[0.88fr,1.12fr] lg:items-center">
                    <div data-reveal="left">
                        <div class="overflow-hidden rounded-md border border-slate-200 bg-white shadow-[0_24px_60px_rgba(15,23,42,0.10)]">
                            <img
                                src="/images/foto2.jpg"
                                alt="Equipe e estrutura da Nova Era Placas"
                                class="block h-auto w-full object-contain"
                                loading="lazy"
                                decoding="async"
                            />
                            <div class="flex items-center gap-4 border-t border-slate-200 bg-white px-5 py-4">
                                <strong class="site-heading shrink-0 text-xl font-bold text-red-600">25 anos</strong>
                                <span class="h-8 w-px bg-slate-200"></span>
                                <p class="text-xs font-semibold leading-5 text-slate-500">
                                construindo confiança em cada atendimento.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div data-reveal="right">
                        <p class="section-eyebrow">Quem somos</p>
                        <h2 class="section-title mt-4">
                            Qualidade que acompanha cada etapa do seu veículo.
                        </h2>
                        <p class="mt-6 text-base leading-8 text-slate-600">
                            A Nova Era Placas é referência no fornecimento de placas de identificação
                            veicular. Unimos experiência, atendimento próximo, certificação ISO 9001 e
                            produção alinhada à ISO 7591 para entregar soluções seguras e em conformidade.
                        </p>

                        <ul class="mt-7 space-y-4">
                            <li
                                v-for="diferencial in diferenciais"
                                :key="diferencial"
                                class="flex items-start gap-3 text-sm font-semibold leading-6 text-slate-700"
                            >
                                <span class="mt-0.5 inline-flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700">
                                    <Check class="h-3.5 w-3.5" />
                                </span>
                                {{ diferencial }}
                            </li>
                        </ul>

                        <div class="mt-9 grid gap-3 sm:grid-cols-2">
                            <div class="flex items-center gap-4 rounded-lg border border-slate-200 bg-white p-4">
                                <img src="/images/selo.png" alt="Selo de qualidade" class="h-14 w-16 object-contain" loading="lazy" />
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Procedência</p>
                                    <p class="mt-1 text-sm font-extrabold text-slate-900">Qualidade comprovada</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4 rounded-lg border border-slate-200 bg-white p-4">
                                <img src="/images/iso.png" alt="Certificação ISO 9001" class="h-14 w-16 object-contain" loading="lazy" />
                                <div>
                                    <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Certificação</p>
                                    <p class="mt-1 text-sm font-extrabold text-slate-900">Gestão ISO 9001</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section
                id="produtos"
                class="scroll-mt-20 border-y border-slate-200 bg-white px-5 py-24 lg:px-8"
            >
                <div class="mx-auto max-w-[1320px]">
                    <div class="flex flex-col gap-5 md:flex-row md:items-end md:justify-between" data-reveal>
                        <div class="max-w-2xl">
                            <p class="section-eyebrow">Nossos produtos</p>
                            <h2 class="section-title mt-4">Soluções para diferentes tipos de veículo.</h2>
                        </div>
                        <p class="max-w-md text-sm leading-7 text-slate-500">
                            Produtos fabricados com materiais selecionados para oferecer acabamento,
                            resistência e conformidade.
                        </p>
                    </div>

                    <div v-if="produtos.length" class="mt-12 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                        <article
                            v-for="(produto, index) in produtos"
                            :key="produto.id ?? index"
                            class="group overflow-hidden rounded-lg border border-slate-200 bg-[#fafafa] transition duration-300 hover:-translate-y-1 hover:border-red-200 hover:shadow-[0_20px_45px_rgba(15,23,42,0.09)]"
                            data-reveal
                            :style="{ '--reveal-delay': `${(index % 3) * 90}ms` }"
                        >
                            <div class="overflow-hidden border-b border-slate-200 bg-white">
                                <img
                                    :src="produto.imagem"
                                    :alt="produto.nome"
                                    class="aspect-[4/3] w-full object-cover transition duration-500 group-hover:scale-[1.025]"
                                    loading="lazy"
                                    decoding="async"
                                />
                            </div>
                            <div class="p-5">
                                <div class="flex items-start justify-between gap-4">
                                    <h3 class="site-heading text-lg font-semibold text-slate-950">
                                        {{ produto.nome }}
                                    </h3>
                                    <span class="mt-1 h-2 w-2 shrink-0 rounded-full bg-red-500"></span>
                                </div>
                                <p class="mt-3 text-sm leading-6 text-slate-600">
                                    {{ produto.descricao }}
                                </p>
                                <p v-if="produto.exibir_valor" class="mt-5 text-lg font-extrabold text-blue-700">
                                    {{ produto.valor }}
                                </p>
                            </div>
                        </article>
                    </div>

                    <div v-else class="mt-10 rounded-lg border border-dashed border-slate-300 bg-slate-50 px-6 py-12 text-center">
                        <p class="text-sm font-bold text-slate-700">Produtos disponíveis em nossas unidades.</p>
                        <p class="mt-1 text-xs text-slate-500">Fale com nossa equipe para conhecer as opções.</p>
                    </div>
                </div>
            </section>

            <section id="lojas" class="scroll-mt-20 px-5 py-24 lg:px-8">
                <div class="mx-auto max-w-[1320px]">
                    <div class="grid gap-8 lg:grid-cols-[1fr,0.8fr] lg:items-end" data-reveal>
                        <div>
                            <p class="section-eyebrow">Onde estamos</p>
                            <h2 class="section-title mt-4">Encontre a Nova Era mais próxima.</h2>
                            <p class="mt-5 max-w-2xl text-sm leading-7 text-slate-600">
                                Todas as unidades cadastradas estão disponíveis abaixo. Pesquise por
                                cidade, bairro ou nome e acesse rapidamente o WhatsApp e a localização.
                            </p>
                        </div>

                        <label class="relative block">
                            <span class="sr-only">Pesquisar unidade</span>
                            <Search class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-slate-400" />
                            <input
                                v-model="buscaLoja"
                                type="search"
                                placeholder="Digite uma cidade ou unidade"
                                class="w-full rounded-lg border-slate-300 bg-white py-3.5 pl-12 pr-4 text-sm font-semibold shadow-sm focus:border-red-500 focus:ring-red-500"
                            />
                        </label>
                    </div>

                    <div v-if="lojasFiltradas.length" class="mt-10 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <article
                            v-for="(loja, index) in lojasFiltradas"
                            :key="loja.id ?? index"
                            class="overflow-hidden rounded-lg border border-slate-200 bg-white transition duration-300 hover:border-red-200 hover:shadow-[0_16px_40px_rgba(15,23,42,0.08)]"
                            data-reveal
                            :style="{ '--reveal-delay': `${(index % 4) * 70}ms` }"
                        >
                            <img
                                v-if="loja.imagem"
                                :src="loja.imagem"
                                :alt="loja.nome"
                                class="h-40 w-full border-b border-slate-100 bg-slate-50 object-cover"
                                loading="lazy"
                                decoding="async"
                            />
                            <div v-else class="flex h-28 items-center justify-center border-b border-slate-100 bg-slate-50 text-slate-300">
                                <Building2 class="h-9 w-9" />
                            </div>

                            <div class="p-4">
                                <p class="text-[10px] font-bold uppercase tracking-[0.18em] text-red-600">
                                    {{ loja.endereco?.cidade || 'Unidade Nova Era' }}
                                </p>
                                <h3 class="site-heading mt-2 text-base font-semibold text-slate-950">
                                    {{ loja.nome }}
                                </h3>
                                <p class="mt-2 text-[11px] font-semibold text-slate-400">
                                    CNPJ {{ loja.cnpj_formatado }}
                                </p>
                                <p class="mt-3 min-h-[42px] text-xs leading-5 text-slate-500">
                                    {{ enderecoLoja(loja) || 'Endereço disponível com nosso atendimento.' }}
                                </p>

                                <div class="mt-4 grid grid-cols-2 gap-2 border-t border-slate-100 pt-4">
                                    <a
                                        :href="`https://wa.me/55${formatarNumero(loja.telefone)}`"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center justify-center gap-2 rounded-md bg-emerald-600 px-3 py-2 text-xs font-bold text-white hover:bg-emerald-700"
                                    >
                                        <Phone class="h-3.5 w-3.5" /> WhatsApp
                                    </a>
                                    <a
                                        :href="mapsUrl(loja)"
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        class="inline-flex items-center justify-center gap-2 rounded-md border border-slate-200 px-3 py-2 text-xs font-bold text-slate-700 hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700"
                                    >
                                        <MapPin class="h-3.5 w-3.5" /> Mapa
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>

                    <div v-else class="mt-10 rounded-lg border border-dashed border-slate-300 bg-white px-6 py-12 text-center">
                        <MapPin class="mx-auto h-8 w-8 text-slate-300" />
                        <p class="mt-3 text-sm font-bold text-slate-700">Nenhuma unidade encontrada.</p>
                        <p class="mt-1 text-xs text-slate-500">Tente pesquisar por outra cidade ou bairro.</p>
                    </div>
                </div>
            </section>

            <section id="duvidas" class="scroll-mt-20 border-y border-slate-200 bg-slate-950 px-5 py-24 text-white lg:px-8">
                <div class="mx-auto grid max-w-[1320px] gap-12 lg:grid-cols-[0.72fr,1.28fr]">
                    <div data-reveal="left">
                        <p class="text-xs font-bold uppercase tracking-[0.24em] text-red-400">Dúvidas frequentes</p>
                        <h2 class="site-heading mt-4 text-3xl font-semibold leading-tight tracking-[-0.02em] sm:text-4xl">
                            Informação simples para você decidir com segurança.
                        </h2>
                        <p class="mt-5 max-w-md text-sm leading-7 text-white/60">
                            Reunimos respostas para as principais dúvidas sobre emplacamento, instalação e pagamento.
                        </p>
                        <a
                            href="https://wa.me/559884209099"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="mt-8 inline-flex items-center gap-2 rounded-md bg-red-600 px-5 py-3 text-sm font-bold text-white hover:bg-red-700"
                        >
                            Ainda tenho uma dúvida
                            <ArrowRight class="h-4 w-4" />
                        </a>
                    </div>

                    <div class="divide-y divide-white/10 border-y border-white/15" data-reveal="right">
                        <article v-for="(faq, index) in faqs" :key="faq.question">
                            <button
                                type="button"
                                class="flex w-full items-center justify-between gap-5 py-5 text-left"
                                :aria-expanded="faqAberta === index"
                                @click="faqAberta = faqAberta === index ? null : index"
                            >
                                <span class="text-sm font-bold leading-6 text-white sm:text-base">
                                    {{ faq.question }}
                                </span>
                                <span class="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md border border-white/15 text-white/60">
                                    <ChevronDown :class="{ 'rotate-180': faqAberta === index }" class="h-4 w-4 transition" />
                                </span>
                            </button>
                            <div v-show="faqAberta === index">
                                <p class="max-w-3xl pb-5 pr-12 text-sm leading-7 text-white/60">
                                    {{ faq.answer }}
                                </p>
                            </div>
                        </article>
                    </div>
                </div>
            </section>

            <section id="midia" class="scroll-mt-20 bg-white px-5 py-24 lg:px-8">
                <VideoGallery />
            </section>

            <section id="galeria" class="scroll-mt-20 px-5 py-24 lg:px-8">
                <MovingEventGallery />
            </section>

            <section id="links" class="scroll-mt-20 border-t border-slate-200 bg-white px-5 py-24 lg:px-8">
                <LinksSection :links="linksUteis" />

                <div class="mx-auto mt-10 max-w-[1320px] border-t border-slate-200 pt-6 text-xs leading-6 text-slate-500">
                    <p>
                        <strong class="text-slate-700">Aviso de isenção de responsabilidade:</strong>
                        este site não possui vínculo com o Departamento Estadual de Trânsito (DETRAN)
                        ou outro órgão governamental. Os links oficiais são disponibilizados apenas para
                        facilitar o acesso a informações públicas.
                    </p>
                </div>
            </section>
        </main>

        <footer class="border-t-4 border-red-600 bg-[#0d1321] px-5 py-12 text-white lg:px-8">
            <div class="mx-auto grid max-w-[1320px] gap-10 md:grid-cols-[1.2fr,0.8fr,0.8fr]">
                <div>
                    <div class="flex items-center gap-3">
                        <img src="/images/logo.jpg" alt="Nova Era Placas" class="h-12 w-auto rounded-md bg-white object-contain" />
                        <div>
                            <p class="font-sans text-sm font-bold tracking-[0.15em]">NOVA ERA</p>
                            <p class="text-xs text-white/50">Placas veiculares</p>
                        </div>
                    </div>
                    <p class="mt-5 max-w-md text-sm leading-7 text-white/55">
                        Qualidade, segurança e atendimento especializado para o seu emplacamento.
                    </p>
                </div>

                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-white/40">Navegação</p>
                    <div class="mt-4 grid gap-3">
                        <button
                            v-for="item in navItems.slice(1, 5)"
                            :key="`footer-${item.id}`"
                            type="button"
                            class="w-fit text-sm font-semibold text-white/65 hover:text-white"
                            @click="scrollToSection(item.id)"
                        >
                            {{ item.label }}
                        </button>
                    </div>
                </div>

                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-white/40">Fale conosco</p>
                    <div class="mt-4 flex gap-2">
                        <a href="https://www.instagram.com/novaera.placass/" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="Instagram">
                            <Instagram class="h-5 w-5" />
                        </a>
                        <a href="https://www.facebook.com/people/Nova-Era-Placas/61559921292385/" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="Facebook">
                            <Facebook class="h-5 w-5" />
                        </a>
                        <a href="https://wa.me/559884209099" target="_blank" rel="noopener noreferrer" class="social-link" aria-label="WhatsApp">
                            <MessageCircle class="h-5 w-5" />
                        </a>
                        <a href="mailto:novaeraplacas@yahoo.com" class="social-link" aria-label="E-mail">
                            <Mail class="h-5 w-5" />
                        </a>
                    </div>
                </div>
            </div>

            <div class="mx-auto mt-10 flex max-w-[1320px] flex-col gap-3 border-t border-white/10 pt-6 text-xs text-white/40 sm:flex-row sm:items-center sm:justify-between">
                <p>© {{ new Date().getFullYear() }} Nova Era Placas. Todos os direitos reservados.</p>
                <a href="https://www.detran.ma.gov.br/inicio/paginas/Home.xhtml" target="_blank" rel="noopener noreferrer" class="hover:text-white">
                    Acessar site oficial do DETRAN/MA
                </a>
            </div>
        </footer>

        <ChatButton />
    </div>
</template>

<style scoped>
.hero-grid {
    background-image:
        linear-gradient(rgba(255, 255, 255, 0.08) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255, 255, 255, 0.08) 1px, transparent 1px);
    background-size: 52px 52px;
}

.section-eyebrow {
    font-size: 0.7rem;
    font-weight: 800;
    letter-spacing: 0.24em;
    text-transform: uppercase;
    color: #dc2626;
}

.section-title {
    max-width: 48rem;
    font-family: 'IBM Plex Sans', Manrope, sans-serif;
    font-size: clamp(1.75rem, 2.5vw, 2.35rem);
    font-weight: 600;
    line-height: 1.16;
    letter-spacing: -0.02em;
    color: #0f172a;
}

.social-link {
    display: inline-flex;
    height: 2.75rem;
    width: 2.75rem;
    align-items: center;
    justify-content: center;
    border: 1px solid rgba(255, 255, 255, 0.14);
    border-radius: 0.375rem;
    color: rgba(255, 255, 255, 0.68);
    transition: 180ms ease;
}

.social-link:hover {
    border-color: rgba(248, 113, 113, 0.65);
    background: rgba(220, 38, 38, 0.18);
    color: white;
}

[data-reveal] {
    opacity: 0;
    transform: translate3d(0, 34px, 0);
    transition:
        opacity 750ms cubic-bezier(0.16, 1, 0.3, 1),
        transform 750ms cubic-bezier(0.16, 1, 0.3, 1);
    transition-delay: var(--reveal-delay, 0ms);
}

[data-reveal='left'] {
    transform: translate3d(-38px, 0, 0);
}

[data-reveal='right'] {
    transform: translate3d(38px, 0, 0);
}

[data-reveal].is-visible {
    opacity: 1;
    transform: translate3d(0, 0, 0);
}

.mobile-menu-enter-active,
.mobile-menu-leave-active {
    transition: opacity 180ms ease, transform 180ms ease;
}

.mobile-menu-enter-from,
.mobile-menu-leave-to {
    opacity: 0;
    transform: translateY(-8px);
}

@media (prefers-reduced-motion: reduce) {
    [data-reveal] {
        opacity: 1;
        transform: none;
        transition: none;
    }
}
</style>
