<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $contact_id
 * @property int $user_id
 */
class Share extends Model
{
    use HasFactory;

    protected $fillable = [
        'contact_id',
        'user_id',
    ];

    public $timestamps = false;

    public function sharedContact() {
        return $this->belongsTo(Contact::class, 'contact_id', 'id');
    }

    public function sharedWith() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
