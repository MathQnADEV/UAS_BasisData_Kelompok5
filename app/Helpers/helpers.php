<?php

function formatRupiah($number)
{
    return 'Rp ' . number_format($number, 0, ',', '.');
}
