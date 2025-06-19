import React, { useEffect, useState } from 'react';
import { View, Text, FlatList, TouchableOpacity, ActivityIndicator, StyleSheet, StatusBar } from 'react-native';
import { useLocalSearchParams, useRouter } from 'expo-router';
import { AntDesign } from '@expo/vector-icons';
import { useSafeAreaInsets } from 'react-native-safe-area-context';
import { API_URL } from '../api/config';

const ListEdukasiByJenis = () => {
  const { jenis } = useLocalSearchParams();
  const jenisString = Array.isArray(jenis) ? jenis[0] : jenis ?? '';
  const router = useRouter();
  const insets = useSafeAreaInsets();
  const [data, setData] = useState([]);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    if (!jenisString) return;
    fetch(`${API_URL}/edukasi-bencana/${encodeURIComponent(jenisString)}`)
      .then(res => res.json())
      .then(json => {
        setData(Array.isArray(json) ? json : (json.data || []));
        setLoading(false);
      })
      .catch(() => setLoading(false));
  }, [jenisString]);

  const renderItem = ({ item }: { item: any }) => (
    <TouchableOpacity
      style={styles.card}
      onPress={() => router.push({ pathname: '/detailEdukasi', params: { id: item.id } })}
      activeOpacity={0.8}
    >
      <Text style={styles.title}>{item.judul}</Text>
      <Text style={styles.subtitle}>{item.tanggal}</Text>
    </TouchableOpacity>
  );

  return (
    <View style={styles.container}>
      <StatusBar barStyle="dark-content" backgroundColor="#FFF8F0" />
      {/* Header */}
      <View style={[styles.header, { paddingTop: insets.top + 10 }]}>
        <TouchableOpacity style={styles.backButton} onPress={() => router.push('/(tabs)/EdukasiBencana')}>
          <AntDesign name="arrowleft" size={24} color="#D48442" />
        </TouchableOpacity>
        <Text style={styles.headerText}>Edukasi {jenisString}</Text>
        <View style={{ width: 24 }} />
      </View>
      {/* List */}
      {loading ? (
        <ActivityIndicator size="large" color="#D48442" style={{ marginTop: 40 }} />
      ) : (
        <FlatList
          data={data}
          renderItem={renderItem}
          keyExtractor={item => item.id.toString()}
          contentContainerStyle={styles.listContainer}
          ListEmptyComponent={
            <Text style={{ textAlign: 'center', marginTop: 40, color: '#888' }}>
              Belum ada edukasi untuk {jenisString}
            </Text>
          }
        />
      )}
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#FFF8F0',
  },
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
  backButton: {
    padding: 4,
  },
  headerText: {
    flex: 1,
    textAlign: 'center',
    color: '#D48442',
    fontWeight: 'bold',
    fontSize: 20,
  },
  listContainer: {
    padding: 16,
    paddingTop: 8,
  },
  card: {
    backgroundColor: 'white',
    borderRadius: 12,
    padding: 18,
    marginBottom: 14,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.08,
    shadowRadius: 3,
    elevation: 3,
    borderWidth: 1,
    borderColor: '#f2e6da',
  },
  title: {
    fontWeight: 'bold',
    fontSize: 16,
    color: '#333',
    marginBottom: 6,
  },
  subtitle: {
    color: '#888',
    fontSize: 13,
  },
});

export default ListEdukasiByJenis;
