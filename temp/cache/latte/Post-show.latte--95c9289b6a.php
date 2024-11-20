<?php

declare(strict_types=1);

use Latte\Runtime as LR;

/** source: /home/david/github-classroom/ossp-cz/wp3-01-komarekdavid/app/UI/Post/show.latte */
final class Template_95c9289b6a extends Latte\Runtime\Template
{
	public const Source = '/home/david/github-classroom/ossp-cz/wp3-01-komarekdavid/app/UI/Post/show.latte';

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
			foreach (array_intersect_key(['comment' => '20'], $this->params) as $ʟ_v => $ʟ_l) {
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
<p><a href="';
		echo LR\Filters::escapeHtmlAttr($this->global->uiControl->link('Home:default')) /* line 3 */;
		echo '">← zpět na výpis příspěvků</a></p>

<div class="date">';
		echo LR\Filters::escapeHtmlText(($this->filters->date)($post->created_at, 'F j, Y')) /* line 5 */;
		echo '</div>

';
		$this->renderBlock('title', get_defined_vars()) /* line 7 */;
		echo '
<div class="post">';
		echo LR\Filters::escapeHtmlText($post->content) /* line 9 */;
		echo '</div>
 

 
<h2>Vložte nový příspěvek</h2>

';
		$ʟ_tmp = $this->global->uiControl->getComponent('commentForm');
		if ($ʟ_tmp instanceof Nette\Application\UI\Renderable) $ʟ_tmp->redrawControl(null, false);
		$ʟ_tmp->render() /* line 15 */;

		echo '
<h2>Komentáře</h2>

<div class="comments">
';
		foreach ($comments as $comment) /* line 20 */ {
			echo '		<p><b>';
			$ʟ_tag[0] = '';
			if ($comment->email) /* line 21 */ {
				echo '<';
				echo $ʟ_tmp = 'a' /* line 21 */;
				$ʟ_tag[0] = '</' . $ʟ_tmp . '>' . $ʟ_tag[0];
				echo ' href="mailto:';
				echo LR\Filters::escapeHtmlAttr($comment->email) /* line 21 */;
				echo '">';
			}
			echo '
			';
			echo LR\Filters::escapeHtmlText($comment->name) /* line 22 */;
			echo '
		';
			echo $ʟ_tag[0];
			echo '</b> napsal:</p>

		<div>';
			echo LR\Filters::escapeHtmlText($comment->content) /* line 25 */;
			echo '</div>
';

		}

		echo '</div>
';
	}


	/** n:block="title" on line 7 */
	public function blockTitle(array $ʟ_args): void
	{
		extract($this->params);
		extract($ʟ_args);
		unset($ʟ_args);

		echo '<h1>';
		echo LR\Filters::escapeHtmlText($post->title) /* line 7 */;
		echo '</h1>
';
	}
}
