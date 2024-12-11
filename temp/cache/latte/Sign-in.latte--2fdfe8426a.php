<?php

declare(strict_types=1);

use Latte\Runtime as LR;

/** source: /home/david/github-classroom/ossp-cz/Nette/app/UI/Sign/in.latte */
final class Template_2fdfe8426a extends Latte\Runtime\Template
{
	public const Source = '/home/david/github-classroom/ossp-cz/Nette/app/UI/Sign/in.latte';

	public const Blocks = [
		['content' => 'blockContent', 'title' => 'blockTitle'],
	];


	public function main(array $ʟ_args): void
	{
		extract($ʟ_args);
		unset($ʟ_args);

		if ($this->global->snippetDriver?->renderSnippets($this->blocks[self::LayerSnippet], $this->params)) {
			return;
		}

		$this->renderBlock('content', get_defined_vars()) /* line 1 */;
	}


	/** {block content} on line 1 */
	public function blockContent(array $ʟ_args): void
	{
		extract($this->params);
		extract($ʟ_args);
		unset($ʟ_args);

		$this->renderBlock('title', get_defined_vars()) /* line 2 */;
		echo "\n";
		$ʟ_tmp = $this->global->uiControl->getComponent('signInForm');
		if ($ʟ_tmp instanceof Nette\Application\UI\Renderable) $ʟ_tmp->redrawControl(null, false);
		$ʟ_tmp->render() /* line 4 */;
	}


	/** n:block="title" on line 2 */
	public function blockTitle(array $ʟ_args): void
	{
		echo '<h1>Přihlášení</h1>
';
	}
}
