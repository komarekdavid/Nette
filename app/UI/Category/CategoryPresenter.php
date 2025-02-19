<?php
namespace App\UI\Category;

use App\Model\CategoryFacade;
use Nette;
use Nette\Application\UI\Form;

final class CategoryPresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private CategoryFacade $categoryFacade,
    ) {
    }

    
    public function renderDefault(): void
    {
        $this->template->categories = $this->categoryFacade->getCategories();
    }
    

    public function renderEdit(int $id): void
    {
        $category = $this->categoryFacade->getCategoryById($id);
        if (!$category) {
            $this->error('Kategorie nebyla nalezena');
        }

        $this['categoryForm']->setDefaults([
            'name' => $category->name,
            'description' => $category->description,
        ]);

        $this->template->category = $category;
    }

    public function renderCreate(): void
    {
        // Pouze pro vykreslení formuláře
    }

    protected function createComponentCategoryForm(): Form
    {
        $form = new Form;
        $form->addText('name', 'Název')
            ->setRequired()
            ->addRule(Form::MAX_LENGTH, 'Maximální délka je 255 znaků.', 255);

        $form->addTextArea('description', 'Popis')
            ->setHtmlAttribute('class', 'category-textarea');

        $form->addSubmit('save', 'Uložit')
            ->setHtmlAttribute('class', 'category-button');

        $form->onSuccess[] = function (Form $form, \stdClass $values) {
            if ($this->getParameter('id')) {
                $this->categoryFacade->updateCategory($this->getParameter('id'), $values->name, $values->description);
                $this->flashMessage('Kategorie byla upravena.', 'success');
            } else {
                $this->categoryFacade->addCategory($values->name, $values->description);
                $this->flashMessage('Kategorie byla přidána.', 'success');
            }

            $this->redirect('default');
        };

        return $form;
    }

    public function handleDelete(int $id): void
    {
        $this->categoryFacade->deleteCategory($id);
        $this->flashMessage('Kategorie byla odstraněna.', 'success');
        $this->redirect('default');
    }
}
