// ================= STORES (TOKO) =================
const stores = {
  "office-tech": {
    name: "Office Tech",
    active: "Aktif 5 menit lalu",
    followers: "12,4RB",
    rating: "4.9 (3.210 Penilaian)",
    joined: "18 Bulan Lalu",
    following: 2,
    chatPerf: "99% (Hitungan Menit)"
  },
  "printindo": {
    name: "PrintIndo",
    active: "Aktif 12 menit lalu",
    followers: "8,9RB",
    rating: "4.8 (2.100 Penilaian)",
    joined: "24 Bulan Lalu",
    following: 1,
    chatPerf: "98% (Hitungan Menit)"
  },
  "networkpro": {
    name: "NetworkPro",
    active: "Aktif hari ini",
    followers: "6,2RB",
    rating: "4.7 (1.540 Penilaian)",
    joined: "30 Bulan Lalu",
    following: 0,
    chatPerf: "97% (Hitungan Menit)"
  }
};

// ================= PRODUCTS (ELEKTRONIK KANTOR) =================
const products = [
  // ===== OFFICE TECH =====
  {
    id: 1,
    name: "Laptop Kantor Lenovo ThinkPad",
    price: 12500000,
    storeId: "office-tech",
    img: "https://picsum.photos/400?office1",
    desc: "Laptop bisnis handal untuk kerja kantor, multitasking, dan meeting online."
  },
  {
    id: 2,
    name: "Monitor LED 24 Inch Full HD",
    price: 2350000,
    storeId: "office-tech",
    img: "https://picsum.photos/400?office2",
    desc: "Monitor kantor Full HD dengan panel IPS, nyaman untuk kerja seharian."
  },
  {
    id: 3,
    name: "Keyboard & Mouse Wireless Kantor",
    price: 275000,
    storeId: "office-tech",
    img: "https://picsum.photos/400?office3",
    desc: "Keyboard + mouse wireless, meja kerja jadi rapi dan nyaman dipakai."
  },

  // ✅ ini yang tampil di beranda kamu: Mouse Wireless
  {
    id: 4,
    name: "Mouse Wireless",
    price: 150000,
    storeId: "office-tech",
    img: "https://picsum.photos/400?office4",
    desc: "Mouse wireless ergonomis, responsif dan nyaman untuk kerja kantor."
  },

  // ✅ ini yang tampil di beranda kamu: Headset Gaming (kita jadikan headset kantor)
  {
    id: 5,
    name: "Headset Gaming",
    price: 450000,
    storeId: "office-tech",
    img: "https://picsum.photos/400?office5",
    desc: "Headset dengan mikrofon jernih untuk meeting online dan komunikasi tim."
  },

  // ===== PRINTINDO =====
  {
    id: 6,
    name: "Printer Laser Monokrom",
    price: 1850000,
    storeId: "printindo",
    img: "https://picsum.photos/400?office6",
    desc: "Printer laser cepat dan hemat toner, cocok untuk dokumen kantor."
  },
  {
    id: 7,
    name: "Printer Inkjet All in One",
    price: 2250000,
    storeId: "printindo",
    img: "https://picsum.photos/400?office7",
    desc: "Printer multifungsi: print, scan, copy untuk operasional kantor."
  },
  {
    id: 8,
    name: "Tinta Printer Original",
    price: 120000,
    storeId: "printindo",
    img: "https://picsum.photos/400?office8",
    desc: "Tinta printer untuk hasil cetak tajam dan awet, cocok untuk kebutuhan kantor."
  },

  // ===== NETWORKPRO =====
  {
    id: 9,
    name: "Router WiFi Kantor",
    price: 650000,
    storeId: "networkpro",
    img: "https://picsum.photos/400?office9",
    desc: "Router stabil untuk internet kantor dan jaringan internal."
  },
  {
    id: 10,
    name: "Switch Jaringan 8 Port",
    price: 520000,
    storeId: "networkpro",
    img: "https://picsum.photos/400?office10",
    desc: "Switch LAN 8 port untuk distribusi koneksi di kantor."
  },
  {
    id: 11,
    name: "Kabel LAN Cat6 10 Meter",
    price: 65000,
    storeId: "networkpro",
    img: "https://picsum.photos/400?office11",
    desc: "Kabel LAN Cat6 untuk koneksi cepat dan stabil di lingkungan kantor."
  }
];

// ================= FORMAT RUPIAH =================
function formatRupiah(n){
  return "Rp" + n.toLocaleString("id-ID");
}
