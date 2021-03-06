<?php

namespace Commands\Backup;

use Commands\Command as BaseCommand;


abstract class Command extends BaseCommand
{

	protected function cleanUp()
	{
		unlink($this->getTempNameDb());
		rmdir($this->getTempDir());
	}

	protected function getDir()
	{
		return $this->container->parameters['backupDir'];
	}

	/**
	 * @return string dir path
	 */
	protected function getTempDir()
	{
		$dir = $this->getDir() . '/.temp';
		if (!file_exists($dir))
		{
			mkdir($dir);
		}

		return $dir;
	}

	protected function getNameDb()
	{
		return 'database.sql';
	}

	/**
	 * @return string file path
	 */
	protected function getTempNameDb()
	{
		return $this->getTempDir() . '/' . $this->getNameDb();
	}


}

