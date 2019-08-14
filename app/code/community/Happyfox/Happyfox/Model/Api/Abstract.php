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
	
	class Happyfox_Happyfox_Model_Api_Abstract extends Mage_Core_Model_Abstract
	{
		protected function _getUrl($path)
		{
			$base_url = trim(Mage::getStoreConfig('happyfox/general/domain'), "/");
			
			//If protocol is missing, add it.
			if(preg_match('/^https:\/\//', $base_url) === 0) {
				$base_url = str_replace("http://", "", $base_url);
				$base_url = 'https://' . $base_url;
			}
			
			$base_url =  $base_url . "/api/1.1/json";
			$path = trim($path, "/");
			
			if(preg_match("/[&\?]/", $path) === 0) {
				$path .= "/";
			}
			
			return $base_url . "/" . $path;
		}
		
		protected function _request($resource, $params = null, $method = 'GET', $data = null)
		{
			if($params && is_array($params) && count($params) > 0) {
				$args = array();
				
				foreach($params as $key => $value) {
					$args[] = urlencode($key) . '=' . urlencode($value);
				}
				
				$resource .= '?' . implode('&', $args);
			}
			
			$url = $this->_getUrl($resource);
			$method = strtoupper($method);
			
			$client = new Zend_Http_Client($url);
			$client->setMethod($method);
			$client->setHeaders(
				array(
					'Accept' => 'application/json',
					'Content-Type' => 'application/json',
				)
			);
			
			$client->setAuth(
				Mage::getStoreConfig('happyfox/general/apiKey'),
				Mage::getStoreConfig('happyfox/general/authCode')
			);
			
			if($method === 'POST') {
				$client->setRawData(json_encode($data), 'application/json');
			}
			
			$response = $client->request();
			$body = json_decode($response->getBody());
			
			if($response->isError()) {
				$status = $response->getStatus();
				
				if($status == "401" || $status == "404") {
					$error = "HappyFox server says: 401 Unauthorized. Please double-check your HappyFox subdomain, API Key and API Auth Code settings. If your HappyFox account settings are correct, then your Magento Admin email address does not match your HappyFox Staff email. They need to be the same in order to fetch HappyFox tickets and categories that apply to your HappyFox account.";
					throw new Exception($error, $status);
				} else {
					throw new Exception("An unknown error occurred.", $status);
				}
			}
			
			return $body;
		}
	}
