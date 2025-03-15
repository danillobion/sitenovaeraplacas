<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const estrutura = ref({
    cnpj_raiz: null,
    razao_social: null,
    capital_social: null,
    responsavel_federativo: null,
    atualizado_em: null,
    telefone1: null,
    uf: null,
    cidade: null,
    bairro: null,
    logradouro: null,
    numero: null,
    complemento: null,
    porte: {
        id: null,
        descricao: null
    },
    natureza_juridica: {
        id: null,
        descricao: null
    },
    qualificacao_do_responsavel: {
        id: null,
        descricao: null
    },
    socios: null,
});

const resultado = ref({});

const consulta_em_andamento = ref(false);

const form = useForm({
    cnpj: null,
});

const consultarCnpj = async () => {
    try {
        const cnpjSemFormatacao = form.cnpj.replace(/\D/g, '');

        consulta_em_andamento.value = true; //bloquear campos
        resultado.value = estrutura.value; //resetar os campos

        const response = await axios.get(route('tela.consultar.cnpj', cnpjSemFormatacao));

        if (response.data.error) {
            alert(response.data.error);
            return;
        }

        resultado.value = response.data;
    } catch (error) {
        alert("Erro ao buscar o CNPJ. Tente novamente.");
        console.error(error);
    } finally {
        consulta_em_andamento.value = false;
    }
};

const formatarCNPJ = (cnpj) => {
    cnpj = cnpj.replace(/\D/g, '');
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

onMounted(() => {    
    resultado.value = estrutura.value;
});

</script>

<template>
    <Head title="Consultar CNPJ" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-start">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Consultar CNPJ</h2>
            </div>
        </template>

        <div class="py-1">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="mb-4 text-lg">Consulta</h2>
                        <!-- Campo de CNPJ e Botão -->
                        <div class="mt-4 flex flex-wrap gap-4 items-end">
                            <div class="flex-1">
                                <TextInput
                                    id="consultar"
                                    type="text"
                                    class="block w-full"
                                    v-model="form.cnpj"
                                    @input="form.cnpj = formatarCNPJ(form.cnpj)"
                                    required
                                    placeholder="Digite o CNPJ"
                                    :disabled="consulta_em_andamento"
                                />
                                <InputError class="mt-2" />
                            </div>
                            <PrimaryButton
                                class="mb-1"
                                :class="{ 'opacity-25': consulta_em_andamento }"
                                :disabled="consulta_em_andamento"
                                @click="consultarCnpj"
                            >
                                <span v-if="consulta_em_andamento">Pesquisando...</span>
                                <span v-else>Pesquisar</span>
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Resultado da pesquisa -->
        <div class="py-1 pb-5" v-if="resultado.razao_social">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="mb-4 text-lg">Resultado</h2>

                        <!-- Exibição dos dados do CNPJ -->
                        <div class="mt-6 grid md:grid-cols-2 gap-6">
                            <div class="mt-4">
                                <p class="font-semibold">Razão social</p>
                                <p>{{ resultado.razao_social ?? '-' }}</p>
                            </div>
                            <div class="mt-4">
                                <p class="font-semibold">CNPJ raiz</p>
                                <p>{{ resultado.cnpj_raiz ?? '-' }}</p>
                            </div>

                            <div class="mt-4">
                                <p class="font-semibold">Natureza jurídica</p>
                                <p>{{ resultado.natureza_juridica.descricao }} - {{ resultado.natureza_juridica.id }}</p>
                            </div>

                            <div class="mt-4">
                                <p class="font-semibold">Porte</p>
                                <p>{{ resultado.porte.descricao }} - {{ resultado.porte.id }}</p>
                            </div>

                            <div class="mt-4">
                                <p class="font-semibold">Qualificação do responsável</p>
                                <p>{{ resultado.qualificacao_do_responsavel.descricao }} - {{ resultado.qualificacao_do_responsavel.id }}</p>
                            </div>

                            <div class="mt-4">
                                <p class="font-semibold">Atualizado em</p>
                                <p>{{ resultado.atualizado_em ? new Date(resultado.atualizado_em).toLocaleString('pt-BR') : '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabela de endereco -->
        <div class="py-1 pb-5" v-if="resultado.cep">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">Endereço</h3>
                        <div class="mt-6 grid md:grid-cols-2 gap-6">
                            <div class="mt-4">
                                <p class="font-semibold">CEP</p>
                                <p>{{ resultado.cep ?? '-' }}</p>
                            </div>
                            <div class="mt-4">
                                <p class="font-semibold">UF</p>
                                <p>{{ resultado.uf ?? '-' }}</p>
                            </div>
                            <div class="mt-4">
                                <p class="font-semibold">Cidade</p>
                                <p>{{ resultado.cidade ?? '-' }}</p>
                            </div>
                            <div class="mt-4">
                                <p class="font-semibold">Bairro</p>
                                <p>{{ resultado.bairro ?? '-' }}</p>
                            </div>
                            <div class="mt-4">
                                <p class="font-semibold">Logradouro</p>
                                <p>{{ resultado.logradouro ?? '-' }}</p>
                            </div>
                            <div class="mt-4">
                                <p class="font-semibold">Número</p>
                                <p>{{ resultado.numero ?? '-' }}</p>
                            </div>
                            <div class="mt-4">
                                <p class="font-semibold">Complemento</p>
                                <p>{{ resultado.complemento ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Tabela de Sócios -->
        <div class="py-1 pb-5" v-if="resultado.socios">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-semibold mb-4">Sócios</h3>
                        <table class="min-w-full table-auto border-collapse">
                            <thead>
                                <tr class="bg-gray-100 text-left dark:bg-gray-700">
                                    <th class="px-4 py-2 border-b">Nome</th>
                                    <th class="px-4 py-2 border-b">CPF/CNPJ Sócio</th>
                                    <th class="px-4 py-2 border-b">CPF Representante</th>
                                    <th class="px-4 py-2 border-b">Data de Entrada</th>
                                    <th class="px-4 py-2 border-b">Faixa Etária</th>
                                    <th class="px-4 py-2 border-b">Qualificação</th>
                                    <th class="px-4 py-2 border-b">País</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="resultado.socios.length > 0" v-for="socio in resultado.socios" :key="socio.id">
                                    <td class="px-4 py-2 border-b">{{ socio.nome ?? '-' }}</td>
                                    <td class="px-4 py-2 border-b">{{ socio.cpf_cnpj_socio ?? '-' }}</td>
                                    <td class="px-4 py-2 border-b">{{ socio.cpf_representante_legal ?? '-' }}</td>
                                    <td class="px-4 py-2 border-b">{{ new Date(socio.data_entrada).toLocaleDateString() ?? '-' }}</td>
                                    <td class="px-4 py-2 border-b">{{ socio.faixa_etaria ?? '-' }}</td>
                                    <td class="px-4 py-2 border-b">{{ socio.qualificacao_socio.descricao ?? '-' }}</td>
                                    <td class="px-4 py-2 border-b">{{ socio.pais.nome ?? '-' }}</td>
                                </tr>
                                <tr v-if="resultado.socios.length === 0">
                                    <td class="px-4 py-2 border-b text-center" colspan="7">Nenhum sócio cadastrado</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
