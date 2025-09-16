<template>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Kelola Produk</h2>
            <button @click="openModal()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                Tambah Produk
            </button>
        </div>

        <!-- Products Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            SKU
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Harga
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Reference
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="product in productsStore.products" :key="product.id">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ product.sku }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ product.name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp {{ product.price ? product?.price?.toLocaleString('id-ID') : '0' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ product.reference }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                            <button @click="openModal(product)" class="text-blue-600 hover:text-blue-900">
                                Edit
                            </button>
                            <button @click="deleteProduct(product.id)" class="text-red-600 hover:text-red-900">
                                Hapus
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Product Modal -->
        <ProductModal v-if="showModal" :product="editingProduct" @close="closeModal" @saved="handleProductSaved" />
    </div>
</template>

<script setup lang="ts">
import { useProductsStore } from '../../stores/products'
import type { Product } from '../../types'
import { ref, onMounted } from 'vue'

const productsStore = useProductsStore()
const showModal = ref(false)
const editingProduct = ref<Product | null>(null)

onMounted(() => {
    productsStore.fetchProducts()
})

const openModal = (product?: Product) => {
    editingProduct.value = product || null
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
    editingProduct.value = null
}

const handleProductSaved = () => {
    closeModal()
    productsStore.fetchProducts()
}

const deleteProduct = async (id: number) => {
    if (confirm('Apakah Anda yakin ingin menghapus produk ini?')) {
        try {
            await productsStore.deleteProduct(id)
            // Show success toast
            console.log('Produk berhasil dihapus')
        } catch (error) {
            // Show error toast
            console.error('Gagal menghapus produk:', error)
        }
    }
}
</script>
