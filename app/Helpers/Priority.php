<?php

function getPriority($before, $after, $priorityMin, $priorityMax)
{
    $nextGlobal = $priorityMax + 1;
    $prevGlobal = $priorityMin - 1;
    $countAfter = count($after);
    $countBefore = count($before);
    $src = array_merge($before, $after);
    $direction = -1;

    if ($countAfter > 0) {
        $nextGlobal = $after[0]->priority;
    }
    if ($countBefore > 0) {
        $prevGlobal = $before[$countBefore - 1]->priority;
    }

    if ($nextGlobal - $prevGlobal == 1) {
        $emplaced = false;

        for ($i = $countBefore; $i < $countAfter + $countBefore && !$emplaced; ++$i) {
            $direction = 0;
            $next = $priorityMax + 1;
            if ($i < $countAfter + $countBefore - 1) {
                $next = $src[$i + 1]->priority;
            }

            $prev = $priorityMin - 1;
            if ($i >= 0) {
                $prev = $src[$i]->priority;
            }

            $src[$i]->priority = (int) (($next + $prev + 1 + $direction) / 2);

            if ($next - $prev > 1) {
                $emplaced = true;
                for (; $i >= $countBefore; --$i) {
                    $src[$i]->save();
                } 
            }
        }

        for ($i = $countBefore - 1; $i >= 0 && !$emplaced; --$i) {
            $direction = -1;
            $next = $priorityMax + 1;
            if ($i < $countAfter + $countBefore - 1) {
                $next = $src[$i]->priority;
            }

            $prev = $priorityMin - 1;
            if ($i > 0) {
                $prev = $src[$i - 1]->priority;
            }

            $src[$i]->priority = (int) (($next + $prev + 1 + $direction) / 2);

            if ($next - $prev > 1) {
                $emplaced = true;
                for (; $i < $countBefore; ++$i) {
                    $src[$i]->save();
                } 
            }
        }

        if (!$emplaced) {
            throw new Exception('Not enough space for priority');
        }
    }
    return (int) (($nextGlobal + $prevGlobal + 1 + $direction) / 2);
}
