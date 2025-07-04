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
import { useNavigation, useFocusEffect } from '@react-navigation/native';
import DateTimePicker from '@react-native-community/datetimepicker';
import { useRouter, useLocalSearchParams } from 'expo-router';
import * as ImagePicker from 'expo-image-picker';
import { Video, ResizeMode } from 'expo-av';
import axios from 'axios';
import * as Location from 'expo-location';
import JenisBencanaPicker from '../api/JenisBencanaPicker';
import LocationPickers from '../api/LocationPickers';  
import AsyncStorage from '@react-native-async-storage/async-storage';
import { API_URL } from '../api/config';

export default function ReportScreen() {
  const navigation = useNavigation();
  const router = useRouter();

  const [namaLengkap, setNamaLengkap] = useState('');
  const [tanggal, setTanggal] = useState(new Date());
  const [waktu, setWaktu] = useState('');
  const [showDatePicker, setShowDatePicker] = useState(false);
  const [showTimePicker, setShowTimePicker] = useState(false);

  const [jenisBencana, setJenisBencana] = useState('');
  const [customJenisBencana, setCustomJenisBencana] = useState('');

  const [kecamatan, setKecamatan] = useState('Pilih Kecamatan');
  const [desa, setDesa] = useState('Pilih Desa');

  const [alamat, setAlamat] = useState('');
  const [media, setMedia] = useState<{ uri: string; type: string }[]>([]);
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
          const response = await fetch(`${API_URL}/user/${userId}`);
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

  const handleTimeChange = (event: any, selectedTime?: Date) => {
    setShowTimePicker(false);
    if (selectedTime) {
      const jam = selectedTime.getHours().toString().padStart(2, '0');
      const menit = selectedTime.getMinutes().toString().padStart(2, '0');
      setWaktu(`${jam}:${menit}`);
    }
  };

  const formatDate = (date: Date) => {
    // Format: YYYY-MM-DD
    const year = date.getFullYear();
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const day = date.getDate().toString().padStart(2, '0');
    return `${year}-${month}-${day}`;
  };

  const { data } = useLocalSearchParams();

  const pickFromGallery = async () => {
    const result = await ImagePicker.launchImageLibraryAsync({
      mediaTypes: ImagePicker.MediaTypeOptions.All,
      allowsMultipleSelection: true,
      quality: 1,
    });
    if (!result.canceled) {
      setMedia([...media, ...result.assets.map(a => ({ uri: a.uri, type: a.type || 'image' }))]);
    }
  };

  const pickFromCamera = async () => {
    const result = await ImagePicker.launchCameraAsync({
      mediaTypes: ImagePicker.MediaTypeOptions.All,
      quality: 1,
    });
    if (!result.canceled) {
      setMedia([...media, { uri: result.assets[0].uri, type: result.assets[0].type || 'image' }]);
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
      const alamatGabungan = `${dummyAddress}|||${latitude},${longitude}`;
      setAlamat(alamatGabungan);
      
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
      media.forEach((m, idx) => {
        formData.append('media[]', {
          uri: m.uri,
          name: `bukti${idx}.${m.type === 'image' ? 'jpg' : 'mp4'}`,
          type: m.type === 'image' ? 'image/jpeg' : 'video/mp4',
        } as any);
      });
    }

    try {
      const response = await axios.post(`${API_URL}/pengaduans`, formData, {
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

      // Fallback jika geo tidak ada
      let alamatTeks = geo
        ? [geo.street, geo.name, geo.subregion, geo.region, geo.postalCode, geo.country].filter(Boolean).join(', ')
        : `Lokasi: ${location.coords.latitude},${location.coords.longitude}`;
      const alamatGabungan = `${alamatTeks}|||${location.coords.latitude},${location.coords.longitude}`;
      setAlamat(alamatGabungan);
      setKecamatan(geo && geo.subregion ? geo.subregion : '');
      setDesa(geo && geo.name ? geo.name : '');

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

      // Fallback jika geo tidak ada
      let alamatTeks = geo
        ? [geo.street, geo.name, geo.subregion, geo.region].filter(Boolean).join(', ')
        : `Lokasi: ${location.coords.latitude},${location.coords.longitude}`;
      const alamatGabungan = `${alamatTeks}|||${location.coords.latitude},${location.coords.longitude}`;
      setAlamat(alamatGabungan);
      setKecamatan(geo && geo.subregion ? geo.subregion : '');
      setDesa(geo && geo.name ? geo.name : '');

      setLinkLokasi(`https://maps.google.com/?q=${location.coords.latitude},${location.coords.longitude}`);

      Alert.alert('Link Lokasi Dilampirkan', 'Alamat dan link lokasi berhasil diisi.');
    } catch (error) {
      Alert.alert('Gagal', 'Tidak bisa mendapatkan lokasi.');
    }
  };

  useFocusEffect(
    React.useCallback(() => {
      // Reset semua state ke default
      const fetchUserAndReset = async () => {
        // Fetch userId dari AsyncStorage
        const userId = await AsyncStorage.getItem('userId');
        let nama = '';
        if (userId) {
          try {
            const response = await fetch(`${API_URL}/user/${userId}`);
            const result = await response.json();
            if (result.success && result.data) {
              nama = result.data.nama_user || '';
            }
          } catch (e) {
            nama = '';
          }
        }
        setNamaLengkap(nama);
        setUserId(userId || '');
        setTanggal(new Date());
        // Set waktu saat ini (jam:menit)
        const now = new Date();
        const jam = now.getHours().toString().padStart(2, '0');
        const menit = now.getMinutes().toString().padStart(2, '0');
        setWaktu(`${jam}:${menit}`);
        setJenisBencana('');
        setCustomJenisBencana('');
        setKecamatan('Pilih Kecamatan');
        setDesa('Pilih Desa');
        setAlamat('');
        setMedia([]);
        setDeskripsi('');
        setLinkLokasi('');
      };
      fetchUserAndReset();
    }, [])
  );

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
              maximumDate={new Date()}
              minimumDate={(() => { let d = new Date(); d.setDate(d.getDate() - 13); return d; })()}
            />
          )}

          <Text style={styles.label}>Waktu Kejadian</Text>
          <TouchableOpacity onPress={() => setShowTimePicker(true)} style={styles.input}>
            <Text>{waktu}</Text>
          </TouchableOpacity>
          {showTimePicker && (
            <DateTimePicker
              value={(() => { let now = new Date(); const [h, m] = waktu.split(":"); now.setHours(Number(h)); now.setMinutes(Number(m)); return now; })()}
              mode="time"
              display={Platform.OS === 'ios' ? 'spinner' : 'default'}
              onChange={handleTimeChange}
            />
          )}

          {/* Memanggil komponen JenisBencanaPicker */}
          <JenisBencanaPicker
            jenisBencana={jenisBencana}
            setJenisBencana={setJenisBencana}
            customJenisBencana={customJenisBencana}
            setCustomJenisBencana={setCustomJenisBencana}
          />

          {/* Input Kecamatan dan Desa diganti dari dropdown menjadi TextInput */}
          <Text style={styles.label}>Kecamatan</Text>
          <TextInput
            style={styles.input}
            placeholder="Masukkan Kecamatan"
            value={kecamatan}
            onChangeText={setKecamatan}
          />
          <Text style={styles.label}>Desa</Text>
          <TextInput
            style={styles.input}
            placeholder="Masukkan Desa"
            value={desa}
            onChangeText={setDesa}
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
              <Text style={{ color: 'white', textAlign: 'center' }}>Isi Otomatis Lokasi</Text>
            </TouchableOpacity>
            <TouchableOpacity
              style={[styles.button, { backgroundColor: '#43A047', flex: 1, marginLeft: 5 }]}
              onPress={lampirkanLinkLokasi}
            >
              <Text style={{ color: 'white', textAlign: 'center' }}>Lampirkan Link Lokasi</Text>
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
          {media.map((m, idx) =>
            m.type === 'image' ? (
              <Image key={idx} source={{ uri: m.uri }} style={styles.previewMedia} />
            ) : (
              <Video key={idx} source={{ uri: m.uri }} style={styles.previewMedia} useNativeControls resizeMode={ResizeMode.CONTAIN} />
            )
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