<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Angkatan;
use App\Models\Coach;
use App\Models\Evaluator;
use App\Models\JenisPelatihan;
use App\Models\Kelompok;
use App\Models\kelompok_peserta;
use App\Models\Mentor;
use App\Models\Pendaftaran;
use App\Models\Penguji;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KelompokController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelompok::with(['jenisPelatihan', 'angkatan', 'mentor', 'coach', 'penguji', 'evaluator']);

        if ($request->filled('jenis_pelatihan')) {
            $query->where('id_jenis_pelatihan', $request->jenis_pelatihan);
        }
        if ($request->filled('angkatan')) {
            $query->where('id_angkatan', $request->angkatan);
        }
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('search')) {
            $query->where('nama_kelompok', 'like', '%' . $request->search . '%');
        }

        $kelompok = $query->orderBy('tahun', 'desc')->orderBy('nama_kelompok')->paginate(10)->withQueryString();

        $jenisPelatihan = JenisPelatihan::where('aktif', true)->get();
        $angkatanList   = $this->sortAngkatan(Angkatan::orderBy('tahun', 'desc')->get());
        $tahunList      = Kelompok::distinct()->orderBy('tahun', 'desc')->pluck('tahun');

        return view('admin.kelompok.index', compact('kelompok', 'jenisPelatihan', 'angkatanList', 'tahunList'));
    }

    public function create()
    {
        $jenisPelatihan = JenisPelatihan::where('aktif', true)->get();
        $angkatanList   = $this->sortAngkatan(Angkatan::with('jenisPelatihan')->orderBy('tahun', 'desc')->get());
        $mentorList     = Mentor::orderBy('nama_mentor')->get();
        $coachList      = Coach::where('status_aktif', true)->orderBy('nama')->get();
        $pengujiList    = Penguji::where('status_aktif', true)->orderBy('nama')->get();
        $evaluatorList  = Evaluator::where('status_aktif', true)->orderBy('nama')->get();

        return view('admin.kelompok.create', compact(
            'jenisPelatihan', 'angkatanList', 'mentorList', 'coachList', 'pengujiList', 'evaluatorList'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_jenis_pelatihan' => 'required|exists:jenis_pelatihan,id',
            'id_angkatan'        => 'required|exists:angkatan,id',
            'nama_kelompok'      => [
                'required', 'string', 'max:255',
                Rule::unique('kelompoks')
                    ->where('id_angkatan', $request->id_angkatan)
                    ->where('tahun', $request->tahun),
            ],
            'tahun'              => 'required|integer|min:2000|max:2099',
            'id_mentor'          => 'nullable|exists:mentor,id',
            'id_coach'           => 'nullable|exists:coaches,id',
            'id_penguji'         => 'nullable|exists:pengujis,id',
            'id_evaluator'       => 'nullable|exists:evaluators,id',
            'keterangan'         => 'nullable|string',
            'link_laporan'       => 'nullable|url|max:2048', // ✅ tambahan
        ], [
            'nama_kelompok.unique' => 'Nama kelompok "' . $request->nama_kelompok . '" sudah ada pada angkatan dan tahun yang sama.',
            'link_laporan.url'     => 'Link laporan harus berupa URL yang valid (contoh: https://drive.google.com/...)',
        ]);

        Kelompok::create($validated);

        return redirect()->route('kelompok.index')->with('success', 'Kelompok berhasil ditambahkan.');
    }

    public function show(Kelompok $kelompok)
    {
        $kelompok->load(['jenisPelatihan', 'angkatan', 'mentor', 'coach', 'penguji', 'evaluator', 'peserta.kepegawaian']);
        return view('admin.kelompok.show', compact('kelompok'));
    }

    public function edit(Kelompok $kelompok)
    {
        $jenisPelatihan = JenisPelatihan::where('aktif', true)->get();
        $angkatanList   = $this->sortAngkatan(Angkatan::with('jenisPelatihan')->orderBy('tahun', 'desc')->get());
        $mentorList     = Mentor::orderBy('nama_mentor')->get();
        $coachList      = Coach::where('status_aktif', true)->orderBy('nama')->get();
        $pengujiList    = Penguji::where('status_aktif', true)->orderBy('nama')->get();
        $evaluatorList  = Evaluator::where('status_aktif', true)->orderBy('nama')->get();

        return view('admin.kelompok.edit', compact(
            'kelompok', 'jenisPelatihan', 'angkatanList', 'mentorList', 'coachList', 'pengujiList', 'evaluatorList'
        ));
    }

    public function update(Request $request, Kelompok $kelompok)
    {
        $validated = $request->validate([
            'id_jenis_pelatihan' => 'required|exists:jenis_pelatihan,id',
            'id_angkatan'        => 'required|exists:angkatan,id',
            'nama_kelompok'      => [
                'required', 'string', 'max:255',
                Rule::unique('kelompoks')
                    ->ignore($kelompok->id)
                    ->where('id_angkatan', $request->id_angkatan)
                    ->where('tahun', $request->tahun),
            ],
            'tahun'              => 'required|integer|min:2000|max:2099',
            'id_mentor'          => 'nullable|exists:mentor,id',
            'id_coach'           => 'nullable|exists:coaches,id',
            'id_penguji'         => 'nullable|exists:pengujis,id',
            'id_evaluator'       => 'nullable|exists:evaluators,id',
            'keterangan'         => 'nullable|string',
            'link_laporan'       => 'nullable|url|max:2048', // ✅ tambahan
        ], [
            'nama_kelompok.unique' => 'Nama kelompok "' . $request->nama_kelompok . '" sudah ada pada angkatan dan tahun yang sama.',
            'link_laporan.url'     => 'Link laporan harus berupa URL yang valid (contoh: https://drive.google.com/...)',
        ]);

        $kelompok->update($validated);

        return redirect()->route('kelompok.index')->with('success', 'Kelompok berhasil diperbarui.');
    }

    public function destroy(Kelompok $kelompok)
    {
        kelompok_peserta::where('id_kelompok', $kelompok->id)->delete();
        $kelompok->delete();

        return back()->with('success', 'Kelompok berhasil dihapus beserta seluruh pesertanya.');
    }

    // ─── Kelola Peserta ──────────────────────────────────────────────

    public function kelolaPeserta(Kelompok $kelompok)
    {
        $kelompok->load(['jenisPelatihan', 'angkatan', 'mentor', 'coach', 'penguji', 'evaluator']);

        $pesertaTersedia = Pendaftaran::with('peserta.kepegawaian')
            ->where('id_angkatan', $kelompok->id_angkatan)
            ->whereNotIn('id_peserta', function ($q) use ($kelompok) {
                $q->select('kp.id_peserta')
                    ->from('kelompok_pesertas as kp')
                    ->join('kelompoks as k', 'k.id', '=', 'kp.id_kelompok')
                    ->where('k.id_angkatan', $kelompok->id_angkatan);
            })
            ->get()
            ->map(fn($p) => $p->peserta)
            ->filter()
            ->unique('id')
            ->sortBy(fn($p) => $p->ndh ?? 9999)
            ->values();

        $pesertaTerhubung = $kelompok->peserta()
            ->with('kepegawaian')
            ->get()
            ->sortBy(fn($p) => $p->ndh ?? 9999)
            ->values();

        return view('admin.kelompok.kelola-peserta', compact('kelompok', 'pesertaTersedia', 'pesertaTerhubung'));
    }

    public function tambahPeserta(Request $request, Kelompok $kelompok)
    {
        $request->validate([
            'peserta_ids'   => 'required|array',
            'peserta_ids.*' => 'exists:peserta,id',
        ]);

        $inserted = 0;
        foreach ($request->peserta_ids as $idPeserta) {
            $exists = kelompok_peserta::where('id_kelompok', $kelompok->id)
                ->where('id_peserta', $idPeserta)
                ->exists();
            if (!$exists) {
                kelompok_peserta::create([
                    'id_kelompok' => $kelompok->id,
                    'id_peserta'  => $idPeserta,
                ]);
                $inserted++;
            }
        }

        return back()->with('success', "$inserted peserta berhasil ditambahkan ke kelompok.");
    }

    public function lepasPeserta(Request $request, Kelompok $kelompok)
    {
        $request->validate(['peserta_id' => 'required|exists:peserta,id']);

        kelompok_peserta::where('id_kelompok', $kelompok->id)
            ->where('id_peserta', $request->peserta_id)
            ->delete();

        return back()->with('success', 'Peserta berhasil dilepas dari kelompok.');
    }

    // ─── API helper ──────────────────────────────────────────────────

    public function getAngkatanByJenis(Request $request)
    {
        $angkatan = $this->sortAngkatan(
            Angkatan::where('id_jenis_pelatihan', $request->id_jenis_pelatihan)
                ->orderBy('tahun', 'desc')
                ->get(['id', 'nama_angkatan', 'tahun'])
        );

        return response()->json($angkatan);
    }

    // ─── Private Helpers ─────────────────────────────────────────────

    private function romanToInt(string $roman): int
    {
        $map    = ['I' => 1, 'V' => 5, 'X' => 10, 'L' => 50, 'C' => 100, 'D' => 500, 'M' => 1000];
        $result = 0;
        $prev   = 0;
        foreach (array_reverse(str_split(strtoupper($roman))) as $char) {
            $val    = $map[$char] ?? 0;
            $result += $val < $prev ? -$val : $val;
            $prev   = $val;
        }
        return $result ?: 9999;
    }

    private function sortAngkatan($angkatanList)
    {
        return $angkatanList->sortBy(function ($ang) {
            preg_match('/([IVXLCDM]+)$/i', trim($ang->nama_angkatan), $m);
            return $m ? $this->romanToInt($m[1]) : 9999;
        })->values();
    }
}