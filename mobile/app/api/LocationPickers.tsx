import React from 'react';
import { View, Text, StyleSheet } from 'react-native';
import { Picker } from '@react-native-picker/picker';

interface LocationPickersProps {
  kecamatan: string;
  setKecamatan: (value: string) => void;
  desa: string;
  setDesa: (value: string) => void;
}

const LocationPickers: React.FC<LocationPickersProps> = ({
  kecamatan,
  setKecamatan,
  desa,
  setDesa,
}) => {
  const kecamatanList = [
    'Pilih Kecamatan',
    'Binong', 'Blanakan', 'Ciasem', 'Ciater', 'Cibogo', 'Cijambe', 'Cikaum',
    'Cipeundeuy', 'Cipunagara', 'Cisalak', 'Compreng', 'Dawuan', 'Jalancagak',
    'Kalijati', 'Kasomalang', 'Kota Subang', 'Legonkulon', 'Pabuaran', 'Pagaden',
    'Pagaden Barat', 'Pamanukan', 'Patokbeusi', 'Purwadadi', 'Pusakanagara',
    'Pusakajaya', 'Sagalaherang', 'Serangpanjang', 'Sukasari', 'Tambakdahan', 'Tanjungsiang',
  ];

  const desaList = {
    'Pilih Kecamatan': ['Pilih Desa'],
    'Binong': ['Cicadas', 'Cipendeuy', 'Karangwangi', 'Nanggerang', 'Kediri'],
    'Blanakan': ['Blanakan', 'Cilamaya Girang', 'Cilamaya Hilir', 'Jayamukti', 'Langensari'],
    'Ciasem': ['Ciasem Girang', 'Ciasem Hilir', 'Ciasem Baru', 'Sukamandijaya', 'Dukuh'],
    'Ciater': ['Ciater', 'Palasari', 'Cibeusi', 'Nagrak', 'Sariater'],
    'Cibogo': ['Cibogo', 'Cimanggu', 'Majasari', 'Sukamaju', 'Padamulya'],
    'Cijambe': ['Cijambe', 'Cikadu', 'Cipancar', 'Jati', 'Tanjungwangi'],
    'Cikaum': ['Cikaum Timur', 'Cikaum Barat', 'Pasirbungur', 'Mekarsari', 'Tanjungsari Barat', 'Kawunganten'],
    'Cipeundeuy': ['Cipeundeuy', 'Kosar', 'Wantilan', 'Karangmukti', 'Lengkong'],
    'Cipunagara': ['Cipunagara', 'Manyingsal', 'Sidamulya', 'Tanjung', 'Wanasari'],
    'Cisalak': ['Cisalak', 'Cigadog', 'Darmaga', 'Gardusayang', 'Mayang'],
    'Compreng': ['Compreng', 'Jatireja', 'Kiarajaya', 'Sukadana', 'Jatimulya'],
    'Dawuan': ['Dawuan Kaler', 'Dawuan Kidul', 'Manyeti', 'Rawalele', 'Bojongkeding'],
    'Jalancagak': ['Jalancagak', 'Sukamulya', 'Tambakan', 'Sarireja', 'Cibeureum'],
    'Kalijati': ['Kalijati Timur', 'Kalijati Barat', 'Manyingsal', 'Mulyasari', 'Marengmang'],
    'Kasomalang': ['Kasomalang Kulon', 'Kasomalang Wetan', 'Cikawung', 'Pasanggrahan', 'Sukamenak'],
    'Kota Subang': ['Pasirkareumbi', 'Karanganyar', 'Sukamelang', 'Dangdeur', 'Cigadung'],
    'Legonkulon': ['Legonkulon', 'Bobos', 'Mayangan', 'Pangarengan', 'Tegalurung'],
    'Pabuaran': ['Pabuaran', 'Cihambulu', 'Pringkasap', 'Sukamaju', 'Salamjaya'],
    'Pagaden': ['Pagaden', 'Gempol', 'Gunungtua', 'Jati', 'Sukajaya'],
    'Pagaden Barat': ['Pagaden Barat', 'Cidadap', 'Cipunagara', 'Majasari', 'Munjul'],
    'Pamanukan': ['Pamanukan', 'Pamanukan Hilir', 'Pamanukan Sebrang', 'Mulyasari', 'Rancahilir'],
    'Patokbeusi': ['Patokbeusi', 'Ciberes', 'Cipendeuy', 'Gempolsari', 'Rancabango'],
    'Purwadadi': ['Purwadadi', 'Pagon', 'Pasar Cipeundeuy', 'Wanakerta', 'Panyingkiran'],
    'Pusakajaya': ['Pusakajaya', 'Ciasem Baru', 'Ciasem Tengah', 'Ciasem Udik', 'Sukajadi'],
    'Pusakanagara': ['Pusakanagara', 'Pusakaratu', 'Rancadaka', 'Kotakaler', 'Kebonsari'],
    'Sagalaherang': ['Sagalaherang', 'Dayeuhkolot', 'Curugagung', 'Cicadas', 'Cikujang'],
    'Serangpanjang': ['Serangpanjang', 'Cijengkol', 'Cikujang', 'Sukamulya', 'Cipeundeuy'],
    'Sukasari': ['Sukasari', 'Mekarwangi', 'Panyingkiran', 'Sukajaya', 'Tanjungrasa'],
    'Tambakdahan': ['Tambakdahan', 'Mariuk', 'Mekarsari', 'Kertajaya', 'Sukajadi'],
    'Tanjungsiang': ['Tanjungsiang', 'Cikawung', 'Cibuluh', 'Cimeuhmal', 'Cipanas'],
  };

  return (
    <View>
      <Text style={styles.label}>Kecamatan</Text>
      <View style={styles.pickerContainer}>
        <Picker
          selectedValue={kecamatan}
          onValueChange={(itemValue) => {
            setKecamatan(itemValue);
            setDesa('Pilih Desa'); // Reset desa ketika kecamatan berubah
          }}
          style={styles.picker}
        >
          <Picker.Item label="Pilih Kecamatan" value="Pilih Kecamatan" />
          {kecamatanList.map((item) => (
            <Picker.Item key={item} label={item} value={item} />
          ))}
        </Picker>
      </View>

      <Text style={styles.label}>Desa</Text>
      <View style={styles.pickerContainer}>
        <Picker
          selectedValue={desa}
          onValueChange={setDesa}
          style={styles.picker}
          enabled={kecamatan !== 'Pilih Kecamatan'}
        >
          <Picker.Item label="Pilih Desa" value="Pilih Desa" />
          {kecamatan !== 'Pilih Kecamatan' &&
            desaList[kecamatan as keyof typeof desaList]?.map((item) => (
              <Picker.Item key={item} label={item} value={item} />
            ))}
        </Picker>
      </View>
    </View>
  );
};

const styles = StyleSheet.create({
  label: {
    fontSize: 14,
    fontWeight: 'bold',
    color: '#333',
    marginTop: 10,
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
});

export default LocationPickers;