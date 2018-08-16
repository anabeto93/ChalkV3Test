<?php
function getTokenFromBearer(string $bearer){
    return explode('~', $bearer)[0];
}

function getTokenLastUsedFromBearer(string $bearer){
    $timeString = explode('~', $bearer)[1];
    return Carbon\Carbon::createFromTimestamp($timeString);
}
