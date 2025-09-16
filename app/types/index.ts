export interface Product {
  id: number
  sku: string
  name: string
  price: number
  reference: string
  created_at: string
  updated_at: string
}

export interface ProductResponse {
    data: Product[]
    status: string
    message?: string
}

interface FeeStructure {
  flat: number;
  percent: number | string;
}

export interface PaymentChannel {
  group: string;
  code: string;
  name: string;
  type: string;
  fee_merchant: FeeStructure;
  fee_customer: FeeStructure;
  total_fee: FeeStructure;
  minimum_fee: number;
  maximum_fee: number;
  minimum_amount: number;
  maximum_amount: number;
  icon_url: string;
  active: boolean;
}

export interface PaymentChannelResponse {
    data: PaymentChannel[]
    status: string
    message?: string
}

export interface CreateTransactionPayload {
  product_id: number
  payment_method: string
  buyer_email: string
  buyer_phone: string
}

export interface Instruction {
  title: string;
  steps: string[];
}

export interface OrderItem {
  sku: string;
  name: string;
  price: number;
  quantity: number;
  subtotal: number;
  image_url: string | null;
  product_url: string | null;
}

export interface RawResponseData {
  amount: number;
  status: string;
  pay_url: string | null;
  pay_code: string;
  reference: string;
  total_fee: number;
  return_url: string;
  order_items: OrderItem[];
  callback_url: string;
  checkout_url: string;
  expired_time: number;
  fee_customer: number;
  fee_merchant: number;
  instructions: Instruction[];
  merchant_ref: string;
  payment_name: string;
  customer_name: string;
  customer_email: string;
  customer_phone: string;
  payment_method: string;
  amount_received: number;
  payment_selection_type: string;
}

export interface RawResponse {
  data: RawResponseData;
  message: string;
  success: boolean;
}

export interface Invoice {
  id: number;
  product_id: number;
  tripay_reference: string;
  buyer_email: string;
  buyer_phone: string;
  raw_response: RawResponse;
  created_at: string;
  updated_at: string;
  product: Product;
}

export interface InvoiceResponse {
  status: string; 
  data: Invoice[];
}



