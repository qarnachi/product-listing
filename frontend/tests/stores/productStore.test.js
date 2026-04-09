import { setActivePinia, createPinia } from 'pinia'
import axios from 'axios'

jest.mock('axios')

import { useProductStore } from '@/stores/productStore'

describe('Product Store - Jest Unit Test', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
    axios.get.mockReset()
  })

  test('fetchProducts remplit bien la liste des produits', async () => {
    const mockProducts = [
      { id: 1, name: 'Product A' },
      { id: 2, name: 'Product B' },
    ]

    axios.get.mockResolvedValueOnce({ data: mockProducts })

    const store = useProductStore()

    await store.fetchProducts()

    expect(store.products).toEqual(mockProducts)
    expect(store.loading).toBe(false)
  })

  test('loading passe à true pendant fetchProducts', async () => {
    axios.get.mockResolvedValueOnce({ data: [] })

    const store = useProductStore()

    const promise = store.fetchProducts()

    expect(store.loading).toBe(true)

    await promise

    expect(store.loading).toBe(false)
  })
})
