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
	
	class Happyfox_Happyfox_Model_Source_Categories
	{
		protected $api = "/api/1.1/json/";
		
		public function toOptionArray()
		{
			$options[] = array('label' => 'None', 'value' => 'none');
			$domain = Mage::getStoreConfig('happyfox/general/domain');
			$api_key = Mage::getStoreConfig('happyfox/general/apiKey');
			$auth_code = Mage::getStoreConfig('happyfox/general/authCode');
			
			if($domain && $api_key && $auth_code) {
				//can proceed to http call
				if(preg_match('/^https:\/\//', $domain) === 0) {
					$domain = str_replace("http://", "", $domain);
					$domain = 'https://' . $domain;
				}
				
				$url = $domain . $this->api . "staff/";
				$client = new Zend_Http_Client();
				$client->setMethod('GET');
				$client->setHeaders(
					array(
						'Accept' => 'application/json',
						'Content-Type' => 'application/json'
					)
				);
				$client->setAuth($api_key, $auth_code);
				
				$admin_email = Mage::getSingleton('admin/session')->getUser()->getEmail();
				$url .= $admin_email . '/';
				
				$client->setUri($url);
				$response = $client->request();
				$staff = json_decode($response->getBody());
				
				if($response->isError()) {
					$status = $response->getStatus();
					
					if($status == "401" || $status == "400") {
						Mage::getSingleton('adminhtml/session')
							->addError("HappyFox server says: 401 Unauthorized. Please double-check your HappyFox subdomain, API Key and API Auth Code settings. If your HappyFox account settings are correct, then your Magento Admin email address does not match your HappyFox Staff email. They need to be the same in order to fetch HappyFox tickets and categories that apply to your HappyFox account.");
					} else {
						Mage::getSingleton('adminhtml/session')->addNotice("Could not fetch available HappyFox categories at this time.");
					}
				} else {
					$user_categories = array();
					
					foreach($staff->categories as $category) {
						$user_categories[] = $category;
					}
					
					//reset params and fetch all categories to get their names
					$client->resetParameters();
					$client->setMethod('GET');
					$client->setHeaders(
						array(
							'Accept' => 'application/json',
							'Content-Type' => 'application/json'
						)
					);
					
					$client->setAuth($api_key, $auth_code);
					$url = $domain . $this->api . "categories/";
					
					$client->setUri($url);
					$response = $client->request();
					$categories = json_decode($response->getBody());
					
					if(!$response->isError()) {
						foreach($categories as $category) {
							if(in_array($category->id, $user_categories)) {
								$options[] = array('label' => $category->name, 'value' => $category->id);
							}
						}
					}
				}
			}
			/*$options = array(
				array('value' => 1, 'label' => 'Default'),
				array('value' => 2, 'label' => 'YssyBalu')
			);*/
			
			return $options;
		}
	}
