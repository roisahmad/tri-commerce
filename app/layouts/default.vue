<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <NuxtLink to="/" class="text-xl font-bold text-gray-900">
                            E-Commerce TriPay
                        </NuxtLink>
                    </div>
                    <div class="flex items-center space-x-4">
                        <NuxtLink to="/"
                            class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium"
                            :class="{ 'text-blue-600': $route.path === '/' }">
                            Home
                        </NuxtLink>
                        <NuxtLink to="/products"
                            class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium"
                            :class="{ 'text-blue-600': $route.path === '/products' }">
                            Kelola Produk
                        </NuxtLink>
                        <NuxtLink to="/invoices"
                            class="text-gray-600 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium"
                            :class="{ 'text-blue-600': $route.path === '/invoices' }">
                            Invoice
                        </NuxtLink>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main>
            <slot />
        </main>

        <!-- Toast Notifications -->
        <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2">
            <!-- Toasts will be inserted here -->
        </div>
    </div>
</template>