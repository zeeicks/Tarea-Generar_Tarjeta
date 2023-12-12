<?php

function validarRut($rut) {
    $rut = preg_replace('/[\.\-]/', '', $rut);

    if (strlen($rut) < 8 || strlen($rut) > 9) {
        return false;
    }

    $dv = strtoupper(substr($rut, -1));
    $rut = substr($rut, 0, -1);

    if (!ctype_digit($rut) || !ctype_alnum($dv)) {
        return false;
    }

    $sum = 0;
    $mul = 2;

    for ($i = strlen($rut) - 1; $i >= 0; $i--) {
        $sum += intval($rut[$i]) * $mul;

        if ($mul == 7) {
            $mul = 2;
        } else {
            $mul++;
        }
    }

    $mod = $sum % 11;
    $computedDV = ($mod == 0) ? 0 : 11 - $mod;

    if ($computedDV == 10) {
        $computedDV = 'K';
    }

    return $dv == $computedDV;
}

?>
