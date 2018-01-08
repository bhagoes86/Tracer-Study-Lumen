<?php

namespace App\Http\Controllers\Mahasiswa;

use Illuminate\Http\Request;
use App\Mahasiswa;
use App\Akademik;
use App\Foto;
use App\Pekerjaan;
use App\Mahasiswa_Login;
use App\Krisar;

class MahasiswaController extends \App\Http\Controllers\Controller {

    public function base64ToImage($imageData, $nim) {
        list($type, $imageData) = explode(';', $imageData);
        list(, $extension) = explode('/', $type);
        list(, $imageData) = explode(',', $imageData);
        $fileName = $nim . '_' . uniqid() . '.' . $extension;
        $path = "assets/img/$fileName";
        $imageData = base64_decode($imageData);
        file_put_contents($path, $imageData);

        $link_path = $path;
        return $link_path;
    }

    public function put_akun(Request $request, $nim) {
        $password = $request->input('password');

        $mhs = Mahasiswa_Login::find($nim);

        $mhs->nama = $password;

        if ($mhs->save()) {
            $res['success'] = true;
            $res['message'] = 'Sukses Memperbarui!';
            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Gagal Memperbarui!';
            return response($res, 400);
        }
    }

    public function del_akun(Request $request, $nim) {
        $mhs = Mahasiswa_Login::find($nim);

        if ($mhs->delete()) {
            $res['success'] = true;
            $res['message'] = 'Sukses Menghapus!';
            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Gagal Menghapus!';
            return response($res, 400);
        }
    }

    public function get_akun(Request $request, $nim) {
        $mhs = Mahasiswa_Login::where('nim', $nim)->get();
        if ($mhs) {
            $res['success'] = true;
            $res['message'] = $mhs;

            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Cannot find Mahasiswa!';

            return response($res, 400);
        }
    }

    public function get_all_akun(Request $request) {
        $mhs = Mahasiswa_Login::all();
        if ($mhs) {
            $res['success'] = true;
            $res['message'] = $mhs;

            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Cannot find Mahasiswa!';

            return response($res, 400);
        }
    }

    public function set_mhs(Request $request) {
        $nim = $request->input('nim');
        $nama = $request->input('nama');
        $alamat = $request->input('alamat');
        $no_telepon = $request->input('no_telepon');
        $tempat_lahir = $request->input('tempat_lahir');
        $tanggal_lahir = $request->input('tanggal_lahir');
        $email = $request->input('email');

        if (Mahasiswa::find($nim)) {
            $set = Mahasiswa::find($nim);
        } else {
            $set = new Mahasiswa;
        }

        $set->nim = $nim;
        $set->nama = $nama;
        $set->email = $email;
        $set->alamat = $alamat;
        $set->no_telepon = $no_telepon;
        $set->tempat_lahir = $tempat_lahir;
        $set->tanggal_lahir = $tanggal_lahir;
        $set->save();

        //buat akun mahasiswa
        if (Mahasiswa_Login::find($nim)) {
            $set_akun = Mahasiswa_Login::find($nim);
        } else {
            $set_akun = new Mahasiswa_Login;
        }
        $set_akun->nim = $nim;
        $set_akun->password = null;
        $set_akun->save();

        if ($set && $set_akun) {
            $res['success'] = true;
            $res['message'] = 'Sukses Menyimpan!';
            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Gagal Menyimpan!';
            return response($res, 400);
        }
    }

    public function put_mhs(Request $request, $nim) {
        $new_nim = $request->input('nim');
        $nama = $request->input('nama');
        $alamat = $request->input('alamat');
        $no_telepon = $request->input('no_telepon');
        $tempat_lahir = $request->input('tempat_lahir');
        $tanggal_lahir = $request->input('tanggal_lahir');
        $email = $request->input('email');

        $mhs = Mahasiswa::find($nim);

        $mhs->nim = $new_nim;
        $mhs->nama = $nama;
        $mhs->alamat = $alamat;
        $mhs->no_telepon = $no_telepon;
        $mhs->email = $email;
        $mhs->tempat_lahir = $tempat_lahir;
        $mhs->tanggal_lahir = $tanggal_lahir;

        if ($mhs->save()) {
            $res['success'] = true;
            $res['message'] = 'Sukses Memperbarui!';
            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Gagal Memperbarui!';
            return response($res, 400);
        }
    }

    public function del_mhs(Request $request, $nim) {
        $mhs = Mahasiswa::find($nim);

        if ($mhs->delete()) {
            $res['success'] = true;
            $res['message'] = 'Sukses Menghapus!';
            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Gagal Menghapus!';
            return response($res, 400);
        }
    }

    public function get_mhs(Request $request, $nim) {
        $mhs = Mahasiswa::where('nim', $nim)->get();
        if ($mhs) {
            $res['success'] = true;
            $res['message'] = $mhs;

            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Cannot find Mahasiswa!';

            return response($res, 400);
        }
    }

    public function get_all_mhs(Request $request) {
        $mhs = Mahasiswa::all();
        if ($mhs) {
            $res['success'] = true;
            $res['message'] = $mhs;

            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Cannot find Mahasiswa!';

            return response($res, 400);
        }
    }

    public function set_foto(Request $request) {
        $nim = $request->input('nim');
        $foto = $request->input('foto');

        if (strlen($foto) != 0) {
            $foto = $this->base64ToImage($foto, $nim);
        }

        $set = Foto::create([
                    'nim' => $nim,
                    'foto' => $foto
        ]);
        if ($set) {
            $res['success'] = true;
            $res['message'] = 'Sukses Menyimpan!';
            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Gagal Menyimpan!';
            return response($res, 400);
        }
    }

    public function put_foto(Request $request, $nim) {
        $foto = $request->input('foto');

        if (strlen($foto) != 0) {
            $foto = $this->base64ToImage($foto, $nim);
        }

        $put = Foto::find($nim);

        $put->foto = $foto;

        if ($put->save()) {
            $res['success'] = true;
            $res['message'] = 'Sukses Memperbarui!';
            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Gagal Memperbarui!';
            return response($res, 400);
        }
    }

    public function del_foto(Request $request, $nim) {
        $mhs_foto = Foto::find($nim);

        if ($mhs_foto->delete()) {
            $res['success'] = true;
            $res['message'] = 'Sukses Menghapus!';
            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Gagal Menghapus!';
            return response($res, 400);
        }
    }

    public function get_foto(Request $request, $nim) {
        $get_foto = Foto::where('nim', $nim)->get();
        if ($get_foto) {
            $res['success'] = true;
            $res['message'] = $get_foto;

            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Cannot find Foto!';

            return response($res, 400);
        }
    }

    public function get_all_foto(Request $request) {
        $get_foto = Foto::all();
        if ($get_foto) {
            $res['success'] = true;
            $res['message'] = $get_foto;

            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Cannot find Foto!';

            return response($res, 400);
        }
    }

    public function set_pekerjaan(Request $request) {
        $nim = $request->input('nim');
        $status_pekerjaan = $request->input('status_pekerjaan');
        $keterangan = $request->input('keterangan');

        $set = Pekerjaan::create([
                    'nim' => $nim,
                    'status_pekerjaan' => $status_pekerjaan,
                    'keterangan' => json_encode($keterangan)
        ]);
        if ($set) {
            $res['success'] = true;
            $res['message'] = 'Sukses Menyimpan!';
            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Gagal Menyimpan!';
            return response($res, 400);
        }
    }

    public function put_pekerjaan(Request $request, $nim) {
        $status_pekerjaan = $request->input('status_pekerjaan');
        $keterangan = $request->input('keterangan');

        $put = Pekerjaan::find($nim);

        $put->status_pekerjaan = $status_pekerjaan;
        $put->keterangan = $keterangan;

        if ($put->save()) {
            $res['success'] = true;
            $res['message'] = 'Sukses Memperbarui!';
            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Gagal Memperbarui!';
            return response($res, 400);
        }
    }

    public function del_pekerjaan(Request $request, $nim) {
        $mhs_pekerjaan = Pekerjaan::find($nim);

        if ($mhs_pekerjaan->delete()) {
            $res['success'] = true;
            $res['message'] = 'Sukses Menghapus!';
            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Gagal Menghapus!';
            return response($res, 400);
        }
    }

    public function get_pekerjaan(Request $request, $nim) {
        $get_pekerjaan = Pekerjaan::where('nim', $nim)->get();
        if ($get_pekerjaan) {
            $res['success'] = true;
            $res['message'] = $get_pekerjaan;

            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Cannot find Pekerjaan!';

            return response($res, 400);
        }
    }

    public function get_all_pekerjaan(Request $request) {
        $get_pekerjaan = Pekerjaan::all();
        if ($get_pekerjaan) {
            $res['success'] = true;
            $res['message'] = $get_pekerjaan;

            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Cannot find Pekerjaan!';

            return response($res, 400);
        }
    }

    public function set_krisar(Request $request) {
        $nim = $request->input('nim');
        $isi_krisar = $request->input('isi_krisar');

        $set = Krisar::create([
                    'nim' => $nim,
                    'isi_krisar' => $isi_krisar
        ]);
        if ($set) {
            $res['success'] = true;
            $res['message'] = 'Sukses Menyimpan!';
            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Gagal Menyimpan!';
            return response($res, 400);
        }
    }

    public function put_krisar(Request $request, $nim) {
        $isi_krisar = $request->input('isi_krisar');

        $put = Krisar::find($nim);

        $put->isi_krisar = $isi_krisar;

        if ($put->save()) {
            $res['success'] = true;
            $res['message'] = 'Sukses Memperbarui!';
            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Gagal Memperbarui!';
            return response($res, 400);
        }
    }

    public function del_krisar(Request $request, $nim) {
        $krisar = Krisar::find($nim);

        if ($krisar->delete()) {
            $res['success'] = true;
            $res['message'] = 'Sukses Menghapus!';
            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Gagal Menghapus!';
            return response($res, 400);
        }
    }

    public function get_krisar(Request $request, $nim) {
        $krisar = Krisar::where('nim', $nim)->get();
        if ($krisar) {
            $res['success'] = true;
            $res['message'] = $krisar;

            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Cannot find Pekerjaan!';

            return response($res, 400);
        }
    }

    public function get_all_krisar(Request $request) {
        $krisar = Krisar::all();
        if ($krisar) {
            $res['success'] = true;
            $res['message'] = $krisar;

            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Cannot find Pekerjaan!';

            return response($res, 400);
        }
    }

    public function get_detail(Request $request, $nim) {
        $mhs = Mahasiswa::with('mahasiswa_login', 'akademik', 'pekerjaan', 'foto', 'krisar')->where('nim', $nim)->firstOrFail();
        if ($mhs) {
            $res['success'] = true;
            $res['message'] = $mhs;

            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Cannot find Mahasiswa!';

            return response($res, 400);
        }
    }

    public function import_excel(Request $request) {
        $rows = \Excel::load($request->file('mahasiswa'), function ($reader) {
                    
                })->get();

        $rowYangBerhasil = [];

        foreach ($rows as $key => $row) {
            if (!Mahasiswa::where('nim', $row->nim)->count()) {
                # insert mahasiswa
                Mahasiswa::create([
                    'nim' => $row->nim,
                    'nama' => $row->nama,
                    'email' => $row->email,
                    'alamat' => $row->alamat,
                    'no_telepon' => $row->no_telp,
                    'email' => $row->email,
                    'tempat_lahir' => $row->tempat_lahir,
                    'tanggal_lahir' => $row->tanggal_lahir
                ]);

                $rowYangBerhasil[] = $row;
            }

            if (!Akademik::find($row->nim)) {
                Akademik::create([
                    'nim' => $row->nim,
                    'prodi' => str_slug($row->prodi),
                    'angkatan_wisuda' => $row->angkatan_wisuda,
                    'tanggal_lulus' => $row->tanggal_lulus,
                    'nilai_ipk' => $row->nilai_ipk,
                ]);
            }
        }

        return response()->json([
            'rows' => $rowYangBerhasil
        ]);
    }

    public function index (Request $request) {

        $mhs = Mahasiswa::with('mahasiswa_login', 'akademik', 'pekerjaan', 'foto')->search($request)->orderBy('nim')->paginate(100)->appends($request->all());

        if ($request->has('export') && $request->export === 'excel') {
            return \Excel::create('mahasiswa', function ($excel) {
                        $excel->setTitle('Data Alumni Fakultas Teknik');
                        $excel->setCreator('Fakultas Teknik');

                        $excel->sheet('sheet 1', function ($sheet) {
                            $mahasiswa = Mahasiswa::with('mahasiswa_login', 'akademik', 'pekerjaan', 'krisar')->get();
                            $sheet->appendRow([
                                'Nim', 'Nama', 'Email', 'Alamat', 'No Telp', 'Email', 'Tempat Lahir', 'Tanggal Lahir',
                                'Prodi', 'Angkatan Wisuda', 'Tanggal Lulus', 'Nilai IPK',
                                'Status Pekerjaan', 'Keterangan', 'Kritik dan Saran'
                            ]);

                            foreach ($mahasiswa as $key => $row) {
                                $sheet->appendRow([
                                    $row->nim,
                                    $row->nama,
                                    $row->email,
                                    $row->alamat,
                                    $row->no_telepon,
                                    $row->email,
                                    $row->tempat_lahir,
                                    $row->tanggal_lahir,
                                    strtoupper($row->akademik->prodi),
                                    $row->akademik->angkatan_wisuda,
                                    $row->akademik->tanggal_lulus,
                                    $row->akademik->nilai_ipk,
                                    strtoupper($row->pekerjaan->status_pekerjaan),
                                    json_encode($row->pekerjaan->keterangan),
                                    $row->krisar->isi_krisar,
                                ]);
                            }
                        });
                    })->export('xlsx');
        }

        if ($mhs) {
            $res['success'] = true;
            $res['message'] = $mhs;

            return response($res);
        } else {
            $res['success'] = false;
            $res['message'] = 'Cannot find Mahasiswa!';

            return response($res, 400);
        }
    }

}
