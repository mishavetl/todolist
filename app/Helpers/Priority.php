<?php

function getPriority($before, $after, $priorityMin, $priorityMax)
{
    $nextGlobal = $priorityMax + 1;
    $prevGlobal = $priorityMin - 1;
    $countAfter = count($after);
    $countBefore = count($before);

    if ($countAfter > 0) {
        $nextGlobal = $after[0]->priority;
    }
    if ($countBefore > 0) {
        $prevGlobal = $before[$countBefore - 1]->priority;
    }

    $priority = (int) (($nextGlobal + $prevGlobal) / 2);

    if ($nextGlobal - $prevGlobal <= 1) {
        $emplaced = false;

        for ($i = 0; $i < $countAfter && !$emplaced; ++$i) {
            $next = $priorityMax + 1;
            if ($i < $countAfter - 1) {
                $next = $after[$i + 1]->priority;
            }

            $prev = $after[$i]->priority;

            $after[$i]->priority = (int) (($next + $prev) / 2);

            if ($next - $prev > 1) {
                $emplaced = true;
                for (; $i >= 0; --$i) {
                    $next = $priorityMax + 1;
                    if ($i < $countAfter - 1) {
                        $next = $after[$i + 1]->priority;
                    }
        
                    $prev = $priorityMin - 1;
                    if ($i > 0) {
                        $prev = $after[$i - 1]->priority;
                    } else if ($countBefore > 0) {
                        $prev = $priority;
                    }
        
                    $after[$i]->priority = (int) (($next + $prev) / 2);
                    $after[$i]->save();
                } 
            }
        }

        for ($i = $countBefore - 1; $i >= 0 && !$emplaced; --$i) {
            $next = $before[$i]->priority;

            $prev = $priorityMin - 1;
            if ($i > 0) {
                $prev = $before[$i - 1]->priority;
            }

            $before[$i]->priority = (int) (($next + $prev) / 2);

            if ($next - $prev > 1) {
                $emplaced = true;
                for (; $i < $countBefore; ++$i) {
                    $next = $priorityMax + 1;
                    if ($i < $countBefore - 1) {
                        $next = $before[$i + 1]->priority;
                    } else if ($countAfter > 0) {
                        $next = $priority;
                    }
        
                    $prev = $priorityMin - 1;
                    if ($i > 0) {
                        $prev = $before[$i - 1]->priority;
                    }
        
                    $before[$i]->priority = (int) (($next + $prev) / 2);
                    $before[$i]->save();
                } 
            }
        }

        if (!$emplaced) {
            throw new Exception('Not enough space for priority');
        }
    }
    return $priority;
}
