import { View, Text, ScrollView, StyleSheet, TouchableOpacity } from 'react-native';
import React, { useEffect, useState } from 'react';
import { useLocalSearchParams } from 'expo-router';
import { AntDesign } from '@expo/vector-icons';
import { useRouter } from 'expo-router';
import AsyncStorage from '@react-native-async-storage/async-storage';

export default function HistoryLaporan() {
  const { data } = useLocalSearchParams();
  const router = useRouter();

  const [laporanList, setLaporanList] = useState<any[]>([]);

  // Simpan data jika ada yang dikirim dari halaman sebelumnya
  useEffect(() => {
    const saveData = async () => {
      if (data) {
        const newData = JSON.parse(decodeURIComponent(data as string));
        const existingData = await AsyncStorage.getItem('riwayatLaporan');
        const parsedExisting = existingData ? JSON.parse(existingData) : [];
        const updatedData = [...parsedExisting, newData];
        await AsyncStorage.setItem('riwayatLaporan', JSON.stringify(updatedData));
        setLaporanList(updatedData);
      } else {
        // Jika tidak ada data baru, ambil dari penyimpanan
        const stored = await AsyncStorage.getItem('riwayatLaporan');
        if (stored) setLaporanList(JSON.parse(stored));
      }
    };
    saveData();
  }, [data]);

  return (
    <ScrollView style={styles.container}>
      <View style={styles.header}>
        <TouchableOpacity
          style={styles.backButton}
          onPress={() => router.push('/Homepage')}>
          <AntDesign name="arrowleft" size={24} color="black" />
        </TouchableOpacity>
        <Text style={styles.title}>Riwayat Laporan</Text>
      </View>

      {laporanList.length === 0 ? (
        <Text style={styles.noData}>Belum ada laporan.</Text>
      ) : (
        laporanList.map((laporan: any, index: number) => (
          <View key={index} style={styles.card}>
            <Text style={styles.item}>Nama: {laporan.namaLengkap}</Text>
            <Text style={styles.item}>Tanggal: {laporan.tanggal}</Text>
            <Text style={styles.item}>Waktu: {laporan.waktu}</Text>
            <Text style={styles.item}>Bencana: {laporan.jenisBencana}</Text>
            <Text style={styles.item}>Kecamatan: {laporan.kecamatan}</Text>
            <Text style={styles.item}>Desa: {laporan.desa}</Text>
            <Text style={styles.item}>Alamat: {laporan.alamat}</Text>
          </View>
        ))
      )}

      <TouchableOpacity
        style={styles.homeButton}
        onPress={() => router.push('/Homepage')}>
        <Text style={styles.homeButtonText}>Kembali ke Homepage</Text>
      </TouchableOpacity>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: { padding: 20, backgroundColor: '#FFF' },
  header: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 20,
  },
  title: { fontSize: 20, fontWeight: 'bold', marginLeft: 10 },
  card: {
    backgroundColor: '#f0f0f0',
    padding: 15,
    borderRadius: 8,
    marginBottom: 10,
  },
  item: { marginBottom: 4 },
  backButton: {
    padding: 4,
  },
  homeButton: {
    marginTop: 20,
    backgroundColor: '#D2601A',
    paddingVertical: 12,
    borderRadius: 8,
    alignItems: 'center',
  },
  homeButtonText: {
    color: 'white',
    fontSize: 16,
    fontWeight: 'bold',
  },
  noData: {
    textAlign: 'center',
    color: 'gray',
    marginTop: 20,
  },
});
