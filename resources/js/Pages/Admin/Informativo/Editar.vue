<script setup>
import { computed, ref } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import {
    ArrowLeft,
    BellRing,
    CheckCircle2,
    Image as ImageIcon,
    Save,
} from 'lucide-vue-next';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { showSuccessAlert } from '@/lib/sweetAlert';

const props = defineProps({
    informativo: Object,
});

const form = useForm({
    titulo: props.informativo?.titulo || '',
    habilitado: !!props.informativo?.habilitado,
    imagem: null,
});

const preview = ref(props.informativo?.imagem ? `/storage/${props.informativo.imagem}` : null);

const statusLabel = computed(() => (form.habilitado ? 'Ativo' : 'Desativado'));

const statusDescription = computed(() =>
    form.habilitado
        ? 'O aviso será exibido para todos os visitantes da landing page.'
        : 'O conteúdo fica salvo, mas permanece oculto para o público.',
);

const tituloPreview = computed(() => {
    const titulo = form.titulo?.trim();

    return titulo || 'Seu título aparecerá aqui';
});

const onFileChange = (event) => {
    const file = event.target.files[0];

    if (!file) {
        return;
    }

    form.imagem = file;
    preview.value = URL.createObjectURL(file);
};

const salvar = () => {
    form.post(route('informativo.salvar'), {
        forceFormData: true,
        onSuccess: () => {
            showSuccessAlert('Informativo salvo com sucesso!');
        },
    });
};
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Configurar Informativo" />

        

        <div class="grid gap-6 xl:grid-cols-[1.08fr,0.92fr]">
            <section class="rounded-[20px] border border-slate-200 bg-white p-7 shadow-sm">
                <div class="flex flex-col gap-5 border-b border-slate-100 pb-6 lg:flex-row lg:items-start lg:justify-between">
                    <div class="max-w-2xl">
                        <p class="text-xs font-bold uppercase tracking-[0.24em] text-red-500">
                            Publicação da landing page
                        </p>
                        <h3 class="mt-3 font-display text-[2rem] font-extrabold tracking-tight text-slate-900">
                            Edite o informativo.
                        </h3>
                        <p class="mt-3 text-sm leading-6 text-slate-500">
                            Ajuste apenas o essencial: título, imagem e status de publicação.
                        </p>
                    </div>

                    <span
                        :class="form.habilitado
                            ? 'border-emerald-200 bg-emerald-50 text-emerald-700'
                            : 'border-slate-200 bg-slate-100 text-slate-600'"
                        class="inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-semibold"
                    >
                        <CheckCircle2 v-if="form.habilitado" class="h-4 w-4" />
                        <BellRing v-else class="h-4 w-4" />
                        {{ statusLabel }}
                    </span>
                </div>

                <form class="mt-6 space-y-6" @submit.prevent="salvar">
                    <div>
                        <label for="titulo" class="block text-sm font-semibold text-slate-800">
                            Título
                        </label>
                        <input
                            id="titulo"
                            v-model="form.titulo"
                            type="text"
                            placeholder="Ex.: Atenção ao novo horário de atendimento"
                            class="mt-2 w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm font-medium text-slate-900 outline-none transition focus:border-blue-300 focus:bg-white focus:ring-4 focus:ring-blue-100"
                        />
                        <p v-if="form.errors.titulo" class="mt-2 text-sm text-red-600">
                            {{ form.errors.titulo }}
                        </p>
                    </div>

                    <div>
                        <label for="imagem" class="block text-sm font-semibold text-slate-800">
                            Imagem
                        </label>
                        <div class="mt-2 rounded-2xl border border-dashed border-slate-300 bg-slate-50 p-5">
                            <div class="mb-4 flex items-center gap-3 text-slate-600">
                                <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-white ring-1 ring-slate-200">
                                    <ImageIcon class="h-5 w-5" />
                                </div>
                                <p class="text-sm">
                                    Envie uma imagem de apoio para o modal.
                                </p>
                            </div>

                            <input
                                id="imagem"
                                type="file"
                                accept="image/*"
                                class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-blue-600 file:px-4 file:py-2.5 file:text-sm file:font-semibold file:text-white transition hover:file:bg-blue-700"
                                @change="onFileChange"
                            />

                            <p v-if="form.errors.imagem" class="mt-2 text-sm text-red-600">
                                {{ form.errors.imagem }}
                            </p>
                        </div>
                    </div>

                    <div class="rounded-[18px] border border-slate-200 bg-slate-50 p-5">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">
                                    Publicar na tela inicial
                                </p>
                                <p class="mt-1 text-sm leading-6 text-slate-500">
                                    {{ statusDescription }}
                                </p>
                            </div>

                            <label class="inline-flex cursor-pointer items-center gap-3">
                                <input
                                    v-model="form.habilitado"
                                    type="checkbox"
                                    class="peer sr-only"
                                />
                                <span
                                    class="relative h-7 w-12 rounded-full bg-slate-300 transition after:absolute after:left-1 after:top-1 after:h-5 after:w-5 after:rounded-full after:bg-white after:shadow-sm after:transition-all peer-checked:bg-emerald-500 peer-checked:after:translate-x-5"
                                />
                                <span class="text-sm font-semibold text-slate-700">
                                    {{ statusLabel }}
                                </span>
                            </label>
                        </div>
                    </div>

                    <div class="flex flex-col gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:items-center sm:justify-between">
                        <Link
                            :href="route('informativos.index')"
                            class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:bg-slate-50"
                        >
                            <ArrowLeft class="h-4 w-4" />
                            Voltar
                        </Link>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-70"
                        >
                            <Save class="h-4 w-4" />
                            {{ form.processing ? 'Salvando...' : 'Salvar alterações' }}
                        </button>
                    </div>
                </form>
            </section>

            <aside class="rounded-[20px] border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.24em] text-slate-400">
                            Preview
                        </p>
                        <h3 class="mt-2 font-display text-2xl font-extrabold tracking-tight text-slate-900">
                            Resultado no site
                        </h3>
                    </div>

                    <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-semibold text-slate-600">
                        Modal inicial
                    </span>
                </div>

                <div class="mt-5 rounded-[20px] border border-slate-200 bg-[#f6f3f0] p-4">
                    <div class="rounded-[18px] border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-[0.24em] text-red-500">
                            <BellRing class="h-4 w-4" />
                            Informativo
                        </div>

                        <h4 class="mt-3 text-lg font-bold tracking-tight text-slate-900">
                            {{ tituloPreview }}
                        </h4>

                        <div
                            v-if="preview"
                            class="mt-4 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50"
                        >
                            <img
                                :src="preview"
                                alt="Preview do informativo"
                                class="h-60 w-full object-cover"
                            />
                        </div>

                        <div
                            v-else
                            class="mt-4 flex h-60 items-center justify-center rounded-2xl border border-dashed border-slate-300 bg-slate-50 px-6 text-center text-sm leading-6 text-slate-400"
                        >
                            Nenhuma imagem selecionada.
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </AuthenticatedLayout>
</template>
