<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Contact;
use App\Models\Address;


class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id', 'number', 'created_at', 'updated_at'
    ];

    public function numbers()
    {
        return $this->hasMany(Contact::class, 'customer_id');
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'customer_id');
    }
}
