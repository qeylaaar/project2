import { View, Text, StyleSheet, TouchableOpacity, Image, Alert, ScrollView, StatusBar } from 'react-native';
import React, { useState, useEffect } from 'react';
import { AntDesign, FontAwesome, MaterialIcons } from '@expo/vector-icons'; // Tambahkan MaterialIcons jika diperlukan
import { useRouter } from 'expo-router';
import { useSafeAreaInsets } from 'react-native-safe-area-context'; // Import useSafeAreaInsets
import { Modal } from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { API_URL } from '../api/config';
import * as ImagePicker from 'expo-image-picker';
import { useFocusEffect } from '@react-navigation/native';

export default function ProfileScreen() {
  const router = useRouter();
  const insets = useSafeAreaInsets(); // Dapatkan insets untuk area aman

  const [modalVisible, setModalVisible] = useState(false);
  const [user, setUser] = useState({
    nama_lengkap: '',
    email: '',
    no_hp: '',
    profileImage: null as string | null,
  });
  const [loading, setLoading] = useState(true);

  useFocusEffect(
    React.useCallback(() => {
      fetchUser();
    }, [])
  );

  const fetchUser = async () => {
    try {
      const userId = await AsyncStorage.getItem('userId');
      if (userId) {
        const response = await fetch(`${API_URL}/user/${userId}`);
        const result = await response.json();
        
        if (result.success) {
          setUser({
            nama_lengkap: result.data.nama_user || '',
            email: result.data.email || '',
            no_hp: result.data.no_telepon || '',
            profileImage: result.data.foto_profil || null,
          });
        }
      }
    } catch (error) {
      console.error('Gagal fetch user:', error);
    } finally {
      setLoading(false);
    }
  };

  const handleLogout = async () => {
    try {
      await AsyncStorage.clear(); // Hapus semua data lokal
      router.replace('/LoginScreen');
    } catch (error) {
      console.error('Error logging out:', error);
    }
  };

  const handleLihatRiwayatPress = () => {
    router.push('/HistoryLaporan'); // Navigasi ke halaman Riwayat Laporan
  };

  const pickImage = async () => {
    try {
      const { status } = await ImagePicker.requestMediaLibraryPermissionsAsync();
      
      if (status !== 'granted') {
        Alert.alert('Maaf', 'Kami membutuhkan izin untuk mengakses galeri foto Anda');
        return;
      }

      const result = await ImagePicker.launchImageLibraryAsync({
        mediaTypes: ImagePicker.MediaTypeOptions.Images,
        allowsEditing: true,
        aspect: [1, 1],
        quality: 0.5,
      });

      if (!result.canceled) {
        await uploadImage(result.assets[0].uri);
      }
    } catch (error) {
      console.error('Error picking image:', error);
      Alert.alert('Error', 'Terjadi kesalahan saat memilih foto');
    }
  };

  const uploadImage = async (uri: string) => {
    try {
      const userId = await AsyncStorage.getItem('userId');
      if (!userId) return;

      const formData = new FormData();
      formData.append('foto_profil', {
        uri,
        type: 'image/jpeg',
        name: 'profile.jpg',
      } as any);

      const response = await fetch(`${API_URL}/user/update-photo/${userId}`, {
        method: 'POST',
        body: formData,
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });

      const result = await response.json();
      
      if (result.success) {
        setUser({
          ...user,
          profileImage: result.data.foto_profil,
        });
        Alert.alert('Sukses', 'Foto profil berhasil diperbarui');
      } else {
        Alert.alert('Error', result.message || 'Gagal mengupload foto');
      }
    } catch (error) {
      console.error('Error uploading image:', error);
      Alert.alert('Error', 'Terjadi kesalahan saat mengupload foto');
    }
  };

  if (loading) {
    return (
      <View style={styles.loadingContainer}>
        <Text>Loading...</Text>
      </View>
    );
  }

  return (
    <View style={styles.container}>
      <View style={{ flexDirection: 'row', alignItems: 'center', marginTop: 0, marginBottom: 10, paddingTop: insets.top + 10 }}>
        <TouchableOpacity onPress={() => router.push('/Homepage')}>
          <AntDesign name="arrowleft" size={28} color="#D48442" />
        </TouchableOpacity>
        <Text style={{ fontSize: 20, fontWeight: 'bold', color: '#D48442', marginLeft: 10 }}>Profil</Text>
      </View>
      <View style={styles.profileHeader}>
        <TouchableOpacity onPress={pickImage} style={styles.profileImageContainer}>
          {user.profileImage ? (
            <Image source={{ uri: user.profileImage }} style={styles.profileImage} />
          ) : (
            <View style={styles.profileImagePlaceholder}>
              <MaterialIcons name="person" size={50} color="#D48442" />
            </View>
          )}
          <View style={styles.editIconContainer}>
            <MaterialIcons name="edit" size={20} color="white" />
          </View>
        </TouchableOpacity>
        <Text style={styles.userName}>{user.nama_lengkap}</Text>
        <Text style={styles.userEmail}>{user.email}</Text>
      </View>

      <View style={styles.infoContainer}>
        <View style={styles.infoItem}>
          <MaterialIcons name="phone" size={24} color="#D48442" />
          <Text style={styles.infoText}>{user.no_hp}</Text>
        </View>
      </View>

      <TouchableOpacity style={styles.logoutButton} onPress={handleLogout}>
        <Text style={styles.logoutButtonText}>Logout</Text>
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#FFF8F0',
    padding: 20,
  },
  loadingContainer: {
    flex: 1,
    backgroundColor: '#FFF8F0',
    justifyContent: 'center',
    alignItems: 'center',
  },
  profileHeader: {
    alignItems: 'center',
    marginTop: 20,
  },
  profileImageContainer: {
    position: 'relative',
    marginBottom: 15,
  },
  profileImage: {
    width: 120,
    height: 120,
    borderRadius: 60,
  },
  profileImagePlaceholder: {
    width: 120,
    height: 120,
    borderRadius: 60,
    backgroundColor: '#FFF',
    justifyContent: 'center',
    alignItems: 'center',
    borderWidth: 2,
    borderColor: '#D48442',
  },
  editIconContainer: {
    position: 'absolute',
    bottom: 0,
    right: 0,
    backgroundColor: '#D48442',
    borderRadius: 15,
    width: 30,
    height: 30,
    justifyContent: 'center',
    alignItems: 'center',
    borderWidth: 2,
    borderColor: '#FFF',
  },
  userName: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 5,
  },
  userEmail: {
    fontSize: 16,
    color: '#666',
    marginBottom: 20,
  },
  infoContainer: {
    backgroundColor: '#FFF',
    borderRadius: 15,
    padding: 20,
    marginTop: 20,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.1,
    shadowRadius: 3.84,
    elevation: 5,
  },
  infoItem: {
    flexDirection: 'row',
    alignItems: 'center',
    marginBottom: 15,
  },
  infoText: {
    marginLeft: 15,
    fontSize: 16,
    color: '#333',
  },
  logoutButton: {
    backgroundColor: '#D48442',
    padding: 15,
    borderRadius: 10,
    alignItems: 'center',
    marginTop: 30,
  },
  logoutButtonText: {
    color: '#FFF',
    fontSize: 16,
    fontWeight: 'bold',
  },
});