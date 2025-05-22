import { View, Text, TouchableOpacity, StyleSheet, Image, Alert } from 'react-native';
import React, { useEffect, useState } from 'react';
import { StatusBar } from 'expo-status-bar';
import { AntDesign, FontAwesome } from '@expo/vector-icons';
import { useNavigation } from '@react-navigation/native';
import { useRouter } from 'expo-router';
import { Modal } from 'react-native';
import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { API_URL } from '../api/config';

interface User {
    id_user: number;
    nama_user: string;
    email: string;
    no_telepon: string;
    role: string;
}

export default function ProfileScreen() {
    const navigation = useNavigation();
    const [modalVisible, setModalVisible] = useState(false);
    const [user, setUser] = useState<User | null>(null);
    const router = useRouter();

    useEffect(() => {
        fetchUserData();
    }, []);

    const fetchUserData = async () => {
        try {
            const userId = await AsyncStorage.getItem('userId');
            console.log('User ID:', userId); // Debug log

            if (userId) {
                const response = await axios.get(`${API_URL}/user/${userId}`);
                console.log('API Response:', response.data); // Debug log
                
                if (response.data.success) {
                    setUser(response.data.data);
                } else {
                    console.log('API Error:', response.data.message); // Debug log
                    Alert.alert('Error', response.data.message || 'Gagal mengambil data pengguna');
                }
            } else {
                console.log('No user ID found'); // Debug log
                Alert.alert('Error', 'ID pengguna tidak ditemukan');
                router.replace('/LoginScreen');
            }
        } catch (error) {
            console.error('Error fetching user data:', error);
            Alert.alert('Error', 'Gagal mengambil data pengguna');
            router.replace('/LoginScreen');
        }
    };

    const handleLogout = () => {
        Alert.alert(
            "Konfirmasi Logout",
            "Apakah Anda yakin ingin logout?",
            [
                { text: "Batal", style: "cancel" },
                {
                    text: "Logout",
                    style: "destructive",
                    onPress: async () => {
                        await AsyncStorage.removeItem('userId');
                        await AsyncStorage.removeItem('token');
                        router.replace('/LoginScreen');
                    }
                }
            ]
        );
    };

    if (!user) {
        return (
            <View style={styles.container}>
                <Text>Loading...</Text>
            </View>
        );
    }

    return (
        <View style={styles.container}>
            <StatusBar style="light" />

            <View style={styles.topShape}>
                <TouchableOpacity style={styles.backButton} onPress={() => router.push('/Homepage')}>
                    <AntDesign name="arrowleft" size={24} color="white" />
                </TouchableOpacity>
            </View>

            <View style={styles.topShape}>
                <Text style={styles.profileText}>Profile</Text>
            </View>

            <TouchableOpacity onPress={() => setModalVisible(true)}>
                <Image source={require('@/assets/images/IMG_1259.jpg')} style={styles.profileImage} />
            </TouchableOpacity>

            <View style={styles.infoContainer}>
                <View style={styles.infoItem}>
                    <AntDesign name="user" size={20} color="#333" />
                    <Text style={styles.infoText}>{user.nama_user}</Text>
                </View>
                <View style={styles.infoItem}>
                    <FontAwesome name="at" size={20} color="#333" />
                    <Text style={styles.infoText}>{user.email}</Text>
                </View>
                <View style={styles.infoItem}>
                    <FontAwesome name="phone" size={20} color="#333" />
                    <Text style={styles.infoText}>{user.no_telepon}</Text>
                </View>

                <View style={styles.infoItem}>
                    <FontAwesome name="history" size={20} color="#333" />
                    <TouchableOpacity onPress={() => router.push('/HistoryLaporan')}>
                        <Text style={styles.infoText}>Lihat Riwayat Laporan</Text>
                    </TouchableOpacity>
                </View>

                <TouchableOpacity style={styles.logoutButton} onPress={handleLogout}>
                    <FontAwesome name="sign-out" size={20} color="red" />
                    <Text style={styles.logoutText}>Logout</Text>
                </TouchableOpacity>
            </View>

            <Modal visible={modalVisible} transparent={true} animationType="fade">
                <View style={styles.modalBackground}>
                    <TouchableOpacity style={styles.modalContainer} onPress={() => setModalVisible(false)}>
                        <Image
                            source={require('@/assets/images/IMG_1259.jpg')}
                            style={styles.fullImage}
                            resizeMode="contain"
                        />
                    </TouchableOpacity>
                </View>
            </Modal>
        </View>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        alignItems: 'center',
        backgroundColor: '#FFF1E1',
    },
    topShape: {
        position: 'absolute',
        width: '100%',
        height: '30%',
        backgroundColor: '#D2601A',
        borderBottomLeftRadius: 100,
        borderBottomRightRadius: 100,
        alignItems: 'center',
        justifyContent: 'center',
        top: 0,
    },
    profileText: {
        color: 'white',
        fontSize: 20,
        fontWeight: 'bold',
        marginTop: 50,
    },
    profileContainer: {
        marginTop: '35%', 
        alignItems: 'center',
    },
    profileImage: {
        width: 100,
        height: 100,
        borderRadius: 55,
        backgroundColor: 'white',
    },
    infoContainer: {
        width: '90%',
        backgroundColor: 'white',
        padding: 20,
        borderRadius: 12,
        marginTop: 20,
        elevation: 5,
    },
    backButton: {
        position: 'absolute',
        left: 20,
        top: 40,
        zIndex: 1,
    },
    infoItem: {
        flexDirection: 'row',
        alignItems: 'center',
        paddingVertical: 10,
        borderBottomWidth: 1,
        borderBottomColor: '#ddd',
    },
    infoText: {
        fontSize: 16,
        color: '#333',
        marginLeft: 10,
    },
    logoutButton: {
        flexDirection: 'row',
        alignItems: 'center',
        paddingVertical: 10,
        marginTop: 10,
    },
    logoutText: {
        fontSize: 16,
        color: 'red',
        marginLeft: 10,
    },
    modalBackground: {
        flex: 1,
        backgroundColor: 'rgba(0,0,0,0.8)',
        justifyContent: 'center',
        alignItems: 'center',
    },
    modalContainer: {
        width: '100%',
        height: '100%',
        justifyContent: 'center',
        alignItems: 'center',
    },
    fullImage: {
        width: '90%',
        height: '80%',
        borderRadius: 12,
    },
});
