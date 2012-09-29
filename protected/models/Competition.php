<?php

/**
 * This is the model class for table "tlk_competitions".
 *
 * The followings are the available columns in table 'tlk_competitions':
 * @property integer $id
 * @property integer $lan_id
 * @property integer $display_order
 * @property string $short_name
 * @property string $full_name
 *
 * The followings are the available model relations:
 * @property Lans $lan
 * @property Competitors[] $competitors
 * @property Results[] $results
 * @property Submissions[] $submissions
 * @property Votings[] $votings
 */
class Competition extends CActiveRecord {

	/**
	 * Returns the static model of the specified AR class.
	 * @return Competition the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'tlk_competitions';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		return array(
			array('lan_id, display_order, short_name, full_name', 'required'),
			array('lan_id, display_order', 'numerical', 'integerOnly'=>true),
			array('short_name', 'length', 'max'=>20),
			array('full_name', 'length', 'max'=>50),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations() {
		return array(
			'lan'=>array(self::BELONGS_TO, 'Lans', 'lan_id'),
			'competitors'=>array(self::HAS_MANY, 'Competitor', 'competition_id'),
			'competitorCount'=>array(self::STAT, 'Competitor', 'competition_id'),
			'results'=>array(self::HAS_MANY, 'Results', 'compo_id'),
			'submissions'=>array(self::HAS_MANY, 'Submission', 'compo_id'),
			'votings'=>array(self::HAS_MANY, 'Votings', 'compo_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
			'id'=>'ID',
			'lan_id'=>'Lan',
			'display_order'=>'Display Order',
			'short_name'=>'Short Name',
			'full_name'=>'Full Name',
		);
	}

	/**
	 * Finds all competition belonging to the specified LAN
	 * @param int $lanId the LAN
	 * @return array the result 
	 */
	public function findByLan($lanId) {
		return self::model()->findAll('lan_id = :lan_id', array(
			':lan_id'=>$lanId,
		));
	}
	
	/**
	 * Getter for the competition name. This is to make it more logical as 
	 * "full_name" goes against naming conventions
	 */
	public function getName() {
		return $this->full_name;
	}
	
	/**
	 * Returns a list of the competitions belonging to the specified LAN, sorted
	 * by the amount of competitors that have signed up for them.
	 * @param int $lanId the LAN
	 * @return array
	 */
	public function getStatisticsByLan($lanId) {
		$competitions = self::model()->findByLan($lanId);
		$stats = array();

		foreach($competitions as $competition)
			$stats[$competition->short_name] = $competition->competitorCount;

		arsort($stats);

		return $stats;
	}
}