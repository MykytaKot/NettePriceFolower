<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Models\ShopLoader;
use Nette;


final class HomePresenter extends Nette\Application\UI\Presenter
{
    private $shopLoader;
    public function __construct()
    {
        $this->shopLoader = new ShopLoader();
    }
    public function renderDefault(): void
    {
      
        $this->template->products = $this->shopLoader->get_all_lowest_price();
    }
}
