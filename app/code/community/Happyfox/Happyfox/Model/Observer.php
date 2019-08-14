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
	
	class Happyfox_Happyfox_Model_Observer
	{
		protected $api = "/api/1.1/json/";
		
		public function setHook(Varien_Event_Observer $o)
		{
			if(Mage::app()->getFrontController()->getAction()->getFullActionName() === "adminhtml_dashboard_index") {
				$block = $o->getBlock();
				
				if($block->getNameInLayout() === "dashboard") {
					$block->getChild('totals')->setUseAsDashboardHook(true);
				}
			}
		}
		
		public function insertBlock(Varien_Event_Observer $o)
		{
			if(Mage::app()->getFrontController()->getAction()->getFullActionName() === "adminhtml_dashboard_index") {
				if($o->getBlock()->getUseAsDashboardHook()) {
					if(Mage::getStoreConfig('happyfox/extension_settings/display_tickets')) {
						$html = $o->getTransport()->getHtml();
					
						$happyfoxDash = $o->getBlock()->getLayout()->createBlock('happyfox/adminhtml_dashboard')
							->setName('happyfox_dashboard');
						
						$happyfoxGrid = $o->getBlock()->getLayout()->createBlock('happyfox/adminhtml_dashboard_grids')
							->setName('happyfox_dashboard_grids');
						
						$happyfoxDash->setChild('happyfox_dashboard_grids', $happyfoxGrid);
						$html .= $happyfoxDash->toHtml();
						$o->getTransport()->setHtml($html);
					}
				}
			}
		}
		
		public function saveConfig(Varien_Event_Observer $o)
		{
			$domain = Mage::getStoreConfig('happyfox/general/domain');
			$api_key = Mage::getStoreConfig('happyfox/general/apiKey');
			$auth_code = Mage::getStoreConfig('happyfox/general/authCode');
			
			$isError = false;
			$error = "Please enter your";
			
			if(!$domain) {
				$isError = true;
				$error .= " HappyFox Subdomain";
			}
			
			if(!$api_key){
				$isError = true;
				
				if(!$domain) {
					$error .= ",";
				}
				$error .= " API Key";
			}
			
			if(!$auth_code) {
				$isError = true;
				
				if(!$domain || !$api_key) {
					$error .= " and";
				}
				
				$error .= " Auth Code";
			}
			
			$error .= ".";
			
			if($isError) {
				throw new Exception($error);
			}
		}
	}
