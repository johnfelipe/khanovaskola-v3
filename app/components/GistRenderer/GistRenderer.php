<?php

namespace App\Components;

use App\Model\Gist;


class GistRenderer extends Control
{

	/** @var Gist */
	private $gist;

	/** @var bool */
	private $editable = FALSE;

	public function setGist(Gist $gist)
	{
		$this->gist = $gist;
	}

	public function setEditable($bool = TRUE)
	{
		$this->editable = $bool;
	}

	protected function renderDefault()
	{
		$this->template->gist = $this->gist;
		$this->template->editable = $this->editable;
	}

	public function handleSave()
	{
		if (!$this->presenter->isAjax() || !$this->editable)
		{
			$this->presenter->error();
		}
		$text = $this->presenter->getRequest()->getPost()['text'];
		$this->gist->text = $text;
		$this->gist->model->flush();
		$this->presenter->sendJson(['saved' => TRUE]);
	}

}
