<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Models\DatabaseConnect;

use Nette;
use Nette\Application\UI\Form;


final class ShopPresenter extends Nette\Application\UI\Presenter
{
    private $database;
    public function __construct()
    {
        $this->database = new DatabaseConnect();
    }
    protected function createComponentAddShopForm(): Form
	{
		$form = new Form;
		$form->addText('shop', 'Store');
		$form->addSubmit('send', 'Add');
		$form->onSuccess[] = [$this, 'formSucceeded'];
		return $form;
	}
    protected function createComponentDeleteShopForm(): Form
	{
		$form = new Form;
		$form->addText('shop', 'Store');
		$form->addSubmit('send', 'Delete');
		$form->onSuccess[] = [$this, 'formDeleteSucceeded'];
		return $form;
	}
    public function formSucceeded(Form $form, $data): void
	{
		$this->database->AddShop($data['shop']);
		$this->flashMessage("Added Shop");
	
	}
    public function formDeleteSucceeded(Form $form, $data): void
	{
		$this->database->DeleteShop($data['shop']);
		$this->flashMessage("Deleted Shop");
	
	}
    public function renderDefault(): void
    {
            $this->template->shops = $this->database->getStoreNames();
        
    }
    
}
