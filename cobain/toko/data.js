const stores = {
  "urban-shoes": {
    name: "Urban Shoes",
    active: "Aktif 3 menit lalu",
    followers: "88,9RB",
    rating: "4.9 (210,2RB Penilaian)",
    joined: "23 Bulan Lalu",
    following: 1,
    chatPerf: "100% (Hitungan Menit)"
  },
  "bagstore": {
    name: "BagStore",
    active: "Aktif 10 menit lalu",
    followers: "21,3RB",
    rating: "4.8 (98RB Penilaian)",
    joined: "14 Bulan Lalu",
    following: 2,
    chatPerf: "98% (Hitungan Menit)"
  },
  "runfast": {
    name: "RunFast",
    active: "Aktif 1 jam lalu",
    followers: "54RB",
    rating: "4.7 (75RB Penilaian)",
    joined: "30 Bulan Lalu",
    following: 0,
    chatPerf: "95% (Hitungan Menit)"
  },
  "fashionid": {
    name: "FashionID",
    active: "Aktif hari ini",
    followers: "63RB",
    rating: "4.9 (150RB Penilaian)",
    joined: "9 Bulan Lalu",
    following: 4,
    chatPerf: "99% (Hitungan Menit)"
  },
  "techzone": {
    name: "TechZone",
    active: "Aktif 5 menit lalu",
    followers: "40RB",
    rating: "4.8 (120RB Penilaian)",
    joined: "18 Bulan Lalu",
    following: 3,
    chatPerf: "97% (Hitungan Menit)"
  }
};

const products = [
  {
    id: 1,
    name: "Sepatu Sneakers",
    price: 250000,
    storeId: "urban-shoes",
    img: "https://picsum.photos/400?1",
    desc: "Sepatu sneakers dengan desain modern dan bahan berkualitas. Nyaman digunakan untuk aktivitas harian maupun hangout."
  },
  {
    id: 2,
    name: "Sepatu Running",
    price: 320000,
    storeId: "urban-shoes",
    img: "https://picsum.photos/400?2",
    desc: "Sepatu lari ringan dengan bantalan empuk yang membantu menjaga kenyamanan kaki saat berolahraga."
  },
  {
    id: 3,
    name: "Sepatu Casual",
    price: 280000,
    storeId: "urban-shoes",
    img: "https://picsum.photos/400?3",
    desc: "Sepatu casual simpel dan elegan, cocok untuk dipakai ke kantor maupun acara santai."
  },

  {
    id: 4,
    name: "Tas Ransel",
    price: 180000,
    storeId: "bagstore",
    img: "https://picsum.photos/400?4",
    desc: "Tas ransel multifungsi dengan ruang luas, cocok untuk sekolah, kerja, dan traveling."
  },

  {
    id: 5,
    name: "Tas Selempang",
    price: 120000,
    storeId: "bagstore",
    img: "https://picsum.photos/400?5",
    desc: "Tas selempang ringan dan praktis untuk membawa barang penting sehari-hari."
  },

  {
    id: 6,
    name: "Sepatu Lari Pro",
    price: 350000,
    storeId: "runfast",
    img: "https://picsum.photos/400?6",
    desc: "Sepatu lari profesional dengan grip kuat dan sirkulasi udara optimal."
  },

  {
    id: 7,
    name: "Kaos Olahraga",
    price: 90000,
    storeId: "runfast",
    img: "https://picsum.photos/400?7",
    desc: "Kaos olahraga berbahan adem dan cepat kering, cocok untuk berbagai aktivitas fisik."
  },

  {
    id: 8,
    name: "Jaket Hoodie",
    price: 220000,
    storeId: "fashionid",
    img: "https://picsum.photos/400?8",
    desc: "Jaket hoodie stylish dengan bahan lembut dan hangat, cocok untuk cuaca dingin."
  },

  {
    id: 9,
    name: "Celana Jeans",
    price: 200000,
    storeId: "fashionid",
    img: "https://picsum.photos/400?9",
    desc: "Celana jeans dengan potongan modern dan bahan kuat untuk pemakaian jangka panjang."
  },

  {
    id: 10,
    name: "Headset Gaming",
    price: 450000,
    storeId: "techzone",
    img: "https://picsum.photos/400?10",
    desc: "Headset gaming dengan suara jernih dan mikrofon sensitif untuk pengalaman bermain maksimal."
  },

  {
    id: 11,
    name: "Mouse Wireless",
    price: 150000,
    storeId: "techzone",
    img: "https://picsum.photos/400?11",
    desc: "Mouse wireless responsif dengan desain ergonomis, nyaman digunakan seharian."
  }
];


function formatRupiah(n){
  return "Rp" + n.toLocaleString("id-ID");
}

