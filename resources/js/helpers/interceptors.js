import axios from 'axios'
import store from '../store'

axios.interceptors.response.use(
	response => response,
	error => {
		if (error.response.status === 422) {
			store.dispatch('setErrors', error.response.data.errors)
		}

		return Promise.reject(error)
	}
)

axios.interceptors.request.use(
	config =>  {
		store.dispatch('clearErrors')

		return config
	}, 

	error =>  {
		return Promise.reject(error)
	}
);