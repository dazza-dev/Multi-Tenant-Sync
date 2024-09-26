<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'db_host',
        'db_port',
        'db_database',
        'db_username',
        'db_password',
        'raw_query',
        'bindings',
    ];

    protected function casts(): array
    {
        return [
            'db_host' => 'encrypted',
            'db_database' => 'encrypted',
            'db_username' => 'encrypted',
            'db_password' => 'encrypted',
            'raw_query' => 'encrypted',
            'bindings' => 'collection',
        ];
    }

    public function jobs()
    {
        return $this->hasMany(JobExecution::class, 'project_id');
    }
}
