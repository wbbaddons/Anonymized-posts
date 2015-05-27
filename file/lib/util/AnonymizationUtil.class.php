<?php
namespace wbb\util;

/**
 * Provides helper functions for anonymized content.
 * 
 * @author	Tim Düsterhus
 * @copyright	2013 - 2014 Tim Düsterhus
 * @license	Creative Commons Attribution-NonCommercial-ShareAlike <https://creativecommons.org/licenses/by-nc-sa/4.0/legalcode>
 * @package	be.bastelstu.wbb.anonymization
 * @subpackage	util
 */
class AnonymizationUtil {
	/**
	 * Returns the appropriate anonymized username.
	 * 
	 * @param	integer	$userID			userID of the poster
	 * @param	integer	$threadID		threadID of the target thread
	 * @param	string	$anonymizationMode	mode used in the target board
	 * @param	string	$op			hash of OP
	 * @return	string				Anonymized username.
	 */
	public static function createAnonymizedUsername($userID, $threadID = null, $anonymizationMode = 'default', $op = '') {
		if ($anonymizationMode == 'default') $anonymizationMode = WBB_POST_ANONYMIZATION_MODE;
		
		switch ($anonymizationMode) {
			case 'fixed':
				return 'Anonymous';
			case 'hash':
			case 'list':
				if ($op && self::createAnonymizedUsername($userID, $threadID, 'hash') == $op) {
					return 'OP';
				}
				
				$number = abs(hexdec(mb_substr(\wcf\util\Signer::getSignature($userID.'-'.$threadID), 0, 12)));
				
				if ($anonymizationMode == 'list') {
					$names = \wcf\util\ArrayUtil::trim(explode("\n", \wcf\util\StringUtil::unifyNewlines(WBB_POST_ANONYMIZATION_NAMES)));
					return $names[$number % count($names)].' ('.($number % 1000).')';
				}
				
				// convert 12 character hexadecimal number into 10 character number in base 36
				return str_pad(base_convert($number, 10, 36), 10, 0, STR_PAD_LEFT);
		}
	}
}
