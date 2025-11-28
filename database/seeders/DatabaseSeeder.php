<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Poli;
use App\Models\Medicine;
use App\Models\Schedule;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Data Poli
        $poliUmum = Poli::create([
            'name' => 'Poli Umum', 
            'description' => 'Layanan kesehatan dasar untuk keluhan umum.',
            'image' => null
        ]);
    
        $poliGigi = Poli::create([
            'name' => 'Poli Gigi', 
            'description' => 'Pemeriksaan kesehatan gigi dan mulut.',
            'image' => null
        ]);

        $poliAnak = Poli::create([
            'name' => 'Poli Anak', 
            'description' => 'Spesialis kesehatan anak dan imunisasi.',
            'image' => null
        ]);

        // 2. Buat Akun ADMIN
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@rs.com',
            'role' => 'admin',
            'password' => Hash::make('password'),
            'phone' => '081234567890',
            'address' => 'Kantor Admin RS',
        ]);

        // 3. Buat Akun DOKTER
        $dokter = User::create([
            'name' => 'Dr. Budi Santoso',
            'email' => 'dokter@rs.com',
            'role' => 'dokter',
            'password' => Hash::make('password'),
            'phone' => '081298765432',
            'address' => 'Jl. Kesehatan No. 1',
        ]);

        // Hubungkan Dokter ke Tabel doctors
        $doctorDetail = Doctor::create([
            'user_id' => $dokter->id,
            'poli_id' => $poliUmum->id,
            'sip' => '123/SIP/2025',
        ]);

        // Buatkan Jadwal Default untuk Dokter Budi
        Schedule::create(['doctor_id' => $doctorDetail->id, 'day' => 'Senin', 'start_time' => '08:00', 'end_time' => '12:00']);
        Schedule::create(['doctor_id' => $doctorDetail->id, 'day' => 'Rabu', 'start_time' => '13:00', 'end_time' => '16:00']);
        Schedule::create(['doctor_id' => $doctorDetail->id, 'day' => 'Jumat', 'start_time' => '08:00', 'end_time' => '11:00']);

        // 4. Buat Akun PASIEN (Contoh)
        User::create([
            'name' => 'Pasien Contoh',
            'email' => 'pasien@rs.com',
            'role' => 'pasien',
            'password' => Hash::make('password'),
            'phone' => '081987654321',
            'address' => 'Jl. Mawar No. 5',
        ]);

        // 5. Buat Data Obat Dummy
        // --- OBAT INJEKSI (KHUSUS RUMAH SAKIT/UGD) ---
        Medicine::create(['name' => 'Ceftriaxone 1g Inj', 'type' => 'keras', 'stock' => 100, 'price' => 25000, 'description' => 'Antibiotik injeksi spektrum luas']);
        Medicine::create(['name' => 'Ketorolac 30mg Inj', 'type' => 'keras', 'stock' => 80, 'price' => 15000, 'description' => 'Pereda nyeri akut pasca operasi']);
        Medicine::create(['name' => 'Furosemide 20mg Inj', 'type' => 'keras', 'stock' => 60, 'price' => 10000, 'description' => 'Diuretik untuk gagal jantung/edema']);
        Medicine::create(['name' => 'Ranitidine 50mg Inj', 'type' => 'keras', 'stock' => 90, 'price' => 8000, 'description' => 'Injeksi penurun asam lambung akut']);
        Medicine::create(['name' => 'Dexamethasone 5mg Inj', 'type' => 'keras', 'stock' => 120, 'price' => 6000, 'description' => 'Anti radang dan reaksi alergi berat']);
        Medicine::create(['name' => 'Lidocaine 2% Inj', 'type' => 'keras', 'stock' => 50, 'price' => 12000, 'description' => 'Obat bius lokal']);
        Medicine::create(['name' => 'Epinephrine 1mg Inj', 'type' => 'keras', 'stock' => 30, 'price' => 15000, 'description' => 'Obat darurat henti jantung/anafilaksis']);
        Medicine::create(['name' => 'Ondansetron 4mg Inj', 'type' => 'keras', 'stock' => 70, 'price' => 20000, 'description' => 'Anti mual muntah pasca operasi/kemo']);

        // --- OBAT BATUK & FLU ---
        Medicine::create(['name' => 'Ambroxol 30mg', 'type' => 'biasa', 'stock' => 250, 'price' => 2500, 'description' => 'Pengencer dahak batuk berdahak']);
        Medicine::create(['name' => 'OBH Combi Batuk Flu', 'type' => 'biasa', 'stock' => 100, 'price' => 18000, 'description' => 'Sirup obat batuk dan flu']);
        Medicine::create(['name' => 'Tremenza Tablet', 'type' => 'keras', 'stock' => 100, 'price' => 3000, 'description' => 'Meredakan hidung tersumbat dan bersin']);
        Medicine::create(['name' => 'Codeine 10mg', 'type' => 'keras', 'stock' => 20, 'price' => 40000, 'description' => 'Pereda batuk kering berat']);
        Medicine::create(['name' => 'Nalgestan', 'type' => 'biasa', 'stock' => 150, 'price' => 3500, 'description' => 'Dekongestan hidung tersumbat']);

        // --- OBAT TETES (MATA & TELINGA) ---
        Medicine::create(['name' => 'Cendo Xitrol 5ml', 'type' => 'keras', 'stock' => 60, 'price' => 35000, 'description' => 'Tetes mata antibiotik dan steroid']);
        Medicine::create(['name' => 'Cendo Lyteers 15ml', 'type' => 'biasa', 'stock' => 80, 'price' => 28000, 'description' => 'Air mata buatan untuk mata kering']);
        Medicine::create(['name' => 'Erlamycetin Plus', 'type' => 'keras', 'stock' => 40, 'price' => 15000, 'description' => 'Tetes mata untuk infeksi bakteri']);
        Medicine::create(['name' => 'Otopain Tetes Telinga', 'type' => 'keras', 'stock' => 30, 'price' => 65000, 'description' => 'Obat infeksi dan nyeri telinga']);
        Medicine::create(['name' => 'Forumen', 'type' => 'biasa', 'stock' => 25, 'price' => 40000, 'description' => 'Pencair kotoran telinga']);

        // --- OBAT JANTUNG & KOLESTEROL LANJUTAN ---
        Medicine::create(['name' => 'Atorvastatin 20mg', 'type' => 'keras', 'stock' => 150, 'price' => 25000, 'description' => 'Menurunkan kolesterol dan trigliserida']);
        Medicine::create(['name' => 'Candesartan 8mg', 'type' => 'keras', 'stock' => 200, 'price' => 10000, 'description' => 'Obat hipertensi ARB']);
        Medicine::create(['name' => 'Furosemide 40mg Tablet', 'type' => 'keras', 'stock' => 150, 'price' => 3000, 'description' => 'Peluruh air seni untuk hipertensi']);
        Medicine::create(['name' => 'Aspilet Thrombo 80mg', 'type' => 'biasa', 'stock' => 180, 'price' => 15000, 'description' => 'Pengencer darah untuk jantung']);
        Medicine::create(['name' => 'ISDN 5mg', 'type' => 'keras', 'stock' => 100, 'price' => 5000, 'description' => 'Obat nyeri dada (angina)']);

        // --- OBAT SARAF & NYERI SARAF ---
        Medicine::create(['name' => 'Gabapentin 300mg', 'type' => 'keras', 'stock' => 80, 'price' => 30000, 'description' => 'Pereda nyeri saraf dan kejang']);
        Medicine::create(['name' => 'Mecobalamin 500mcg', 'type' => 'keras', 'stock' => 120, 'price' => 5000, 'description' => 'Vitamin saraf tepi']);
        Medicine::create(['name' => 'Diazepam 2mg', 'type' => 'keras', 'stock' => 10, 'price' => 50000, 'description' => 'Obat penenang dan anti kejang']);

        // --- OBAT PENCERNAAN LANJUTAN ---
        Medicine::create(['name' => 'Sucralfate Suspensi', 'type' => 'keras', 'stock' => 60, 'price' => 45000, 'description' => 'Pelapis lambung untuk tukak lambung']);
        Medicine::create(['name' => 'Domperidone 10mg', 'type' => 'keras', 'stock' => 100, 'price' => 4000, 'description' => 'Anti mual dan kembung']);
        Medicine::create(['name' => 'New Diatabs', 'type' => 'biasa', 'stock' => 150, 'price' => 3500, 'description' => 'Penyerap racun diare']);
        Medicine::create(['name' => 'Laxadine Sirup', 'type' => 'biasa', 'stock' => 50, 'price' => 40000, 'description' => 'Obat pelancar BAB (Sembelit)']);
        Medicine::create(['name' => 'Buscopan 10mg', 'type' => 'keras', 'stock' => 60, 'price' => 25000, 'description' => 'Pereda nyeri kram perut']);

        // --- IBU & ANAK ---
        Medicine::create(['name' => 'Folamil Genio', 'type' => 'biasa', 'stock' => 100, 'price' => 150000, 'description' => 'Suplemen ibu hamil dan menyusui']);
        Medicine::create(['name' => 'Tempra Drop', 'type' => 'biasa', 'stock' => 80, 'price' => 35000, 'description' => 'Paracetamol tetes untuk bayi']);
        Medicine::create(['name' => 'Stimuno Sirup', 'type' => 'biasa', 'stock' => 70, 'price' => 40000, 'description' => 'Vitamin daya tahan tubuh anak']);
        Medicine::create(['name' => 'Curcuma Plus', 'type' => 'biasa', 'stock' => 90, 'price' => 25000, 'description' => 'Penambah nafsu makan anak']);

        // --- OBAT ASAM URAT & REMATIK ---
        Medicine::create(['name' => 'Allopurinol 100mg', 'type' => 'keras', 'stock' => 200, 'price' => 3000, 'description' => 'Penurun kadar asam urat']);
        Medicine::create(['name' => 'Allopurinol 300mg', 'type' => 'keras', 'stock' => 100, 'price' => 6000, 'description' => 'Penurun asam urat dosis tinggi']);
        Medicine::create(['name' => 'Meloxicam 15mg', 'type' => 'keras', 'stock' => 120, 'price' => 5000, 'description' => 'Anti nyeri radang sendi']);
        Medicine::create(['name' => 'Piroxicam 20mg', 'type' => 'keras', 'stock' => 100, 'price' => 4000, 'description' => 'Anti inflamasi rematik']);

        // --- LAIN-LAIN (ALAT & BAHAN HABIS PAKAI) ---
        Medicine::create(['name' => 'Kasa Steril 16x16', 'type' => 'biasa', 'stock' => 300, 'price' => 8000, 'description' => 'Kain kasa penutup luka']);
        Medicine::create(['name' => 'Hansaplast Plester', 'type' => 'biasa', 'stock' => 500, 'price' => 1000, 'description' => 'Plester luka ringan']);
        Medicine::create(['name' => 'Masker Medis 3Ply', 'type' => 'biasa', 'stock' => 1000, 'price' => 2000, 'description' => 'Masker pelindung pernapasan']);
        Medicine::create(['name' => 'Hand Sanitizer 500ml', 'type' => 'biasa', 'stock' => 50, 'price' => 35000, 'description' => 'Cairan pembersih tangan antiseptik']);
        Medicine::create(['name' => 'Alkohol 70% 100ml', 'type' => 'biasa', 'stock' => 100, 'price' => 10000, 'description' => 'Cairan antiseptik steril']);  
        
        // --- OBAT KULIT & SALEP (TOPIKAL) ---
        Medicine::create(['name' => 'Gentamicin Salep Kulit', 'type' => 'keras', 'stock' => 50, 'price' => 15000, 'description' => 'Antibiotik oles untuk infeksi kulit']);
        Medicine::create(['name' => 'Miconazole Cream', 'type' => 'luar', 'stock' => 80, 'price' => 10000, 'description' => 'Obat jamur kulit ringan']);
        Medicine::create(['name' => 'Hydrocortisone 2.5%', 'type' => 'keras', 'stock' => 60, 'price' => 12000, 'description' => 'Krim anti gatal dan alergi kulit']);
        Medicine::create(['name' => 'Acyclovir 5% Cream', 'type' => 'keras', 'stock' => 40, 'price' => 18000, 'description' => 'Salep untuk herpes dan cacar air']);
        Medicine::create(['name' => 'Burnazin (Silver Sulfadiazine)', 'type' => 'keras', 'stock' => 30, 'price' => 65000, 'description' => 'Salep luka bakar tingkat lanjut']);
        Medicine::create(['name' => 'Voltaren Gel', 'type' => 'luar', 'stock' => 45, 'price' => 75000, 'description' => 'Gel pereda nyeri otot dan sendi']);
        Medicine::create(['name' => 'Scabimite Cream', 'type' => 'keras', 'stock' => 20, 'price' => 80000, 'description' => 'Obat oles untuk penyakit kudis/scabies']);

        // --- OBAT MULUT & GIGI ---
        Medicine::create(['name' => 'Betadine Gargle 190ml', 'type' => 'luar', 'stock' => 50, 'price' => 35000, 'description' => 'Obat kumur antiseptik']);
        Medicine::create(['name' => 'Enkasari Cair', 'type' => 'biasa', 'stock' => 60, 'price' => 25000, 'description' => 'Obat kumur herbal sariawan']);
        Medicine::create(['name' => 'Cataflam 50mg', 'type' => 'keras', 'stock' => 100, 'price' => 8000, 'description' => 'Pereda nyeri sakit gigi (Kalium Diklofenak)']);
        Medicine::create(['name' => 'Clindamycin 300mg', 'type' => 'keras', 'stock' => 80, 'price' => 20000, 'description' => 'Antibiotik infeksi gigi dan gusi']);
        Medicine::create(['name' => 'Kenalog in Orabase', 'type' => 'keras', 'stock' => 25, 'price' => 65000, 'description' => 'Salep khusus sariawan berat']);

        // --- KESEHATAN JIWA (PSIKIATRI) ---
        Medicine::create(['name' => 'Alprazolam 0.5mg', 'type' => 'keras', 'stock' => 50, 'price' => 15000, 'description' => 'Obat penenang ansietas/panik']);
        Medicine::create(['name' => 'Risperidone 2mg', 'type' => 'keras', 'stock' => 60, 'price' => 10000, 'description' => 'Antipsikotik untuk skizofrenia']);
        Medicine::create(['name' => 'Amitriptyline 25mg', 'type' => 'keras', 'stock' => 70, 'price' => 5000, 'description' => 'Antidepresan trisiklik']);
        Medicine::create(['name' => 'Haloperidol 1.5mg', 'type' => 'keras', 'stock' => 50, 'price' => 6000, 'description' => 'Obat gangguan mental berat']);
        Medicine::create(['name' => 'Clozapine 25mg', 'type' => 'keras', 'stock' => 30, 'price' => 15000, 'description' => 'Antipsikotik atipikal']);

        // --- KEBIDANAN, KANDUNGAN & HORMONAL ---
        Medicine::create(['name' => 'Asam Folat 400mcg', 'type' => 'biasa', 'stock' => 200, 'price' => 2000, 'description' => 'Vitamin wajib ibu hamil']);
        Medicine::create(['name' => 'Microgynon', 'type' => 'keras', 'stock' => 50, 'price' => 25000, 'description' => 'Pil kontrasepsi (KB)']);
        Medicine::create(['name' => 'Utrogestan 200mg', 'type' => 'keras', 'stock' => 40, 'price' => 180000, 'description' => 'Penguat kandungan (Progesteron)']);
        Medicine::create(['name' => 'Cal-95', 'type' => 'biasa', 'stock' => 60, 'price' => 50000, 'description' => 'Kalsium tinggi untuk ibu hamil/lansia']);
        Medicine::create(['name' => 'Cyclo-Progynova', 'type' => 'keras', 'stock' => 20, 'price' => 120000, 'description' => 'Terapi sulih hormon menopause']);

        // --- ANTI VIRUS & IMUN SISTEM ---
        Medicine::create(['name' => 'Acyclovir 400mg Tablet', 'type' => 'keras', 'stock' => 100, 'price' => 10000, 'description' => 'Antivirus herpes dan cacar air']);
        Medicine::create(['name' => 'Oseltamivir 75mg', 'type' => 'keras', 'stock' => 50, 'price' => 25000, 'description' => 'Antivirus influenza berat']);
        Medicine::create(['name' => 'Methisoprinol 500mg', 'type' => 'keras', 'stock' => 60, 'price' => 15000, 'description' => 'Imunomodulator infeksi virus']);
        Medicine::create(['name' => 'Valaciclovir 500mg', 'type' => 'keras', 'stock' => 30, 'price' => 35000, 'description' => 'Antivirus herpes zoster']);

        // --- ALAT KESEHATAN HABIS PAKAI (ALKES) ---
        Medicine::create(['name' => 'Spuit 3cc', 'type' => 'biasa', 'stock' => 500, 'price' => 2000, 'description' => 'Alat suntik 3ml dengan jarum']);
        Medicine::create(['name' => 'Spuit 5cc', 'type' => 'biasa', 'stock' => 500, 'price' => 3000, 'description' => 'Alat suntik 5ml dengan jarum']);
        Medicine::create(['name' => 'IV Catheter No. 20G', 'type' => 'biasa', 'stock' => 200, 'price' => 15000, 'description' => 'Jarum infus dewasa (Pink)']);
        Medicine::create(['name' => 'IV Catheter No. 24G', 'type' => 'biasa', 'stock' => 200, 'price' => 15000, 'description' => 'Jarum infus anak/bayi (Kuning)']);
        Medicine::create(['name' => 'Foley Catheter No. 16', 'type' => 'biasa', 'stock' => 50, 'price' => 25000, 'description' => 'Selang kateter urin dewasa']);
        Medicine::create(['name' => 'Urine Bag', 'type' => 'biasa', 'stock' => 100, 'price' => 10000, 'description' => 'Kantong penampung urin']);
        Medicine::create(['name' => 'Blood Set', 'type' => 'biasa', 'stock' => 50, 'price' => 20000, 'description' => 'Selang infus khusus transfusi darah']);

        // --- CAIRAN INFUS KHUSUS ---
        Medicine::create(['name' => 'Dextrose 5% 500ml', 'type' => 'biasa', 'stock' => 200, 'price' => 18000, 'description' => 'Cairan gula infus']);
        Medicine::create(['name' => 'Dextrose 40% 25ml', 'type' => 'keras', 'stock' => 50, 'price' => 10000, 'description' => 'Cairan gula pekat untuk hipoglikemia']);
        Medicine::create(['name' => 'Manitol 20%', 'type' => 'keras', 'stock' => 30, 'price' => 60000, 'description' => 'Infus penurun tekanan intrakranial']);
        Medicine::create(['name' => 'Aminofluid 500ml', 'type' => 'keras', 'stock' => 20, 'price' => 100000, 'description' => 'Infus nutrisi asam amino']);
        Medicine::create(['name' => 'Paracetamol Infus', 'type' => 'keras', 'stock' => 50, 'price' => 45000, 'description' => 'Pereda nyeri infus pasca operasi']);

        // --- OBAT SALURAN KEMIH ---
        Medicine::create(['name' => 'Urinter 400mg', 'type' => 'keras', 'stock' => 60, 'price' => 8000, 'description' => 'Antibiotik saluran kemih']);
        Medicine::create(['name' => 'Batugin Elixir', 'type' => 'biasa', 'stock' => 40, 'price' => 35000, 'description' => 'Peluruh batu ginjal herbal']);
        Medicine::create(['name' => 'Harnal Ocas 0.4mg', 'type' => 'keras', 'stock' => 30, 'price' => 15000, 'description' => 'Obat prostat']);

        // --- OBAT WASIR/AMBEIEN ---
        Medicine::create(['name' => 'Ambeven Kapsul', 'type' => 'biasa', 'stock' => 80, 'price' => 20000, 'description' => 'Obat wasir herbal']);
        Medicine::create(['name' => 'Ardium 500mg', 'type' => 'keras', 'stock' => 50, 'price' => 12000, 'description' => 'Obat wasir akut micronized flavonoid']);
        Medicine::create(['name' => 'Borraginol-S Suppositoria', 'type' => 'keras', 'stock' => 30, 'price' => 15000, 'description' => 'Obat peluru wasir (dimasukkan dubur)']);
    }
}