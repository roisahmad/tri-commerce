import { defineStore } from 'pinia'
import type { Invoice, PaymentChannel, CreateTransactionPayload, PaymentChannelResponse, InvoiceResponse } from '~/types'

export const useInvoicesStore = defineStore('invoices', () => {
  const invoices = ref<Invoice[]>([])
  const paymentChannels = ref<PaymentChannel[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)

  const api = useApi()

  const fetchInvoices = async () => {
    try {
      loading.value = true
      error.value = null

      const response = await api<InvoiceResponse>(`/invoices`)
      invoices.value = response.data   // <-- ambil data arraynya

    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to fetch invoices'
    } finally {
      loading.value = false
    }
  }


  const fetchPaymentChannels = async () => {
    try {
      loading.value = true
      error.value = null
      const response = await api<PaymentChannelResponse>(`/invoices/payment/channels`)
      console.log('Payment Channels Response:', response)
      paymentChannels.value = response.data
    } catch (err) {
      error.value = err instanceof Error ? err.message : 'Failed to fetch payment channels'
    } finally {
      loading.value = false
    }
  }

const createTransaction = async (payload: CreateTransactionPayload) => {
  try {
    loading.value = true
    error.value = null
    const response = await api<{ status: string; data: any }>(`/invoices/transaction`, {
      method: 'POST',
      body: payload
    })
    await fetchInvoices()
    return response.data 
  } catch (err) {
    error.value = err instanceof Error ? err.message : 'Failed to create transaction'
    throw err
  } finally {
    loading.value = false
  }
}


  return {
    invoices: readonly(invoices),
    paymentChannels: readonly(paymentChannels),
    loading: readonly(loading),
    error: readonly(error),
    fetchInvoices,
    fetchPaymentChannels,
    createTransaction
  }
})