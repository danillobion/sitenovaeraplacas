<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';

const form = useForm({
    placa: '',
    cnpj_estampadora: '',
    situacao: '',
    situacao_pagamento: '',
    numero_autorizacao: '',
    numero_autorizacao_local: '',
    id: '',
    data_inicio_abertura: '',
    data_fim_abertura: '',
});
const resultado = ref({});

const consulta_em_andamento = ref(false);

const consultarEncomendas = async () => {
    consulta_em_andamento.value = true;
    try {
        const response = await axios.post(route('encomenda.consultar'), form);
        resultado.value = response.data;
        if(response.data.error){
            alert(response.data.message);
        }
    } catch (error) {
        console.error(error);
        alert("Erro na consulta.");
    } finally {
        consulta_em_andamento.value = false;
    }
};

const formatKey = (key) => key.replace(/_/g, ' ');

const situacao = (key) => {
    switch (key) {
        case "PEN":
            return "Pendente";
        case "EXO":
            return "Exonerado";
        case "CAN":
            return "Cancelado";
        default:
            return "Desconhecido";
    }
}

const situacaoPagamento = (key) => {
    switch (key) {
        case "PEN":
            return "Pendente";
        case "PAG":
            return "Pago";
        default:
            return "Desconhecido";
    }
}

const situacaoDocumentos = (key) => {
    switch (key) {
        case "PEN":
            return "Pendente";
        case "PAG":
            return "Pago";
        default:
            return "Desconhecido";
    }
}

const placa = (key) => {
    switch (key) {
        case 1:
            return "Sim";
        case 0:
            return "Não";
        default:
            return "Não informado";
    }
}

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

</script>

<template>
    <Head title="Consulta de Encomendas" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold text-gray-800">Consulta de Encomendas</h2>
        </template>

        <div class="py-0">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <InputLabel for="id" value="ID" />
                            <TextInput 
                                id="id" 
                                v-model="form.id" 
                                class="mt-1 block w-full"
                                placeholder="Digite o ID"
                            />
                        </div>

                        <div>
                            <InputLabel for="placa" value="Placa" />
                            <TextInput id="placa" 
                                v-model="form.placa" 
                                class="mt-1 block w-full"
                                @input="form.placa"
                                placeholder="Digite a placa"
                            />
                        </div>

                        <div>
                            <InputLabel for="cnpj_estampadora" value="CNPJ Estampadora" />
                            <TextInput id="cnpj_estampadora" 
                                v-model="form.cnpj_estampadora" 
                                class="mt-1 block w-full"
                                @input="form.cnpj_estampadora = formatarCNPJ(form.cnpj_estampadora)"
                                placeholder="Digite o CNPJ da Estampadora"
                            />
                        </div>

                        <div>
                            <InputLabel for="situacao" value="Situação" />
                            <select
                                id="situacao"
                                v-model="form.situacao"
                                class="w-full mt-1 rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Selecione uma situação</option>
                                <option value="ABE">Abertas</option>
                                <option value="FIN">Finalizadas</option>
                                <option value="CAN">Canceladas</option>
                            </select>
                        </div>

                        <div>
                            <InputLabel for="situacao_pagamento" value="Situação Pagamento" />
                            <select
                                id="situacao_pagamento"
                                v-model="form.situacao_pagamento"
                                class="w-full mt-1 rounded border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option value="">Selecione uma situação</option>
                                <option value="PAG">Pago</option>
                                <option value="EMT">Emitido</option>
                                <option value="PEN">Pendente</option>
                                <option value="CAN">Cancelado</option>
                            </select>
                        </div>

                        <div>
                            <InputLabel for="numero_autorizacao" value="Nº Autorização" />
                            <TextInput 
                                id="numero_autorizacao" 
                                v-model="form.numero_autorizacao" 
                                class="mt-1 block w-full" 
                                placeholder="Digite o Nº Autorização"
                            />
                        </div>

                        <div>
                            <InputLabel for="numero_autorizacao_local" value="Nº Autorização Local" />
                            <TextInput 
                                id="numero_autorizacao_local" 
                                v-model="form.numero_autorizacao_local" 
                                class="mt-1 block w-full"
                                placeholder="Digite o Nº Autorização Local"
                            />
                        </div>

                        <div>
                            <InputLabel for="data_inicio_abertura" value="Data Início Abertura" />
                            <TextInput 
                                id="data_inicio_abertura" 
                                type="date" 
                                v-model="form.data_inicio_abertura" 
                                class="mt-1 block w-full"
                                placeholder="Digite a Data Início Abertura"
                            />
                        </div>

                        <div>
                            <InputLabel for="data_fim_abertura" value="Data Fim Abertura" />
                            <TextInput 
                                id="data_fim_abertura" 
                                type="date" 
                                v-model="form.data_fim_abertura" 
                                class="mt-1 block w-full" 
                                placeholder="Digite a Data Fim Abertura"
                            />
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <PrimaryButton
                            :disabled="consulta_em_andamento"
                            @click="consultarEncomendas"
                        >
                            <span v-if="consulta_em_andamento">Consultando...</span>
                            <span v-else>Consultar</span>
                        </PrimaryButton>
                    </div>
                </div>

                <!-- Exibição dos resultados -->
                <div v-if="resultado.encomendas && resultado.encomendas.length" class="mt-8 bg-white p-6 shadow rounded-lg">
                    <h3 class="text-lg font-semibold mb-4">Resultado da Consulta</h3>

                    <div
                        v-for="(item, index) in resultado.encomendas"
                        :key="index"
                        class="border-b border-gray-200 py-4"
                    >
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">
                            <div v-for="(value, key) in item" :key="key">
                                <div v-if="typeof value !== 'object'">
                                <strong>{{ formatKey(key) }}: </strong>
                                    <span>
                                        {{
                                            key === 'situacao' ? situacao(value)
                                            : key === 'situacao_pagamento' ? situacaoPagamento(value)
                                            : key === 'situacao_documentos' ? situacaoDocumentos(value)
                                            : key === 'placa_dianteira' ? placa(value) 
                                            : key === 'placa_traseira' ? placa(value)
                                            : key === 'placa_segunda_traseira' ? placa(value)
                                            : value ?? '-'
                                        }}
                                    </span>
                                </div>

                                <!-- Exibir objetos aninhados (ex: pagamento) -->
                                <div v-else>
                                    <strong>{{ formatKey(key) }}:</strong>
                                    <ul class="pl-4 list-disc text-gray-700">
                                        <li v-for="(subValue, subKey) in value" :key="subKey">
                                            <div>
                                                {{ formatKey(subKey) }}: {{ subValue ?? '-' }}
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
