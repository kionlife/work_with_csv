<?php

include 'core.php';

function getRange($ranges)
{
    $result = [];

    usort($ranges, function ($a, $b) {
        return strcmp($a['pickup'], $b['pickup']);
    });

    $currentRange = [];
    foreach ($ranges as $range) {

        if ($range['pickup'] >= $range['dropoff']) {
            continue;
        }

        if (empty($currentRange)) {
            $currentRange = $range;
            continue;
        }

        if ($currentRange['dropoff'] < $range['pickup']) {
            $result[] = $currentRange;
            $currentRange = $range;
        } elseif ($currentRange['dropoff'] < $range['dropoff']) {
            $currentRange ['dropoff'] = $range['dropoff'];
        }
    }

    if ($currentRange) {
        $result[] = $currentRange;
    }

    return $result;
}


function totalTime($trips, $key) {

    usort($trips, function($a, $b){
        return (strtotime($a['pickup']) - strtotime($b['pickup']));
    });

    $uniqueRange = getRange($trips);
    $total = 0;

    foreach ($uniqueRange as $range) {
        $total += (strtotime($range['dropoff']) - strtotime($range['pickup']))/60;
    }

    return [
        'driver_id'  => $key,
        'total_time' => $total
    ];
}

$result = array();
$csv = new CSV('data/trips.csv');
$data = $csv->getContent();
$result = array_map('totalTime', $data, array_keys($data));

$csv->saveContent('data/trips_new.csv', $result);

/*
1605001205
1605002345
*/