<template>
    <div class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md mx-4">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                {{ product ? 'Edit Produk' : 'Tambah Produk' }}
            </h3>

            <form @submit.prevent="handleSubmit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        SKU
                    </label>
                    <input v-model="form.sku" type="text" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                        :class="{ 'border-red-500': errors.sku }">
                    <p v-if="errors.sku" class="text-red-500 text-xs mt-1">{{ errors.sku }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Nama Produk
                    </label>
                    <input v-model="form.name" type="text" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                        :class="{ 'border-red-500': errors.name }">
                    <p v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Harga
                    </label>
                    <input v-model.number="form.price" type="number" min="0" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                        :class="{ 'border-red-500': errors.price }">
                    <p v-if="errors.price" class="text-red-500 text-xs mt-1">{{ errors.price }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Reference
                    </label>
                    <input v-model="form.reference" type="text" required
                        class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                        :class="{ 'border-red-500': errors.reference }">
                    <p v-if="errors.reference" class="text-red-500 text-xs mt-1">{{ errors.reference }}</p>
                </div>

                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" @click="$emit('close')"
                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md transition-colors">
                        Batal
                    </button>
                    <button type="submit" :disabled="loading"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors disabled:opacity-50">
                        {{ loading ? 'Menyimpan...' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, reactive } from 'vue';
import { useProductsStore } from '../stores/products';
import type { Product } from '../types'

interface Props {
    product?: Product | null
}

const props = defineProps<Props>()
const emit = defineEmits<{
    close: []
    saved: []
}>()

const productsStore = useProductsStore()
const loading = ref(false)
const errors = ref<Record<string, string>>({})

const form = reactive({
    sku: props.product?.sku || '',
    name: props.product?.name || '',
    price: props.product?.price || 0,
    reference: props.product?.reference || ''
})

const validateForm = () => {
    errors.value = {}

    if (!form.sku.trim()) {
        errors.value.sku = 'SKU harus diisi'
    }

    if (!form.name.trim()) {
        errors.value.name = 'Nama produk harus diisi'
    }

    if (form.price <= 0) {
        errors.value.price = 'Harga harus lebih dari 0'
    }

    if (!form.reference.trim()) {
        errors.value.reference = 'Reference harus diisi'
    }

    return Object.keys(errors.value).length === 0
}

const handleSubmit = async () => {
    if (!validateForm()) return

    try {
        loading.value = true

        if (props.product) {
            await productsStore.updateProduct(props.product.id, form)
        } else {
            await productsStore.createProduct(form)
        }

        emit('saved')
    } catch (error) {
        console.error('Failed to save product:', error)
    } finally {
        loading.value = false
    }
}
</script>