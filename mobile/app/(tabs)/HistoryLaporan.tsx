import { View, Text, ScrollView, StyleSheet, TouchableOpacity, ActivityIndicator, RefreshControl } from 'react-native';
import React, { useEffect, useState } from 'react';
import { useLocalSearchParams } from 'expo-router';
import { AntDesign } from '@expo/vector-icons';
import { useRouter } from 'expo-router';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { Picker } from '@react-native-picker/picker';
import { API_URL } from '../api/config';
import { useFocusEffect } from '@react-navigation/native';

export default function HistoryLaporan() {
  const router = useRouter();
  const [laporanList, setLaporanList] = useState<any[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState('');
  const [jenisFilter, setJenisFilter] = useState<string>('Semua');
  const [jenisList, setJenisList] = useState<string[]>([]);
  const [refreshing, setRefreshing] = useState(false);

  const fetchLaporan = async () => {
    try {
      setLoading(true);
      const userId = await AsyncStorage.getItem('userId');
      console.log('User ID from storage:', userId);
      if (!userId) {
        setError('User tidak ditemukan. Silakan login kembali.');
        setLoading(false);
        return;
      }
      const response = await fetch(`${API_URL}/pengaduans/user/${userId}`);
      const data = await response.json();
      console.log('Response from API:', data);
      if (data.success) {
        setLaporanList(data.data);
        const jenisSet = new Set(data.data.map((item: any) => item.jenis_pengaduan));
        setJenisList(['Semua', ...Array.from(jenisSet) as string[]]);
      } else {
        setError('Gagal mengambil data laporan');
      }
    } catch (error) {
      console.error('Error fetching laporan:', error);
      setError('Terjadi kesalahan saat mengambil data');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchLaporan();
  }, []);

  useFocusEffect(
    React.useCallback(() => {
      fetchLaporan();
    }, [])
  );

  const onRefresh = async () => {
    setRefreshing(true);
    await fetchLaporan();
    setRefreshing(false);
  };

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'Menunggu':
        return '#FFA500'; // Orange
      case 'Proses':
        return '#007AFF'; // Blue
      case 'Selesai':
        return '#4CAF50'; // Green
      default:
        return '#666666'; // Gray
    }
  };

  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
      day: '2-digit',
      month: 'long',
      year: 'numeric'
    });
  };

  // Filter laporan sesuai jenis pengaduan
  const filteredLaporan = jenisFilter === 'Semua'
    ? laporanList
    : laporanList.filter((laporan: any) => laporan.jenis_pengaduan === jenisFilter);

  if (loading) {
    return (
      <View style={styles.centerContainer}>
        <ActivityIndicator size="large" color="#D2601A" />
      </View>
    );
  }

  if (error) {
    return (
      <View style={styles.centerContainer}>
        <Text style={styles.errorText}>{error}</Text>
        <TouchableOpacity
          style={styles.homeButton}
          onPress={() => router.push('/Homepage')}>
          <Text style={styles.homeButtonText}>Kembali ke Homepage</Text>
        </TouchableOpacity>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <View style={styles.header}>
        <TouchableOpacity
          style={styles.backButton}
          onPress={() => router.push('/Homepage')}>
          <AntDesign name="arrowleft" size={24} color="black" />
        </TouchableOpacity>
        <Text style={styles.title}>Riwayat Laporan</Text>
      </View>

      {/* Dropdown Filter Jenis Pengaduan */}
      <View style={styles.pickerContainer}>
        <Picker
          selectedValue={jenisFilter as any}
          onValueChange={(itemValue: any) => setJenisFilter(String(itemValue))}
          style={styles.picker}
        >
          {jenisList.map((jenis, idx) => (
            <Picker.Item key={idx} label={jenis} value={jenis} />
          ))}
        </Picker>
      </View>

      {filteredLaporan.length === 0 ? (
        <View style={styles.noDataContainer}>
          <Text style={styles.noData}>Belum ada laporan.</Text>
          <TouchableOpacity
            style={styles.homeButton}
            onPress={() => router.push('/Homepage')}>
            <Text style={styles.homeButtonText}>Kembali ke Homepage</Text>
          </TouchableOpacity>
        </View>
      ) : (
        <ScrollView
          style={styles.scrollView}
          refreshControl={
            <RefreshControl refreshing={refreshing} onRefresh={onRefresh} />
          }
        >
          {filteredLaporan.map((laporan: any, index: number) => (
            <TouchableOpacity
              key={index}
              style={styles.card}
              onPress={() => router.push({
                pathname: '/DetailLaporan',
                params: { data: encodeURIComponent(JSON.stringify(laporan)) }
              })}>
              <View style={styles.cardHeader}>
                <Text style={styles.date}>{formatDate(laporan.tanggal)}</Text>
                <Text style={[styles.status, { color: getStatusColor(laporan.status) }]}>
                  {laporan.status}
                </Text>
              </View>
              
              <Text style={styles.jenisBencana}>{laporan.jenis_pengaduan}</Text>
              
              <View style={styles.locationContainer}>
                <Text style={styles.location}>
                  {laporan.desa}, Kec. {laporan.kecamatan}
                </Text>
              </View>
              
              <Text style={styles.alamat}>{laporan.alamat}</Text>
              
              {laporan.deskripsi && (
                <Text style={styles.deskripsi}>{laporan.deskripsi}</Text>
              )}
              
              <View style={styles.footer}>
                <Text style={styles.time}>{laporan.waktu}</Text>
              </View>
            </TouchableOpacity>
          ))}
        </ScrollView>
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#FFF1E1',
    paddingTop: 40,
  },
  scrollView: {
    padding: 16,
  },
  centerContainer: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#FFF1E1',
    padding: 20,
  },
  header: {
    flexDirection: 'row',
    alignItems: 'center',
    paddingHorizontal: 16,
    paddingVertical: 12,
    backgroundColor: '#FFF',
    elevation: 2,
  },
  title: {
    fontSize: 20,
    fontWeight: 'bold',
    marginLeft: 32,
    color: '#333',
  },
  pickerContainer: {
    backgroundColor: '#fff',
    marginHorizontal: 16,
    marginTop: 10,
    borderRadius: 8,
    elevation: 2,
    overflow: 'hidden',
  },
  picker: {
    width: '100%',
    height: 50,
    color: '#333',
  },
  card: {
    backgroundColor: '#FFF',
    borderRadius: 12,
    padding: 16,
    marginBottom: 12,
    elevation: 2,
  },
  cardHeader: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 8,
  },
  date: {
    fontSize: 14,
    color: '#666',
  },
  status: {
    fontSize: 14,
    fontWeight: 'bold',
  },
  jenisBencana: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 8,
  },
  locationContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 4,
  },
  location: {
    fontSize: 14,
    color: '#666',
  },
  alamat: {
    fontSize: 14,
    color: '#333',
    marginBottom: 8,
  },
  deskripsi: {
    fontSize: 14,
    color: '#666',
    marginTop: 8,
    fontStyle: 'italic',
  },
  footer: {
    marginTop: 8,
    flexDirection: 'row',
    justifyContent: 'flex-end',
  },
  time: {
    fontSize: 12,
    color: '#999',
  },
  backButton: {
    padding: 8,
  },
  homeButton: {
    marginTop: 20,
    backgroundColor: '#D2601A',
    paddingVertical: 12,
    paddingHorizontal: 24,
    borderRadius: 8,
    alignItems: 'center',
  },
  homeButtonText: {
    color: 'white',
    fontSize: 16,
    fontWeight: 'bold',
  },
  noDataContainer: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
  },
  noData: {
    fontSize: 16,
    color: '#666',
    textAlign: 'center',
  },
  errorText: {
    color: '#FF3B30',
    fontSize: 16,
    textAlign: 'center',
    marginBottom: 20,
  },
});
