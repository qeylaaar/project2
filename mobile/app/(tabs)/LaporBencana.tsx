import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  Platform,
  Image,
  ScrollView,
  Alert,
  ActivityIndicator,} from 'react-native';
import React, { useState, useEffect } from 'react';
import { StatusBar } from 'expo-status-bar';
import { AntDesign } from '@expo/vector-icons';
import { useNavigation } from '@react-navigation/native';
import DateTimePicker from '@react-native-community/datetimepicker';
import { useRouter, useLocalSearchParams } from 'expo-router';
import * as ImagePicker from 'expo-image-picker';
import { Video, ResizeMode } from 'expo-av';
import axios from 'axios';
import * as Location from 'expo-location';
import JenisBencanaPicker from '../api/JenisBencanaPicker';
import LocationPickers from '../api/LocationPickers';  
import AsyncStorage from '@react-native-async-storage/async-storage';

export default function ReportScreen() {
  const navigation = useNavigation();
  const router = useRouter();

  const [namaLengkap, setNamaLengkap] = useState('');
  const [tanggal, setTanggal] = useState(new Date());
  const [waktu, setWaktu] = useState('');
  const [showDatePicker, setShowDatePicker] = useState(false);

  const [jenisBencana, setJenisBencana] = useState('');
  const [customJenisBencana, setCustomJenisBencana] = useState('');

  const [kecamatan, setKecamatan] = useState('Pilih Kecamatan');
  const [desa, setDesa] = useState('Pilih Desa');

  const [alamat, setAlamat] = useState('');
  const [media, setMedia] = useState<{ uri: string; type: string } | null>(null);
  const [loadingLocation, setLoadingLocation] = useState(false); 
  const [deskripsi, setDeskripsi] = useState('');
  const [userId, setUserId] = useState('');
  const [linkLokasi, setLinkLokasi] = useState('');

  useEffect(() => {
    const fetchUser = async () => {
      try {
        const userId = await AsyncStorage.getItem('userId');
        if (userId) {
          setUserId(userId);
          const response = await fetch(`http://192.168.56.1:8000/api/user/${userId}`);
          const result = await response.json();
          if (result.success && result.data) {
            setNamaLengkap(result.data.nama_user || '');
          }
        }
      } catch (e) {
        setNamaLengkap('');
      }
    };
    fetchUser();

    const now = new Date();
    const jam = now.getHours().toString().padStart(2, '0');
    const menit = now.getMinutes().toString().padStart(2, '0');
    setWaktu(`${jam}:${menit}`);
  }, []);

  const handleDateChange = (event: any, selectedDate?: Date) => {
    setShowDatePicker(false);
    if (selectedDate) setTanggal(selectedDate);
  };

  const formatDate = (date: Date) => {
    // Format: YYYY-MM-DD
    const year = date.getFullYear();
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const day = date.getDate().toString().padStart(2, '0');
    return `${year}-${month}-${day}`;
  };

  const { data } = useLocalSearchParams();

  const pickFromCamera = async () => {
    const result = await ImagePicker.launchCameraAsync({
      mediaTypes: ImagePicker.MediaTypeOptions.All,
      allowsEditing: true,
      quality: 1,
    });
    if (!result.canceled) {
      const selected = result.assets[0];
      setMedia({
        uri: selected.uri,
        type: selected.type || 'image'
      });
    }
  };

  const pickFromGallery = async () => {
    const result = await ImagePicker.launchImageLibraryAsync({
      mediaTypes: ImagePicker.MediaTypeOptions.All,
      allowsEditing: true,
      quality: 1,
    });
    if (!result.canceled) {
      const selected = result.assets[0];
      setMedia({
        uri: selected.uri,
        type: selected.type || 'image'
      });
    }
  };

  const handleGetLocation = async () => {
    setLoadingLocation(true);
    try {
      let { status } = await Location.requestForegroundPermissionsAsync();
      if (status !== 'granted') {
        Alert.alert('Izin Lokasi Ditolak', 'Mohon berikan izin akses lokasi untuk mengisi alamat secara otomatis.');
        setLoadingLocation(false);
        return;
      }

      let location = await Location.getCurrentPositionAsync({});
      const { latitude, longitude } = location.coords;

      const dummyAddress = `Jalan Contoh No. 123, Desa Dummy, Kecamatan Dummy, Subang`;
      setAlamat(dummyAddress);
      
      Alert.alert('Lokasi Ditemukan', 'Alamat berhasil diisi secara otomatis.');

    } catch (error) {
      console.error('Gagal mendapatkan lokasi:', error);
      Alert.alert('Gagal Mendapatkan Lokasi', 'Terjadi kesalahan saat mencoba mendapatkan lokasi Anda.');
    } finally {
      setLoadingLocation(false);
    }
  };

  const handleSubmit = async () => {
    // Tentukan jenis bencana akhir yang akan disimpan
    const finalJenisBencana = jenisBencana === 'Lainnya' ? customJenisBencana : jenisBencana;

    // Validasi dasar
    if (!namaLengkap || !finalJenisBencana || kecamatan === 'Pilih Kecamatan' || desa === 'Pilih Desa' || !alamat) {
      Alert.alert('Form Belum Lengkap', 'Mohon lengkapi semua kolom yang wajib diisi.');
      return;
    }

    // Validasi jenis bencana kustom jika 'Lainnya' dipilih
    if (jenisBencana === 'Lainnya' && !customJenisBencana.trim()) {
      Alert.alert('Jenis Bencana Tidak Boleh Kosong', 'Mohon masukkan jenis bencana lainnya.');
      return;
    }

    const formData = new FormData();
    formData.append('nama_pelapor', namaLengkap);
    formData.append('tanggal', formatDate(tanggal));
    formData.append('waktu', waktu);
    formData.append('jenis_pengaduan', finalJenisBencana);
    formData.append('kecamatan', kecamatan);
    formData.append('desa', desa);
    formData.append('alamat', alamat);
    formData.append('status', 'Menunggu');
    formData.append('deskripsi', deskripsi);
    formData.append('user_id', userId);

    console.log('Form Data yang dikirim:', {
      nama_pelapor: namaLengkap,
      tanggal: formatDate(tanggal),
      waktu: waktu,
      jenis_pengaduan: finalJenisBencana,
      kecamatan: kecamatan,
      desa: desa,
      alamat: alamat,
      status: 'Menunggu',
      deskripsi: deskripsi,
      user_id: userId
    });

    if (media) {
      formData.append('media', {
        uri: media.uri,
        name: 'bukti.' + (media.type === 'image' ? 'jpg' : 'mp4'),
        type: media.type === 'image' ? 'image/jpeg' : 'video/mp4',
      } as any);
    }

    try {
      const response = await axios.post('http://192.168.56.1:8000/api/pengaduans', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      });
      console.log('Response dari server:', response.data);
      
      Alert.alert('Laporan Terkirim', 'Laporan bencana Anda berhasil dikirim!');
      router.push('/Homepage');
    } catch (error: any) {
      console.error('Gagal menyimpan laporan:', error);
      if (error.response) {
        console.log('Response data:', error.response.data);
        console.log('Response status:', error.response.status);
        console.log('Response headers:', error.response.headers);
      } else if (error.request) {
        console.log('Request:', error.request);
      } else {
        console.log('Error Message:', error.message);
      }
      Alert.alert('Terjadi Kesalahan', 'Terjadi kesalahan saat menyimpan laporan.');
    }
  };

  const isiOtomatisLokasi = async () => {
    try {
      let { status } = await Location.requestForegroundPermissionsAsync();
      if (status !== 'granted') {
        Alert.alert('Izin Lokasi Ditolak', 'Mohon izinkan akses lokasi.');
        return;
      }
      let location = await Location.getCurrentPositionAsync({});
      let [geo] = await Location.reverseGeocodeAsync(location.coords);

      setAlamat(
        [geo.street, geo.name, geo.subregion, geo.region]
          .filter(Boolean)
          .join(', ')
      );
      setKecamatan(geo.subregion || '');
      setDesa(geo.name || '');

      Alert.alert('Lokasi Diisi Otomatis', 'Kecamatan, Desa, dan alamat berhasil diisi.');
    } catch (error) {
      Alert.alert('Gagal', 'Tidak bisa mendapatkan lokasi.');
    }
  };

  const lampirkanLinkLokasi = async () => {
    try {
      let { status } = await Location.requestForegroundPermissionsAsync();
      if (status !== 'granted') {
        Alert.alert('Izin Lokasi Ditolak', 'Mohon izinkan akses lokasi.');
        return;
      }
      let location = await Location.getCurrentPositionAsync({});
      let [geo] = await Location.reverseGeocodeAsync(location.coords);

      setAlamat(
        [geo.street, geo.name, geo.subregion, geo.region]
          .filter(Boolean)
          .join(', ')
      );
      setKecamatan(geo.subregion || '');
      setDesa(geo.name || '');

      // Buat link Google Maps
      const link = `https://maps.google.com/?q=${location.coords.latitude},${location.coords.longitude}`;
      setLinkLokasi(link);

      Alert.alert('Link Lokasi Dilampirkan', 'Kecamatan, Desa, alamat, dan link lokasi berhasil diisi.');
    } catch (error) {
      Alert.alert('Gagal', 'Tidak bisa mendapatkan lokasi.');
    }
  };

  return (
    <View style={styles.container}>
      <StatusBar style="light" />
      
      {/* Header tetap di atas */}
      <View style={styles.topShape}>
        <TouchableOpacity
          style={styles.backButton}
          onPress={() => router.push('/Homepage')}>
          <AntDesign name="arrowleft" size={24} color="white" />
        </TouchableOpacity>
        <Text style={styles.headerText}>Form Laporan Bencana</Text>
      </View>

      {/* Konten yang dapat di-scroll */}
      <ScrollView
        style={styles.scrollView}
        contentContainerStyle={styles.scrollContainer}
        showsVerticalScrollIndicator={false}
      >
        <View style={styles.formContainer}>
          <Text style={styles.label}>Nama Lengkap</Text>
          <TextInput
            style={styles.input}
            value={namaLengkap}
            editable={false}
            placeholder="Nama Lengkap"
          />

          <Text style={styles.label}>Tanggal Kejadian</Text>
          <TouchableOpacity onPress={() => setShowDatePicker(true)} style={styles.input}>
            <Text>{formatDate(tanggal)}</Text>
          </TouchableOpacity>
          {showDatePicker && (
            <DateTimePicker
              value={tanggal}
              mode="date"
              display={Platform.OS === 'ios' ? 'spinner' : 'default'}
              onChange={handleDateChange}
            />
          )}

          <Text style={styles.label}>Waktu Kejadian</Text>
          <TextInput style={styles.input} value={waktu} editable={false} />

          {/* Memanggil komponen JenisBencanaPicker */}
          <JenisBencanaPicker
            jenisBencana={jenisBencana}
            setJenisBencana={setJenisBencana}
            customJenisBencana={customJenisBencana}
            setCustomJenisBencana={setCustomJenisBencana}
          />

          {/* Memanggil komponen LocationPickers */}
          <LocationPickers
            kecamatan={kecamatan}
            setKecamatan={setKecamatan}
            desa={desa}
            setDesa={setDesa}
          />

          <Text style={styles.label}>Alamat Lengkap</Text>
          <TextInput
            style={styles.input}
            placeholder="Masukkan Alamat Lengkap"
            value={alamat}
            onChangeText={setAlamat}
            multiline
            numberOfLines={4}
          />
          
          <View style={{ flexDirection: 'row', justifyContent: 'space-between', marginTop: 10 }}>
            <TouchableOpacity
              style={[styles.button, { backgroundColor: '#D32F2F', flex: 1, marginRight: 5 }]}
              onPress={isiOtomatisLokasi}
            >
              <Text style={{ color: 'white', textAlign: 'center' }}>Isi Otomatis</Text>
            </TouchableOpacity>
            <TouchableOpacity
              style={[styles.button, { backgroundColor: '#43A047', flex: 1, marginLeft: 5 }]}
              onPress={lampirkanLinkLokasi}
            >
              <Text style={{ color: 'white', textAlign: 'center' }}>Lampirkan Link</Text>
            </TouchableOpacity>
          </View>

          <Text style={styles.label}>Deskripsi Kejadian</Text>
          <TextInput
            style={[styles.input, styles.textArea]}
            placeholder="Masukkan deskripsi kejadian"
            value={deskripsi}
            onChangeText={setDeskripsi}
            multiline
            numberOfLines={4}
          />

          <View style={{ flexDirection: 'row', justifyContent: 'space-between', marginTop: 10 }}>
            <TouchableOpacity
              onPress={pickFromCamera}
              style={[styles.uploadButton, { backgroundColor: '#1976D2', flex: 1, marginRight: 5 }]}
            >
              <Text style={[styles.uploadText, { color: 'white', textAlign: 'center' }]}>Buka Kamera</Text>
            </TouchableOpacity>
            <TouchableOpacity
              onPress={pickFromGallery}
              style={[styles.uploadButton, { backgroundColor: '#7C3AED', flex: 1, marginLeft: 5 }]}
            >
              <Text style={[styles.uploadText, { color: 'white', textAlign: 'center' }]}>Pilih Galeri</Text>
            </TouchableOpacity>
          </View>
          {media?.type === 'image' && (
            <Image source={{ uri: media.uri }} style={styles.previewMedia} />
          )}
          {media?.type === 'video' && (
            <Video
              source={{ uri: media.uri }}
              style={styles.previewMedia}
              useNativeControls
              resizeMode={ResizeMode.CONTAIN}
            />
          )}

          <TouchableOpacity style={styles.button} onPress={handleSubmit}>
            <Text style={styles.buttonText}>LAPOR</Text>
          </TouchableOpacity>
        </View>
      </ScrollView>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#FFF1E1' },
  topShape: {
    position: 'absolute',
    width: '100%',
    height: 100,
    backgroundColor: '#D2601A',
    borderBottomLeftRadius: 30,
    borderBottomRightRadius: 30,
    justifyContent: 'center',
    paddingTop: 20,
    zIndex: 5,
  },
  backButton: {
    position: 'absolute',
    left: 20,
    top: 40,
    zIndex: 11,
  },
  headerText: {
    color: 'white',
    fontSize: 20,
    fontWeight: 'bold',
    textAlign: 'center',
    marginTop: 10,
  },
  scrollView: {
    flex: 1,
    marginTop: 100,
  },
  scrollContainer: {
    alignItems: 'center',
    paddingBottom: 30,
  },
  formContainer: {
    width: '90%',
    backgroundColor: 'white',
    padding: 20,
    borderRadius: 12,
    elevation: 5,
  },
  label: {
    fontSize: 14,
    fontWeight: 'bold',
    color: '#333',
    marginTop: 10,
  },
  input: {
    width: '100%',
    backgroundColor: '#f5f5f5',
    padding: 12,
    borderRadius: 8,
    fontSize: 14,
    color: '#333',
    marginTop: 5,
  },
  pickerContainer: {
    backgroundColor: '#f5f5f5',
    borderRadius: 8,
    marginTop: 5,
    overflow: 'hidden',
  },
  picker: {
    width: '100%',
    height: 50,
    color: '#333',
  },
  uploadButton: {
    padding: 12,
    borderRadius: 8,
    marginTop: 5,
    alignItems: 'center',
  },
  uploadText: {
    color: '#fff',
    fontWeight: 'bold',
  },
  previewMedia: {
    width: '100%',
    height: 200,
    marginTop: 10,
    borderRadius: 8,
  },
  button: {
    marginTop: 20,
    width: '100%',
    backgroundColor: '#D2601A',
    padding: 14,
    borderRadius: 8,
    alignItems: 'center',
  },
  buttonText: { color: 'white', fontSize: 16, fontWeight: 'bold' },
  autoFillButton: {
    backgroundColor: '#FF0000',
    marginTop: 10,
  },
  textArea: {
    height: 100,
    textAlignVertical: 'top',
  },
});