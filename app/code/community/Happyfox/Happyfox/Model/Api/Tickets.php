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
	
	class Happyfox_Happyfox_Model_Api_Tickets extends Happyfox_Happyfox_Model_Api_Abstract
	{
		public function get($id)
		{
			if(!$id) {
				throw new InvalidArgumentException('Invalid ticket ID provided.');
			}
			
			$response = $this->_request('ticket/' . $id . '/');
			return $response;
		}
		
		public function getAll($cat_id, $status = "all")
		{
			if(!$cat_id) {
				throw new InvalidArgumentException('Ticket category not provided!');
			}
			
			$common_users = Mage::getModel('happyfox/api_users')->getAll();
			$common_users = implode(",", $common_users);
			
			if($status == "pending") {
				$status = "&status=_pending&q=contact" . urlencode(":$common_users");
			} else if($status == "completed") {
				//fetch all user statuses with behavior "completed"
				$user_statuses = "";
				$statuses = $this->_request('statuses/');
				
				foreach($statuses as $s) {
					if($s->behavior == "completed") {
						$user_statuses .= $s->name . ",";
					}
				}
				$user_statuses = trim($user_statuses, ",");
				$status = "&q=status" . urlencode(":$user_statuses") . "+" . urlencode("contact:$common_users");
			} else {
				$status = ""; //no trolling
			}
			
			$size = Mage::getStoreConfig('happyfox/extension_settings/number_of_tickets');
			
			$tickets = $this->_request("tickets/?category=$cat_id&size=$size" . $status);
			
			return $tickets;
		}
		
		public function customerTickets($customer_email)
		{
			$cat_id = Mage::getStoreConfig('happyfox/extension_settings/ticket_category');
			$show_on_customer = Mage::getStoreConfig('happyfox/extension_settings/show_on_customer');
			$size = Mage::getStoreConfig('happyfox/extension_settings/number_of_tickets');
			
			if($cat_id == 'none' || $show_on_customer == 0) {
				return; //nothing to do here, ticket display is disabled
			}
			//
			if(!$customer_email || $customer_email == "") {
				throw new InvalidArgumentException('Customer email address is not valid.');
			}
			
			$response = $this->_request("tickets/?q=" . urlencode("contact:$customer_email") . "&category=$cat_id&size=$size");
			
			return $response;
		}
		
		public function forOrder($order)
		{
			//TODO: only query ticket summary info
			if(!$order) {
				throw new InvalidArgumentException("Order ID is not valid.");
			}
			
			$cat_id = Mage::getStoreConfig('happyfox/extension_settings/ticket_category');
			$show_on_order = Mage::getStoreConfig('happyfox/extension_settings/show_on_order');
			$custom_field = Mage::getStoreConfig('happyfox/extension_settings/happyfox_order_field');
			
			if($cat_id == 'none' || $show_on_order == 0 || $custom_field == "") {
				return; //display on order view is disabled, return
			}
			
			$size = Mage::getStoreConfig('happyfox/extension_settings/number_of_tickets');
			
			$response = $this->_request('tickets/?q=' . urlencode("\"$custom_field\":\"$order\"") . "&category=$cat_id&size=$size");
			
			return $response;
		}
	}
