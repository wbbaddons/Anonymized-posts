<?php
namespace wbb\system\event\listener;

/**
 * Provides the anonymized username to the template.
 * 
 * @author	Tim Düsterhus
 * @copyright	2013 - 2014 Tim Düsterhus
 * @license	Creative Commons Attribution-NonCommercial-ShareAlike <https://creativecommons.org/licenses/by-nc-sa/4.0/legalcode>
 * @package	be.bastelstu.wbb.anonymization
 * @subpackage	system.event.listener
 */
class ThreadPageAnonymizationListener implements \wcf\system\event\listener\IParameterizedEventListener {
	/**
	 * @see	\wcf\system\event\listener\IParameterizedEventListener::execute()
	 */
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		switch ($eventName) {
			case 'assignVariables':
				\wcf\system\WCF::getTPL()->assign(array(
					'anonymizationUsername' => \wbb\util\AnonymizationUtil::createAnonymizedUsername(\wcf\system\WCF::getUser()->userID, $eventObj->threadID, $eventObj->board->anonymizationMode, $eventObj->thread->anonymizedOp)
				));
		}
	}
}
