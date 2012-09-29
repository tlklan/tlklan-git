<?php

/**
 * This is the model class for table "tlk_submissions".
 *
 * The followings are the available columns in table 'tlk_submissions':
 * @property integer $id
 * @property integer $compo_id
 * @property integer $submitter_id
 * @property string $name
 * @property string $physical_path
 * @property string $comments
 * @property integer $disqualified
 *
 * The followings are the available model relations:
 * @property Competition $competition
 * @property Vote[] $votes
 * @property VoteCount $voteCount
 * 
 */
class Submission extends CActiveRecord {

	// Values for the formatSize() method
	const GIGA = 1073741824;
	const MEGA = 1048576;
	const KILO = 1024;
	
	public $file;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return Submission the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'tlk_submissions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		return array(
			array('compo_id, submitter_id, name', 'required'),
			array('file', 'file', 'on'=>'insert'),
			array('compo_id, submitter_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>30),
			array('comments', 'safe'),
			// the file doesn't have to be resubmitted when updating
			array('file', 'file', 'allowEmpty'=>true, 'on'=>'update'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		return array(
			'competition'=>array(self::BELONGS_TO, 'Competition', 'compo_id'),
			'submitter'=>array(self::BELONGS_TO, 'Registration', 'submitter_id'),
			'vote'=>array(self::HAS_MANY, 'Vote', 'submission_id'),
			'voteCount'=>array(self::STAT, 'Vote', 'submission_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id'=>'ID',
			'compo_id'=>'Tävling',
			'submitter_id'=>'Ditt nick',
			'name'=>'Entrynamn',
			'file'=>'Filnamn',
			'physical_path'=>'Sökväg', // mostly internal
			'comments'=>'Kommentarer',
			'disqualified'=>'Diskvalificerad',
		);
	}
	
	protected function afterConstruct() {
		$this->disqualified = false;
		
		parent::afterConstruct();
	}
	
	/**
	 * Returns a list of submissions belonging to competition that belong to 
	 * the specified LAN
	 * 
	 * @param int $lanId the LAN
	 * @return array the sumissions
	 */
	public function findByLAN($lanId) {
		return $this->with('competition')->findAll('competition.lan_id = :lan_id', array(
			':lan_id'=>$lanId,
		));
	}
	
	/**
	 * Returns the size of the submission
	 * 
	 * @param boolean $formatted whether to format the size (B, kB, MB etc.)
	 * @return mixed if $formatted is set to true, a string representation of
	 * the size is returned, otherwise the size in bytes is returned as an integer
	 */
	public function getSize($formatted = true) {
		// Abort if file not found
		if(!is_readable($this->physical_path))
			return 0;
		
		$stat = stat($this->physical_path);
		$sizeBytes = $stat[7];
		
		return ($formatted === false) ? $sizeBytes : $this->formatSize($sizeBytes);
	}
	
	/**
	 * Returns a string representation of the given size
	 * 
	 * @param integer $sizeBytes size in bytes
	 * @return string the formatted size 
	 */
	private function formatSize($sizeBytes) {
		$size = 0;
		$unit = "";

		if($sizeBytes > self::GIGA) {
			$size = $sizeBytes / self::GIGA;
			$unit = "GiB";
		}
		elseif($sizeBytes > self::MEGA) {
			$size = $sizeBytes / self::MEGA;
			$unit = "MiB";
		}
		elseif($sizeBytes > self::KILO) {
			$size = $sizeBytes / self::KILO;
			$unit = "kiB";
		}
		else {
			$size = $sizeBytes." B";
			$unit = "B";
		}

		return number_format($size, 1, ".", " ")." $unit";
	}

}