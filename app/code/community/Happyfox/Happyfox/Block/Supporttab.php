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
	
	class Happyfox_Happyfox_Block_Supporttab extends Mage_Core_Block_Template
	{
		protected function _toHtml()
		{
			if(!Mage::getStoreConfig('happyfox/extension_settings/support_tab_active')) {
				return '';
			}
			
			return Mage::getStoreConfig('happyfox/extension_settings/support_tab_code');
		}
	}
