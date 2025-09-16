<template>
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Checkout</h3>

            <form @submit.prevent="handleCheckout" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Email
                    </label>
                    <input v-model="form.buyer_email" type="email" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        No. Handphone
                    </label>
                    <input v-model="form.buyer_phone" type="tel" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="bg-gray-50 p-4 rounded-md">
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Produk:</span>
                            <span class="font-medium">{{ product.name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Harga:</span>
                            <span>Rp {{ product.price.toLocaleString('id-ID') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Metode:</span>
                            <span>{{ paymentMethod.name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Biaya Admin:</span>
                            <span>Rp {{ (paymentMethod.total_fee.flat || 0).toLocaleString('id-ID') }}</span>
                        </div>
                        <div class="flex justify-between font-bold text-lg border-t pt-2">
                            <span>Total:</span>
                            <span>Rp {{ totalAmount.toLocaleString('id-ID') }}</span>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" @click="$emit('close')"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md transition-colors">
                        Batal
                    </button>
                    <button type="submit" :disabled="loading"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition-colors disabled:opacity-50">
                        {{ loading ? 'Memproses...' : 'Bayar Sekarang' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed } from 'vue'
import { useInvoicesStore } from '../stores/invoices';
import type { Product, PaymentChannel, CreateTransactionPayload } from '../types'

interface Props {
    product: Product
    paymentMethod: PaymentChannel
}

const props = defineProps<Props>()
const emit = defineEmits<{
    close: []
    success: [any]
}>()

const invoicesStore = useInvoicesStore()
const loading = ref(false)

const form = reactive({
    buyer_email: '',
    buyer_phone: ''
})

const totalAmount = computed(() => {
    return props.product.price + (props.paymentMethod.total_fee.flat || 0)
})

const handleCheckout = async () => {
    try {
        loading.value = true

        const payload: CreateTransactionPayload = {
            product_id: props.product.id,
            payment_method: props.paymentMethod.code,
            buyer_email: form.buyer_email,
            buyer_phone: form.buyer_phone
        }

        const response = await invoicesStore.createTransaction(payload) as {
            invoice: any
            tripay_data: {
                data: {
                    reference: string
                    status: string
                    amount: number
                }
            }
        }

        emit('success', response)
        emit('close')

    } catch (error) {
        console.error('Checkout failed:', error)
        alert('Terjadi kesalahan saat membuat transaksi. Silakan coba lagi.')
    } finally {
        loading.value = false
    }
}

</script>