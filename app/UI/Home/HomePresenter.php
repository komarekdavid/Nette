<?php
namespace App\UI\Home;

use App\Model\PostFacade;
use App\Model\CategoryFacade;
use App\Model\GameFacade;
use Nette;

final class HomePresenter extends Nette\Application\UI\Presenter
{
    public function __construct(
        private PostFacade $facade,
    ) {
    }

    public function renderDefault(): void
    {
        $this->template->posts = $this->facade
            ->getPublicArticles();
    }

}
