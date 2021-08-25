<?php

namespace App\Models;

use Carbon\Traits\Date;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string $phone
 * @property int $creator_id
 * @property Date $created_at
 * @property Date $updated_at
 */
class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'creator_id',
    ];

    public function creator() {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }

    public function receivers() {
        return $this->belongsToMany(User::class, 'shares');
    }

    public function contactShares() {
        return $this->hasMany(Share::class, 'contact_id', 'id');
    }

}
