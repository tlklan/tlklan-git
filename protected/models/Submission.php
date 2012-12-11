<?php

/**
 * This is the model class for table "tlk_submissions".
 *
 * The followings are the available columns in table 'tlk_submissions':
 * @property integer $id
 * @property integer $compo_id
 * @property integer $user_id
 * @property string $name
 * @property string $physical_path
 * @property int $size
 * @property string $comments
 * @property integer $disqualified
 *
 * The followings are the available model relations:
 * @property Competition $competition
 * @property Registration $submitter
 * @property Vote[] $votes
 * @property VoteCount $voteCount
 * 
 */
class Submission extends CActiveRecord
{
	/**
	 * @var CUploadedFile the submission file
	 */
	public $file;

	/**
	 * Returns the static model of the specified AR class.
	 * @return Submission the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tlk_submissions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('compo_id, user_id, name', 'required'),
			array('file', 'required', 'on'=>'insert'),
			array('file', 'file', 'on'=>'insert'),
			array('compo_id, user_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>30),
			array('size, comments', 'safe'),
			// the file doesn't have to be resubmitted when updating
			array('file', 'file', 'allowEmpty'=>true, 'on'=>'update'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		return array(
			'competition'=>array(self::BELONGS_TO, 'Competition', 'compo_id'),
			'submitter'=>array(self::BELONGS_TO, 'User', 'user_id'),
			'vote'=>array(self::HAS_MANY, 'Vote', 'submission_id'),
			'voteCount'=>array(self::STAT, 'Vote', 'submission_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>'ID',
			'compo_id'=>'Tävling',
			'competitionName'=>'Tävling',
			'submitter'=>'Skapare',
			'user_id'=>'Ditt nick',
			'name'=>'Entrynamn',
			'file'=>'Filnamn',
			'physical_path'=>'Sökväg', // mostly internal
			'size'=>'Storlek',
			'comments'=>'Kommentarer',
			'disqualified'=>'Diskvalificerad',
		);
	}

	/**
	 * Returns the size of the submission
	 * 
	 * @param boolean $formatted whether to format the size (B, kB, MB etc.)
	 * @return mixed if $formatted is set to true, a string representation of
	 * the size is returned, otherwise the size in bytes is returned as an integer
	 */
	public function getSize($formatted = true)
	{
		// Format it
		$formatter = new CFormatter();
		$formatter->sizeFormat = array('base'=>1024, 'decimals'=>1);
		return !$formatted ? $this->size : $formatter->formatSize($this->size);
	}
	
	/**
	 * Returns true if the submission was the winning one in its competition
	 * @return boolean
	 */
	public function isWinner()
	{
		$winningSubmission = SubmissionVote::model()->getWinningSubmission($this->competition->id);

		if ($winningSubmission !== null)
			return $winningSubmission->user_id == $this->user_id;

		return false;
	}

}