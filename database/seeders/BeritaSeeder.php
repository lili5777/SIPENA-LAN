<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Berita;

class BeritaSeeder extends Seeder
{
    public function run()
    {
        $beritas = [
            [
                'judul' => 'Seminar Nasional Teknologi Informasi 2023',
                'isi' => '<p>SIMPEL mengadakan seminar nasional tentang perkembangan teknologi informasi terkini dengan menghadirkan pembicara dari berbagai institusi ternama. Seminar ini bertujuan untuk meningkatkan pengetahuan dan keterampilan peserta di bidang teknologi informasi.</p>
                         <p>Acara ini dihadiri oleh lebih dari 500 peserta dari berbagai latar belakang profesional. Materi yang disampaikan sangat relevan dengan kebutuhan industri saat ini.</p>
                         <h3>Topik yang dibahas:</h3>
                         <ul>
                             <li>Artificial Intelligence dan Machine Learning</li>
                             <li>Cybersecurity di Era Digital</li>
                             <li>Big Data Analytics</li>
                             <li>Cloud Computing</li>
                         </ul>',
                'foto' => null,
                'created_at' => now()->subDays(15),
            ],
            [
                'judul' => 'Pelatihan Digitalisasi Sistem Informasi',
                'isi' => '<p>Program pelatihan intensif untuk meningkatkan kompetensi di bidang sistem informasi dan transformasi digital bagi profesional. Pelatihan ini berlangsung selama 5 hari dengan materi yang komprehensif.</p>
                         <p>Pelatihan diikuti oleh 120 peserta dari berbagai instansi pemerintah dan swasta. Para peserta mendapatkan sertifikat resmi setelah menyelesaikan seluruh sesi pelatihan.</p>
                         <h3>Materi Pelatihan:</h3>
                         <ol>
                             <li>Dasar-dasar Sistem Informasi</li>
                             <li>Transformasi Digital dalam Organisasi</li>
                             <li>Manajemen Basis Data</li>
                             <li>Pengembangan Aplikasi Web</li>
                             <li>Keamanan Sistem Informasi</li>
                         </ol>',
                'foto' => null,
                'created_at' => now()->subDays(12),
            ],
            [
                'judul' => 'Rilis Publikasi Terbaru SIMPEL 2023',
                'isi' => '<p>SIMPEL telah merilis publikasi terbaru tentang perkembangan sistem informasi di Indonesia tahun 2023 yang dapat diakses secara gratis melalui website resmi.</p>
                         <p>Publikasi ini berisi analisis mendalam tentang tren teknologi informasi, statistik penggunaan sistem informasi di berbagai sektor, dan prediksi perkembangan di masa depan.</p>
                         <p><strong>Download publikasi:</strong> <a href="#">Laporan Sistem Informasi Indonesia 2023.pdf</a></p>
                         <p>Publikasi ini telah diunduh lebih dari 2,000 kali dalam minggu pertama perilisannya.</p>',
                'foto' => null,
                'created_at' => now()->subDays(10),
            ],
            [
                'judul' => 'Workshop Pengembangan Aplikasi Mobile',
                'isi' => '<p>SIMPEL menyelenggarakan workshop pengembangan aplikasi mobile untuk pemula. Workshop ini ditujukan bagi mereka yang ingin memulai karir di bidang pengembangan aplikasi.</p>
                         <p>Durasi workshop: 2 hari penuh<br>
                         Lokasi: Laboratorium Komputer SIMPEL<br>
                         Peserta: 80 orang<br>
                         Instruktur: Tim Developer SIMPEL</p>
                         <h3>Teknologi yang diajarkan:</h3>
                         <ul>
                             <li>React Native untuk cross-platform development</li>
                             <li>Flutter untuk pengembangan cepat</li>
                             <li>Firebase untuk backend</li>
                             <li>UI/UX Design Principles</li>
                         </ul>',
                'foto' => null,
                'created_at' => now()->subDays(8),
            ],
            [
                'judul' => 'Kerjasama dengan Universitas Terkemuka',
                'isi' => '<p>SIMPEL menandatangani Memorandum of Understanding (MoU) dengan tiga universitas terkemuka di Indonesia untuk kerjasama dalam penelitian dan pengembangan sistem informasi.</p>
                         <p>Kerjasama ini mencakup:</p>
                         <ol>
                             <li>Pertukaran ahli dan peneliti</li>
                             <li>Pengembangan kurikulum bersama</li>
                             <li>Penelitian terapan di bidang IT</li>
                             <li>Program magang untuk mahasiswa</li>
                         </ol>
                         <p>Universitas yang bekerjasama: Universitas Indonesia, Institut Teknologi Bandung, dan Universitas Gadjah Mada.</p>',
                'foto' => null,
                'created_at' => now()->subDays(6),
            ],
            [
                'judul' => 'Launching Website Baru SIMPEL',
                'isi' => '<p>SIMPEL meluncurkan website baru dengan desain yang lebih modern dan fitur yang lebih lengkap. Website baru ini dilengkapi dengan berbagai fasilitas untuk pengguna.</p>
                         <p><strong>Fitur baru yang tersedia:</strong></p>
                         <ul>
                             <li>Responsive design untuk semua perangkat</li>
                             <li>Sistem pencarian yang lebih canggih</li>
                             <li>Portal anggota dengan fitur lengkap</li>
                             <li>Download center untuk publikasi</li>
                             <li>Event registration system</li>
                         </ul>
                         <p>Website dapat diakses di: <a href="https://simpel.id">https://simpel.id</a></p>',
                'foto' => null,
                'created_at' => now()->subDays(5),
            ],
            [
                'judul' => 'Webinar Internasional: Future of IT',
                'isi' => '<p>SIMPEL mengadakan webinar internasional dengan tema "The Future of Information Technology" yang menghadirkan pembicara dari luar negeri.</p>
                         <p><strong>Pembicara:</strong></p>
                         <ol>
                             <li>Dr. Michael Chen - Google AI Research (USA)</li>
                             <li>Prof. Sarah Johnson - MIT Computer Science (USA)</li>
                             <li>Dr. Kenji Tanaka - Tokyo University (Japan)</li>
                             <li>Prof. Dr. Ahmad Surya - SIMPEL (Indonesia)</li>
                         </ol>
                         <p>Webinar diikuti oleh lebih dari 1,200 peserta dari 15 negara berbeda. Rekaman webinar tersedia di channel YouTube SIMPEL.</p>',
                'foto' => null,
                'created_at' => now()->subDays(4),
            ],
            [
                'judul' => 'Sertifikasi Profesional IT Batch 5',
                'isi' => '<p>Program sertifikasi profesional IT batch 5 telah dibuka dengan 5 bidang spesialisasi baru. Program ini bertujuan untuk meningkatkan kompetensi tenaga IT di Indonesia.</p>
                         <p><strong>Bidang sertifikasi yang tersedia:</strong></p>
                         <ul>
                             <li>Certified Network Administrator</li>
                             <li>Certified Database Administrator</li>
                             <li>Certified Cybersecurity Specialist</li>
                             <li>Certified Web Developer</li>
                             <li>Certified Data Analyst</li>
                         </ul>
                         <p>Pendaftaran dibuka hingga 30 November 2023. Tes sertifikasi akan dilaksanakan secara online.</p>',
                'foto' => null,
                'created_at' => now()->subDays(3),
            ],
            [
                'judul' => 'Penghargaan sebagai Lembaga Pelatihan Terbaik',
                'isi' => '<p>SIMPEL menerima penghargaan sebagai "Lembaga Pelatihan dan Sertifikasi IT Terbaik 2023" dari Kementerian Komunikasi dan Informatika.</p>
                         <p>Penghargaan ini diberikan berdasarkan penilaian terhadap:</p>
                         <ol>
                             <li>Kualitas materi pelatihan</li>
                             <li>Kompetensi instruktur</li>
                             <li>Fasilitas pendukung</li>
                             <li>Tingkat kepuasan peserta</li>
                             <li>Dampak terhadap peningkatan kompetensi</li>
                         </ol>
                         <p>Penghargaan diserahkan langsung oleh Menteri Kominfo dalam acara Indonesia Digital Awards 2023.</p>',
                'foto' => null,
                'created_at' => now()->subDays(2),
            ],
            [
                'judul' => 'Peluncuran Aplikasi SIMPEL Mobile',
                'isi' => '<p>SIMPEL resmi meluncurkan aplikasi mobile untuk memudahkan akses layanan melalui smartphone. Aplikasi tersedia untuk platform Android dan iOS.</p>
                         <p><strong>Fitur utama aplikasi:</strong></p>
                         <ul>
                             <li>Notifikasi kegiatan dan event</li>
                             <li>Pendaftaran pelatihan online</li>
                             <li>Akses materi pembelajaran</li>
                             <li>Sistem ujian online</li>
                             <li>Forum diskusi anggota</li>
                             <li>Konsultasi dengan ahli</li>
                         </ul>
                         <p>Aplikasi dapat diunduh di Google Play Store dan Apple App Store dengan nama "SIMPEL Indonesia".</p>',
                'foto' => null,
                'created_at' => now()->subDays(1),
            ],
            [
                'judul' => 'Program Beasiswa untuk Mahasiswa',
                'isi' => '<p>SIMPEL membuka program beasiswa untuk 50 mahasiswa berprestasi dari berbagai universitas di Indonesia. Beasiswa ini mencakup biaya pelatihan dan sertifikasi.</p>
                         <p><strong>Syarat pendaftaran:</strong></p>
                         <ol>
                             <li>Mahasiswa aktif S1/S2</li>
                             <li>IPK minimal 3.25</li>
                             <li>Surat rekomendasi dari kampus</li>
                             <li>Esai tentang pentingnya teknologi informasi</li>
                             <li>Portofolio (jika ada)</li>
                         </ol>
                         <p>Pendaftaran dibuka hingga 15 Desember 2023. Hasil seleksi akan diumumkan pada 20 Desember 2023.</p>',
                'foto' => null,
                'created_at' => now(),
            ],
            [
                'judul' => 'Hackathon Nasional 2023',
                'isi' => '<p>SIMPEL menyelenggarakan Hackathon Nasional dengan tema "Digital Solutions for Social Problems". Kompetisi ini diikuti oleh 150 tim dari seluruh Indonesia.</p>
                         <p><strong>Kategori lomba:</strong></p>
                         <ul>
                             <li>Best Education Solution</li>
                             <li>Best Health Solution</li>
                             <li>Best Environmental Solution</li>
                             <li>Best Community Solution</li>
                         </ul>
                         <p><strong>Hadiah total:</strong> Rp 500.000.000,-</p>
                         <p>Juara 1: Tim "EduTech" dari Universitas Indonesia dengan solusi platform pembelajaran adaptif untuk daerah terpencil.</p>',
                'foto' => null,
                'created_at' => now()->subDays(7),
            ],
            [
                'judul' => 'Magang Bersertifikat Batch 3',
                'isi' => '<p>Program magang bersertifikat batch 3 telah dimulai dengan 75 peserta dari berbagai perguruan tinggi. Program berlangsung selama 3 bulan.</p>
                         <p><strong>Divisi yang tersedia untuk magang:</strong></p>
                         <ol>
                             <li>Software Development</li>
                             <li>Data Science & Analytics</li>
                             <li>Network & Infrastructure</li>
                             <li>Digital Marketing</li>
                             <li>UI/UX Design</li>
                         </ol>
                         <p>Setelah menyelesaikan program, peserta akan mendapatkan sertifikat magang dan surat rekomendasi. Beberapa peserta berkesempatan direkrut sebagai karyawan tetap.</p>',
                'foto' => null,
                'created_at' => now()->subDays(9),
            ],
            [
                'judul' => 'Konferensi Tahunan IT Leaders',
                'isi' => '<p>SIMPEL menyelenggarakan konferensi tahunan yang menghadirkan para pemimpin IT dari perusahaan-perusahaan besar di Indonesia. Acara ini menjadi ajang sharing best practices.</p>
                         <p><strong>Perusahaan yang hadir:</strong></p>
                         <ul>
                             <li>GoTo Group</li>
                             <li>Bank Central Asia</li>
                             <li>Telkom Indonesia</li>
                             <li>Pertamina</li>
                             <li>Bukalapak</li>
                             <li>Traveloka</li>
                         </ul>
                         <p>Topik utama: "Digital Transformation in Post-Pandemic Era" dan "Building Resilient IT Infrastructure".</p>',
                'foto' => null,
                'created_at' => now()->subDays(11),
            ],
            [
                'judul' => 'Research Grant untuk Dosen & Peneliti',
                'isi' => '<p>SIMPEL membuka program research grant senilai total Rp 2 Miliar untuk dosen dan peneliti di bidang teknologi informasi. Program ini mendukung penelitian terapan.</p>
                         <p><strong>Bidang penelitian yang didukung:</strong></p>
                         <ol>
                             <li>Artificial Intelligence & Machine Learning</li>
                             <li>Internet of Things (IoT)</li>
                             <li>Cybersecurity</li>
                             <li>Blockchain Technology</li>
                             <li>Human-Computer Interaction</li>
                         </ol>
                         <p>Pendaftaran proposal dibuka hingga 30 November 2023. Hasil seleksi diumumkan pada Januari 2024.</p>',
                'foto' => null,
                'created_at' => now()->subDays(13),
            ],
            [
                'judul' => 'Digital Literacy Program untuk UMKM',
                'isi' => '<p>SIMPEL meluncurkan program literasi digital untuk 1,000 pelaku UMKM di Jawa Barat. Program ini bertujuan meningkatkan kemampuan digital UMKM.</p>
                         <p><strong>Materi pelatihan:</strong></p>
                         <ul>
                             <li>Digital Marketing Dasar</li>
                             <li>E-commerce Setup</li>
                             <li>Social Media Management</li>
                             <li>Keamanan Digital untuk Bisnis</li>
                             <li>Analytics untuk UMKM</li>
                         </ul>
                         <p>Program berlangsung selama 2 bulan dengan kombinasi tatap muka dan online learning. Didukung oleh Kementerian Koperasi dan UKM.</p>',
                'foto' => null,
                'created_at' => now()->subDays(14),
            ],
            [
                'judul' => 'Upgrade Data Center SIMPEL',
                'isi' => '<p>SIMPEL melakukan upgrade besar-besaran pada data center untuk meningkatkan kapasitas dan keamanan layanan. Upgrade ini mencakup:</p>
                         <ol>
                             <li>Penambahan server baru</li>
                             <li>Implementasi sistem redundancy</li>
                             <li>Peningkatan bandwidth internet</li>
                             <li>Security system upgrade</li>
                             <li>Backup power system</li>
                         </ol>
                         <p>Dengan upgrade ini, layanan SIMPEL dapat melayani hingga 100,000 pengguna secara bersamaan dengan uptime 99.9%.</p>
                         <p>Data center baru telah mendapatkan sertifikasi Tier III dari Uptime Institute.</p>',
                'foto' => null,
                'created_at' => now()->subDays(16),
            ],
            [
                'judul' => 'MOU dengan Asosiasi Industri IT',
                'isi' => '<p>SIMPEL menandatangani Nota Kesepahaman dengan ASPINDI (Asosiasi Pengusaha Indonesia) untuk pengembangan SDM IT di Indonesia.</p>
                         <p><strong>Bentuk kerjasama:</strong></p>
                         <ul>
                             <li>Penyelarasan kurikulum dengan kebutuhan industri</li>
                             <li>Program sertifikasi bersama</li>
                             <li>Job matching untuk lulusan</li>
                             <li>Penelitian kebutuhan skill IT</li>
                             <li>Pengembangan kompetensi instruktur</li>
                         </ul>
                         <p>Kerjasama ini diharapkan dapat mengurangi gap antara kebutuhan industri dengan kemampuan lulusan.</p>',
                'foto' => null,
                'created_at' => now()->subDays(17),
            ],
            [
                'judul' => 'Sosialisasi Program Baru 2024',
                'isi' => '<p>SIMPEL mengadakan sosialisasi program baru yang akan diluncurkan pada tahun 2024. Acara dihadiri oleh perwakilan dari berbagai instansi dan perusahaan.</p>
                         <p><strong>Program baru 2024:</strong></p>
                         <ol>
                             <li>AI Masterclass Series</li>
                             <li>Cloud Computing Certification</li>
                             <li>Cybersecurity Bootcamp</li>
                             <li>Data Science Professional Program</li>
                             <li>Digital Leadership Program</li>
                         </ol>
                         <p>Pendaftaran early bird dibuka mulai 1 Desember 2023 dengan diskon 30% untuk 100 pendaftar pertama.</p>',
                'foto' => null,
                'created_at' => now()->subDays(18),
            ],
        ];

        foreach ($beritas as $berita) {
            Berita::create($berita);
        }
    }
}
