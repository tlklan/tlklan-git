<?php

/**
 * Cache dependency based on the last modified row in a set of tables.
 * Usage examples:
 * 
 * $dep = new DbModifiedDependency('product');
 * $dep = new DbModifiedDependency('category', 'last_modified');
 * $dep = new DbModifiedDependency(array('product', 'category'));
 * $dep = new DbModifiedDependency(array(
 *     'product', 
 *     array(
 * 	       'table'=>'category',
 *         'modifiedColumn'=>'last_modified',
 *     ),
 * ));
 * 
 * If the same dependency is used multiple times per request (doesn't have to 
 * be the same instance, just the same constructor) the tables will not be 
 * queried again.
 * 
 * @author Sam Stenvall <sam@supportersplace.com>
 */
class DbModifiedDependency extends CDbCacheDependency
{
	
	/**
	 * @var boolean whether to reuse the dependency data for the duration of 
	 * the request. Defauls to true.
	 */
	public static $_persistentlyReuseDependentData = true;

	/**
	 * Class constructor.
	 * @param mixed $tables either an array of tables or the name of a single 
	 * table as a string.
	 * @param string $modifiedColumn the column containing the last modified 
	 * timestamp
	 */
	public function __construct($tables, $modifiedColumn = 'modified')
	{
		// Use a simple SELECT MAX for single table or SELECT GREATEST(...) for 
		// multiple.
		if (!is_array($tables))
		{
			$sql = 'SELECT MAX(`'.$modifiedColumn.'`) FROM `'.$tables.'`';
		}
		else
		{
			$greatestParams = array();
			foreach ($tables as $table)
			{
				// Determine column and table name
				$col = is_array($table) ? $table['modifiedColumn'] : $modifiedColumn;
				$tbl = is_array($table) ? $table['table'] : $table;
				
				$greatestParams[] = '(SELECT MAX(`'.$col.'`) FROM `'.$tbl.'`)';
			}

			$sql = 'SELECT GREATEST('.implode(', ', $greatestParams).')';
		}

		parent::__construct($sql);
	}
	
	/**
	 * Overriden to set $reuseDependentData
	 */
	public function evaluateDependency()
	{
		$this->reuseDependentData = self::$_persistentlyReuseDependentData;
		
		parent::evaluateDependency();
	}

	/**
	 * Overriden to set $reuseDependentData
	 */
	public function getHasChanged()
	{
		$this->reuseDependentData = self::$_persistentlyReuseDependentData;
		
		return parent::getHasChanged();
	}


}