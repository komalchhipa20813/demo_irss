<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgentDocuments extends Model 
{
    use HasFactory;
    protected $guarded = [];
    public function documents_type()
    {
        return $this->hasOne(DocumentType::class, 'id', 'document_type')->where('status',1);
    }
}
