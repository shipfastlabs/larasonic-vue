<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/* CREATE TABLE laravel.document_locations (
    id                   int  NOT NULL  AUTO_INCREMENT  PRIMARY KEY,
    birth_certificate    varchar(255)      ,
    form_138             varchar(255)      ,
    form_137             varchar(255)      ,
    good_moral_cert      varchar(255)      ,
    transfer_credentials varchar(255)      ,
    transcript_records   varchar(255)      ,
    picture_1x1          varchar(255)
 );

 */
class document_locations extends Model
{
    use HasFactory;

    protected $table = 'document_locations';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'birth_certificate',
        'form_138',
        'form_137',
        'good_moral_cert',
        'transfer_credentials',
        'transcript_records',
        'picture_1x1',

    ];

    protected $hidden = [
        'id',
    ];

    protected $casts = [
        'birth_certificate' => 'string',
        'form_138' => 'string',
        'form_137' => 'string',
        'good_moral_cert' => 'string',
        'transfer_credentials' => 'string',
        'transcript_records' => 'string',
        'picture_1x1' => 'string',
    ];
}
