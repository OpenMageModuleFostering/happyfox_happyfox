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
	
	class Happyfox_Happyfox_Block_Adminhtml_Dashboard_Grids extends Mage_Adminhtml_Block_Widget_Tabs
	{
		public function __construct()
		{
			parent::__construct();
			$this->setId('happyfox_tickets_grid_tab');
			$this->setDestElementId('happyfox_tickets_grid_tab_content');
			$this->setTemplate('widget/tabshoriz.phtml');
		}
		
		protected function _prepareLayout()
		{
			if(!$this->isHappFoxDashboard() && !Mage::getStoreConfig('happyfox/extension_settings/display_tickets'))
			{
				return parent::_prepareLayout();
			}
			
			if(Mage::getStoreConfig('happyfox/extension_settings/ticket_category')) {
				$cat_id = trim(trim(Mage::getStoreConfig('happyfox/extension_settings/ticket_category')));
			}
			
			if($cat_id && $cat_id != 'none') {
				try {
					if($this->isHappFoxDashboard()) {
						$label = 'Pending tickets';
					} else {
						$label = 'HappyFox Tickets';
					}
					
					$this->addTab('tab-pending', array(
						'label' => $label,
						'content' => $this->getLayout()->createBlock('happyfox/adminhtml_dashboard_tab_view')->setView('pending')->toHtml(),
						'active' => true
					));
					
					if($this->isHappFoxDashboard()) { //need solved tickets only in HF dashboard
						$this->addTab('tab-solved', array(
							'label' => 'Solved tickets',
							'content' => $this->getLayout()->createBlock('happyfox/adminhtml_dashboard_tab_view')->setView('solved')->toHtml(),
						));
					}
				} catch(Exception $e) {
					Mage::getSingleton('core/session')->addNotice($e->getMessage());
				}
			} else {
				if($this->isHappFoxDashboard()) {
					$block = $this->getLayout()->createBlock('core/template', 'happyfox_dashboard_empty')->setTemplate('happyfox/dashboard/empty.phtml');
					$this->getLayout()->getBlock('happyfox_dashboard')->append($block);
				}
			}
			
			return parent::_prepareLayout();
		}
		
		public function isHappFoxDashboard()
		{
			$controller = Mage::app()->getFrontController()->getRequest()->getControllerName();
			
			if($controller == 'happyfox') {
				return true;
			} else {
				return false;
			}
		}
	}
