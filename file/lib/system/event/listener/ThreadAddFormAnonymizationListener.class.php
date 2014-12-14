<?php
namespace wbb\system\event\listener;

/**
 * Allows to create an anonymized thread.
 * 
 * @author	Tim Düsterhus, Maximilian Mader
 * @copyright	2013 - 2014 Tim Düsterhus
 * @license	Creative Commons Attribution-NonCommercial-ShareAlike <https://creativecommons.org/licenses/by-nc-sa/4.0/legalcode>
 * @package	be.bastelstu.wbb.anonymization
 * @subpackage	system.event.listener
 */
class ThreadAddFormAnonymizationListener implements \wcf\system\event\listener\IParameterizedEventListener {
	/**
	 * whether the post should be anonymized
	 * @var boolean
	 */
	public $anonymize = false;
	
	/**
	 * The real user
	 * @var \wcf\data\user\User
	 */
	public $oldUser = null;
	
	/**
	 * @see	\wcf\system\event\listener\IParameterizedEventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		$board = $eventObj->board;
		if (!$board->getPermission('canCreateAnonymizedThread') && !$board->anonymizationForced) return;
		
		switch ($eventName) {
			case 'readFormParameters':
				if (isset($_POST['anonymize']) || $board->anonymizationForced) $this->anonymize = true;
				return;
			case 'show':
				\wcf\system\WCF::getTPL()->assign(array(
					'anonymize' => $this->anonymize
				));
				return;
			case 'save':
				if (!$this->anonymize) return;
				
				$this->oldUser = \wcf\system\WCF::getUser();
				\wcf\system\session\SessionHandler::getInstance()->changeUser(new \wcf\data\user\User(null), true);
				$eventObj->username = 'OP';
				if ($board->anonymizationMode === 'fixed' || ($board->anonymizationMode === 'default' && WBB_POST_ANONYMIZATION_MODE === 'fixed')) {
					$eventObj->username = 'Anonymous';
				}
				
				$eventObj->additionalPostFields['ipAddress'] = '';
				$eventObj->additionalPostFields['isAnonymized'] = 1;
				
				
				if (WBB_POST_ANONYMIZATION_REQUIRE_ACTIVATION) {
					$eventObj->disableThread = true;
				}
				return;
			case 'saved':
				if (!$this->anonymize) return;
				
				if ($this->oldUser !== null) {
					\wcf\system\session\SessionHandler::getInstance()->changeUser($this->oldUser, true);
				}
				
				if ($eventObj->objectAction && $board->anonymizationMode !== 'fixed' && ($board->anonymizationMode !== 'default' || WBB_POST_ANONYMIZATION_MODE !== 'fixed')) {
					$returnValues = $eventObj->objectAction->getReturnValues();
					$threadEditor = new \wbb\data\thread\ThreadEditor($returnValues['returnValues']);
					$threadEditor->update(array(
						'anonymizedOp' => \wbb\util\AnonymizationUtil::createAnonymizedUsername($this->oldUser->userID, $returnValues['returnValues']->threadID, 'hash')
					));
				}
		}
	}
}
