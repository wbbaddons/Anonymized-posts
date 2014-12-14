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
class PostAddFormAnonymizationListener implements \wcf\system\event\listener\IParameterizedEventListener {
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
		if (!$board->getPermission('canCreateAnonymizedPost') && !$board->anonymizationForced) return;
		
		switch ($eventName) {
			case 'readData':
				if (empty($_POST)) {
					if ($eventObj->thread->getFirstPost()->isAnonymized) $this->anonymize = true;
				}
				return;
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
				
				$eventObj->username = \wbb\util\AnonymizationUtil::createAnonymizedUsername($this->oldUser->userID, $eventObj->thread->threadID, $board->anonymizationMode, $eventObj->thread->anonymizedOp);
				$eventObj->additionalFields['ipAddress'] = '';
				$eventObj->additionalFields['isAnonymized'] = 1;
				
				if (WBB_POST_ANONYMIZATION_REQUIRE_ACTIVATION) {
					$eventObj->disablePost = true;
				}
				return;
			case 'saved':
				if (!$this->anonymize) return;
				
				if ($this->oldUser !== null) {
					\wcf\system\session\SessionHandler::getInstance()->changeUser($this->oldUser, true);
				}
		}
	}
}
