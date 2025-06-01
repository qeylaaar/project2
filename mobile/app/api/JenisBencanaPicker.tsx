import React from 'react';
import { View, Text, TextInput, StyleSheet } from 'react-native';
import { Picker } from '@react-native-picker/picker';

interface JenisBencanaPickerProps {
  jenisBencana: string;
  setJenisBencana: (value: string) => void;
  customJenisBencana: string;
  setCustomJenisBencana: (value: string) => void;
}

// Komponen untuk memilih jenis bencana, termasuk opsi 'Lainnya'
const JenisBencanaPicker: React.FC<JenisBencanaPickerProps> = ({
  jenisBencana,
  setJenisBencana,
  customJenisBencana,
  setCustomJenisBencana,
}) => {
  const jenisBencanaList = [
    'Banjir',
    'Longsor',
    'Kebakaran',
    'Angin Kencang',
    'Gempa Bumi',
    'Lainnya',
  ];

  return (
    <View>
      <Text style={styles.label}>Jenis Bencana</Text>
      <View style={styles.pickerContainer}>
        <Picker
          selectedValue={jenisBencana}
          onValueChange={(itemValue) => setJenisBencana(itemValue)}
          style={styles.picker}
        >
          <Picker.Item label="Pilih Jenis Bencana" value="" />
          {jenisBencanaList.map((item) => (
            <Picker.Item key={item} label={item} value={item} />
          ))}
        </Picker>
      </View>
      {jenisBencana === 'Lainnya' && (
        <TextInput
          style={styles.input}
          placeholder="Masukkan jenis bencana lainnya"
          value={customJenisBencana}
          onChangeText={setCustomJenisBencana}
        />
      )}
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
});

export default JenisBencanaPicker;