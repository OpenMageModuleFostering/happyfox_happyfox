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
	
	class Happyfox_Happyfox_Model_Api_Users extends Happyfox_Happyfox_Model_Api_Abstract
	{
		//Returns email addresses of all users common to Magento and HappyFox
		public function getAll($type = "users")
		{
			//get all 
			$magento_users = Mage::getModel('customer/customer')->getCollection()
				->addAttributeToSelect('*');
			
			$mage_customers = array();
			
			foreach($magento_users as $user) {
				$mage_customers[] = $user->toArray();
			}
			
			$resource = "users/?size=50";
			
			$response = $this->_request($resource);
			$happyfox_users = $response->data;
			$pages = $response->page_info->page_count; //get amount of pages
			
			if($pages > 1) {
				for($page = 1; $page < $pages; $page++) {
					$response = $this->_request($resource . "&page=" . ($page + 1));
					
					foreach($response->data as $user) {
						$happyfox_users[] = $user; //push users into existing array
					}
				}
			}
			
			//manual intersection
			$common_users = array();
			
			foreach($mage_customers as $user) {
				$email = $user['email'];
				
				foreach($happyfox_users as $hf) {
					if($email === $hf->email) {
						$common_users[] = $email;
					}
				}
			}
			
			return $common_users;
		}
	}
