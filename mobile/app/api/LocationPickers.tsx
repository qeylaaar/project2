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
    'Binong',
    'Blanakan',
    'Ciasem',
    'Cibogo',
    'Cijambe',
    'Cikaum',
    'Cipeundeuy',
    'Cipunagara',
    'Cisalak',
    'Compreng',
    'Dawuan',
    'Jalancagak',
    'Kalijati',
    'Kasomalang',
    'Legonkulon',
    'Pabuaran',
    'Pagaden',
    'Pagaden Barat',
    'Pamanukan',
    'Patokbeusi',
    'Purwadadi',
    'Pusakajaya',
    'Pusakanagara',
    'Sagalaherang',
    'Serangpanjang',
    'Subang',
    'Sukasari',
    'Tambakdahan',
    'Tanjungsiang',
  ];

  const desaList = {
    'Binong': ['Binong', 'Cicadas', 'Citrajaya', 'Karangwangi', 'Mekarsari'],
    'Blanakan': ['Blanakan', 'Cilamaya Girang', 'Cilamaya Hilir', 'Jayamukti', 'Rawamekar'],
    // Tambahkan desa untuk kecamatan lainnya sesuai kebutuhan
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