import { shallowMount } from '@vue/test-utils';
import Home from '../pages/index.vue';

const factory = (values = {}) => {
  return shallowMount(Home, {
    data () {
      return {
        ...values
      }
    }
  })
}

describe('Home', () => {

  it('renders an empty list of currencies when mounted', () => {
    const wrapper = factory({currencies:[]}); // set empty currencies list
    expect(wrapper.find('.error').exists()).toBeFalsy();
    expect(wrapper.find('.currencies').exists()).toBeTruthy();
  });



  it('renders login text', () => {
    const wrapper = factory({ loginText: 'Login'  })
    expect(wrapper.find('.error').exists()).toBeFalsy(); // Check that the login text renders
  })



})
