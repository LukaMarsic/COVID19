<?php

class Funkcije
{

    public static function escapeSingleQuote($s)
    {
        return str_replace('\'','\\\'',$s);
    }
}