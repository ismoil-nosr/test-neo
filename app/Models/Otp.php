<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $hash
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Otp extends Model
{
    use HasFactory;
}
