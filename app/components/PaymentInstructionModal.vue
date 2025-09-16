<template>
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4 relative">
            <button @click="$emit('close')" class="absolute top-2 right-2 text-gray-400 hover:text-gray-600">
                ✕
            </button>

            <h3 class="text-lg font-bold text-gray-900 mb-4">Instruksi Pembayaran</h3>

            <div class="space-y-3">
                <div class="flex justify-between">
                    <span class="text-gray-600">Reference</span>
                    <span class="font-medium">{{ invoice.reference }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Total</span>
                    <span class="font-medium">Rp {{ invoice.amount.toLocaleString('id-ID') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Status</span>
                    <span :class="invoice.status === 'UNPAID' ? 'text-yellow-600' : 'text-green-600'"
                        class="font-medium">
                        {{ invoice.status }}
                    </span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Expired</span>
                    <span class="font-medium">{{ countdown }}</span>
                </div>
            </div>

            <div class="mt-6">
                <h4 class="font-semibold mb-2">Langkah Pembayaran:</h4>
                <ol class="list-decimal list-inside space-y-1 text-sm text-gray-700">
                    <li v-for="(step, index) in instructions" :key="index" v-html="step"></li>
                </ol>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

interface Props {
    invoice: {
        reference: string
        status: string
        amount: number
        expired_time: number
    }
    instructions: string[]
}

const props = defineProps<Props>()
const emit = defineEmits<{ close: [] }>()

const countdown = ref('')

let interval: any = null
const updateCountdown = () => {
    const now = Math.floor(Date.now() / 1000)
    let diff = props.invoice.expired_time - now

    if (diff <= 0) {
        countdown.value = 'Expired'
        clearInterval(interval)
        return
    }

    const h = Math.floor(diff / 3600)
    const m = Math.floor((diff % 3600) / 60)
    const s = diff % 60
    countdown.value = `${h.toString().padStart(2, '0')}:${m
        .toString()
        .padStart(2, '0')}:${s.toString().padStart(2, '0')}`
}

onMounted(() => {
    updateCountdown()
    interval = setInterval(updateCountdown, 1000)
})

onUnmounted(() => {
    clearInterval(interval)
})
</script>
