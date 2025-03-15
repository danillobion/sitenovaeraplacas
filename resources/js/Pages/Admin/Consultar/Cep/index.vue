<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';

const form = useForm({
    cep: null,
    uf: null,
    cidade: null,
    bairro: null,
    logradouro: null,
    numero: null,
    complemento: null
});

const consulta_em_andamento = ref(false);

// Função para formatar o CEP
const formatarCep = (cep) => {
    cep = cep.replace(/\D/g, ''); // Remove tudo o que não for número
    if (cep.length <= 5) {
        return cep.slice(0, 5); // Formata até 5 dígitos
    }
    return `${cep.slice(0, 5)}-${cep.slice(5, 8)}`; // Adiciona o traço após os 5 primeiros números
};

// Função para consultar o CEP
const consultarCep = async () => {
    try {
        consulta_em_andamento.value = true; // Bloquear campos enquanto consulta
        const cepSemFormatacao = form.cep.replace(/\D/g, ''); // Remove ponto e traço do CEP

        const response = await axios.get(route('consultar.cep', cepSemFormatacao));

        if (response.data.error) {
            alert(response.data.error);
            return;
        }

        let nao_informado = '- Não informado -';

        form.cep = response.data.cep || nao_informado;
        form.uf = response.data.uf || nao_informado;
        form.cidade = response.data.cidade || nao_informado;
        form.bairro = response.data.bairro || nao_informado;
        form.logradouro = response.data.logradouro || nao_informado;
        form.complemento = response.data.complemento || nao_informado;
        form.numero = response.data.numero || nao_informado;
    } catch (error) {
        alert("Erro ao buscar o CEP. Tente novamente.");
        console.error(error);
    } finally {
        consulta_em_andamento.value = false;
    }
};

</script>

<template>
    <Head title="Consultar CEP" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-start">
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Consultar CEP</h2>
            </div>
        </template>

        <div class="py-1">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="mb-4 text-lg">Consulta</h2>
                        <!-- Campo de CEP e Botão -->
                        <div class="mt-4 flex flex-wrap gap-4 items-end">
                            <div class="flex-1">
                                <TextInput
                                    id="consultar"
                                    type="text"
                                    class="block w-full"
                                    v-model="form.cep"
                                    @input="form.cep = formatarCep(form.cep)"
                                    required
                                    placeholder="Digite o CEP"
                                    :disabled="consulta_em_andamento"
                                />
                                <InputError class="mt-2" />
                            </div>
                            <PrimaryButton
                                class="mb-1"
                                :class="{ 'opacity-25': consulta_em_andamento }"
                                :disabled="consulta_em_andamento"
                                @click="consultarCep"
                            >
                                <span v-if="consulta_em_andamento">Pesquisando...</span>
                                <span v-else>Pesquisar</span>
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="py-1 pb-5" v-if="form.uf">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h2 class="mb-4 text-lg">Endereço</h2>
                        <!-- Campos de Retorno (UF, Cidade, etc.) -->
                        <div class="mt-6 grid md:grid-cols-2 gap-6">
                            <div class="mt-4" v-if="form.uf">
                                <p class="font-semibold">UF</p>
                                <p>{{ form.uf }}</p>
                            </div>
                            <div class="mt-4" v-if="form.cidade">
                                <p class="font-semibold">Cidade</p>
                                <p>{{ form.cidade }}</p>
                            </div>
                            <div class="mt-4" v-if="form.bairro">
                                <p class="font-semibold">Bairro</p>
                                <p>{{ form.bairro }}</p>
                            </div>
                            <div class="mt-4" v-if="form.logradouro">
                                <p class="font-semibold">Logradouro</p>
                                <p>{{ form.logradouro }}</p>
                            </div>
                            <div class="mt-4" v-if="form.numero">
                                <p class="font-semibold">Número</p>
                                <p>{{ form.numero }}</p>
                            </div>
                            <div class="mt-4" v-if="form.complemento">
                                <p class="font-semibold">Complemento</p>
                                <p>{{ form.complemento }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
