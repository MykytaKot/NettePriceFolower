<?php
namespace App\Models;
use Nette;

interface ShopInterface
{
    public function LoadProducts();
    public function getAll();
}