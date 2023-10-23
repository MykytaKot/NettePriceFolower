<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Models\ShopLoader;
use Nette;


final class ProductPresenter extends Nette\Application\UI\Presenter
{
    private $shopLoader;
    public function __construct()
    {
        $this->shopLoader = new ShopLoader();
    }
    public function renderDefault($ean): void
    {
        $data = $this->shopLoader->get_product($ean);
        $this->template->product = $data;
    }
}