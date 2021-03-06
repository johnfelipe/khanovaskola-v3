<?php

namespace App\Presenters;

use App\Model\Video;
use Nette\Application\Responses\TextResponse;


final class SrtPresenter extends BasePresenter
{

	/**
	 * @persistent
	 * @var int
	 */
	public $videoId;

	/** @var Video */
	public $video;

	public function startup()
	{
		parent::startup();

		$this->video = $this->orm->videos->getById($this->videoId);
		if (!$this->video)
		{
			$this->error();
		}
	}

	public function actionDefault()
	{
		$this->sendResponse(new TextResponse($this->video->getSubtitles()));
	}

}
