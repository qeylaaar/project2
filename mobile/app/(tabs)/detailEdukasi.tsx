import React, { useEffect, useState } from 'react';
import { View, Text, ActivityIndicator, StyleSheet, ScrollView, TouchableOpacity, StatusBar } from 'react-native';
import { useLocalSearchParams, useRouter } from 'expo-router';
import { AntDesign } from '@expo/vector-icons';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { API_URL } from '../api/config';

const DetailEdukasi = () => {
  const { id } = useLocalSearchParams();
  const router = useRouter();
  const insets = useSafeAreaInsets();
  const [data, setData] = useState<any>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (!id) return;
    fetch(`${API_URL}/edukasi-bencana/detail/${id}`)
      .then(res => res.json())
      .then(json => {
        setData(json);
        setLoading(false);
      })
      .catch(() => setLoading(false));
  }, [id]);

  if (loading) {
    return <ActivityIndicator size="large" color="#D48442" style={{ marginTop: 40 }} />;
  }

  if (!data) {
    return <Text style={{ textAlign: 'center', marginTop: 40, color: 'red' }}>Data tidak ditemukan.</Text>;
  }

  return (
    <View style={styles.container}>
      <StatusBar barStyle="light-content" backgroundColor="#D48442" />
      <View style={[styles.header, { paddingTop: insets.top + 10 }]}>
        <TouchableOpacity 
          style={styles.backButton} 
          onPress={() => data && router.push({ pathname: '/(tabs)/ListEdukasiByJenis', params: { jenis: data.jenis_bencana } })}
          disabled={!data}
        >
          <AntDesign name="arrowleft" size={24} color="#D48442" />
        </TouchableOpacity>
        <Text style={styles.headerText}>Detail Edukasi</Text>
        <View style={{ width: 24 }} />
      </View>
      <ScrollView contentContainerStyle={styles.content}>
        <Text style={styles.title}>{data.judul}</Text>
        <Text style={styles.label}>Jenis Bencana: <Text style={styles.value}>{data.jenis_bencana}</Text></Text>
        <Text style={styles.label}>Tanggal: <Text style={styles.value}>{data.tanggal}</Text></Text>
        <Text style={styles.label}>Deskripsi:</Text>
        <Text style={styles.desc}>{data.deskripsi}</Text>
      </ScrollView>
    </View>
  );
};

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#FFF8F0' },
  header: {
    width: '100%',
    backgroundColor: '#FFF8F0',
    flexDirection: 'row',
    alignItems: 'center',
    justifyContent: 'space-between',
    paddingHorizontal: 16,
    paddingBottom: 16,
    borderBottomLeftRadius: 20,
    borderBottomRightRadius: 20,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.07,
    shadowRadius: 3,
    elevation: 2,
    marginBottom: 8,
  },
  backButton: { padding: 4 },
  headerText: {
    flex: 1,
    textAlign: 'center',
    color: '#D48442',
    fontWeight: 'bold',
    fontSize: 20,
  },
  content: { padding: 20 },
  title: { fontWeight: 'bold', fontSize: 22, color: '#333', marginBottom: 10 },
  label: { fontWeight: 'bold', fontSize: 15, marginTop: 10, color: '#D48442' },
  value: { fontWeight: 'normal', color: '#333' },
  desc: { marginTop: 8, fontSize: 15, color: '#444', textAlign: 'justify' },
});

export default DetailEdukasi;