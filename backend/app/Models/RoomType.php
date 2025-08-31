<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    /** @use HasFactory<\Database\Factories\RoomTypeFactory> */
    use HasFactory , HasUlids;

    protected $table = "room_types";

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'bed_count',
    ];

    public function rooms(){
        return $this->hasMany(Room::class , 'room_type_id' , 'id');
    }

    public function inventory(){
        return $this->hasMany(Inventory::class);
    }

}
