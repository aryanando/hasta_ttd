<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TtdDataModel extends Model
{
    use HasFactory;
    protected $table = 'ttd_data';
    protected $fillable = ['nosurat','ttd','waktu','unique_id'];
    
}
