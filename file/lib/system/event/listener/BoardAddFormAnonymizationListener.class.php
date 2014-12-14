<?php
namespace wbb\system\event\listener;

/**
 * Allows to create an anonymized post.
 * 
 * @author	Tim Düsterhus
 * @copyright	2013 - 2014 Tim Düsterhus
 * @license	Creative Commons Attribution-NonCommercial-ShareAlike <https://creativecommons.org/licenses/by-nc-sa/4.0/legalcode>
 * @package	be.bastelstu.wbb.anonymization
 * @subpackage	system.event.listener
 */
class BoardAddFormAnonymizationListener implements \wcf\system\event\listener\IParameterizedEventListener {
	/**
	 * whether anonymization should be forced in this board
	 * @var boolean
	 */
	public $anonymizationForced = false;
	
	/**
	 * anonymization mode used in this board
	 * @var boolean
	 */
	public $anonymizationMode = 'default';
	
	/**
	 * @see	\wcf\system\event\listener\IParameterizedEventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		switch ($eventName) {
			case 'readData':
				if (empty($_POST) && $eventObj instanceof \wbb\acp\form\BoardEditForm) {
					if ($eventObj->board->anonymizationForced) $this->anonymizationForced = true;
					if ($eventObj->board->anonymizationMode) $this->anonymizationMode = $eventObj->board->anonymizationMode;
				}
				return;
			case 'readFormParameters':
				if (isset($_POST['anonymizationForced'])) $this->anonymizationForced = true;
				if (isset($_POST['anonymizationMode'])) $this->anonymizationMode = \wcf\util\StringUtil::trim($_POST['anonymizationMode']);
				return;
			case 'validate':
				switch ($this->anonymizationMode) {
					case 'default':
					case 'list':
					case 'hash':
					case 'fixed':
						// fine
					break;
					default:
						throw new \wcf\system\exception\UserInputException('anonymizationMode', 'notValid');
				}
				return;
			case 'assignVariables':
				\wcf\system\WCF::getTPL()->assign(array(
					'anonymizationForced' => $this->anonymizationForced,
					'anonymizationMode' => $this->anonymizationMode
				));
				return;
			case 'save':
				$eventObj->additionalFields = array_merge($eventObj->additionalFields, array(
					'anonymizationForced' => $this->anonymizationForced ? 1 : 0,
					'anonymizationMode' => $this->anonymizationMode
				));
		}
	}
}
