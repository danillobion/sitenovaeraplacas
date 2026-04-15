<script setup>
import MercosulPlatePreview from '@/Components/MercosulPlatePreview.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { computed, onBeforeUnmount, onMounted, reactive, ref, watch } from 'vue';
import {
    Building2,
    CalendarRange,
    ChevronDown,
    ChevronLeft,
    ChevronRight,
    ChevronUp,
    CircleDollarSign,
    FileSearch2,
    Filter,
    Hash,
    PackageSearch,
    RefreshCw,
    Search,
    ShieldCheck,
    SlidersHorizontal,
    Ticket,
    X,
} from 'lucide-vue-next';
import { showErrorAlert } from '@/lib/sweetAlert';

const props = defineProps({
    apiDisponivel: { type: Boolean, default: false },
    quantidadeMinima: { type: Number, default: 10 },
});

const quantidadeBase = computed(() => Math.max(props.quantidadeMinima ?? 10, 10));

const criarFiltrosPadrao = () => ({
    id: '',
    placa: '',
    cnpj_estampadora: '',
    situacao: 'ABE',
    situacao_pagamento: '',
    numero_autorizacao: '',
    numero_autorizacao_local: '',
    data_inicio_abertura: '',
    data_fim_abertura: '',
    quantidade: quantidadeBase.value,
    ordenacao: 'id_desc',
});

const filtros = reactive(criarFiltrosPadrao());
const filtrosAvancadosAbertos = ref(false);
const carregando = ref(false);
const consultaRealizada = ref(false);
const erroConsulta = ref('');
const errosValidacao = ref({});
const resultadoBruto = ref({});
const ultimaConsultaEm = ref(null);
const paginaAtual = ref(1);
const modalDetalhesAberta = ref(false);
const encomendaSelecionada = ref(null);

const situacoes = [
    { value: '', label: 'Todas as situações' },
    { value: 'ABE', label: 'Abertas' },
    { value: 'FIN', label: 'Finalizadas' },
    { value: 'CAN', label: 'Canceladas' },
];

const situacoesPagamento = [
    { value: '', label: 'Todos os pagamentos' },
    { value: 'PAG', label: 'Pago' },
    { value: 'EMT', label: 'Emitido' },
    { value: 'PEN', label: 'Pendente' },
    { value: 'CAN', label: 'Cancelado' },
];

const ordenacoes = [
    { value: 'id_desc', label: 'Novos primeiro - ID' },
    { value: 'id_asc', label: 'Antigos primeiro - ID' },
    { value: 'placa_asc', label: 'Placa A-Z' },
    { value: 'situacao_asc', label: 'Situação A-Z' },
];

const quantidades = [10, 25, 50, 100];

const digitsOnly = (value = '') => String(value).replace(/\D/g, '');

const normalizarPlaca = (value = '') =>
    String(value)
        .replace(/[^a-zA-Z0-9]/g, '')
        .toUpperCase()
        .slice(0, 7);

const formatarCNPJ = (value = '') => {
    const cnpj = digitsOnly(value).slice(0, 14);

    if (cnpj.length <= 2) {
        return cnpj;
    }

    if (cnpj.length <= 5) {
        return `${cnpj.slice(0, 2)}.${cnpj.slice(2)}`;
    }

    if (cnpj.length <= 8) {
        return `${cnpj.slice(0, 2)}.${cnpj.slice(2, 5)}.${cnpj.slice(5)}`;
    }

    if (cnpj.length <= 12) {
        return `${cnpj.slice(0, 2)}.${cnpj.slice(2, 5)}.${cnpj.slice(5, 8)}/${cnpj.slice(8)}`;
    }

    return `${cnpj.slice(0, 2)}.${cnpj.slice(2, 5)}.${cnpj.slice(5, 8)}/${cnpj.slice(8, 12)}-${cnpj.slice(12, 14)}`;
};

const formatarCPF = (value = '') => {
    const cpf = digitsOnly(value).slice(0, 11);

    if (cpf.length <= 3) {
        return cpf;
    }

    if (cpf.length <= 6) {
        return `${cpf.slice(0, 3)}.${cpf.slice(3)}`;
    }

    if (cpf.length <= 9) {
        return `${cpf.slice(0, 3)}.${cpf.slice(3, 6)}.${cpf.slice(6)}`;
    }

    return `${cpf.slice(0, 3)}.${cpf.slice(3, 6)}.${cpf.slice(6, 9)}-${cpf.slice(9, 11)}`;
};

const formatarDocumento = (value = '') => {
    const digits = digitsOnly(value);

    if (!digits) {
        return value ? String(value) : '-';
    }

    if (digits.length === 11) {
        return formatarCPF(digits);
    }

    if (digits.length === 14) {
        return formatarCNPJ(digits);
    }

    return digits;
};

const formatarTelefone = (value = '') => {
    const digits = digitsOnly(value);

    if (digits.length === 11) {
        return `(${digits.slice(0, 2)}) ${digits.slice(2, 7)}-${digits.slice(7, 11)}`;
    }

    if (digits.length === 10) {
        return `(${digits.slice(0, 2)}) ${digits.slice(2, 6)}-${digits.slice(6, 10)}`;
    }

    return value ? String(value) : '-';
};

const formatarCEP = (value = '') => {
    const digits = digitsOnly(value).slice(0, 8);

    if (digits.length <= 5) {
        return digits;
    }

    return `${digits.slice(0, 5)}-${digits.slice(5, 8)}`;
};

const formatarMoeda = (value) => {
    if (value === null || value === undefined || value === '') {
        return '-';
    }

    const normalizedValue = Number(String(value).replace(',', '.'));

    if (Number.isNaN(normalizedValue)) {
        return String(value);
    }

    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    }).format(normalizedValue);
};

const formatarFlagPlaca = (value) => {
    if (value === null || value === undefined || value === '') {
        return '-';
    }

    return Number(value) === 1 ? 'Sim' : 'Não';
};

const possuiValor = (value) =>
    value !== undefined &&
    value !== null &&
    !(typeof value === 'string' && value.trim() === '');

const textoAmigavel = (value = '') =>
    String(value)
        .replace(/_/g, ' ')
        .trim()
        .replace(/\s+/g, ' ')
        .replace(/^\w/, (letra) => letra.toUpperCase());

const formatarDataHora = (value) => {
    if (!value) {
        return '-';
    }

    const data = new Date(value);

    if (Number.isNaN(data.getTime())) {
        return String(value);
    }

    return new Intl.DateTimeFormat('pt-BR', {
        dateStyle: 'short',
        timeStyle: 'short',
    }).format(data);
};

const getValue = (source, path) =>
    path.split('.').reduce((accumulator, key) => {
        if (accumulator == null) {
            return undefined;
        }

        return accumulator[key];
    }, source);

const pickFirstValue = (source, paths) => {
    for (const path of paths) {
        const value = getValue(source, path);

        if (value !== undefined && value !== null && value !== '') {
            return value;
        }
    }

    return '';
};

const pickFirstPrimitive = (source, paths) => {
    for (const path of paths) {
        const value = getValue(source, path);

        if (
            value !== undefined &&
            value !== null &&
            value !== '' &&
            !Array.isArray(value) &&
            typeof value !== 'object'
        ) {
            return value;
        }
    }

    return '';
};

const arrayify = (value) => {
    if (Array.isArray(value)) {
        return value
            .flatMap((item) => {
                if (item == null || item === '') {
                    return [];
                }

                if (typeof item === 'object') {
                    return Object.values(item);
                }

                return item;
            })
            .map((item) => String(item).trim())
            .filter(Boolean);
    }

    if (value && typeof value === 'object') {
        return Object.values(value)
            .map((item) => String(item).trim())
            .filter(Boolean);
    }

    if (value !== undefined && value !== null && value !== '') {
        return String(value)
            .split(/[,\n;]+/)
            .map((item) => item.trim())
            .filter(Boolean);
    }

    return [];
};

const uniqueValues = (values) => [...new Set(values.filter(Boolean))];

const traduzirSituacao = (codigo, fallback = 'Não informado') => {
    const mapa = {
        ABE: 'Aberto',
        FIN: 'Finalizado',
        CAN: 'Cancelado',
        PEN: 'Pendente',
        PAG: 'Pago',
        EMT: 'Emitido',
        EXO: 'Exonerado',
    };

    const chave = String(codigo ?? '')
        .trim()
        .toUpperCase();

    if (!chave) {
        return fallback;
    }

    return mapa[chave] ?? textoAmigavel(chave.toLowerCase());
};

const classeStatus = (value) => {
    const codigo = String(value ?? '')
        .trim()
        .toUpperCase();

    if (['PAG', 'FIN'].includes(codigo)) {
        return 'border-emerald-200 bg-emerald-50 text-emerald-700';
    }

    if (['CAN'].includes(codigo)) {
        return 'border-red-200 bg-red-50 text-red-700';
    }

    if (['PEN', 'EMT'].includes(codigo)) {
        return 'border-amber-200 bg-amber-50 text-amber-700';
    }

    if (['ABE'].includes(codigo)) {
        return 'border-blue-200 bg-blue-50 text-blue-700';
    }

    return 'border-slate-200 bg-slate-100 text-slate-600';
};

const construirLocalizacao = (cidade, uf) =>
    [cidade, uf]
        .filter((item) => possuiValor(item))
        .join(' - ');

const isMotoVeiculo = (tipoVeiculo = '') =>
    ['MOTO', 'MOTOCICLETA', 'MOTONETA', 'CICLOMOTOR', 'TRICICLO'].some((item) =>
        String(tipoVeiculo || '')
            .toUpperCase()
            .includes(item),
    );

const construirCidadeUf = (item) => {
    const cidadeEntrega = pickFirstPrimitive(item, ['cidade_entrega']);
    const ufEntrega = pickFirstPrimitive(item, ['uf_entrega']);

    if (cidadeEntrega || ufEntrega) {
        return construirLocalizacao(cidadeEntrega, ufEntrega);
    }

    const cidadeProprietario = pickFirstPrimitive(item, ['cidade_proprietario']);
    const ufProprietario = pickFirstPrimitive(item, ['uf_proprietario']);

    if (cidadeProprietario || ufProprietario) {
        return construirLocalizacao(cidadeProprietario, ufProprietario);
    }

    const cidadeUf = pickFirstPrimitive(item, [
        'cidade_uf',
        'municipio_uf',
        'placa_cidade_uf',
        'localidade',
    ]);

    if (cidadeUf) {
        return cidadeUf;
    }

    const cidade = pickFirstPrimitive(item, [
        'cidade',
        'municipio',
        'placa_cidade',
        'estampadora.cidade',
        'loja_cidade',
    ]);
    const uf = pickFirstPrimitive(item, [
        'uf',
        'estado',
        'sigla_uf',
        'estampadora.uf',
        'loja_uf',
    ]);

    return construirLocalizacao(cidade, uf);
};

const normalizeEncomenda = (item, index) => {
    const pagamentoCodigo = pickFirstPrimitive(item, [
        'situacao_pagamento',
        'pagamento.situacao',
        'pagamento.status',
        'pagamento.codigo_status',
    ]);

    const documentosCodigo = pickFirstPrimitive(item, [
        'situacao_documentos',
        'documentos.situacao',
        'documentacao.situacao',
        'status_documentos',
    ]);

    const situacaoCodigo = pickFirstPrimitive(item, [
        'situacao',
        'status',
        'status_pedido',
    ]);

    const autorizacao = pickFirstPrimitive(item, [
        'numero_autorizacao',
        'autorizacao',
        'numero_os',
        'numero_encomenda',
    ]);

    const autorizacaoLocal = pickFirstPrimitive(item, [
        'numero_autorizacao_local',
        'autorizacao_local',
        'numero_os_local',
    ]);

    const tipoVeiculo = pickFirstPrimitive(item, ['tipo_veiculo']) || 'Não informado';
    const categoriaVeiculo =
        pickFirstPrimitive(item, ['categoria_veiculo']) || 'Não informado';
    const especieVeiculo = pickFirstPrimitive(item, ['especie_veiculo']) || 'Não informado';
    const nomeProprietario =
        pickFirstPrimitive(item, ['nome_proprietario']) || 'Não informado';
    const documentoProprietario = pickFirstPrimitive(item, ['documento_proprietario']);
    const nomeResponsavel = pickFirstPrimitive(item, ['nome_responsavel']);
    const documentoResponsavel = pickFirstPrimitive(item, ['documento_responsavel']);
    const nomeLocalEntrega = pickFirstPrimitive(item, ['nome_local_entrega']);
    const cidadeEntrega = pickFirstPrimitive(item, ['cidade_entrega']);
    const ufEntrega = pickFirstPrimitive(item, ['uf_entrega']);
    const cidadeProprietario = pickFirstPrimitive(item, ['cidade_proprietario']);
    const ufProprietario = pickFirstPrimitive(item, ['uf_proprietario']);
    const cnpjEstampador = pickFirstPrimitive(item, ['cnpj_estampador', 'cnpj_estampadora']);
    const chassi = pickFirstPrimitive(item, ['chassi']);
    const renavam = pickFirstPrimitive(item, ['renavam']);
    const dataAgendamento = pickFirstPrimitive(item, ['data_agendamento']);
    const placaDianteira = Number(pickFirstPrimitive(item, ['placa_dianteira']) || 0);
    const placaTraseira = Number(pickFirstPrimitive(item, ['placa_traseira']) || 0);
    const placaSegundaTraseira = Number(
        pickFirstPrimitive(item, ['placa_segunda_traseira']) || 0,
    );
    const ehMoto = isMotoVeiculo(tipoVeiculo);
    const localEntregaCidadeUf = construirLocalizacao(cidadeEntrega, ufEntrega);
    const proprietarioCidadeUf = construirLocalizacao(cidadeProprietario, ufProprietario);

    const serials = uniqueValues([
        ...arrayify(
            pickFirstValue(item, [
                'seriais',
                'serials',
                'serial',
                'numero_serial',
                'seriais_placas',
            ]),
        ),
        ...arrayify(
            pickFirstValue(item, [
                'serial_placa',
                'serial_placa_dianteira',
                'serial_placa_traseira',
            ]),
        ),
    ]);

    return {
        id:
            pickFirstPrimitive(item, [
                'id',
                'numero_os',
                'numero_encomenda',
                'pedido_id',
            ]) || index + 1,
        placa:
            normalizarPlaca(
            pickFirstPrimitive(item, [
                'placa',
                'placa_veiculo',
                'placa_formatada',
            ]),
        ) || '---',
        cidadeUf: construirCidadeUf(item) || 'Local não informado',
        cnpjEstampador,
        serials:
            serials.length > 0
                ? serials
                : [autorizacaoLocal, autorizacao].filter(Boolean),
        autorizacao: autorizacao || '-',
        autorizacaoLocal: autorizacaoLocal || '-',
        tipoVeiculo,
        categoriaVeiculo,
        especieVeiculo,
        nomeProprietario,
        documentoProprietario,
        nomeResponsavel,
        documentoResponsavel,
        nomeLocalEntrega: nomeLocalEntrega || 'Não informado',
        localEntregaCidadeUf: localEntregaCidadeUf || 'Não informado',
        proprietarioCidadeUf: proprietarioCidadeUf || 'Não informado',
        chassi: chassi || '-',
        renavam: renavam || '-',
        dataAgendamento,
        placaDianteira,
        placaTraseira,
        placaSegundaTraseira,
        ehMoto,
        configuracaoPlaca: ehMoto
            ? 'Moto'
            : placaDianteira === 1 && (placaTraseira === 1 || placaSegundaTraseira === 1)
              ? 'Par'
              : 'Unitária',
        pagamento: traduzirSituacao(pagamentoCodigo),
        pagamentoCodigo,
        documentos: traduzirSituacao(documentosCodigo),
        documentosCodigo,
        situacao: traduzirSituacao(situacaoCodigo),
        situacaoCodigo,
        abertura: pickFirstPrimitive(item, [
            'data_abertura',
            'created_at',
            'aberto_em',
            'data_criacao',
        ]),
        consultaEm:
            dataAgendamento ||
            pickFirstPrimitive(item, [
                'data_abertura',
                'created_at',
                'aberto_em',
                'data_criacao',
            ]),
        raw: item,
    };
};

const DETAIL_FIELD_LABELS = {
    id: 'ID',
    cnpj_estampador: 'CNPJ estampador',
    numero_autorizacao: 'N° autorização',
    numero_autorizacao_local: 'N° autorização local',
    placa: 'Placa',
    chassi: 'Chassi',
    renavam: 'Renavam',
    placa_dianteira: 'Placa dianteira',
    placa_traseira: 'Placa traseira',
    placa_segunda_traseira: '2ª placa traseira',
    tipo_veiculo: 'Tipo do veículo',
    categoria_veiculo: 'Categoria do veículo',
    especie_veiculo: 'Espécie do veículo',
    nome_proprietario: 'Nome do proprietário',
    documento_proprietario: 'Documento do proprietário',
    email_proprietario: 'E-mail do proprietário',
    telefone_proprietario: 'Telefone do proprietário',
    logradouro_proprietario: 'Logradouro do proprietário',
    numero_proprietario: 'Número do proprietário',
    bairro_proprietario: 'Bairro do proprietário',
    cidade_proprietario: 'Cidade do proprietário',
    uf_proprietario: 'UF do proprietário',
    complemento_proprietario: 'Complemento do proprietário',
    cep_proprietario: 'CEP do proprietário',
    nome_responsavel: 'Nome do responsável',
    documento_responsavel: 'Documento do responsável',
    email_responsavel: 'E-mail do responsável',
    telefone_responsavel: 'Telefone do responsável',
    tipo_entrega: 'Tipo de entrega',
    data_agendamento: 'Data de agendamento',
    documento_local_entrega: 'Documento do local de entrega',
    nome_local_entrega: 'Nome do local de entrega',
    endereco_entrega: 'Endereço de entrega',
    bairro_entrega: 'Bairro de entrega',
    cidade_entrega: 'Cidade de entrega',
    uf_entrega: 'UF de entrega',
    cep_entrega: 'CEP de entrega',
    valor_frete: 'Valor do frete',
    situacao: 'Situação',
    situacoes_blanks: 'Situações blanks',
    situacao_pagamento: 'Situação do pagamento',
    situacao_documentos: 'Situação dos documentos',
};

const DETAIL_SECTIONS_CONFIG = [
    {
        title: 'Identificação',
        fields: [
            'id',
            'placa',
            'numero_autorizacao',
            'numero_autorizacao_local',
            'cnpj_estampador',
            'tipo_veiculo',
            'categoria_veiculo',
            'especie_veiculo',
            'chassi',
            'renavam',
        ],
    },
    {
        title: 'Placas solicitadas',
        fields: [
            'placa_dianteira',
            'placa_traseira',
            'placa_segunda_traseira',
            'situacao',
            'situacoes_blanks',
            'situacao_pagamento',
            'situacao_documentos',
        ],
    },
    {
        title: 'Proprietário',
        fields: [
            'nome_proprietario',
            'documento_proprietario',
            'email_proprietario',
            'telefone_proprietario',
        ],
    },
    {
        title: 'Endereço do proprietário',
        fields: [
            'logradouro_proprietario',
            'numero_proprietario',
            'complemento_proprietario',
            'bairro_proprietario',
            'cidade_proprietario',
            'uf_proprietario',
            'cep_proprietario',
        ],
    },
    {
        title: 'Responsável',
        fields: [
            'nome_responsavel',
            'documento_responsavel',
            'email_responsavel',
            'telefone_responsavel',
        ],
    },
    {
        title: 'Entrega',
        fields: [
            'tipo_entrega',
            'data_agendamento',
            'documento_local_entrega',
            'nome_local_entrega',
            'endereco_entrega',
            'bairro_entrega',
            'cidade_entrega',
            'uf_entrega',
            'cep_entrega',
            'valor_frete',
        ],
    },
];

const formatarCampoDetalhe = (key, value) => {
    if (!possuiValor(value)) {
        return '-';
    }

    switch (key) {
        case 'cnpj_estampador':
        case 'documento_proprietario':
        case 'documento_responsavel':
        case 'documento_local_entrega':
            return formatarDocumento(value);
        case 'telefone_proprietario':
        case 'telefone_responsavel':
            return formatarTelefone(value);
        case 'cep_proprietario':
        case 'cep_entrega':
            return formatarCEP(value);
        case 'placa_dianteira':
        case 'placa_traseira':
        case 'placa_segunda_traseira':
            return formatarFlagPlaca(value);
        case 'situacao':
        case 'situacao_pagamento':
        case 'situacao_documentos':
            return traduzirSituacao(value);
        case 'valor_frete':
            return formatarMoeda(value);
        case 'data_agendamento':
            return formatarDataHora(value);
        default:
            if (Array.isArray(value) || typeof value === 'object') {
                return JSON.stringify(value);
            }

            return String(value);
    }
};

const camposDetalheConfigurados = new Set(
    DETAIL_SECTIONS_CONFIG.flatMap((section) => section.fields),
);

const extractEncomendas = (payload) => {
    const candidates = [
        payload?.encomendas,
        payload?.data?.encomendas,
        payload?.data,
        payload?.resultado?.encomendas,
        payload?.results,
    ];

    return candidates.find((item) => Array.isArray(item)) ?? [];
};

const compararTexto = new Intl.Collator('pt-BR', {
    numeric: true,
    sensitivity: 'base',
});

const encomendasNormalizadas = computed(() =>
    extractEncomendas(resultadoBruto.value).map((item, index) =>
        normalizeEncomenda(item, index),
    ),
);

const quantidadeSolicitada = computed(() =>
    Math.max(Number(filtros.quantidade) || quantidadeBase.value, quantidadeBase.value),
);

const encomendasOrdenadas = computed(() => {
    const lista = [...encomendasNormalizadas.value];

    switch (filtros.ordenacao) {
        case 'id_asc':
            return lista.sort((a, b) => Number(a.id || 0) - Number(b.id || 0));
        case 'placa_asc':
            return lista.sort((a, b) => compararTexto.compare(a.placa, b.placa));
        case 'situacao_asc':
            return lista.sort((a, b) => compararTexto.compare(a.situacao, b.situacao));
        case 'id_desc':
        default:
            return lista.sort((a, b) => Number(b.id || 0) - Number(a.id || 0));
    }
});

const totalEncontrado = computed(() => encomendasNormalizadas.value.length);
const totalPaginas = computed(() =>
    Math.max(Math.ceil(totalEncontrado.value / quantidadeSolicitada.value), 1),
);
const paginaAtualSegura = computed(() => Math.min(paginaAtual.value, totalPaginas.value));
const encomendasVisiveis = computed(() => {
    const inicio = (paginaAtualSegura.value - 1) * quantidadeSolicitada.value;

    return encomendasOrdenadas.value.slice(inicio, inicio + quantidadeSolicitada.value);
});
const totalExibido = computed(() => encomendasVisiveis.value.length);
const indiceInicialExibicao = computed(() =>
    totalEncontrado.value
        ? (paginaAtualSegura.value - 1) * quantidadeSolicitada.value + 1
        : 0,
);
const indiceFinalExibicao = computed(() =>
    totalEncontrado.value
        ? indiceInicialExibicao.value + totalExibido.value - 1
        : 0,
);
const paginasVisiveis = computed(() => {
    const total = totalPaginas.value;
    const atual = paginaAtualSegura.value;
    let inicio = Math.max(1, atual - 2);
    let fim = Math.min(total, inicio + 4);

    inicio = Math.max(1, fim - 4);

    return Array.from({ length: fim - inicio + 1 }, (_, index) => inicio + index);
});

const filtrosAtivos = computed(() => {
    const payload = montarPayload();

    return Object.entries(payload).filter(([key, value]) => {
        if (['quantidade'].includes(key)) {
            return false;
        }

        return value !== '' && value !== null && value !== undefined;
    }).length;
});

const ultimaConsultaFormatada = computed(() =>
    ultimaConsultaEm.value ? formatarDataHora(ultimaConsultaEm.value) : 'Ainda não consultado',
);

const mensagemResumo = computed(() => {
    if (!props.apiDisponivel) {
        return 'API não configurada neste ambiente.';
    }

    if (carregando.value) {
        return 'Atualizando a listagem de encomendas...';
    }

    if (erroConsulta.value) {
        return erroConsulta.value;
    }

    if (!consultaRealizada.value) {
        return `Pronta para consultar até ${quantidadeSolicitada.value} encomendas por vez.`;
    }

    if (!totalEncontrado.value) {
        return 'Nenhuma encomenda encontrada com os filtros atuais.';
    }

    return `Exibindo ${indiceInicialExibicao.value}-${indiceFinalExibicao.value} de ${totalEncontrado.value} encomendas retornadas pela API.`;
});

const detalhesEncomenda = computed(() => {
    const raw = encomendaSelecionada.value?.raw ?? {};

    if (!Object.keys(raw).length) {
        return [];
    }

    const sections = DETAIL_SECTIONS_CONFIG.map((section) => ({
        title: section.title,
        items: section.fields
            .map((field) => ({
                label: DETAIL_FIELD_LABELS[field] ?? textoAmigavel(field),
                value: formatarCampoDetalhe(field, raw[field]),
                rawValue: raw[field],
            }))
            .filter((item) => possuiValor(item.rawValue) || item.rawValue === 0),
    })).filter((section) => section.items.length > 0);

    const camposExtras = Object.entries(raw)
        .filter(([key, value]) => !camposDetalheConfigurados.has(key))
        .filter(([, value]) => possuiValor(value) || value === 0)
        .map(([key, value]) => ({
            label: DETAIL_FIELD_LABELS[key] ?? textoAmigavel(key),
            value:
                Array.isArray(value) || typeof value === 'object'
                    ? JSON.stringify(value)
                    : String(value),
        }));

    if (camposExtras.length > 0) {
        sections.push({
            title: 'Campos adicionais',
            items: camposExtras,
        });
    }

    return sections;
});

const resumoDetalhes = computed(() => {
    const item = encomendaSelecionada.value;

    if (!item) {
        return [];
    }

    return [
        {
            label: 'Veículo',
            value: item.tipoVeiculo,
            secondary: `${item.categoriaVeiculo} • ${item.especieVeiculo}`,
        },
        {
            label: 'Proprietário',
            value: item.nomeProprietario,
            secondary: formatarDocumento(item.documentoProprietario),
        },
        {
            label: 'Entrega',
            value: item.nomeLocalEntrega,
            secondary: item.localEntregaCidadeUf,
        },
        {
            label: 'Estampador',
            value: formatarDocumento(item.cnpjEstampador),
            secondary: `Configuração ${item.configuracaoPlaca}`,
        },
    ];
});

const campoComErro = (campo) => errosValidacao.value?.[campo]?.[0] ?? '';

const irParaPagina = (pagina) => {
    paginaAtual.value = Math.min(Math.max(Number(pagina) || 1, 1), totalPaginas.value);
};

const paginaAnterior = () => {
    if (paginaAtualSegura.value > 1) {
        irParaPagina(paginaAtualSegura.value - 1);
    }
};

const proximaPagina = () => {
    if (paginaAtualSegura.value < totalPaginas.value) {
        irParaPagina(paginaAtualSegura.value + 1);
    }
};

const abrirDetalhes = (item) => {
    encomendaSelecionada.value = {
        ...item,
        serials: Array.isArray(item?.serials) ? [...item.serials] : [],
        raw:
            item?.raw && typeof item.raw === 'object'
                ? { ...item.raw }
                : item?.raw ?? {},
    };
    modalDetalhesAberta.value = true;
};

const fecharDetalhes = () => {
    modalDetalhesAberta.value = false;
};

const atualizarPlaca = (event) => {
    filtros.placa = normalizarPlaca(event.target.value);
};

const atualizarCNPJ = (event) => {
    filtros.cnpj_estampadora = formatarCNPJ(event.target.value);
};

const montarPayload = () => {
    const payload = {
        id: filtros.id ? Number(filtros.id) : '',
        placa: normalizarPlaca(filtros.placa),
        cnpj_estampadora: digitsOnly(filtros.cnpj_estampadora),
        situacao: filtros.situacao,
        situacao_pagamento: filtros.situacao_pagamento,
        numero_autorizacao: filtros.numero_autorizacao?.trim() ?? '',
        numero_autorizacao_local: filtros.numero_autorizacao_local?.trim() ?? '',
        data_inicio_abertura: filtros.data_inicio_abertura,
        data_fim_abertura: filtros.data_fim_abertura,
        quantidade: quantidadeSolicitada.value,
    };

    const possuiFiltroReal = Object.entries(payload).some(([key, value]) => {
        if (key === 'quantidade') {
            return false;
        }

        return value !== '' && value !== null && value !== undefined;
    });

    if (!possuiFiltroReal) {
        payload.situacao = 'ABE';
    }

    return payload;
};

const consultarEncomendas = async ({ silenciosa = false } = {}) => {
    if (!props.apiDisponivel) {
        return;
    }

    paginaAtual.value = 1;
    carregando.value = true;
    consultaRealizada.value = true;
    erroConsulta.value = '';
    errosValidacao.value = {};
    resultadoBruto.value = {};

    try {
        const response = await axios.post(route('encomenda.consultar'), montarPayload());
        const data = response.data ?? {};

        if (data.error) {
            erroConsulta.value = data.message || 'Não foi possível consultar as encomendas.';

            if (!silenciosa) {
                showErrorAlert(erroConsulta.value);
            }

            return;
        }

        resultadoBruto.value = data;
        ultimaConsultaEm.value = new Date();
    } catch (error) {
        if (error.response?.status === 422) {
            errosValidacao.value = error.response.data.errors ?? {};
            erroConsulta.value = 'Revise os filtros destacados e tente novamente.';
        } else {
            erroConsulta.value =
                error.response?.data?.message ||
                'Não foi possível consultar as encomendas no momento.';
        }

        if (!silenciosa) {
            showErrorAlert(erroConsulta.value);
        }
    } finally {
        carregando.value = false;
    }
};

const limparFiltros = async () => {
    Object.assign(filtros, criarFiltrosPadrao());
    paginaAtual.value = 1;
    errosValidacao.value = {};
    erroConsulta.value = '';

    if (props.apiDisponivel) {
        await consultarEncomendas({ silenciosa: true });
        return;
    }

    consultaRealizada.value = false;
    resultadoBruto.value = {};
};

const handleEscape = (event) => {
    if (event.key === 'Escape' && modalDetalhesAberta.value) {
        fecharDetalhes();
    }
};

watch(modalDetalhesAberta, (isOpen) => {
    document.body.style.overflow = isOpen ? 'hidden' : '';
    document.documentElement.style.overflow = isOpen ? 'hidden' : '';

    if (!isOpen) {
        window.setTimeout(() => {
            encomendaSelecionada.value = null;
        }, 180);
    }
});

watch(quantidadeSolicitada, () => {
    paginaAtual.value = 1;
});

watch(
    () => filtros.ordenacao,
    () => {
        paginaAtual.value = 1;
    },
);

watch(totalPaginas, (total) => {
    if (paginaAtual.value > total) {
        paginaAtual.value = total;
    }
});

onMounted(async () => {
    window.addEventListener('keydown', handleEscape);

    if (!props.apiDisponivel) {
        return;
    }

    await consultarEncomendas({ silenciosa: true });
});

onBeforeUnmount(() => {
    window.removeEventListener('keydown', handleEscape);
    document.body.style.overflow = '';
    document.documentElement.style.overflow = '';
});
</script>

<template>
    <Head title="Consulta de Encomendas" />

    <AuthenticatedLayout content-width="wide">
        <template #header>
            <div>
                <h2>Consulta de Encomendas</h2>
                <p>Pesquisa operacional com filtros mais completos e foco total em consulta.</p>
            </div>
        </template>

        <div class="space-y-6">
            <section class="rounded-[18px] border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-100 px-6 py-6">
                    <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                        <div class="max-w-3xl">
                            <p class="text-xs font-bold uppercase tracking-[0.24em] text-blue-500">
                                Consulta apenas
                            </p>
                            <h3 class="mt-3 font-display text-[2rem] font-extrabold tracking-tight text-slate-900">
                                Localize placas e encomendas.
                            </h3>
                            <p class="mt-3 text-sm leading-6 text-slate-500">
                                 Utilize os filtros avançados para refinar sua busca e encontrar exatamente o que precisa de forma rápida e segura.
                            </p>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <div class="inline-flex items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-4 py-2 text-sm font-semibold text-blue-700">
                                <ShieldCheck class="h-4 w-4" />
                                Consulta segura
                            </div>

                            <button
                                type="button"
                                class="inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:border-blue-200 hover:text-blue-700"
                                @click="filtrosAvancadosAbertos = !filtrosAvancadosAbertos"
                            >
                                <SlidersHorizontal class="h-4 w-4" />
                                Filtros avançados
                                <ChevronUp
                                    v-if="filtrosAvancadosAbertos"
                                    class="h-4 w-4"
                                />
                                <ChevronDown
                                    v-else
                                    class="h-4 w-4"
                                />
                            </button>
                        </div>
                    </div>

                    <div
                        v-if="!apiDisponivel"
                        class="mt-5 rounded-[16px] border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800"
                    >
                        A API de encomendas não está configurada neste ambiente local. A tela já
                        está pronta para produção e passa a listar automaticamente assim que o
                        token estiver disponível.
                    </div>
                </div>

                <form class="space-y-5 px-6 py-6" @submit.prevent="consultarEncomendas()">
                    <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                        <label class="block">
                            <span class="mb-2 flex items-center gap-2 text-sm font-semibold text-slate-700">
                                <Hash class="h-4 w-4 text-blue-500" />
                                ID
                            </span>
                            <input
                                v-model="filtros.id"
                                type="number"
                                min="1"
                                placeholder="Digite o ID"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 outline-none transition focus:border-blue-300 focus:bg-white focus:ring-4 focus:ring-blue-100"
                            />
                            <p v-if="campoComErro('id')" class="mt-2 text-xs text-red-600">
                                {{ campoComErro('id') }}
                            </p>
                        </label>

                        <label class="block">
                            <span class="mb-2 flex items-center gap-2 text-sm font-semibold text-slate-700">
                                <Search class="h-4 w-4 text-blue-500" />
                                Placa
                            </span>
                            <input
                                :value="filtros.placa"
                                type="text"
                                maxlength="7"
                                placeholder="Digite a placa"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium uppercase text-slate-900 outline-none transition focus:border-blue-300 focus:bg-white focus:ring-4 focus:ring-blue-100"
                                @input="atualizarPlaca"
                            />
                            <p v-if="campoComErro('placa')" class="mt-2 text-xs text-red-600">
                                {{ campoComErro('placa') }}
                            </p>
                        </label>

                        <label class="block">
                            <span class="mb-2 flex items-center gap-2 text-sm font-semibold text-slate-700">
                                <Building2 class="h-4 w-4 text-blue-500" />
                                CNPJ Estampadora
                            </span>
                            <input
                                :value="filtros.cnpj_estampadora"
                                type="text"
                                placeholder="Digite o CNPJ"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 outline-none transition focus:border-blue-300 focus:bg-white focus:ring-4 focus:ring-blue-100"
                                @input="atualizarCNPJ"
                            />
                            <p
                                v-if="campoComErro('cnpj_estampadora')"
                                class="mt-2 text-xs text-red-600"
                            >
                                {{ campoComErro('cnpj_estampadora') }}
                            </p>
                        </label>

                        <label class="block">
                            <span class="mb-2 flex items-center gap-2 text-sm font-semibold text-slate-700">
                                <Filter class="h-4 w-4 text-blue-500" />
                                Situação
                            </span>
                            <select
                                v-model="filtros.situacao"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 outline-none transition focus:border-blue-300 focus:bg-white focus:ring-4 focus:ring-blue-100"
                            >
                                <option
                                    v-for="option in situacoes"
                                    :key="option.value || 'situacao-todas'"
                                    :value="option.value"
                                >
                                    {{ option.label }}
                                </option>
                            </select>
                            <p v-if="campoComErro('situacao')" class="mt-2 text-xs text-red-600">
                                {{ campoComErro('situacao') }}
                            </p>
                        </label>
                    </div>

                    <div
                        v-if="filtrosAvancadosAbertos"
                        class="grid gap-4 md:grid-cols-2 xl:grid-cols-4"
                    >
                        <label class="block">
                            <span class="mb-2 flex items-center gap-2 text-sm font-semibold text-slate-700">
                                <CircleDollarSign class="h-4 w-4 text-blue-500" />
                                Situação do pagamento
                            </span>
                            <select
                                v-model="filtros.situacao_pagamento"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 outline-none transition focus:border-blue-300 focus:bg-white focus:ring-4 focus:ring-blue-100"
                            >
                                <option
                                    v-for="option in situacoesPagamento"
                                    :key="option.value || 'pagamento-todos'"
                                    :value="option.value"
                                >
                                    {{ option.label }}
                                </option>
                            </select>
                            <p
                                v-if="campoComErro('situacao_pagamento')"
                                class="mt-2 text-xs text-red-600"
                            >
                                {{ campoComErro('situacao_pagamento') }}
                            </p>
                        </label>

                        <label class="block">
                            <span class="mb-2 flex items-center gap-2 text-sm font-semibold text-slate-700">
                                <Ticket class="h-4 w-4 text-blue-500" />
                                N° Autorização
                            </span>
                            <input
                                v-model="filtros.numero_autorizacao"
                                type="text"
                                placeholder="Digite o N° da autorização"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 outline-none transition focus:border-blue-300 focus:bg-white focus:ring-4 focus:ring-blue-100"
                            />
                            <p
                                v-if="campoComErro('numero_autorizacao')"
                                class="mt-2 text-xs text-red-600"
                            >
                                {{ campoComErro('numero_autorizacao') }}
                            </p>
                        </label>

                        <label class="block">
                            <span class="mb-2 flex items-center gap-2 text-sm font-semibold text-slate-700">
                                <Ticket class="h-4 w-4 text-blue-500" />
                                N° Autorização local
                            </span>
                            <input
                                v-model="filtros.numero_autorizacao_local"
                                type="text"
                                placeholder="Digite o N° local"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 outline-none transition focus:border-blue-300 focus:bg-white focus:ring-4 focus:ring-blue-100"
                            />
                            <p
                                v-if="campoComErro('numero_autorizacao_local')"
                                class="mt-2 text-xs text-red-600"
                            >
                                {{ campoComErro('numero_autorizacao_local') }}
                            </p>
                        </label>

                        <label class="block">
                            <span class="mb-2 flex items-center gap-2 text-sm font-semibold text-slate-700">
                                <PackageSearch class="h-4 w-4 text-blue-500" />
                                Quantidade
                            </span>
                            <select
                                v-model="filtros.quantidade"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 outline-none transition focus:border-blue-300 focus:bg-white focus:ring-4 focus:ring-blue-100"
                            >
                                <option
                                    v-for="quantidade in quantidades"
                                    :key="quantidade"
                                    :value="quantidade"
                                >
                                    {{ quantidade }}
                                </option>
                            </select>
                            <p v-if="campoComErro('quantidade')" class="mt-2 text-xs text-red-600">
                                {{ campoComErro('quantidade') }}
                            </p>
                        </label>

                        <label class="block">
                            <span class="mb-2 flex items-center gap-2 text-sm font-semibold text-slate-700">
                                <CalendarRange class="h-4 w-4 text-blue-500" />
                                Data de início de abertura
                            </span>
                            <input
                                v-model="filtros.data_inicio_abertura"
                                type="date"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 outline-none transition focus:border-blue-300 focus:bg-white focus:ring-4 focus:ring-blue-100"
                            />
                            <p
                                v-if="campoComErro('data_inicio_abertura')"
                                class="mt-2 text-xs text-red-600"
                            >
                                {{ campoComErro('data_inicio_abertura') }}
                            </p>
                        </label>

                        <label class="block">
                            <span class="mb-2 flex items-center gap-2 text-sm font-semibold text-slate-700">
                                <CalendarRange class="h-4 w-4 text-blue-500" />
                                Data de fim de abertura
                            </span>
                            <input
                                v-model="filtros.data_fim_abertura"
                                type="date"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 outline-none transition focus:border-blue-300 focus:bg-white focus:ring-4 focus:ring-blue-100"
                            />
                            <p
                                v-if="campoComErro('data_fim_abertura')"
                                class="mt-2 text-xs text-red-600"
                            >
                                {{ campoComErro('data_fim_abertura') }}
                            </p>
                        </label>

                        <label class="block md:col-span-2">
                            <span class="mb-2 flex items-center gap-2 text-sm font-semibold text-slate-700">
                                <Filter class="h-4 w-4 text-blue-500" />
                                Ordenação
                            </span>
                            <select
                                v-model="filtros.ordenacao"
                                class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 outline-none transition focus:border-blue-300 focus:bg-white focus:ring-4 focus:ring-blue-100"
                            >
                                <option
                                    v-for="ordenacao in ordenacoes"
                                    :key="ordenacao.value"
                                    :value="ordenacao.value"
                                >
                                    {{ ordenacao.label }}
                                </option>
                            </select>
                        </label>
                    </div>

                    <div class="flex flex-col gap-4 border-t border-slate-100 pt-5 xl:flex-row xl:items-center xl:justify-between">
                        <div class="flex flex-wrap items-center gap-2 text-sm">
                            <span class="inline-flex items-center rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 font-medium text-blue-700">
                                Base inicial: Abertas
                            </span>
                            <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 font-medium text-slate-600">
                                {{ filtrosAtivos }} filtros ativos
                            </span>
                            <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 font-medium text-slate-600">
                                Última atualização: {{ ultimaConsultaFormatada }}
                            </span>
                            <span class="inline-flex items-center rounded-full border border-blue-100 bg-blue-50 px-3 py-1.5 font-medium text-blue-700">
                                Exibição mínima: {{ quantidadeSolicitada }}
                            </span>
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row">
                            <button
                                type="button"
                                class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50"
                                @click="limparFiltros"
                            >
                                <RefreshCw class="h-4 w-4" />
                                Limpar filtros
                            </button>

                            <button
                                type="submit"
                                :disabled="carregando || !apiDisponivel"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:bg-blue-300"
                            >
                                <RefreshCw v-if="carregando" class="h-4 w-4 animate-spin" />
                                <Search v-else class="h-4 w-4" />
                                {{ carregando ? 'Consultando...' : 'Pesquisar' }}
                            </button>
                        </div>
                    </div>
                </form>
            </section>

            <section class="overflow-hidden rounded-[18px] border border-slate-200 bg-white shadow-sm">
                <div class="flex flex-col gap-4 border-b border-slate-100 px-6 py-5 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.24em] text-slate-400">
                            Resultado da consulta
                        </p>
                        <h3 class="mt-2 font-display text-2xl font-extrabold tracking-tight text-slate-900">
                            Encomendas localizadas
                        </h3>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            {{ mensagemResumo }}
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm font-semibold text-slate-600">
                            {{ totalEncontrado }} retornos
                        </span>
                        <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-sm font-semibold text-slate-600">
                            {{ totalExibido }} exibidos
                        </span>
                    </div>
                </div>

                <div
                    v-if="erroConsulta"
                    class="border-b border-red-100 bg-red-50 px-6 py-4 text-sm text-red-700"
                >
                    {{ erroConsulta }}
                </div>

                <div v-if="carregando" class="space-y-4 px-6 py-6">
                    <div
                        v-for="indice in 4"
                        :key="indice"
                        class="animate-pulse rounded-[16px] border border-slate-200 bg-slate-50 px-5 py-5"
                    >
                        <div class="grid gap-4 lg:grid-cols-[0.8fr,1.2fr,1.4fr,1fr,1fr,1fr]">
                            <div class="h-4 rounded bg-slate-200" />
                            <div class="h-16 rounded bg-slate-200" />
                            <div class="h-4 rounded bg-slate-200" />
                            <div class="h-10 rounded bg-slate-200" />
                            <div class="h-10 rounded bg-slate-200" />
                            <div class="h-10 rounded bg-slate-200" />
                        </div>
                    </div>
                </div>

                <div v-else-if="encomendasVisiveis.length">
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto">
                            <thead class="bg-slate-50 text-left">
                                <tr class="text-xs font-bold uppercase tracking-[0.2em] text-slate-500">
                                    <th class="px-4 py-4">Placa</th>
                                    <th class="px-4 py-4">Veículo / Proprietário</th>
                                    <th class="px-4 py-4">Estampador</th>
                                    <th class="px-4 py-4">Pagamento</th>
                                    <th class="px-4 py-4">Documentos</th>
                                    <th class="px-4 py-4">Situação</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-100">
                                <tr
                                    v-for="item in encomendasVisiveis"
                                    :key="`${item.id}-${item.placa}-${item.autorizacao}`"
                                    class="align-top transition hover:bg-slate-50/80"
                                >
                                    <td class="px-4 py-5">
                                        <button
                                            type="button"
                                            class="group flex w-full max-w-[14.5rem] flex-col items-center rounded-[20px] px-2 py-3 text-left transition hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-200"
                                            @click.stop.prevent="abrirDetalhes(item)"
                                        >
                                            <MercosulPlatePreview
                                                :placa="item.placa"
                                                :cidade-uf="item.cidadeUf"
                                                :tipo-veiculo="item.tipoVeiculo"
                                                :placa-dianteira="item.placaDianteira"
                                                :placa-traseira="item.placaTraseira"
                                                :placa-segunda-traseira="item.placaSegundaTraseira"
                                            />
                                            <span class="mt-3 inline-flex items-center rounded-full border border-blue-100 bg-blue-50 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.12em] text-blue-700 transition group-hover:border-blue-200 group-hover:bg-blue-100">
                                                Abrir detalhes
                                            </span>
                                        </button>
                                    </td>

                                    <td class="px-4 py-5">
                                        <p class="text-sm font-semibold uppercase text-slate-900">
                                            {{ item.tipoVeiculo }}
                                        </p>
                                        <p class="mt-1 text-xs text-slate-500">
                                            {{ item.categoriaVeiculo }} • {{ item.especieVeiculo }}
                                        </p>
                                        <div class="mt-3 space-y-1 text-sm text-slate-700">
                                            <p class="font-semibold text-slate-800">
                                                {{ item.nomeProprietario }}
                                            </p>
                                            <p class="text-xs text-slate-500">
                                                Doc. {{ formatarDocumento(item.documentoProprietario) }}
                                            </p>
                                        </div>
                                    </td>

                                    <td class="px-4 py-5">
                                        <div class="space-y-2 text-sm text-slate-700">
                                            <p class="font-semibold text-slate-800">
                                                {{ item.nomeLocalEntrega }}
                                            </p>
                                            <p class="text-xs text-slate-500">
                                                {{ item.localEntregaCidadeUf }}
                                            </p>
                                            <p class="text-xs text-slate-500">
                                                Responsável
                                                {{ item.nomeResponsavel || 'Não informado' }}
                                            </p>
                                            <p class="text-xs text-slate-500">
                                                Estampador
                                                {{ formatarDocumento(item.cnpjEstampador) }}
                                            </p>
                                            <p class="text-xs text-slate-500">
                                                Configuração {{ item.configuracaoPlaca }}
                                            </p>
                                        </div>
                                    </td>

                                    <td class="px-4 py-5">
                                        <div class="space-y-2">
                                            <span
                                                class="inline-flex rounded-full border px-3 py-1.5 text-xs font-semibold"
                                                :class="classeStatus(item.pagamentoCodigo)"
                                            >
                                                {{ item.pagamento }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="px-4 py-5">
                                        <div class="space-y-2">
                                            <span
                                                class="inline-flex rounded-full border px-3 py-1.5 text-xs font-semibold"
                                                :class="classeStatus(item.documentosCodigo)"
                                            >
                                                {{ item.documentos }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="px-4 py-5">
                                        <span
                                            class="inline-flex rounded-full border px-3 py-1.5 text-xs font-semibold"
                                            :class="classeStatus(item.situacaoCodigo)"
                                        >
                                            {{ item.situacao }}
                                        </span>
                                        <div class="mt-3 text-sm font-medium text-slate-700">
                                            {{ formatarDataHora(item.consultaEm) }}
                                        </div>
                                        <p class="mt-2 text-xs text-slate-400">
                                            Consulta somente leitura
                                        </p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div
                        v-if="totalPaginas > 1"
                        class="flex flex-col gap-4 border-t border-slate-100 px-4 py-4 lg:flex-row lg:items-center lg:justify-between"
                    >
                        <p class="text-sm text-slate-500">
                            Página {{ paginaAtualSegura }} de {{ totalPaginas }}.
                            Mostrando {{ indiceInicialExibicao }}-{{ indiceFinalExibicao }} de {{ totalEncontrado }} resultados.
                        </p>

                        <div class="flex flex-wrap items-center gap-2">
                            <button
                                type="button"
                                class="inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-45"
                                :disabled="paginaAtualSegura <= 1"
                                @click="paginaAnterior"
                            >
                                <ChevronLeft class="h-4 w-4" />
                                Anterior
                            </button>

                            <button
                                v-for="pagina in paginasVisiveis"
                                :key="pagina"
                                type="button"
                                class="inline-flex h-10 min-w-10 items-center justify-center rounded-xl border px-3 text-sm font-semibold transition"
                                :class="
                                    pagina === paginaAtualSegura
                                        ? 'border-blue-200 bg-blue-50 text-blue-700'
                                        : 'border-slate-200 bg-white text-slate-700 hover:border-slate-300 hover:bg-slate-50'
                                "
                                @click="irParaPagina(pagina)"
                            >
                                {{ pagina }}
                            </button>

                            <button
                                type="button"
                                class="inline-flex h-10 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50 disabled:cursor-not-allowed disabled:opacity-45"
                                :disabled="paginaAtualSegura >= totalPaginas"
                                @click="proximaPagina"
                            >
                                Próxima
                                <ChevronRight class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </div>

                <div
                    v-else
                    class="px-6 py-16 text-center"
                >
                    <div class="mx-auto flex max-w-md flex-col items-center">
                        <div class="flex h-16 w-16 items-center justify-center rounded-[18px] bg-slate-100 text-slate-500">
                            <FileSearch2 class="h-7 w-7" />
                        </div>
                        <h4 class="mt-5 font-display text-2xl font-extrabold tracking-tight text-slate-900">
                            Nenhuma encomenda para exibir
                        </h4>
                        <p class="mt-3 text-sm leading-6 text-slate-500">
                            Ajuste os filtros para localizar uma placa específica ou rode a
                            consulta para carregar a listagem inicial de encomendas.
                        </p>
                    </div>
                </div>
            </section>

            <Teleport to="body">
                <transition
                    enter-active-class="duration-200 ease-out"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-active-class="duration-150 ease-in"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div
                        v-if="modalDetalhesAberta && encomendaSelecionada"
                        class="fixed inset-0 z-[120] bg-slate-950/60 px-4 py-6 backdrop-blur-sm"
                        @click.self="fecharDetalhes"
                    >
                        <div class="mx-auto flex h-full max-w-7xl flex-col overflow-hidden rounded-[28px] border border-slate-200 bg-white shadow-2xl">
                        <div class="flex items-start justify-between gap-4 border-b border-slate-100 px-6 py-5">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.24em] text-blue-500">
                                    Detalhes completos
                                </p>
                                <h3 class="mt-2 font-display text-3xl font-extrabold tracking-tight text-slate-900">
                                    Placa {{ encomendaSelecionada.placa }}
                                </h3>
                                <p class="mt-2 text-sm text-slate-500">
                                    Consulta detalhada com todos os campos retornados pela API.
                                </p>
                            </div>

                            <button
                                type="button"
                                class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 text-slate-500 transition hover:border-slate-300 hover:bg-slate-50 hover:text-slate-900"
                                @click="fecharDetalhes"
                            >
                                <X class="h-5 w-5" />
                            </button>
                        </div>

                        <div class="grid flex-1 overflow-y-auto lg:grid-cols-[0.9fr,1.1fr]">
                            <aside class="border-b border-slate-100 bg-slate-50 px-6 py-6 lg:border-b-0 lg:border-r">
                                <MercosulPlatePreview
                                    size="modal"
                                    :placa="encomendaSelecionada.placa"
                                    :cidade-uf="encomendaSelecionada.cidadeUf"
                                    :tipo-veiculo="encomendaSelecionada.tipoVeiculo"
                                    :placa-dianteira="encomendaSelecionada.placaDianteira"
                                    :placa-traseira="encomendaSelecionada.placaTraseira"
                                    :placa-segunda-traseira="encomendaSelecionada.placaSegundaTraseira"
                                />

                                <div class="mt-6 grid gap-3 sm:grid-cols-2">
                                    <div class="rounded-[18px] border border-slate-200 bg-white p-4">
                                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">
                                            ID
                                        </p>
                                        <p class="mt-2 text-2xl font-extrabold text-slate-900">
                                            {{ encomendaSelecionada.id }}
                                        </p>
                                        <p class="mt-2 text-sm text-slate-500">
                                            Aut. {{ encomendaSelecionada.autorizacao }}
                                        </p>
                                    </div>

                                    <div class="rounded-[18px] border border-slate-200 bg-white p-4">
                                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">
                                            Configuração
                                        </p>
                                        <p class="mt-2 text-2xl font-extrabold text-slate-900">
                                            {{ encomendaSelecionada.configuracaoPlaca }}
                                        </p>
                                        <p class="mt-2 text-sm text-slate-500">
                                            {{ encomendaSelecionada.tipoVeiculo }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-4 rounded-[18px] border border-slate-200 bg-white p-4">
                                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">
                                        Resumo rápido
                                    </p>
                                    <div class="mt-4 space-y-4">
                                        <div v-for="card in resumoDetalhes" :key="card.label">
                                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-slate-400">
                                                {{ card.label }}
                                            </p>
                                            <p class="mt-1 text-sm font-semibold text-slate-900">
                                                {{ card.value }}
                                            </p>
                                            <p class="mt-1 text-xs text-slate-500">
                                                {{ card.secondary }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </aside>

                            <div class="space-y-6 px-6 py-6">
                                <div class="grid gap-4 md:grid-cols-3">
                                    <div class="rounded-[18px] border border-slate-200 bg-slate-50 p-4">
                                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">
                                            Pagamento
                                        </p>
                                        <p class="mt-2 text-lg font-bold text-slate-900">
                                            {{ encomendaSelecionada.pagamento }}
                                        </p>
                                    </div>

                                    <div class="rounded-[18px] border border-slate-200 bg-slate-50 p-4">
                                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">
                                            Documentos
                                        </p>
                                        <p class="mt-2 text-lg font-bold text-slate-900">
                                            {{ encomendaSelecionada.documentos }}
                                        </p>
                                    </div>

                                    <div class="rounded-[18px] border border-slate-200 bg-slate-50 p-4">
                                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-slate-400">
                                            Situação
                                        </p>
                                        <p class="mt-2 text-lg font-bold text-slate-900">
                                            {{ encomendaSelecionada.situacao }}
                                        </p>
                                    </div>
                                </div>

                                <div class="grid gap-4 xl:grid-cols-2">
                                    <section
                                        v-for="section in detalhesEncomenda"
                                        :key="section.title"
                                        class="rounded-[20px] border border-slate-200 bg-white p-5 shadow-sm"
                                    >
                                        <p class="text-xs font-bold uppercase tracking-[0.22em] text-blue-500">
                                            {{ section.title }}
                                        </p>

                                        <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                            <div
                                                v-for="field in section.items"
                                                :key="`${section.title}-${field.label}`"
                                                class="rounded-[16px] border border-slate-100 bg-slate-50 p-3"
                                            >
                                                <p class="text-[11px] font-bold uppercase tracking-[0.16em] text-slate-400">
                                                    {{ field.label }}
                                                </p>
                                                <p class="mt-2 break-words text-sm font-semibold text-slate-900">
                                                    {{ field.value }}
                                                </p>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </transition>
            </Teleport>
        </div>
    </AuthenticatedLayout>
</template>
