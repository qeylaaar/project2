import { View, Text, TextInput, TouchableOpacity, StyleSheet, Image, Alert } from 'react-native';
import React, { useState } from 'react';
import { StatusBar } from 'expo-status-bar';
import { useRouter, Link } from 'expo-router';
import { login } from '../api/auth';
import AsyncStorage from '@react-native-async-storage/async-storage';

export default function Loginscreen() {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [passwordVisible, setPasswordVisible] = useState(false);
    const [error, setError] = useState('');
    const [isLoading, setIsLoading] = useState(false);
    const router = useRouter();

    const handleLogin = async () => {
        if (!email || !password) {
            setError('Email dan Password wajib diisi');
            return;
        }

        setIsLoading(true);
        setError('');

        try {
            console.log('Attempting login with:', { email, password });
            const response = await login(email, password);
            console.log('Login response:', response);
            
            if (response.success) {
                console.log('Login berhasil:', response.data);
                // Reset form
                setEmail('');
                setPassword('');
                // Redirect ke Homepage
                router.replace('/Homepage');
                await AsyncStorage.setItem('user', JSON.stringify(response.data));
            } else {
                console.log('Login failed:', response.error);
                setError(response.error || 'Email atau Password salah');
            }
        } catch (err) {
            console.error('Login error:', err);
            setError('Terjadi kesalahan saat login. Silakan coba lagi.');
        } finally {
            setIsLoading(false);
        }
    };

    const togglePasswordVisibility = () => {
        setPasswordVisible(!passwordVisible);
    };

    return (
        <View style={styles.container}>
            <StatusBar style="light" />
            
            <View style={styles.topShape} />
            <View style={styles.bottomShape} />

            <View style={styles.overlay}>
                <Image source={require('@/assets/images/LogoBPBD.jpg')} style={styles.logo} />
                <Text style={styles.loginText}>LOGIN</Text>

                <View style={styles.inputWrapper}>
                    <Text style={styles.label}>Masukan Email dan Password</Text>
                    <TextInput 
                        placeholder="Email" 
                        placeholderTextColor="#aaa" 
                        style={styles.input}
                        value={email}
                        onChangeText={setEmail}
                        keyboardType="email-address"
                        autoCapitalize="none"
                        editable={!isLoading}
                    />
                    <View style={styles.passwordRow}>
                        <TextInput 
                            placeholder="Password" 
                            placeholderTextColor="#aaa" 
                            secureTextEntry={!passwordVisible}
                            style={styles.inputPassword}
                            value={password}
                            onChangeText={setPassword}
                            editable={!isLoading}
                        />
                        <TouchableOpacity 
                            style={styles.showHideButton} 
                            onPress={togglePasswordVisibility}
                        >
                            <Text style={styles.showHideButtonText}>
                                {passwordVisible ? 'Hide' : 'Show'}
                            </Text>
                        </TouchableOpacity>
                    </View>
                </View>

                {error ? <Text style={styles.errorText}>{error}</Text> : null}

                <TouchableOpacity 
                    style={[styles.button, isLoading && styles.buttonDisabled]} 
                    onPress={handleLogin}
                    disabled={isLoading}
                >
                    <Text style={styles.buttonText}>{isLoading ? 'Loading...' : 'Login'}</Text>
                </TouchableOpacity>

                <Text style={styles.registerText}>
                    Belum Punya Akun?{' '}
                    <Link href={'RegisScreen' as any} style={styles.registerLink}>
                        Daftar
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
        backgroundColor: 'white',
        padding: 27,
        borderRadius: 13,
    },
    logo: {
        width: 120,
        height: 120,
        marginBottom: 20,
    },
    loginText: {
        color: '#D2601A',
        fontWeight: 'bold',
        fontSize: 24,
        marginBottom: 10,
    },
    label: {
        fontSize: 14,
        color: 'grey',
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
        color: 'black',
        borderWidth: 1,
        borderColor: '#D2601A',
        marginBottom: 10,
    },
    passwordRow: {
        flexDirection: 'row',
        alignItems: 'center',
        marginBottom: 10,
    },
    inputPassword: {
        flex: 1,
        backgroundColor: '#f5f5f5',
        padding: 14,
        borderRadius: 8,
        fontSize: 16,
        color: 'black',
        borderWidth: 1,
        borderColor: '#D2601A',
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
    registerText: {
        marginTop: 10,
        color: '#666',
    },
    registerLink: {
        color: '#D2601A',
        fontWeight: 'bold',
    },
    topShape: {
        position: 'absolute',
        width: '100%',
        height: '40%',
        backgroundColor: '#D2601A',
        borderBottomLeftRadius: 100,
        borderBottomRightRadius: 100,
        top: -1,
    },
    bottomShape: {
        position: 'absolute',
        width: '100%',
        height: '40%',
        backgroundColor: '#D2601A',
        borderTopLeftRadius: 100,
        borderTopRightRadius: 100,
        bottom: -1,
    },
    errorText: {
        color: 'red',
        marginBottom: 10,
    },
    showHideButton: {
        paddingHorizontal: 10,
        paddingVertical: 8,
    },
    showHideButtonText: {
        color: '#D2601A',
        fontWeight: 'bold',
        fontSize: 14,
    },
});