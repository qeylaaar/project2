import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { API_URL } from './config';

// Membuat instance axios dengan konfigurasi default
const api = axios.create({
  baseURL: API_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
});

export const login = async (email, password) => {
  try {
    console.log('Attempting login with:', { email }); // Log email yang digunakan
    
    const response = await api.post('/login', {
      email,
      password
    });

    console.log('Login response:', response.data); // Log response lengkap

    if (response.data.success) {
      console.log('Login successful, user data:', response.data.data); // Log data user
      // Simpan userId dan token ke AsyncStorage
      await AsyncStorage.setItem('userId', response.data.data.id_user.toString());
      await AsyncStorage.setItem('token', response.data.token);
      return { success: true, data: response.data };
    } else {
      console.log('Login failed:', response.data.message); // Log pesan error
      return { 
        success: false, 
        error: response.data.message || 'Login gagal'
      };
    }
  } catch (error) {
    console.error('Login error details:', {
      message: error.message,
      response: error.response?.data,
      status: error.response?.status,
      config: error.config // Log konfigurasi request
    });
    
    return { 
      success: false, 
      error: error.response?.data?.message || error.message || 'Terjadi kesalahan saat login. Pastikan server aktif dan terhubung ke internet.' 
    };
  }
};

export const register = async (nama_user, no_telepon, email, password) => {
  try {
    console.log('Sending register request with data:', { nama_user, no_telepon, email });
    console.log('Request URL:', `${API_URL}/register`);
    
    const response = await api.post('/register', {
      nama_user,
      no_telepon,
      email,
      password,
      username: email.split('@')[0], // Menambahkan username dari email
      role: 'user' // Menambahkan role default
    });

    console.log('Register response:', response.data);
    
    if (response.data.success) {
      return { success: true, data: response.data };
    } else {
      return { 
        success: false, 
        error: response.data.message || 'Registrasi gagal'
      };
    }
  } catch (error) {
    console.error('Register error details:', {
      message: error.message,
      response: error.response?.data,
      status: error.response?.status,
      requestData: { nama_user, no_telepon, email },
      headers: error.response?.headers,
      config: error.config,
      fullError: error
    });
    
    // Log response data jika ada
    if (error.response?.data) {
      console.error('Error response data:', error.response.data);
    }
    
    return { 
      success: false, 
      error: error.response?.data?.message || error.message || 'Terjadi kesalahan saat registrasi. Pastikan server aktif dan terhubung ke internet.' 
    };
  }
};
