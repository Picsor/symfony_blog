<?php

namespace App\Enum;

enum SecurityQuestion : string
{
    case NameOfFirstPet = 'Quel est le nom de votre premier animal de compagnie ?';
    case BirthCity = 'Quelle est votre ville de naissance ?';
    case NameOfFavoriteTeacher = 'Comment s’appelait votre instituteur préféré ?';
}
