import { View, Text, StyleSheet, TouchableOpacity, ScrollView, Image, Dimensions } from 'react-native';
import React, { useState } from 'react';
import { useLocalSearchParams, useRouter } from 'expo-router';
import { AntDesign } from '@expo/vector-icons';
import { Video, ResizeMode } from 'expo-av';
import Swiper from 'react-native-swiper';
import ImageViewing from 'react-native-image-viewing';
import { API_URL } from '../api/config';

const BASE_URL = API_URL.replace('/api', '');

export default function DetailLaporan() {
  const router = useRouter();
  const params = useLocalSearchParams();
  const laporan = JSON.parse(decodeURIComponent(params.data as string));
  const [imageError, setImageError] = useState(false);

  const [visiblePenanganan, setVisiblePenanganan] = useState(false);
  const [zoomIndexPenanganan, setZoomIndexPenanganan] = useState(0);

  const [visibleUser, setVisibleUser] = useState(false);
  const [zoomIndexUser, setZoomIndexUser] = useState(0);

  const buktiArray = Array.isArray(laporan.bukti) ? laporan.bukti : (laporan.bukti ? [laporan.bukti] : []);

  const imageBukti = buktiArray
    .map((b: string) => `${BASE_URL}/storage/${b}`)
    .filter((uri: string) => uri.match(/\.(jpg|jpeg|png|gif)$/i));

  let imageIdx = 0;

  // Ambil array media user dari laporan, pastikan parsing JSON jika perlu
  let mediaArray: string[] = [];
  if (Array.isArray(laporan.media_uri)) {
    mediaArray = laporan.media_uri;
  } else if (typeof laporan.media_uri === 'string') {
    try {
      mediaArray = JSON.parse(laporan.media_uri);
    } catch {
      mediaArray = laporan.media_uri ? [laporan.media_uri] : [];
    }
  }

  // Array khusus gambar dari media user untuk fitur zoom
  const imageMediaUser = mediaArray
    .map((m: string) => (m.startsWith('http') ? m : `${BASE_URL}/${m.replace(/^\//, '')}`))
    .filter((uri: string) => uri.match(/\.(jpg|jpeg|png|gif)$/i));

  const getStatusColor = (status: string) => {
    switch (status) {
      case 'Menunggu':
        return '#FFA500';
      case 'Proses':
        return '#007AFF';
      case 'Selesai':
        return '#4CAF50';
      default:
        return '#666666';
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

  const renderMedia = () => {
    if (!laporan.media_uri) return null;

    console.log('Media URI:', laporan.media_uri);

    if (laporan.media_type === 'video') {
      return (
        <Video
          source={{ uri: laporan.media_uri }}
          style={styles.mediaVideo}
          useNativeControls
          resizeMode={ResizeMode.CONTAIN}
          isLooping={false}
        />
      );
    } else {
      return (
        <Image 
          source={{ 
            uri: imageError 
              ? `${BASE_URL}/uploads/${laporan.media_uri.split('/').pop()}`
              : laporan.media_uri 
          }}
          style={styles.mediaImage}
          resizeMode="cover"
          onError={() => setImageError(true)}
        />
      );
    }
  };

  return (
    <View style={styles.container}>
      <View style={styles.header}>
        <TouchableOpacity
          style={styles.backButton}
          onPress={() => router.push('/HistoryLaporan')}>
          <AntDesign name="arrowleft" size={24} color="white" />
        </TouchableOpacity>
        <Text style={styles.headerTitle}>Detail Laporan</Text>
      </View>

      <ScrollView style={styles.content}>
        <View style={styles.card}>
          <View style={[styles.statusBadge, { backgroundColor: getStatusColor(laporan.status) }]}>
            <Text style={styles.statusText}>{laporan.status}</Text>
          </View>

          <Text style={styles.title}>{laporan.jenis_pengaduan}</Text>
          
          <View style={styles.infoSection}>
            <Text style={styles.infoLabel}>Waktu Kejadian:</Text>
            <Text style={styles.infoValue}>{laporan.waktu}, {formatDate(laporan.tanggal)}</Text>
          </View>

          <View style={styles.infoSection}>
            <Text style={styles.infoLabel}>Lokasi:</Text>
            <Text style={styles.infoValue}>Desa {laporan.desa}</Text>
            <Text style={styles.infoValue}>Kecamatan {laporan.kecamatan}</Text>
            <Text style={styles.infoValue}>{laporan.alamat}</Text>
          </View>

          <View style={styles.infoSection}>
            <Text style={styles.infoLabel}>Deskripsi:</Text>
            <Text style={styles.infoValue}>{laporan.deskripsi || '-'}</Text>
          </View>

          {laporan.feedback && (
            <View style={styles.infoSection}>
              <Text style={styles.infoLabel}>Feedback dari Admin:</Text>
              <Text style={styles.infoValue}>{laporan.feedback}</Text>
            </View>
          )}

          {buktiArray.length > 0 && (
            <View style={styles.infoSection}>
              <Text style={styles.infoLabel}>Bukti Penanganan:</Text>
              <View style={{ height: 260 }}>
                <Swiper showsPagination={true} loop={false}>
                  {buktiArray.map((bukti: string, idx: number) => {
                    const uri = `${BASE_URL}/storage/${bukti}`;
                    if (bukti.match(/\.(mp4|mov|avi)$/i)) {
                      return (
                        <Video
                          key={bukti}
                          source={{ uri }}
                          style={styles.mediaVideo}
                          useNativeControls
                          resizeMode={ResizeMode.CONTAIN}
                          isLooping={false}
                        />
                      );
                    } else {
                      const currentImageIdx = imageIdx;
                      imageIdx++;
                      return (
                        <TouchableOpacity
                          key={bukti}
                          onPress={() => {
                            setZoomIndexPenanganan(currentImageIdx);
                            setVisiblePenanganan(true);
                          }}>
                          <Image
                            source={{ uri }}
                            style={styles.mediaImage}
                            resizeMode="cover"
                          />
                        </TouchableOpacity>
                      );
                    }
                  })}
                </Swiper>
                <ImageViewing
                  images={imageBukti.map((uri: string) => ({ uri }))}
                  imageIndex={zoomIndexPenanganan}
                  visible={visiblePenanganan}
                  onRequestClose={() => setVisiblePenanganan(false)}
                />
              </View>
            </View>
          )}

          {/* Bukti media user */}
          {mediaArray.length > 0 && (
            <View style={styles.infoSection}>
              <Text style={styles.infoLabel}>Bukti:</Text>
              <View style={{ height: 260 }}>
                <Swiper showsPagination={mediaArray.length > 1} loop={false}>
                  {mediaArray.map((media: string, idx: number) => {
                    // Pastikan path URL benar
                    const uri = media.startsWith('http') ? media : `${BASE_URL}/${media.replace(/^\//, '')}`;
                    if (uri.match(/\.(mp4|mov|avi)$/i)) {
                      return (
                        <Video
                          key={media}
                          source={{ uri }}
                          style={styles.mediaVideo}
                          useNativeControls
                          resizeMode={ResizeMode.CONTAIN}
                          isLooping={false}
                        />
                      );
                    } else {
                      // Untuk gambar, tambahkan fitur zoom
                      return (
                        <TouchableOpacity
                          key={media}
                          onPress={() => {
                            setZoomIndexUser(imageMediaUser.indexOf(uri));
                            setVisibleUser(true);
                          }}>
                          <Image
                            source={{ uri }}
                            style={styles.mediaImage}
                            resizeMode="cover"
                          />
                        </TouchableOpacity>
                      );
                    }
                  })}
                </Swiper>
                <ImageViewing
                  images={imageMediaUser.map((uri: string) => ({ uri }))}
                  imageIndex={zoomIndexUser}
                  visible={visibleUser}
                  onRequestClose={() => setVisibleUser(false)}
                />
              </View>
            </View>
          )}

          <View style={styles.infoSection}>
            <Text style={styles.infoLabel}>Dilaporkan oleh:</Text>
            <Text style={styles.infoValue}>{laporan.nama_pelapor}</Text>
          </View>
        </View>

        <TouchableOpacity
          style={styles.button}
          onPress={() => router.push('/HistoryLaporan')}>
          <Text style={styles.buttonText}>Kembali ke Riwayat Laporan</Text>
        </TouchableOpacity>
      </ScrollView>


    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#FFF1E1',
  },
  header: {
    backgroundColor: '#D2601A',
    paddingTop: 40,
    paddingBottom: 16,
    paddingHorizontal: 16,
    flexDirection: 'row',
    alignItems: 'center',
  },
  headerTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    color: 'white',
    marginLeft: 16,
  },
  backButton: {
    padding: 8,
  },
  content: {
    flex: 1,
    padding: 16,
  },
  card: {
    backgroundColor: 'white',
    borderRadius: 12,
    padding: 16,
    marginBottom: 16,
    elevation: 2,
  },
  statusBadge: {
    alignSelf: 'flex-start',
    paddingHorizontal: 12,
    paddingVertical: 6,
    borderRadius: 16,
    marginBottom: 12,
  },
  statusText: {
    color: 'white',
    fontWeight: 'bold',
    fontSize: 14,
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#333',
    marginBottom: 16,
  },
  infoSection: {
    marginBottom: 16,
  },
  infoLabel: {
    fontSize: 14,
    color: '#666',
    marginBottom: 4,
  },
  infoValue: {
    fontSize: 16,
    color: '#333',
    marginBottom: 2,
  },
  mediaImage: {
    width: '100%',
    height: 250,
    borderRadius: 8,
    marginTop: 8,
  },
  mediaVideo: {
    width: '100%',
    height: 250,
    borderRadius: 8,
    marginTop: 8,
    backgroundColor: '#000',
  },
  button: {
    marginTop: 20,
    backgroundColor: '#D2601A',
    paddingVertical: 12,
    paddingHorizontal: 24,
    borderRadius: 8,
    alignItems: 'center',
  },
  buttonText: {
    color: 'white',
    fontSize: 16,
    fontWeight: 'bold',
  },
}); 