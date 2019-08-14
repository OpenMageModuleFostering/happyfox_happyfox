<?php
/**
 * Copyright 2014 HappyFox.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
	class Happyfox_Happyfox_Helper_Data extends Mage_Core_Helper_Abstract {
		
		public function getUrl($type = '', $id= null)
		{
			$domain = Mage::getStoreConfig('happyfox/general/domain');
			$domain = trim($domain, "/");
			
			if(preg_match('/^https:\/\//', $domain) === 0) {
				$domain = str_replace("http://", "", $domain);
				$domain = 'https://' . $domain;
			}
			
			switch($type) {
				case 'ticket':
					return $domain . '/staff/ticket/' . $id;
					break;
				case 'user':
					return $domain . '/staff/contact/' . $id;
					break;
				default:
					return $domain;
			}
		}
		
		public function getSnippet($string, $words = 7)
		{
			$string_holder = explode(' ', $string);
			$snippet = "";
			
			if(count($string_holder) < $words) {
				return $string;
			}
			
			for($i = 0; $i < $words; $i++) {
				$snippet .= $string_holder[$i] . " ";
			}
			
			return trim($snippet) . "...";
		}
	}
