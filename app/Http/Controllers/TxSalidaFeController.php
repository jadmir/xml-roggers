<?php

namespace App\Http\Controllers;

use App\Models\SalidaFe\TxSalidacRepository;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class TxSalidaFeController extends Controller
{
    protected $txSalidacRepository;

    public function __construct(TxSalidacRepository $txSalidacRepository)
    {
        $this->txSalidacRepository = $txSalidacRepository;
    }

    /**
     * Realiza un explode sobre el nombre del archivo
     * y retorna el registro del documento en caso exista
     */
    private function findDocument($fileName)
    {
        $name = str_replace('.zip', '', $fileName);
        $arrName = explode('-', $name);

        $ruc = $arrName[0];
        $ctipdocu = $arrName[1];
        $cserdocu = $arrName[2];
        $cnumdocu = $arrName[3];

        $document = $this->txSalidacRepository->findDocument($ctipdocu, $cserdocu, $cnumdocu);

        return [$document, $ruc, $ctipdocu, $cserdocu, $cnumdocu];
    }

    /**
     * Carga archivos xml hacia el servidor,
     * y actualiza la URL del xml del respectivo registro
     */
    public function uploadXmlFileS3()
    {
        $hasFile = request()->hasFile('files');

        if (!$hasFile) {
            return response([
                'message' => 'Debe seleccionar archivos válidos',
            ], 400);
        }

        $folderFiles = 'CAMISAS-ROGGERS';

        foreach (request()->file('files') as $file) {
            $name = $file->getClientOriginalName();

            list($document, $ruc, $ctipdocu, $cserdocu) = $this->findDocument($name);

            if ($document) {
                $folderName = "{$folderFiles}/$ruc/$ctipdocu/$cserdocu";
                $uploaded = Storage::disk('s3')->put("{$folderName}/{$name}", File::get($file));
                $url =  Storage::disk('s3')->url("{$folderName}/{$name}");

                if ($uploaded) {
                    $success[] = $url;
                    $this->txSalidacRepository->updateXmlURL($document, $url);
                } else {
                    $failed[] = $name;
                }
            } else {
                $failed[] = $name;
            }
        }

        return response([
            'message' => 'Upload files',
            'success' => $success ?? [],
            'failed' => $failed ?? []
        ], 200);
    }
}
