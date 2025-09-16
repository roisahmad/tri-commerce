<template>
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                Pilih Metode Pembayaran
            </h3>

            <div v-if="invoicesStore.loading" class="flex justify-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            </div>

            <div v-else class="space-y-2 max-h-96 overflow-y-auto">
                <div v-for="method in invoicesStore.paymentChannels" :key="method.code"
                    @click="selectPaymentMethod(method)"
                    class="border border-gray-200 rounded-md p-3 cursor-pointer hover:bg-gray-50 transition-colors">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="font-medium text-gray-900">{{ method.name }}</p>
                            <p class="text-sm text-gray-500">{{ method.group }}</p>
                        </div>
                        <p class="text-sm text-gray-600">
                            {{ method?.total_fee?.flat ? `+Rp ${method?.total_fee?.flat?.toLocaleString('id-ID')}` :
                                'Gratis'
                            }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="flex justify-end pt-4">
                <button @click="$emit('close')"
                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md transition-colors">
                    Batal
                </button>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useInvoicesStore } from '../stores/invoices';
import type { Product, PaymentChannel } from '../types'

interface Props {
    product: Product
}

const props = defineProps<Props>()
const emit = defineEmits<{
    close: []
    paymentSelected: [method: PaymentChannel]
}>()

const selectPaymentMethod = (method: PaymentChannel) => {
    emit('paymentSelected', method)
}


const invoicesStore = useInvoicesStore()
// const selectedPaymentMethod = ref<PaymentChannel | null>(null)

onMounted(() => {
    invoicesStore.fetchPaymentChannels()
})
</script>