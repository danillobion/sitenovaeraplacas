<script setup>
import InputError from '@/Components/InputError.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import {
    ArrowLeft,
    ArrowRight,
    AtSign,
    CheckCircle2,
    Eye,
    EyeOff,
    LoaderCircle,
    LockKeyhole,
    ShieldCheck,
} from 'lucide-vue-next';
import { ref } from 'vue';

defineProps({
    canResetPassword: {
        type: Boolean,
        default: false,
    },
    status: {
        type: String,
        default: '',
    },
});

const showPassword = ref(false);

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Entrar" />

    <div class="login-shell relative min-h-screen overflow-hidden bg-[#f7f8fa] text-slate-900">
        <div class="login-grid pointer-events-none absolute inset-0" />
        <div class="login-noise pointer-events-none absolute inset-0" />
        <div class="login-glow login-glow--primary pointer-events-none absolute" />
        <div class="login-glow login-glow--secondary pointer-events-none absolute" />

        <header class="relative z-20 mx-auto flex w-full max-w-[1240px] items-center justify-between px-5 py-5 sm:px-8 lg:px-10">
            <Link href="/" class="group inline-flex items-center gap-3">
                <span class="flex h-11 w-[4.6rem] items-center justify-center overflow-hidden rounded-md border border-slate-200 bg-white shadow-sm">
                    <img
                        src="/images/logo.jpg"
                        alt="Nova Era Placas"
                        class="h-full w-full object-cover"
                    />
                </span>

                <span class="hidden sm:block">
                    <span class="block font-display text-sm font-extrabold uppercase tracking-[0.2em] text-red-600">
                        Nova Era
                    </span>
                    <span class="mt-0.5 block text-[11px] font-medium text-slate-400">
                        Painel administrativo
                    </span>
                </span>
            </Link>

            <Link
                href="/"
                class="inline-flex h-10 items-center gap-2 rounded-md border border-slate-200 bg-white px-3.5 text-xs font-semibold text-slate-500 shadow-sm transition hover:border-red-200 hover:text-red-600"
            >
                <ArrowLeft class="h-4 w-4" />
                <span class="hidden sm:inline">Voltar ao site</span>
                <span class="sm:hidden">Voltar</span>
            </Link>
        </header>

        <main class="relative z-10 mx-auto grid min-h-[calc(100vh-162px)] w-full max-w-[1240px] items-center gap-14 px-5 pb-12 pt-6 sm:px-8 lg:grid-cols-[1.1fr,0.9fr] lg:px-10 lg:py-12">
            <section class="hidden max-w-xl lg:block">
                <div class="inline-flex items-center gap-2 rounded-md border border-red-100 bg-red-50 px-3.5 py-2 text-[11px] font-bold uppercase tracking-[0.18em] text-red-600">
                    <span class="h-2 w-2 rounded-full bg-red-500 shadow-[0_0_12px_rgba(239,68,68,0.35)]" />
                    Ambiente seguro
                </div>

                <h1 class="mt-7 font-display text-5xl font-extrabold leading-[1.08] tracking-[-0.04em] text-slate-950 xl:text-[3.7rem]">
                    Sua operação,<br />
                    <span class="login-title-accent">em um só lugar.</span>
                </h1>

                <p class="mt-6 max-w-lg text-base leading-7 text-slate-500">
                    Acesse as consultas, cadastros e rotinas administrativas da Nova Era com uma experiência mais simples e organizada.
                </p>

                <div class="mt-10 grid max-w-lg grid-cols-2 gap-3">
                    <div class="login-feature">
                        <ShieldCheck class="h-5 w-5 text-red-500" />
                        <div>
                            <p class="text-sm font-semibold text-slate-800">Acesso protegido</p>
                            <p class="mt-1 text-xs leading-5 text-slate-500">Ambiente restrito à equipe autorizada.</p>
                        </div>
                    </div>

                    <div class="login-feature">
                        <CheckCircle2 class="h-5 w-5 text-red-400" />
                        <div>
                            <p class="text-sm font-semibold text-slate-800">Operação integrada</p>
                            <p class="mt-1 text-xs leading-5 text-slate-500">Informações essenciais sempre à mão.</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="mx-auto w-full max-w-[440px] lg:mx-0 lg:ml-auto">
                <div class="mb-7 text-center lg:hidden">
                    <p class="font-display text-2xl font-extrabold tracking-tight text-slate-950">
                        Painel Nova Era
                    </p>
                    <p class="mt-2 text-sm text-slate-500">
                        Entre para acessar sua área administrativa.
                    </p>
                </div>

                <div class="login-card relative overflow-hidden rounded-lg border border-slate-200 bg-white/95 p-6 shadow-[0_28px_70px_rgba(15,23,42,0.1)] backdrop-blur-2xl sm:p-8">
                    <div class="pointer-events-none absolute inset-x-12 top-0 h-px bg-gradient-to-r from-transparent via-red-400/80 to-transparent" />

                    <div>
                        <p class="text-[11px] font-bold uppercase tracking-[0.22em] text-red-500">
                            Bem-vindo de volta
                        </p>
                        <h2 class="mt-3 font-display text-3xl font-extrabold tracking-tight text-slate-950">
                            Acesse sua conta
                        </h2>
                        <p class="mt-2 text-sm leading-6 text-slate-500">
                            Use suas credenciais para continuar.
                        </p>
                    </div>

                    <div
                        v-if="status"
                        class="mt-6 flex items-start gap-3 rounded-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm leading-6 text-emerald-700"
                    >
                        <CheckCircle2 class="mt-0.5 h-4 w-4 shrink-0" />
                        {{ status }}
                    </div>

                    <form class="mt-7" @submit.prevent="submit">
                        <div class="space-y-5">
                            <div>
                                <label for="email" class="login-label">E-mail</label>
                                <div class="group relative mt-2">
                                    <AtSign class="login-field-icon" />
                                    <input
                                        id="email"
                                        v-model="form.email"
                                        type="email"
                                        autocomplete="username"
                                        autofocus
                                        required
                                        placeholder="seu@email.com"
                                        class="login-input"
                                    />
                                </div>
                                <InputError class="mt-2 text-xs text-red-300" :message="form.errors.email" />
                            </div>

                            <div>
                                <div class="flex items-center justify-between gap-4">
                                    <label for="password" class="login-label">Senha</label>
                                    <Link
                                        v-if="canResetPassword"
                                        :href="route('password.request')"
                                        class="text-xs font-semibold text-red-500 transition hover:text-red-700"
                                    >
                                        Esqueceu a senha?
                                    </Link>
                                </div>

                                <div class="group relative mt-2">
                                    <LockKeyhole class="login-field-icon" />
                                    <input
                                        id="password"
                                        v-model="form.password"
                                        :type="showPassword ? 'text' : 'password'"
                                        autocomplete="current-password"
                                        required
                                        placeholder="Digite sua senha"
                                        class="login-input pr-12"
                                    />
                                    <button
                                        type="button"
                                        class="absolute inset-y-0 right-0 flex w-12 items-center justify-center text-slate-300 transition hover:text-red-600"
                                        :aria-label="showPassword ? 'Ocultar senha' : 'Mostrar senha'"
                                        @click="showPassword = !showPassword"
                                    >
                                        <EyeOff v-if="showPassword" class="h-[18px] w-[18px]" />
                                        <Eye v-else class="h-[18px] w-[18px]" />
                                    </button>
                                </div>
                                <InputError class="mt-2 text-xs text-red-300" :message="form.errors.password" />
                            </div>
                        </div>

                        <label class="mt-5 inline-flex cursor-pointer items-center gap-3 text-sm text-white/42">
                            <input
                                v-model="form.remember"
                                type="checkbox"
                                class="h-4 w-4 rounded border-slate-300 bg-white text-red-600 focus:ring-2 focus:ring-red-200 focus:ring-offset-0"
                            />
                            <span class="text-slate-500">Manter meu acesso neste dispositivo</span>
                        </label>

                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="login-submit group mt-7 flex h-12 w-full items-center justify-center gap-2.5 rounded-md bg-red-600 px-5 text-sm font-bold text-white shadow-[0_14px_35px_rgba(220,38,38,0.2)] transition hover:bg-red-500 disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            <LoaderCircle v-if="form.processing" class="h-4 w-4 animate-spin" />
                            <template v-else>
                                Entrar no painel
                                <ArrowRight class="h-4 w-4 transition-transform group-hover:translate-x-0.5" />
                            </template>
                        </button>
                    </form>

                    <div class="mt-7 flex items-center gap-3 border-t border-slate-100 pt-5 text-xs text-slate-400">
                        <ShieldCheck class="h-4 w-4 shrink-0 text-slate-400" />
                        Acesso exclusivo para usuários autorizados.
                    </div>
                </div>
            </section>
        </main>

        <footer class="relative z-10 mx-auto flex w-full max-w-[1240px] items-center justify-between border-t border-slate-200/80 px-5 py-5 text-[11px] text-slate-400 sm:px-8 lg:px-10">
            <span>© {{ new Date().getFullYear() }} Nova Era Placas</span>
            <span class="hidden sm:inline">Tecnologia a serviço da sua operação</span>
        </footer>
    </div>
</template>

<style scoped>
.login-grid {
    background-image:
        linear-gradient(rgba(15, 23, 42, 0.035) 1px, transparent 1px),
        linear-gradient(90deg, rgba(15, 23, 42, 0.035) 1px, transparent 1px);
    background-size: 54px 54px;
    mask-image: linear-gradient(to bottom, black 0%, transparent 92%);
}

.login-noise {
    opacity: 0.012;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 180 180' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)'/%3E%3C/svg%3E");
}

.login-glow {
    border-radius: 999px;
    filter: blur(2px);
    opacity: 0.75;
    animation: login-float 14s ease-in-out infinite alternate;
}

.login-glow--primary {
    top: -22rem;
    left: -15rem;
    height: 42rem;
    width: 42rem;
    background: radial-gradient(circle, rgba(220, 38, 38, 0.13), rgba(220, 38, 38, 0) 68%);
}

.login-glow--secondary {
    right: -19rem;
    bottom: -25rem;
    height: 48rem;
    width: 48rem;
    background: radial-gradient(circle, rgba(248, 113, 113, 0.09), rgba(248, 113, 113, 0) 68%);
    animation-delay: -7s;
}

.login-title-accent {
    background: linear-gradient(100deg, #b91c1c 0%, #dc2626 50%, #f87171 100%);
    background-clip: text;
    color: transparent;
}

.login-feature {
    display: flex;
    min-height: 6.6rem;
    gap: 0.85rem;
    border: 1px solid rgb(226 232 240);
    border-radius: 0.5rem;
    background: rgba(255, 255, 255, 0.8);
    padding: 1rem;
    box-shadow: 0 10px 28px rgba(15, 23, 42, 0.045);
    backdrop-filter: blur(14px);
}

.login-label {
    display: block;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.14em;
    text-transform: uppercase;
    color: rgb(100 116 139);
}

.login-field-icon {
    pointer-events: none;
    position: absolute;
    left: 0.95rem;
    top: 50%;
    z-index: 1;
    height: 1rem;
    width: 1rem;
    transform: translateY(-50%);
    color: rgb(148 163 184);
    transition: color 180ms ease;
}

.group:focus-within .login-field-icon {
    color: rgb(220 38 38);
}

.login-input {
    width: 100%;
    height: 3rem;
    border: 1px solid rgb(226 232 240);
    border-radius: 0.5rem;
    background: rgb(248 250 252);
    padding: 0 0.9rem 0 2.75rem;
    color: rgb(15 23 42);
    font-size: 0.875rem;
    outline: none;
    transition: border-color 180ms ease, background 180ms ease, box-shadow 180ms ease;
}

.login-input::placeholder {
    color: rgb(148 163 184);
}

.login-input:focus {
    border-color: rgba(220, 38, 38, 0.55);
    background: white;
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.08);
}

.login-card {
    animation: login-card-in 650ms cubic-bezier(0.16, 1, 0.3, 1) both;
}

@keyframes login-card-in {
    from {
        opacity: 0;
        transform: translateY(18px) scale(0.985);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

@keyframes login-float {
    from {
        transform: translate3d(0, 0, 0) scale(1);
    }
    to {
        transform: translate3d(4rem, 2rem, 0) scale(1.08);
    }
}

@media (prefers-reduced-motion: reduce) {
    .login-card,
    .login-glow {
        animation: none;
    }
}
</style>
