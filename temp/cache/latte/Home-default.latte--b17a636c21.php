<?php

declare(strict_types=1);

use Latte\Runtime as LR;

/** source: /home/david/github-classroom/ossp-cz/Nette/app/UI/Home/default.latte */
final class Template_b17a636c21 extends Latte\Runtime\Template
{
	public const Source = '/home/david/github-classroom/ossp-cz/Nette/app/UI/Home/default.latte';

	public const Blocks = [
		['content' => 'blockContent'],
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


	public function prepare(): array
	{
		extract($this->params);

		if (!$this->getReferringTemplate() || $this->getReferenceType() === 'extends') {
			foreach (array_intersect_key(['post' => '4'], $this->params) as $ʟ_v => $ʟ_l) {
				trigger_error("Variable \$$ʟ_v overwritten in foreach on line $ʟ_l");
			}
		}
		return get_defined_vars();
	}


	/** {block content} on line 1 */
	public function blockContent(array $ʟ_args): void
	{
		extract($this->params);
		extract($ʟ_args);
		unset($ʟ_args);

		echo '	<h1>Můj blog</h1>

';
		foreach ($posts as $post) /* line 4 */ {
			echo '	<div class="post">
		<div class="date">';
			echo LR\Filters::escapeHtmlText(($this->filters->date)($post->created_at, 'F j, Y')) /* line 5 */;
			echo '</div>

		<h2><a href="';
			echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link('Post:show', [$post->id])) /* line 7 */;
			echo '">';
			echo LR\Filters::escapeHtmlText($post->title) /* line 7 */;
			echo '</a></h2>

		<div>';
			echo LR\Filters::escapeHtmlText(($this->filters->truncate)($post->content, 256)) /* line 9 */;
			echo '</div>
	</div>
';

		}

		if ($user->isLoggedIn()) /* line 11 */ {
			echo '	<a href="';
			echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link('Edit:create')) /* line 11 */;
			echo '">Vytvořit příspěvek</a>
';
		}
		echo "\n";
	}
}
