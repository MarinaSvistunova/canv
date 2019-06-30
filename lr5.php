<?php
    
    header('Access-Control-Allow-Origin: *');
    header('Content-type: text/plain; charset=utf-8');
    header('Access-Control-Allow-Methods: GET,POST,DELETE');
    // Svistunova Marina
    require_once 'lib/imagettf.php';
    $w = 700;
    $h = 220;
    $ctx = imageCreate($w, $h);
    $white = imageColorAllocate($ctx, 255, 255, 255);
    $red = imageColorAllocate($ctx, 255, 0, 0);
    $blue = imageColorAllocate($ctx, 0, 0, 255);
    $rates = json_decode(file_get_contents('https://kodaktor.ru/j/rates'));
    $names = array_map(function($x){return $x -> name;}, $rates);
    $rates = array_map(function($x){return $x -> sell;}, $rates);
    $maxEl = max($rates);
    $font = getcwd().'/OpenSans.ttf';
    $rates = array_map(function($x) use ($h, $maxEl) {return $x*$h/$maxEl;}, $rates);
    $wRect = floor($w/count($names));
    $fnc = function() use ($wRect, $font, $ctx, $h, $red, $blue) {
      $size = imageTtfGetMaxSize(0, $font, 'Marina Svistunova', $wRect, 20);
      imageTtfText($ctx, $size, 0, 40,40, $blue, $font, 'Marina Svistunova');
    };
    $fnc();
    array_walk($rates, function($x, $i) use ($wRect, $font, $ctx, $h, $red, $blue, $names) {
      $size = imageTtfGetMaxSize(0, $font, $names[$i], $wRect, 20);
      imageFilledRectangle($ctx, 2*($i) + $i * $wRect, $h - $x, 2*($i) + ($i+1)*$wRect, $h, $red);
      imageTtfText($ctx, $size, 0, 2*($i) + $i * $wRect, $h - 5, $blue, $font, $names[$i]);
    });
    header('Content-type: image/png');
    imagePng($ctx);
