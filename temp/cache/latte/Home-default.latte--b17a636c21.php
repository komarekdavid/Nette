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
			foreach (array_intersect_key(['post' => '5'], $this->params) as $ʟ_v => $ʟ_l) {
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

		echo '    <h1>Můj blog</h1>

    <div class="post-list">
';
		foreach ($posts as $post) /* line 5 */ {
			echo '        <div class="post">
            <div class="post-meta">
                <span class="date">';
			echo LR\Filters::escapeHtmlText(($this->filters->date)($post->created_at, 'F j, Y')) /* line 7 */;
			echo '</span>
            </div>

            <h2 class="post-title">
                <a href="';
			echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link('Post:show', [$post->id])) /* line 11 */;
			echo '">';
			echo LR\Filters::escapeHtmlText($post->title) /* line 11 */;
			echo '</a>
            </h2>

            <div class="post-image">
';
			if ($post->image) /* line 15 */ {
				echo '                    <img src="';
				echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 16 */;
				echo '/';
				echo LR\Filters::escapeHtmlAttr($post->image) /* line 16 */;
				echo '" alt="Obrázek příspěvku" class="image">
';
			} else /* line 17 */ {
				echo '                    <img src="';
				echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 18 */;
				echo '/upload/image.png" alt="Výchozí obrázek" class="image-default">
';
			}
			echo '            </div>

            <div class="post-content">';
			echo LR\Filters::escapeHtmlText(($this->filters->truncate)($post->content, 256)) /* line 22 */;
			echo '</div>
        </div>
';

		}

		echo '    </div>

';
		if ($user->isLoggedIn()) /* line 26 */ {
			echo '    <a href="';
			echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link('Edit:create')) /* line 26 */;
			echo '" class="create-post-link">Vytvořit příspěvek</a>
';
		}
	}
}
