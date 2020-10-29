<?php

namespace App\Models\SalidaFe;

use Illuminate\Database\Eloquent\Model;

class TxSalidac extends Model
{
    protected $table = 'tx_salidac';

    protected $primaryKey = 'ccodinte';

    protected $keyType = 'string';

    public $timestamps = false;
}
