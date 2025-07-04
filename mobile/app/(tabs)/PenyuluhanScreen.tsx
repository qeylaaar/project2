import { View, Text, StyleSheet, TouchableOpacity, FlatList, ActivityIndicator, ScrollView } from 'react-native';
import React, { useEffect, useState } from 'react';
import { useRouter } from 'expo-router';
import { AntDesign } from '@expo/vector-icons';
import axios from 'axios';
import { API_URL } from '../api/config';

// Konfigurasi axios
const api = axios.create({
  baseURL: API_URL.replace(/\/$/, ''), // pastikan tidak ada trailing slash
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
});

interface Penyuluhan {
  id: number;
  judul: string;
  deskripsi: string;
  tanggal: string;
  waktu: string;
  lokasi: string;
  pemateri: string;
  status: string;
}

function formatTanggal(tanggal: string) {
  const date = new Date(tanggal);
  return date.toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' });
}

function formatWaktu(waktu: string) {
  const date = new Date(waktu);
  return date.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
}

export default function PenyuluhanScreen() {
  const router = useRouter();
  const [penyuluhan, setPenyuluhan] = useState<Penyuluhan[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  useEffect(() => {
    fetchPenyuluhan();
  }, []);

  const fetchPenyuluhan = async () => {
    try {
      setLoading(true);
      const response = await api.get('/penyuluhan');
      setPenyuluhan(response.data.data);
      setError(null);
    } catch (err: any) {
      let errorMessage = 'Gagal memuat data penyuluhan';
      if (err.response) {
        // Server merespons dengan status code di luar range 2xx
        console.error('Error response:', err.response.data);
        errorMessage = `Error: ${err.response.status} - ${err.response.data.message || 'Unknown error'}`;
      } else if (err.request) {
        // Request dibuat tapi tidak ada response
        console.error('No response received:', err.request);
        errorMessage = 'Tidak dapat terhubung ke server. Pastikan server berjalan.';
      } else {
        // Ada error saat setup request
        console.error('Error setting up request:', err.message);
        errorMessage = `Error: ${err.message}`;
      }
      setError(errorMessage);
    } finally {
      setLoading(false);
    }
  };

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <ActivityIndicator size="large" color="#D2601A" />
      </View>
    );
  }

  if (error) {
    return (
      <View style={styles.errorContainer}>
        <Text style={styles.errorText}>{error}</Text>
        <TouchableOpacity style={styles.retryButton} onPress={fetchPenyuluhan}>
          <Text style={styles.retryText}>Coba Lagi</Text>
        </TouchableOpacity>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <FlatList
        data={penyuluhan}
        keyExtractor={(item) => item.id.toString()}
        contentContainerStyle={{ padding: 16, paddingTop: 80 }}
        renderItem={({ item }) => {
          console.log('ITEM:', item);
          return (
            <TouchableOpacity 
              onPress={() => router.push({
                pathname: '/detailPenyuluhan',
                params: { id: item.id },
              })}
            >
              <View style={styles.card}>
                <Text style={styles.tema}>{item.judul}</Text>
                <Text style={styles.subText}>Tanggal: {formatTanggal(item.tanggal)}</Text>
                <Text style={styles.subText}>Waktu: {formatWaktu(item.waktu)}</Text>
                <Text style={styles.subText}>Lokasi: {item.lokasi}</Text>
                <Text style={styles.subText}>Pemateri: {item.pemateri}</Text>
                <Text style={styles.deskripsi}>{item.deskripsi}</Text>
                <View style={styles.statusContainer}>
                  <Text style={[
                    styles.statusText,
                    { color: item.status === 'aktif' ? '#4CAF50' : 
                            item.status === 'selesai' ? '#9E9E9E' : '#F44336' }
                  ]}>
                    Status: {item.status}
                  </Text>
                </View>
              </View>
            </TouchableOpacity>
          );
        }}
      />
      <TouchableOpacity
        style={styles.backButtonBottom}
        onPress={() => router.push('/Homepage')}
      >
        <Text style={styles.backButtonText}>Kembali ke Beranda</Text>
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#FFF1E1',
  },
  loadingContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#FFF1E1',
  },
  errorContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#FFF1E1',
    padding: 20,
  },
  errorText: {
    color: '#D2601A',
    fontSize: 16,
    marginBottom: 16,
    textAlign: 'center',
  },
  retryButton: {
    backgroundColor: '#D2601A',
    paddingHorizontal: 20,
    paddingVertical: 10,
    borderRadius: 8,
  },
  retryText: {
    color: 'white',
    fontSize: 16,
  },
  card: {
    backgroundColor: '#D2601A',
    padding: 16,
    borderRadius: 20,
    marginBottom: 16,
  },
  tema: {
    color: 'white',
    fontSize: 18,
    fontWeight: 'bold',
  },
  subText: {
    color: '#fff',
    marginTop: 4,
  },
  deskripsi: {
    color: '#fff',
    marginTop: 8,
    fontStyle: 'italic',
  },
  statusContainer: {
    marginTop: 8,
    paddingTop: 8,
    borderTopWidth: 1,
    borderTopColor: 'rgba(255,255,255,0.3)',
  },
  statusText: {
    fontWeight: 'bold',
  },
  backButtonBottom: {
    backgroundColor: '#D2601A',
    paddingVertical: 14,
    paddingHorizontal: 32,
    borderRadius: 25,
    marginTop: 30,
    marginBottom: 40,
    alignSelf: 'center',
    elevation: 4,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.2,
    shadowRadius: 4,
  },
  backButtonText: {
    color: 'white',
    fontSize: 17,
    fontWeight: 'bold',
    letterSpacing: 1,
    textAlign: 'center',
  },
});
