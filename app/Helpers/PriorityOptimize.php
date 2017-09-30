<?php

function optimizePriority($objs, $priorityMin, $priorityMax)
{
    $n = count($objs) + 1;
    $shift = (int) ((int) ((abs($priorityMax) + 1) / ($n)) + (int) (abs($priorityMin) / ($n)));
    $priority = (int) ($priorityMin + (int) $shift);
    foreach ($objs as $obj) {
        $obj->priority = (int) $priority;
        $obj->save();
        $priority += $shift;
    }
}