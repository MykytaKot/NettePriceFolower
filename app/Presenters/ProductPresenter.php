<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Models\ProductLoader;
use App\Models\PriceFollowLoader;
use Nette\Application\UI\Form;
use Nette;


final class ProductPresenter extends Nette\Application\UI\Presenter
{
    private $shopLoader;
    private $pricefollowLoader;
    public function __construct()
    {
        $this->shopLoader = new ProductLoader();
        $this->pricefollowLoader = new PriceFollowLoader();
    }
    protected function createComponentRegistrationForm(): Form
	{
		$form = new Form;
		$form->addEmail('email', 'Name:');
        $form->addText('ean', 'product');
		$form->addText('change', 'Password:');
		$form->addSubmit('send', 'Follow');
		$form->onSuccess[] = [$this, 'formSucceeded'];
		return $form;
	}
    
	public function formSucceeded(Form $form, $data): void
	{
		$message = $this->pricefollowLoader->addFollower($data);
		$this->flashMessage($message);
	
	}
    public function renderDefault($ean): void
    {
        $data = $this->shopLoader->get_product($ean);
        
        $this->template->product = $data['product'];
        $this->template->shops = $data['shops'];
    }
}
