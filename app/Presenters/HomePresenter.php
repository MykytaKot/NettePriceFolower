<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Models\ProductLoader;
use Nette;


final class HomePresenter extends Nette\Application\UI\Presenter
{
    private $shopLoader;
    public function __construct()
    {
        $this->shopLoader = new ProductLoader();
    }
    public function renderDefault(): void
    {
      
        $this->template->products = $this->shopLoader->get_all_lowest_price(1);
        $this->template->pagination = $this->shopLoader->get_pagination_data();
        $this->template->currentpage = 1;
    }
    public function renderPaged($page): void
    {
      
        $this->template->products = $this->shopLoader->get_all_lowest_price($page);
        $this->template->pagination = $this->shopLoader->get_pagination_data();
        $this->template->currentpage = $page;
    }
}
