import { Stack } from 'expo-router';

export default function Layout() {
  return (
    <Stack
      screenOptions={{
        headerShown: false,
        animation: 'slide_from_right',
      }}>
      <Stack.Screen name="Login" />
      <Stack.Screen name="Homepage" />
      <Stack.Screen name="HistoryLaporan" />
      <Stack.Screen name="DetailLaporan" />
      <Stack.Screen name="LaporBencana" />
    </Stack>
  );
}
