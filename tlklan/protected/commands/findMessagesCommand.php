<?php

/**
 * Searches for translatable messages and inserts them into the database
 *
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class findMessagesCommand extends CConsoleCommand
{

	/**
	 * @var string the directory to search under. Defaults to the application
	 * base path.
	 */
	public $rootDirectory;

	/**
	 * @var string the method name to search for. Default to "Yii::t".
	 */
	public $translateFunction = 'Yii::t';

	/**
	 * @var array list of files extensions to search for tags in
	 */
	public $extensions = array('php');

	/**
	 * @var array list of files/directories to exclude from the search
	 */
	public $exclude = array('tests', 'extensions', 'cms', 'vendors');

	/**
	 * @var boolean whether to include core messages ("yii" category) in the 
	 * search. Defaults to false.
	 */
	public $includeCoreMessages = false;

	/**
	 * @var array list of code files found during the search
	 */
	private $_fileList = array();

	/**
	 * @var array list of tags found
	 */
	private $_tagList = array();

	/**
	 * Initializes the command
	 */
	public function init()
	{
		$this->defaultAction = 'search';

		if (!isset($this->rootDirectory))
			$this->rootDirectory = Yii::app()->basePath;

		parent::init();
	}

	/**
	 * Returns the output of yiic help <command>
	 * @return string the help message
	 */
	public function getHelp()
	{
		$help = 'Usage: '.$this->commandRunner->getScriptName().' '.$this->getName();
		$help .= ' [--rootDirectory=/path/to/dir] [--translateFunction=Yii::t] [--extension=php ...] [--exclude=tests --exclude=extensions --exclude=cms ...]';
		$help .= PHP_EOL;
		return $help;
	}

	/**
	 * Default action. It searches for translations under rootDirectory.
	 */
	public function actionSearch()
	{
		$startTime = microtime(true);

		// Find all files to search in, then find all tags in those files
		$this->findFiles($this->rootDirectory, $this->extensions, $this->exclude);
		foreach ($this->_fileList as $file)
			$this->extractMessages($file['filename']);

		// Mark all MessageSource rows as not in use by default. Below we'll 
		// mark the rows which are in use as.
		$messageSourceList = MessageSource::model()->findAll();
		foreach ($messageSourceList as $messageSource)
		{
			$messageSource->used = 0;
			$messageSource->save(false);
		}

		// Keep track of how many messages we've found
		$newMessages = 0;

		try
		{
			// Insert the new messages into the database
			foreach ($this->_tagList as $category=> $stringList)
			{
				foreach ($stringList as $string)
				{
					// Possibly skip core category
					if ($category == 'yii' && !$this->includeCoreMessages)
						continue;

					// Check if the message already exists. If not we add it.
					$messageSource = MessageSource::model()->findByAttributes(array(
						'category'=>$category,
						'message'=>$string));

					if ($messageSource === null)
					{
						$this->log('Found new message "'.$string.'" in category "'.$category.'"');

						$messageSource = new MessageSource();
						$messageSource->category = $category;
						$messageSource->message = $string;

						$newMessages++;
					}

					// Mark the message as in use
					$messageSource->used = 1;

					if (!$messageSource->save())
						throw new Exception('Some of the messages could not be saved');
				}
			}
		}
		catch (Exception $e)
		{
			echo $e->getMessage();
		}

		$this->log("Found $newMessages new message(s)");
		$this->log('Execution took: '.(microtime(true) - $startTime).' seconds');
	}

	/**
	 * Find all files in a folder recursively
	 * @param string $path the path to begin searching under
	 * @param array $extensionList list of extensions to search for
	 * @param array $excludeEntries list of files/directories to exclude
	 */
	private function findFiles($path, $extensionList, $excludeEntries)
	{
		// Check that the given path is a directory
		if (!is_dir($path))
			return;

		// Loop through the directory
		$directory = dir($path);
		while (($entry = $directory->read()) !== false)
		{
			if ($entry === '.' || $entry === '..' || in_array($entry, $excludeEntries))
				continue;

			// Build the full path to this entry
			$fullPath = $path.DIRECTORY_SEPARATOR.$entry;

			// Recurse into sub-directories
			if (is_dir($fullPath))
				$this->findFiles($fullPath, $extensionList, $excludeEntries);
			else
			{
				$extension = strtolower(substr($fullPath, strrpos($fullPath, '.') + 1));

				// Add it to the list of files if the extension is valid
				if (in_array($extension, $extensionList, true))
					$this->_fileList[] = array('filename'=>$fullPath, 'extension'=>$extension);
			}
		}

		$directory->close();
	}

	/**
	 * Copied from the yiic message command. It searches the specified file
	 * for occurences of translateFunction and populates _tagList with the 
	 * results.
	 * @param string $fileName the filename to search in
	 */
	private function extractMessages($fileName)
	{
		$subject = file_get_contents($fileName);
		$n = preg_match_all('/\b'.$this->translateFunction.'\s*\(\s*(\'.*?(?<!\\\\)\'|".*?(?<!\\\\)")\s*,\s*(\'.*?(?<!\\\\)\'|".*?(?<!\\\\)")\s*[,\)]/s', $subject, $matches, PREG_SET_ORDER);

		for ($i = 0; $i < $n; ++$i)
		{
			if (($pos = strpos($matches[$i][1], '.')) !== false)
				$category = substr($matches[$i][1], $pos + 1, -1);
			else
				$category = substr($matches[$i][1], 1, -1);
			$message = $matches[$i][2];

			$this->_tagList[$category][] = eval("return $message;");  // use eval to eliminate quote escape
		}
	}

	/**
	 * Helper for printing text to the console
	 * @param string $message the message to be printed
	 */
	private function log($message)
	{
		echo $message."\n";
	}

}