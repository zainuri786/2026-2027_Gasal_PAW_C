<?php
function setheight($minheight = 50) {
    echo "The height is : $minheight <br>";
}

setheight(350);
setheight(); // Akan menampilkan nilai default 50
setheight(135);
?>