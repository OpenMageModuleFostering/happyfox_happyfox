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
 
	class Happyfox_Happyfox_Adminhtml_HappyfoxController extends Mage_Adminhtml_Controller_Action
	{
		protected $_publicActions = array('redirect');
		
		public function indexAction()
		{
			$this->_title('HappyFox Dashboard');
			$this->loadLayout();
			$this->_setActiveMenu('happyfox/happyfox_dashboard');
			$this->renderLayout();
		}
		
		public function redirectAction()
		{
			$type = $this->getRequest()->getParam('type');
			$id = $this->getRequest()->getParam('id');
			
			if($id && $type && in_array($type, array('customer', 'order', 'settings'))) {
				switch($type)
				{
					case "settings":
						$this->_redirect('adminhtml/system_config/edit/section/happyfox');
						break;
					//others
				}
			} else {
				$this->_redirect(Mage::getSingleton('admin/session')->getUser()->getStartupPageUrl());
			}
		}
		
		public function launchAction()
		{
			$this->_redirectUrl(Mage::helper('happyfox')->getUrl() . '/staff');
		}
	}
