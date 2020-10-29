<?php

namespace App\Models\SalidaFe;

class TxSalidacRepository
{
    protected $txSalidac;

    public function __construct(TxSalidac $txSalidac)
    {
        $this->txSalidac = $txSalidac;
    }

    /**
     * Obtiene un registro a partir de
     * @param $ctipdocu
     * @param $cserdocu
     * @param $cnumdocu
     */
    public function findDocument($ctipdocu, $cserdocu, $cnumdocu)
    {
        $document = $this->txSalidac->where('ctipdocu', $ctipdocu)
            ->where('cserdocu', $cserdocu)
            ->where('cnumdocu', $cnumdocu)
            ->first();

        return $document;
    }

    /**
     * Actualiza la ruta del archivo xml de un registro
     * @param TxSalidac $document, documento a ser actualizado
     * @param string $xmlURL, ruta del archivo zip
     */
    public function updateXmlURL(TxSalidac $txSalidac, string $xmlURL): bool
    {
        $txSalidac->xml_firmado = $xmlURL;
        return $txSalidac->save();
    }
}
