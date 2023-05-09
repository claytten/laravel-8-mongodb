<?php

namespace App\Models;

use App\Enums\PenjualanStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'penjualans';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kendaraan_id',
        'nama_pembeli',
        'alamat_pembeli',
        'harga_jual',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => PenjualanStatusEnum::class,
    ];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }
}
