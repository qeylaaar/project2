import { View, Text, TextInput, TouchableOpacity, StyleSheet, Image, Alert } from 'react-native';
import React, { useState } from 'react';
import { StatusBar } from 'expo-status-bar';
import { useRouter, Link } from 'expo-router';
import { register } from '../api/auth';

export default function RegisterScreen() {
    const router = useRouter();

    const [fullName, setFullName] = useState('');
    const [email, setEmail] = useState('');
    const [phone, setPhone] = useState('');
    const [password, setPassword] = useState('');
    const [confirmPassword, setConfirmPassword] = useState('');
    const [passwordVisible, setPasswordVisible] = useState(false);
    const [isLoading, setIsLoading] = useState(false);

    const togglePasswordVisibility = () => {
        setPasswordVisible(!passwordVisible);
    };

    const isValidEmail = (email: string): boolean => {
        return /\S+@\S+\.\S+/.test(email);
    };

    const handleRegister = async () => {
        if (!fullName || !email || !phone || !password || !confirmPassword) {
            Alert.alert('Peringatan', 'Semua field wajib diisi!');
            return;
        }

        if (!isValidEmail(email)) {
            Alert.alert('Error', 'Format email tidak valid!');
            return;
        }

        if (password !== confirmPassword) {
            Alert.alert('Error', 'Password dan Konfirmasi tidak cocok!');
            return;
        }

        setIsLoading(true);

        try {
            const response = await register(fullName, phone, email, password);

            if (response.success) {
                Alert.alert(
                    'Sukses',
                    'Registrasi berhasil! Silakan login untuk melanjutkan.',
                    [
                        {
                            text: 'OK',
                            onPress: () => {
                                // Reset form
                                setFullName('');
                                setEmail('');
                                setPhone('');
                                setPassword('');
                                setConfirmPassword('');
                                // Redirect ke LoginScreen
                                router.replace('/LoginScreen');
                            },
                        },
                    ]
                );
            } else {
                Alert.alert('Gagal', response.error || 'Registrasi gagal.');
            }
        } catch (error) {
            console.error('Register error:', error);
            Alert.alert('Error', 'Terjadi kesalahan saat registrasi. Silakan coba lagi.');
        } finally {
            setIsLoading(false);
        }
    };

    return (
        <View style={styles.container}>
            <StatusBar style="light" />
            <View style={styles.topShape} />
            <View style={styles.bottomShape} />
            <View style={styles.overlay}>
                <Image source={require('@/assets/images/LogoBPBD.jpg')} style={styles.logo} />
                <Text style={styles.registerText}>Buat Akun Untuk Melapor</Text>

                <View style={styles.inputWrapper}>
                    <Text style={styles.label}>Nama Lengkap</Text>
                    <TextInput
                        value={fullName}
                        onChangeText={setFullName}
                        placeholder="Masukan Nama Lengkap"
                        placeholderTextColor="#aaa"
                        style={styles.input}
                        editable={!isLoading}
                    />
                    <Text style={styles.label}>Email</Text>
                    <TextInput
                        value={email}
                        onChangeText={setEmail}
                        placeholder="Masukan Email"
                        placeholderTextColor="#aaa"
                        keyboardType="email-address"
                        style={styles.input}
                        autoCapitalize="none"
                        editable={!isLoading}
                    />
                    <Text style={styles.label}>Nomor Telephone</Text>
                    <TextInput
                        value={phone}
                        onChangeText={setPhone}
                        placeholder="Masukan Nomor Telephone"
                        placeholderTextColor="#aaa"
                        keyboardType="phone-pad"
                        style={styles.input}
                        editable={!isLoading}
                    />
                    <Text style={styles.label}>Password</Text>
                    <TextInput
                        value={password}
                        onChangeText={setPassword}
                        placeholder="Masukan Password"
                        placeholderTextColor="#aaa"
                        secureTextEntry={!passwordVisible}
                        style={styles.input}
                        editable={!isLoading}
                    />
                    <TouchableOpacity onPress={togglePasswordVisibility} style={styles.passwordToggle}>
                        <Text style={styles.passwordToggleText}>
                            {passwordVisible ? 'Sembunyikan Password' : 'Tampilkan Password'}
                        </Text>
                    </TouchableOpacity>

                    <Text style={styles.label}>Konfirmasi Password</Text>
                    <TextInput
                        value={confirmPassword}
                        onChangeText={setConfirmPassword}
                        placeholder="Konfirmasi Password"
                        placeholderTextColor="#aaa"
                        secureTextEntry={!passwordVisible}
                        style={styles.input}
                        editable={!isLoading}
                    />
                </View>

                <TouchableOpacity 
                    style={[styles.button, isLoading && styles.buttonDisabled]} 
                    onPress={handleRegister}
                    disabled={isLoading}
                >
                    <Text style={styles.buttonText}>{isLoading ? 'Loading...' : 'Register'}</Text>
                </TouchableOpacity>

                <Text style={styles.loginText}>
                    Sudah punya akun?{' '}
                    <Link href={'LoginScreen' as any} style={styles.loginLink}>
                        Login
                    </Link>
                </Text>
            </View>
        </View>
    );
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
        backgroundColor: '#FFF1E1',
    },
    overlay: {
        width: '90%',
        alignItems: 'center',
        backgroundColor: '#fff',
        padding: 20,
        borderRadius: 12,
    },
    logo: {
        width: 120,
        height: 120,
        marginBottom: 10,
    },
    registerText: {
        color: '#D2601A',
        fontWeight: 'bold',
        fontSize: 20,
        marginBottom: 15,
    },
    label: {
        fontSize: 14,
        color: '#888',
        marginBottom: 5,
    },
    inputWrapper: {
        width: '100%',
        marginBottom: 15,
    },
    input: {
        width: '100%',
        backgroundColor: '#f5f5f5',
        padding: 14,
        borderRadius: 8,
        fontSize: 16,
        color: '#333',
        borderWidth: 1,
        borderColor: '#D2601A',
        marginBottom: 10,
    },
    button: {
        width: '100%',
        backgroundColor: '#D2601A',
        padding: 14,
        borderRadius: 8,
        alignItems: 'center',
    },
    buttonDisabled: {
        backgroundColor: '#D2601A80',
    },
    buttonText: {
        color: 'white',
        fontSize: 18,
        fontWeight: 'bold',
    },
    loginText: {
        marginTop: 10,
        color: '#666',
    },
    loginLink: {
        color: '#D2601A',
        fontWeight: 'bold',
    },
    topShape: {
        position: 'absolute',
        width: '100%',
        height: '30%',
        backgroundColor: '#D2601A',
        borderBottomLeftRadius: 100,
        borderBottomRightRadius: 100,
        top: 0,
    },
    bottomShape: {
        position: 'absolute',
        width: '100%',
        height: '30%',
        backgroundColor: '#D2601A',
        borderTopLeftRadius: 100,
        borderTopRightRadius: 100,
        bottom: 0,
    },
    passwordToggle: {
        marginBottom: 10,
    },
    passwordToggleText: {
        color: '#D2601A',
        fontWeight: 'bold',
    },
});