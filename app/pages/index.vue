<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Daftar Produk</h2>

        <!-- Loading State -->
        <div v-if="productsStore.loading" class="flex justify-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
        </div>

        <!-- Error State -->
        <div v-else-if="productsStore.error" class="text-center py-8">
            <p class="text-red-600">{{ productsStore.error }}</p>
            <button @click="productsStore.fetchProducts()"
                class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                Retry
            </button>
        </div>

        <!-- Product Grid -->
        <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <div v-for="product in productsStore.products" :key="product.id"
                class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ product.name }}</h3>
                    <p class="text-sm text-gray-500 mb-1">SKU: {{ product?.sku }}</p>
                    <p class="text-2xl font-bold text-blue-600 mb-4">
                        Rp {{ product?.price ? product?.price?.toLocaleString('id-ID') : '0' }}
                    </p>
                    <button @click="buyProduct(product)"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md font-medium transition-colors">
                        Beli Sekarang
                    </button>
                </div>
            </div>
        </div>

        <!-- Payment Modal -->
        <PaymentModal v-if="selectedProduct && !selectedPaymentMethod" :product="selectedProduct"
            @close="resetPurchaseFlow" @payment-selected="handlePaymentSelected" />

        <!-- Checkout Modal -->
        <CheckoutModal v-if="selectedProduct && selectedPaymentMethod" :product="selectedProduct"
            :payment-method="selectedPaymentMethod" @close="resetPurchaseFlow" @success="handleTransactionSuccess" />

        <!-- Payment Instruction Modal could be triggered here if needed -->
        <PaymentInstructionModal v-if="paymentPopup" :invoice="paymentPopup.invoice"
            :instructions="paymentPopup.instructions" @close="paymentPopup = null" />
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useProductsStore } from '../stores/products'
import type { Product, PaymentChannel } from '../types'

const productsStore = useProductsStore()
const selectedProduct = ref<Product | null>(null)
const selectedPaymentMethod = ref<PaymentChannel | null>(null)

const paymentPopup = ref<{ invoice: any; instructions: string[] } | null>(null)

onMounted(() => {
    productsStore.fetchProducts()
})

const buyProduct = (product: Product) => {
    selectedProduct.value = product
}

const handlePaymentSelected = (method: PaymentChannel) => {
    selectedPaymentMethod.value = method
}

const resetPurchaseFlow = () => {
    selectedProduct.value = null
    selectedPaymentMethod.value = null
}

const handleTransactionSuccess = (response: any) => {
    resetPurchaseFlow()

    paymentPopup.value = {
        invoice: response.tripay_data.data,
        instructions: response.tripay_data.data.instructions?.[0]?.steps || []
    }
}
</script>