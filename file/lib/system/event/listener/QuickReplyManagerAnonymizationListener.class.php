<?php
namespace wbb\system\event\listener;

/**
 * Allows to create an anonymized post in quick reply.
 * 
 * @author	Tim Düsterhus
 * @copyright	2013 - 2014 Tim Düsterhus
 * @license	Creative Commons Attribution-NonCommercial-ShareAlike <https://creativecommons.org/licenses/by-nc-sa/4.0/legalcode>
 * @package	be.bastelstu.wbb.anonymization
 * @subpackage	system.event.listener
 */
class QuickReplyManagerAnonymizationListener implements \wcf\system\event\listener\IParameterizedEventListener {
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
		if (!($eventObj->getContainer() instanceof \wbb\data\thread\Thread || ($eventObj->getContainer() instanceof \wcf\data\DatabaseObjectDecorator && $eventObj->getContainer()->getDecoratedObject() instanceof \wbb\data\thread\Thread))) return;
		$board = $eventObj->getContainer()->getBoard();
		if (!$board->getPermission('canCreateAnonymizedPost') && !$board->anonymizationForced) return;
		
		switch ($eventName) {
			case 'validateParameters':
				if (isset($parameters['data']['anonymize']) || $board->anonymizationForced) $this->anonymize = true;
				unset($parameters['data']['anonymize']);
				return;
			case 'createMessage':
				if (!$this->anonymize) return;
				$this->oldUser = \wcf\system\WCF::getUser();
				
				$username = \wbb\util\AnonymizationUtil::createAnonymizedUsername($this->oldUser->userID, $eventObj->getContainer()->threadID, $board->anonymizationMode, $eventObj->getContainer()->anonymizedOp);
				
				\wcf\system\session\SessionHandler::getInstance()->changeUser(new \wcf\data\user\User(null, array(
					'userID' => null,
					'username' => $username
				)), true);
				
				$parameters['ipAddress'] = '';
				$parameters['isAnonymized'] = 1;
				
				if (WBB_POST_ANONYMIZATION_REQUIRE_ACTIVATION) {
					$parameters['isDisabled'] = 1;
				}
				return;
			case 'createdMessage':
				if (!$this->anonymize) return;
				
				if ($this->oldUser !== null) {
					\wcf\system\session\SessionHandler::getInstance()->changeUser($this->oldUser, true);
				}
		}
	}
}
