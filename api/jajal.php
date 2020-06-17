<?php

$star_icon='';
$max_star=5;

$star = 5.0;

$star_lenght = floor($star);

// for solid
for($i=0; $i<$star_lenght;)
{
    $star_icon = $star_icon.'*';

    $i++;
}


if($star>$star_lenght )
{
    $star_icon = $star_icon.'/';
    $max_star = $max_star-1;
}
if($star<5)
{
    $selisih = $max_star-$star_lenght;

    for($i=0; $i<$selisih;)
    {
        $star_icon = $star_icon.'o';
        $i++;
    }
}



echo $star_icon;

?>