<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Motor extends Model
{
    use HasFactory, SoftDeletes;

    const MotorTypeModel = 'App\\Models\\Motor';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'motors';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'mesin',
        'suspensi',
        'transmisi',
    ];

    public function kendaraan()
    {
        return $this->morphOne(Kendaraan::class, 'kendaraanable');
    }
}
