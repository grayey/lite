import { shallowMount } from '@vue/test-utils'
import Transactions from '../pages/dashboard/transactions/index.vue'

const factory = (values = {}) => {
  return shallowMount(Transactions, {
    data () {
      return {
        ...values
      }
    }
  })
}

describe('Transactions', () => {
  it('renders an empty list of transactions when mounted', () => {
    const wrapper = factory({transactions:[]}); // set empty transactions list
    expect(wrapper.find('.error').exists()).toBeFalsy();
    wrapper.setData({transactions:[]});
    expect(wrapper.find('.transactions').exists()).toBeFalsy();

  })

  // it('renders an error when username is less than 7 characters', () => {
  //   const wrapper = factory({ username: ''  })

  //   expect(wrapper.find('.error').exists()).toBeTruthy()
  // })



})
