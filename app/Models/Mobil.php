<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Mobil extends Model
{
    use HasFactory, SoftDeletes;

    const MobilTypeModel = 'App\\Models\\Mobil';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mobils';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mesin',
        'kapasitas_penumpang',
        'tipe',
    ];

    public function kendaraan()
    {
        return $this->morphOne(Kendaraan::class, 'kendaraanable');
    }
}
