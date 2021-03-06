<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Mahasiswa extends Model implements AuthenticatableContract, AuthorizableContract {

    use Authenticatable,
        Authorizable;

    public $primaryKey = 'nim';
    public $table = 'mahasiswa';
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nim', 'nama', 'email', 'alamat', 'no_telepon', 'tempat_lahir', 'tanggal_lahir'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function akademik() {
        return $this->hasOne('App\Akademik', 'nim');
    }

    public function foto() {
        return $this->hasOne('App\Foto', 'nim');
    }

    public function pekerjaan() {
        return $this->hasOne('App\Pekerjaan', 'nim');
    }

    public function krisar() {
        return $this->hasOne('App\Krisar', 'nim');
    }
    
    public function mahasiswa_login() {
        return $this->hasOne('App\Mahasiswa_Login', 'nim');
    }

    public function scopeSearch($query, $request)
    {
        if ($request->has('q')) {
            $query->where(function ($query) use ($request) {
                $query->orWhere('nim', 'like', "%{$request->q}%");
                $query->orWhere('nama', 'like', "%{$request->q}%");
            });
        }

        if ($request->has('prodi')) {
            $query->whereHas('akademik', function ($query) use ($request) {
                $query->where('prodi', 'like', "%{$request->prodi}%");
            });
        }

        if ($request->has('angkatan_wisuda')) {
            $query->whereHas('akademik', function ($query) use ($request) {
                $query->where('angkatan_wisuda', 'like', "%{$request->angkatan_wisuda}%");
            });
        }
    }

}
