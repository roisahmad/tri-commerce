import { defineStore } from 'pinia'
import { type ProductResponse, type Product } from '~/types'

export const useProductsStore = defineStore('products', () => {
  const products = ref<Product[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  const api = useApi()

const fetchProducts = async () => {
  try {
    console.log('fetch start')
    loading.value = true
    error.value = null
    const response = await api<ProductResponse>(`/products`)
    console.log('response', response)
    products.value = response.data
    console.log('fetch success')
  } catch (err) {
    console.error('fetch error', err)
    error.value = err instanceof Error ? err.message : 'Failed to fetch products'
  } finally {
    loading.value = false
    console.log('loading reset:', loading.value)
  }
}


  const createProduct = async (product: Omit<Product, 'id' | 'created_at' | 'updated_at'>) => {
    try {
      loading.value = true
      error.value = null
      const response = await api<Product>(`/products`, {
        method: 'POST',
        body: product
      })
      products.value.push(response)
      return response
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to create product'
      throw err
    } finally {
      loading.value = false
    }
  }

  const updateProduct = async (id: number, product: Partial<Product>) => {
    try {
      loading.value = true
      error.value = null
      const response = await api<Product>(`/products/${id}`, {
        method: 'PUT',
        body: product
      })
      const index = products.value.findIndex(p => p.id === id)
      if (index !== -1) {
        products.value[index] = response
      }
      return response
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to update product'
      throw err
    } finally {
      loading.value = false
    }
  }

  const deleteProduct = async (id: number) => {
    try {
      loading.value = true
      error.value = null
      await api(`/products/${id}`, {
        method: 'DELETE'
      })
      products.value = products.value.filter(p => p.id !== id)
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to delete product'
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    products: readonly(products),
    loading: readonly(loading),
    error: readonly(error),
    fetchProducts,
    createProduct,
    updateProduct,
    deleteProduct
  }
})