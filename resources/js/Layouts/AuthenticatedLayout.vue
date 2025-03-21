<script setup>
import { onMounted, ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link,usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const { auth } = usePage().props;

const userHasPermission = (permission) => {
    return auth?.permissions?.includes(permission);
};
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100">
            <nav class="border-b border-red-500 bg-white shadow-md">
            <!-- Primary Navigation Menu -->
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex shrink-0 items-center">
                        <div class="logo-container flex justify-center items-center">
                            <img src="/images/logo.jpg" alt="Logo da Empresa" class="w-16 md:w-20 h-auto rounded-lg shadow-md" :key="route().current()"/>
                            </div>
                         </div>


                            <!-- Navigation Links -->
                            <div
                                 class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                            >
                            <NavLink
                                :href="route('dashboard')"
                                :active="route().current('dashboard')"
                                :class="{
                                    'text-blue-600 border-b-2 border-blue-500': route().current('dashboard'),
                                    'text-red-500 hover:text-blue-800': !route().current('dashboard')
                                }"
                            >
                                Dashboard
                            </NavLink>

                            <NavLink
                                v-if="userHasPermission('tela-listagem-estampadoras')"
                                :href="route('estampadora.index')"
                                :active="route().current('estampadora.index')"
                                :class="{
                                    'text-blue-600 border-b-2 border-blue-500': route().current('estampadora.index'),
                                    'text-red-500 hover:text-blue-800': !route().current('estampadora.index')
                                }"
                            >
                                Estampadoras
                            </NavLink>

                            <NavLink
                                v-if="userHasPermission('tela-listagem-produtos')"
                                :href="route('produtos.index')"
                                :active="route().current('produtos.index')"
                                :class="{
                                    'text-blue-600 border-b-2 border-blue-500': route().current('produtos.index'),
                                    'text-red-500 hover:text-blue-800': !route().current('produtos.index')
                                }"
                            >
                                Produtos
                            </NavLink>

                            <NavLink
                                v-if="userHasPermission('tela-listagem-usuarios')"
                                :href="route('usuarios.index')"
                                :active="route().current('usuarios.index')"
                                :class="{
                                    'text-blue-600 border-b-2 border-blue-500': route().current('usuarios.index'),
                                    'text-red-500 hover:text-blue-800': !route().current('usuarios.index')
                                }"
                            >
                                Usuários
                            </NavLink>

                            <NavLink
                                v-if="userHasPermission('tela-tipos-usuarios')"
                                :href="route('tipos.index')"
                                :active="route().current('tipos.index')"
                                :class="{
                                    'text-blue-600 border-b-2 border-blue-500': route().current('tipos.index'),
                                    'text-red-500 hover:text-blue-800': !route().current('tipos.index')
                                }"
                            >
                                Tipos de usuários
                            </NavLink>

                            <NavLink
                                v-if="userHasPermission('tela-consultar-cep')"
                                :href="route('consultar.cep.index')"
                                :active="route().current('consultar.cep.index')"
                                :class="{
                                    'text-blue-600 border-b-2 border-blue-500': route().current('consultar.cep.index'),
                                    'text-red-500 hover:text-blue-800': !route().current('consultar.cep.index')
                                }"
                            >
                                CEP
                            </NavLink>

                            <NavLink
                                v-if="userHasPermission('tela-consultar-cnpj')"
                                :href="route('consultar.cnpj.index')"
                                :active="route().current('consultar.cnpj.index')"
                                :class="{
                                    'text-blue-600 border-b-2 border-blue-500': route().current('consultar.cnpj.index'),
                                    'text-red-600 hover:text-blue-800': !route().current('consultar.cnpj.index')
                                }"
                            >
                                CNPJ
                            </NavLink>
                            <NavLink
                                    v-if="userHasPermission('tela-senatran')"
                                    :href="route('senatran.index')"
                                    :active="route().current('senatran.index')"
                                    :class="{
                                    'text-blue-600 border-b-2 border-blue-500': route().current('consultar.cnpj.index'),
                                    'text-red-600 hover:text-blue-800': !route().current('consultar.cnpj.index')
                                }"
                                >
                                    SENATRAN
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:ms-6 sm:flex sm:items-center">
                            <!-- Settings Dropdown -->
                            <div class="relative ms-3">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-red-600 hover:text-red-800 transition duration-150 ease-in-out focus:outline-none"
                                            >
                                                {{ $page.props.auth.user.nome }}

                                                <svg
                                                    class="-me-0.5 ms-2 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink
                                            :href="route('profile.edit')"
                                        >
                                            Perfil
                                        </DropdownLink>
                                        <DropdownLink
                                            :href="route('logout')"
                                            method="post"
                                            as="button"
                                        >
                                            Sair
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button
                                @click="
                                    showingNavigationDropdown =
                                        !showingNavigationDropdown
                                "
                               class="inline-flex items-center justify-center rounded-md p-2 text-red-600 hover:text-blue-800 transition duration-150 ease-in-out focus:outline-none"
                            >
                                <svg
                                    class="h-6 w-6"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex':
                                                !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex':
                                                showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{
                        block: showingNavigationDropdown,
                        hidden: !showingNavigationDropdown,
                    }"
                    class="sm:hidden"
                >
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink
                            :href="route('dashboard')"
                            :active="route().current('dashboard')"
                            class="text-red-600 hover:text-blue-800"
                        >
                            Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            v-if="userHasPermission('tela-listagem-estampadoras')"
                            :href="route('estampadora.index')"
                            :active="route().current('estampadora.index')"
                            class="text-red-600 hover:text-blue-800"
                        >
                            Estampadora
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            v-if="userHasPermission('tela-listagem-produtos')"
                            :href="route('produtos.index')"
                            :active="route().current('produtos.index')"
                            class="text-red-600 hover:text-blue-800"
                        >
                            Produtos
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            v-if="userHasPermission('tela-listagem-usuarios')"
                            :href="route('usuarios.index')"
                            :active="route().current('usuarios.index')"
                            class="text-red-600 hover:text-blue-800"
                        >
                            Usuários
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            v-if="userHasPermission('tela-tipos-usuarios')"
                            :href="route('tipos.index')"
                            :active="route().current('tipos.index')"
                            class="text-red-600 hover:text-blue-800"
                        >
                            Tipos de usuários
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            v-if="userHasPermission('tela-consultar-cep')"
                            :href="route('consultar.cep.index')"
                            :active="route().current('consultar.cep.index')"
                            class="text-red-600 hover:text-blue-800"
                        >
                            CEP
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            v-if="userHasPermission('tela-consultar-cnpj')"
                            :href="route('consultar.cnpj.index')"
                            :active="route().current('consultar.cnpj.index')"
                            class="text-red-600 hover:text-blue-800"
                        >
                            CNPJ
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            v-if="userHasPermission('tela-senatran')"
                            :href="route('senatran.index')"
                            :active="route().current('senatran.index')"
                        >
                            SENATRAN
                        </ResponsiveNavLink>
                    </div>

                    <!-- Responsive Settings Options -->
                    <div
                        class="border-t border-red-200 pb-1 pt-4 white:border-blue-600"
                    >
                        <div class="px-4">
                            <div
                                class="text-base font-medium text-red-800 white:text-blue-200"
                            >
                                {{ $page.props.auth.user.nome }}
                            </div>
                            <div class="text-sm font-medium text-blue-500">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Perfil
                            </ResponsiveNavLink>
                            <ResponsiveNavLink
                                :href="route('logout')"
                                method="post"
                                as="button"
                            >
                                Sair
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
