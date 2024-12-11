<?php

declare(strict_types=1);

use Latte\Runtime as LR;

/** source: /home/david/github-classroom/ossp-cz/Nette/app/UI/Post/show.latte */
final class Template_ef31bd495b extends Latte\Runtime\Template
{
	public const Source = '/home/david/github-classroom/ossp-cz/Nette/app/UI/Post/show.latte';

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


	public function prepare(): array
	{
		extract($this->params);

		if (!$this->getReferringTemplate() || $this->getReferenceType() === 'extends') {
			foreach (array_intersect_key(['comment' => '33'], $this->params) as $ʟ_v => $ʟ_l) {
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

		echo '
<div class="post-detail">
	<p><a href="';
		echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link('Home:default')) /* line 4 */;
		echo '" class="back-link">← zpět na výpis příspěvků</a></p>

	<div class="post-meta">
		<span class="date">';
		echo LR\Filters::escapeHtmlText(($this->filters->date)($post->created_at, 'F j, Y')) /* line 7 */;
		echo '</span>
	</div>

';
		$this->renderBlock('title', get_defined_vars()) /* line 10 */;
		echo '
	<div class="post-content">';
		echo LR\Filters::escapeHtmlText($post->content) /* line 12 */;
		echo '</div>

	<div class="post-image">
';
		if ($post->image) /* line 15 */ {
			echo '			<img src="';
			echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 16 */;
			echo '/';
			echo LR\Filters::escapeHtmlAttr($post->image) /* line 16 */;
			echo '" alt="Obrázek k článku ';
			echo LR\Filters::escapeHtmlAttr($post->title) /* line 16 */;
			echo '">
			<br>
			<a href="';
			echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link('deleteImage!', [$post->id])) /* line 18 */;
			echo '" class="delete-image-link">Smazat obrázek</a>
';
		} else /* line 19 */ {
			echo '			<img src="';
			echo LR\Filters::escapeHtmlAttr(LR\Filters::safeUrl($basePath)) /* line 20 */;
			echo '/upload/image.png" alt="Výchozí obrázek">
';
		}
		echo '	</div>

	<div class="post-actions">
		<a href="';
		echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link('Edit:edit', [$post->id])) /* line 25 */;
		echo '" class="edit-link">Upravit příspěvek</a>
	</div>
</div>

<div class="comments-section">
	<h2>Komentáře</h2>

	<div class="comments">
';
		foreach ($comments as $comment) /* line 33 */ {
			echo '			<div class="comment" id="comment-';
			echo LR\Filters::escapeHtmlAttr($comment->id) /* line 34 */;
			echo '">
				<p><b>';
			$ʟ_tag[0] = '';
			if ($comment->email) /* line 35 */ {
				echo '<';
				echo $ʟ_tmp = 'a' /* line 35 */;
				$ʟ_tag[0] = '</' . $ʟ_tmp . '>' . $ʟ_tag[0];
				echo ' href="mailto:';
				echo LR\Filters::escapeHtmlAttr($comment->email) /* line 35 */;
				echo '" class="comment-author">';
			}
			echo '
					';
			echo LR\Filters::escapeHtmlText($comment->name) /* line 36 */;
			echo '
				';
			echo $ʟ_tag[0];
			echo '</b> napsal:</p>

				<div class="comment-content">';
			echo LR\Filters::escapeHtmlText($comment->content) /* line 39 */;
			echo '</div>

';
			if ($user) /* line 41 */ {
				echo '					<a href="';
				echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link('deleteComment!', [$comment->id])) /* line 42 */;
				echo '" class="delete-comment-link">Smazat komentář</a>
';
			}
			echo '			</div>
';

		}

		echo '	</div>
</div>

<div class="comment-form-section">
	<h2>Vložte nový příspěvek</h2>
';
		$ʟ_tmp = $this->global->uiControl->getComponent('commentForm');
		if ($ʟ_tmp instanceof Nette\Application\UI\Renderable) $ʟ_tmp->redrawControl(null, false);
		$ʟ_tmp->render() /* line 51 */;

		echo '</div>




';
	}


	/** n:block="title" on line 10 */
	public function blockTitle(array $ʟ_args): void
	{
		extract($this->params);
		extract($ʟ_args);
		unset($ʟ_args);

		echo '	<h1 class="post-title">';
		echo LR\Filters::escapeHtmlText($post->title) /* line 10 */;
		echo '</h1>
';
	}
}
