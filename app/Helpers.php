<?php

use App\Exceptions\CustomException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;

/**
 * Cambia la conexión de la bbdd programaticamente
 */
if (!function_exists('ChangeDatabase')) {
    function ChangeDatabase($host, $port, $db, $user, $password)
    {
        Config::set("database.connections.db", array(
            'driver'    => 'mysql',
            'host'      => $host,
            'port'      => $port,
            'database'  => $db,
            'username'  => $user,
            'password'  => $password,
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ));
    }
}

/**
 * Actualiza la información de un modelo
 */
if (!function_exists('DataUpdate')) {
    function DataUpdate($model, $request, $disabled = [])
    {
        foreach ($model->toArray() as $k => $v) {
            $e = false;

            foreach ($disabled as $d) {
                $e = $e || $d == $k;
            }

            if (!$e) {
                foreach ($request as $key => $value) {
                    if ($key == $k) {
                        $model[$k] = $value;
                    }
                }
            }
        }

        return $model;
    }
}

/**
 * Realiza una validación y lanza el error en caso este falle
 */
if (!function_exists('CheckValidate')) {
    function CheckValidate($data, $rules, $messages)
    {
        $validator = Validator::make($data, $rules, $messages);

        if ($validator->fails()) {
            $errors = $validator->errors()->all()[0];

            throw new CustomException(
                $errors,
                402
            );
        }
    }
}

/**
 * Verifica que un array cuenta con registros
 */
if (!function_exists('CheckArray')) {
    /**
     * @param array $array, Array que será verificado
     * @param string $message, Mensaje personalizado opcional
     */
    function CheckArray(array $array, string $message = null)
    {
        if (empty($array)) {
            throw new CustomException(
                $message ?? 'No se han encontrado registros',
                400
            );
        }
    }
}

/**
 * Verifica que un modelo exista
 */
if (!function_exists('CheckModel')) {
    /**
     * @param array $array, Array que será verificado
     * @param string $message, Mensaje personalizado opcional
     */
    function CheckModel($model, $message = null)
    {
        if (!$model) {
            throw new CustomException(
                $message ?? 'No se han encontrado el registro',
                400
            );
        }
    }
}

/**
 * Convierte un string array en un array
 */
if (!function_exists('ConvertStringArrayToArray')) {
    /**
     * @param array $array, Array que será verificado
     * @param string $message, Mensaje personalizado opcional
     */
    function ConvertStringArrayToArray($string)
    {
        if (gettype($string) === 'string') {
            $sub = substr($string, 1, strlen($string) - 2);
            $sub = explode(',', $sub);

            $newArray = [];
            foreach ($sub as $s) {
                array_push($newArray, trim($s));
            }

            return $newArray;
        }

        return $string;
    }
}

/**
 * Lanza un Bad Request exception
 */
if (!function_exists('ThrowBadRequest')) {
    /**
     * Throw a Bad request exception with status code: 400
     * @param $message, message to send
     */
    function ThrowBadRequest($message, $detailException = null)
    {
        throw new CustomException(
            $message,
            400,
            $detailException ?? 'No details'
        );
    }
}

/**
 * Lanza una execpcion
 */
if (!function_exists('ThrowException')) {
    /**
     * Throw new exception
     * @para $status, status code
     * @param $message, message to send
     * @param $detailException, details exception
     */
    function ThrowException($status, $message, $detailException = null)
    {
        throw new CustomException(
            $message,
            $status,
            $detailException ?? 'No details'
        );
    }
}
