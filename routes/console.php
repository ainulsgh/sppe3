<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment('Tetap semangat!');
})->purpose('Tampilkan pesan motivasi');
