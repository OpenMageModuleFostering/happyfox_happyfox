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

function checkFields() {
	var domain = jQuery('input[name="groups[general][fields][domain][value]"]').val();
	var api_key = jQuery('input[name="groups[general][fields][apiKey][value]"]').val();
	var auth_code = jQuery('input[name="groups[general][fields][authCode][value]"]').val();
	
	if(domain == "" || api_key == "" || auth_code == "") {
		jQuery("div.section-config:eq(1)").removeClass("active");
		jQuery("a#happyfox_extension_settings-head").removeClass("open");
		jQuery("fieldset#happyfox_extension_settings").hide();
		jQuery("button.scalable.save:eq(0)").empty().append("<span><span><span>Save & Continue</span></span></span>");
		jQuery("button.scalable.save:eq(0)").attr("title", "Save & Continue");
	}
	
	if(domain !== "" && api_key !== "" && auth_code !== "") {
		jQuery("button.scalable.save:eq(0)").empty().append("<span><span><span>Save</span></span></span>");
		jQuery("button.scalable.save:eq(0)").attr("title", "Save settings");
	}
	
	//show warning message if all details are available, but category is not chosen
	if(domain && api_key && auth_code) {
		if(jQuery('select[name="groups[extension_settings][fields][ticket_category][value]"]').val() === "none") {
			console.log("Value is none, must add message.");
			var msg = " Please select a category to display your HappyFox tickets.";
			if(jQuery("li.success-msg").length) {
				jQuery("li.success-msg li > span").append(msg);
			} else {
			jQuery("div.main-col-inner").prepend('<div id="messages"><ul class="messages"><li class="notice-msg"><ul><li><span>' + msg + '</span></li></ul></li></ul></div>');
			}
		}
	}
}

jQuery(document).ready(function() {
	checkFields();
});
