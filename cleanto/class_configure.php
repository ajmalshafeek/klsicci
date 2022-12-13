<?php   
class cleanto_configure
{
	public $ct_timezone;
	public $ct_country_code;
    public $conn;
	public $email;
	public $password;
	public $pc;
	public $dh;
	public $du;
	public $dp;
	public $dn;
	
	public function __construct(){
		$this->ct_timezone = date_default_timezone_get();
	}
    /*Function for Read Only one data matched with Id*/
    public function q1()
    {
        $query = "CREATE TABLE IF NOT EXISTS `ct_addon_service_rate` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
              `addon_service_id` int(11) NOT NULL,
              `unit` varchar(20) NOT NULL,
              `rules` enum('E','G') NOT NULL,
              `rate` double NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        mysqli_query($this->conn, $query);
    }
    public function q2()
    {
        $admin_info = "CREATE TABLE IF NOT EXISTS `ct_admin_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `password` varchar(50) NOT NULL,
  `email` varchar(250) NOT NULL,
  `fullname` varchar(70) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `address` varchar(250) NOT NULL,
  `city` varchar(48) NOT NULL,
  `state` varchar(48) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `country` varchar(48) NOT NULL,
  `role` varchar(20) NOT NULL,
  `description` varchar(320) NOT NULL,
  `enable_booking` enum('Y','N') NOT NULL,
  `service_commission` enum('F','P') NOT NULL,
  `commision_value` double NOT NULL,
  `schedule_type` enum('M','W') NOT NULL,
  `image` varchar(250) NOT NULL,
  `service_ids` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        mysqli_query($this->conn, $admin_info);
    }
    public function q3()
    {
        $bookings = "CREATE TABLE IF NOT EXISTS `ct_bookings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) NOT NULL,
  `client_id` bigint(20) NOT NULL,
  `order_date` date NOT NULL,
  `booking_date_time` datetime NOT NULL,
  `service_id` int(11) NOT NULL,
  `method_id` int(11) NOT NULL,
  `method_unit_id` int(11) NOT NULL,
  `method_unit_qty` double NOT NULL,
  `method_unit_qty_rate` double NOT NULL,
  `booking_status` enum('A','C','R','CC','CS','CO','MN','RS') NOT NULL COMMENT 'A=active, C=confirm, R=Reject, CC=Cancel by Client, CS=Cancel by service provider,CO=Completed,MN=MARK AS NOSHOW',
  `reject_reason` varchar(200) NOT NULL,
  `reminder_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT '0=Email Not Sent,1=Email Sent',
  `lastmodify` datetime NOT NULL,
  `read_status` enum('R','U') NOT NULL DEFAULT 'U',
  `staff_ids` varchar(160) DEFAULT NULL,
  `gc_event_id` varchar(255) DEFAULT NULL,
  `gc_staff_event_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        mysqli_query($this->conn, $bookings);
    }
    public function q4()
    {
        $booking_addons = "CREATE TABLE IF NOT EXISTS `ct_booking_addons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) NOT NULL,
  `service_id` int(11) NOT NULL,
  `addons_service_id` int(11) NOT NULL,
  `addons_service_qty` int(11) NOT NULL,
  `addons_service_rate` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;";
        mysqli_query($this->conn, $booking_addons);
    }
    public function q5()
    {
        $coupons = "CREATE TABLE IF NOT EXISTS `ct_coupons` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `coupon_code` varchar(250) NOT NULL,
  `coupon_type` enum('P','F') NOT NULL,
  `coupon_limit` int(11) NOT NULL,
  `coupon_used` int(11) NOT NULL,
  `coupon_value` double NOT NULL,
  `coupon_expiry` date NOT NULL,
  `status` enum('on','off') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        mysqli_query($this->conn, $coupons);
    }
    public function q6()
    {
        $frequently_discount = "CREATE TABLE IF NOT EXISTS `ct_frequently_discount` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discount_typename` varchar(20) NOT NULL,
  `d_type` enum('F','P') NOT NULL,
  `rates` double NOT NULL,
  `labels` varchar(50) NOT NULL,
  `status` enum('E','D') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
        mysqli_query($this->conn, $frequently_discount);
    }
    public function q7()
    {
        $off_days = "
CREATE TABLE IF NOT EXISTS `ct_off_days` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `off_date` date NOT NULL,
  `lastmodify` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `status` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;";
        mysqli_query($this->conn, $off_days);
    }
    public function q8()
    {
        $order_client_info = "CREATE TABLE IF NOT EXISTS `ct_order_client_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `client_name` varchar(200) NOT NULL,
  `client_email` varchar(200) NOT NULL,
  `client_phone` varchar(15) NOT NULL,
  `client_personal_info` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
        mysqli_query($this->conn, $order_client_info);
		
    }
    public function q9()
    {
        $payments = "CREATE TABLE IF NOT EXISTS `ct_payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `payment_method` varchar(200) NOT NULL,
  `transaction_id` varchar(300) NOT NULL,
  `amount` double NOT NULL,
  `discount` double NOT NULL,
  `taxes` double NOT NULL,
  `partial_amount` double NOT NULL,
  `payment_date` date NOT NULL,
  `net_amount` double NOT NULL,
  `lastmodify` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `frequently_discount` enum('O','W','B','M') NOT NULL,
  `frequently_discount_amount` double NOT NULL,
  `recurrence_status` enum('Y', 'N') NOT NULL,
  `payment_status` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        mysqli_query($this->conn, $payments);
    }
    public function q10()
    {
        $schedule_breaks = "CREATE TABLE IF NOT EXISTS `ct_schedule_breaks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider_id` int(11) NOT NULL,
  `week_id` int(11) NOT NULL,
  `weekday_id` int(11) NOT NULL,
  `break_start` time NOT NULL,
  `break_end` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        mysqli_query($this->conn, $schedule_breaks);
    }
    public function q11()
    {
        $schedule_offtimes = "CREATE TABLE IF NOT EXISTS `ct_schedule_offtimes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider_id` int(11) NOT NULL,
  `start_date_time` datetime NOT NULL,
  `end_date_time` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        mysqli_query($this->conn, $schedule_offtimes);
    }
    public function q12(){
        $services = "CREATE TABLE IF NOT EXISTS `ct_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `color` varchar(8) NOT NULL,
  `image` varchar(250) NOT NULL,
  `status` enum('E','D') NOT NULL DEFAULT 'D',
  `position` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
        mysqli_query($this->conn, $services);
    }
    public function q13(){
$services_addon = "CREATE TABLE IF NOT EXISTS `ct_services_addon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `addon_service_name` varchar(50) NOT NULL,
  `base_price` double NOT NULL,
  `maxqty` int(11) NOT NULL,
  `image` varchar(250) NOT NULL,
  `multipleqty` enum('Y','N') NOT NULL,
  `status` enum('E','D') NOT NULL DEFAULT 'E',
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
mysqli_query($this->conn, $services_addon);
}
    public function q14(){
        $services_method = "CREATE TABLE IF NOT EXISTS `ct_services_method` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `method_title` varchar(250) NOT NULL,
  `status` enum('E','D') NOT NULL DEFAULT 'E',
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
        mysqli_query($this->conn, $services_method);
    }
    public function q15(){
        $services_methods_units_rate = "CREATE TABLE IF NOT EXISTS `ct_services_methods_units_rate` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `units_id` int(11) NOT NULL,
  `units` varchar(30) NOT NULL,
  `rules` enum('G','E') NOT NULL,
  `rates` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
        mysqli_query($this->conn, $services_methods_units_rate);
    }
    public function q16(){
  $service_methods_design = "CREATE TABLE IF NOT EXISTS `ct_service_methods_design` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_methods_id` int(11) NOT NULL,
  `design` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
        mysqli_query($this->conn, $service_methods_design);
    }
    public function q17(){
$services_methods_units = "CREATE TABLE IF NOT EXISTS `ct_service_methods_units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `services_id` int(11) NOT NULL,
  `methods_id` int(11) NOT NULL,
  `units_title` varchar(256) NOT NULL,
  `base_price` double NOT NULL,
  `maxlimit` int(11) NOT NULL,
  `status` enum('E','D') NOT NULL,
  `position` int(11) NOT NULL,
  `limit_title` varchar(256) NOT NULL,
  `half_section` enum('E','D') NOT NULL DEFAULT 'D',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
mysqli_query($this->conn, $services_methods_units);
}
public function q18(){
    $settings = "CREATE TABLE IF NOT EXISTS `ct_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `option_name` varchar(500) NOT NULL,
  `option_value` text NOT NULL,
  `postalcode` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
    mysqli_query($this->conn, $settings);
}
public function q19(){
    $users = "CREATE TABLE IF NOT EXISTS `ct_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_email` varchar(96) DEFAULT '',
  `user_pwd` varchar(40) DEFAULT '',
  `first_name` varchar(32) DEFAULT '',
  `last_name` varchar(32) DEFAULT '',
  `phone` varchar(15) DEFAULT '',
  `zip` varchar(10) DEFAULT '',
  `address` varchar(250) DEFAULT '',
  `city` varchar(48) DEFAULT '',
  `state` varchar(48) DEFAULT '',
  `notes` varchar(800) DEFAULT '',
  `vc_status` enum('Y','N','-') DEFAULT 'N',
  `p_status` enum('Y','N','-') DEFAULT 'N',
  `contact_status` varchar(500) DEFAULT '',
  `status` enum('E','D') DEFAULT 'D',
  `usertype` varchar(50) DEFAULT '',  
  `cus_dt` TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
    mysqli_query($this->conn, $users);
}
    public function q20(){
        $week_days_available = "
CREATE TABLE IF NOT EXISTS `ct_week_days_available` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `provider_id` int(11) NOT NULL DEFAULT '0',
  `week_id` int(11) NOT NULL,
  `weekday_id` int(11) NOT NULL,
  `day_start_time` time NOT NULL,
  `day_end_time` time NOT NULL,
  `off_day` enum('Y','N') NOT NULL,
  `provider_schedule_type` varchar(30) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
        mysqli_query($this->conn, $week_days_available);
    }
    public function q21(){
		$languages = "CREATE TABLE IF NOT EXISTS `ct_languages` (
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `label_data` longtext NOT NULL,
					  `language` varchar(5) NOT NULL,
					  `admin_labels` longtext NOT NULL,
					  `error_labels` longtext NOT NULL,
					  `extra_labels` longtext NOT NULL,
					  `front_error_labels` longtext NOT NULL,
					  `language_status` enum('Y','N') NOT NULL DEFAULT 'Y',
					  PRIMARY KEY (`id`)
					 ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
        mysqli_query($this->conn,$languages);
    }
	
  public function q23(){
	    
        $pass = md5($this->password);
        
		
		$regadmin = mysqli_query($this->conn,"select * from `ct_admin_info` where `id`=1");

		
		if(@mysqli_num_rows($regadmin)>0) {
			
			 $query1 = "Update `ct_admin_info` set `password`='" . $pass . "',`email`='".$this->email."'  where `id`=1";
		     mysqli_query($this->conn, $query1);
			 return '1';
		}else {
			 $query1 = "INSERT INTO `ct_admin_info` (`id`, `password`, `email`, `fullname`, `phone`, `address`, `city`, `state`, `zip`, `country`,`role`, `description`, `enable_booking`, `service_commission`, `commision_value`, `schedule_type`, `image`, `service_ids`) VALUES (1, '" . $pass . "','".$this->email."' , '', '', '', '', '', '', '','admin', '', 'N', 'F', '0', 'W', '', '');";
			 mysqli_query($this->conn, $query1);
			 return mysqli_insert_id($this->conn);
		}
    }
    public function q22(){
        $query2 = "INSERT INTO `ct_frequently_discount` (`id`, `discount_typename`, `d_type`, `rates`, `labels`, `status`) VALUES
        (1, 'Once', 'P', 0, 'ZERO', 'E'),
		(2, 'Weekly', 'P', 15, 'Save 15%', 'E'),
		(3, 'Bi-Weekly', 'P', 12.5, 'Save 12.5%', 'E'),
		(4, 'Monthly', 'P', 10, 'Save 10%', 'E')";
        mysqli_query($this->conn, $query2);
 $query3 = "INSERT INTO `ct_settings` (`id`, `option_name`, `option_value`,`postalcode`) VALUES
(1, 'ct_company_name', 'Cleaning',''),
(2, 'ct_company_email', 'cleaning@example.com',''),
(3, 'ct_company_address', 'SUITE 5A-1204, 799 E DRAGRAM',''),
(4, 'ct_company_city', 'TUCSON',''),
(5, 'ct_company_state', 'AZ',''),
(6, 'ct_company_zip_code', '85001',''),
(7, 'ct_company_country', 'USA',''),
(8, 'ct_company_logo', '',''),
(9, 'ct_time_interval', '30',''),
(10, 'ct_min_advance_booking_time', '60',''),
(11, 'ct_max_advance_booking_time', '6',''),
(12, 'ct_booking_padding_time', '',''),
(13, 'ct_service_padding_time_before', '',''),
(14, 'ct_service_padding_time_after', '',''),
(15, 'ct_cancellation_buffer_time', '60',''),
(16, 'ct_reshedule_buffer_time', '60',''),
(17, 'ct_currency', 'USD',''),
(18, 'ct_currency_symbol_position', '$100',''),
(19, 'ct_price_format_decimal_places', '2',''),
(20, 'ct_tax_vat_status', 'Y',''),
(21, 'ct_tax_vat_type', '',''),
(22, 'ct_tax_vat_value', '',''),
(23, 'ct_partial_deposit_status', 'N',''),
(24, 'ct_partial_deposit_amount', '50',''),
(25, 'ct_partial_deposit_message', 'You only need to pay a deposit to confirm your booking. The remaining amount needs to be paid on arrival.',''),
(26, 'ct_thankyou_page_url', '',''),
(27, 'ct_allow_multiple_booking_for_same_timeslot_status', 'Y',''),
(28, 'ct_appointment_auto_confirm_status', 'N',''),
(29, 'ct_allow_day_closing_time_overlap_booking', 'N',''),
(30, 'ct_allow_terms_and_conditions', 'Y',''),
(31, 'ct_primary_color', '#3d3d3d',''),
(32, 'ct_secondary_color', '#00cded',''),
(33, 'ct_text_color', '#666666',''),
(34, 'ct_text_color_on_bg', '#ffffff',''),
(35, 'ct_primary_color_admin', '#3d3d3d',''),
(36, 'ct_show_coupons_input_on_checkout', 'on',''),
(37, 'ct_hide_faded_already_booked_time_slots', 'off',''),
(38, 'ct_guest_user_checkout', 'off',''),
(39, 'ct_date_picker_date_format', 'd-M-Y',''),
(40, 'ct_all_payment_gateway_status', 'off',''),
(41, 'ct_pay_locally_status', 'on',''),
(42, 'ct_paypal_express_checkout_status', 'off',''),
(43, 'ct_paypal_api_username', '',''),
(44, 'ct_paypal_api_password', '',''),
(45, 'ct_paypal_api_signature', '',''),
(46, 'ct_paypal_guest_payment_status', 'off',''),
(47, 'ct_paypal_test_mode_status', 'off',''),
(48, 'ct_stripe_payment_form_status', 'off',''),
(49, 'ct_stripe_secretkey', '',''),
(50, 'ct_stripe_publishablekey', '',''),
(51, 'ct_admin_email_notification_status', 'N',''),
(52, 'ct_staff_email_notification_status', 'N',''),
(53, 'ct_client_email_notification_status', 'N',''),
(54, 'ct_email_sender_name', '',''),
(55, 'ct_email_sender_address', '',''),
(56, 'ct_email_appointment_reminder_buffer', '60',''),
(57, 'ct_sms_service_status', 'N',''),
(58, 'ct_sms_twilio_account_SID', '',''),
(59, 'ct_sms_twilio_auth_token', '',''),
(60, 'ct_sms_twilio_sender_number', '',''),
(61, 'ct_sms_twilio_send_sms_to_service_provider_status', 'N',''),
(62, 'ct_sms_twilio_send_sms_to_client_status', 'N',''),
(63, 'ct_sms_twilio_send_sms_to_admin_status', 'N',''),
(64, 'ct_sms_twilio_admin_phone_number', '',''),
(65, 'ct_sms_template_admin_notification', '',''),
(66, 'ct_sms_template_service_provider', '',''),
(67, 'ct_sms_template_client_notification', '',''),
(68, 'ct_time_slots_schedule_type', 'weekly',''),
(69, 'ct_choose_time_format', '12',''),
(70, 'ct_secondary_color_admin', '#00cded',''),
(71, 'ct_text_color_admin', '#ffffff',''),
(72, 'ct_postal_code', '','90001,90002,90003,90004,90005,90006,90007,90008,90009,90010,90011,90012,90013'),
(73, 'ct_time_format', '12',''),
(74, 'ct_partial_type', '',''),
(75, 'ct_cancelation_policy_status', 'N',''),
(76, 'ct_cancel_policy_header', 'Free cancellation before redemption',''),
(77, 'ct_cancel_policy_textarea', 'Full refund if cancelled within 24 hours of placing the order.\n\n\nIf you cancel the order more than 24 hours, you can get a credit note for the amount paid.\n\n\n\nIf cancelled in less than 24 hours before time of appointment/stay or in case of no-show, order will not be refunded.',''),
(78, 'ct_terms_condition_link', '',''),
(79, 'ct_addons_default_design', '2',''),
(80, 'ct_method_default_design', '3',''),
(81, 'ct_front_desc', '<div class=\"features\">\n	<img class=\"feature-img\" src=\"<?php  echo BASE_URL ?>/assets/images/icon21.png\" alt=\"\">\n	<h4 class=\"feature-tittle\">Safety</h4>\n	<p class=\"feature-text\">Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\n</div>\n<div class=\"features\">\n	<img class=\"feature-img\" src=\"<?php  echo BASE_URL ?>/assets/images/icon31.png\" alt=\"\">\n	<h4 class=\"feature-tittle\">Best in Quality</h4>\n	<p class=\"feature-text\">Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\n</div>\n<div class=\"features\">\n	<img class=\"feature-img\" src=\"<?php  echo BASE_URL ?>/assets/images/icon51.png\" alt=\"\">\n	<h4 class=\"feature-tittle\">Communication</h4>\n	<p class=\"feature-text\">Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\n</div>\n<div class=\"features\">\n	<img class=\"feature-img\" src=\"<?php  echo BASE_URL ?>/assets/images/icon17.png\" alt=\"\">\n	<h4 class=\"feature-tittle\">Saves You Time</h4>\n	<p class=\"feature-text\">Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\n</div>\n<div class=\"features\">\n	<img class=\"feature-img\" src=\"<?php  echo BASE_URL ?>/assets/images/icon61.png\" alt=\"\">\n	<h4 class=\"feature-tittle\">Card Payment</h4>\n	<p class=\"feature-text\"> Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>\n</div>',''),
(82, 'ct_subheaders', 'N',''),
(83, 'ct_cart_scrollable', 'Y',''),
(84, 'ct_service_default_design', '1',''),
(85, 'ct_privacy_policy_link', '',''),
(86, 'ct_allow_privacy_policy', 'Y',''),
(87, 'ct_allow_front_desc', 'Y',''),
(88, 'ct_currency_symbol', '$',''),
(89, 'ct_smtp_hostname', '',''),
(90, 'ct_smtp_username', '',''),
(91, 'ct_smtp_password', '',''),
(92, 'ct_smtp_port', '',''),
(93, 'ct_sample_data_status', 'N',''),
(94, 'ct_remove_data_array', '',''),
(95, 'ct_timezone', '".$this->ct_timezone."',''),
(96, 'ct_company_country_code', '+1,us,United States: +1',''),
(97, 'ct_language', 'en',''),
(98, 'ct_custom_css', '',''),
(99, 'ct_authorizenet_status', '',''),
(100, 'ct_authorizenet_API_login_ID', '',''),
(101, 'ct_authorizenet_transaction_key', '',''),
(102, 'ct_authorize_sandbox_mode', '',''),
(103, 'ct_version','4.2',''),
(104, 'ct_sms_plivo_account_SID', '',''),
(105, 'ct_sms_plivo_auth_token', '',''),
(106, 'ct_sms_plivo_sender_number', '',''),
(107, 'ct_sms_plivo_send_sms_to_service_provider_status', 'N',''),
(108, 'ct_sms_plivo_send_sms_to_client_status', 'N',''),
(109, 'ct_sms_plivo_send_sms_to_admin_status', 'N',''),
(110, 'ct_sms_plivo_admin_phone_number', '',''),
(111, 'ct_vc_status', 'N',''),
(112, 'ct_p_status', 'N',''),
(113, 'ct_sms_twilio_status', 'N',''),
(114, 'ct_sms_plivo_status', 'N',''),
(115, 'ct_company_phone', '',''),
(116, 'ct_admin_optional_email', '',''),
(117, 'ct_2checkout_sandbox_mode', 'Y',''),
(118, 'ct_2checkout_privatekey', '',''),
(119, 'ct_2checkout_publishkey', '',''),
(120, 'ct_2checkout_sellerid', '',''),
(121, 'ct_2checkout_status', 'N',''),
(122, 'ct_postalcode_status', 'Y',''),
(123, 'ct_front_image', '',''),
(124, 'ct_login_image', '',''),
(125, 'ct_company_header_address', 'Y',''),
(126, 'ct_front_tool_tips_my_bookings','',''),
(127, 'ct_front_tool_tips_postal_code','',''),
(128, 'ct_front_tool_tips_services','',''),
(129, 'ct_front_tool_tips_addons_services','',''),
(130, 'ct_front_tool_tips_frequently_discount','',''),
(131, 'ct_front_tool_tips_time_slots','',''),
(132, 'ct_front_tool_tips_personal_details','',''),
(133, 'ct_front_tool_tips_promocode','',''),
(134, 'ct_front_tool_payment_method','',''),
(135, 'ct_sms_nexmo_status','',''),
(136, 'ct_nexmo_api_key','',''),
(137, 'ct_nexmo_api_secret','',''),
(138, 'ct_nexmo_from','',''),
(139, 'ct_nexmo_status','',''),
(140, 'ct_sms_nexmo_send_sms_to_client_status','',''),
(141, 'ct_sms_nexmo_send_sms_to_admin_status','',''),
(142, 'ct_sms_nexmo_admin_phone_number','',''),
(143, 'ct_front_tool_tips_status','',''),
(144, 'ct_existing_and_new_user_checkout','on',''),
(145, 'ct_user_zip_code','Y',''),
(146, 'ct_payumoney_salt','',''),
(147, 'ct_payumoney_merchant_key','',''),
(148, 'ct_payumoney_status','N',''),
(149, 'ct_company_logo_display','N',''),
(150, 'ct_sms_textlocal_account_username','',''),
(151, 'ct_sms_textlocal_account_hash_id','',''),
(152, 'ct_sms_textlocal_send_sms_to_client_status','N',''),
(153, 'ct_sms_textlocal_send_sms_to_admin_status','N',''),
(154, 'ct_sms_textlocal_admin_phone','',''),
(155, 'ct_sms_textlocal_status','N',''),
(156, 'ct_company_service_desc_status','Y',''),
(157, 'ct_company_willwe_getin_status','N',''),
(158, 'ct_bank_name','',''),
(159, 'ct_account_name','',''),
(161, 'ct_account_number','',''),
(162, 'ct_branch_code','',''),
(163, 'ct_ifsc_code','',''),
(164, 'ct_bank_transfer_status','N',''),
(165, 'ct_phone_display_country_code','',''),
(166, 'ct_bank_description','',''),
(167, 'ct_smtp_encryption','',''),
(168, 'ct_smtp_authetication','false',''),
(169, 'ct_bf_first_name','on,Y,3,15',''),
(170, 'ct_bf_last_name','on,Y,3,15',''),
(171, 'ct_bf_email','on,Y,5,30',''),
(172, 'ct_bf_password','on,Y,8,10',''),
(173, 'ct_bf_phone','on,Y,9,15',''),
(174, 'ct_bf_address','on,Y,10,40',''),
(175, 'ct_bf_zip_code','on,Y,5,7',''),
(176, 'ct_bf_city','on,Y,3,15',''),
(177, 'ct_bf_state','on,Y,3,15',''),
(178, 'ct_bf_notes','on,N,10,70',''),
(179, 'ct_front_language_selection_dropdown','Y',''),
(180, 'ct_calculation_policy','M',''),
(181, 'ct_staff_email_notification_status','N',''),
(182, 'ct_frontend_fonts','Raleway',''),
(183, 'ct_favicon_image','fevicon.png',''),
(184, 'ct_appointment_details_display','on',''),
(185, 'ct_loader','default',''),
(186, 'ct_recurrence_booking_status','N',''),
(187, 'ct_page_title','Cleanto Booking',''),
(188, 'ct_google_analytics_code','',''),
(189, 'ct_seo_meta_description','',''),
(190, 'ct_seo_og_title','',''),
(191, 'ct_seo_og_type','',''),
(192, 'ct_seo_og_url','',''),
(193, 'ct_seo_og_image','',''),
(194, 'ct_company_title_display','Y',''),
(195, 'ct_custom_gif_loader','',''),
(196, 'ct_custom_css_loader','',''),
(197, 'ct_calendar_defaultView','month',''),
(198, 'ct_calendar_firstDay','1',''),
(199, 'ct_gc_status','N',''),
(200, 'ct_gc_id','',''),
(201, 'ct_gc_client_id','',''),
(202, 'ct_gc_client_secret','',''),
(203, 'ct_gc_status_configure','',''),
(204, 'ct_gc_status_sync_configure','',''),
(205, 'ct_gc_token','',''),
(206, 'ct_gc_purchase_status','N',''),
(207, 'ct_gc_frontend_url','',''),
(208, 'ct_gc_version','',''),
(209, 'ct_gc_admin_url','',''),
(210, 'ct_payway_purchase_status','N',''),
(211, 'ct_payway_status','N',''),
(212, 'ct_payway_publishable_key','',''),
(213, 'ct_payway_secure_key','',''),
(214, 'ct_payway_version','',''),
(215, 'ct_payway_merchant_ID','',''),
(216, 'ct_eway_purchase_status','N',''),
(217, 'ct_eway_test_mode_status','N',''),
(218, 'ct_eway_api_username','',''),
(219, 'ct_eway_api_password','',''),
(220, 'ct_eway_status','N',''),
(221, 'ct_eway_version','',''),
(222, 'ct_payment_extensions','".serialize(array('payway' => array('method' => 'indirect', 'include_path' => '/extension/payway/payway.php', 'option_name' => 'ct_payway_purchase_status'), 'eway' => array('method' => 'direct', 'include_path' => '/extension/eway/eway.php', 'option_name' => 'ct_eway_purchase_status')))."',''),
(223, 'ct_special_offer','N',''),
(224, 'ct_special_offer_text','','')
(225, 'ct_color_already_full_bookings', 'off','');";
        mysqli_query($this->conn, $query3);
    }
    public function q24(){
        $delete_schedule = "delete from ct_week_days_available";
        mysqli_query($this->conn,$delete_schedule);
        $insert_schedule = "INSERT INTO `ct_week_days_available` (`id`, `provider_id`, `week_id`, `weekday_id`, `day_start_time`, `day_end_time`, `off_day`, `provider_schedule_type`) VALUES
		(NULL, 1, 1, 1, '10:00:00', '20:00:00', 'N', 'weekly'),
		(NULL, 1, 1, 2, '10:00:00', '20:00:00', 'N', 'weekly'),
		(NULL, 1, 1, 3, '10:00:00', '20:00:00', 'N', 'weekly'),
		(NULL, 1, 1, 4, '10:00:00', '20:00:00', 'N', 'weekly'),
		(NULL, 1, 1, 5, '10:00:00', '20:00:00', 'N', 'weekly'),
		(NULL, 1, 1, 6, '10:00:00', '20:00:00', 'N', 'weekly'),
		(NULL, 1, 1, 7, '10:00:00', '20:00:00', 'Y', 'weekly');";
        mysqli_query($this->conn,$insert_schedule);
    }
	public function q28(){
		$email_user = "CREATE TABLE IF NOT EXISTS `ct_email_user` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `cus_ids` text NOT NULL,
        `cus_sub` varchar(100) NOT NULL,
        `cus_msg` text NOT NULL,
        `cus_img` text NOT NULL,
        `cus_dt` TIMESTAMP,
        PRIMARY KEY (`id`)
       ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
		mysqli_query($this->conn,$email_user);
	}
	public function q29(){
		$email_user = "CREATE TABLE IF NOT EXISTS `ct_sms_user` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `cus_ids` text NOT NULL,
        `cus_msg` text NOT NULL,
        `cus_dt` TIMESTAMP,
        PRIMARY KEY (`id`)
       ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";
		mysqli_query($this->conn,$email_user);
	}
    public function q0(){	
	eval(base64_decode('JHBjPSAkdGhpcy0+cGM7CgkJJGRoPSAkdGhpcy0+ZGg7CgkJJGR1PSAkdGhpcy0+ZHU7CgkJJGRwPSAkdGhpcy0+ZHA7CgkJJGRuPSAkdGhpcy0+ZG47CgkJJHBvc3R1cmwgPSBzdHJfcm90MTMoJ3VnZ2M6Ly9qamouZnhsemJiYXlub2YucGJ6L3B5cm5hZ2IvcHVycHhfY2hlcHVuZnJfcGJxci5jdWMnKTsKCQkkY2ggPSBjdXJsX2luaXQoKTsKCQljdXJsX3NldG9wdCgkY2gsIENVUkxPUFRfVVJMLCRwb3N0dXJsKTsKCQljdXJsX3NldG9wdCgkY2gsIENVUkxPUFRfUE9TVCwgMSk7CgkJY3VybF9zZXRvcHQoJGNoLCBDVVJMT1BUX1BPU1RGSUVMRFMsInB1cmNoYXNlX2NvZGU9Ii4kX1NFUlZFUlsnU0VSVkVSX05BTUUnXS4iJCQiLiRwYyk7CgkJY3VybF9zZXRvcHQoJGNoLCBDVVJMT1BUX1JFVFVSTlRSQU5TRkVSLCB0cnVlKTsKCQkkcmVzdWx0ZCA9IGN1cmxfZXhlYygkY2gpOwoJCWlmKCRyZXN1bHRkPT0nVmFsaWQnKSB7CgkJJGY9Zm9wZW4oIi4vLi4vLi4vY29uZmlnLnBocCIsInciKTsKCQlAY2htb2QoJGYsIDA3NTUpOwoJCSRkYXRhYmFzZV9pbmY9Jzw/cGhwCgljbGFzcyBjbGVhbnRvX215dmFyaWFibGV7CglwdWJsaWMgJGhvc3RuYW1lcyA9ICInLiRkaC4nIjsKCXB1YmxpYyAkdXNlcm5hbWUgPSAiJy4kZHUuJyI7CglwdWJsaWMgJHBhc3N3b3JkcyA9ICInLiRkcC4nIjsKCXB1YmxpYyAkZGF0YWJhc2UgPSAiJy4kZG4uJyI7CglwdWJsaWMgJGVwY29kZSA9ICInLiRwYy4nIjsKfSA/Pic7CgkJJGNsaWVudF9uYW1lX25vbnd3d3cgPSBzdHJfcmVwbGFjZSgnd3d3LicsJycsJF9TRVJWRVJbJ1NFUlZFUl9OQU1FJ10pOwoJCSRjbGllbnRfbmFtZV93d3cgPSAnd3d3LicuJGNsaWVudF9uYW1lX25vbnd3d3c7CgkJaWYgKGZ3cml0ZSgkZiwkZGF0YWJhc2VfaW5mKT4wKXsgZmNsb3NlKCRmKTsgfQoJCSRjaGtxdWVyeSA9ICJzZWxlY3QgKiBmcm9tIGN0X29yZGVyX2NsaWVudF9pbmZvIHdoZXJlIG9yZGVyX2lkPScwJyBhbmQgKGNsaWVudF9uYW1lPSciLiRjbGllbnRfbmFtZV9ub253d3d3LiInIG9yIGNsaWVudF9uYW1lPSciLiRjbGllbnRfbmFtZV93d3cuIicpIjsKCQkkcmVzdWx0ID0gQG15c3FsaV9xdWVyeSgkdGhpcy0+Y29ubiwkY2hrcXVlcnkpOwoJCSRyZXN1bHRyID0gQG15c3FsaV9mZXRjaF9yb3coJHJlc3VsdCk7CgkJaWYoQCRyZXN1bHQtPm51bV9yb3dzPT1udWxsIHx8IChpc3NldCgkX1NFU1NJT05bJ2luc3RhbGxlcl9tb2RlJ10pICYmICRfU0VTU0lPTlsnaW5zdGFsbGVyX21vZGUnXT09J2YnKSApewoJCSR0aGlzLT5xOCgpOwoJCSRvcmRlcl9pbnNlcnRfcXVlcnkgPSAiSU5TRVJUIElOVE8gY3Rfb3JkZXJfY2xpZW50X2luZm8gKGlkLCBvcmRlcl9pZCwgY2xpZW50X25hbWUsIGNsaWVudF9lbWFpbCwgY2xpZW50X3Bob25lLCBjbGllbnRfcGVyc29uYWxfaW5mbykgVkFMVUVTIChOVUxMLCcwJywnIi4kX1NFUlZFUlsnU0VSVkVSX05BTUUnXS4iJywnTlVMTCcsJ05VTEwnLCdOVUxMJykiOwoJCUBteXNxbGlfcXVlcnkoJHRoaXMtPmNvbm4sICRvcmRlcl9pbnNlcnRfcXVlcnkpOyAkdGhpcy0+cTEoKTskdGhpcy0+cTIoKTskdGhpcy0+cTMoKTskdGhpcy0+cTQoKTskdGhpcy0+cTUoKTskdGhpcy0+cTYoKTskdGhpcy0+cTcoKTskdGhpcy0+cTkoKTsgJHRoaXMtPnExMCgpOyR0aGlzLT5xMTEoKTskdGhpcy0+cTEyKCk7JHRoaXMtPnExMygpOyR0aGlzLT5xMTQoKTskdGhpcy0+cTE1KCk7JHRoaXMtPnExNigpOyR0aGlzLT5xMTcoKTskdGhpcy0+cTE4KCk7JHRoaXMtPnExOSgpOyR0aGlzLT5xMjAoKTskdGhpcy0+cTIxKCk7JHRoaXMtPnEyMigpOyR0aGlzLT5xMjQoKTskdGhpcy0+cTI1KCk7JHRoaXMtPnEyNygpOyR0aGlzLT5xMjgoKTskdGhpcy0+cTI5KCk7JHRoaXMtPnEzMCgpOwoJCX0KCQlpZihAJHJlc3VsdC0+bnVtX3Jvd3MhPW51bGwgJiYgaXNzZXQoJF9TRVNTSU9OWydpbnN0YWxsZXJfbW9kZSddKSAmJiAkX1NFU1NJT05bJ2luc3RhbGxlcl9tb2RlJ109PSdmJykgeyBlY2hvICIKCQlJdCBzZWVtcyBDbGVhbnRvIGlzIGFscmVhZHkgaW5zdGFsbGVkIG9uIHlvdXIgc2VydmVyLCBkYXRhYmFzZSB0YWJsZXMgZm91bmQuCgkJIjsgfSBlbHNlIHsgZWNobyAiWW91ciBwcm9kdWN0IHB1cmNoYXNlIGNvZGUgdmVyaWZpZWQgbm93ISI7IH0gY3VybF9jbG9zZSAoJGNoKTsgZGllOyB9CgkJZWxzZSB7IGVjaG8gIllvdXIgY29weSBvZiBDbGVhbnRvIGlzIG5vdCByZWdpc3RlcmVkLCBQbGVhc2UgdXNlIGNvcnJlY3QgRW52YXRvIFB1cmNoYXNlIGNvZGUgdG8gYWN0aXZhdGUgaXQuIjsgfQ=='));
	}
	public function q25()
    {
$errors = array(
"language_status_change_successfully"=>urlencode("Language Status Change Successfully"),
"commission_amount_should_not_be_greater_then_order_amount"=>urlencode("Commission Amount should not be Greater then Order Amount"),
"please_enter_merchant_ID"=>urlencode("Please enter merchant ID"),
"please_enter_secure_key"=>urlencode("Please enter secure key"),
"please_enter_google_calender_admin_url"=>urlencode("Please enter google calender admin url"),
"please_enter_google_calender_frontend_url"=>urlencode("Please enter google calender frontend url"),
"please_enter_google_calender_client_secret"=>urlencode("Please enter google calender client secret"),
"please_enter_google_calender_client_ID"=>urlencode("Please enter google calender client ID"),
"please_enter_google_calender_ID"=>urlencode("Please enter google calender ID"),
"you_cannot_book_on_past_date"=>urlencode("You cannot book on past date"),
"Invalid_Image_Type"=>urlencode("Invalid Image Type"),
"seo_settings_updated_successfully"=>urlencode("SEO settings updated successfully"),
"customer_deleted_successfully"=>urlencode("Customer deleted successfully"),
"please_enter_below_36_characters" => urlencode("Please enter below 36 characters"),
"are_you_sure_you_want_to_delete_client"=>urlencode("Are You Sure You Want To Delete Client?"),
"please_select_atleast_one_unit"=>urlencode("Please select atleast one unit"),
"atleast_one_payment_method_should_be_enable"=>urlencode("Atleast one payment method should be enable"),
"appointment_booking_confirm"=>urlencode("Appointment booking confirm"),
"appointment_booking_rejected"=>urlencode("Appointment booking rejected"),
"booking_cancel"=>urlencode("Boooking Cancelled"),
"appointment_marked_as_no_show"=>urlencode("Appointment marked as no show"),
"appointment_reschedules_successfully"=>urlencode("Appointment Reschedules successfully"),
"booking_deleted"=>urlencode("Booking Deleted"),
"break_end_time_should_be_greater_than_start_time"=>urlencode("Break End Time should be greater than Start time"),
"cancel_by_client"=>urlencode("Cancel by client"),
"cancelled_by_service_provider"=>urlencode("Cancelled by service provider"),
"design_set_successfully"=>urlencode("Design set successfully"),
"end_break_time_updated"=>urlencode("End break time updated"),
"enter_alphabets_only"=>urlencode("Enter alphabets only"),
"enter_only_alphabets"=>urlencode("Enter only alphabets"),
"enter_only_alphabets_numbers"=>urlencode("Enter only Alphabets/Numbers"),
"enter_only_digits"=>urlencode("Enter only Digits"),
"enter_valid_url"=>urlencode("Enter valid Url"),
"enter_only_numeric"=>urlencode("Enter only numeric"),
"enter_proper_country_code"=>urlencode("Enter proper country code"),
"frequently_discount_status_updated"=>urlencode("Frequently discount status updated"),
"frequently_discount_updated"=>urlencode("Frequently discount updated"),
"manage_addons_service"=>urlencode("Manage addons service"),
"maximum_file_upload_size_2_mb"=>urlencode("Maximum file upload size 2 MB"),
"method_deleted_successfully"=>urlencode("Method deleted successfully"),
"method_inserted_successfully"=>urlencode("Method inserted successfully"),
"minimum_file_upload_size_1_kb"=>urlencode("Minimum file upload size 1 KB"),
"off_time_added_successfully"=>urlencode("Off time added successfully"),
"only_jpeg_png_and_gif_images_allowed"=>urlencode("Only jpeg, png and gif images Allowed"),
"only_jpeg_png_gif_zip_and_pdf_allowed"=>urlencode("Only jpeg, png, gif, zip and pdf Allowed"),
"please_wait_while_we_send_all_your_message"=>urlencode("Please Wait While We Send All Your Messages"),
"please_enable_email_to_client"=>urlencode("Please Enable Emails To Client."),
"please_enable_sms_gateway"=>urlencode("Please Enable SMS Gateway."),
"please_enable_client_notification"=>urlencode("Please Enable Client Notification."),
"password_must_be_8_character_long"=>urlencode("Password must be 8 character long"),
"password_should_not_exist_more_then_20_characters"=>urlencode("Password should not exist more then 20 characters"),
"please_assign_base_price_for_unit"=>urlencode("Please assign base price for unit"),
"please_assign_price"=>urlencode("Please assign price"),
"please_assign_qty"=>urlencode("Please assign quantity"),
"please_enter_api_password"=>urlencode("Please enter API Password"),
"please_enter_api_username"=>urlencode("Please enter API Username"),
"please_enter_color_code"=>urlencode("Please enter Color Code"),
"please_enter_country"=>urlencode("Please enter country"),
"please_enter_coupon_limit"=>urlencode("Please enter coupon limit"),
"please_enter_coupon_value"=>urlencode("Please enter coupon value"),
"please_enter_coupon_code"=>urlencode("Please enter coupon code"),
"please_enter_email"=>urlencode("Please enter email"),
"please_enter_fullname"=>urlencode("Please enter Fullname"),
"please_enter_maxlimit"=>urlencode("Please enter maxLimit"),
"please_enter_method_title"=>urlencode("Please enter method title"),
"please_enter_name"=>urlencode("Please enter name"),
"please_enter_only_numeric"=>urlencode("Please enter only numeric"),
"please_enter_proper_base_price"=>urlencode("Please enter proper base price"),
"please_enter_proper_name"=>urlencode("Please enter proper name"),
"please_enter_proper_title"=>urlencode("Please enter proper title"),
"please_enter_publishable_key"=>urlencode("Please enter publishable key"),
"please_enter_secret_key"=>urlencode("Please enter secret key"),
"please_enter_service_title"=>urlencode("Please enter service Title"),
"please_enter_signature"=>urlencode("Please enter signature"),
"please_enter_some_qty"=>urlencode("Please enter some quantity"),
"please_enter_title"=>urlencode("Please enter title"),
"please_enter_unit_title"=>urlencode("Please enter unit title"),
"please_enter_valid_country_code"=>urlencode("Please enter valid country code"),
"please_enter_valid_service_title"=>urlencode("Please enter valid service title"),
"please_enter_valid_price"=>urlencode("Please enter valid price"),
"please_enter_zipcode"=>urlencode("Please enter zipcode"),
"please_enter_state"=>urlencode("Please enter state"),
"please_retype_correct_password"=>urlencode("Please retype correct password"),
"please_select_porper_time_slots"=>urlencode("Please select porper time slots"),
"please_select_time_between_day_availability_time"=>urlencode("Please select time between day availability time"),
"please_valid_value_for_discount"=>urlencode("Please valid value for discount"),
"please_enter_confirm_password"=>urlencode("Please enter confirm password"),
"please_enter_new_password"=>urlencode("Please enter new password"),
"please_enter_old_password"=>urlencode("Please enter old password"),
"please_enter_valid_number"=>urlencode("Please enter valid number"),
"please_enter_valid_number_with_country_code"=>urlencode("Please enter valid number with country code"),
"please_select_end_time_greater_than_start_time"=>urlencode("Please select end time greater than start time"),
"please_select_end_time_less_than_start_time"=>urlencode("Please select end time less than start time"),
"please_select_a_crop_region_and_then_press_upload"=>urlencode("Please select a crop region and then press upload"),
"please_select_a_valid_image_file_jpg_and_png_are_allowed"=>urlencode("Please select a valid image file jpg and png are allowed"),
"profile_updated_successfully"=>urlencode("Profile updated successfully"),
"qty_rule_deleted"=>urlencode("Quantity rule deleted"),
"record_deleted_successfully"=>urlencode("Record deleted successfully"),
"record_updated_successfully"=>urlencode("Record updated successfully"),
"rescheduled"=>urlencode("Rescheduled"),
"schedule_updated_to_monthly"=>urlencode("Schedule updated to Monthly"),
"schedule_updated_to_weekly"=>urlencode("Schedule updated to Weekly"),
"sorry_method_already_exist"=>urlencode("Sorry method already exist"),
"sorry_no_notification"=>urlencode("Sorry, you have not any upcoming appointment"),
"sorry_promocode_already_exist"=>urlencode("Sorry promocode already exist"),
"sorry_unit_already_exist"=>urlencode("Sorry unit already exist"),
"sorry_we_are_not_available"=>urlencode("Sorry we are not available"),
"start_break_time_updated"=>urlencode("Start break time updated"),
"status_updated"=>urlencode("Status updated"),
"time_slots_updated_successfully"=>urlencode("Time slots updated successfully"),
"unit_inserted_successfully"=>urlencode("Unit inserted successfully"),
"units_status_updated"=>urlencode("Units status updated"),
"updated_appearance_settings"=>urlencode("Updated appearance aettings"),
"updated_company_details"=>urlencode("Updated company details"),
"updated_email_settings"=>urlencode("Updated E-mail settings"),
"updated_general_settings"=>urlencode("Updated general settings"),
"updated_payments_settings"=>urlencode("Updated payments settings"),
"your_old_password_incorrect"=>urlencode("Old password incorrect"),
"please_enter_minimum_5_chars"=>urlencode("Please enter minimum 5 characters"),
"please_enter_maximum_10_chars"=>urlencode("Please enter maximum 10 characters"),
"please_enter_postal_code"=>urlencode("Please enter postal code"),
"please_select_a_service"=>urlencode("Please select a service"),
"please_select_units_and_addons"=>urlencode("Please select units and addons"),
"please_select_units_or_addons"=>urlencode("Please select units or addons"),
"please_login_to_complete_booking"=>urlencode("Please login to complete booking"),
"please_select_appointment_date"=>urlencode("Please select appointment date"),
"please_accept_terms_and_conditions"=>urlencode("Please accept terms and conditions"),
"incorrect_email_address_or_password"=>urlencode("Incorrect email address or password"),
"please_enter_valid_email_address"=>urlencode("Please enter valid email address"),
"please_enter_email_address"=>urlencode("Please enter email address"),
"please_enter_password"=>urlencode("Please enter password"),
"please_enter_minimum_8_characters"=>urlencode("Please enter minimum 8 characters"),
"please_enter_maximum_15_characters"=>urlencode("Please enter maximum 15 characters"),
"please_enter_first_name"=>urlencode("Please enter first name"),
"please_enter_only_alphabets"=>urlencode("Please enter only alphabets"),
"please_enter_minimum_2_characters"=>urlencode("Please enter minimum 2 characters"),
"please_enter_last_name"=>urlencode("Please enter last name"),
"email_already_exists"=>urlencode("Email already exists"),
"please_enter_phone_number"=>urlencode("Please enter phone number"),
"please_enter_only_numerics"=>urlencode("Please enter only numerics"),
"please_enter_minimum_10_digits"=>urlencode("Please enter minimum 10 digits"),
"please_enter_maximum_14_digits"=>urlencode("Please enter maximum 14 digits"),
"please_enter_address"=>urlencode("Please enter address"),
"please_enter_minimum_20_characters"=>urlencode("Please Enter Minimum 20 Characters"),
"please_enter_zip_code"=>urlencode("Please enter zip code"),
"please_enter_proper_zip_code"=>urlencode("Please enter proper zip code"),
"please_enter_minimum_5_digits"=>urlencode("Please enter minimum 5 digits"),
"please_enter_maximum_7_digits"=>urlencode("Please enter maximum 7 digits"),
"please_enter_city"=>urlencode("Please enter city"),
"please_enter_proper_city"=>urlencode("Please enter proper city"),
"please_enter_maximum_48_characters"=>urlencode("Please enter maximum 48 characters"),
"please_enter_proper_state"=>urlencode("Please enter proper state"),
"please_enter_contact_status"=>urlencode("Please enter contact status"),
"please_enter_maximum_100_characters"=>urlencode("Please enter maximum 100 characters"),
"your_cart_is_empty_please_add_cleaning_services"=>urlencode("Your cart is empty please add cleaning services"),
"please_enter_coupon_code"=>urlencode("Please enter Coupon code"),
"coupon_expired"=>urlencode("Coupon expired"),
"invalid_coupon"=>urlencode("Invalid coupon"),
"our_service_not_available_at_your_location"=>urlencode("Our service not available at your location"),
"please_enter_proper_postal_code"=>urlencode("Please enter proper postal code"),
"invalid_email_id_please_register_first"=>urlencode("Invalid Email ID please register first"),
"your_password_send_successfully_at_your_registered_email_id"=>urlencode("Your password send successfully at your registered Email ID"),
"your_password_reset_successfully_please_login"=>urlencode("Your password reset successfully please login"),
"new_password_and_retype_new_password_mismatch"=>urlencode("New password and retype new password mismatch"),
"new"=>urlencode("New"),
"your_reset_password_link_expired"=>urlencode("Your reset password link expired"),
"front_display_language_changed"=>urlencode("Front display language changed"),
"updated_front_display_language_and_update_labels"=>urlencode("Updated front display language and update labels"),
"please_enter_only_7_chars_maximum"=>urlencode("Please enter only 7 chars maximum"),
"please_enter_maximum_20_chars"=>urlencode("Please enter maximum 20 characters"),
"record_inserted_successfully"=>urlencode("Record Inserted Successfully"),
"please_enter_account_sid"=>urlencode("Please enter Accout SID"),
"please_enter_auth_token"=>urlencode("Please enter Auth Token"),
"please_enter_sender_number"=>urlencode("Please enter Sender Number"),
"please_enter_admin_number"=>urlencode("Please enter Admin Number"),
"sorry_service_already_exist"=>urlencode("Sorry service already exist"),
 "please_enter_api_login_id"=>urlencode("Please Enter API Login ID"),
 "please_enter_transaction_key"=>urlencode("Please Enter Transaction Key"),
 "please_enter_secret_key"=>urlencode("Please Enter Secret Key"),
 "please_enter_publishable_key"=>urlencode("Please Enter Publishable Key"),
 "please_enter_sms_message"=>urlencode("Please enter sms message"),
 "please_enter_email_message"=>urlencode("Please enter email message"),
 "please_enter_some_qty"=>urlencode("Please Enter Some Qty"),
 "please_enter_private_key"=>urlencode("Please Enter Private Key"),
 "please_enter_seller_id"=>urlencode("Please enter Seller ID"),
 "please_enter_valid_value_for_discount"=>urlencode("Please enter valid value for discount"),
 "password_must_be_only_10_characters"=>urlencode("Password Must Be Only 10 Characters"),
 "password_at_least_have_8_characters"=>urlencode("Password At Least Have 8 Characters"),
 "please_enter_retype_new_password"=>urlencode("Please Enter Retype New Password"),
 "your_password_send_successfully_at_your_email_id"=>urlencode("Your Password Send Successfully At Your Email ID"),
 "please_select_expiry_date"=>urlencode("Please select expiry date"),
 "please_enter_merchant_key"=>urlencode("Please enter Merchant Key"),
 "please_enter_salt_key"=>urlencode("Please enter Salt Key"),
  "please_enter_account_username"=>urlencode("Please enter account username"),
 "please_enter_account_hash_id"=>urlencode("Please enter account hash id"),
 "invalid_values"=>urlencode("Invalid values"),
 "please_select_atleast_one_checkout_method"=>urlencode("Please select atleast one checkout method"));
		

        $admin_labels = array(
		"payment_status"=>urlencode("Payment Status"),
		"staff_booking_status"=>urlencode("Staff Booking Status"),
		"accept"=>urlencode("Accept"),
		"accepted"=>urlencode("Accepted"),
		"decline"=>urlencode("Decline"),
		"paid"=>urlencode("Paid"),
		"eway"=>urlencode("Eway"),
		"half_section"=>urlencode("Half Section"),
		"option_title"=>urlencode("Option Title"),
		"merchant_ID"=>urlencode("Merchant ID"),
		"How_it_works"=>urlencode("How it works?"),
		"Your_currency_should_be_AUD_to_enable_payway_payment_gateway"=>urlencode("Your currency should be Australia Dollar to enable payway payment gateway"),
		"secure_key"=>urlencode("Secure Key"),
	    "payway"=>urlencode("Payway"),
	    "Your_Google_calendar_id_where_you_need_to_get_alerts_its_normaly_your_Gmail_ID"=>urlencode("Your Google calendar id, where you need to get alerts, its normaly your Gmail ID. e.g. johndoe@example.com"),
	    "You_can_get_your_client_ID_from_your_Google_Calendar_Console"=>urlencode("You can get your client ID from your Google Calendar Console"),
	    "You_can_get_your_client_secret_from_your_Google_Calendar_Console"=>urlencode("You can get your client secret from your Google Calendar Console"),
	    "its_your_Cleanto_booking_form_page_url"=>urlencode("its your Cleanto booking form page url"),
	    "Its_your_Cleanto_Google_Settings_page_url"=>urlencode("Its your Cleanto Google Settings page url"),
		"Add_Manual_booking"=>urlencode("Add Manual Booking"),
		"Google_Calender_Settings"=>urlencode("Google Calender Settings"),
		"Add_Appointments_To_Google_Calender"=>urlencode("Add Appointments To Google Calender"),
		"Google_Calender_Id"=>urlencode("Google Calender ID"),
		"Google_Calender_Client_Id"=>urlencode("Google Calender Client ID"),
		"Google_Calender_Client_Secret"=>urlencode("Google Calender Client Secret"),
		"Google_Calender_Frontend_URL"=>urlencode("Google Calender Frontend URL"),
		"Google_Calender_Admin_URL"=>urlencode("Google Calender Admin URL"),
		"Google_Calender_Configuration"=>urlencode("Google Calender Configuration"),
		"Two_Way_Sync"=>urlencode("Two Way Sync"),
		"Verify_Account"=>urlencode("Verify Account"),
		"Select_Calendar"=>urlencode("Select Calendar"),
		"Disconnect"=>urlencode("Disconnect"),
		"Calendar_Fisrt_Day"=>urlencode("Calendar First Day"),
		"Calendar_Default_View"=>urlencode("Calendar Default View"),
		"Show_company_title"=>urlencode("Show company title"),
		"front_language_flags_list"=>urlencode("Front languages flag list"),
		"Google_Analytics_Code"=>urlencode("Google Analytics Code"),
		"Page_Meta_Tag"=>urlencode("Page/Meta Tag"),
		"SEO_Settings"=>urlencode("SEO Settings"),
		"Meta_Description"=>urlencode("Meta Description"),
		"SEO"=>urlencode("SEO"),
		"og_tag_image"=>urlencode("og Tag Image"),
		"og_tag_url"=>urlencode("og Tag URL"),
		"og_tag_type"=>urlencode("og Tag Type"),
		"og_tag_title"=>urlencode("og Tag Title"),
		"Quantity"=>urlencode("Quantity"),
		"Send_Invoice"=>urlencode("Send Invoice"),
		"Recurrence"=>urlencode("Recurrence"),
		"Recurrence_booking"=>urlencode("Recurrence Booking"),
		"Reset_Color"=>urlencode("Reset Color"),
		"Loader"=>urlencode("Loader"),
		"CSS_Loader"=>urlencode("CSS Loader"),
		"GIF_Loader"=>urlencode("GIF Loader"),
		"Default_Loader"=>urlencode("Default Loader"),
		"for_a"=>urlencode("for a"),
		"show_company_logo"=>urlencode("Show company logo"),
		"on"=>urlencode("on"),
		"user_zip_code"=>urlencode("zip Code"),
		"delete_this_method"=>urlencode("Delete this method?"),
		"authorize_net"=>urlencode("Authorize.Net"),
		"staff_details"=>urlencode("STAFF DETAILS"),
		"client_payments" => urlencode("Client Payments"),
		"staff_payments" => urlencode("Staff Payments"),
		"staff_payments_details" => urlencode("Staff Payments Details"),
		"advance_paid" => urlencode("Advance Paid"),
		"change_calculation_policyy" => urlencode("Change Calculation Policy"),
		"frontend_fonts" => urlencode("Frontend fonts"),
		"favicon_image" => urlencode("Favicon Image"),
		"staff_email_template" => urlencode("Staff Email Template"),
		"staff_details_add_new_and_manage_staff_payments" => urlencode("Staff Details, Add new and manage staff payments"),
		"add_staff" => urlencode("Add staff"),
		"staff_bookings_and_payments" => urlencode("Staff Bookings & Payments"),
		"staff_booking_details_and_payment" => urlencode("Staff Booking Details and Payment"),
		"select_option_to_show_bookings" => urlencode("Select option to show bookings"),
		"select_service" => urlencode("Select Service"),
		"staff_name" => urlencode("Staff Name"),
		"staff_payment" => urlencode("Staff Payment"),
		"add_payment_to_staff_account" => urlencode("Add Payment to staff account"),
		"amount_payable" => urlencode("Amount Payable"),
		"advance_paid" => urlencode("Advance Paid"),
		"save_changes" => urlencode("Save changes"),
		"front_error_labels" => urlencode("Front Error Labels"),
"stripe"=>urlencode("Stripe"),
"checkout_title"=>urlencode("2Checkout"),
"nexmo_sms_gateway"=>urlencode("Nexmo SMS Gateway"),
"nexmo_sms_setting"=>urlencode("Nexmo SMS Setting"),
"nexmo_api_key"=>urlencode("Nexmo API Key"),
"nexmo_api_secret"=>urlencode("Nexmo API Secret"),
"nexmo_from"=>urlencode("Nexmo From"),
"nexmo_status"=>urlencode("Nexmo Status"),
"nexmo_send_sms_to_client_status"=>urlencode("Nexmo Send Sms To Client Status"),
"nexmo_send_sms_to_admin_status"=>urlencode("Nexmo Send Sms To admin Status"),
"nexmo_admin_phone_number"=>urlencode("Nexmo Admin Phone Number"),
"save_12_5"=>urlencode("save 12.5 %"),
"front_tool_tips"=>urlencode("FRONT TOOL TIPS"),
"front_tool_tips_lower"=>urlencode("Front Tool Tips"),
"tool_tip_my_bookings"=>urlencode("My Bookings"),
"tool_tip_postal_code"=>urlencode("Postal Code"),
"tool_tip_services"=>urlencode("Services"),
"tool_tip_extra_service"=>urlencode("Extra service"),
"tool_tip_frequently_discount"=>urlencode("Frequently discount"),
"tool_tip_when_would_you_like_us_to_come"=>urlencode("When would you like us to come?"),
"tool_tip_your_personal_details"=>urlencode("Your Personal Details"),
"tool_tip_have_a_promocode"=>urlencode("Have A Promocode"),
"tool_tip_preferred_payment_method"=>urlencode("Preferred Payment Method"),
		"login_page"=>urlencode("Login Page"),"front_page"=>urlencode("Front Page"),"before_e_g_100"=>urlencode("Before(e.g.$100)"),"after_e_g_100"=>urlencode("After(e.g.100$)"),"tax_vat"=>urlencode("Tax/Vat"),
		"wrong_url"=>urlencode("Wrong URL"),
		"choose_file"=>urlencode("Choose File"),
		"frontend_labels"=>urlencode("Frontend Labels"),
"admin_labels"=>urlencode("Admin Labels"),
"dropdown_design"=>urlencode("DropDown Design"),
"blocks_as_button_design"=>urlencode("Blocks As Button Design"),
"qty_control_design"=>urlencode("Qty Control Design"),
"dropdowns"=>urlencode("DropDowns"),
"big_images_radio"=>urlencode("Big Images Radio"),
"errors"=>urlencode("Errors"),
"extra_labels"=>urlencode("Extra Labels"),
"api_password"=>urlencode("API Password"),
"api_username"=>urlencode("API Username"),
"appearance"=>urlencode("APPEARANCE"),
"action"=>urlencode("Action"),
"actions"=>urlencode("Actions"),
"add_break"=>urlencode("Add Break"),
"add_breaks"=>urlencode("Add Breaks"),
"add_cleaning_service"=>urlencode("Add Cleaning Service"),
"add_method"=>urlencode("Add Method"),
"add_new"=>urlencode("Add New"),
"add_sample_data"=>urlencode("Add Sample Data"),
"add_unit"=>urlencode("Add Unit"),
"add_your_off_times"=>urlencode("Add Your Off Times"),
"add_new_off_time"=>urlencode("Add new off time"),
"add_ons"=>urlencode("Add-ons"),
"addons_bookings"=>urlencode("AddOns Bookings"),
"addon_service_front_view"=>urlencode("Addon-Service Front View"),
"addons"=>urlencode("Addons"),
"service_commission"=>urlencode("Service Commission"),
"commission_total"=>urlencode("Commission Total"),
"address"=>urlencode("Address"),
"new_appointment_assigned"=>urlencode("New Appointment Assigned"),
"admin_email_notifications"=>urlencode("Admin Email Notifications"),
"all_payment_gateways"=>urlencode("All Payment Gateways"),
"all_services"=>urlencode("All Services"),
"allow_multiple_booking_for_same_timeslot"=>urlencode("Allow Multiple Booking For Same Timeslot"),
"amount"=>urlencode("Amount"),
"app_date"=>urlencode("App. Date"),
"appearance_settings"=>urlencode("Appearance Settings"),
"appointment_completed"=>urlencode("Appointment Completed"),
"appointment_details"=>urlencode("Appointment Details"),
"appointment_marked_as_no_show"=>urlencode("Appointment Marked As No Show"),
"mark_as_no_show"=>urlencode("Mark As No Show"),
"appointment_reminder_buffer"=>urlencode("Appointment Reminder Buffer"),
"appointment_auto_confirm"=>urlencode("Appointment auto confirm"),
"appointments"=>urlencode("Appointments"),
"admin_area_color_scheme"=>urlencode("Admin Area Color Scheme"),
"thankyou_page_url"=>urlencode("Thankyou Page URL"),
"addon_title"=>urlencode("Addon Title"),
"availabilty"=>urlencode("Availabilty"),
"background_color"=>urlencode("Background color"),
"behaviour_on_click_of_button"=>urlencode("Behaviour on click of button"),
"book_now"=>urlencode("Book Now"),
"booking_date_and_time"=>urlencode("Booking Date & Time"),
"booking_details"=>urlencode("Booking Details"),
"booking_information"=>urlencode("Booking Information"),
"booking_serve_date"=>urlencode("Booking Serve Date"),
"booking_status"=>urlencode("Booking Status"),
"booking_notifications"=>urlencode("Booking notifications"),
"bookings"=>urlencode("Bookings"),
"button_position"=>urlencode("Button Position"),
"button_text"=>urlencode("Button Text"),
"company"=>urlencode("COMPANY"),
"cannot_cancel_now"=>urlencode("Cannot Cancel Now"),
"cannot_reschedule_now"=>urlencode("Cannot Reschedule Now"),
"cancel"=>urlencode("Cancel"),
"cancellation_buffer_time"=>urlencode("Cancellation Buffer Time"),
"cancelled_by_client"=>urlencode("Cancelled by client"),
"cancelled_by_service_provider"=>urlencode("Cancelled by service provider"),
"change_password"=>urlencode("Change password"),
"cleaning_service"=>urlencode("Cleaning Service"),
"client"=>urlencode("Client"),
"client_email_notifications"=>urlencode("Client Email Notifications"),
"client_name"=>urlencode("Client Name"),
"color_scheme"=>urlencode("Color Scheme"),
"color_tag"=>urlencode("Color Tag"),
"company_address"=>urlencode("Address"),
"company_email"=>urlencode("Email"),
"company_logo"=>urlencode("Company Logo"),
"company_name"=>urlencode("Business Name"),
"company_settings"=>urlencode("Business Info Settings"),
"companyname"=>urlencode("Company Name"),
"company_info_settings"=>urlencode("Company Info Settings"),
"completed"=>urlencode("Completed"),
"confirm"=>urlencode("Confirm"),
"confirmed"=>urlencode("Confirmed"),
"contact_status"=>urlencode("Contact Status"),
"country"=>urlencode("Country"),
"country_code_phone"=>urlencode("Country Code (phone)"),
"coupon"=>urlencode("Coupon"),
"coupon_code"=>urlencode("Coupon Code"),
"coupon_limit"=>urlencode("Coupon Limit"),
"coupon_type"=>urlencode("Coupon Type"),
"coupon_used"=>urlencode("Coupon Used"),
"coupon_value"=>urlencode("Coupon Value"),
"create_addon_service"=>urlencode("Create Addon Service"),
"crop_and_save"=>urlencode("Crop & Save"),
"currency"=>urlencode("Currency"),
"currency_symbol_position"=>urlencode("Currency Symbol Position"),
"customer"=>urlencode("Customer"),
"customer_information"=>urlencode("Customer Information"),
"customers"=>urlencode("Customers"),
"date_and_time"=>urlencode("Date & Time"),
"date_picker_date_format"=>urlencode("Date-Picker Date Format"),
"default_design_for_addons"=>urlencode("Default Design For Addons"),
"default_design_for_methods_with_multiple_units"=>urlencode("Default Design For Methods With Multiple units"),
"default_design_for_services"=>urlencode("Default Design For Services"),
"default_setting"=>urlencode("Default Setting"),
"delete"=>urlencode("Delete"),
"description"=>urlencode("Description"),
"discount"=>urlencode("Discount"),
"download_invoice"=>urlencode("Download Invoice"),
"email_notification"=>urlencode("EMAIL NOTIFICATION"),
"email"=>urlencode("Email"),
"email_settings"=>urlencode("Email Settings"),
"embed_code"=>urlencode("Embed Code"),
"enter_your_email_and_we_will_send_you_instructions_on_resetting_your_password"=>urlencode("Enter your email and we will send you instructions on resetting your password."),
"expiry_date"=>urlencode("Expiry Date"),
"export"=>urlencode("Export"),
"export_your_details"=>urlencode("Export Your Details"),
"frequently_discount_setting_tabs"=>urlencode("FREQUENTLY DISCOUNT"),
"frequently_discount_header"=>urlencode("Frequently Discount"),
"field_is_required"=>urlencode("Field is required"),
"file_size"=>urlencode("File size"),
"flat_fee"=>urlencode("Flat Fee"),
"flat"=>urlencode("Flat"),
"freq_discount"=>urlencode("Freq-Discount"),
"frequently_discount_label"=>urlencode("Frequently Discount Label"),
"frequently_discount_type"=>urlencode("Frequently Discount Type"),
"frequently_discount_value"=>urlencode("Frequently Discount Value"),
"front_service_box_view"=>urlencode("Front Service Box View"),
"front_service_dropdown_view"=>urlencode("Front Service Dropdown View"),
"front_view_options"=>urlencode("Front View Options"),
"full_name"=>urlencode("Full name"),
"general"=>urlencode("GENERAL"),
"general_settings"=>urlencode("General Settings"),
"get_embed_code_to_show_booking_widget_on_your_website"=>urlencode("Get embed code to show booking widget on your website"),
"get_the_embeded_code"=>urlencode("Get the Embeded Code"),
"guest_customers"=>urlencode("Guest Customers"),
"guest_user_checkout"=>urlencode("Guest user checkout"),
"hide_faded_already_booked_time_slots"=>urlencode("Hide faded already booked time slots"),
"color_already_full_bookings"=>urlencode("Color already full booking"),
"hostname"=>urlencode("Hostname"),
"labels"=>urlencode("LABELS"),
"legends"=>urlencode("Legends"),
"login"=>urlencode("Login"),
"maximum_advance_booking_time"=>urlencode("Maximum advance booking time"),
"method"=>urlencode("Method"),
"method_name"=>urlencode("Method Name"),
"method_title"=>urlencode("Method Title"),
"method_unit_quantity"=>urlencode("Method Unit Quantity"),
"method_unit_quantity_rate"=>urlencode("Method Unit Quantity Rate"),
"method_unit_title"=>urlencode("Method Unit Title"),
"method_units_front_view"=>urlencode("Method Units Front View "),
"methods"=>urlencode("Methods"),
"methods_booking"=>urlencode("Methods Booking"),
"methods_bookings"=>urlencode("Methods Bookings"),
"minimum_advance_booking_time"=>urlencode("Minimum advance booking time"),
"more"=>urlencode("More"),
"more_details"=>urlencode("More Details"),
"my_appointments"=>urlencode("My Appointments"),
"name"=>urlencode("Name"),
"net_total"=>urlencode("Net Total"),
"new_password"=>urlencode("New Password"),
"notes"=>urlencode("Notes"),
"off_days"=>urlencode("Off Days"),
"off_time"=>urlencode("Off Time"),
"old_password"=>urlencode("Old Password"),
"online_booking_button_style"=>urlencode("Online booking Button Style"),
"open_widget_in_a_new_page"=>urlencode("Open widget in a new page"),
"order"=>urlencode("Order"),
"order_date"=>urlencode("Order Date"),
"order_time"=>urlencode("Order Time"),
"payments_setting"=>urlencode("PAYMENT"),
"promocode"=>urlencode("PROMOCODE"),
"promocode_header"=>urlencode("Promocode"),
"padding_time_before"=>urlencode("Padding Time Before"),
"parking"=>urlencode("Parking"),
"partial_amount"=>urlencode("Partial Amount"),
"partial_deposit"=>urlencode("Partial Deposit"),
"partial_deposit_amount"=>urlencode("Partial Deposit Amount"),
"partial_deposit_message"=>urlencode("Partial Deposit Message"),
"password"=>urlencode("Password"),
"payment"=>urlencode("Payment"),
"payment_date"=>urlencode("Payment Date"),
"payment_gateways"=>urlencode("Payment Gateways"),
"payment_method"=>urlencode("Payment Method"),
"payments"=>urlencode("Payments"),
"payments_history_details"=>urlencode("Payments History Details"),
"paypal_express_checkout"=>urlencode("Paypal Express Checkout"),
"paypal_guest_payment"=>urlencode("Paypal guest payment"),
"pending"=>urlencode("Pending"),
"percentage"=>urlencode("Percentage"),
"personal_information"=>urlencode("Personal Information"),
"phone"=>urlencode("Phone"),
"please_copy_above_code_and_paste_in_your_website"=>urlencode("Please Copy above code and paste in your website."),
"please_enable_payment_gateway"=>urlencode("Please Enable Payment Gateway"),
"please_set_below_values"=>urlencode("Please Set Below Values"),
"port"=>urlencode("Port"),
"postal_codes"=>urlencode("Postal Codes"),
"price"=>urlencode("Price"),
"price_calculation_method"=>urlencode("Price calculation method"),
"price_format_decimal_places"=>urlencode("Price Format"),
"pricing"=>urlencode("Pricing"),
"primary_color"=>urlencode("Primary Color"),
"privacy_policy_link"=>urlencode("Privacy Policy Link"),
"profile"=>urlencode("Profile"),
"promocodes"=>urlencode("Promocodes"),
"promocodes_list"=>urlencode("Promocodes list"),
"registered_customers"=>urlencode("Registered Customers"),
"registered_customers_bookings"=>urlencode("Registered Customers Bookings"),
"reject"=>urlencode("Reject"),
"rejected"=>urlencode("Rejected"),
"remember_me"=>urlencode("Remember Me"),
"remove_sample_data"=>urlencode("Remove Sample Data"),
"reschedule"=>urlencode("Reschedule"),
"reset"=>urlencode("Reset"),
"reset_password"=>urlencode("Reset Password"),
"reshedule_buffer_time"=>urlencode("Reshedule Buffer Time"),
"retype_new_password"=>urlencode("Retype New Password"),
"right_side_description"=>urlencode("Booking Page Rightside Description"),
"save"=>urlencode("Save"),
"save_availability"=>urlencode("Save Availability"),
"save_setting"=>urlencode("Save Setting"),
"save_labels_setting"=>urlencode("Save Labels Setting"),
"schedule"=>urlencode("Schedule"),
"schedule_type"=>urlencode("Schedule Type"),
"secondary_color"=>urlencode("Secondary color"),
"select_language_for_update"=>urlencode("Select Language for update"),
"select_language_to_change_label"=>urlencode("Select language to change label"),
"select_language_to_display"=>urlencode("Language"),
"display_sub_headers_below_headers"=>urlencode("Sub Headings on Booking page"),
"select_payment_option_export_details"=>urlencode("Select payment option export details"),
"send_mail"=>urlencode("Send Mail"),
"sender_email_address_cleanto_admin_email"=>urlencode("Sender Email"),
"sender_name"=>urlencode("Sender Name"),
"service"=>urlencode("Service"),
"service_add_ons_front_block_view"=>urlencode("Service Add-ons Front Block View"),
"service_add_ons_front_increase_decrease_view"=>urlencode("Service Add-ons Front Increase/Decrease View"),
"service_description"=>urlencode("Service Description"),
"service_front_view"=>urlencode("Service Front View"),
"service_image"=>urlencode("Service Image"),
"service_methods"=>urlencode("Service Methods"),
"service_padding_time_after"=>urlencode("Service Padding Time After"),
"padding_time_after"=>urlencode("Padding Time After"),
"service_padding_time_before"=>urlencode("Service Padding Time Before"),
"service_quantity"=>urlencode("Service Quantity"),
"service_rate"=>urlencode("Service Rate"),
"service_title"=>urlencode("Service Title"),
"serviceaddons_name"=>urlencode("ServiceAddOns Name"),
"services"=>urlencode("Services"),
"services_information"=>urlencode("Services Information"),
"set_email_reminder_buffer"=>urlencode("Set Email Reminder Buffer"),
"set_language"=>urlencode("Set Language"),
"settings"=>urlencode("Settings"),
"show_all_bookings"=>urlencode("Show All Bookings"),
"show_button_on_given_embeded_position"=>urlencode("Show button on given embeded position"),
"show_coupons_input_on_checkout"=>urlencode("Show coupons input on checkout"),
"show_on_a_button_click"=>urlencode("Show on a button click"),
"show_on_page_load"=>urlencode("Show on page load"),
"signature"=>urlencode("Signature"),
"sorry_wrong_email_or_password"=>urlencode("Sorry Wrong Email Or Password"),
"start_date"=>urlencode("Start Date"),
"status"=>urlencode("Status"),
"submit"=>urlencode("Submit"),
"staff_email_notification"=>urlencode("Staff Email Notification"),
"tax"=>urlencode("Tax"),
"test_mode"=>urlencode("Test Mode"),
"text_color"=>urlencode("Text color"),
"text_color_on_bg"=>urlencode("Text Color on bg"),
"terms_and_condition_link"=>urlencode("Terms & Condition Link"),
"this_week_breaks"=>urlencode("This Week Breaks"),
"this_week_time_scheduling"=>urlencode("This Week Time Scheduling"),
"time_format"=>urlencode("Time Format"),
"time_interval"=>urlencode("Time Interval"),
"timezone"=>urlencode("TimeZone"),
"units"=>urlencode("Units"),
"unit_name"=>urlencode("Unit Name"),
"units_of_methods"=>urlencode("Units Of Methods"),
"update"=>urlencode("Update"),
"update_appointment"=>urlencode("Update Appointment"),
"update_promocode"=>urlencode("Update Promocode"),
"username"=>urlencode("Username"),
"vaccum_cleaner"=>urlencode("Vaccum-Cleaner"),
"view_slots_by"=>urlencode("View Slots By?"),
"week"=>urlencode("Week"),
"week_breaks"=>urlencode("Week Breaks"),
"week_time_scheduling"=>urlencode("Week Time Scheduling"),
"widget_loading_style"=>urlencode("Widget Loading style"),
"zip"=>urlencode("Zip"),
"logout"=>urlencode("logout"),
"to"=>urlencode("to"),
"add_new_promocode"=>urlencode("Add New Promocode"),
"create"=>urlencode("Create"),
"end_date"=>urlencode("End Date"),
"end_time"=>urlencode("End Time"),
"labels_settings"=>urlencode("Labels Settings"),
"limit"=>urlencode("Limit"),
"max_limit"=>urlencode("Max Limit"),
"start_time"=>urlencode("Start Time"),
"value"=>urlencode("Value"),
"active"=>urlencode("Active"),
"appointment_reject_reason"=>urlencode("Appointment Reject Reason"),
"search"=>urlencode("Search"),
"custom_thankyou_page_url"=>urlencode("Custom Thankyou Page Url"),
"price_per_unit"=>urlencode("Price Per unit"),
"confirm_appointment"=>urlencode("Confirm Appointment"),
"reject_reason"=>urlencode("Reject Reason"),
"delete_this_appointment"=>urlencode("Delete this appointment"),
"close_notifications"=>urlencode("Close Notifications"),
"booking_cancel_reason"=>urlencode("Booking Cancel reason"),
"service_color_badge"=>urlencode("Service color badge"),
"manage_price_calculation_methods"=>urlencode("Manage price calculation methods"),
"manage_addons_of_this_service"=>urlencode("Manage addons of this service"),
"service_is_booked"=>urlencode("Service is booked"),
"delete_this_service"=>urlencode("Delete this service"),
"delete_service"=>urlencode("Delete Service"),
"remove_image"=>urlencode("Remove Image"),
"remove_service_image"=>urlencode("Remove service image"),
"company_name_is_used_for_invoice_purpose"=>urlencode("Company name is used for invoice purpose"),
"remove_company_logo"=>urlencode("Remove Company Logo"),
"time_interval_is_helpful_to_show_time_difference_between_availability_time_slots"=>urlencode("Time interval is helpful to show time difference between availability time slots"),
"minimum_advance_booking_time_restrict_client_to_book_last_minute_booking_so_that_you_should_have_sufficient_time_before_appointment"=>urlencode("Minimum advance booking time restrict client to book last minute booking, so that you should have sufficient time before appointment"),
"cancellation_buffer_helps_service_providers_to_avoid_last_minute_cancellation_by_their_clients"=>urlencode("Cancellation buffer helps service providers to avoid last minute cancellation by their clients"),
"partial_payment_option_will_help_you_to_charge_partial_payment_of_total_amount_from_client_and_remaining_you_can_collect_locally"=>urlencode("Partial payment option will help you to charge partial payment of total amount from client and remaining you can collect locally"),
"allow_multiple_appointment_booking_at_same_time_slot_will_allow_you_to_show_availability_time_slot_even_you_have_booking_already_for_that_time"=>urlencode("Allow multiple appointment booking at same time slot, will allow you to show availability time slot even you have booking already for that time"),
"with_Enable_of_this_feature_Appointment_request_from_clients_will_be_auto_confirmed"=>urlencode("With Enable of this feature, Appointment request from clients will be auto confirmed"),
"write_html_code_for_the_right_side_panel"=>urlencode("Write HTML code for the right side panel"),
"do_you_want_to_show_subheaders_below_the_headers"=>urlencode("Do you want to show subheaders below the headers"),
"you_can_show_hide_coupon_input_on_checkout_form"=>urlencode("You can show/hide coupon input on checkout form"),
"with_this_feature_you_can_allow_a_visitor_to_book_appointment_without_registration"=>urlencode("With this feature you can allow a visitor to book appointment without registration"),
"paypal_api_username_can_get_easily_from_developer_paypal_com_account"=>urlencode("Paypal API username can get easily from developer.paypal.com account"),
"paypal_api_password_can_get_easily_from_developer_paypal_com_account"=>urlencode("Paypal API password can get easily from developer.paypal.com account"),
"paypal_api_signature_can_get_easily_from_developer_paypal_com_account"=>urlencode("Paypal API Signature can get easily from developer.paypal.com account"),
"let_user_pay_through_credit_card_without_having_paypal_account"=>urlencode("Let user pay through credit card without having Paypal account"),
"you_can_enable_paypal_test_mode_for_sandbox_account_testing"=>urlencode("You can enable Paypal test mode for sandbox account testing"),
"you_can_enable_authorize_net_test_mode_for_sandbox_account_testing"=>urlencode("You can enable Authorize.Net test mode for sandbox account testing"),
"edit_coupon_code"=>urlencode("Edit coupon code"),
"delete_promocode"=>urlencode("Delete Promocode?"),
"coupon_code_will_work_for_such_limit"=>urlencode("Coupon code will work for such limit"),
"coupon_code_will_work_for_such_date"=>urlencode("Coupon code will work for such date"),
"coupon_value_would_be_consider_as_percentage_in_percentage_mode_and_in_flat_mode_it_will_be_consider_as_amount_no_need_to_add_percentage_sign_it_will_auto_added"=>urlencode("Coupon Value would be consider as percentage in percentage mode and in flat mode it will be consider as amount.No need to add percentage sign it will auto added."),
"unit_is_booked"=>urlencode("Unit is Booked"),
"delete_this_service_unit"=>urlencode("Delete this service unit?"),
"delete_service_unit"=>urlencode("Delete Service Unit"),
"manage_unit_price"=>urlencode("Manage Unit Price"),
"extra_service_title"=>urlencode("Extra Service Title"),
"addon_is_booked"=>urlencode("Addon is Booked"),
"delete_this_addon_service"=>urlencode("Delete this addon service?"),
"choose_your_addon_image"=>urlencode("Choose your addon image"),
"addon_image"=>urlencode("Addon Image"),
"administrator_email"=>urlencode("Administrator Email"),
"company_settings"=>urlencode("Business Info Settings"),
"admin_profile_address"=>urlencode("Address"),
"select_language_to_display"=>urlencode("Language"),
"company_name"=>urlencode("Business Name"),
"company_email"=>urlencode("Email"),
"default_country_code"=>urlencode("Country Code"),
"company_address"=>urlencode("Address"),
"currency_symbol_position"=>urlencode("Currency Symbol Position"),
"price_format_decimal_places"=>urlencode("Price Format"),
"cancellation_policy"=>urlencode("Cancellation Policy"),
"allow_multiple_booking_for_same_timeslot"=>urlencode("Allow Multiple Booking For Same Timeslot"),
"right_side_description"=>urlencode("Booking Page Rightside Description"),
"display_sub_headers_below_headers"=>urlencode("Sub Headings on Booking page "),
"sender_email_address_cleanto_admin_email"=>urlencode("Sender Email"),
"transaction_id"=>urlencode("Transaction ID"),
"sms_reminder"=>urlencode("SMS Reminder"),
"save_sms_settings"=>urlencode("Save SMS Settings"),
"sms_service"=>urlencode("SMS Service"),
"it_will_send_sms_to_service_provider_and_client_for_appointment_booking"=>urlencode("It will send sms to service provider and client for appointment booking"),
"twilio_account_settings"=>urlencode("Twilio Account Settings"),
"plivo_account_settings"=>urlencode("Plivo Account Settings"),
"account_sid"=>urlencode("Account SID"),
"auth_token"=>urlencode("Auth Token"),
"twilio_sender_number"=>urlencode("Twilio Sender Number"),
"plivo_sender_number"=>urlencode("Plivo Sender Number"),
"twilio_sms_settings"=>urlencode("Twilio SMS Settings"),
"plivo_sms_settings"=>urlencode("Plivo SMS Settings"),
"twilio_sms_gateway"=>urlencode("Twilio SMS Gateway"),
"plivo_sms_gateway"=>urlencode("Plivo SMS Gateway"),
"send_sms_to_client"=>urlencode("Send SMS To Client"),
"send_sms_to_admin"=>urlencode("Send SMS To Admin"),
"admin_phone_number"=>urlencode("Admin Phone Number"),
"available_from_within_your_twilio_account"=>urlencode("Available from within your Twilio Account."),
"must_be_a_valid_number_associated_with_your_twilio_account"=>urlencode("Must be a valid number associated with your Twilio account."),
"enable_or_disable_send_sms_to_client_for_appointment_booking_info"=>urlencode("Enable or Disable, Send SMS to client for appointment booking info."),
"enable_or_disable_send_sms_to_admin_for_appointment_booking_info"=>urlencode("Enable or Disable, Send SMS to admin for appointment booking info."),
"updated_sms_settings"=>urlencode("Updated SMS Settings"),
"parking_availability_frontend_option_display_status"=>urlencode("Parking"),
"vaccum_cleaner_frontend_option_display_status"=>urlencode("Vaccume Cleaner"),
"o_n"=>urlencode("On"),
"off"=>urlencode("Off"),
"enable"=>urlencode("Enable"),
"disable"=>urlencode("Disable"),
"monthly"=>urlencode("Monthly"),
"weekly"=>urlencode("Weekly"),
"email_template"=>urlencode("EMAIL TEMPLATE"),
"sms_notification"=>urlencode("SMS NOTIFICATION"),
"sms_template"=>urlencode("SMS TEMPLATE"),
"email_template_settings"=>urlencode("Email Template Settings"),
"client_email_templates"=>urlencode("Client Email Template"),
"client_sms_templates"=>urlencode("Client SMS Template"),
"admin_email_template"=>urlencode("Admin Email Template"),
"admin_sms_template"=>urlencode("Admin SMS Template"),
"tags"=>urlencode("Tags"),
"booking_date"=>urlencode("booking_date"),
"service_name"=>urlencode("service_name"),
"business_logo"=>urlencode("business_logo"),
"business_logo_alt"=>urlencode("business_logo_alt"),
"admin_name"=>urlencode("admin_name"),
"client_name"=>urlencode("client_name"),
"methodname"=>urlencode("method_name"),
"firstname"=>urlencode("firstname"),
"lastname"=>urlencode("lastname"),
"client_email"=>urlencode("client_email"),
"vaccum_cleaner_status"=>urlencode("vaccum_cleaner_status"),
"parking_status"=>urlencode("parking_status"),
"app_remain_time"=>urlencode("app_remain_time"),
"reject_status"=>urlencode("reject_status"),
"save_template"=>urlencode("Save Template"),
"default_template"=>urlencode("Default Template"),
"sms_template_settings"=>urlencode("SMS Template Settings"),
"secret_key"=>urlencode("Secret Key"),
"publishable_key"=>urlencode("Publishable Key"),
"payment_form"=>urlencode("Payment Form"),
"api_login_id"=>urlencode("API Login ID"),
"transaction_key"=>urlencode("Transaction Key"),
"sandbox_mode"=>urlencode("Sandbox Mode"),
"available_from_within_your_plivo_account"=>urlencode("Available from within your Plivo Account."),
"must_be_a_valid_number_associated_with_your_plivo_account"=>urlencode("Must be a valid number associated with your Plivo account."),
"whats_new"=>urlencode("What's new?"),
"company_phone"=>urlencode("Phone"),
"default_country_code"=>urlencode("Country Code"),
"company__name"=>urlencode("company_name"),
"booking_time"=>urlencode("booking_time"),
"company__email"=>urlencode("company_email"),
"company__address"=>urlencode("company_address"),
"company__zip"=>urlencode("company_zip"),
"company__phone"=>urlencode("company_phone"),
"company__state"=>urlencode("company_state"),
"company__country"=>urlencode("company_country"),
"company__city"=>urlencode("company_city"),
"page_title"=>urlencode("Page Title"),
"client__zip"=>urlencode("client_zip"),
"client__state"=>urlencode("client_state"),
"client__city"=>urlencode("client_city"),
"client__address"=>urlencode("client_address"),
"client__phone"=>urlencode("client_phone"),
"company_logo_is_used_for_invoice_purpose"=>urlencode("Company Logo get used in email and booking page"),
"private_key"=>urlencode("Private Key"),
"seller_id"=>urlencode("Seller ID"),
"postal_codes_ed"=>urlencode("You can Enable or Disable Postal or Zip codes feature as per your country requirements, as some countries like UAE has not postal code."),
"postal_codes_info"=>urlencode("You can mention postal codes in two ways:
#1. You can mention full post codes for match like K1A232,L2A334,C3A4C4.
#2. You can use partial postal codes for wild card match entries,e.g. K1A,L2A,C3 ,system will match those starting letters of postal code on front and it will avoid you to write so many postal codes."),
"first"=>urlencode("First"),
"second"=>urlencode("Second"),
"third"=>urlencode("Third"),
"fourth"=>urlencode("Fourth"),
"fifth"=>urlencode("Fifth"),
"first_week"=>urlencode("First-Week"),
"second_week"=>urlencode("Second-Week"),
"third_week"=>urlencode("Third-Week"),
"fourth_week"=>urlencode("Fourth-Week"),
"fifth_week"=>urlencode("Fifth-Week"),
"this_week"=>urlencode("This Week"),
"monday"=>urlencode("Monday"),
"tuesday"=>urlencode("Tuesday"),
"wednesday"=>urlencode("Wednesday"),
"thursday"=>urlencode("Thursday"),
"friday"=>urlencode("Friday"),
"saturday"=>urlencode("Saturday"),
"sunday"=>urlencode("Sunday"),
"appointment_request"=>urlencode("Appointment Request"),
"appointment_approved"=>urlencode("Appointment Approved"),
"appointment_rejected"=>urlencode("Appointment Rejected"),
"appointment_cancelled_by_you"=>urlencode("Appointment Cancelled by you"),
"appointment_rescheduled_by_you"=>urlencode("Appointment Rescheduled by you"),
"client_appointment_reminder"=>urlencode("Client Appointment Reminder"),
"new_appointment_request_requires_approval"=>urlencode("New Appointment Request Requires Approval"),
"appointment_cancelled_by_customer"=>urlencode("Appointment Cancelled By Customer"),
"appointment_rescheduled_by_customer"=>urlencode("Appointment Rescheduled By Customer"),
"admin_appointment_reminder"=>urlencode("Admin Appointment Reminder"),
"off_days_added_successfully"=>urlencode("Off Days Added Successfully"),
"off_days_deleted_successfully"=>urlencode("Off Days Deleted Successfully"),
"sorry_not_available"=>urlencode("Sorry Not Available"),
"success"=>urlencode("Success"),
"failed"=>urlencode("Failed"),
"once"=>urlencode("Once"),
"weekly"=>urlencode("Weekly"),
"Bi_Monthly"=>urlencode("Bi-Monthly"),
"Fortnightly"=>urlencode("Fortnightly"),
"Recurrence_Type"=>urlencode("Recurrence Type"),
"bi_weekly"=>urlencode("Bi-Weekly"),
"monthly"=>urlencode("Monthly"),
"Daily"=>urlencode("Daily"),
"guest_customers_bookings"=>urlencode("Guest Customers Bookings"),
"existing_and_new_user_checkout"=>urlencode("Existing & new user checkout"),
"it_will_allow_option_for_user_to_get_booking_with_new_user_or_existing_user"=>urlencode("It will allow option for user to get booking with new user or existing user"),
"0_1"=>urlencode("01"),
"1_1"=>urlencode("1.1"),
"1_2"=>urlencode("1.2"),
"0_2"=>urlencode("02"),
"free"=>urlencode("Free"),
"show_company_address_in_header"=>urlencode("Show company address in header"),
"calendar_week"=>urlencode("Week"),
"calendar_month"=>urlencode("Month"),
"calendar_day"=>urlencode("Day"),
"calendar_today"=>urlencode("Today"),
"restore_default"=>urlencode("Restore Default"),
"scrollable_cart"=>urlencode("Scrollable Cart"),
"merchant_key"=>urlencode("Merchant Key"),
"salt_key"=>urlencode("Salt Key"),
"textlocal_sms_gateway"=>urlencode("Textlocal SMS Gateway"),
"textlocal_sms_settings"=>urlencode("Textlocal SMS Settings"),
"textlocal_account_settings"=>urlencode("Textlocal Account Settings"),
"account_username"=>urlencode("Account Username"),
"account_hash_id"=>urlencode("Account Hash ID"),
"email_id_registered_with_you_textlocal"=>urlencode("Provide your email registered with textlocal"),
"hash_id_provided_by_textlocal"=>urlencode("Hash id provided by textlocal"),
"bank_transfer"=>urlencode("Bank Transfer"),
"bank_name"=>urlencode("Bank Name"),
"account_name"=>urlencode("Account Name"),
"account_number"=>urlencode("Account Number"),
"branch_code"=>urlencode("Branch Code"),
"ifsc_code"=>urlencode("IFSC Code"),
"bank_description"=>urlencode("Bank Description"),
"your_cart_items"=>urlencode("Your Cart Items"),
"show_how_will_we_get_in"=>urlencode("Show How will we get in"),
"show_description"=>urlencode("Show Description"),
"bank_details"=>urlencode("Bank Details"),
"ok_remove_sample_data"=>urlencode("Ok"),
"book_appointment"=>urlencode("Book Appointment"),
"remove_sample_data_message"=>urlencode("You are trying to remove sample data. If you remove sample data your booking related with sample services will be permanently deleted. To proceed please click on 'OK'"), 
"recommended_image_type_jpg_jpeg_png_gif"=>urlencode("(Recommended image type jpg,jpeg,png,gif)"),
"authetication"=>urlencode("Authentication"),
"encryption_type"=>urlencode("Encryption Type"),
"plain"=>urlencode("Plain"),
"true"=>urlencode("True"),
"false"=>urlencode("False"),
"front_tool_tips"=>urlencode("FRONT TOOL TIPS"),
"change_calculation_policy"=>urlencode("Change Calculation"), 
"multiply"=>urlencode("Multiply"),
"equal"=>urlencode("Equal"),
"warning"=>urlencode("Warning!"),
"field_name"=>urlencode("Field Name"),
"enable_disable"=>urlencode("Enable/Disable"),
"required"=>urlencode("Required"),
"min_length"=>urlencode("Min Length"),
"max_length"=>urlencode("Max Length"),
"appointment_details_section"=>urlencode("Appointment Details Section"),
"if_you_are_having_booking_system_which_need_the_booking_address_then_please_make_this_field_enable_or_else_it_will_not_able_to_take_the_booking_address_and_display_blank_address_in_the_booking"=>urlencode("If you are having booking system which need the booking address then please make this field enable or else it will not able to take the booking address and display blank address in the booking"),
"front_language_dropdown"=>urlencode("Front Language Dropdown"),
"enabled"=>urlencode("Enabled "),
"vaccume_cleaner"=>urlencode("Vaccume Cleaner"),
"parking"=>urlencode("Parking"),
"staff_members"=>urlencode("Staff Members"),
"add_new_staff_member"=>urlencode("Add new staff member"),
"role"=>urlencode("Role"),
"staff"=>urlencode("Staff"),
"admin"=>urlencode("Admin"),
"create"=>urlencode("Create"),
"service_details"=>urlencode("Service Details"),
"technical_admin"=>urlencode("Technical Admin"),
"enable_booking"=>urlencode("Enable Booking"),
"service_commission"=>urlencode("Service Commission"),
"percentage"=>urlencode("Percentage"),
"flat_commission"=>urlencode("Flat Commission"),
"manageable_form_fields_front_booking_form"=>urlencode("Manageable Form Fields For Front Booking Form"),
"manageable_form_fields"=>urlencode("Manageable Form Fields"),
"save"=>urlencode("Save"),
		"sms"=>urlencode("SMS"),
		"crm"=>urlencode("CRM"),
		"message"=>urlencode("Message"),
		"send_message"=>urlencode("Send Message"),
		"all_messages"=>urlencode("All Messages"),
		"subject"=>urlencode("Subject"),
		"add_attachment"=>urlencode("Add Attachment"),
		"send"=>urlencode("Send"),
		"close"=>urlencode("Close"),
		"delete_this_customer?"=>urlencode("Delete This Customer?"),
		"yes"=>urlencode("Yes"),
		"add_new_customer"=>urlencode("Add New Customer"),
		"attachment"=>urlencode("attachment"),
		"date"=>urlencode("date"),
		"see_attachment"=>urlencode("See Attachment"),
		"no_attachment"=>urlencode("No Attachment"),
		"ct_special_offer"=>urlencode("Special Offer"),
		"ct_special_offer_text"=>urlencode("Special offer Text"),
		"ct_staff_status_booking"=>urlencode("Staff Status Of Booking"),
		"ct_staff_status"=>urlencode("Staff Status ")
		);

        $front_labels = array(
		"none_available"=>urlencode("None Available"),
		"appointment_zip" => urlencode("Appointment Zip"),
		"appointment_city" => urlencode("Appointment City"),
		"appointment_state" => urlencode("Appointment State"),
		"appointment_address" => urlencode("Appointment Address"),
		"guest_user"=>urlencode("Guest User"),
		"service_usage_methods"=>urlencode("Service Usage Methods"),
		"paypal"=>urlencode("Paypal"),
		"please_check_for_the_below_missing_information"=>urlencode("Please check for the below missing information."),
		"please_provide_company_details_from_the_admin_panel"=>urlencode("Please provide company details from the admin panel."),
		"please_add_some_services_methods_units_addons_from_the_admin_panel"=>urlencode("Please add some services, methods, units, addons from the admin panel."),
		"please_add_time_scheduling_from_the_admin_panel"=>urlencode("Please add time scheduling from the admin panel."),
		"please_complete_configurations_before_you_created_website_embed_code"=>urlencode("Please complete configurations before you created website embed code."),
		"cvc"=>urlencode("CVC"),
		"mm_yyyy"=>urlencode("(MM/YYYY)"),
		"expiry_date_or_csv"=>urlencode("Expiry date or CSV"),
		"street_address_placeholder"=>urlencode("e.g. Central Ave"),
		"zip_code_placeholder"=>urlencode("e.g. 90001"),
		"city_placeholder"=>urlencode("eg. Los Angeles"),
		"state_placeholder"=>urlencode("eg. CA"),
		"payumoney"=>urlencode("PayUmoney"),
		"same_as_above"=>urlencode("Same As Above"),
		"sun"=>urlencode("Sun"),
		"mon"=>urlencode("Mon"),
		"tue"=>urlencode("Tue"),
		"wed"=>urlencode("Wed"),
		"thu"=>urlencode("Thu"),
		"fri"=>urlencode("Fri"),
		"sat"=>urlencode("Sat"),
		"su"=>urlencode("Su"),
		"mo"=>urlencode("Mo"),
		"tu"=>urlencode("Tu"),
		"we"=>urlencode("We"),
		"th"=>urlencode("Th"),
		"fr"=>urlencode("Fr"),
		"sa"=>urlencode("Sa"),
		"my_bookings"=>urlencode("My Bookings"),
"your_postal_code"=>urlencode("Zip or Postal Code"),
"where_would_you_like_us_to_provide_service"=>urlencode("Where would you like us to provide service?"),
"choose_service"=>urlencode("Choose service"),
"how_often_would_you_like_us_provide_service"=>urlencode("How often would you like us provide service?"),
"when_would_you_like_us_to_come"=>urlencode("When would you like us to come?"),
"today"=>urlencode("TODAY"),
"your_personal_details"=>urlencode("Your Personal Details"),
"existing_user"=>urlencode("Existing User"),
"new_user"=>urlencode("New User"),
"preferred_email"=>urlencode("Preferred Email"),
"preferred_password"=>urlencode("Preferred Password"),
"your_valid_email_address"=>urlencode("Your valid email address"),
"first_name"=>urlencode("First Name"),
"your_first_name"=>urlencode("Your First Name"),
"last_name"=>urlencode("Last Name"),
"your_last_name"=>urlencode("Your Last Name"),
"street_address"=>urlencode("Street Address"),
"cleaning_service"=>urlencode("Cleaning Service"),
"please_select_method"=>urlencode("Please Select Method"),
"zip_code"=>urlencode("Zip Code"),
"city"=>urlencode("City"),
"state"=>urlencode("State"),
"special_requests_notes"=>urlencode("Special requests ( Notes )"),
"do_you_have_a_vaccum_cleaner"=>urlencode("Do you have a vacuum cleaner?"),
"assign_appointment_to_staff"=>urlencode("Assign Appointment to Staff"),
"delete_member"=>urlencode("Delete Member?"),
"yes"=>urlencode("Yes"),
"no"=>urlencode("No"),
"preferred_payment_method"=>urlencode("Preferred Payment Method"),
"please_select_one_payment_method"=>urlencode("Please select one payment method"),
"partial_deposit"=>urlencode("Partial Deposit"),
"remaining_amount"=>urlencode("Remaining Amount"),
"please_read_our_terms_and_conditions_carefully"=>urlencode("Please read our terms and conditions carefully"),
"do_you_have_parking"=>urlencode("Do you have parking?"),
"how_will_we_get_in"=>urlencode("How will we get in?"),
"i_will_be_at_home"=>urlencode("I'll be at home"),
"please_call_me"=>urlencode("Please call me"),
"recurring_discounts_apply_from_the_second_cleaning_onward"=>urlencode("Recurring discounts apply from the second cleaning onward."),
"please_provide_your_address_and_contact_details"=>urlencode("Please provide your address and contact details"),
"you_are_logged_in_as"=>urlencode("You are logged in as"),
"the_key_is_with_the_doorman"=>urlencode("The key is with the doorman"),
"other"=>urlencode("Other"),
"have_a_promocode"=>urlencode("Have a promocode?"),
"apply"=>urlencode("Apply"),
"applied_promocode"=>urlencode("Applied Promocode"),
"complete_booking"=>urlencode("Complete Booking"),
"cancellation_policy"=>urlencode("Cancellation Policy"),
"cancellation_policy_header"=>urlencode("Cancellation Policy Header"),
"cancellation_policy_textarea"=>urlencode("Cancellation Policy Textarea"),
"free_cancellation_before_redemption"=>urlencode("Free cancellation before redemption"),
"show_more"=>urlencode("Show More"),
"please_select_service"=>urlencode("Please Select Service"),
"choose_your_service_and_property_size"=>urlencode("Choose your service and property size"),
"choose_your_service"=>urlencode("Choose Your Service"),
"please_configure_first_cleaning_services_and_settings_in_admin_panel"=>urlencode("Please configure first Cleaning Services and settings in admin panel"),
"i_have_read_and_accepted_the"=>urlencode("I have read and accepted the "),
"terms_and_condition"=>urlencode("Terms & Conditions"),
"and"=>urlencode("and"),
"updated_labels"=>urlencode("Updated labels"),
"privacy_policy"=>urlencode("Privacy Policy"),
"please_fill_all_the_company_informations_and_add_some_services_and_addons"=>urlencode("Required configurations are not completed."),
"booking_summary"=>urlencode("Booking Summary"),
"your_email"=>urlencode("Your Email"),
"enter_email_to_login"=>urlencode("Enter Email to Login"),
"your_password"=>urlencode("Your Password"),
"enter_your_password"=>urlencode("Enter your Password"),
"forget_password"=>urlencode("Forget Password?"),
"reset_password"=>urlencode("Reset Password"),
"enter_your_email_and_we_send_you_instructions_on_resetting_your_password"=>urlencode("Enter your email and we'll send you instructions on resetting your password."),
"registered_email"=>urlencode("Registered Email"),
"send_mail"=>urlencode("Send Mail"),
"back_to_login"=>urlencode("Back to Login"),
"your"=>urlencode("Your"),
"your_clean_items"=>urlencode("Your clean items"),
"your_cart_is_empty"=>urlencode("Your cart is empty"),
"sub_totaltax"=>urlencode("Sub TotalTax"),
"sub_total"=>urlencode("Sub Total"),
"no_data_available_in_table"=>urlencode("No data available in table"),
"total"=>urlencode("Total"),
"or"=>urlencode("Or"),
"select_addon_image"=>urlencode("Select addon image"),
"inside_fridge"=>urlencode("Inside Fridge"),
"inside_oven"=>urlencode("Inside Oven"),
"inside_windows"=>urlencode("Inside Windows"),
"carpet_cleaning"=>urlencode("Carpet Cleaning"),
"green_cleaning"=>urlencode("Green Cleaning"),
"pets_care"=>urlencode("Pets Care"),
"tiles_cleaning"=>urlencode("Tiles Cleaning"),
"wall_cleaning"=>urlencode("Wall Cleaning"),
"laundry"=>urlencode("Laundry"),
"basement_cleaning"=>urlencode("Basement Cleaning"),
"basic_price"=>urlencode("Basic Price"),
"max_qty"=>urlencode("Max Qty"),
"multiple_qty"=>urlencode("Multiple Qty"),
"base_price"=>urlencode("Base Price"),
"unit_pricing"=>urlencode("Unit Pricing"),
"method_is_booked"=>urlencode("Method is booked"),
"service_addons_price_rules"=>urlencode("Service Addons price rules"),
"service_unit_front_dropdown_view"=>urlencode("Service Unit Front DropDown View"),
"service_unit_front_block_view"=>urlencode("Service Unit Front Block View"),
"service_unit_front_increase_decrease_view"=>urlencode("Service Unit Front Increase/Decrease View"),
"are_you_sure"=>urlencode("Are You Sure"),
"service_unit_price_rules"=>urlencode("Service Unit price rules"),
"close"=>urlencode("Close"),
"closed"=>urlencode("Closed"),
"service_addons"=>urlencode("Service Addons"),
"service_enable"=>urlencode("Service Enable"),
"service_disable"=>urlencode("Service Disable"),
"method_enable"=>urlencode("Method Enable"),
"off_time_deleted"=>urlencode("Off Time Deleted"),
"error_in_delete_of_off_time"=>urlencode("Error in Delete of Off Time"),
"method_disable"=>urlencode("Method Disable"),
"extra_services"=>urlencode("Extra Services"),
"for_initial_cleaning_only_contact_us_to_apply_to_recurrings"=>urlencode("For initial cleaning only. Contact us to apply to recurrings."),
"number_of"=>urlencode("Number of"),
"extra_services_not_available"=>urlencode("Extra Services Not Available"),
"available"=>urlencode("Available"),
"selected"=>urlencode("Selected"),
"not_available"=>urlencode("Not Available"),
"none"=>urlencode("None"),
"none_of_time_slot_available_please_check_another_dates"=>urlencode("None of time slot available Please check another dates"),
"availability_is_not_configured_from_admin_side"=>urlencode("Availability is not configured from admin side"),
"how_many_intensive"=>urlencode("How many Intensive"),
"no_intensive"=>urlencode("No Intensive"),
"frequently_discount"=>urlencode("Frequently Discount"),
"coupon_discount"=>urlencode("Coupon Discount"),
"how_many"=>urlencode("How many"),
"enter_your_other_option"=>urlencode("Enter your Other option"),
"log_out"=>urlencode("Log Out"),
"your_added_off_times"=>urlencode("Your Added Off Times"),
"log_in"=>urlencode("Log In"),
"custom_css"=>urlencode("Custom Css"),
"success"=>urlencode("Success"),
"failure"=>urlencode("Failure"),
"you_can_only_use_valid_zipcode"=>urlencode("You can only use valid zipcode"),
"minutes"=>urlencode("Minutes"),
"hours"=>urlencode("Hours"),
"days"=>urlencode("Days"),
"months"=>urlencode("Months"),
"year"=>urlencode("Year"),
"default_url_is"=>urlencode("Default url is"),
"card_payment"=>urlencode("Card payment"),
"pay_at_venue"=>urlencode("Pay At Venue"),
"card_details"=>urlencode("Card details"),
"card_number"=>urlencode("Card number"),
"invalid_card_number"=>urlencode("Invalid card number"),
"expiry"=>urlencode("Expiry"),
"button_preview"=>urlencode("Button Preview"),
"thankyou"=>urlencode("Thankyou"),
"thankyou_for_booking_appointment"=>urlencode("Thankyou! for booking appointment"),
"you_will_be_notified_by_email_with_details_of_appointment"=>urlencode("You will be notified by email with details of appointment"),
"please_enter_firstname"=>urlencode("Please enter firstname"),
"please_enter_lastname"=>urlencode("Please enter lastname"),
"remove_applied_coupon"=>urlencode("Remove applied coupon"),
"eg_799_e_dragram_suite_5a"=>urlencode("eg. 799 E DRAGRAM SUITE 5A"),
"eg_14114"=>urlencode("eg. 14114"),
"eg_tucson"=>urlencode("eg. TUCSON"),
"eg_az"=>urlencode("eg. AZ"),
"warning"=>urlencode("Warning"),
"try_later"=>urlencode("Try Later"),
"choose_your"=>urlencode("Choose Your"),
"configure_now_new"=>urlencode("Configure Now"),
"january"=>urlencode("JANUARY"),
"february"=>urlencode("FEBRUARY"),
"march"=>urlencode("MARCH"),
"april"=>urlencode("APRIL"),
"may"=>urlencode("MAY"),
"june"=>urlencode("JUNE"),
"july"=>urlencode("JULY"),
"august"=>urlencode("AUGUST"),
"september"=>urlencode("SEPTEMBER"),	
"october"=>urlencode("OCTOBER"),
"november"=>urlencode("NOVEMBER"),
"december"=>urlencode("DECEMBER"),
"jan"=>urlencode("JAN"),
"feb"=>urlencode("FEB"),
"mar"=>urlencode("MAR"),
"apr"=>urlencode("APR"),
"may"=>urlencode("MAY"),
"jun"=>urlencode("JUN"),
"jul"=>urlencode("JUL"),
"aug"=>urlencode("AUG"),
"sep"=>urlencode("SEP"),	
"oct"=>urlencode("OCT"),
"nov"=>urlencode("NOV"),
"dec"=>urlencode("DEC"),
"pay_locally"=>urlencode("Pay Locally"),
"please_select_provider"=>urlencode("Please select provider")

);

        $extra_labels = array(
"please_enter_minimum_3_chars"=>urlencode("Please enter minimum 3 Characters"),
"invoice"=>urlencode("INVOICE"),
"invoice_to"=>urlencode("INVOICE TO"),
"invoice_date"=>urlencode("Invoice Date"),
"cash"=>urlencode("CASH"),
"service_name"=>urlencode("Service Name"),
"qty"=>urlencode("Qty"),
"booked_on"=>urlencode("Booked On"));

		$front_form_error_labels = array(
		"min_ff_ps"=>urlencode("Please enter minimum 8 characters"),
		"max_ff_ps"=>urlencode("Please enter maximum 10 characters"),
		"req_ff_fn"=>urlencode("Please enter first name"),
		"min_ff_fn"=>urlencode("Please enter minimum 3 characters"),
		"max_ff_fn"=>urlencode("Please enter maximum 15 characters"),
		"req_ff_ln"=>urlencode("Please enter last name"),
		"min_ff_ln"=>urlencode("Please enter minimum 3 characters"),
		"max_ff_ln"=>urlencode("Please enter maximum 15 characters"),
		"req_ff_ph"=>urlencode("Please enter phone number"),
		"min_ff_ph"=>urlencode("Please enter minimum 9 characters"),
		"max_ff_ph"=>urlencode("Please enter maximum 15 characters"),
		"req_ff_sa"=>urlencode("Please enter street address"),
		"min_ff_sa"=>urlencode("Please enter minimum 10 characters"),
		"max_ff_sa"=>urlencode("Please enter maximum 40 characters"),
		"req_ff_zp"=>urlencode("Please enter zip code"),
		"min_ff_zp"=>urlencode("Please enter minimum 3 characters"),
		"max_ff_zp"=>urlencode("Please enter maximum 7 characters"),
		"req_ff_ct"=>urlencode("Please enter city"),
		"min_ff_ct"=>urlencode("Please enter minimum 3 characters"),
		"max_ff_ct"=>urlencode("Please enter maximum 15 characters"),
		"req_ff_st"=>urlencode("Please enter state"),
		"min_ff_st"=>urlencode("Please enter minimum 3 characters"),
		"max_ff_st"=>urlencode("Please enter maximum 15 characters"),
		"req_ff_srn"=>urlencode("Please enter notes"),
		"min_ff_srn"=>urlencode("Please enter minimum 10 characters"),
		"max_ff_srn"=>urlencode("Please enter maximum 70 characters"),
		"Transaction_failed_please_try_again"=>urlencode("Transaction failed please try again"),
		"Please_Enter_valid_card_detail"=>urlencode("Please Enter valid card detail"));
		
        $language_front_arr = base64_encode(serialize($front_labels));
        $language_admin_arr = base64_encode(serialize($admin_labels));
        $language_error_arr = base64_encode(serialize($errors));
        $language_extra_arr = base64_encode(serialize($extra_labels));
        $language_form_error_arr = base64_encode(serialize($front_form_error_labels));
		
        $delete_default_lang = "TRUNCATE TABLE  `ct_languages`;";
        mysqli_query($this->conn, $delete_default_lang);
		
        $insert_default_lang = "insert into `ct_languages` (`id`,`label_data`,`language`, `admin_labels`, `error_labels`, `extra_labels`, `front_error_labels`,`language_status`) values(NULL,'" . $language_front_arr . "','en','" . $language_admin_arr . "','" . $language_error_arr . "','" . $language_extra_arr . "','" . $language_form_error_arr . "','Y')";
        mysqli_query($this->conn, $insert_default_lang);
		
/** German Language **/
$label_data_de_DE = array (
"none_available"=>urlencode("Keine verfügbar"),
"appointment_zip"=>urlencode(" Termin Zip"),
"appointment_city"=>urlencode("Verabredung Stadt"),
"appointment_state"=>urlencode("Ernennung Staat"),
"appointment_address"=>urlencode("Terminadresse"),
"guest_user"=>urlencode("Gastbenutzer"),
"service_usage_methods"=>urlencode(" Dienstverwendungsmethoden"),
"paypal"=>urlencode("Paypal"),
"please_check_for_the_below_missing_information"=>urlencode("Bitte überprüfen Sie die folgenden fehlenden Informationen."),
"please_provide_company_details_from_the_admin_panel"=>urlencode("Bitte geben Sie die Firmendaten über das Admin-Panel an."),
"please_add_some_services_methods_units_addons_from_the_admin_panel"=>urlencode("Bitte fügen Sie einige Dienste, Methoden, Einheiten, Addons aus dem Admin-Panel hinzu."),
"please_add_time_scheduling_from_the_admin_panel"=>urlencode("Bitte fügen Sie die Zeitplanung über das Admin-Panel hinzu."),
"please_complete_configurations_before_you_created_website_embed_code"=>urlencode(" Bitte schließen Sie die Konfigurationen ab, bevor Sie den Code zum Einbetten der Website erstellt haben."),
"cvc"=>urlencode("CVC"),
"mm_yyyy"=>urlencode("(MM / JJJJ)"),
"expiry_date_or_csv"=>urlencode(" Ablaufdatum oder CSV"),
"street_address_placeholder"=>urlencode("z.B. Zentrale Ave"),
"zip_code_placeholder"=>urlencode("gt; 90001"),
"city_placeholder"=>urlencode("z.B. Los Angeles"),
"state_placeholder"=>urlencode("zB. CA"),
"payumoney"=>urlencode("PayUmoney"),
"same_as_above"=>urlencode("Das gleiche wie oben"),
"sun"=>urlencode(" Sonne"),
"mon"=>urlencode("Montag"),
"tue"=>urlencode("Di"),
"wed"=>urlencode("Heiraten"),
"thu"=>urlencode("Do"),
"fri"=>urlencode("Fr"),
"sat"=>urlencode("Sa."),
"su"=>urlencode("Dein"),
"mo"=>urlencode(" Du"),
"tu"=>urlencode("Du"),
"we"=>urlencode("Wir"),
"th"=>urlencode("Th"),
"fr"=>urlencode(" Fr"),
"sa"=>urlencode(" sie"),
"my_bookings"=>urlencode("Meine Buchungen"),
"your_postal_code"=>urlencode("Postleitzahl"),
"where_would_you_like_us_to_provide_service"=>urlencode("Wo möchten Sie uns unterstützen?"),
"choose_service"=>urlencode("Wählen Sie den Service"),
"how_often_would_you_like_us_provide_service"=>urlencode("Wie oft möchten Sie, dass wir Ihnen einen Service bieten?"),
"when_would_you_like_us_to_come"=>urlencode("Wann möchten Sie, dass wir kommen?"),
"today"=>urlencode(" HEUTE"),
"your_personal_details"=>urlencode("Deine Persönlichen Details"),
"existing_user"=>urlencode("Existierender Benutzer"),
"new_user"=>urlencode("Neuer Benutzer"),
"preferred_email"=>urlencode("Bevorzugte E-Mail-Adresse"),
"preferred_password"=>urlencode("Bevorzugtes Passwort"),
"your_valid_email_address"=>urlencode(" Ihre gültige E-Mail-Adresse"),
"first_name"=>urlencode("Vorname"),
"your_first_name"=>urlencode("Ihr Vorname"),
"last_name"=>urlencode("Familienname, Nachname"),
"your_last_name"=>urlencode("Ihr Nachname"),
"street_address"=>urlencode("Adresse"),
"cleaning_service"=>urlencode("Reinigungsservice"),
"please_select_method"=>urlencode("Bitte wählen Sie Methode"),
"zip_code"=>urlencode("Postleitzahl"),
"city"=>urlencode("Stadt"),
"state"=>urlencode("Zustand"),
"special_requests_notes"=>urlencode("Sonderwünsche (Hinweise)"),
"do_you_have_a_vaccum_cleaner"=>urlencode("Hast du einen Staubsauger?"),
"assign_appointment_to_staff"=>urlencode("Termin dem Personal zuweisen"),
"delete_member"=>urlencode("Mitglied löschen?"),
"yes"=>urlencode(" Ja"),
"no"=>urlencode(" Nein"),
"preferred_payment_method"=>urlencode("Bevorzugte Zahlungsmethode"),
"please_select_one_payment_method"=>urlencode(" Bitte wählen Sie eine Zahlungsart aus"),
"partial_deposit"=>urlencode("Teilkaution"),
"remaining_amount"=>urlencode("Restbetrag"),
"please_read_our_terms_and_conditions_carefully"=>urlencode("Bitte lesen Sie unsere AGB sorgfältig durch"),
"do_you_have_parking"=>urlencode("Haben Sie Parkplätze?"),
"how_will_we_get_in"=>urlencode("Wie werden wir reinkommen?"),
"i_will_be_at_home"=>urlencode(" Ich werde zu Hause sein"),
"please_call_me"=>urlencode(" Bitte ruf mich an"),
"recurring_discounts_apply_from_the_second_cleaning_onward"=>urlencode("Wiederkehrende Rabatte gelten ab der zweiten Reinigung."),
"please_provide_your_address_and_contact_details"=>urlencode("Bitte geben Sie Ihre Adresse und Kontaktdaten an"),
"you_are_logged_in_as"=>urlencode("Du bist eingeloggt als"),
"the_key_is_with_the_doorman"=>urlencode("Der Schlüssel ist mit dem Portier"),
"other"=>urlencode("Andere"),
"have_a_promocode"=>urlencode(" Haben Sie einen Promo-Code?"),
"apply"=>urlencode("Sich bewerben"),
"applied_promocode"=>urlencode("Angewandter Promocode"),
"complete_booking"=>urlencode("Komplette Buchung"),
"cancellation_policy"=>urlencode("Stornierungsbedingungen"),
"cancellation_policy_header"=>urlencode("Stornierungsrichtlinien-Header"),
"cancellation_policy_textarea"=>urlencode("Widerrufsbelehrung"),
"free_cancellation_before_redemption"=>urlencode("Kostenlose Stornierung vor der Einlösung"),
"show_more"=>urlencode("Zeig mehr"),
"please_select_service"=>urlencode("Bitte wählen Sie Service"),
"choose_your_service_and_property_size"=>urlencode("Wählen Sie Ihre Service- und Grundstücksgröße"),
"choose_your_service"=>urlencode("Wählen Sie Ihren Service"),
"please_configure_first_cleaning_services_and_settings_in_admin_panel"=>urlencode(" Bitte konfigurieren Sie die ersten Reinigungsdienste und Einstellungen im Admin-Panel"),
"i_have_read_and_accepted_the"=>urlencode(" Ich habe gelesen und akzeptiert"),
"terms_and_condition"=>urlencode("Terms & amp; Bedingungen"),
"and"=>urlencode("und"),
"updated_labels"=>urlencode("Aktualisierte Etiketten"),
"privacy_policy"=>urlencode("Datenschutz-Bestimmungen"),
"please_fill_all_the_company_informations_and_add_some_services_and_addons"=>urlencode("Erforderliche Konfigurationen sind nicht abgeschlossen."),
"booking_summary"=>urlencode("Buchungsübersicht"),
"your_email"=>urlencode("Deine E-Mail"),
"enter_email_to_login"=>urlencode("Geben Sie E-Mail-Adresse ein"),
"your_password"=>urlencode("Ihr Passwort"),
"enter_your_password"=>urlencode(" Geben Sie Ihr Passwort ein"),
"forget_password"=>urlencode(" Passwort vergessen?"),
"reset_password"=>urlencode("Passwort zurücksetzen"),
"enter_your_email_and_we_send_you_instructions_on_resetting_your_password"=>urlencode("Geben Sie Ihre E-Mail-Adresse ein und wir senden Ihnen Anweisungen zum Zurücksetzen Ihres Passworts."),
"registered_email"=>urlencode("Registrierte Email"),
"send_mail"=>urlencode("E-Mail senden"),
"back_to_login"=>urlencode("Zurück zur Anmeldung"),
"your"=>urlencode("Ihre"),
"your_clean_items"=>urlencode("Deine sauberen Gegenstände"),
"your_cart_is_empty"=>urlencode("Ihr Warenkorb ist leer"),
"sub_totaltax"=>urlencode(" Sub TotalTax"),
"sub_total"=>urlencode("Untersumme"),
"no_data_available_in_table"=>urlencode(" Keine Daten in der Tabelle verfügbar"),
"total"=>urlencode("Gesamt"),
"or"=>urlencode("Oder"),
"select_addon_image"=>urlencode("Wählen Sie ein Zusatzbild aus"),
"inside_fridge"=>urlencode("Innen Kühlschrank"),
"inside_oven"=>urlencode(" Innen Ofen"),
"inside_windows"=>urlencode("In Windows"),
"carpet_cleaning"=>urlencode("Teppichreinigung"),
"green_cleaning"=>urlencode("Grüne Reinigung"),
"pets_care"=>urlencode("Haustiere Pflege"),
"tiles_cleaning"=>urlencode("Fliesen Reinigung"),
"wall_cleaning"=>urlencode("Wandreinigung"),
"laundry"=>urlencode("Wäsche"),
"basement_cleaning"=>urlencode("Kellerreinigung"),
"basic_price"=>urlencode("Grundpreis"),
"max_qty"=>urlencode("Max. Menge"),
"multiple_qty"=>urlencode("Mehrfache Menge"),
"base_price"=>urlencode("Grundpreis"),
"unit_pricing"=>urlencode("Stückpreis"),
"method_is_booked"=>urlencode("Methode ist gebucht"),
"service_addons_price_rules"=>urlencode("Preisregeln für Service-Add-ons"),
"service_unit_front_dropdown_view"=>urlencode("Vorderansicht der Service Unit DropDown"),
"service_unit_front_block_view"=>urlencode("Vorderseite der Wartungseinheit Blockansicht"),
"service_unit_front_increase_decrease_view"=>urlencode("Service Unit Front Ansicht vergrößern / verkleinern"),
"are_you_sure"=>urlencode("Bist du sicher"),
"service_unit_price_rules"=>urlencode("Service-Preis-Regeln"),
"close"=>urlencode("Schließen"),
"closed"=>urlencode("Geschlossen"),
"service_addons"=>urlencode("Service-Erweiterungen"),
"service_enable"=>urlencode("Service aktivieren"),
"service_disable"=>urlencode("Dienst deaktivieren"),
"method_enable"=>urlencode("Methode aktivieren"),
"off_time_deleted"=>urlencode("Auszeit gelöscht"),
"error_in_delete_of_off_time"=>urlencode("Fehler beim Löschen der Auszeit"),
"method_disable"=>urlencode("Methode Deaktivieren"),
"extra_services"=>urlencode("Extra Dienstleistungen"),
"for_initial_cleaning_only_contact_us_to_apply_to_recurrings"=>urlencode(" Nur zur anfänglichen Reinigung. Kontaktieren Sie uns, um die Rekursion zu beantragen."),
"number_of"=>urlencode("Anzahl von"),
"extra_services_not_available"=>urlencode("Zusätzliche Dienste nicht verfügbar"),
"available"=>urlencode("Verfügbar"),
"selected"=>urlencode(" Ausgewählt"),
"not_available"=>urlencode("Nicht verfügbar"),
"none"=>urlencode(" Keiner"),
"none_of_time_slot_available_please_check_another_dates"=>urlencode("Kein Zeitfenster verfügbar Bitte überprüfen Sie ein anderes Datum"),
"availability_is_not_configured_from_admin_side"=>urlencode("Verfügbarkeit ist nicht von der Admin-Seite konfiguriert"),
"how_many_intensive"=>urlencode("Wie viele Intensiv"),
"no_intensive"=>urlencode("Kein Intensiv"),
"frequently_discount"=>urlencode("Häufig Rabatt"),
"coupon_discount"=>urlencode(" Gutschein Rabatt"),
"how_many"=>urlencode("Wie viele"),
"enter_your_other_option"=>urlencode("Geben Sie Ihre andere Option ein"),
"log_out"=>urlencode("Ausloggen"),
"your_added_off_times"=>urlencode("Ihre addierten Zeiten"),
"log_in"=>urlencode("Anmeldung"),
"custom_css"=>urlencode("Benutzerdefinierte CSS"),
"success"=>urlencode("Erfolg"),
"failure"=>urlencode("Fehler"),
"you_can_only_use_valid_zipcode"=>urlencode("Sie können nur eine gültige Postleitzahl verwenden"),
"minutes"=>urlencode("Protokoll"),
"hours"=>urlencode(" Std"),
"days"=>urlencode(" Tage"),
"months"=>urlencode("Monate"),
"year"=>urlencode(" Jahr"),
"default_url_is"=>urlencode("Standard-URL ist"),
"card_payment"=>urlencode("Kartenzahlung"),
"pay_at_venue"=>urlencode("Am Veranstaltungsort bezahlen"),
"card_details"=>urlencode("Kartendetails"),
"card_number"=>urlencode("Kartennummer"),
"invalid_card_number"=>urlencode("Ungültige Kartennumme"),
"expiry"=>urlencode(" Ablauf"),
"button_preview"=>urlencode("Schaltfläche Vorschau"),
"thankyou"=>urlencode("Vielen Dank"),
"thankyou_for_booking_appointment"=>urlencode(" Vielen Dank! für Buchungstermin"),
"you_will_be_notified_by_email_with_details_of_appointment"=>urlencode("Sie werden per E-Mail mit Details des Termins benachrichtigt"),
"please_enter_firstname"=>urlencode("Bitte geben Sie Vorname ein"),
"please_enter_lastname"=>urlencode("Bitte geben Sie den Nachnamen ein"),
"remove_applied_coupon"=>urlencode(" Entfernen Sie den aufgebrachten Gutschein"),
"eg_799_e_dragram_suite_5a"=>urlencode("zB. 799 E DRAGRAM SUITE 5A"),
"eg_14114"=>urlencode("z.B. 14114"),
"eg_tucson"=>urlencode("z.B. TUCSON"),
"eg_az"=>urlencode(" zB. DAS"),
"warning"=>urlencode("Warnung"),
"try_later"=>urlencode("Versuche es später"),
"choose_your"=>urlencode("Wähle deinen"),
"configure_now_new"=>urlencode("Jetzt konfigurieren"),
"january"=>urlencode("JANUAR"),
"february"=>urlencode("FEBRUAR"),
"march"=>urlencode(" MÄRZ"),
"april"=>urlencode(" APRIL"),
"may"=>urlencode("KANN"),
"june"=>urlencode(" JUNI"),
"july"=>urlencode("JULI"),
"august"=>urlencode(" AUGUST"),
"september"=>urlencode("SEPTEMBER"),
"october"=>urlencode("OKTOBER"),
"november"=>urlencode("NOVEMBER"),
"december"=>urlencode("DEZEMBER"),
"jan"=>urlencode("JAN"),
"feb"=>urlencode("FEB"),
"mar"=>urlencode("BESCHÄDIGEN"),
"apr"=>urlencode("APR"),
"jun"=>urlencode("JUN"),
"jul"=>urlencode("JUL"),
"aug"=>urlencode("AUG"),
"sep"=>urlencode("SEP"),
"oct"=>urlencode("OKT"),
"nov"=>urlencode("NOV"),
"dec"=>urlencode("DEC"),
"pay_locally"=>urlencode("Vor Ort bezahlen"),
"please_select_provider"=>urlencode("Bitte wählen Sie den Anbieter aus"),
);

$admin_labels_de_DE = array (
"payment_status"=>urlencode("Betalingsstatus"),
"staff_booking_status"=>urlencode("Boekingstatus personeel"),
"accept"=>urlencode("Aanvaarden"),
"accepted"=>urlencode("Aanvaard"),
"decline"=>urlencode("Afwijzen"),
"paid"=>urlencode("Betaald"),
"eway"=>urlencode("Eway"),
"half_section"=>urlencode("Halber Abschnitt"),
"option_title"=>urlencode("Option Titel"),
"merchant_ID"=>urlencode("Händler-ID"),
"How_it_works"=>urlencode("Wie es funktioniert?"),
"Your_currency_should_be_AUD_to_enable_payway_payment_gateway"=>urlencode("Ihre Währung sollte Australien Dollar sein, um Payway Payment Gateway zu ermöglichen"),
"secure_key"=>urlencode(" Sicherer Schlüssel"),
"payway"=>urlencode("Payway"),
"Your_Google_calendar_id_where_you_need_to_get_alerts_its_normaly_your_Gmail_ID"=>urlencode("Ihre Google Kalender-ID, bei der Sie Benachrichtigungen erhalten müssen, ist normalerweise Ihre Google Mail-ID. z.B. johndoe@beispiel.de"),
"You_can_get_your_client_ID_from_your_Google_Calendar_Console"=>urlencode(" Sie können Ihre Client-ID über Ihre Google Kalender-Konsole abrufen"),
"You_can_get_your_client_secret_from_your_Google_Calendar_Console"=>urlencode("Sie können Ihren Client in Ihrer Google Kalender-Konsole geheim halten"),
"its_your_Cleanto_booking_form_page_url"=>urlencode(" es ist Ihre Cleanto Buchungsformular Seite URL"),
"Its_your_Cleanto_Google_Settings_page_url"=>urlencode("Es ist Ihre Cleanto Google Einstellungen Seite URL"),
"Add_Manual_booking"=>urlencode("Manuelle Buchung hinzufügen"),
"Google_Calender_Settings"=>urlencode("Google Kalendereinstellungen"),
"Add_Appointments_To_Google_Calender"=>urlencode("Termine zu Google Kalender hinzufügen"),
"Google_Calender_Id"=>urlencode("Google Kalender-ID"),
"Google_Calender_Client_Id"=>urlencode(" Google Kalender-Client-ID"),
"Google_Calender_Client_Secret"=>urlencode("Google Kalender-Client-Geheimnis"),
"Google_Calender_Frontend_URL"=>urlencode("Google Kalender-Front-End-URL"),
"Google_Calender_Admin_URL"=>urlencode("Google Kalender-Administrator-URL"),
"Google_Calender_Configuration"=>urlencode("Google Kalenderkonfiguration"),
"Two_Way_Sync"=>urlencode("Zwei-Wege-Synchronisierung"),
"Verify_Account"=>urlencode("Konto überprüfen"),
"Select_Calendar"=>urlencode("Wählen Sie Kalender"),
"Disconnect"=>urlencode("Trennen"),
"Calendar_Fisrt_Day"=>urlencode(" Kalender erster Tag"),
"Calendar_Default_View"=>urlencode("Kalender Standardansicht"),
"Show_company_title"=>urlencode("Firmentitel anzeigen"),
"front_language_flags_list"=>urlencode("Front Sprachen Flagge Liste"),
"Google_Analytics_Code"=>urlencode("Google Analytics-Code"),
"Page_Meta_Tag"=>urlencode("Seite / Meta-Tag"),
"SEO_Settings"=>urlencode("SEO Einstellungen"),
"Meta_Description"=>urlencode("Meta Beschreibung"),
"SEO"=>urlencode("SEO"),
"og_tag_image"=>urlencode("und nimm Bild"),
"og_tag_url"=>urlencode("und Tag-URL"),
"og_tag_type"=>urlencode("und Tagtyp"),
"og_tag_title"=>urlencode("und Tag-Titel"),
"Quantity"=>urlencode("Menge"),
"Send_Invoice"=>urlencode("Rechnung senden"),
"Recurrence"=>urlencode("Wiederholung"),
"Recurrence_booking"=>urlencode("Wiederholung Buchung"),
"Reset_Color"=>urlencode("Farbe zurücksetzen"),
"Loader"=>urlencode("Lader"),
"CSS_Loader"=>urlencode("CSS-Lader"),
"GIF_Loader"=>urlencode("GIF-Lader"),
"Default_Loader"=>urlencode("Standardlader"),
"for_a"=>urlencode(" Für ein"),
"show_company_logo"=>urlencode(" Firmenlogo anzeigen"),
"on"=>urlencode("auf"),
"user_zip_code"=>urlencode("Postleitzahl"),
"delete_this_method"=>urlencode("Löschen Sie diese Methode?"),
"authorize_net"=>urlencode("Autorisieren.Net"),
"staff_details"=>urlencode("Mitarbeiterdetails"),
"client_payments"=>urlencode(" Kundenzahlungen"),
"staff_payments"=>urlencode(" Personalzahlungen"),
"staff_payments_details"=>urlencode("Personalzahlungen Details"),
"advance_paid"=>urlencode("Vorauszahlung"),
"change_calculation_policyy"=>urlencode("Ändern Sie die Berechnungsrichtlinie"),
"frontend_fonts"=>urlencode("Front-End-Schriftarten"),
"favicon_image"=>urlencode(" Favicon Bild"),
"staff_email_template"=>urlencode("Mitarbeiter E-Mail-Vorlage"),
"staff_details_add_new_and_manage_staff_payments"=>urlencode("Personaldetails, neue Mitarbeiter hinzufügen und Mitarbeiterzahlungen verwalten"),
"add_staff"=>urlencode("Personal hinzufügen"),
"staff_bookings_and_payments"=>urlencode("Mitarbeiter Buchungen und Zahlungen"),
"staff_booking_details_and_payment"=>urlencode("Mitarbeiter Buchungsdetails und Zahlung"),
"select_option_to_show_bookings"=>urlencode("Wählen Sie die Option, um Buchungen anzuzeigen"),
"select_service"=>urlencode("Wählen Sie Service"),
"staff_name"=>urlencode("Personal Name"),
"staff_payment"=>urlencode("Personalzahlung"),
"add_payment_to_staff_account"=>urlencode("Fügen Sie dem Mitarbeiterkonto Zahlung hinzu"),
"amount_payable"=>urlencode(" Bezahlbarer Betrag"),
"save_changes"=>urlencode(" Änderungen speichern"),
"front_error_labels"=>urlencode("Front Error Etiketten"),
"stripe"=>urlencode(" Streifen"),
"checkout_title"=>urlencode("2Checkout"),
"nexmo_sms_gateway"=>urlencode("Nexmo SMS Gateway"),
"nexmo_sms_setting"=>urlencode("Nexmo SMS Einstellung"),
"nexmo_api_key"=>urlencode(" Nexmo-API-Schlüssel"),
"nexmo_api_secret"=>urlencode("Nexmo-API-Geheimnis"),
"nexmo_from"=>urlencode("Nexmo von"),
"nexmo_status"=>urlencode("Nexmo Status"),
"nexmo_send_sms_to_client_status"=>urlencode("Nexmo sendet SMS an den Client-Status"),
"nexmo_send_sms_to_admin_status"=>urlencode(" Nexmo Senden Sms Zum Admin Status"),
"nexmo_admin_phone_number"=>urlencode("Nexmo Admin Telefonnummer"),
"save_12_5"=>urlencode("12,5% sparen"),
"front_tool_tips"=>urlencode("VORDERE WERKZEUG-TIPPS"),
"front_tool_tips_lower"=>urlencode("Tipps zum vorderen Werkzeug"),
"tool_tip_my_bookings"=>urlencode("Meine Buchungen"),
"tool_tip_postal_code"=>urlencode("Postleitzahl"),
"tool_tip_services"=>urlencode("Dienstleistungen"),
"tool_tip_extra_service"=>urlencode("Extra service"),
"tool_tip_frequently_discount"=>urlencode("Häufig Rabatt"),
"tool_tip_when_would_you_like_us_to_come"=>urlencode("Wann möchten Sie, dass wir kommen?"),
"tool_tip_your_personal_details"=>urlencode("Deine Persönlichen Details"),
"tool_tip_have_a_promocode"=>urlencode(" Haben Sie einen Promo-Code"),
"tool_tip_preferred_payment_method"=>urlencode("Bevorzugte Zahlungsmethode"),
"login_page"=>urlencode("Loginseite"),
"front_page"=>urlencode("Titelseite"),
"before_e_g_100"=>urlencode("Vorher (z. B. 100 $)"),
"after_e_g_100"=>urlencode("Nachher (z. B. 100 $)"),
"tax_vat"=>urlencode("Steuer / Mehrwertsteuer"),
"wrong_url"=>urlencode("Falsche URL"),
"choose_file"=>urlencode("Datei wählen"),
"frontend_labels"=>urlencode("Datei wählen"),
"admin_labels"=>urlencode("Admin-Labels"),
"dropdown_design"=>urlencode("DropDown-Design"),
"blocks_as_button_design"=>urlencode("Blöcke als Button Design"),
"qty_control_design"=>urlencode("Menge Kontrolldesign"),
"dropdowns"=>urlencode("DropDowns"),
"big_images_radio"=>urlencode("Große Bilder Radio"),
"errors"=>urlencode("Fehler"),
"extra_labels"=>urlencode("Zusätzliche Etiketten"),
"api_password"=>urlencode("API-Passwort"),
"api_username"=>urlencode("API-Benutzername"),
"appearance"=>urlencode("AUSSEHEN"),
"action"=>urlencode("Aktion"),
"actions"=>urlencode("Aktionen"),
"add_break"=>urlencode("Pause hinzufügen"),
"add_breaks"=>urlencode("Fügen Sie Pausen hinzu"),
"add_cleaning_service"=>urlencode("Reinigungsservice hinzufügen"),
"add_method"=>urlencode(" Methode hinzufügen"),
"add_new"=>urlencode("Neue hinzufügen"),
"add_sample_data"=>urlencode("Hinzufügen von Beispieldaten"),
"add_unit"=>urlencode(" Einheit hinzufügen"),
"add_your_off_times"=>urlencode("Fügen Sie Ihre Auszeiten hinzu"),
"add_new_off_time"=>urlencode("Neue Auszeit hinzufügen"),
"add_ons"=>urlencode("Add-ons"),
"addons_bookings"=>urlencode("AddOns Buchungen"),
"addon_service_front_view"=>urlencode("Addon-Service Vorderansicht"),
"addons"=>urlencode("Addons"),
"service_commission"=>urlencode(" Service-Kommission"),
"commission_total"=>urlencode("Kommission insgesamt"),
"address"=>urlencode("Adresse"),
"new_appointment_assigned"=>urlencode("Neuer Termin zugewiesen"),
"admin_email_notifications"=>urlencode(" Admin E-Mail-Benachrichtigungen"),
"all_payment_gateways"=>urlencode("Alle Zahlungsgateways"),
"all_services"=>urlencode("Alle Dienstleistungen"),
"allow_multiple_booking_for_same_timeslot"=>urlencode("Mehrfachbuchung für denselben Timeslot zulassen"),
"amount"=>urlencode("Menge"),
"app_date"=>urlencode("App. Datum"),
"appearance_settings"=>urlencode("Aussehen Einstellungen"),
"appointment_completed"=>urlencode("Termin abgeschlossen"),
"appointment_details"=>urlencode("Termindetails"),
"appointment_marked_as_no_show"=>urlencode("Termin als No Show markiert"),
"mark_as_no_show"=>urlencode("Als nicht anzeigen markieren"),
"appointment_reminder_buffer"=>urlencode("Termin Erinnerungspuffer"),
"appointment_auto_confirm"=>urlencode("Termin automatisch bestätigen"),
"appointments"=>urlencode("Termine"),
"admin_area_color_scheme"=>urlencode("Admin-Bereich Farbschema"),
"thankyou_page_url"=>urlencode("Danke Seiten-URL"),
"addon_title"=>urlencode(" Addon-Titel"),
"availabilty"=>urlencode("Verfügbarkeit"),
"background_color"=>urlencode("Hintergrundfarbe"),
"behaviour_on_click_of_button"=>urlencode("Verhalten bei Klick auf den Button"),
"book_now"=>urlencode(" buchen Sie jetzt"),
"booking_date_and_time"=>urlencode("Buchungsdatum und -zeit"),
"booking_details"=>urlencode("Buchungsdetails"),
"booking_information"=>urlencode("Buchungsinformation"),
"booking_serve_date"=>urlencode("Buchungsdatum"),
"booking_status"=>urlencode("Buchungsstatus"),
"booking_notifications"=>urlencode("Buchungsbenachrichtigungen"),
"bookings"=>urlencode("Buchungen"),
"button_position"=>urlencode("Tastenposition"),
"button_text"=>urlencode("Schaltflächentext"),
"company"=>urlencode("UNTERNEHMEN"),
"cannot_cancel_now"=>urlencode("Kann jetzt nicht abbrechen"),
"cannot_reschedule_now"=>urlencode(" Kann jetzt nicht neu geplant werden"),
"cancel"=>urlencode("Stornieren"),
"cancellation_buffer_time"=>urlencode(" Stornierungspufferzeit"),
"cancelled_by_client"=>urlencode("Vom Kunden storniert"),
"cancelled_by_service_provider"=>urlencode("Vom Dienstleister storniert"),
"change_password"=>urlencode(" Passwort ändern"),
"cleaning_service"=>urlencode("Reinigungsservice"),
"client"=>urlencode(" Klient"),
"client_email_notifications"=>urlencode(" Client-E-Mail-Benachrichtigungen"),
"client_name"=>urlencode(" Kundenname"),
"color_scheme"=>urlencode("Farbschema"),
"color_tag"=>urlencode("Farbmarkierung"),
"company_address"=>urlencode("Adresse"),
"company_email"=>urlencode("Email"),
"company_logo"=>urlencode("Firmenlogo"),
"company_name"=>urlencode("Geschäftsname"),
"company_settings"=>urlencode("Business Info Einstellungen"),
"companyname"=>urlencode("Name der Firma"),
"company_info_settings"=>urlencode("Unternehmensinfos Einstellungen"),
"completed"=>urlencode("Abgeschlossen"),
"confirm"=>urlencode("Bestätigen"),
"confirmed"=>urlencode("Bestätigt"),
"contact_status"=>urlencode(" Kontaktstatus"),
"country"=>urlencode("Land"),
"country_code_phone"=>urlencode("Ländercode (Telefon)"),
"coupon"=>urlencode("Coupon"),
"coupon_code"=>urlencode("Gutscheincode"),
"coupon_limit"=>urlencode("Gutscheinlimit"),
"coupon_type"=>urlencode(" Gutschein-Typ"),
"coupon_used"=>urlencode("Gutschein verwendet"),
"coupon_value"=>urlencode("Gutscheinwert"),
"create_addon_service"=>urlencode("Erstellen Sie einen Addon-Service"),
"crop_and_save"=>urlencode("Zuschneiden und speichern"),
"currency"=>urlencode("Währung"),
"currency_symbol_position"=>urlencode("Währungssymbolposition"),
"customer"=>urlencode(" Kunde"),
"customer_information"=>urlencode("Kundeninformation"),
"customers"=>urlencode("Kunden"),
"date_and_time"=>urlencode("Terminzeit"),
"date_picker_date_format"=>urlencode("Date-Picker Datumsformat"),
"default_design_for_addons"=>urlencode("Standarddesign für Addons"),
"default_design_for_methods_with_multiple_units"=>urlencode("Standardentwurf für Methoden mit mehreren Einheiten"),
"default_design_for_services"=>urlencode("Standardentwurf für Dienste"),
"default_setting"=>urlencode("Standardeinstellung"),
"delete"=>urlencode("Löschen"),
"description"=>urlencode("Beschreibung"),
"discount"=>urlencode("Rabatt"),
"download_invoice"=>urlencode("Download Rechnung"),
"email_notification"=>urlencode("EMAIL BENACHRICHTIGUNG"),
"email"=>urlencode("Email"),
"email_settings"=>urlencode(" Email Einstellungen"),
"embed_code"=>urlencode("Code einbetten"),
"enter_your_email_and_we_will_send_you_instructions_on_resetting_your_password"=>urlencode(" Geben Sie Ihre E-Mail-Adresse ein und wir senden Ihnen Anweisungen zum Zurücksetzen Ihres Passworts."),
"expiry_date"=>urlencode("Verfallsdatum"),
"export"=>urlencode("Export"),
"export_your_details"=>urlencode(" Exportieren Sie Ihre Daten"),
"frequently_discount_setting_tabs"=>urlencode("HÄUFIG GÜLTIG"),
"frequently_discount_header"=>urlencode("Häufig Rabatt"),
"field_is_required"=>urlencode("Feld ist erforderlich"),
"file_size"=>urlencode("Dateigröße"),
"flat_fee"=>urlencode("Pauschalgebühr"),
"flat"=>urlencode("Eben"),
"freq_discount"=>urlencode(" Freq-Rabatt"),
"frequently_discount_label"=>urlencode("Häufig Rabatt-Label"),
"frequently_discount_type"=>urlencode("Häufig Rabattart"),
"frequently_discount_value"=>urlencode("Häufig Rabattwert"),
"front_service_box_view"=>urlencode("Vorderansicht der Service Box"),
"front_service_dropdown_view"=>urlencode(" Front Service Dropdown-Ansicht"),
"front_view_options"=>urlencode("Frontansicht Optionen"),
"full_name"=>urlencode("Vollständiger Name"),
"general"=>urlencode("ALLGEMEINES"),
"general_settings"=>urlencode("Allgemeine Einstellungen"),
"get_embed_code_to_show_booking_widget_on_your_website"=>urlencode("Holen Sie sich den Einbettungscode, um das Buchungs-Widget auf Ihrer Website anzuzeigen"),
"get_the_embeded_code"=>urlencode("Erhalte den eingebetteten Code"),
"guest_customers"=>urlencode("Gast Kunden"),
"guest_user_checkout"=>urlencode("Gast Benutzer Checkout"),
"hide_faded_already_booked_time_slots"=>urlencode("Verblassen Sie bereits ausgebuchte Zeitfenster"),
"hostname"=>urlencode("Hostname"),
"labels"=>urlencode("ETIKETTEN"),
"legends"=>urlencode("Legenden"),
"login"=>urlencode("Anmeldung"),
"maximum_advance_booking_time"=>urlencode("Maximale Vorverkaufszeit"),
"method"=>urlencode("Methode"),
"method_name"=>urlencode("Methodenname"),
"method_title"=>urlencode("Methodentitel"),
"method_unit_quantity"=>urlencode(" Methode Einheit Menge"),
"method_unit_quantity_rate"=>urlencode(" Methode Einheit Menge Rate"),
"method_unit_title"=>urlencode(" Titel der Methodeneinheit"),
"method_units_front_view"=>urlencode(" Methode Einheiten Vorderansicht"),
"methods"=>urlencode(" Methoden"),
"methods_booking"=>urlencode("Methoden Buchung"),
"methods_bookings"=>urlencode("Methodenbuchungen"),
"minimum_advance_booking_time"=>urlencode("Mindestvorverkaufszeit"),
"more"=>urlencode(" Mehr"),
"more_details"=>urlencode("Mehr Details"),
"my_appointments"=>urlencode("Meine Termine"),
"name"=>urlencode("Name"),
"net_total"=>urlencode(" Netto Summe"),
"new_password"=>urlencode(" Neues Kennwort"),
"notes"=>urlencode("Anmerkungen"),
"off_days"=>urlencode(" Freie Tage"),
"off_time"=>urlencode(" Freizeit"),
"old_password"=>urlencode("Altes Passwort"),
"online_booking_button_style"=>urlencode("Online-Buchung Button Stil"),
"open_widget_in_a_new_page"=>urlencode("Widget in einer neuen Seite öffnen"),
"order"=>urlencode("Auftrag"),
"order_date"=>urlencode("Auftragsdatum"),
"order_time"=>urlencode("Bestellzeitpunkt"),
"payments_setting"=>urlencode("ZAHLUNG"),
"promocode"=>urlencode("GUTSCHEINCODE"),
"promocode_header"=>urlencode("Gutscheincode"),
"padding_time_before"=>urlencode("Polsterungszeit vorher"),
"parking"=>urlencode("Parken"),
"partial_amount"=>urlencode("Teilbetrag"),
"partial_deposit"=>urlencode(" Teilkaution"),
"partial_deposit_amount"=>urlencode("Teilbetrag"),
"partial_deposit_message"=>urlencode(" Teileinzahlungsnachricht"),
"password"=>urlencode("Passwort"),
"payment"=>urlencode("Zahlung"),
"payment_date"=>urlencode("Zahlungsdatum"),
"payment_gateways"=>urlencode("Zahlungsgateways"),
"payment_method"=>urlencode("Bezahlverfahren"),
"payments"=>urlencode("Zahlungen"),
"payments_history_details"=>urlencode("Details zur Zahlungshistorie"),
"paypal_express_checkout"=>urlencode(" PayPal Express Checkout"),
"paypal_guest_payment"=>urlencode("Paypal Gästezahlung"),
"pending"=>urlencode(" steht aus"),
"percentage"=>urlencode("Prozentsatz"),
"personal_information"=>urlencode("Persönliche Angaben"),
"phone"=>urlencode("Telefon"),
"please_copy_above_code_and_paste_in_your_website"=>urlencode(" Kopieren Sie den obigen Code und fügen Sie ihn in Ihre Website ein."),
"please_enable_payment_gateway"=>urlencode("Bitte aktivieren Sie das Zahlungsgateway"),
"please_set_below_values"=>urlencode("Bitte legen Sie die folgenden Werte fest"),
"port"=>urlencode("Hafen"),
"postal_codes"=>urlencode("Postleitzahlen"),
"price"=>urlencode("Preis"),
"price_calculation_method"=>urlencode("Preisberechnungsmethode"),
"price_format_decimal_places"=>urlencode("Preisformat"),
"pricing"=>urlencode("Preisgestaltung"),
"primary_color"=>urlencode("Primärfarbe"),
"privacy_policy_link"=>urlencode(" Datenschutz-Link"),
"profile"=>urlencode("Profil"),
"promocodes"=>urlencode("Promocodes"),
"promocodes_list"=>urlencode("Promocodes Liste"),
"registered_customers"=>urlencode("Registrierte Kunden"),
"registered_customers_bookings"=>urlencode("Registrierte Kunden Buchungen"),
"reject"=>urlencode("Ablehnen"),
"rejected"=>urlencode("Abgelehnt"),
"remember_me"=>urlencode("Erinnere dich an mich"),
"remove_sample_data"=>urlencode(" Entfernen Sie Beispieldaten"),
"reschedule"=>urlencode("Neu planen"),
"reset"=>urlencode("Zurücksetzen"),
"reset_password"=>urlencode(" Passwort zurücksetzen"),
"reshedule_buffer_time"=>urlencode("Reshedule Buffer Time"),
"retype_new_password"=>urlencode("Geben Sie das neue Passwort ein"),
"right_side_description"=>urlencode("Buchungsseite rechtsseitige Beschreibung"),
"save"=>urlencode("sparen"),
"save_availability"=>urlencode(" Verfügbarkeit speichern"),
"save_setting"=>urlencode("Einstellung sichern"),
"save_labels_setting"=>urlencode("Etiketteneinstellungen speichern"),
"schedule"=>urlencode("Zeitplan"),
"schedule_type"=>urlencode("Zeitplan-Typ"),
"secondary_color"=>urlencode("Sekundäre Farbe"),
"select_language_for_update"=>urlencode("Wählen Sie Sprache für die Aktualisierung"),
"select_language_to_change_label"=>urlencode("Wählen Sie die Sprache, um das Etikett zu ändern"),
"select_language_to_display"=>urlencode("Sprache"),
"display_sub_headers_below_headers"=>urlencode("Unterüberschriften auf Buchungsseite"),
"select_payment_option_export_details"=>urlencode("Wählen Sie Exportdetails für Zahlungsoptionen aus"),
"send_mail"=>urlencode(" E-Mail senden"),
"sender_email_address_cleanto_admin_email"=>urlencode("Absender E-Mail"),
"sender_name"=>urlencode("Absender"),
"service"=>urlencode("Bedienung"),
"service_add_ons_front_block_view"=>urlencode("Service-Add-ons Frontblockansicht"),
"service_add_ons_front_increase_decrease_view"=>urlencode("Service Add-ons Front Ansicht vergrößern / verkleinern"),
"service_description"=>urlencode("Leistungsbeschreibung"),
"service_front_view"=>urlencode(" Service Vorderansicht"),
"service_image"=>urlencode("Service Bild"),
"service_methods"=>urlencode("Servicemethoden"),
"service_padding_time_after"=>urlencode("Service-Auffüllzeit nach"),
"padding_time_after"=>urlencode("Polsterzeit nach"),
"service_padding_time_before"=>urlencode("Service-Polsterungs-Zeit vorher"),
"service_quantity"=>urlencode("Service Menge"),
"service_rate"=>urlencode("Service-Rate"),
"service_title"=>urlencode("Service-Titel"),
"serviceaddons_name"=>urlencode(" ServiceAddOns Name"),
"services"=>urlencode("Dienstleistungen"),
"services_information"=>urlencode("Informationen zu Dienstleistungen"),
"set_email_reminder_buffer"=>urlencode("Legen Sie den E-Mail-Erinnerungspuffer fest"),
"set_language"=>urlencode("Sprache einstellen"),
"settings"=>urlencode("die Einstellungen"),
"show_all_bookings"=>urlencode("Alle Buchungen anzeigen"),
"show_button_on_given_embeded_position"=>urlencode("Zeigen Sie die Schaltfläche auf der angegebenen eingebetteten Position"),
"show_coupons_input_on_checkout"=>urlencode("Gutscheineingaben beim Checkout anzeigen"),
"show_on_a_button_click"=>urlencode("Zeigen Sie auf einen Knopf klicken"),
"show_on_page_load"=>urlencode(" Beim Laden der Seite anzeigen"),
"signature"=>urlencode("Unterschrift"),
"sorry_wrong_email_or_password"=>urlencode("Sorry, falsche E-Mail oder Passwort"),
"start_date"=>urlencode("Anfangsdatum"),
"status"=>urlencode("Status"),
"submit"=>urlencode("einreichen"),
"staff_email_notification"=>urlencode("Mitarbeiter E-Mail-Benachrichtigung"),
"tax"=>urlencode("MwSt"),
"test_mode"=>urlencode("Testmodus"),
"text_color"=>urlencode("Textfarbe"),
"text_color_on_bg"=>urlencode("Textfarbe auf bg"),
"terms_and_condition_link"=>urlencode("Nutzungsbedingungen Link"),
"this_week_breaks"=>urlencode("Diese Woche bricht"),
"this_week_time_scheduling"=>urlencode("Diese Wochenzeitplanung"),
"time_format"=>urlencode("Zeitformat"),
"time_interval"=>urlencode("Zeitintervall"),
"timezone"=>urlencode("Zeitzone"),
"units"=>urlencode("Einheiten"),
"unit_name"=>urlencode("Einheitenname"),
"units_of_methods"=>urlencode("Einheiten von Methoden"),
"update"=>urlencode("Aktualisieren"),
"update_appointment"=>urlencode("Termin aktualisieren"),
"update_promocode"=>urlencode("Promocode aktualisieren"),
"username"=>urlencode("Nutzername"),
"vaccum_cleaner"=>urlencode("Staubsauger"),
"view_slots_by"=>urlencode("Slots anzeigen nach?"),
"week"=>urlencode("Woche"),
"week_breaks"=>urlencode("Wochenpausen"),
"week_time_scheduling"=>urlencode("Wochenzeitplanung"),
"widget_loading_style"=>urlencode("Widget Ladeart"),
"zip"=>urlencode("Postleitzahl"),
"logout"=>urlencode("Ausloggen"),
"to"=>urlencode("zu"),
"add_new_promocode"=>urlencode("Neuen Promocode hinzufügen"),
"create"=>urlencode("Erstellen"),
"end_date"=>urlencode("Endtermin"),
"end_time"=>urlencode("Endzeit"),
"labels_settings"=>urlencode("Etiketteneinstellungen"),
"limit"=>urlencode("Grenze"),
"max_limit"=>urlencode("Höchstgrenze"),
"start_time"=>urlencode("Startzeit"),
"value"=>urlencode("Wert"),
"active"=>urlencode("Aktiv"),
"appointment_reject_reason"=>urlencode("Termin Ablehnung Grund"),
"search"=>urlencode("Suche"),
"custom_thankyou_page_url"=>urlencode("Benutzerdefinierte Danke Seite Url"),
"price_per_unit"=>urlencode("Preis pro Einheit"),
"confirm_appointment"=>urlencode("Bestätigen Sie den Termin"),
"reject_reason"=>urlencode("Ablehnungsgrund"),
"delete_this_appointment"=>urlencode("Löschen Sie diesen Termin"),
"close_notifications"=>urlencode("Benachrichtigungen schließen"),
"booking_cancel_reason"=>urlencode("Buchung Grund stornieren"),
"service_color_badge"=>urlencode("Buchung Grund stornieren"),
"manage_price_calculation_methods"=>urlencode("Manage price calculation methods"),
"manage_addons_of_this_service"=>urlencode("Verwalten Sie Add-Ons dieses Dienstes"),
"service_is_booked"=>urlencode("Der Service ist gebucht"),
"delete_this_service"=>urlencode("Löschen Sie diesen Dienst"),
"delete_service"=>urlencode("Dienst löschen"),
"remove_image"=>urlencode("Entferne Bild"),
"remove_service_image"=>urlencode("Entfernen Sie das Servicebild"),
"company_name_is_used_for_invoice_purpose"=>urlencode("Der Firmenname wird zu Rechnungszwecken verwendet"),
"remove_company_logo"=>urlencode("Entfernen Sie das Firmenlogo"),
"time_interval_is_helpful_to_show_time_difference_between_availability_time_slots"=>urlencode("Das Zeitintervall ist hilfreich, um den Zeitunterschied zwischen den Verfügbarkeitszeitfenstern anzuzeigen"),
"minimum_advance_booking_time_restrict_client_to_book_last_minute_booking_so_that_you_should_have_sufficient_time_before_appointment"=>urlencode("Mindestvorlaufzeit beschränkt den Kunden auf die Buchung von Last-Minute-Buchungen, so dass Sie ausreichend Zeit vor dem Termin haben sollten"),
"cancellation_buffer_helps_service_providers_to_avoid_last_minute_cancellation_by_their_clients"=>urlencode("Der Stornierungspuffer hilft Dienstanbietern, Last-Minute-Stornierungen durch ihre Kunden zu vermeiden"),
"partial_payment_option_will_help_you_to_charge_partial_payment_of_total_amount_from_client_and_remaining_you_can_collect_locally"=>urlencode("Die Teilzahlungsoption hilft Ihnen, die Teilzahlung des Gesamtbetrags vom Kunden zu berechnen, die Sie vor Ort sammeln können"),
"allow_multiple_appointment_booking_at_same_time_slot_will_allow_you_to_show_availability_time_slot_even_you_have_booking_already_for_that_time"=>urlencode("Erlauben Sie mehrere Terminbuchungen auf demselben Zeitfenster, so können Sie das Verfügbarkeitszeitfenster anzeigen, selbst wenn Sie bereits für diese Zeit gebucht haben"),
"with_Enable_of_this_feature_Appointment_request_from_clients_will_be_auto_confirmed"=>urlencode("Mit Aktivieren dieser Funktion wird die Terminanfrage von Kunden automatisch bestätigt"),
"write_html_code_for_the_right_side_panel"=>urlencode("Schreiben Sie HTML-Code für das rechte Seitenfeld"),
"do_you_want_to_show_subheaders_below_the_headers"=>urlencode("Möchten Sie Unterüberschriften unterhalb der Überschriften anzeigen?"),
"you_can_show_hide_coupon_input_on_checkout_form"=>urlencode("Möchten Sie Unterüberschriften unterhalb der Überschriften anzeigen?"),
"with_this_feature_you_can_allow_a_visitor_to_book_appointment_without_registration"=>urlencode("Mit dieser Funktion können Sie einem Besucher erlauben, einen Termin ohne Registrierung zu buchen"),
"paypal_api_username_can_get_easily_from_developer_paypal_com_account"=>urlencode("Paypal API Benutzername kann leicht von developer.paypal.com Konto erhalten"),
"paypal_api_password_can_get_easily_from_developer_paypal_com_account"=>urlencode("Paypal API Passwort kann leicht von developer.paypal.com account bekommen"),
"paypal_api_signature_can_get_easily_from_developer_paypal_com_account"=>urlencode("Die Paypal-API-Signatur kann einfach vom developer.paypal.com-Konto abgerufen werden"),
"let_user_pay_through_credit_card_without_having_paypal_account"=>urlencode("Lassen Sie den Benutzer mit Kreditkarte zahlen, ohne Paypal-Konto zu haben"),
"you_can_enable_paypal_test_mode_for_sandbox_account_testing"=>urlencode("Sie können den Paypal-Testmodus für das Testen von Sandbox-Konten aktivieren"),
"you_can_enable_authorize_net_test_mode_for_sandbox_account_testing"=>urlencode("Sie können den Paypal-Testmodus für das Testen von Sandbox-Konten aktivieren"),
"edit_coupon_code"=>urlencode("Gutscheincode bearbeiten"),
"delete_promocode"=>urlencode("Promocode löschen?"),
"coupon_code_will_work_for_such_limit"=>urlencode("Der Gutscheincode wird für ein solches Limit funktionieren"),
"coupon_code_will_work_for_such_date"=>urlencode("Der Gutscheincode funktioniert für ein solches Datum"),
"coupon_value_would_be_consider_as_percentage_in_percentage_mode_and_in_flat_mode_it_will_be_consider_as_amount_no_need_to_add_percentage_sign_it_will_auto_added"=>urlencode("Der Couponwert wird als prozentualer Wert im Prozentsatz betrachtet und im flachen Modus wird er als Betrag betrachtet. Es muss kein Prozentzeichen hinzugefügt werden, es wird automatisch hinzugefügt."),
"unit_is_booked"=>urlencode("Die Einheit wird gebucht"),
"delete_this_service_unit"=>urlencode("Löschen Sie diese Serviceeinheit?"),
"delete_service_unit"=>urlencode("Serviceeinheit löschen"),
"manage_unit_price"=>urlencode("Manage Unit Price"),
"extra_service_title"=>urlencode("Einheitspreis verwalten"),
"addon_is_booked"=>urlencode("Addon wird gebucht"),
"delete_this_addon_service"=>urlencode("Diesen Addon-Service löschen?"),
"choose_your_addon_image"=>urlencode("Wähle dein Addon-Bild"),
"addon_image"=>urlencode("Zusatzbild"),
"administrator_email"=>urlencode("Administrator E-Mail"),
"admin_profile_address"=>urlencode("Adresse"),
"default_country_code"=>urlencode("Landesvorwahl"),
"cancellation_policy"=>urlencode("Stornierungsbedingungen"),
"transaction_id"=>urlencode("Transaktions-ID"),
"sms_reminder"=>urlencode("SMS Erinnerung"),
"save_sms_settings"=>urlencode("SMS-Einstellungen speichern"),
"sms_service"=>urlencode("SMS Service"),
"it_will_send_sms_to_service_provider_and_client_for_appointment_booking"=>urlencode("Es sendet SMS an Service Provider und Kunden zur Terminbuchung"),
"twilio_account_settings"=>urlencode("Twilio Kontoeinstellungen"),
"plivo_account_settings"=>urlencode("Plivo Kontoeinstellungen"),
"account_sid"=>urlencode("Konto SID"),
"auth_token"=>urlencode("Auth Token"),
"twilio_sender_number"=>urlencode("Twilio Sender Number"),
"plivo_sender_number"=>urlencode("Plivo Absender Nummer"),
"twilio_sms_settings"=>urlencode("Twilio SMS Einstellungen"),
"plivo_sms_settings"=>urlencode("Plivo SMS Einstellungen"),
"twilio_sms_gateway"=>urlencode("Twilio SMS Gateway"),
"plivo_sms_gateway"=>urlencode("Plivo SMS Gateway"),
"send_sms_to_client"=>urlencode("Senden Sie SMS an den Client"),
"send_sms_to_admin"=>urlencode("Senden Sie SMS an den Administrator"),
"admin_phone_number"=>urlencode("Admin-Telefonnummer"),
"available_from_within_your_twilio_account"=>urlencode("Verfügbar in Ihrem Twilio-Konto."),
"must_be_a_valid_number_associated_with_your_twilio_account"=>urlencode("Muss eine gültige Nummer sein, die mit Ihrem Twilio-Konto verknüpft ist."),
"enable_or_disable_send_sms_to_client_for_appointment_booking_info"=>urlencode("Aktivieren oder Deaktivieren, SMS an den Client senden für Terminbuchungsinformationen."),
"enable_or_disable_send_sms_to_admin_for_appointment_booking_info"=>urlencode("Aktivieren oder Deaktivieren, SMS an Admin senden für Terminbuchungsinformationen."),
"updated_sms_settings"=>urlencode("SMS-Einstellungen aktualisiert"),
"parking_availability_frontend_option_display_status"=>urlencode("Parken"),
"vaccum_cleaner_frontend_option_display_status"=>urlencode("Vaccume Cleaner"),
"o_n"=>urlencode("Auf"),
"off"=>urlencode("aus"),
"enable"=>urlencode("Aktivieren"),
"disable"=>urlencode("Deaktivieren"),
"monthly"=>urlencode("Monatlich"),
"weekly"=>urlencode("Wöchentlich"),
"email_template"=>urlencode("E-MAIL-VORLAGE"),
"sms_notification"=>urlencode("SMS Benachrichtigung"),
"sms_template"=>urlencode("SMS SCHABLONE"),
"email_template_settings"=>urlencode("Email Template Settings"),
"client_email_templates"=>urlencode("E-Mail-VorlageneinstellungenE-Mail-Vorlageneinstellungen"),
"client_sms_templates"=>urlencode("Client-SMS-Vorlage"),
"admin_email_template"=>urlencode("E-Mail-Vorlage für Administratoren"),
"admin_sms_template"=>urlencode("Admin SMS Vorlage"),
"tags"=>urlencode("Stichworte"),
"booking_date"=>urlencode("Buchungsdatum"),
"service_name"=>urlencode("Dienstname"),
"business_logo"=>urlencode("business_logo"),
"business_logo_alt"=>urlencode("business_alt"),
"admin_name"=>urlencode("Verwaltungsname"),
"methodname"=>urlencode("method_name"),
"firstname"=>urlencode("Vorname"),
"lastname"=>urlencode("Familienname, Nachname"),
"client_email"=>urlencode("client_email"),
"vaccum_cleaner_status"=>urlencode("vakuum_cleaner_status"),
"parking_status"=>urlencode("Parkplatz_Status"),
"app_remain_time"=>urlencode("app_remain_time"),
"reject_status"=>urlencode("ablehnen_status"),
"save_template"=>urlencode("Vorlage speichern"),
"default_template"=>urlencode("Standardvorlage"),
"sms_template_settings"=>urlencode("SMS-Vorlageneinstellungen"),
"secret_key"=>urlencode("Geheimer Schlüssel"),
"publishable_key"=>urlencode("Veröffentlichbarer Schlüssel"),
"payment_form"=>urlencode("Zahlungsformular"),
"api_login_id"=>urlencode("API-Anmelde-ID"),
"transaction_key"=>urlencode("Transaktionsschlüssel"),
"sandbox_mode"=>urlencode("Sandbox-Modus"),
"available_from_within_your_plivo_account"=>urlencode("Erhältlich in Ihrem Plivo-Konto."),
"must_be_a_valid_number_associated_with_your_plivo_account"=>urlencode("Muss eine gültige Nummer sein, die mit Ihrem Plivo-Konto verknüpft ist."),
"whats_new"=>urlencode("Was gibt's Neues?"),
"company_phone"=>urlencode("Telefon"),
"company__name"=>urlencode("Name der Firma"),
"booking_time"=>urlencode("Buchungszeit"),
"company__email"=>urlencode("Firmen-E-Mail"),
"company__address"=>urlencode("Firmenanschrift"),
"company__zip"=>urlencode("Firmenzip"),
"company__phone"=>urlencode("Firmentelefon"),
"company__state"=>urlencode("Firmenstatus"),
"company__country"=>urlencode("Firmenland"),
"company__city"=>urlencode("Firmen_City"),
"page_title"=>urlencode("Seitentitel"),
"client__zip"=>urlencode("Client_Zip"),
"client__state"=>urlencode("Kundenstatus"),
"client__city"=>urlencode("client_city"),
"client__address"=>urlencode("Clientadresse"),
"client__phone"=>urlencode("client_phone"),
"company_logo_is_used_for_invoice_purpose"=>urlencode("Firmenlogo wird in E-Mail und Buchungsseite verwendet"),
"private_key"=>urlencode("Privat Schlüssel"),
"seller_id"=>urlencode("Verkäufer-ID"),
"postal_codes_ed"=>urlencode("Sie können die Funktion für Postleitzahlen oder Postleitzahlen gemäß Ihren Länderanforderungen aktivieren oder deaktivieren, da einige Länder wie die VAE keine Postleitzahl haben."),
"postal_codes_info"=>urlencode("Sie können die Postleitzahlen auf zwei Arten angeben: # 1. Sie können vollständige PLZ für Übereinstimmung wie K1A232, L2A334, C3A4C4 erwähnen. Sie können teilweise Postleitzahlen für Wildcard-Match-Einträge verwenden, z. K1A, L2A, C3, System wird diese Anfangsbuchstaben der Postleitzahl auf der Vorderseite entsprechen und es wird vermeiden, dass Sie so viele Postleitzahlen schreiben."),
"first"=>urlencode("Zuerst"),
"second"=>urlencode("Zweite"),
"third"=>urlencode("Dritte"),
"fourth"=>urlencode("Vierte"),
"fifth"=>urlencode("Fünfte"),
"first_week"=>urlencode("Erste Woche"),
"second_week"=>urlencode("Zweite Woche"),
"third_week"=>urlencode("Dritte Woche"),
"fourth_week"=>urlencode("Vierte Woche"),
"fifth_week"=>urlencode("Fünfte Woche"),
"this_week"=>urlencode("Diese Woche"),
"monday"=>urlencode("Montag"),
"tuesday"=>urlencode("Dienstag"),
"wednesday"=>urlencode("Mittwoch"),
"thursday"=>urlencode("Donnerstag"),
"friday"=>urlencode("Freitag"),
"saturday"=>urlencode("Saturday"),
"sunday"=>urlencode("Sonntag"),
"appointment_request"=>urlencode("Terminanfrage"),
"appointment_approved"=>urlencode("Termin genehmigt"),
"appointment_rejected"=>urlencode("Termin abgelehnt"),
"appointment_cancelled_by_you"=>urlencode("Termin von Ihnen storniert"),
"appointment_rescheduled_by_you"=>urlencode("Termin von Ihnen neu geplant"),
"client_appointment_reminder"=>urlencode("Kunden Termin Erinnerung"),
"new_appointment_request_requires_approval"=>urlencode("Neue Terminanfrage erfordert Genehmigung"),
"appointment_cancelled_by_customer"=>urlencode("Termin vom Kunden storniert"),
"appointment_rescheduled_by_customer"=>urlencode("Termin wird vom Kunden verschoben"),
"admin_appointment_reminder"=>urlencode("Admin Termin Erinnerung"),
"off_days_added_successfully"=>urlencode("Off Tage erfolgreich hinzugefügt"),
"off_days_deleted_successfully"=>urlencode("Aus-Tage wurden erfolgreich gelöscht"),
"sorry_not_available"=>urlencode("Entschuldigung Nicht verfügbar"),
"success"=>urlencode("Erfolg"),
"failed"=>urlencode("Gescheitert"),
"once"=>urlencode("Einmal"),
"Bi_Monthly"=>urlencode("Zweimonatlich"),
"Fortnightly"=>urlencode("Vierzehn Tage"),
"Recurrence_Type"=>urlencode("Wiederholungstyp"),
"bi_weekly"=>urlencode("Zweiwöchentlich"),
"Daily"=>urlencode("Täglich"),
"guest_customers_bookings"=>urlencode("Gästekunden Buchungen"),
"existing_and_new_user_checkout"=>urlencode("Vorhandener & neuer Nutzer-Checkout"),
"it_will_allow_option_for_user_to_get_booking_with_new_user_or_existing_user"=>urlencode("Vorhandener & neuer Nutzer-Checkout"),
"0_1"=>urlencode("01"),
"1_1"=>urlencode("1.1"),
"1_2"=>urlencode("1.2"),
"0_2"=>urlencode("02"),
"free"=>urlencode("Frei"),
"show_company_address_in_header"=>urlencode("Firmenadresse in der Kopfzeile anzeigen"),
"calendar_week"=>urlencode("Woche"),
"calendar_month"=>urlencode("Monat"),
"calendar_day"=>urlencode("Tag"),
"calendar_today"=>urlencode("Heute"),
"restore_default"=>urlencode("Standard wiederherstellen"),
"scrollable_cart"=>urlencode("Standard wiederherstellen"),
"merchant_key"=>urlencode("Händlerschlüssel"),
"salt_key"=>urlencode("Salz Schlüssel"),
"textlocal_sms_gateway"=>urlencode("Textlocal SMS-Gateway"),
"textlocal_sms_settings"=>urlencode("Textlocal SMS-Einstellungen"),
"textlocal_account_settings"=>urlencode("Textlocal Kontoeinstellungen"),
"account_username"=>urlencode("Benutzername"),
"account_hash_id"=>urlencode("Konto-Hash-ID"),
"email_id_registered_with_you_textlocal"=>urlencode("Geben Sie Ihre mit textlocal registrierte E-Mail-Adresse an"),
"hash_id_provided_by_textlocal"=>urlencode("Hash-ID von textlocal bereitgestellt"),
"bank_transfer"=>urlencode("Banküberweisung"),
"bank_name"=>urlencode("Bank Name"),
"account_name"=>urlencode("Kontobezeichnung"),
"account_number"=>urlencode("Accountnummer"),
"branch_code"=>urlencode("Branchencode"),
"ifsc_code"=>urlencode("IFSC-Code"),
"bank_description"=>urlencode("Bankbeschreibung"),
"your_cart_items"=>urlencode("Ihre Einkaufswagen Artikel"),
"show_how_will_we_get_in"=>urlencode("Show Wie kommen wir rein?"),
"show_description"=>urlencode("Beschreibung anzeigen"),
"bank_details"=>urlencode("Bankdaten"),
"ok_remove_sample_data"=>urlencode("Ok"),
"book_appointment"=>urlencode("Einen Termin verabreden"),
"remove_sample_data_message"=>urlencode("Sie versuchen, Beispieldaten zu entfernen. Wenn Sie Beispieldaten entfernen, wird Ihre Buchung, die sich auf Beispieldienste bezieht, endgültig gelöscht. Um fortzufahren, klicken Sie bitte auf 'OK'"),
"recommended_image_type_jpg_jpeg_png_gif"=>urlencode("(Empfohlener Bildtyp jpg, jpeg, png, gif)"),
"authetication"=>urlencode("Authentifizierung"),
"encryption_type"=>urlencode("Verschlüsselungstyp"),
"plain"=>urlencode("Einfach"),
"true"=>urlencode("Wahr"),
"false"=>urlencode("Falsch"),
"change_calculation_policy"=>urlencode("Berechnung ändern"),
"multiply"=>urlencode("Multiplizieren"),
"equal"=>urlencode("Gleich"),
"warning"=>urlencode("Warnung!"),
"field_name"=>urlencode("Feldname"),
"enable_disable"=>urlencode("Aktivieren deaktivieren"),
"required"=>urlencode("Erforderlich"),
"min_length"=>urlencode("Minimale Länge"),
"max_length"=>urlencode("Maximale Länge"),
"appointment_details_section"=>urlencode("Termin Details Abschnitt"),
"if_you_are_having_booking_system_which_need_the_booking_address_then_please_make_this_field_enable_or_else_it_will_not_able_to_take_the_booking_address_and_display_blank_address_in_the_booking"=>urlencode("Wenn Sie ein Buchungssystem haben, das die Buchungsadresse benötigt, dann machen Sie bitte dieses Feld frei, sonst kann es die Buchungsadresse nicht übernehmen und keine leere Adresse in der Buchung anzeigen"),
"front_language_dropdown"=>urlencode("Front Language Dropdown"),
"enabled"=>urlencode("aktiviert"),
"vaccume_cleaner"=>urlencode("Staubsauger"),
"staff_members"=>urlencode("Mitarbeiter"),
"add_new_staff_member"=>urlencode("Neuen Mitarbeiter hinzufügen"),
"role"=>urlencode("Rolle"),
"staff"=>urlencode("Mitarbeiter"),
"admin"=>urlencode("Administrator"),
"service_details"=>urlencode("Servicedetails"),
"technical_admin"=>urlencode("Technischer Administrator"),
"enable_booking"=>urlencode("Buchung aktivieren"),
"flat_commission"=>urlencode("Buchung aktivieren"),
"manageable_form_fields_front_booking_form"=>urlencode("Manageable Form Felder für Front Booking Form"),
"manageable_form_fields"=>urlencode("Verwaltbare Formularfelder"),
"sms"=>urlencode("SMS"),
"crm"=>urlencode("CRM"),
"message"=>urlencode("Botschaft"),
"send_message"=>urlencode("Nachricht senden"),
"all_messages"=>urlencode("Alle Nachrichten"),
"subject"=>urlencode("Gegenstand"),
"add_attachment"=>urlencode("Anhang hinzufügen"),
"send"=>urlencode("Senden"),
"close"=>urlencode("Schließen"),
"delete_this_customer?"=>urlencode("Delete This Customer?"),
"yes"=>urlencode("Ja"),
"add_new_customer"=>urlencode("Neuen Kunden hinzufügen"),
"attachment"=>urlencode("Befestigung"),
"date"=>urlencode("Datum"),
"see_attachment"=>urlencode("Siehe Anhang"),
"no_attachment"=>urlencode("Keine Befestigung"),
"ct_special_offer"=>urlencode("Sonderangebot"),
"ct_special_offer_text"=>urlencode("Sonderangebot Text"),
);

$error_labels_de_DE = array (
"language_status_change_successfully"=>urlencode("Taal Status is succesvol gewijzigd"),
"commission_amount_should_not_be_greater_then_order_amount"=>urlencode("Bedragen van de Commissie mogen niet groter zijn dan het bestellingsbedrag"),
"please_enter_merchant_ID"=>urlencode(" Bitte geben Sie die Händler-ID ein"),
"please_enter_secure_key"=>urlencode("Bitte geben Sie den Sicherheitsschlüssel ein"),
"please_enter_google_calender_admin_url"=>urlencode("Bitte geben Sie Google Kalender Admin URL ein"),
"please_enter_google_calender_frontend_url"=>urlencode("Bitte geben Sie Google Kalender Frontend URL ein"),
"please_enter_google_calender_client_secret"=>urlencode("Bitte geben Sie den Google Kalender Client Client ein"),
"please_enter_google_calender_client_ID"=>urlencode("Bitte geben Sie die Google Kalender-Client-ID ein"),
"please_enter_google_calender_ID"=>urlencode("Bitte geben Sie die Google Kalender-ID ein"),
"you_cannot_book_on_past_date"=>urlencode("Sie können nicht am vergangenen Datum buchen"),
"Invalid_Image_Type"=>urlencode("Ungültiger Bildtyp"),
"seo_settings_updated_successfully"=>urlencode("SEO-Einstellungen wurden erfolgreich aktualisiert"),
"customer_deleted_successfully"=>urlencode("Der Kunde wurde erfolgreich gelöscht"),
"please_enter_below_36_characters"=>urlencode("Bitte geben Sie unter 36 Zeichen ein"),
"are_you_sure_you_want_to_delete_client"=>urlencode("Sind Sie sicher, dass Sie löschen möchten Klient?"),
"please_select_atleast_one_unit"=>urlencode("Bitte wählen Sie mindestens eine Einheit aus"),
"atleast_one_payment_method_should_be_enable"=>urlencode("Mindestens eine Zahlungsmethode sollte aktiviert sein"),
"appointment_booking_confirm"=>urlencode("Terminbuchung bestätigen"),
"appointment_booking_rejected"=>urlencode("Terminbuchung abgelehnt"),
"booking_cancel"=>urlencode("Buchung abgebrochen"),
"appointment_marked_as_no_show"=>urlencode("Termin als Nichterscheinen markiert"),
"appointment_reschedules_successfully"=>urlencode("Termin wird erfolgreich neu terminiert"),
"booking_deleted"=>urlencode("Buchung gelöscht"),
"break_end_time_should_be_greater_than_start_time"=>urlencode("Break End Time sollte größer als Startzeit sein"),
"cancel_by_client"=>urlencode("Abbrechen vom Kunden"),
"cancelled_by_service_provider"=>urlencode("Vom Dienstleister storniert"),
"design_set_successfully"=>urlencode("Design erfolgreich eingestellt"),
"end_break_time_updated"=>urlencode("Ende der Pausenzeit aktualisiert"),
"enter_alphabets_only"=>urlencode("Gib nur Alphabete ein"),
"enter_only_alphabets"=>urlencode("Geben Sie nur Alphabete ein"),
"enter_only_alphabets_numbers"=>urlencode("Gib nur Alphabete / Zahlen ein"),
"enter_only_digits"=>urlencode("Geben Sie nur Ziffern ein"),
"enter_valid_url"=>urlencode("Geben Sie eine gültige URL ein"),
"enter_only_numeric"=>urlencode("Geben Sie nur Zahlen ein"),
"enter_proper_country_code"=>urlencode("Geben Sie den richtigen Ländercode ein"),
"frequently_discount_status_updated"=>urlencode("Häufig Rabattstatus aktualisiert"),
"frequently_discount_updated"=>urlencode("Häufig Rabatt aktualisiert"),
"manage_addons_service"=>urlencode("Verwalten Sie Addons-Service"),
"maximum_file_upload_size_2_mb"=>urlencode("Maximale Größe für das Hochladen von Dateien 2 MB"),
"method_deleted_successfully"=>urlencode("Methode wurde erfolgreich gelöscht"),
"method_inserted_successfully"=>urlencode("Methode wurde erfolgreich eingefügt"),
"minimum_file_upload_size_1_kb"=>urlencode("Dateigröße mindestens 1 KB"),
"off_time_added_successfully"=>urlencode("Auszeit wurde erfolgreich hinzugefügt"),
"only_jpeg_png_and_gif_images_allowed"=>urlencode("Nur JPEG-, PNG- und GIF-Bilder erlaubt"),
"only_jpeg_png_gif_zip_and_pdf_allowed"=>urlencode("Nur jpeg, png, gif, zip und pdf erlaubt"),
"please_wait_while_we_send_all_your_message"=>urlencode("Bitte warten Sie, während wir alle Ihre Nachrichten senden"),
"please_enable_email_to_client"=>urlencode("Bitte aktivieren Sie E-Mails für den Client."),
"please_enable_sms_gateway"=>urlencode("Bitte aktivieren Sie E-Mails für den Client."),
"please_enable_client_notification"=>urlencode("Bitte aktivieren Sie die Kundenbenachrichtigung."),
"password_must_be_8_character_long"=>urlencode("Das Passwort muss 8 Zeichen lang sein"),
"password_should_not_exist_more_then_20_characters"=>urlencode("Das Passwort sollte nicht länger als 20 Zeichen sein"),
"please_assign_base_price_for_unit"=>urlencode("Bitte geben Sie den Basispreis für die Einheit an"),
"please_assign_price"=>urlencode("Bitte legen Sie den Preis fest"),
"please_assign_qty"=>urlencode("Bitte legen Sie den Preis fest"),
"please_enter_api_password"=>urlencode("Bitte geben Sie das API-Passwort ein"),
"please_enter_api_username"=>urlencode("Bitte geben Sie den API-Benutzernamen ein"),
"please_enter_color_code"=>urlencode("Bitte geben Sie den Farbcode ein"),
"please_enter_country"=>urlencode("Bitte geben Sie das Land ein"),
"please_enter_coupon_limit"=>urlencode("Bitte geben Sie das Gutscheinlimit ein"),
"please_enter_coupon_value"=>urlencode("Bitte geben Sie den Gutscheinwert ein"),
"please_enter_coupon_code"=>urlencode("Bitte geben Sie den Gutscheincode ein"),
"please_enter_email"=>urlencode("Bitte geben Sie eine Email ein"),
"please_enter_fullname"=>urlencode("Bitte geben Sie Fullname ein"),
"please_enter_maxlimit"=>urlencode("Bitte geben Sie maxLimit ein"),
"please_enter_method_title"=>urlencode("Please enter method title"),
"please_enter_name"=>urlencode("Bitte geben Sie den Namen ein"),
"please_enter_only_numeric"=>urlencode("Bitte geben Sie nur Zahlen ein"),
"please_enter_proper_base_price"=>urlencode("Bitte geben Sie den korrekten Grundpreis ein"),
"please_enter_proper_name"=>urlencode("Bitte geben Sie den richtigen Namen ein"),
"please_enter_proper_title"=>urlencode("Bitte geben Sie den richtigen Titel ein"),
"please_enter_publishable_key"=>urlencode(" Bitte geben Sie den Publishable Key ein. Geben Sie den Publishable Key ein"),
"please_enter_secret_key"=>urlencode("Bitte geben Sie den geheimen Schlüssel ein"),
"please_enter_service_title"=>urlencode("Bitte geben Sie den Service-Titel ein"),
"please_enter_signature"=>urlencode("Bitte Unterschrift eingeben"),
"please_enter_some_qty"=>urlencode("Bitte geben Sie eine Menge ein"),
"please_enter_title"=>urlencode("Bitte geben Sie den Titel ein"),
"please_enter_unit_title"=>urlencode("Bitte geben Sie den Titel der Einheit ein"),
"please_enter_valid_country_code"=>urlencode("Bitte geben Sie den gültigen Ländercode ein"),
"please_enter_valid_service_title"=>urlencode("Bitte geben Sie einen gültigen Service-Titel ein"),
"please_enter_valid_price"=>urlencode("Bitte geben Sie den gültigen Preis ein"),
"please_enter_zipcode"=>urlencode("Bitte Postleitzahl eingeben"),
"please_enter_state"=>urlencode("Bitte geben Sie den Status ein"),
"please_retype_correct_password"=>urlencode("Bitte geben Sie das richtige Passwort ein"),
"please_select_porper_time_slots"=>urlencode("Bitte wählen Sie porper Zeitfenster"),
"please_select_time_between_day_availability_time"=>urlencode("Bitte wählen Sie die Zeit zwischen der Verfügbarkeit am Tag"),
"please_valid_value_for_discount"=>urlencode("Bitte gültigen Wert für Rabatt"),
"please_enter_confirm_password"=>urlencode("Bitte bestätigen Sie das Passwort"),
"please_enter_new_password"=>urlencode("Bitte neues Passwort eingeben"),
"please_enter_old_password"=>urlencode("Bitte altes Passwort eingeben"),
"please_enter_valid_number"=>urlencode("Bitte geben Sie eine gültige Nummer ein"),
"please_enter_valid_number_with_country_code"=>urlencode("Bitte geben Sie eine gültige Nummer mit Ländercode ein"),
"please_select_end_time_greater_than_start_time"=>urlencode("Bitte wählen Sie die Endzeit größer als die Startzeit"),
"please_select_end_time_less_than_start_time"=>urlencode("Bitte wählen Sie die Endzeit weniger als die Startzeit"),
"please_select_a_crop_region_and_then_press_upload"=>urlencode("Bitte wählen Sie eine Anbauregion und drücken Sie dann auf Hochladen"),
"please_select_a_valid_image_file_jpg_and_png_are_allowed"=>urlencode("Bitte wählen Sie eine gültige Bilddatei jpg und png sind erlaubt"),
"profile_updated_successfully"=>urlencode("Profil erfolgreich aktualisiert"),
"qty_rule_deleted"=>urlencode("Mengenregel gelöscht"),
"record_deleted_successfully"=>urlencode("Datensatz wurde erfolgreich gelöscht"),
"record_updated_successfully"=>urlencode("Datensatz erfolgreich aktualisiert"),
"rescheduled"=>urlencode("Umdisponiert"),
"schedule_updated_to_monthly"=>urlencode("Zeitplan auf Monatlich aktualisiert"),
"schedule_updated_to_weekly"=>urlencode("Zeitplan auf Wöchentlich aktualisiert"),
"sorry_method_already_exist"=>urlencode("Sorry-Methode existiert bereits"),
"sorry_no_notification"=>urlencode("Entschuldigung, Sie haben keinen bevorstehenden Termin"),
"sorry_promocode_already_exist"=>urlencode("Sorry Promocode existiert bereits"),
"sorry_unit_already_exist"=>urlencode("Sorry-Einheit existiert bereits"),
"sorry_we_are_not_available"=>urlencode("Entschuldigung, wir sind nicht verfügbar"),
"start_break_time_updated"=>urlencode("Startpause aktualisiert"),
"status_updated"=>urlencode("Status aktualisiert"),
"time_slots_updated_successfully"=>urlencode("Zeitfenster wurden erfolgreich aktualisiert"),
"unit_inserted_successfully"=>urlencode("Gerät wurde erfolgreich eingefügt"),
"units_status_updated"=>urlencode("Einheitenstatus aktualisiert"),
"updated_appearance_settings"=>urlencode("Einheitenstatus aktualisiert"),
"updated_company_details"=>urlencode("Aktualisierte Firmendetails"),
"updated_email_settings"=>urlencode("Aktualisierte E-Mail-Einstellungen"),
"updated_general_settings"=>urlencode("Allgemeine Einstellungen aktualisiert"),
"updated_payments_settings"=>urlencode("Zahlungseinstellungen aktualisiert"),
"your_old_password_incorrect"=>urlencode("Altes Passwort falsch"),
"please_enter_minimum_5_chars"=>urlencode("Bitte geben Sie mindestens 5 Zeichen ein"),
"please_enter_maximum_10_chars"=>urlencode("Bitte geben Sie maximal 10 Zeichen ein"),
"please_enter_postal_code"=>urlencode("Bitte geben Sie die Postleitzahl ein"),
"please_select_a_service"=>urlencode("Bitte wählen Sie einen Service"),
"please_select_units_and_addons"=>urlencode("Bitte wählen Sie Einheiten und Addons"),
"please_select_units_or_addons"=>urlencode("Bitte wählen Sie Einheiten oder Addons"),
"please_login_to_complete_booking"=>urlencode("Bitte loggen Sie sich ein, um die Buchung abzuschließen"),
"please_select_appointment_date"=>urlencode("Bitte wählen Sie das Termindatum aus"),
"please_accept_terms_and_conditions"=>urlencode("Bitte akzeptieren Sie die Allgemeinen Geschäftsbedingungen"),
"incorrect_email_address_or_password"=>urlencode("Falsche E-Mail-Adresse oder Passwort"),
"please_enter_valid_email_address"=>urlencode("Bitte geben Sie eine gültige Email Adresse an"),
"please_enter_email_address"=>urlencode("Bitte geben Sie die E-Mail Adresse ein"),
"please_enter_password"=>urlencode("Bitte geben Sie die E-Mail Adresse ein"),
"please_enter_minimum_8_characters"=>urlencode("Bitte geben Sie mindestens 8 Zeichen ein"),
"please_enter_maximum_15_characters"=>urlencode("Bitte geben Sie maximal 15 Zeichen ein"),
"please_enter_first_name"=>urlencode("Bitte geben Sie den Vornamen ein"),
"please_enter_only_alphabets"=>urlencode("Bitte geben Sie nur Alphabete ein"),
"please_enter_minimum_2_characters"=>urlencode("Bitte geben Sie mindestens 2 Zeichen ein"),
"please_enter_last_name"=>urlencode("Bitte geben Sie den Nachnamen ein"),
"email_already_exists"=>urlencode("E-Mail existiert bereits"),
"please_enter_phone_number"=>urlencode("Bitte geben Sie die Telefonnummer ein"),
"please_enter_only_numerics"=>urlencode("Bitte geben Sie nur Ziffern ein"),
"please_enter_minimum_10_digits"=>urlencode("Bitte geben Sie mindestens 10 Ziffern ein"),
"please_enter_maximum_14_digits"=>urlencode("Please enter maximum 14 digits"),
"please_enter_address"=>urlencode("Bitte geben Sie die Adresse ein"),
"please_enter_minimum_20_characters"=>urlencode("Bitte geben Sie mindestens 20 Zeichen ein"),
"please_enter_zip_code"=>urlencode("Bitte geben Sie die Postleitzahl ein"),
"please_enter_proper_zip_code"=>urlencode("Bitte geben Sie die richtige Postleitzahl ein"),
"please_enter_minimum_5_digits"=>urlencode("Bitte geben Sie mindestens 5 Ziffern ein"),
"please_enter_maximum_7_digits"=>urlencode("Bitte geben Sie maximal 7 Ziffern ein"),
"please_enter_city"=>urlencode("Bitte geben Sie die Stadt ein"),
"please_enter_proper_city"=>urlencode("Bitte geben Sie die richtige Stadt ein"),
"please_enter_maximum_48_characters"=>urlencode("Bitte geben Sie die richtige Stadt ein"),
"please_enter_proper_state"=>urlencode("Bitte geben Sie den richtigen Status ein"),
"please_enter_contact_status"=>urlencode("Bitte geben Sie den Kontaktstatus ein"),
"please_enter_maximum_100_characters"=>urlencode("Bitte geben Sie maximal 100 Zeichen ein"),
"your_cart_is_empty_please_add_cleaning_services"=>urlencode("Ihr Warenkorb ist leer. Bitte Reinigungsservice hinzufügen"),
"coupon_expired"=>urlencode("Gutschein abgelaufen"),
"invalid_coupon"=>urlencode("Ungültiger Gutschein"),
"our_service_not_available_at_your_location"=>urlencode("Unser Service ist an Ihrem Standort nicht verfügbar"),
"please_enter_proper_postal_code"=>urlencode("Bitte geben Sie die richtige Postleitzahl ein"),
"invalid_email_id_please_register_first"=>urlencode("Ungültige E-Mail-ID bitte zuerst registrieren"),
"your_password_send_successfully_at_your_registered_email_id"=>urlencode("Ihr Passwort wird erfolgreich an Ihre registrierte Email-ID gesendet"),
"your_password_reset_successfully_please_login"=>urlencode("Ihr Passwort erfolgreich zurückgesetzt, bitte loggen Sie sich ein"),
"new_password_and_retype_new_password_mismatch"=>urlencode("Neues Passwort und erneutes Eingeben des neuen Passworts"),
"new"=>urlencode("Neu"),
"your_reset_password_link_expired"=>urlencode("Ihr Link zum Zurücksetzen des Passworts ist abgelaufen"),
"front_display_language_changed"=>urlencode("Front-Display-Sprache geändert"),
"updated_front_display_language_and_update_labels"=>urlencode("Aktualisierte Front-Display-Sprache und Update-Etiketten"),
"please_enter_only_7_chars_maximum"=>urlencode("Bitte geben Sie maximal 7 Zeichen ein"),
"please_enter_maximum_20_chars"=>urlencode("Bitte geben Sie maximal 20 Zeichen ein"),
"record_inserted_successfully"=>urlencode("Datensatz erfolgreich eingefügt"),
"please_enter_account_sid"=>urlencode("Bitte geben Sie Accout SID ein"),
"please_enter_auth_token"=>urlencode("Bitte geben Sie Auth Token ein"),
"please_enter_sender_number"=>urlencode("Bitte geben Sie die Sendernummer ein"),
"please_enter_admin_number"=>urlencode("Bitte geben Sie die Admin-Nummer ein"),
"sorry_service_already_exist"=>urlencode("Sorry-Service ist bereits vorhanden"),
"please_enter_api_login_id"=>urlencode("Bitte geben Sie die API-Login-ID ein"),
"please_enter_transaction_key"=>urlencode("Bitte Transaktionsschlüssel eingeben"),
"please_enter_sms_message"=>urlencode("Bitte geben Sie eine SMS-Nachricht ein"),
"please_enter_email_message"=>urlencode("Bitte geben Sie eine E-Mail-Nachricht ein"),
"please_enter_private_key"=>urlencode("Please Enter Private Key"),
"please_enter_seller_id"=>urlencode("Bitte geben Sie die Verkäufer-ID ein"),
"please_enter_valid_value_for_discount"=>urlencode("Bitte geben Sie einen gültigen Wert für den Rabatt ein"),
"password_must_be_only_10_characters"=>urlencode("Das Passwort darf nur aus 10 Zeichen bestehen"),
"password_at_least_have_8_characters"=>urlencode("Passwort Mindestens 8 Zeichen"),
"please_enter_retype_new_password"=>urlencode("Bitte geben Sie das neue Passwort ein"),
"your_password_send_successfully_at_your_email_id"=>urlencode("Ihr Passwort wird erfolgreich an Ihre E-Mail-ID gesendet"),
"please_select_expiry_date"=>urlencode("Bitte wählen Sie das Ablaufdatum"),
"please_enter_merchant_key"=>urlencode("Bitte geben Sie den Händlerschlüssel ein"),
"please_enter_salt_key"=>urlencode("Bitte geben Sie Salt Key ein"),
"please_enter_account_username"=>urlencode("Bitte geben Sie den Benutzernamen des Kontos ein"),
"please_enter_account_hash_id"=>urlencode("Bitte geben Sie die Kontohash-ID ein"),
"invalid_values"=>urlencode("Ungültige Werte"),
"please_select_atleast_one_checkout_method"=>urlencode("Bitte wählen Sie mindestens eine Checkout-Methode aus"),
);

$extra_labels_de_DE = array (
"please_enter_minimum_3_chars"=>urlencode("Bitte geben Sie mindestens 3 Zeichen ein"),
"invoice"=>urlencode("RECHNUNG"),
"invoice_to"=>urlencode("RECHNUNG AN"),
"invoice_date"=>urlencode("Rechnungsdatum"),
"cash"=>urlencode("KASSE"),
"service_name"=>urlencode("Dienstname"),
"qty"=>urlencode("Menge"),
"booked_on"=>urlencode("Gebucht am"),
);

$front_error_labels_de_DE = array (
"min_ff_ps"=>urlencode("Bitte geben Sie mindestens 8 Zeichen ein"),
"max_ff_ps"=>urlencode("Bitte geben Sie maximal 10 Zeichen ein"),
"req_ff_fn"=>urlencode("Bitte geben Sie den Vornamen ein"),
"min_ff_fn"=>urlencode("Bitte geben Sie den Vornamen ein"),
"max_ff_fn"=>urlencode("Bitte geben Sie maximal 15 Zeichen ein"),
"req_ff_ln"=>urlencode("Bitte geben Sie den Nachnamen ein"),
"min_ff_ln"=>urlencode("Bitte geben Sie mindestens 3 Zeichen ein"),
"max_ff_ln"=>urlencode("Bitte geben Sie maximal 15 Zeichen ein"),
"req_ff_ph"=>urlencode("Bitte geben Sie die Telefonnummer ein"),
"min_ff_ph"=>urlencode("Bitte geben Sie mindestens 9 Zeichen ein"),
"max_ff_ph"=>urlencode("Bitte geben Sie maximal 15 Zeichen ein"),
"req_ff_sa"=>urlencode("Bitte geben Sie die Adresse ein"),
"min_ff_sa"=>urlencode("Bitte geben Sie mindestens 10 Zeichen ein"),
"max_ff_sa"=>urlencode("Bitte geben Sie maximal 40 Zeichen ein"),
"req_ff_zp"=>urlencode("Bitte Postleitzahl eingebenPlease en"),
"min_ff_zp"=>urlencode("Bitte geben Sie mindestens 3 Zeichen ein"),
"max_ff_zp"=>urlencode("Bitte geben Sie maximal 7 Zeichen ein"),
"req_ff_ct"=>urlencode("Bitte geben Sie die Stadt ein"),
"min_ff_ct"=>urlencode("Bitte geben Sie mindestens 3 Zeichen ein"),
"max_ff_ct"=>urlencode("Bitte geben Sie maximal 15 Zeichen ein"),
"req_ff_st"=>urlencode("Bitte geben Sie den Status ein"),
"min_ff_st"=>urlencode("Bitte geben Sie mindestens 3 Zeichen ein"),
"max_ff_st"=>urlencode("Bitte geben Sie maximal 15 Zeichen ein"),
"req_ff_srn"=>urlencode("Bitte geben Sie Notizen ein"),
"min_ff_srn"=>urlencode("Bitte geben Sie mindestens 10 Zeichen ein"),
"max_ff_srn"=>urlencode("Bitte geben Sie maximal 70 Zeichen ein"),
"Transaction_failed_please_try_again"=>urlencode("Transaktion fehlgeschlagen. Bitte versuchen Sie es erneut"),
"Please_Enter_valid_card_detail"=>urlencode("Bitte geben Sie ein gültiges Kartendetail ein"),
);

$language_front_arr_de_DE = base64_encode(serialize($label_data_de_DE));
$language_admin_arr_de_DE = base64_encode(serialize($admin_labels_de_DE));
$language_error_arr_de_DE = base64_encode(serialize($error_labels_de_DE));
$language_extra_arr_de_DE = base64_encode(serialize($extra_labels_de_DE));
$language_form_error_arr_de_DE = base64_encode(serialize($front_error_labels_de_DE));

$insert_default_lang_de_DE = "insert into `ct_languages` (`id`,`label_data`,`language`, `admin_labels`, `error_labels`, `extra_labels`, `front_error_labels`,`language_status`) values(NULL,'" . $language_front_arr_de_DE . "','de_DE','" . $language_admin_arr_de_DE . "','" . $language_error_arr_de_DE . "','" . $language_extra_arr_de_DE . "','" . $language_form_error_arr_de_DE . "','Y')";
mysqli_query($this->conn, $insert_default_lang_de_DE);

/** Spanish Language **/
$label_data_es_ES = array (
"none_available"=>urlencode("Ninguno disponible"),
"appointment_zip"=>urlencode("Cita de la cita"),
"appointment_city"=>urlencode("Ciudad de cita"),
"appointment_state"=>urlencode("Estado de nombramiento"),
"appointment_address"=>urlencode("Dirección de la cita"),
"guest_user"=>urlencode("Usuario invitado"),
"service_usage_methods"=>urlencode("Métodos de uso del servicio"),
"paypal"=>urlencode("Paypal"),
"please_check_for_the_below_missing_information"=>urlencode("Verifique la información que falta a continuación."),
"please_provide_company_details_from_the_admin_panel"=>urlencode("Proporcione los detalles de la empresa desde el panel de administración."),
"please_add_some_services_methods_units_addons_from_the_admin_panel"=>urlencode("Agregue algunos servicios, métodos, unidades, complementos del panel de administración."),
"please_add_time_scheduling_from_the_admin_panel"=>urlencode("Agregue programación horaria desde el panel de administración."),
"please_complete_configurations_before_you_created_website_embed_code"=>urlencode("Complete las configuraciones antes de crear el código de inserción del sitio web."),
"cvc"=>urlencode("CVC"),
"mm_yyyy"=>urlencode("(MM / AAAA)"),
"expiry_date_or_csv"=>urlencode("Fecha de caducidad o CSV"),
"street_address_placeholder"=>urlencode("p.ej. Central Ave"),
"zip_code_placeholder"=>urlencode("gt; 90001"),
"city_placeholder"=>urlencode("p.ej. los Angeles"),
"state_placeholder"=>urlencode("por ejemplo. California"),
"payumoney"=>urlencode("PayUmoney"),
"same_as_above"=>urlencode("Lo mismo que arriba"),
"sun"=>urlencode("Sol"),
"mon"=>urlencode("Lun"),
"tue"=>urlencode("Mar"),
"wed"=>urlencode("Mie"),
"thu"=>urlencode("Jue"),
"fri"=>urlencode("Vie"),
"sat"=>urlencode("Sab"),
"su"=>urlencode("Su"),
"mo"=>urlencode("Mo"),
"tu"=>urlencode("Tu"),
"we"=>urlencode("We"),
"th"=>urlencode("Th"),
"fr"=>urlencode("Fr"),
"sa"=>urlencode("Sa"),
"my_bookings"=>urlencode("Mis reservas"),
"your_postal_code"=>urlencode("CP o Código Postal"),
"where_would_you_like_us_to_provide_service"=>urlencode("¿Dónde te gustaría que brindemos el servicio?"),
"choose_service"=>urlencode("Elija el servicio"),
"how_often_would_you_like_us_provide_service"=>urlencode("¿Con qué frecuencia le gustaría que brindemos el servicio?"),
"when_would_you_like_us_to_come"=>urlencode("¿Cuándo quieres que vengamos?"),
"today"=>urlencode("HOY"),
"your_personal_details"=>urlencode("tus detalles personales"),
"existing_user"=>urlencode("Usuario existente"),
"new_user"=>urlencode("Nuevo usuario"),
"preferred_email"=>urlencode("Correo electrónico preferido"),
"preferred_password"=>urlencode("Contraseña preferida"),
"your_valid_email_address"=>urlencode("Tu dirección de correo electrónico válida"),
"first_name"=>urlencode("Nombre de pila"),
"your_first_name"=>urlencode("Su nombre"),
"last_name"=>urlencode("Apellido"),
"your_last_name"=>urlencode("Tu apellido"),
"street_address"=>urlencode("Dirección"),
"cleaning_service"=>urlencode("Servicio de limpieza"),
"please_select_method"=>urlencode("Por favor seleccione el método"),
"zip_code"=>urlencode("Código postal"),
"city"=>urlencode("Ciudad"),
"state"=>urlencode("Estado"),
"special_requests_notes"=>urlencode("Peticiones especiales (Notas)"),
"do_you_have_a_vaccum_cleaner"=>urlencode("¿Tienes una aspiradora?"),
"assign_appointment_to_staff"=>urlencode("Asignar cita al personal"),
"delete_member"=>urlencode("¿Eliminar miembro?"),
"yes"=>urlencode("Sí"),
"no"=>urlencode("No"),
"preferred_payment_method"=>urlencode("Método de pago preferido"),
"please_select_one_payment_method"=>urlencode("Seleccione un método de pago"),
"partial_deposit"=>urlencode("Depósito parcial"),
"remaining_amount"=>urlencode("Cantidad restante"),
"please_read_our_terms_and_conditions_carefully"=>urlencode("Por favor, lea nuestros términos y condiciones cuidadosamente"),
"do_you_have_parking"=>urlencode("¿Tienes estacionamiento?"),
"how_will_we_get_in"=>urlencode("¿Cómo entraremos?"),
"i_will_be_at_home"=>urlencode("Estaré en casa"),
"please_call_me"=>urlencode("Por favor, llámame"),
"recurring_discounts_apply_from_the_second_cleaning_onward"=>urlencode("Los descuentos recurrentes se aplican desde la segunda limpieza en adelante."),
"please_provide_your_address_and_contact_details"=>urlencode("Por favor ingrese su dirección y detalles de contacto"),
"you_are_logged_in_as"=>urlencode("Has iniciado sesión como"),
"the_key_is_with_the_doorman"=>urlencode("La clave está en el portero"),
"other"=>urlencode("Otro"),
"have_a_promocode"=>urlencode("¿Tienes un código de promoción?"),
"apply"=>urlencode("Aplicar"),
"applied_promocode"=>urlencode("Applied Promocode"),
"complete_booking"=>urlencode("Reserva completa"),
"cancellation_policy"=>urlencode("Política de cancelación"),
"cancellation_policy_header"=>urlencode("Encabezado de la política de cancelación"),
"cancellation_policy_textarea"=>urlencode("Política de cancelación Textarea"),
"free_cancellation_before_redemption"=>urlencode("Cancelación gratuita antes de la redención"),
"show_more"=>urlencode("Mostrar más"),
"please_select_service"=>urlencode("Por favor seleccione el servicio"),
"choose_your_service_and_property_size"=>urlencode("Elija su servicio y tamaño de propiedad"),
"choose_your_service"=>urlencode("Elija su servicio"),
"please_configure_first_cleaning_services_and_settings_in_admin_panel"=>urlencode("Configure primero los Servicios de limpieza y la configuración en el panel de administración"),
"i_have_read_and_accepted_the"=>urlencode("He leído y acepto el"),
"terms_and_condition"=>urlencode("Términos y condiciones"),
"and"=>urlencode(" y"),
"updated_labels"=>urlencode("Etiquetas actualizadas"),
"privacy_policy"=>urlencode("Política de privacidad"),
"please_fill_all_the_company_informations_and_add_some_services_and_addons"=>urlencode("Las configuraciones requeridas no se completan."),
"booking_summary"=>urlencode("Resumen de reserva"),
"your_email"=>urlencode("Tu correo electrónico"),
"enter_email_to_login"=>urlencode("Ingrese el correo electrónico para iniciar sesión"),
"your_password"=>urlencode("Tu contraseña"),
"enter_your_password"=>urlencode("Ingresa tu contraseña"),
"forget_password"=>urlencode("¿Contraseña olvidada?"),
"reset_password"=>urlencode("Restablecer la contraseña"),
"enter_your_email_and_we_send_you_instructions_on_resetting_your_password"=>urlencode("Ingrese su correo electrónico y le enviaremos instrucciones para restablecer su contraseña."),
"registered_email"=>urlencode("Ingrese su correo electrónico y le enviaremos instrucciones para restablecer su contraseña."),
"send_mail"=>urlencode("Enviar correo"),
"back_to_login"=>urlencode("Atrás para iniciar sesión"),
"your"=>urlencode("Tu"),
"your_clean_items"=>urlencode("Tus artículos limpios"),
"your_cart_is_empty"=>urlencode("Tu carrito esta vacío"),
"sub_totaltax"=>urlencode("Sub TotalTax"),
"sub_total"=>urlencode("Sub Total"),
"no_data_available_in_table"=>urlencode("No hay datos disponibles en la tabla"),
"total"=>urlencode("Total"),
"or"=>urlencode("O"),
"select_addon_image"=>urlencode("Seleccionar imagen de complemento"),
"inside_fridge"=>urlencode("Nevera interior"),
"inside_oven"=>urlencode("Horno interno"),
"inside_windows"=>urlencode("Dentro de Windows"),
"carpet_cleaning"=>urlencode("Limpieza de alfombra"),
"green_cleaning"=>urlencode("Limpieza verde"),
"pets_care"=>urlencode("Cuidado de mascotas"),
"tiles_cleaning"=>urlencode("Azulejos de limpieza"),
"wall_cleaning"=>urlencode("Limpieza de paredes"),
"laundry"=>urlencode("Lavandería"),
"basement_cleaning"=>urlencode("Limpieza del sótano"),
"basic_price"=>urlencode("Precio básico"),
"max_qty"=>urlencode("Cantidad máxima"),
"multiple_qty"=>urlencode("Cantidad Múltiple"),
"base_price"=>urlencode("Precio base"),
"unit_pricing"=>urlencode("Precio unitario"),
"method_is_booked"=>urlencode("El método está reservado"),
"service_addons_price_rules"=>urlencode("Reglas de precios de los complementos de servicio"),
"service_unit_front_dropdown_view"=>urlencode("Unidad de servicio Vista frontal DropDown"),
"service_unit_front_block_view"=>urlencode("Vista de bloque frontal de la unidad de servicio"),
"service_unit_front_increase_decrease_view"=>urlencode("Unidad de servicio Vista de aumento / disminución frontal"),
"are_you_sure"=>urlencode("Estás seguro"),
"service_unit_price_rules"=>urlencode("Reglas de precio de unidad de servicio"),
"close"=>urlencode("Cerca"),
"closed"=>urlencode("Cerrado"),
"service_addons"=>urlencode("Complementos de servicio"),
"service_enable"=>urlencode("Servicio habilitado"),
"service_disable"=>urlencode("Deshabilitar servicio"),
"method_enable"=>urlencode("Método Habilitar"),
"off_time_deleted"=>urlencode("Tiempo de inactividad eliminado"),
"error_in_delete_of_off_time"=>urlencode("Error en la eliminación de la hora de apagado"),
"method_disable"=>urlencode("Método Deshabilitar"),
"extra_services"=>urlencode("Servicios extra"),
"for_initial_cleaning_only_contact_us_to_apply_to_recurrings"=>urlencode("Para limpieza inicial solamente. Póngase en contacto con nosotros para solicitar recurrencias."),
"number_of"=>urlencode("Número de"),
"extra_services_not_available"=>urlencode("Servicios adicionales no disponibles"),
"available"=>urlencode("Disponible"),
"selected"=>urlencode("Seleccionado"),
"not_available"=>urlencode("No disponible"),
"none"=>urlencode("Ninguna"),
"none_of_time_slot_available_please_check_another_dates"=>urlencode("Ninguno de los horarios disponibles. Por favor, verifique otras fechas"),
"availability_is_not_configured_from_admin_side"=>urlencode("La disponibilidad no está configurada desde el lado del administrador"),
"how_many_intensive"=>urlencode("Cuantos Intensivos"),
"no_intensive"=>urlencode("No Intensivo"),
"frequently_discount"=>urlencode("Descuento frecuente"),
"coupon_discount"=>urlencode("Descuento de cupones"),
"how_many"=>urlencode("Cuántos"),
"enter_your_other_option"=>urlencode("Ingrese su Otra opción"),
"log_out"=>urlencode("Cerrar sesión"),
"your_added_off_times"=>urlencode("Sus tiempos de inactividad agregados"),
"log_in"=>urlencode("iniciar sesión"),
"custom_css"=>urlencode("Css personalizado"),
"success"=>urlencode("Éxito"),
"failure"=>urlencode("Fracaso"),
"you_can_only_use_valid_zipcode"=>urlencode("Solo puedes usar un código postal válido"),
"minutes"=>urlencode("Minutos"),
"hours"=>urlencode("Horas"),
"days"=>urlencode("Dias"),
"months"=>urlencode("Meses"),
"year"=>urlencode("Año"),
"default_url_is"=>urlencode("La URL predeterminada es"),
"card_payment"=>urlencode("Pago con tarjeta"),
"pay_at_venue"=>urlencode("Pagar en el lugar"),
"card_details"=>urlencode("Detalles de tarjeta"),
"card_number"=>urlencode("Número de tarjeta"),
"invalid_card_number"=>urlencode("numero de tarjeta invalido"),
"expiry"=>urlencode("Expiración"),
"button_preview"=>urlencode("Vista previa del botón"),
"thankyou"=>urlencode("Gracias"),
"thankyou_for_booking_appointment"=>urlencode("¡Gracias! para reservar cita"),
"you_will_be_notified_by_email_with_details_of_appointment"=>urlencode("Se le notificará por correo electrónico con los detalles de la cita"),
"please_enter_firstname"=>urlencode("Por favor ingrese el primer nombre"),
"please_enter_lastname"=>urlencode("Por favor ingrese el apellido"),
"remove_applied_coupon"=>urlencode("Eliminar el cupón aplicado"),
"eg_799_e_dragram_suite_5a"=>urlencode("g. 799 Y DRAGRAM SUITE 5A"),
"eg_14114"=>urlencode("p.ej. 14114"),
"eg_tucson"=>urlencode("p.ej. TUCSON"),
"eg_az"=>urlencode("por ejemplo. LA"),
"warning"=>urlencode("Advertencia"),
"try_later"=>urlencode("Intenta más tarde"),
"choose_your"=>urlencode("Escoge tu"),
"configure_now_new"=>urlencode("Configurar ahora"),
"january"=>urlencode("ENERO"),
"february"=>urlencode("FEBRERO"),
"march"=>urlencode("MARZO"),
"april"=>urlencode("ABRIL"),
"may"=>urlencode("MAYO"),
"june"=>urlencode("JUNIO"),
"july"=>urlencode("JULIO"),
"august"=>urlencode("AGOSTO"),
"september"=>urlencode("SEPTIEMBRE"),
"october"=>urlencode("OCTUBRE"),
"november"=>urlencode("NOVIEMBRE"),
"december"=>urlencode("DICIEMBRE"),
"jan"=>urlencode("ENE"),
"feb"=>urlencode("FEB"),
"mar"=>urlencode("MAR"),
"apr"=>urlencode("ABR"),
"jun"=>urlencode("JUN"),
"jul"=>urlencode("JUL"),
"aug"=>urlencode("AGO"),
"sep"=>urlencode("SEP"),
"oct"=>urlencode("OCT"),
"nov"=>urlencode("NOV"),
"dec"=>urlencode("DIC"),
"pay_locally"=>urlencode("Pagar localmente"),
"please_select_provider"=>urlencode("Por favor seleccione proveedor"),
);

$admin_labels_es_ES = array (
"payment_status"=>urlencode("Estado de pago"),
"staff_booking_status"=>urlencode("Estado de la reserva del personal"),
"accept"=>urlencode("Aceptar"),
"accepted"=>urlencode("Aceptado"),
"decline"=>urlencode("Disminución"),
"paid"=>urlencode("Pagado"),
"eway"=>urlencode(" Eway"),
"half_section"=>urlencode(" Media sección"),
"option_title"=>urlencode("Título de la opción"),
"merchant_ID"=>urlencode("Identificación del comerciante"),
"How_it_works"=>urlencode(" ¿Cómo funciona?"),
"Your_currency_should_be_AUD_to_enable_payway_payment_gateway"=>urlencode("Su moneda debe ser Australia Dollar para habilitar la puerta de enlace de pago"),
"secure_key"=>urlencode("Clave segura"),
"payway"=>urlencode(" Payway"),
"Your_Google_calendar_id_where_you_need_to_get_alerts_its_normaly_your_Gmail_ID"=>urlencode("Su ID de calendario de Google, donde necesita recibir alertas, es normalmente su ID de Gmail. p.ej. johndoe@example.com"),
"You_can_get_your_client_ID_from_your_Google_Calendar_Console"=>urlencode("Puede obtener su ID de cliente desde su Google Calendar Console"),
"You_can_get_your_client_secret_from_your_Google_Calendar_Console"=>urlencode("Puede obtener el secreto de su cliente desde su Google Calendar Console"),
"its_your_Cleanto_booking_form_page_url"=>urlencode("es tu URL de página de formulario de reserva de Cleanto"),
"Its_your_Cleanto_Google_Settings_page_url"=>urlencode("Es su url de la página de configuración de Google Cleanto"),
"Add_Manual_booking"=>urlencode("Agregar reserva manual"),
"Google_Calender_Settings"=>urlencode("Configuraciones de Google Calender"),
"Add_Appointments_To_Google_Calender"=>urlencode("Agregar citas al calendario de Google"),
"Google_Calender_Id"=>urlencode("Identificación de Google Calender"),
"Google_Calender_Client_Id"=>urlencode(" ID del cliente de Google Calender"),
"Google_Calender_Client_Secret"=>urlencode("Google Calender Client Secret"),
"Google_Calender_Frontend_URL"=>urlencode("URL de la interfaz de Google Calender"),
"Google_Calender_Admin_URL"=>urlencode("URL de administración de Google Calender"),
"Google_Calender_Configuration"=>urlencode("Configuración de Google Calender"),
"Two_Way_Sync"=>urlencode("Sincronización bidireccional"),
"Verify_Account"=>urlencode("Verificar Cuenta"),
"Select_Calendar"=>urlencode("Seleccionar calendario"),
"Disconnect"=>urlencode("Desconectar"),
"Calendar_Fisrt_Day"=>urlencode("Calendario Primer día"),
"Calendar_Default_View"=>urlencode("Vista predeterminada del calendario"),
"Show_company_title"=>urlencode("Mostrar el título de la compañía"),
"front_language_flags_list"=>urlencode("Lista de banderas de lenguas frontales"),
"Google_Analytics_Code"=>urlencode("Código de Google Analytics"),
"Page_Meta_Tag"=>urlencode("Página / Meta Tag"),
"SEO_Settings"=>urlencode(" Configuración de SEO"),
"Meta_Description"=>urlencode(" Metadescripción"),
"SEO"=>urlencode("SEO"),
"og_tag_image"=>urlencode("og Imagen de etiqueta"),
"og_tag_url"=>urlencode("og Etiqueta URL"),
"og_tag_type"=>urlencode("og Tipo de etiqueta"),
"og_tag_title"=>urlencode("og Título de etiqueta"),
"Quantity"=>urlencode("Cantidad"),
"Send_Invoice"=>urlencode("Enviará la factura"),
"Recurrence"=>urlencode("Reaparición"),
"Recurrence_booking"=>urlencode("Reserva de recurrencia"),
"Reset_Color"=>urlencode("Restablecer color"),
"Loader"=>urlencode("Cargador"),
"CSS_Loader"=>urlencode("Cargador de CSS"),
"GIF_Loader"=>urlencode("Cargador GIF"),
"Default_Loader"=>urlencode("Cargador predeterminado"),
"for_a"=>urlencode("para"),
"show_company_logo"=>urlencode("Mostrar el logotipo de la empresa"),
"on"=>urlencode(" en"),
"user_zip_code"=>urlencode("código postal"),
"delete_this_method"=>urlencode("Eliminar este método?"),
"authorize_net"=>urlencode("Authorize.Net"),
"staff_details"=>urlencode("DETALLES DEL PERSONAL"),
"client_payments"=>urlencode(" Pagos del cliente"),
"staff_payments"=>urlencode("Pagos del personal"),
"staff_payments_details"=>urlencode("Detalles de pagos del personal"),
"advance_paid"=>urlencode(" Pago anticipado"),
"change_calculation_policyy"=>urlencode("Cambiar la política de cálculo"),
"frontend_fonts"=>urlencode("Fuentes frontend"),
"favicon_image"=>urlencode("Favicon Image"),
"staff_email_template"=>urlencode(" Plantilla de correo electrónico del personal"),
"staff_details_add_new_and_manage_staff_payments"=>urlencode("Detalles del personal, agregue nuevos y administre los pagos del personal"),
"add_staff"=>urlencode("Añadir personal"),
"staff_bookings_and_payments"=>urlencode(" Reservas de personal y pagos"),
"staff_booking_details_and_payment"=>urlencode("Detalles de reserva de personal y pago"),
"select_option_to_show_bookings"=>urlencode("Seleccione la opción para mostrar reservas"),
"select_service"=>urlencode("Seleccionar servicio"),
"staff_name"=>urlencode("Nombre del personal"),
"staff_payment"=>urlencode("Pago del personal"),
"add_payment_to_staff_account"=>urlencode("Agregar pago a la cuenta del personal"),
"amount_payable"=>urlencode("Cantidad pagable"),
"save_changes"=>urlencode("Guardar cambios"),
"front_error_labels"=>urlencode("Etiquetas de error frontales"),
"stripe"=>urlencode("Raya"),
"checkout_title"=>urlencode(" 2Checkout"),
"nexmo_sms_gateway"=>urlencode("Nexmo SMS Gateway"),
"nexmo_sms_setting"=>urlencode("Configuración de Nexmo SMS"),
"nexmo_api_key"=>urlencode("Clave de la API Nexmo"),
"nexmo_api_secret"=>urlencode(" Nexmo API Secret"),
"nexmo_from"=>urlencode("Nexmo desde"),
"nexmo_status"=>urlencode("Estado de Nexmo"),
"nexmo_send_sms_to_client_status"=>urlencode("Nexmo envía SMS al estado del cliente"),
"nexmo_send_sms_to_admin_status"=>urlencode("Nexmo Enviar SMS al estado de administrador"),
"nexmo_admin_phone_number"=>urlencode(" Número de teléfono de Nexmo Admin"),
"save_12_5"=>urlencode("ahorra 12.5%"),
"front_tool_tips"=>urlencode(" CONSEJOS DELANTEROS"),
"front_tool_tips_lower"=>urlencode("Consejos para herramientas frontales"),
"tool_tip_my_bookings"=>urlencode("Mis reservas"),
"tool_tip_postal_code"=>urlencode("Código postal"),
"tool_tip_services"=>urlencode("Servicios"),
"tool_tip_extra_service"=>urlencode("Servicio extra"),
"tool_tip_frequently_discount"=>urlencode(" Descuento frecuente"),
"tool_tip_when_would_you_like_us_to_come"=>urlencode("¿Cuándo quieres que vengamos?"),
"tool_tip_your_personal_details"=>urlencode("tus detalles personales"),
"tool_tip_have_a_promocode"=>urlencode("Tienes un código de promoción"),
"tool_tip_preferred_payment_method"=>urlencode(" Método de pago preferido"),
"login_page"=>urlencode(" Página de inicio de sesión"),
"front_page"=>urlencode(" Página delantera"),
"before_e_g_100"=>urlencode("Antes (por ejemplo, $ 100)"),
"after_e_g_100"=>urlencode("Después (por ejemplo, $ 100)"),
"tax_vat"=>urlencode("Tax / Vat"),
"wrong_url"=>urlencode("URL errónea"),
"choose_file"=>urlencode("Elija el archivo"),
"frontend_labels"=>urlencode("Etiquetas frontend"),
"admin_labels"=>urlencode("Etiquetas de administrador"),
"dropdown_design"=>urlencode("Diseño DropDown"),
"blocks_as_button_design"=>urlencode("Bloques como diseño de botones"),
"qty_control_design"=>urlencode("Qty Control Design"),
"dropdowns"=>urlencode(" Listas deplegables"),
"big_images_radio"=>urlencode("Big Images Radio"),
"errors"=>urlencode("Errores"),
"extra_labels"=>urlencode("Etiquetas adicionales"),
"api_password"=>urlencode("Contraseña API"),
"api_username"=>urlencode("Nombre de usuario API"),
"appearance"=>urlencode("APARIENCIA"),
"action"=>urlencode("Acción"),
"actions"=>urlencode("Comportamiento"),
"add_break"=>urlencode("Agregar descanso"),
"add_breaks"=>urlencode("Añadir escapadas"),
"add_cleaning_service"=>urlencode("Añadir servicio de limpieza"),
"add_method"=>urlencode("Agregar método"),
"add_new"=>urlencode("Agregar nuevo"),
"add_sample_data"=>urlencode("Añadir datos de muestra"),
"add_unit"=>urlencode("Agregar unidad"),
"add_your_off_times"=>urlencode("Agregue sus tiempos de apagado"),
"add_new_off_time"=>urlencode("Agregar nuevo tiempo de inactividad"),
"add_ons"=>urlencode("Complementos"),
"addons_bookings"=>urlencode("Reservas de complementos"),
"addon_service_front_view"=>urlencode("Vista frontal del servicio adicional"),
"addons"=>urlencode("Complementos"),
"service_commission"=>urlencode("Comisión de servicio"),
"commission_total"=>urlencode("Comisión Total"),
"address"=>urlencode("Dirección"),
"new_appointment_assigned"=>urlencode("Nueva cita asignada"),
"admin_email_notifications"=>urlencode("Notificaciones de correo electrónico de administrador"),
"all_payment_gateways"=>urlencode("Todas las pasarelas de pago"),
"all_services"=>urlencode("Todos los servicios"),
"allow_multiple_booking_for_same_timeslot"=>urlencode("Permitir varias reservas para el mismo intervalo de tiempo"),
"amount"=>urlencode("Cantidad"),
"app_date"=>urlencode("Aplicación Fecha"),
"appearance_settings"=>urlencode("Configuración de la apariencia"),
"appointment_completed"=>urlencode("Cita completada"),
"appointment_details"=>urlencode("Detalles de la cita"),
"appointment_marked_as_no_show"=>urlencode("Cita marcada como No Show"),
"mark_as_no_show"=>urlencode("Marcar como no mostrado"),
"appointment_reminder_buffer"=>urlencode("Tampón recordatorio de citas"),
"appointment_auto_confirm"=>urlencode("La cita confirma automáticamente"),
"appointments"=>urlencode("Equipo"),
"admin_area_color_scheme"=>urlencode("Esquema de colores del área de administración"),
"thankyou_page_url"=>urlencode("Gracias URL de la página"),
"addon_title"=>urlencode("Título del complemento"),
"availabilty"=>urlencode("Disponibilidad"),
"background_color"=>urlencode("Color de fondo"),
"behaviour_on_click_of_button"=>urlencode("Comportamiento al hacer clic en el botón"),
"book_now"=>urlencode("Reservar ahora"),
"booking_date_and_time"=>urlencode("Fecha y hora de reserva"),
"booking_details"=>urlencode("Detalles de reserva"),
"booking_information"=>urlencode("Infomación sobre reservas"),
"booking_serve_date"=>urlencode("Reserva Serve Date"),
"booking_status"=>urlencode("Estado de la reservación"),
"booking_notifications"=>urlencode("Notificaciones de reserva"),
"bookings"=>urlencode("Reservas"),
"button_position"=>urlencode("Posición del botón"),
"button_text"=>urlencode("Botón de texto"),
"company"=>urlencode("EMPRESA"),
"cannot_cancel_now"=>urlencode("No se puede cancelar ahora"),
"cannot_reschedule_now"=>urlencode("No se puede reprogramar ahora"),
"cancel"=>urlencode("Cancelar"),
"cancellation_buffer_time"=>urlencode("Tiempo de búfer de cancelación"),
"cancelled_by_client"=>urlencode("Cancelado por el cliente"),
"cancelled_by_service_provider"=>urlencode("Cancelado por el proveedor del servicio"),
"change_password"=>urlencode("Cambia la contraseña"),
"cleaning_service"=>urlencode("Servicio de limpieza"),
"client"=>urlencode("Cliente"),
"client_email_notifications"=>urlencode("Notificaciones de correo electrónico del cliente"),
"client_name"=>urlencode("nombre del cliente"),
"color_scheme"=>urlencode("Esquema de colores"),
"color_tag"=>urlencode("Etiqueta de color"),
"company_address"=>urlencode("Dirección"),
"company_email"=>urlencode("Email"),
"company_logo"=>urlencode("Logo de la compañía"),
"company_name"=>urlencode("Nombre del Negocio"),
"company_settings"=>urlencode("Configuración de información empresarial"),
"companyname"=>urlencode("nombre de empresa"),
"company_info_settings"=>urlencode("Configuración de información de la compañía"),
"completed"=>urlencode("Terminado"),
"confirm"=>urlencode("Confirmar"),
"confirmed"=>urlencode("Confirmado"),
"contact_status"=>urlencode("Estado de contacto"),
"country"=>urlencode("País"),
"country_code_phone"=>urlencode("Código de país (teléfono)"),
"coupon"=>urlencode("Cupón"),
"coupon_code"=>urlencode("Código promocional"),
"coupon_limit"=>urlencode("Límite de cupones"),
"coupon_type"=>urlencode("Tipo de cupón"),
"coupon_used"=>urlencode("Cupón utilizado"),
"coupon_value"=>urlencode("Valor de cupón"),
"create_addon_service"=>urlencode("Crear complemento de servicio"),
"crop_and_save"=>urlencode("Recortar y guardar"),
"currency"=>urlencode("Moneda"),
"currency_symbol_position"=>urlencode("Posición del símbolo de moneda"),
"customer"=>urlencode(" Cliente"),
"customer_information"=>urlencode("Información al cliente"),
"customers"=>urlencode("Clientes"),
"date_and_time"=>urlencode("Fecha y hora"),
"date_picker_date_format"=>urlencode("Formato de fecha del selector de fecha"),
"default_design_for_addons"=>urlencode("Diseño predeterminado para complementos"),
"default_design_for_methods_with_multiple_units"=>urlencode("Diseño predeterminado para métodos con unidades múltiples"),
"default_design_for_services"=>urlencode("Diseño predeterminado para servicios"),
"default_setting"=>urlencode("Configuración predeterminada"),
"delete"=>urlencode("Borrar"),
"description"=>urlencode("Descripción"),
"discount"=>urlencode("Descuento"),
"download_invoice"=>urlencode("Descargar factura"),
"email_notification"=>urlencode("NOTIFICACIÓN DE CORREO ELECTRÓNICO"),
"email"=>urlencode("Email"),
"email_settings"=>urlencode("Ajustes del correo electrónico"),
"embed_code"=>urlencode("Código de inserción"),
"enter_your_email_and_we_will_send_you_instructions_on_resetting_your_password"=>urlencode("Ingrese su correo electrónico y le enviaremos instrucciones para restablecer su contraseña."),
"expiry_date"=>urlencode("Fecha de caducidad"),
"export"=>urlencode("Exportar"),
"export_your_details"=>urlencode("Exportar tus detalles"),
"frequently_discount_setting_tabs"=>urlencode("DESCUENTO FRECUENTE"),
"frequently_discount_header"=>urlencode("Descuento frecuente"),
"field_is_required"=>urlencode("Se requiere campo"),
"file_size"=>urlencode("Tamaño del archivo"),
"flat_fee"=>urlencode("Tarifa fija"),
"flat"=>urlencode("Plano"),
"freq_discount"=>urlencode("Freq-descuento"),
"frequently_discount_label"=>urlencode("Etiqueta de descuento frecuente"),
"frequently_discount_type"=>urlencode("Tipo de descuento frecuente"),
"frequently_discount_value"=>urlencode("Valor de descuento frecuente"),
"front_service_box_view"=>urlencode("Vista de cuadro de servicio frontal"),
"front_service_dropdown_view"=>urlencode("Vista desplegable del servicio frontal"),
"front_view_options"=>urlencode("Opciones de vista frontal"),
"full_name"=>urlencode("Nombre completo"),
"general"=>urlencode("GENERAL"),
"general_settings"=>urlencode("Configuración general"),
"get_embed_code_to_show_booking_widget_on_your_website"=>urlencode("Obtenga un código incrustado para mostrar el widget de reserva en su sitio web"),
"get_the_embeded_code"=>urlencode("Obtenga el código incrustado"),
"guest_customers"=>urlencode("Clientes Invitados"),
"guest_user_checkout"=>urlencode("Salida de usuario invitado"),
"hide_faded_already_booked_time_slots"=>urlencode("Ocultar desvaneció ranuras de tiempo ya reservado"),
"hostname"=>urlencode("Nombre de host"),
"labels"=>urlencode("ETIQUETAS"),
"legends"=>urlencode("Leyendas"),
"login"=>urlencode("Iniciar sesión"),
"maximum_advance_booking_time"=>urlencode("Tiempo máximo de reserva anticipada"),
"method"=>urlencode("Método"),
"method_name"=>urlencode("Nombre del método"),
"method_title"=>urlencode("Título del método"),
"method_unit_quantity"=>urlencode("Cantidad de unidad de método"),
"method_unit_quantity_rate"=>urlencode("Método Cantidad de unidades"),
"method_unit_title"=>urlencode("Título de la unidad del método"),
"method_units_front_view"=>urlencode("Método de vista frontal de las unidades"),
"methods"=>urlencode("Métodos"),
"methods_booking"=>urlencode("Métodos de reserva"),
"methods_bookings"=>urlencode("Reservas de métodos"),
"minimum_advance_booking_time"=>urlencode("Tiempo mínimo de reserva anticipada"),
"more"=>urlencode("Más"),
"more_details"=>urlencode("Más detalles"),
"my_appointments"=>urlencode("Mis citas"),
"name"=>urlencode("Nombre"),
"net_total"=>urlencode("Total neto"),
"new_password"=>urlencode("Nueva contraseña"),
"notes"=>urlencode("Notas"),
"off_days"=>urlencode("Días de descanso"),
"off_time"=>urlencode("Fuera de tiempo"),
"old_password"=>urlencode("Contraseña anterior"),
"online_booking_button_style"=>urlencode("Estilo de botón de reserva en línea"),
"open_widget_in_a_new_page"=>urlencode("Abrir widget en una nueva página"),
"order"=>urlencode("Orden"),
"order_date"=>urlencode("Fecha de orden"),
"order_time"=>urlencode("Tiempo de la orden"),
"payments_setting"=>urlencode("PAGO"),
"promocode"=>urlencode("CÓDIGO PROMOCIONAL"),
"promocode_header"=>urlencode("Código promocional"),
"padding_time_before"=>urlencode("Tiempo de relleno antes"),
"parking"=>urlencode("Estacionamiento"),
"partial_amount"=>urlencode("Cantidad parcial"),
"partial_deposit"=>urlencode("Depósito parcial"),
"partial_deposit_amount"=>urlencode("Cantidad de depósito parcial"),
"partial_deposit_message"=>urlencode("Mensaje de depósito parcial"),
"password"=>urlencode("Contraseña"),
"payment"=>urlencode("Pago"),
"payment_date"=>urlencode("Fecha de pago"),
"payment_gateways"=>urlencode("Via de pago"),
"payment_method"=>urlencode("Método de pago"),
"payments"=>urlencode("Pagos"),
"payments_history_details"=>urlencode("Detalles del historial de pagos"),
"paypal_express_checkout"=>urlencode("Pago exprés"),
"paypal_guest_payment"=>urlencode("Pago de invitado de Paypal"),
"pending"=>urlencode("Pendiente"),
"percentage"=>urlencode("Porcentaje"),
"personal_information"=>urlencode("Informacion personal"),
"phone"=>urlencode("Teléfono"),
"please_copy_above_code_and_paste_in_your_website"=>urlencode("Copie el código anterior y péguelo en su sitio web."),
"please_enable_payment_gateway"=>urlencode("Habilite Payment Gateway"),
"please_set_below_values"=>urlencode("Por favor configure los valores debajo de"),
"port"=>urlencode("Puerto"),
"postal_codes"=>urlencode("Códigos postales"),
"price"=>urlencode("Precio"),
"price_calculation_method"=>urlencode("Método de cálculo de precio"),
"price_format_decimal_places"=>urlencode("Formato de precio"),
"pricing"=>urlencode("Precios"),
"primary_color"=>urlencode("Color primario"),
"privacy_policy_link"=>urlencode("Política de privacidad Enlace"),
"profile"=>urlencode("Perfil"),
"promocodes"=>urlencode("Códigos promocionales"),
"promocodes_list"=>urlencode("Lista de códigos promocionales"),
"registered_customers"=>urlencode("Clientes Registrados"),
"registered_customers_bookings"=>urlencode("Reservas de clientes registrados"),
"reject"=>urlencode("Rechazar"),
"rejected"=>urlencode("Rechazado"),
"remember_me"=>urlencode("Recuérdame"),
"remove_sample_data"=>urlencode("Eliminar datos de muestra"),
"reschedule"=>urlencode("Reprogramar"),
"reset"=>urlencode("Reiniciar"),
"reset_password"=>urlencode("Restablecer la contraseña"),
"reshedule_buffer_time"=>urlencode("Volver a programar el tiempo de búfer"),
"retype_new_password"=>urlencode("Reescriba nueva contraseña"),
"right_side_description"=>urlencode("Página de reservación Descripción del borde"),
"save"=>urlencode("Salvar"),
"save_availability"=>urlencode("Guardar disponibilidad"),
"save_setting"=>urlencode("Guardar configuración"),
"save_labels_setting"=>urlencode("Guardar configuración de etiquetas"),
"schedule"=>urlencode("Programar"),
"schedule_type"=>urlencode("Tipo de horario"),
"secondary_color"=>urlencode("Color secundario"),
"select_language_for_update"=>urlencode("Seleccione el idioma para la actualización"),
"select_language_to_change_label"=>urlencode("Seleccione el idioma para cambiar la etiqueta"),
"select_language_to_display"=>urlencode("Idioma"),
"display_sub_headers_below_headers"=>urlencode("Subcabeceras en la página de reservas"),
"select_payment_option_export_details"=>urlencode("Seleccione los detalles de exportación de la opción de pago"),
"send_mail"=>urlencode("Enviar correo"),
"sender_email_address_cleanto_admin_email"=>urlencode("Correo electrónico del remitente"),
"sender_name"=>urlencode("Nombre del remitente"),
"service"=>urlencode("Servicio"),
"service_add_ons_front_block_view"=>urlencode("Complementos de servicio Vista de bloque frontal"),
"service_add_ons_front_increase_decrease_view"=>urlencode("Complementos de servicio Vista de aumento / disminución frontal"),
"service_description"=>urlencode("Descripción del servicio"),
"service_front_view"=>urlencode("Servicio Vista frontal"),
"service_image"=>urlencode("Imagen de servicio"),
"service_methods"=>urlencode("Métodos de servicio"),
"service_padding_time_after"=>urlencode("Servicio de relleno Tiempo después"),
"padding_time_after"=>urlencode("Tiempo de relleno después"),
"service_padding_time_before"=>urlencode("Servicio de tiempo de relleno antes"),
"service_quantity"=>urlencode("Cantidad de servicio"),
"service_rate"=>urlencode("Tasa de servicio"),
"service_title"=>urlencode("Título del servicio"),
"serviceaddons_name"=>urlencode("Nombre de ServiceAddOns"),
"services"=>urlencode("Servicios"),
"services_information"=>urlencode("Información de servicios"),
"set_email_reminder_buffer"=>urlencode("Establecer el búfer de recordatorio de correo electrónico"),
"set_language"=>urlencode("Elegir lenguaje"),
"settings"=>urlencode("Configuraciones"),
"show_all_bookings"=>urlencode("Mostrar todas las reservas"),
"show_button_on_given_embeded_position"=>urlencode("Mostrar el botón en una posición incrustada dada"),
"show_coupons_input_on_checkout"=>urlencode("Mostrar entrada de cupones en el pago y envío"),
"show_on_a_button_click"=>urlencode("Mostrar con un clic en un clic"),
"show_on_page_load"=>urlencode("Mostrar en la carga de la página"),
"signature"=>urlencode("Firma"),
"sorry_wrong_email_or_password"=>urlencode("Lo siento Correo electrónico incorrecto o contraseña"),
"start_date"=>urlencode("Fecha de inicio"),
"status"=>urlencode("Estado"),
"submit"=>urlencode("Enviar"),
"staff_email_notification"=>urlencode("Notificación por correo electrónico del personal"),
"tax"=>urlencode("Impuesto"),
"test_mode"=>urlencode("Modo de prueba"),
"text_color"=>urlencode("Color de texto"),
"text_color_on_bg"=>urlencode("Color del texto en bg"),
"terms_and_condition_link"=>urlencode("Términos y Condiciones Link"),
"this_week_breaks"=>urlencode("Esta semana se rompe"),
"this_week_time_scheduling"=>urlencode("Este horario de la semana"),
"time_format"=>urlencode("Formato de tiempo"),
"time_interval"=>urlencode("Intervalo de tiempo"),
"timezone"=>urlencode("Zona horaria"),
"units"=>urlencode("Unidades"),
"unit_name"=>urlencode("Nombre de la unidad"),
"units_of_methods"=>urlencode("Unidades de métodos"),
"update"=>urlencode("Actualizar"),
"update_appointment"=>urlencode("Actualizar cita"),
"update_promocode"=>urlencode("Actualizar Promocode"),
"username"=>urlencode("Nombre de usuario"),
"vaccum_cleaner"=>urlencode("Aspiradora"),
"view_slots_by"=>urlencode("Ver ranuras por?"),
"week"=>urlencode("Semana"),
"week_breaks"=>urlencode("Semana de vacaciones"),
"week_time_scheduling"=>urlencode("Horario semanal"),
"widget_loading_style"=>urlencode("Estilo de carga de widgets"),
"zip"=>urlencode("Cremallera"),
"logout"=>urlencode("cerrar sesión"),
"to"=>urlencode("a"),
"add_new_promocode"=>urlencode("Agregar nuevo código promocional"),
"create"=>urlencode("Crear"),
"end_date"=>urlencode("Fecha final"),
"end_time"=>urlencode("Hora de finalización"),
"labels_settings"=>urlencode("Configuración de etiquetas"),
"limit"=>urlencode("Límite"),
"max_limit"=>urlencode("Límite máximo"),
"start_time"=>urlencode("Hora de inicio"),
"value"=>urlencode("Valor"),
"active"=>urlencode("Activo"),
"appointment_reject_reason"=>urlencode("Motivo de rechazo de cita"),
"search"=>urlencode("Buscar"),
"custom_thankyou_page_url"=>urlencode("URL de página personalizada gracias"),
"price_per_unit"=>urlencode("Precio por unidad"),
"confirm_appointment"=>urlencode("Confirmar cita"),
"reject_reason"=>urlencode("Rechazar la razón"),
"delete_this_appointment"=>urlencode("Eliminar esta cita"),
"close_notifications"=>urlencode("Notificaciones cerradas"),
"booking_cancel_reason"=>urlencode("Reserva cancelar motivo"),
"service_color_badge"=>urlencode("Insignia de color de servicio"),
"manage_price_calculation_methods"=>urlencode("Administrar los métodos de cálculo de precios"),
"manage_addons_of_this_service"=>urlencode("Administrar complementos de este servicio"),
"service_is_booked"=>urlencode("El servicio está reservado"),
"delete_this_service"=>urlencode("Eliminar este servicio"),
"delete_service"=>urlencode("Eliminar servicio"),
"remove_image"=>urlencode("Quita la imagen"),
"remove_service_image"=>urlencode("Eliminar imagen de servicio"),
"company_name_is_used_for_invoice_purpose"=>urlencode("El nombre de la compañía se utiliza para fines de facturación"),
"remove_company_logo"=>urlencode("Eliminar logotipo de la empresa"),
"time_interval_is_helpful_to_show_time_difference_between_availability_time_slots"=>urlencode("El intervalo de tiempo es útil para mostrar la diferencia de tiempo entre ranuras de tiempo de disponibilidad"),
"minimum_advance_booking_time_restrict_client_to_book_last_minute_booking_so_that_you_should_have_sufficient_time_before_appointment"=>urlencode("El tiempo mínimo de reserva anticipada restringe al cliente a la reserva de última hora, por lo que debe tener suficiente tiempo antes de la cita."),
"cancellation_buffer_helps_service_providers_to_avoid_last_minute_cancellation_by_their_clients"=>urlencode("El buffer de cancelación ayuda a los proveedores de servicios a evitar la cancelación de última hora por parte de sus clientes"),
"partial_payment_option_will_help_you_to_charge_partial_payment_of_total_amount_from_client_and_remaining_you_can_collect_locally"=>urlencode("La opción de pago parcial le ayudará a cobrar el pago parcial del monto total del cliente y el restante puede cobrar localmente"),
"allow_multiple_appointment_booking_at_same_time_slot_will_allow_you_to_show_availability_time_slot_even_you_have_booking_already_for_that_time"=>urlencode("Permitir la reserva de citas múltiples al mismo tiempo, le permitirá mostrar el intervalo de tiempo de disponibilidad incluso si ya tiene reserva para ese momento"),
"with_Enable_of_this_feature_Appointment_request_from_clients_will_be_auto_confirmed"=>urlencode("Con Habilitar esta función, la solicitud de cita de los clientes se confirmará automáticamente"),
"write_html_code_for_the_right_side_panel"=>urlencode("Escribir código HTML para el panel lateral derecho"),
"do_you_want_to_show_subheaders_below_the_headers"=>urlencode("¿Quieres mostrar subtítulos debajo de los encabezados"),
"you_can_show_hide_coupon_input_on_checkout_form"=>urlencode("Puede mostrar / ocultar la entrada de cupones en el formulario de pago"),
"with_this_feature_you_can_allow_a_visitor_to_book_appointment_without_registration"=>urlencode("Con esta función, puede permitir que un visitante reserve una cita sin registrarse"),
"paypal_api_username_can_get_easily_from_developer_paypal_com_account"=>urlencode("El nombre de usuario de Paypal API puede obtenerse fácilmente desde la cuenta de developer.paypal.com"),
"paypal_api_password_can_get_easily_from_developer_paypal_com_account"=>urlencode("La contraseña de la API de Paypal puede obtenerse fácilmente desde la cuenta de developer.paypal.com"),
"paypal_api_signature_can_get_easily_from_developer_paypal_com_account"=>urlencode("La firma API Paypal puede obtenerse fácilmente desde la cuenta developer.paypal.com"),
"let_user_pay_through_credit_card_without_having_paypal_account"=>urlencode("Permita que el usuario pague con tarjeta de crédito sin tener una cuenta Paypal"),
"you_can_enable_paypal_test_mode_for_sandbox_account_testing"=>urlencode("Puede habilitar el modo de prueba Paypal para las pruebas de cuenta de sandbox"),
"you_can_enable_authorize_net_test_mode_for_sandbox_account_testing"=>urlencode("Puede habilitar el modo de prueba Authorize.Net para la prueba de la cuenta de sandbox"),
"edit_coupon_code"=>urlencode("Editar código de cupón"),
"delete_promocode"=>urlencode("Eliminar código promocional?"),
"coupon_code_will_work_for_such_limit"=>urlencode("El código de cupón funcionará para dicho límite"),
"coupon_code_will_work_for_such_date"=>urlencode("El código de cupón funcionará para esa fecha"),
"coupon_value_would_be_consider_as_percentage_in_percentage_mode_and_in_flat_mode_it_will_be_consider_as_amount_no_need_to_add_percentage_sign_it_will_auto_added"=>urlencode("El valor del cupón se considerará como porcentaje en modo porcentual y en modo plano se considerará como importe. No es necesario agregar un signo de porcentaje para que se agregue automáticamente."),
"unit_is_booked"=>urlencode("La unidad está reservada"),
"delete_this_service_unit"=>urlencode("Eliminar esta unidad de servicio?"),
"delete_service_unit"=>urlencode("Eliminar unidad de servicio"),
"manage_unit_price"=>urlencode("Administrar precio unitario"),
"extra_service_title"=>urlencode("Título de servicio adicional"),
"addon_is_booked"=>urlencode("Complemento está reservado"),
"delete_this_addon_service"=>urlencode("Eliminar este servicio de complemento?"),
"choose_your_addon_image"=>urlencode("Elija su imagen de complemento"),
"addon_image"=>urlencode("Addon Image"),
"administrator_email"=>urlencode("Correo electrónico del administrador"),
"admin_profile_address"=>urlencode("Dirección"),
"default_country_code"=>urlencode("Código de país"),
"cancellation_policy"=>urlencode("Política de cancelación"),
"transaction_id"=>urlencode("ID de transacción"),
"sms_reminder"=>urlencode("Recordatorio de SMS"),
"save_sms_settings"=>urlencode("Guardar configuración de SMS"),
"sms_service"=>urlencode("Servicio de SMS"),
"it_will_send_sms_to_service_provider_and_client_for_appointment_booking"=>urlencode("Enviará sms al proveedor del servicio y al cliente para la reserva de citas"),
"twilio_account_settings"=>urlencode("Configuración de la cuenta Twilio"),
"plivo_account_settings"=>urlencode("Configuración de la cuenta Plivo"),
"account_sid"=>urlencode("SID de la cuenta"),
"auth_token"=>urlencode("Token de autenticación"),
"twilio_sender_number"=>urlencode("Número de remitente Twilio"),
"plivo_sender_number"=>urlencode("Número de remitente Plivo"),
"twilio_sms_settings"=>urlencode("Configuración de Twilio SMS"),
"plivo_sms_settings"=>urlencode("Configuración de Plivo SMS"),
"twilio_sms_gateway"=>urlencode("Twilio SMS Gateway"),
"plivo_sms_gateway"=>urlencode("Plivo SMS Gateway"),
"send_sms_to_client"=>urlencode("Enviar SMS al cliente"),
"send_sms_to_admin"=>urlencode("Enviar SMS a la administración"),
"admin_phone_number"=>urlencode("Número de teléfono del administrador"),
"available_from_within_your_twilio_account"=>urlencode("Disponible desde tu cuenta de Twilio."),
"must_be_a_valid_number_associated_with_your_twilio_account"=>urlencode("Debe ser un número válido asociado con su cuenta de Twilio."),
"enable_or_disable_send_sms_to_client_for_appointment_booking_info"=>urlencode("Habilitar o deshabilitar, enviar SMS al cliente para información de reserva de citas."),
"enable_or_disable_send_sms_to_admin_for_appointment_booking_info"=>urlencode("Habilitar o deshabilitar, enviar SMS al administrador para información de reserva de citas."),
"updated_sms_settings"=>urlencode("Configuraciones de SMS actualizadas"),
"parking_availability_frontend_option_display_status"=>urlencode("Estacionamiento"),
"vaccum_cleaner_frontend_option_display_status"=>urlencode("Aspiradora"),
"o_n"=>urlencode("En"),
"off"=>urlencode("Apagado"),
"enable"=>urlencode("Habilitar"),
"disable"=>urlencode("Inhabilitar"),
"monthly"=>urlencode("Mensual"),
"weekly"=>urlencode("Semanal"),
"email_template"=>urlencode("PLANTILLA DE CORREO ELECTRÓNICO"),
"sms_notification"=>urlencode("NOTIFICACIÓN DE SMS"),
"sms_template"=>urlencode("PLANTILLA DE SMS"),
"email_template_settings"=>urlencode("Configuración de plantilla de correo electrónico"),
"client_email_templates"=>urlencode("Plantilla de correo electrónico del cliente"),
"client_sms_templates"=>urlencode("Plantilla SMS de cliente"),
"admin_email_template"=>urlencode("Plantilla de correo electrónico de administrador"),
"admin_sms_template"=>urlencode("Plantilla de SMS de administrador"),
"tags"=>urlencode("Etiquetas"),
"booking_date"=>urlencode("fecha para registrarse"),
"service_name"=>urlencode("Nombre del Servicio"),
"business_logo"=>urlencode("business_logo"),
"business_logo_alt"=>urlencode("business_logo_alt"),
"admin_name"=>urlencode("admin_name"),
"methodname"=>urlencode("method_name"),
"firstname"=>urlencode("nombre de pila"),
"lastname"=>urlencode("apellido"),
"client_email"=>urlencode("client_email"),
"vaccum_cleaner_status"=>urlencode("vaccum_cleaner_status"),
"parking_status"=>urlencode("parking_status"),
"app_remain_time"=>urlencode("app_remain_time"),
"reject_status"=>urlencode("reject_status"),
"save_template"=>urlencode("Guardar plantilla"),
"default_template"=>urlencode("Plantilla predeterminada"),
"sms_template_settings"=>urlencode("Configuración de plantilla de SMS"),
"secret_key"=>urlencode("Llave secreta"),
"publishable_key"=>urlencode("Clave publicable"),
"payment_form"=>urlencode("Formulario de pago"),
"api_login_id"=>urlencode("ID de inicio de sesión API"),
"transaction_key"=>urlencode("Clave de transacción"),
"sandbox_mode"=>urlencode("modo sandbox"),
"available_from_within_your_plivo_account"=>urlencode("Disponible desde tu cuenta de Plivo."),
"must_be_a_valid_number_associated_with_your_plivo_account"=>urlencode("Debe ser un número válido asociado con su cuenta de Plivo."),
"whats_new"=>urlencode("¿Qué hay de nuevo?"),
"company_phone"=>urlencode("Teléfono"),
"company__name"=>urlencode("nombre de empresa"),
"booking_time"=>urlencode("booking_time"),
"company__email"=>urlencode("company_email"),
"company__address"=>urlencode("company_address"),
"company__zip"=>urlencode("company_zip"),
"company__phone"=>urlencode("company_phone"),
"company__state"=>urlencode("company_state"),
"company__country"=>urlencode("company_country"),
"company__city"=>urlencode("company_city"),
"page_title"=>urlencode("Page Title"),
"client__zip"=>urlencode("client_zip"),
"client__state"=>urlencode("client_state"),
"client__city"=>urlencode("client_city"),
"client__address"=>urlencode("client_address"),
"client__phone"=>urlencode("client_phone"),
"company_logo_is_used_for_invoice_purpose"=>urlencode("El logotipo de la empresa se utiliza en la página de correo electrónico y reserva"),
"private_key"=>urlencode("Llave privada"),
"seller_id"=>urlencode("Identificación del vendedor"),
"postal_codes_ed"=>urlencode("Puede habilitar o deshabilitar la función postal o de códigos postales según los requisitos de su país, ya que algunos países como Emiratos Árabes Unidos no tienen código postal."),
"postal_codes_info"=>urlencode("Puede mencionar los códigos postales de dos maneras: # 1. Puede mencionar códigos postales completos para coincidencia como K1A232, L2A334, C3A4C4. # 2. Puede usar códigos postales parciales para las entradas de partido de comodín, p. Ej. El sistema K1A, L2A, C3 coincidirá con las letras iniciales del código postal en el frente y evitará que escriba tantos códigos postales."),
"first"=>urlencode("primero"),
"second"=>urlencode("Segundo"),
"third"=>urlencode("Tercero"),
"fourth"=>urlencode("Cuarto"),
"fifth"=>urlencode("Quinto"),
"first_week"=>urlencode("First-Week"),
"second_week"=>urlencode("Second-Week"),
"third_week"=>urlencode("Third-Week"),
"fourth_week"=>urlencode("Fourth-Week"),
"fifth_week"=>urlencode("Fifth-Week"),
"this_week"=>urlencode("Esta semana"),
"monday"=>urlencode("lunes"),
"tuesday"=>urlencode("martes"),
"wednesday"=>urlencode("miércoles"),
"thursday"=>urlencode("jueves"),
"friday"=>urlencode("viernes"),
"saturday"=>urlencode("sábado"),
"sunday"=>urlencode("domingo"),
"appointment_request"=>urlencode("Solicitud de cita"),
"appointment_approved"=>urlencode("Cita aprobada"),
"appointment_rejected"=>urlencode("Nombramiento rechazado"),
"appointment_cancelled_by_you"=>urlencode("Cita cancelada por usted"),
"appointment_rescheduled_by_you"=>urlencode("Cita reprogramada por usted"),
"client_appointment_reminder"=>urlencode("Recordatorio de cita del cliente"),
"new_appointment_request_requires_approval"=>urlencode("Nueva solicitud de cita requiere aprobación"),
"appointment_cancelled_by_customer"=>urlencode("Cita cancelada por el cliente"),
"appointment_rescheduled_by_customer"=>urlencode("Cita reprogramada por el cliente"),
"admin_appointment_reminder"=>urlencode("Recordatorio de citas administrativas"),
"off_days_added_successfully"=>urlencode("Días sin descuento agregados con éxito"),
"off_days_deleted_successfully"=>urlencode("Días perdidos eliminados con éxito"),
"sorry_not_available"=>urlencode("Lo siento no disponible"),
"success"=>urlencode("Éxito"),
"failed"=>urlencode("Ha fallado"),
"once"=>urlencode("Una vez"),
"Bi_Monthly"=>urlencode("Bimensual"),
"Fortnightly"=>urlencode("Quincenal"),
"Recurrence_Type"=>urlencode("Tipo de recurrencia"),
"bi_weekly"=>urlencode("Quincenal"),
"Daily"=>urlencode("Diario"),
"guest_customers_bookings"=>urlencode("Reservas de Clientes Invitados"),
"existing_and_new_user_checkout"=>urlencode("Pago de usuario existente y nuevo"),
"it_will_allow_option_for_user_to_get_booking_with_new_user_or_existing_user"=>urlencode("Permitirá que el usuario pueda reservar con un nuevo usuario o usuario existente"),
"0_1"=>urlencode("01"),
"1_1"=>urlencode("1.1"),
"1_2"=>urlencode("1.2"),
"0_2"=>urlencode("02"),
"free"=>urlencode("Gratis"),
"show_company_address_in_header"=>urlencode("Mostrar la dirección de la empresa en el encabezado"),
"calendar_week"=>urlencode("Semana"),
"calendar_month"=>urlencode("Mes"),
"calendar_day"=>urlencode("Día"),
"calendar_today"=>urlencode("Hoy"),
"restore_default"=>urlencode("Restaurar por defecto"),
"scrollable_cart"=>urlencode("Carro desplazable"),
"merchant_key"=>urlencode("Clave del comerciante"),
"salt_key"=>urlencode("Salt Key"),
"textlocal_sms_gateway"=>urlencode("Textlocal SMS Gateway"),
"textlocal_sms_settings"=>urlencode("Configuración de SMS de Textlocal"),
"textlocal_account_settings"=>urlencode("Configuración de la cuenta de Textlocal"),
"account_username"=>urlencode("Nombre de usuario"),
"account_hash_id"=>urlencode("ID de hash de cuenta"),
"email_id_registered_with_you_textlocal"=>urlencode("Proporcione su correo electrónico registrado con texto local"),
"hash_id_provided_by_textlocal"=>urlencode("ID de hash proporcionado por textlocal"),
"bank_transfer"=>urlencode("Transferencia bancaria"),
"bank_name"=>urlencode("Nombre del banco"),
"account_name"=>urlencode("Nombre de la cuenta"),
"account_number"=>urlencode("Número de cuenta"),
"branch_code"=>urlencode("Código de rama"),
"ifsc_code"=>urlencode("Código IFSC"),
"bank_description"=>urlencode("Descripción del banco"),
"your_cart_items"=>urlencode("Tus artículos del carro"),
"show_how_will_we_get_in"=>urlencode("Mostrar cómo entraremos"),
"show_description"=>urlencode("Mostrar descripcion"),
"bank_details"=>urlencode("Detalles del banco"),
"ok_remove_sample_data"=>urlencode("De acuerdo"),
"book_appointment"=>urlencode("Reservar una cita"),
"remove_sample_data_message"=>urlencode("Está intentando eliminar datos de muestra. Si elimina los datos de muestra, su reserva relacionada con los servicios de muestra se eliminará permanentemente. Para continuar, haga clic en 'Aceptar'"),
"recommended_image_type_jpg_jpeg_png_gif"=>urlencode("(Tipo de imagen recomendada jpg, jpeg, png, gif)"),
"authetication"=>urlencode("Autenticación"),
"encryption_type"=>urlencode("Tipo de cifrado"),
"plain"=>urlencode("Llanura"),
"true"=>urlencode("Cierto"),
"false"=>urlencode("Falso"),
"change_calculation_policy"=>urlencode("Cálculo de cambio"),
"multiply"=>urlencode("Multiplicar"),
"equal"=>urlencode("Igual"),
"warning"=>urlencode("¡Advertencia!"),
"field_name"=>urlencode("Nombre del campo"),
"enable_disable"=>urlencode("Habilitar/deshabilitar"),
"required"=>urlencode("Necesario"),
"min_length"=>urlencode("Longitud mínima"),
"max_length"=>urlencode("Longitud máxima"),
"appointment_details_section"=>urlencode("Sección de detalles de la cita"),
"if_you_are_having_booking_system_which_need_the_booking_address_then_please_make_this_field_enable_or_else_it_will_not_able_to_take_the_booking_address_and_display_blank_address_in_the_booking"=>urlencode("Si tiene un sistema de reserva que necesita la dirección de reserva, habilite este campo o de lo contrario no podrá tomar la dirección de reserva y mostrar la dirección en blanco en la reserva."),
"front_language_dropdown"=>urlencode("Configuración desplegable del idioma frontal"),
"enabled"=>urlencode("Habilitado"),
"vaccume_cleaner"=>urlencode("Aspiradora"),
"staff_members"=>urlencode("Los miembros del personal"),
"add_new_staff_member"=>urlencode("Agregar nuevo miembro del personal"),
"role"=>urlencode("Papel"),
"staff"=>urlencode("Staff"),
"admin"=>urlencode("Administración"),
"service_details"=>urlencode("Detalles del servicio"),
"technical_admin"=>urlencode("Administrador técnico"),
"enable_booking"=>urlencode("Habilitar reserva"),
"flat_commission"=>urlencode("Comisión plana"),
"manageable_form_fields_front_booking_form"=>urlencode("Campos de formulario manejables para formulario de reserva frontal"),
"manageable_form_fields"=>urlencode("Campos de formulario manejables"),
"sms"=>urlencode("SMS"),
"crm"=>urlencode("CRM"),
"message"=>urlencode("Mensaje"),
"send_message"=>urlencode("Enviar mensaje"),
"all_messages"=>urlencode("Todos los mensajes"),
"subject"=>urlencode("Tema"),
"add_attachment"=>urlencode("Añadir un adjunto"),
"send"=>urlencode("Enviar"),
"close"=>urlencode("Cerca"),
"delete_this_customer?"=>urlencode("Eliminar este cliente?"),
"yes"=>urlencode(" Sí"),
"add_new_customer"=>urlencode("Añadir nuevo cliente"),
"attachment"=>urlencode("adjunto archivo"),
"date"=>urlencode("fecha"),
"see_attachment"=>urlencode("Ver archivo adjunto"),
"no_attachment"=>urlencode("No hay adjuntos"),
"ct_special_offer"=>urlencode("Oferta especial"),
"ct_special_offer_text"=>urlencode("Texto de oferta especial"),
);

$error_labels_es_ES = array (
"language_status_change_successfully"=>urlencode("Cambio de estado del idioma con éxito"),
"commission_amount_should_not_be_greater_then_order_amount"=>urlencode("La cantidad de la comisión no debe ser mayor que el monto de la orden"),
"please_enter_merchant_ID"=>urlencode("Ingrese ID de comerciante"),
"please_enter_secure_key"=>urlencode("Por favor ingrese la clave segura"),
"please_enter_google_calender_admin_url"=>urlencode("Por favor, ingrese google calendar url admin"),
"please_enter_google_calender_frontend_url"=>urlencode("Por favor ingrese la URL de la interfaz de google calender"),
"please_enter_google_calender_client_secret"=>urlencode("Ingrese el secreto del cliente de google calendar"),
"please_enter_google_calender_client_ID"=>urlencode("Ingrese la ID del cliente de google calendar"),
"please_enter_google_calender_ID"=>urlencode("Ingrese la ID del calendario de google"),
"you_cannot_book_on_past_date"=>urlencode("No puedes reservar en una fecha pasada"),
"Invalid_Image_Type"=>urlencode("Tipo de imagen inválida"),
"seo_settings_updated_successfully"=>urlencode("Configuración de SEO actualizada con éxito"),
"customer_deleted_successfully"=>urlencode("Cliente eliminado con éxito"),
"please_enter_below_36_characters"=>urlencode("Por favor ingrese abajo 36 caracteres"),
"are_you_sure_you_want_to_delete_client"=>urlencode("¿Estás seguro de que quieres eliminar al cliente?"),
"please_select_atleast_one_unit"=>urlencode("Seleccione al menos una unidad"),
"atleast_one_payment_method_should_be_enable"=>urlencode("Al menos un método de pago debe ser habilitado"),
"appointment_booking_confirm"=>urlencode("Confirmación de reserva de cita"),
"appointment_booking_rejected"=>urlencode("Reserva de citas rechazada"),
"booking_cancel"=>urlencode("Boooking cancelado"),
"appointment_marked_as_no_show"=>urlencode("Cita marcada como no show"),
"appointment_reschedules_successfully"=>urlencode("La cita reprograma exitosamente"),
"booking_deleted"=>urlencode("Reserva eliminada"),
"break_end_time_should_be_greater_than_start_time"=>urlencode("Break End Time debe ser mayor que Start time"),
"cancel_by_client"=>urlencode("Cancelar por cliente"),
"cancelled_by_service_provider"=>urlencode("Cancelado por el proveedor del servicio"),
"design_set_successfully"=>urlencode("Diseño establecido con éxito"),
"end_break_time_updated"=>urlencode("Tiempo final de descanso actualizado"),
"enter_alphabets_only"=>urlencode("Ingrese solo alfabetos"),
"enter_only_alphabets"=>urlencode("Ingresa solo alfabetos"),
"enter_only_alphabets_numbers"=>urlencode("Ingresa solo alfabetos / números"),
"enter_only_digits"=>urlencode("Ingresa solo dígitos"),
"enter_valid_url"=>urlencode("Ingrese una URL válida"),
"enter_only_numeric"=>urlencode("Ingrese solo numérico"),
"enter_proper_country_code"=>urlencode("Ingrese el código de país correcto"),
"frequently_discount_status_updated"=>urlencode("Estado de descuento frecuente actualizado"),
"frequently_discount_updated"=>urlencode("Descuento frecuente actualizado"),
"manage_addons_service"=>urlencode("Administrar el servicio de complementos"),
"maximum_file_upload_size_2_mb"=>urlencode("Tamaño máximo de carga de archivos 2 MB"),
"method_deleted_successfully"=>urlencode("Método eliminado con éxito"),
"method_inserted_successfully"=>urlencode("Método insertado con éxito"),
"minimum_file_upload_size_1_kb"=>urlencode("Tamaño mínimo de carga de archivos 1 KB"),
"off_time_added_successfully"=>urlencode("Hora de apagado agregada con éxito"),
"only_jpeg_png_and_gif_images_allowed"=>urlencode("Solo se permiten imágenes jpeg, png y gif"),
"only_jpeg_png_gif_zip_and_pdf_allowed"=>urlencode("Solo se permiten jpeg, png, gif, zip y pdf"),
"please_wait_while_we_send_all_your_message"=>urlencode("Por favor espere mientras enviamos todos sus mensajes"),
"please_enable_email_to_client"=>urlencode("Habilite los correos electrónicos al cliente."),
"please_enable_sms_gateway"=>urlencode("Habilite SMS Gateway."),
"please_enable_client_notification"=>urlencode("Habilite la notificación del cliente."),
"password_must_be_8_character_long"=>urlencode("La contraseña debe tener 8 caracteres de largo"),
"password_should_not_exist_more_then_20_characters"=>urlencode("La contraseña no debería existir más de 20 caracteres"),
"please_assign_base_price_for_unit"=>urlencode("Por favor, asigne el precio base para la unidad"),
"please_assign_price"=>urlencode("Por favor asignar precio"),
"please_assign_qty"=>urlencode("Por favor, asignar cantidad"),
"please_enter_api_password"=>urlencode("Por favor ingrese la contraseña de API"),
"please_enter_api_username"=>urlencode("Por favor ingrese el nombre de usuario de API"),
"please_enter_color_code"=>urlencode("Por favor ingrese el código de color"),
"please_enter_country"=>urlencode("Por favor ingrese país"),
"please_enter_coupon_limit"=>urlencode("Por favor ingrese el límite de cupón"),
"please_enter_coupon_value"=>urlencode("Por favor ingrese el límite de cupón"),
"please_enter_coupon_code"=>urlencode("Por favor ingrese el código de cupón"),
"please_enter_email"=>urlencode("Por favor ingrese el correo"),
"please_enter_fullname"=>urlencode("Por favor ingrese Fullname"),
"please_enter_maxlimit"=>urlencode("Por favor, introduzca maxLimit"),
"please_enter_method_title"=>urlencode("Por favor ingrese el título del método"),
"please_enter_name"=>urlencode("Por favor ingrese el nombre"),
"please_enter_only_numeric"=>urlencode("Por favor ingrese solo numérico"),
"please_enter_proper_base_price"=>urlencode("Por favor ingrese el precio base correcto"),
"please_enter_proper_name"=>urlencode("Por favor ingrese el nombre propio"),
"please_enter_proper_title"=>urlencode("Por favor ingrese el título apropiado"),
"please_enter_publishable_key"=>urlencode("Por favor ingrese la clave publicable"),
"please_enter_secret_key"=>urlencode("Por favor ingrese la clave secreta"),
"please_enter_service_title"=>urlencode("Por favor ingrese el título del servicio"),
"please_enter_signature"=>urlencode("Por favor ingrese la firma"),
"please_enter_some_qty"=>urlencode("Por favor ingrese alguna cantidad"),
"please_enter_title"=>urlencode("Por favor ingrese el título"),
"please_enter_unit_title"=>urlencode("Ingrese el título de la unidad"),
"please_enter_valid_country_code"=>urlencode("Por favor ingrese el código de país válido"),
"please_enter_valid_service_title"=>urlencode("Ingrese un título de servicio válido"),
"please_enter_valid_price"=>urlencode("Por favor ingrese el precio válido"),
"please_enter_zipcode"=>urlencode("Por favor ingrese el código postal"),
"please_enter_state"=>urlencode("Por favor ingrese estado"),
"please_retype_correct_password"=>urlencode("Por favor, vuelva a escribir la contraseña correcta"),
"please_select_porper_time_slots"=>urlencode("Por favor seleccione ranuras de tiempo porper"),
"please_select_time_between_day_availability_time"=>urlencode("Por favor seleccione el tiempo entre el tiempo de disponibilidad del día"),
"please_valid_value_for_discount"=>urlencode("Por favor valor válido para descuento"),
"please_enter_confirm_password"=>urlencode("Por favor ingrese confirmar contraseña"),
"please_enter_new_password"=>urlencode("Por favor ingrese una nueva contraseña"),
"please_enter_old_password"=>urlencode("Por favor ingrese la contraseña anterior"),
"please_enter_valid_number"=>urlencode("Por favor ingrese un número válido"),
"please_enter_valid_number_with_country_code"=>urlencode("Por favor ingrese un número válido con el código del país"),
"please_select_end_time_greater_than_start_time"=>urlencode("Seleccione el tiempo de finalización mayor que la hora de inicio"),
"please_select_end_time_less_than_start_time"=>urlencode("Por favor, seleccione la hora de finalización inferior a la hora de inicio"),
"please_select_a_crop_region_and_then_press_upload"=>urlencode("Seleccione una región de recorte y luego presione subir"),
"please_select_a_valid_image_file_jpg_and_png_are_allowed"=>urlencode("Seleccione un archivo de imagen válido jpg y png están permitidos"),
"profile_updated_successfully"=>urlencode("perfil actualizado con éxito"),
"qty_rule_deleted"=>urlencode("Regla de cantidad eliminada"),
"record_deleted_successfully"=>urlencode("Grabar eliminado con éxito"),
"record_updated_successfully"=>urlencode("Registro actualizado con éxito"),
"rescheduled"=>urlencode("Reprogramado"),
"schedule_updated_to_monthly"=>urlencode("Horario actualizado a Mensual"),
"schedule_updated_to_weekly"=>urlencode("Horario actualizado a Weekly"),
"sorry_method_already_exist"=>urlencode("Lo siento método ya existe"),
"sorry_no_notification"=>urlencode("Lo siento, no tienes ninguna cita próxima"),
"sorry_promocode_already_exist"=>urlencode("Lo sentimos Promocode ya existe"),
"sorry_unit_already_exist"=>urlencode("Lo siento unidad ya existe"),
"sorry_we_are_not_available"=>urlencode("Lo sentimos, no estamos disponibles"),
"start_break_time_updated"=>urlencode("Tiempo de descanso de inicio actualizado"),
"status_updated"=>urlencode("Estado actualizado"),
"time_slots_updated_successfully"=>urlencode("Los intervalos de tiempo se actualizaron con éxito"),
"unit_inserted_successfully"=>urlencode("Unidad insertada con éxito"),
"units_status_updated"=>urlencode("Estado de las unidades actualizado"),
"updated_appearance_settings"=>urlencode("Aettings actualizados de la apariencia"),
"updated_company_details"=>urlencode("Detalles actualizados de la compañía"),
"updated_email_settings"=>urlencode("Configuración actualizada de correo electrónico"),
"updated_general_settings"=>urlencode("Configuraciones generales actualizadas"),
"updated_payments_settings"=>urlencode("Configuraciones de pagos actualizadas"),
"your_old_password_incorrect"=>urlencode("Contraseña anterior incorrecta"),
"please_enter_minimum_5_chars"=>urlencode("Por favor ingrese un mínimo de 5 caracteres"),
"please_enter_maximum_10_chars"=>urlencode("Por favor ingrese un máximo de 10 caracteres"),
"please_enter_postal_code"=>urlencode("Por favor ingrese el código postal"),
"please_select_a_service"=>urlencode("Por favor seleccione un servicio"),
"please_select_units_and_addons"=>urlencode("Por favor seleccione unidades y complementos"),
"please_select_units_or_addons"=>urlencode("Seleccione unidades o complementos"),
"please_login_to_complete_booking"=>urlencode("Inicia sesión para completar la reserva"),
"please_select_appointment_date"=>urlencode("Por favor seleccione la fecha de cita"),
"please_accept_terms_and_conditions"=>urlencode("Por favor, acepte los términos y condiciones"),
"incorrect_email_address_or_password"=>urlencode("Dirección de correo electrónico o contraseña incorrecta"),
"please_enter_valid_email_address"=>urlencode("Por favor ingrese una dirección de correo electrónico válida"),
"please_enter_email_address"=>urlencode("Por favor ingrese la dirección de correo"),
"please_enter_password"=>urlencode("Por favor, ingrese contraseña"),
"please_enter_minimum_8_characters"=>urlencode("Por favor ingrese un mínimo de 8 caracteres"),
"please_enter_maximum_15_characters"=>urlencode("Por favor ingrese un máximo de 15 caracteres"),
"please_enter_first_name"=>urlencode("Por favor ingrese su nombre"),
"please_enter_only_alphabets"=>urlencode("Por favor ingrese solo alfabetos"),
"please_enter_minimum_2_characters"=>urlencode("Por favor ingrese un mínimo de 2 caracteres"),
"please_enter_last_name"=>urlencode("Por favor ingrese el apellido"),
"email_already_exists"=>urlencode("el Email ya existe"),
"please_enter_phone_number"=>urlencode("Por favor ingrese el número de teléfono"),
"please_enter_only_numerics"=>urlencode("Por favor ingrese solo los números"),
"please_enter_minimum_10_digits"=>urlencode("Por favor ingrese un mínimo de 10 dígitos"),
"please_enter_maximum_14_digits"=>urlencode("Por favor ingrese un máximo de 14 dígitos"),
"please_enter_address"=>urlencode("Por favor ingrese la dirección"),
"please_enter_minimum_20_characters"=>urlencode("Por favor ingrese un mínimo de 20 caracteres"),
"please_enter_zip_code"=>urlencode("Por favor ingrese el código postal"),
"please_enter_proper_zip_code"=>urlencode("Por favor ingrese el código postal correcto"),
"please_enter_minimum_5_digits"=>urlencode("Por favor ingrese un mínimo de 5 dígitos"),
"please_enter_maximum_7_digits"=>urlencode("Por favor ingrese un máximo de 7 dígitos"),
"please_enter_city"=>urlencode("Por favor ingresa ciudad"),
"please_enter_proper_city"=>urlencode("Por favor ingrese la ciudad apropiada"),
"please_enter_maximum_48_characters"=>urlencode("Por favor ingrese un máximo de 48 caracteres"),
"please_enter_proper_state"=>urlencode("Por favor ingrese el estado apropiado"),
"please_enter_contact_status"=>urlencode("Por favor ingrese el estado del contacto"),
"please_enter_maximum_100_characters"=>urlencode("Por favor ingrese un máximo de 100 caracteres"),
"your_cart_is_empty_please_add_cleaning_services"=>urlencode("Su carrito está vacío, agregue servicios de limpieza"),
"coupon_expired"=>urlencode("Cupón caducado"),
"invalid_coupon"=>urlencode("Cupón inválido"),
"our_service_not_available_at_your_location"=>urlencode("Nuestro servicio no está disponible en su ubicación"),
"please_enter_proper_postal_code"=>urlencode("Por favor ingrese el código postal correcto"),
"invalid_email_id_please_register_first"=>urlencode("ID de correo electrónico no válido, regístrese primero"),
"your_password_send_successfully_at_your_registered_email_id"=>urlencode("Su contraseña se envía correctamente a su ID de correo electrónico registrado"),
"your_password_reset_successfully_please_login"=>urlencode("Su contraseña restablecida con éxito por favor inicie sesión"),
"new_password_and_retype_new_password_mismatch"=>urlencode("Nueva contraseña y vuelva a escribir la nueva contraseña no coincidente"),
"new"=>urlencode("Nuevo"),
"your_reset_password_link_expired"=>urlencode("Su enlace de restablecimiento de contraseña expiró"),
"front_display_language_changed"=>urlencode("El idioma de la pantalla frontal ha cambiado"),
"updated_front_display_language_and_update_labels"=>urlencode("Lenguaje de pantalla frontal actualizado y etiquetas de actualización"),
"please_enter_only_7_chars_maximum"=>urlencode("Por favor ingrese solo 7 caracteres como máximo"),
"please_enter_maximum_20_chars"=>urlencode("Por favor ingrese un máximo de 20 caracteres"),
"record_inserted_successfully"=>urlencode("Registro insertado con éxito"),
"please_enter_account_sid"=>urlencode("Por favor ingrese Accout SID"),
"please_enter_auth_token"=>urlencode("Por favor ingrese Auth Token"),
"please_enter_sender_number"=>urlencode("Por favor ingrese el número del remitente"),
"please_enter_admin_number"=>urlencode("Por favor ingrese el número de administrador"),
"sorry_service_already_exist"=>urlencode("Lo siento, el servicio ya existe"),
"please_enter_api_login_id"=>urlencode("Por favor ingrese la ID de inicio"),
"please_enter_transaction_key"=>urlencode("Por favor ingrese la clave de transacción"),
"please_enter_sms_message"=>urlencode("Por favor ingrese el mensaje de sms"),
"please_enter_email_message"=>urlencode("Por favor ingrese un mensaje de correo"),
"please_enter_private_key"=>urlencode("Por favor ingrese la clave privada"),
"please_enter_seller_id"=>urlencode("Por favor ingrese la identificación del vendedor"),
"please_enter_valid_value_for_discount"=>urlencode("Por favor ingrese un valor válido para el descuento"),
"password_must_be_only_10_characters"=>urlencode("La contraseña debe tener solo 10 caracteres"),
"password_at_least_have_8_characters"=>urlencode("La contraseña tiene al menos 8 caracteres"),
"please_enter_retype_new_password"=>urlencode("Por favor, introduzca volver a escribir la nueva contraseña"),
"your_password_send_successfully_at_your_email_id"=>urlencode("Su contraseña se envía correctamente a su ID de correo electrónico"),
"please_select_expiry_date"=>urlencode("Seleccione fecha de caducidad"),
"please_enter_merchant_key"=>urlencode("Por favor ingrese la clave del comerciante"),
"please_enter_salt_key"=>urlencode("Por favor ingrese Salt Key"),
"please_enter_account_username"=>urlencode("Por favor ingrese el nombre de usuario"),
"please_enter_account_hash_id"=>urlencode("Por favor ingrese la identificación hash de la cuenta"),
"invalid_values"=>urlencode("Valores inválidos"),
"please_select_atleast_one_checkout_method"=>urlencode("Seleccione al menos un método de pago"),
);

$extra_labels_es_ES = array (
"please_enter_minimum_3_chars"=>urlencode("Por favor ingrese un mínimo de 3 caracteres"),
"invoice"=>urlencode("FACTURA"),
"invoice_to"=>urlencode("FACTURA A"),
"invoice_date"=>urlencode("Fecha de la factura"),
"cash"=>urlencode("EFECTIVO"),
"service_name"=>urlencode("Nombre del Servicio"),
"qty"=>urlencode("Cantidad"),
"booked_on"=>urlencode("Reservado en"),
);

$front_error_labels_es_ES = array (
"min_ff_ps"=>urlencode("Por favor ingrese un mínimo de 8 caracteres"),
"max_ff_ps"=>urlencode("Por favor ingrese un máximo de 10 caracteres"),
"req_ff_fn"=>urlencode("Por favor ingrese su nombre"),
"min_ff_fn"=>urlencode("Por favor ingrese un mínimo de 3 caracteres"),
"max_ff_fn"=>urlencode("Por favor ingrese un máximo de 15 caracteres"),
"req_ff_ln"=>urlencode("Por favor ingrese el apellido"),
"min_ff_ln"=>urlencode("Por favor ingrese un mínimo de 3 caracteres"),
"max_ff_ln"=>urlencode("Por favor ingrese un máximo de 15 caracteres"),
"req_ff_ph"=>urlencode("Por favor ingrese el número de teléfono"),
"min_ff_ph"=>urlencode("Por favor ingrese un mínimo de 9 caracteres"),
"max_ff_ph"=>urlencode("Por favor ingrese un máximo de 15 caracteres"),
"req_ff_sa"=>urlencode("Por favor ingrese la dirección"),
"min_ff_sa"=>urlencode("Por favor ingrese un mínimo de 10 caracteres"),
"max_ff_sa"=>urlencode("Por favor ingrese un máximo de 40 caracteres"),
"req_ff_zp"=>urlencode("Por favor ingrese el código postal"),
"min_ff_zp"=>urlencode("Por favor ingrese un mínimo de 3 caracteres"),
"max_ff_zp"=>urlencode("Por favor ingrese un máximo de 7 caracteres"),
"req_ff_ct"=>urlencode("Por favor ingresa ciudad"),
"min_ff_ct"=>urlencode("Por favor ingrese un mínimo de 3 caracteres"),
"max_ff_ct"=>urlencode("Por favor ingrese un máximo de 15 caracteres"),
"req_ff_st"=>urlencode("Por favor ingrese estado"),
"min_ff_st"=>urlencode("Por favor ingrese un mínimo de 3 caracteres"),
"max_ff_st"=>urlencode("Por favor ingrese un máximo de 15 caracteres"),
"req_ff_srn"=>urlencode("Por favor ingrese notas"),
"min_ff_srn"=>urlencode("Por favor ingrese un mínimo de 10 caracteres"),
"max_ff_srn"=>urlencode("Por favor ingrese un máximo de 70 caracteres"),
"Transaction_failed_please_try_again"=>urlencode("Transacción fallida, inténtelo de nuevo"),
"Please_Enter_valid_card_detail"=>urlencode("Por favor ingrese el detalle válido de la tarjeta"),
);

$language_front_arr_es_ES = base64_encode(serialize($label_data_es_ES));
$language_admin_arr_es_ES = base64_encode(serialize($admin_labels_es_ES));
$language_error_arr_es_ES = base64_encode(serialize($error_labels_es_ES));
$language_extra_arr_es_ES = base64_encode(serialize($extra_labels_es_ES));
$language_form_error_arr_es_ES = base64_encode(serialize($front_error_labels_es_ES));

$insert_default_lang_es_ES = "insert into `ct_languages` (`id`,`label_data`,`language`, `admin_labels`, `error_labels`, `extra_labels`, `front_error_labels`,`language_status`) values(NULL,'" . $language_front_arr_es_ES . "','es_ES','" . $language_admin_arr_es_ES . "','" . $language_error_arr_es_ES . "','" . $language_extra_arr_es_ES . "','" . $language_form_error_arr_es_ES . "','Y')";
mysqli_query($this->conn, $insert_default_lang_es_ES);

/** French Language **/
$label_data_fr_FR = array (
"none_available"=>urlencode(" Aucun disponible"),
"appointment_zip"=>urlencode(" Rendez-vous Zip"),
"appointment_city"=>urlencode("Ville de rendez-vous"),
"appointment_state"=>urlencode(" État de rendez-vous"),
"appointment_address"=>urlencode("Adresse de rendez-vous"),
"guest_user"=>urlencode("Utilisateur invité"),
"service_usage_methods"=>urlencode("Méthodes d'utilisation du service"),
"paypal"=>urlencode(" Pay Pal"),
"please_check_for_the_below_missing_information"=>urlencode(" Veuillez vérifier les informations manquantes ci-dessous."),
"please_provide_company_details_from_the_admin_panel"=>urlencode(" Veuillez fournir les détails de la société depuis le panneau d'administration."),
"please_add_some_services_methods_units_addons_from_the_admin_panel"=>urlencode("S'il vous plaît ajouter quelques services, méthodes, unités, addons à partir du panneau d'administration."),
"please_add_time_scheduling_from_the_admin_panel"=>urlencode("S'il vous plaît ajouter la planification du temps à partir du panneau d'administration."),
"please_complete_configurations_before_you_created_website_embed_code"=>urlencode("Veuillez compléter les configurations avant de créer le code d'intégration du site Web."),
"cvc"=>urlencode("CVC"),
"mm_yyyy"=>urlencode(" (MM / AAAA)"),
"expiry_date_or_csv"=>urlencode("Date d'expiration ou CSV"),
"street_address_placeholder"=>urlencode("par exemple. Central Ave"),
"zip_code_placeholder"=>urlencode(" gt; 90001"),
"city_placeholder"=>urlencode(" par exemple. Los Angeles"),
"state_placeholder"=>urlencode("par exemple. Californie"),
"payumoney"=>urlencode(" PayUmoney"),
"same_as_above"=>urlencode("Comme ci-dessus"),
"sun"=>urlencode("Soleil"),
"mon"=>urlencode("Lun"),
"tue"=>urlencode("Mar"),
"wed"=>urlencode("Mer"),
"thu"=>urlencode("Jeu"),
"fri"=>urlencode("Ven"),
"sat"=>urlencode("Sam"),
"su"=>urlencode("Votre"),
"mo"=>urlencode("Vous"),
"tu"=>urlencode("Vous"),
"we"=>urlencode("nous"),
"th"=>urlencode("Th"),
"fr"=>urlencode(" Fr"),
"sa"=>urlencode("elle"),
"my_bookings"=>urlencode("Mes réservations"),
"your_postal_code"=>urlencode("Code Postal"),
"where_would_you_like_us_to_provide_service"=>urlencode("Où aimeriez-vous que nous fournissions le service?"),
"choose_service"=>urlencode("Choisissez le service"),
"how_often_would_you_like_us_provide_service"=>urlencode(" À quelle fréquence souhaitez-vous que nous fournissions un service?"),
"when_would_you_like_us_to_come"=>urlencode("Quand voudriez-vous que nous venions?"),
"today"=>urlencode("AUJOURD'HUI"),
"your_personal_details"=>urlencode(" Vos informations personnelles"),
"existing_user"=>urlencode("Utilisateur existant"),
"new_user"=>urlencode("Nouvel utilisateur"),
"preferred_email"=>urlencode("Email préféré"),
"preferred_password"=>urlencode("Mot de passe préféré"),
"your_valid_email_address"=>urlencode("Votre adresse email valide"),
"first_name"=>urlencode("Prénom"),
"your_first_name"=>urlencode(" Ton prénom"),
"last_name"=>urlencode("Nom de famille"),
"your_last_name"=>urlencode("Votre nom de famille"),
"street_address"=>urlencode("Adresse de rue"),
"cleaning_service"=>urlencode(" Service de nettoyage"),
"please_select_method"=>urlencode("Veuillez sélectionner une méthode"),
"zip_code"=>urlencode("Code postal"),
"city"=>urlencode("Ville"),
"state"=>urlencode("Etat"),
"special_requests_notes"=>urlencode(" Demandes spéciales (Notes)"),
"do_you_have_a_vaccum_cleaner"=>urlencode(" Avez-vous un aspirateur?"),
"assign_appointment_to_staff"=>urlencode(" Attribuer un rendez-vous au personnel"),
"delete_member"=>urlencode(" Supprimer un membre?"),
"yes"=>urlencode(" Oui"),
"no"=>urlencode("Non"),
"preferred_payment_method"=>urlencode("Méthode de paiement préférée"),
"please_select_one_payment_method"=>urlencode(" Veuillez sélectionner un mode de paiement"),
"partial_deposit"=>urlencode("Dépôt partiel"),
"remaining_amount"=>urlencode("Montant restant"),
"please_read_our_terms_and_conditions_carefully"=>urlencode(" S'il vous plaît lire attentivement nos termes et conditions"),
"do_you_have_parking"=>urlencode(" Avez-vous un parking?"),
"how_will_we_get_in"=>urlencode(" Comment allons-nous entrer?"),
"i_will_be_at_home"=>urlencode(" Je serai à la maison"),
"please_call_me"=>urlencode("S'il te plait appelle moi"),
"recurring_discounts_apply_from_the_second_cleaning_onward"=>urlencode(" Des réductions récurrentes s'appliquent dès le deuxième nettoyage."),
"please_provide_your_address_and_contact_details"=>urlencode("Veuillez indiquer votre adresse et vos coordonnées"),
"you_are_logged_in_as"=>urlencode("Vous êtes connecté en tant que"),
"the_key_is_with_the_doorman"=>urlencode("La clé est avec le portier"),
"other"=>urlencode("Autre"),
"have_a_promocode"=>urlencode(" Avez vous un code de réduction?"),
"apply"=>urlencode(" Appliquer"),
"applied_promocode"=>urlencode(" Promocode appliqué"),
"complete_booking"=>urlencode(" Réservation complète"),
"cancellation_policy"=>urlencode(" Politique d'annulation"),
"cancellation_policy_header"=>urlencode("En-tête de la politique d'annulation"),
"cancellation_policy_textarea"=>urlencode("Politique d'annulation Textarea"),
"free_cancellation_before_redemption"=>urlencode(" Annulation gratuite avant le remboursement"),
"show_more"=>urlencode("Montre plus"),
"please_select_service"=>urlencode("Veuillez sélectionner un service"),
"choose_your_service_and_property_size"=>urlencode("Choisissez votre service et la taille de la propriété"),
"choose_your_service"=>urlencode("Choisissez votre service"),
"please_configure_first_cleaning_services_and_settings_in_admin_panel"=>urlencode("Veuillez configurer d'abord les services de nettoyage et les paramètres dans le panneau d'administration"),
"i_have_read_and_accepted_the"=>urlencode(" J'ai lu et accepté le"),
"terms_and_condition"=>urlencode(" termes et conditions"),
"and"=>urlencode(" et"),
"updated_labels"=>urlencode("Étiquettes mises à jour"),
"privacy_policy"=>urlencode("Politique de confidentialité"),
"please_fill_all_the_company_informations_and_add_some_services_and_addons"=>urlencode(" Les configurations requises ne sont pas terminées."),
"booking_summary"=>urlencode("Résumé de la réservation"),
"your_email"=>urlencode("Votre Email"),
"enter_email_to_login"=>urlencode(" Entrez un e-mail pour vous connecter"),
"your_password"=>urlencode(" Votre mot de passe"),
"enter_your_password"=>urlencode(" Tapez votre mot de passe"),
"forget_password"=>urlencode(" Mot de passe oublié?"),
"reset_password"=>urlencode("réinitialiser le mot de passe"),
"enter_your_email_and_we_send_you_instructions_on_resetting_your_password"=>urlencode(" Entrez votre email et nous vous enverrons des instructions pour réinitialiser votre mot de passe."),
"registered_email"=>urlencode(" Email enregistré"),
"send_mail"=>urlencode(" Envoyer un mail"),
"back_to_login"=>urlencode("Retour connexion"),
"your"=>urlencode("Votre"),
"your_clean_items"=>urlencode(" Vos articles propres"),
"your_cart_is_empty"=>urlencode(" Votre panier est vide"),
"sub_totaltax"=>urlencode(" Total partiel"),
"sub_total"=>urlencode("Sous total"),
"no_data_available_in_table"=>urlencode(" aucune donnée disponible"),
"total"=>urlencode(" Total"),
"or"=>urlencode("Ou"),
"select_addon_image"=>urlencode(" Sélectionnez l'image addon"),
"inside_fridge"=>urlencode(" Réfrigérateur intérieur"),
"inside_oven"=>urlencode(" Four intérieur"),
"inside_windows"=>urlencode(" Dans Windows"),
"carpet_cleaning"=>urlencode(" Nettoyage de tapis"),
"green_cleaning"=>urlencode(" Nettoyage vert"),
"pets_care"=>urlencode(" Animaux de compagnie"),
"tiles_cleaning"=>urlencode("Nettoyage de carreaux"),
"wall_cleaning"=>urlencode("Nettoyage des murs"),
"laundry"=>urlencode("Blanchisserie"),
"basement_cleaning"=>urlencode(" Nettoyage de sous-sol"),
"basic_price"=>urlencode("Prix ​​de base"),
"max_qty"=>urlencode("Quantité maximum"),
"multiple_qty"=>urlencode("Quantité multiple"),
"base_price"=>urlencode("Prix ​​de base"),
"unit_pricing"=>urlencode(" Prix ​​unitaire"),
"method_is_booked"=>urlencode(" La méthode est réservée"),
"service_addons_price_rules"=>urlencode(" Règles de prix Service Addons"),
"service_unit_front_dropdown_view"=>urlencode("Unité de service Front DropDown View"),
"service_unit_front_block_view"=>urlencode(" Vue avant de l'unité de service"),
"service_unit_front_increase_decrease_view"=>urlencode("Augmentation / diminution de la vue avant de l'unité de service"),
"are_you_sure"=>urlencode(" Êtes-vous sûr"),
"service_unit_price_rules"=>urlencode(" Règles de prix unitaires de service"),
"close"=>urlencode("Fermer"),
"closed"=>urlencode(" Fermé"),
"service_addons"=>urlencode("Addons de service"),
"service_enable"=>urlencode(" Service activé"),
"service_disable"=>urlencode("Désactiver le service"),
"method_enable"=>urlencode(" Méthode Activer"),
"off_time_deleted"=>urlencode("Heure d'arrêt supprimée"),
"error_in_delete_of_off_time"=>urlencode(" Erreur lors de la suppression de l'heure d'arrêt"),
"method_disable"=>urlencode("Méthode Désactiver"),
"extra_services"=>urlencode(" Services supplémentaires"),
"for_initial_cleaning_only_contact_us_to_apply_to_recurrings"=>urlencode(" Pour le nettoyage initial seulement. Contactez-nous pour postuler aux récurrences."),
"number_of"=>urlencode("Nombre de"),
"extra_services_not_available"=>urlencode("Services supplémentaires non disponibles"),
"available"=>urlencode("Disponible"),
"selected"=>urlencode("Choisi"),
"not_available"=>urlencode("Indisponible"),
"none"=>urlencode("Aucun"),
"none_of_time_slot_available_please_check_another_dates"=>urlencode(" Aucun créneau horaire disponible Veuillez cocher d'autres dates"),
"availability_is_not_configured_from_admin_side"=>urlencode(" La disponibilité n'est pas configurée du côté admin"),
"how_many_intensive"=>urlencode(" Combien Intensif"),
"no_intensive"=>urlencode("Non Intensif"),
"frequently_discount"=>urlencode("Foire escompte"),
"coupon_discount"=>urlencode("Coupon Rabais"),
"how_many"=>urlencode("Combien"),
"enter_your_other_option"=>urlencode("Entrez votre autre option"),
"log_out"=>urlencode("Connectez - Out"),
"your_added_off_times"=>urlencode("Vos temps d'arrêt ajoutés"),
"log_in"=>urlencode("s'identifier"),
"custom_css"=>urlencode("CSS personnalisé"),
"success"=>urlencode("Succès"),
"failure"=>urlencode("Échec"),
"you_can_only_use_valid_zipcode"=>urlencode("Vous pouvez uniquement utiliser un code postal valide"),
"minutes"=>urlencode("Minutes"),
"hours"=>urlencode("Heures"),
"days"=>urlencode("Journées"),
"months"=>urlencode("Mois"),
"year"=>urlencode("An"),
"default_url_is"=>urlencode(" L'URL par défaut est"),
"card_payment"=>urlencode("Paiement par carte"),
"pay_at_venue"=>urlencode("Payer sur place"),
"card_details"=>urlencode("Détails de la carte"),
"card_number"=>urlencode("Numéro de carte"),
"invalid_card_number"=>urlencode(" numéro de carte invalide"),
"expiry"=>urlencode("Expiration"),
"button_preview"=>urlencode("Aperçu du bouton"),
"thankyou"=>urlencode("Je vous remercie"),
"thankyou_for_booking_appointment"=>urlencode("Je vous remercie! pour prendre rendez-vous"),
"you_will_be_notified_by_email_with_details_of_appointment"=>urlencode("Vous serez averti par email avec les détails du rendez-vous"),
"please_enter_firstname"=>urlencode(" S'il vous plaît entrer le prénom"),
"please_enter_lastname"=>urlencode("Veuillez entrer le nom de famille"),
"remove_applied_coupon"=>urlencode("Supprimer le coupon appliqué"),
"eg_799_e_dragram_suite_5a"=>urlencode(" par exemple 799 E DRAGRAM SUITE 5A"),
"eg_14114"=>urlencode(" par exemple. 14114"),
"eg_tucson"=>urlencode(" par exemple. TUCSON"),
"eg_az"=>urlencode("par exemple. LA"),
"warning"=>urlencode(" Attention"),
"try_later"=>urlencode(" Essayer plus tard"),
"choose_your"=>urlencode("Choisi ton"),
"configure_now_new"=>urlencode("Configurer maintenant"),
"january"=>urlencode("JANVIER"),
"february"=>urlencode("FÉVRIER"),
"march"=>urlencode("MARS"),
"april"=>urlencode("AVRIL"),
"may"=>urlencode(" MAI"),
"june"=>urlencode("JUIN"),
"july"=>urlencode("JUILLET"),
"august"=>urlencode("AOÛT"),
"september"=>urlencode("SEPTEMBRE"),
"october"=>urlencode("OCTOBRE"),
"november"=>urlencode("NOVEMBRE"),
"december"=>urlencode("DÉCEMBRE"),
"jan"=>urlencode("JAN"),
"feb"=>urlencode("FÉV"),
"mar"=>urlencode("MAR"),
"apr"=>urlencode("AVR"),
"jun"=>urlencode("JUN"),
"jul"=>urlencode("JUIL"),
"aug"=>urlencode("AUG"),
"sep"=>urlencode("SEP"),
"oct"=>urlencode("OCT"),
"nov"=>urlencode("NOV"),
"dec"=>urlencode("DÉC"),
"pay_locally"=>urlencode("Payer localement"),
"please_select_provider"=>urlencode("Veuillez sélectionner un fournisseur"),
);

$admin_labels_fr_FR = array (
"payment_status"=>urlencode("Statut de paiement"),
"staff_booking_status"=>urlencode("Statut de réservation du personnel"),
"accept"=>urlencode("Acceptez"),
"accepted"=>urlencode("Accepté"),
"decline"=>urlencode("Déclin"),
"paid"=>urlencode("Payé"),
"eway"=>urlencode("Eway"),
"half_section"=>urlencode(" Demi-section"),
"option_title"=>urlencode(" Titre de l'option"),
"merchant_ID"=>urlencode("ID du marchand"),
"How_it_works"=>urlencode("Comment ça marche?"),
"Your_currency_should_be_AUD_to_enable_payway_payment_gateway"=>urlencode("Votre devise doit être Dollar Australien pour activer la passerelle de paiement Payway"),
"secure_key"=>urlencode("Clé sécurisée"),
"payway"=>urlencode(" Payway"),
"Your_Google_calendar_id_where_you_need_to_get_alerts_its_normaly_your_Gmail_ID"=>urlencode(" Votre identifiant d'agenda Google, où vous devez recevoir des alertes, c'est normalement votre identifiant Gmail. par exemple. johndoe@example.com"),
"You_can_get_your_client_ID_from_your_Google_Calendar_Console"=>urlencode("Vous pouvez obtenir votre ID client depuis votre console Google Agenda"),
"You_can_get_your_client_secret_from_your_Google_Calendar_Console"=>urlencode(" Vous pouvez obtenir le secret de votre client à partir de votre console Google Agenda"),
"its_your_Cleanto_booking_form_page_url"=>urlencode(" sa page de formulaire de réservation Cleanto urlits votre page de formulaire de réservation Cleanto url"),
"Its_your_Cleanto_Google_Settings_page_url"=>urlencode("C'est l'URL de votre page "),
"Add_Manual_booking"=>urlencode(" Ajouter une réservation manuelle"),
"Google_Calender_Settings"=>urlencode(" Paramètres Google Calender"),
"Add_Appointments_To_Google_Calender"=>urlencode("Ajouter des rendez-vous à Google Agenda"),
"Google_Calender_Id"=>urlencode(" Identifiant Google Calender"),
"Google_Calender_Client_Id"=>urlencode(" Client d'identification Google Calender"),
"Google_Calender_Client_Secret"=>urlencode(" Secret client Google Calender"),
"Google_Calender_Frontend_URL"=>urlencode("URL Frontend de Google Calender"),
"Google_Calender_Admin_URL"=>urlencode(" URL de l'administrateur de Google Calender"),
"Google_Calender_Configuration"=>urlencode(" Configuration de Google Agenda"),
"Two_Way_Sync"=>urlencode("Synchronisation bidirectionnelle"),
"Verify_Account"=>urlencode("vérifier le compte"),
"Select_Calendar"=>urlencode("Sélectionnez le calendrier"),
"Disconnect"=>urlencode("Déconnecter"),
"Calendar_Fisrt_Day"=>urlencode("Calendrier Premier Jour"),
"Calendar_Default_View"=>urlencode(" Calendrier par défaut"),
"Show_company_title"=>urlencode("Afficher le titre de l'entreprise"),
"front_language_flags_list"=>urlencode("Liste des drapeaux des langues avant"),
"Google_Analytics_Code"=>urlencode("Code Google Analytics"),
"Page_Meta_Tag"=>urlencode(" Page / Balise Meta"),
"SEO_Settings"=>urlencode(" Paramètres de référencement"),
"Meta_Description"=>urlencode("Meta Description"),
"SEO"=>urlencode("SEO"),
"og_tag_image"=>urlencode("et prendre l'image"),
"og_tag_url"=>urlencode("et tag URL"),
"og_tag_type"=>urlencode("et le type de tag"),
"og_tag_title"=>urlencode(" et le titre du tag"),
"Quantity"=>urlencode("Quantité"),
"Send_Invoice"=>urlencode("Envoyer une facture"),
"Recurrence"=>urlencode(" Récurrence"),
"Recurrence_booking"=>urlencode(" Récurrence Réservation"),
"Reset_Color"=>urlencode("Réinitialiser la couleur"),
"Loader"=>urlencode("Chargeur"),
"CSS_Loader"=>urlencode("CSS Loader"),
"GIF_Loader"=>urlencode("GIF Loader"),
"Default_Loader"=>urlencode("Chargeur par défaut"),
"for_a"=>urlencode("pour un"),
"show_company_logo"=>urlencode("Afficher le logo de l'entreprise"),
"on"=>urlencode("sur"),
"user_zip_code"=>urlencode(" code postal"),
"delete_this_method"=>urlencode(" Supprimer cette méthode?"),
"authorize_net"=>urlencode("Authorize.Net"),
"staff_details"=>urlencode("DÉTAILS DU PERSONNEL"),
"client_payments"=>urlencode(" Paiements aux clients"),
"staff_payments"=>urlencode("Paiements du personnel"),
"staff_payments_details"=>urlencode(" Détails des paiements du personnel"),
"advance_paid"=>urlencode(" Avance payée"),
"change_calculation_policyy"=>urlencode(" Modifier la politique de calcul"),
"frontend_fonts"=>urlencode(" Polices frontales"),
"favicon_image"=>urlencode("Favicon Image"),
"staff_email_template"=>urlencode("Modèle d'email personnel"),
"staff_details_add_new_and_manage_staff_payments"=>urlencode("Détails du personnel, ajouter du nouveau et gérer les paiements du personnel"),
"add_staff"=>urlencode("Ajouter du personnel"),
"staff_bookings_and_payments"=>urlencode(" Réservations de personnel et paiements"),
"staff_booking_details_and_payment"=>urlencode("Coordonnées du personnel et paiement"),
"select_option_to_show_bookings"=>urlencode("Sélectionnez l'option pour afficher les réservations"),
"select_service"=>urlencode("Sélectionnez le service"),
"staff_name"=>urlencode("Nom du personnel"),
"staff_payment"=>urlencode(" Paiement du personnel"),
"add_payment_to_staff_account"=>urlencode(" Ajouter un paiement au compte personnel"),
"amount_payable"=>urlencode("Montant payable"),
"save_changes"=>urlencode("Sauvegarder les modifications"),
"front_error_labels"=>urlencode("Étiquettes d'erreur avant"),
"stripe"=>urlencode("Bande"),
"checkout_title"=>urlencode("2Checkout"),
"nexmo_sms_gateway"=>urlencode(" Passerelle SMS Nexmo"),
"nexmo_sms_setting"=>urlencode("Paramètre SMS Nexmo"),
"nexmo_api_key"=>urlencode("Clé de l'API Nexmo"),
"nexmo_api_secret"=>urlencode("Secret de l'API Nexmo"),
"nexmo_from"=>urlencode(" Nexmo à partir de"),
"nexmo_status"=>urlencode("Statut Nexmo"),
"nexmo_send_sms_to_client_status"=>urlencode(" Nexmo Envoyer SMS à l'état du client"),
"nexmo_send_sms_to_admin_status"=>urlencode("Nexmo Envoyer Sms à admin statut"),
"nexmo_admin_phone_number"=>urlencode("Numéro de téléphone de l'administrateur Nexmo"),
"save_12_5"=>urlencode("économisez 12.5%"),
"front_tool_tips"=>urlencode("CONSEILS D'OUTIL AVANT"),
"front_tool_tips_lower"=>urlencode("Conseils d'outil avant"),
"tool_tip_my_bookings"=>urlencode("Mes réservations"),
"tool_tip_postal_code"=>urlencode("code postal"),
"tool_tip_services"=>urlencode("Prestations de service"),
"tool_tip_extra_service"=>urlencode(" Service supplémentaire"),
"tool_tip_frequently_discount"=>urlencode("Foire réduction"),
"tool_tip_when_would_you_like_us_to_come"=>urlencode("Quand voudriez-vous que nous venions?"),
"tool_tip_your_personal_details"=>urlencode("Vos informations personnelles"),
"tool_tip_have_a_promocode"=>urlencode("Avez vous un code de réduction"),
"tool_tip_preferred_payment_method"=>urlencode("Méthode de paiement préférée"),
"login_page"=>urlencode("Page de connexion"),
"front_page"=>urlencode("Page de garde"),
"before_e_g_100"=>urlencode("Avant (par exemple 100 $)"),
"after_e_g_100"=>urlencode("Après (par exemple, 100 $)"),
"tax_vat"=>urlencode("Taxe / TVA"),
"wrong_url"=>urlencode("URL incorrecte"),
"choose_file"=>urlencode("Choisir le fichier"),
"frontend_labels"=>urlencode("Étiquettes Frontend"),
"admin_labels"=>urlencode(" Étiquettes Admin"),
"dropdown_design"=>urlencode("DropDown Design"),
"blocks_as_button_design"=>urlencode(" Blocs comme conception de bouton"),
"qty_control_design"=>urlencode("Qty Control Design"),
"dropdowns"=>urlencode("DropDowns"),
"big_images_radio"=>urlencode("Big Images Radio"),
"errors"=>urlencode(" les erreurs"),
"extra_labels"=>urlencode(" Étiquettes supplémentaires"),
"api_password"=>urlencode("Mot de passe API"),
"api_username"=>urlencode(" Nom d'utilisateur de l'API"),
"appearance"=>urlencode("APPARENCE"),
"action"=>urlencode("action"),
"actions"=>urlencode("actes"),
"add_break"=>urlencode(" Ajouter une pause"),
"add_breaks"=>urlencode("Ajouter des pauses"),
"add_cleaning_service"=>urlencode("Ajouter un service de nettoyage"),
"add_method"=>urlencode("Ajouter une méthode"),
"add_new"=>urlencode("Ajouter un nouveau"),
"add_sample_data"=>urlencode("Ajouter des exemples de données"),
"add_unit"=>urlencode("Ajouter une unité"),
"add_your_off_times"=>urlencode("Ajoutez vos temps libres"),
"add_new_off_time"=>urlencode("Ajouter un nouveau temps d'arrêt"),
"add_ons"=>urlencode("Add-ons"),
"addons_bookings"=>urlencode("Réservations AddOns"),
"addon_service_front_view"=>urlencode("Vue avant de l'addon-service"),
"addons"=>urlencode("Addons"),
"service_commission"=>urlencode("Commission de service"),
"commission_total"=>urlencode("Total de la Commission"),
"address"=>urlencode("Adresse"),
"new_appointment_assigned"=>urlencode("Nouvelle nomination assignée"),
"admin_email_notifications"=>urlencode("Notifications par email de l'administrateur"),
"all_payment_gateways"=>urlencode("Toutes les passerelles de paiement"),
"all_services"=>urlencode("Tous les services"),
"allow_multiple_booking_for_same_timeslot"=>urlencode("Autoriser plusieurs réservations pour un même créneau"),
"amount"=>urlencode(" Montant"),
"app_date"=>urlencode("App. Rendez-vous amoureux"),
"appearance_settings"=>urlencode(" Paramètres d'apparence"),
"appointment_completed"=>urlencode(" Rendez-vous complété"),
"appointment_details"=>urlencode("Détails de rendez-vous"),
"appointment_marked_as_no_show"=>urlencode(" Rendez-vous marqué comme non-présentation"),
"mark_as_no_show"=>urlencode(" Marquer comme non Afficher"),
"appointment_reminder_buffer"=>urlencode(" Tampon de rappel de rendez-vous"),
"appointment_auto_confirm"=>urlencode("Rendez-vous auto confirmer"),
"appointments"=>urlencode(" Rendez-vous"),
"admin_area_color_scheme"=>urlencode("Schéma de couleur de la zone d'administration"),
"thankyou_page_url"=>urlencode(" Merci URL de la page"),
"addon_title"=>urlencode("Titre de l'addon"),
"availabilty"=>urlencode("Disponibilité"),
"background_color"=>urlencode("Couleur de fond"),
"behaviour_on_click_of_button"=>urlencode("Comportement au clic du bouton"),
"book_now"=>urlencode(" Reserve maintenant"),
"booking_date_and_time"=>urlencode(" Date et heure de réservation"),
"booking_details"=>urlencode("Les détails de réservation"),
"booking_information"=>urlencode("Informations de réservation"),
"booking_serve_date"=>urlencode("Réservation Servir Date"),
"booking_status"=>urlencode("Statut de réservation"),
"booking_notifications"=>urlencode("Notifications de réservation"),
"bookings"=>urlencode("Réservations"),
"button_position"=>urlencode("Position du bouton"),
"button_text"=>urlencode("Texte du bouton"),
"company"=>urlencode("COMPAGNIE"),
"cannot_cancel_now"=>urlencode("Impossible d'annuler maintenant"),
"cannot_reschedule_now"=>urlencode(" Impossible de reporter à présent"),
"cancel"=>urlencode("Annuler"),
"cancellation_buffer_time"=>urlencode("Heure tampon d'annulation"),
"cancelled_by_client"=>urlencode("Annulé par le client"),
"cancelled_by_service_provider"=>urlencode(" Annulé par le fournisseur de services"),
"change_password"=>urlencode("Changer le mot de passe"),
"cleaning_service"=>urlencode("Service de nettoyage"),
"client"=>urlencode("Client"),
"client_email_notifications"=>urlencode("Notifications par e-mail client"),
"client_name"=>urlencode("Nom du client"),
"color_scheme"=>urlencode("Schéma de couleur"),
"color_tag"=>urlencode("Couleur"),
"company_address"=>urlencode("Adresse"),
"company_email"=>urlencode(" Email"),
"company_logo"=>urlencode(" Logo d'entreprise"),
"company_name"=>urlencode("Nom de l'entreprise"),
"company_settings"=>urlencode("Paramètres d'informations sur l'entreprise"),
"companyname"=>urlencode(" Nom de la compagnie"),
"company_info_settings"=>urlencode("Paramètres de l'entreprise"),
"completed"=>urlencode(" Terminé"),
"confirm"=>urlencode(" Confirmer"),
"confirmed"=>urlencode("Confirmé"),
"contact_status"=>urlencode("Statut du contact"),
"country"=>urlencode("Pays"),
"country_code_phone"=>urlencode("Code de pays (téléphone)"),
"coupon"=>urlencode("Coupon"),
"coupon_code"=>urlencode("Code de coupon"),
"coupon_limit"=>urlencode("Limite du coupon"),
"coupon_type"=>urlencode("Type de coupon"),
"coupon_used"=>urlencode("Coupon utilisé"),
"coupon_value"=>urlencode(" Valeur du coupon"),
"create_addon_service"=>urlencode(" Créer un service d'ajout"),
"crop_and_save"=>urlencode(" Culture et économie"),
"currency"=>urlencode(" Devise"),
"currency_symbol_position"=>urlencode(" Position du symbole monétaire"),
"customer"=>urlencode(" Client"),
"customer_information"=>urlencode(" Informations client"),
"customers"=>urlencode("Les clients"),
"date_and_time"=>urlencode(" Date et heure"),
"date_picker_date_format"=>urlencode("Date-Sélecteur Format de date"),
"default_design_for_addons"=>urlencode("Conception par défaut pour les addons"),
"default_design_for_methods_with_multiple_units"=>urlencode(" Conception par défaut pour les méthodes avec plusieurs unités"),
"default_design_for_services"=>urlencode(" Conception par défaut pour les services"),
"default_setting"=>urlencode(" Paramètres par défaut"),
"delete"=>urlencode(" Effacer"),
"description"=>urlencode("La description"),
"discount"=>urlencode("Remise"),
"download_invoice"=>urlencode(" Télécharger la facture"),
"email_notification"=>urlencode("NOTIFICATION PAR EMAIL"),
"email"=>urlencode("Email"),
"email_settings"=>urlencode(" Paramètres de messagerie"),
"embed_code"=>urlencode(" Code intégré"),
"enter_your_email_and_we_will_send_you_instructions_on_resetting_your_password"=>urlencode(" Entrez votre email et nous vous enverrons des instructions sur la réinitialisation de votre mot de passe."),
"expiry_date"=>urlencode("Date d'expiration"),
"export"=>urlencode("Exportation"),
"export_your_details"=>urlencode("Exporter vos détails"),
"frequently_discount_setting_tabs"=>urlencode(" FRÉQUEMMENT DISCOUNT"),
"frequently_discount_header"=>urlencode(" Foire escompte"),
"field_is_required"=>urlencode("Champ requis"),
"file_size"=>urlencode("Taille du fichier"),
"flat_fee"=>urlencode(" Frais fixes"),
"flat"=>urlencode("Appartement"),
"freq_discount"=>urlencode("Freq-Discount"),
"frequently_discount_label"=>urlencode("Étiquette fréquemment remise"),
"frequently_discount_type"=>urlencode("Foire Type de remise"),
"frequently_discount_value"=>urlencode("Facteur de réduction fréquent"),
"front_service_box_view"=>urlencode(" Vue de la boîte de service avant"),
"front_service_dropdown_view"=>urlencode("Vue de la liste déroulante Service avant"),
"front_view_options"=>urlencode(" Options de vue avant"),
"full_name"=>urlencode(" Nom complet"),
"general"=>urlencode("GÉNÉRAL"),
"general_settings"=>urlencode(" réglages généraux"),
"get_embed_code_to_show_booking_widget_on_your_website"=>urlencode(" Obtenez le code intégré pour afficher le widget de réservation sur votre site Web"),
"get_the_embeded_code"=>urlencode("Obtenir le code incorporé"),
"guest_customers"=>urlencode(" Clients invités"),
"guest_user_checkout"=>urlencode(" Vérification de l'utilisateur invité"),
"hide_faded_already_booked_time_slots"=>urlencode(" Cacher les créneaux horaires déjà réservés"),
"hostname"=>urlencode(" Nom d'hôte"),
"labels"=>urlencode("ÉTIQUETTES"),
"legends"=>urlencode("Légendes"),
"login"=>urlencode("S'identifier"),
"maximum_advance_booking_time"=>urlencode("Temps de réservation anticipé maximum"),
"method"=>urlencode(" Méthode"),
"method_name"=>urlencode("Nom de la méthode"),
"method_title"=>urlencode(" Titre de la méthode"),
"method_unit_quantity"=>urlencode("Méthode Unité Quantité"),
"method_unit_quantity_rate"=>urlencode("Méthode Unité Quantité Taux"),
"method_unit_title"=>urlencode("Titre de l'unité de méthode"),
"method_units_front_view"=>urlencode("Unités de méthode Vue de face"),
"methods"=>urlencode(" Méthodes"),
"methods_booking"=>urlencode(" Méthodes de réservation"),
"methods_bookings"=>urlencode("Réservations de méthodes"),
"minimum_advance_booking_time"=>urlencode("Temps de réservation minimum à l'avance"),
"more"=>urlencode(" Plus"),
"more_details"=>urlencode(" Plus de détails"),
"my_appointments"=>urlencode("Mes rendez-vous"),
"name"=>urlencode("prénom"),
"net_total"=>urlencode("Total net"),
"new_password"=>urlencode(" nouveau mot de passe"),
"notes"=>urlencode("Remarques"),
"off_days"=>urlencode("Jours de congé"),
"off_time"=>urlencode("Délai dépassé"),
"old_password"=>urlencode(" ancien mot de passe"),
"online_booking_button_style"=>urlencode("Style de bouton de réservation en ligne"),
"open_widget_in_a_new_page"=>urlencode("Ouvrir un widget dans une nouvelle page"),
"order"=>urlencode(" Commande"),
"order_date"=>urlencode(" Date de commande"),
"order_time"=>urlencode("Temps de commande"),
"payments_setting"=>urlencode("PAIEMENT"),
"promocode"=>urlencode(" CODE PROMO"),
"promocode_header"=>urlencode(" Code promo"),
"padding_time_before"=>urlencode("Rembourrage Temps avant"),
"parking"=>urlencode(" Parking"),
"partial_amount"=>urlencode("Montant partiel"),
"partial_deposit"=>urlencode("Dépôt partiel"),
"partial_deposit_amount"=>urlencode(" Montant du dépôt partiel"),
"partial_deposit_message"=>urlencode(" Message de dépôt partiel"),
"password"=>urlencode(" Mot de passe"),
"payment"=>urlencode(" Paiement"),
"payment_date"=>urlencode("Date de paiement"),
"payment_gateways"=>urlencode(" Passerelles de paiement"),
"payment_method"=>urlencode("Mode de paiement"),
"payments"=>urlencode("Paiements"),
"payments_history_details"=>urlencode(" Détails de l'historique des paiements"),
"paypal_express_checkout"=>urlencode("PayPal Express Checkout"),
"paypal_guest_payment"=>urlencode("Paiement Paypal invité"),
"pending"=>urlencode(" en attendant"),
"percentage"=>urlencode("Pourcentage"),
"personal_information"=>urlencode("Informations personnelles"),
"phone"=>urlencode("Téléphone"),
"please_copy_above_code_and_paste_in_your_website"=>urlencode(" Veuillez copier le code ci-dessus et collez-le dans votre site Web."),
"please_enable_payment_gateway"=>urlencode("Veuillez activer la passerelle de paiement"),
"please_set_below_values"=>urlencode(" Veuillez définir les valeurs ci-dessous"),
"port"=>urlencode("Port"),
"postal_codes"=>urlencode(" Postal Codes"),
"price"=>urlencode(" Prix"),
"price_calculation_method"=>urlencode(" Méthode de calcul du prix"),
"price_format_decimal_places"=>urlencode("Format des prix"),
"pricing"=>urlencode("Tarification"),
"primary_color"=>urlencode("Couleur primaire"),
"privacy_policy_link"=>urlencode("Politique de confidentialité Link"),
"profile"=>urlencode(" Profil"),
"promocodes"=>urlencode("codes promo"),
"promocodes_list"=>urlencode(" Liste des promocodes"),
"registered_customers"=>urlencode("Clients enregistrés"),
"registered_customers_bookings"=>urlencode("Réservations de clients enregistrés"),
"reject"=>urlencode("Rejeter"),
"rejected"=>urlencode("Rejeté"),
"remember_me"=>urlencode("Souviens-toi de moi"),
"remove_sample_data"=>urlencode("Supprimer des exemples de données"),
"reschedule"=>urlencode("Reporter"),
"reset"=>urlencode("Réinitialiser"),
"reset_password"=>urlencode("réinitialiser le mot de passe"),
"reshedule_buffer_time"=>urlencode("Reshedule Buffer Time"),
"retype_new_password"=>urlencode(" Re-taper le nouveau mot de passe"),
"right_side_description"=>urlencode(" Page de réservation Description de Rightside"),
"save"=>urlencode(" sauvegarder"),
"save_availability"=>urlencode("Enregistrer la disponibilité"),
"save_setting"=>urlencode("Sauvegarder les paramètres"),
"save_labels_setting"=>urlencode("Enregistrer le paramètre des étiquettes"),
"schedule"=>urlencode("Programme"),
"schedule_type"=>urlencode("Type de programme"),
"secondary_color"=>urlencode("Couleur secondaire"),
"select_language_for_update"=>urlencode(" Sélectionnez la langue pour la mise à jour"),
"select_language_to_change_label"=>urlencode(" Sélectionnez la langue pour changer d'étiquette"),
"select_language_to_display"=>urlencode(" La langue"),
"display_sub_headers_below_headers"=>urlencode("Sous-titres sur la page Réservation"),
"select_payment_option_export_details"=>urlencode(" Sélectionnez l'option de paiement détails d'exportation"),
"send_mail"=>urlencode("Envoyer un mail"),
"sender_email_address_cleanto_admin_email"=>urlencode("Email de l'expéditeur"),
"sender_name"=>urlencode(" Nom de l'expéditeur"),
"service"=>urlencode("Un service"),
"service_add_ons_front_block_view"=>urlencode("Modules de service Front Block View"),
"service_add_ons_front_increase_decrease_view"=>urlencode("Modules de service Augmenter / diminuer la vue avant"),
"service_description"=>urlencode(" Description du service"),
"service_front_view"=>urlencode("Service Front View"),
"service_image"=>urlencode(" Image de service"),
"service_methods"=>urlencode(" Méthodes de service"),
"service_padding_time_after"=>urlencode("Rembourrage de service après"),
"padding_time_after"=>urlencode("Rembourrage après"),
"service_padding_time_before"=>urlencode("Rembourrage de service Temps avant"),
"service_quantity"=>urlencode("Quantité de service"),
"service_rate"=>urlencode("Taux de service"),
"service_title"=>urlencode(" Titre du service"),
"serviceaddons_name"=>urlencode("Nom de ServiceAddOns"),
"services"=>urlencode("Prestations de service"),
"services_information"=>urlencode("Informations sur les services"),
"set_email_reminder_buffer"=>urlencode("Définir un tampon de rappel d'e-mail"),
"set_language"=>urlencode("Définir la langue"),
"settings"=>urlencode("Paramètres"),
"show_all_bookings"=>urlencode("Afficher toutes les réservations"),
"show_button_on_given_embeded_position"=>urlencode("Afficher le bouton sur une position incorporée donnée"),
"show_coupons_input_on_checkout"=>urlencode(" Afficher les entrées de coupons à la caisse"),
"show_on_a_button_click"=>urlencode("Afficher sur un bouton cliquer"),
"show_on_page_load"=>urlencode("Afficher sur le chargement de la page"),
"signature"=>urlencode(" Signature"),
"sorry_wrong_email_or_password"=>urlencode(" Désolé mauvais courriel ou mot de passe"),
"start_date"=>urlencode("Date de début"),
"status"=>urlencode(" Statut"),
"submit"=>urlencode("Soumettre"),
"staff_email_notification"=>urlencode(" Notification par courriel du personnel"),
"tax"=>urlencode("Impôt"),
"test_mode"=>urlencode("Mode d'essai"),
"text_color"=>urlencode("Couleur du texte"),
"text_color_on_bg"=>urlencode("Couleur du texte sur bg"),
"terms_and_condition_link"=>urlencode("Conditions générales"),
"this_week_breaks"=>urlencode(" Cette semaine se brise"),
"this_week_time_scheduling"=>urlencode("Cette semaine planification du temps"),
"time_format"=>urlencode(" Format de l'heure"),
"time_interval"=>urlencode(" Intervalle de temps"),
"timezone"=>urlencode(" Fuseau horaire"),
"units"=>urlencode("Unités"),
"unit_name"=>urlencode("Nom de l'unité"),
"units_of_methods"=>urlencode(" Unités de méthodes"),
"update"=>urlencode("Mettre à jour"),
"update_appointment"=>urlencode(" Mise à jour de rendez-vous"),
"update_promocode"=>urlencode(" Mettre à jour Promocode"),
"username"=>urlencode(" Nom d'utilisateur"),
"vaccum_cleaner"=>urlencode(" Aspirateur"),
"view_slots_by"=>urlencode("Voir les machines à sous par?"),
"week"=>urlencode(" La semaine"),
"week_breaks"=>urlencode("Séjours de la semaine"),
"week_time_scheduling"=>urlencode(" Planification de la semaine"),
"widget_loading_style"=>urlencode("Widget Chargement style"),
"zip"=>urlencode("Zip"),
"logout"=>urlencode("Connectez - Out"),
"to"=>urlencode(" à"),
"add_new_promocode"=>urlencode(" Ajouter un nouveau code promo"),
"create"=>urlencode(" Créer"),
"end_date"=>urlencode(" Date de fin"),
"end_time"=>urlencode(" Heure de fin"),
"labels_settings"=>urlencode(" Paramètres des étiquettes"),
"limit"=>urlencode(" Limite"),
"max_limit"=>urlencode("Limite maximum"),
"start_time"=>urlencode(" Heure de début"),
"value"=>urlencode(" Valeur"),
"active"=>urlencode(" actif"),
"appointment_reject_reason"=>urlencode(" Rendez-vous de refus"),
"search"=>urlencode(" Chercher"),
"custom_thankyou_page_url"=>urlencode(" URL de page personnalisée Thankyou"),
"price_per_unit"=>urlencode(" Prix ​​par unité"),
"confirm_appointment"=>urlencode(" Confirmer le rendez-vous"),
"reject_reason"=>urlencode(" Rejeter la raison"),
"delete_this_appointment"=>urlencode(" Supprimer ce rendez-vous"),
"close_notifications"=>urlencode(" Fermer les notifications"),
"booking_cancel_reason"=>urlencode("Réservation Annuler la raison"),
"service_color_badge"=>urlencode(" Badge de couleur de service"),
"manage_price_calculation_methods"=>urlencode(" Gérer les méthodes de calcul des prix"),
"manage_addons_of_this_service"=>urlencode(" Gérer les addons de ce service"),
"service_is_booked"=>urlencode(" Le service est réservé"),
"delete_this_service"=>urlencode("Supprimer ce service"),
"delete_service"=>urlencode("Supprimer le service"),
"remove_image"=>urlencode(" Supprimer l'image"),
"remove_service_image"=>urlencode(" Supprimer l'image du service"),
"company_name_is_used_for_invoice_purpose"=>urlencode(" Le nom de l'entreprise est utilisé à des fins de facturation"),
"remove_company_logo"=>urlencode("Supprimer le logo de l'entreprise"),
"time_interval_is_helpful_to_show_time_difference_between_availability_time_slots"=>urlencode(" L'intervalle de temps est utile pour afficher le décalage horaire entre les créneaux horaires de disponibilité"),
"minimum_advance_booking_time_restrict_client_to_book_last_minute_booking_so_that_you_should_have_sufficient_time_before_appointment"=>urlencode(" Le temps minimum de réservation à l'avance limite le client à la réservation de dernière minute, de sorte que vous devriez avoir suffisamment de temps avant le rendez-vous"),
"cancellation_buffer_helps_service_providers_to_avoid_last_minute_cancellation_by_their_clients"=>urlencode(" Un tampon d'annulation aide les fournisseurs de services à éviter l'annulation de dernière minute par leurs clients"),
"partial_payment_option_will_help_you_to_charge_partial_payment_of_total_amount_from_client_and_remaining_you_can_collect_locally"=>urlencode(" Option de paiement partiel vous aidera à facturer le paiement partiel du montant total du client et restant vous pouvez collecter localement"),
"allow_multiple_appointment_booking_at_same_time_slot_will_allow_you_to_show_availability_time_slot_even_you_have_booking_already_for_that_time"=>urlencode("Autoriser la réservation de plusieurs rendez-vous à la même heure, vous permettra d'afficher la plage de disponibilité, même si vous avez déjà réservé pour cette période"),
"with_Enable_of_this_feature_Appointment_request_from_clients_will_be_auto_confirmed"=>urlencode("Avec Activer cette fonctionnalité, la demande de rendez-vous des clients sera confirmée automatiquement"),
"write_html_code_for_the_right_side_panel"=>urlencode("Ecrire du code HTML pour le panneau de droite"),
"do_you_want_to_show_subheaders_below_the_headers"=>urlencode(" Voulez-vous afficher les sous-en-têtes sous les en-têtes"),
"you_can_show_hide_coupon_input_on_checkout_form"=>urlencode(" Vous pouvez afficher / masquer les entrées de coupon sur le formulaire de paiement"),
"with_this_feature_you_can_allow_a_visitor_to_book_appointment_without_registration"=>urlencode(" Avec cette fonctionnalité, vous pouvez autoriser un visiteur à réserver un rendez-vous sans inscription"),
"paypal_api_username_can_get_easily_from_developer_paypal_com_account"=>urlencode(" Le nom d'utilisateur de l'API Paypal peut être facilement téléchargé depuis le compte developer.paypal.com"),
"paypal_api_password_can_get_easily_from_developer_paypal_com_account"=>urlencode("Le mot de passe de l'API Paypal peut être facilement obtenu à partir du compte developer.paypal.com"),
"paypal_api_signature_can_get_easily_from_developer_paypal_com_account"=>urlencode(" La signature de l'API Paypal peut être facilement obtenue à partir du compte developer.paypal.com"),
"let_user_pay_through_credit_card_without_having_paypal_account"=>urlencode(" Laisser l'utilisateur payer par carte de crédit sans avoir de compte Paypal"),
"you_can_enable_paypal_test_mode_for_sandbox_account_testing"=>urlencode("Vous pouvez activer le mode de test Paypal pour les tests de compte sandbox"),
"you_can_enable_authorize_net_test_mode_for_sandbox_account_testing"=>urlencode(" Vous pouvez activer le mode de test Authorize.Net pour les tests de compte sandbox"),
"edit_coupon_code"=>urlencode(" Modifier le code promo"),
"delete_promocode"=>urlencode("Supprimer Promocode?"),
"coupon_code_will_work_for_such_limit"=>urlencode(" Le code de coupon fonctionnera pour une telle limite"),
"coupon_code_will_work_for_such_date"=>urlencode(" Le code du coupon fonctionnera pour cette date"),
"coupon_value_would_be_consider_as_percentage_in_percentage_mode_and_in_flat_mode_it_will_be_consider_as_amount_no_need_to_add_percentage_sign_it_will_auto_added"=>urlencode(" La valeur du coupon sera considérée comme un pourcentage en mode pourcentage et en mode plat, elle sera considérée comme un montant. Il n'est pas nécessaire d'ajouter un signe de pourcentage pour qu'il soit automatiquement ajouté."),
"unit_is_booked"=>urlencode("L'unité est réservée"),
"delete_this_service_unit"=>urlencode("Supprimer cette unité de service?"),
"delete_service_unit"=>urlencode("Supprimer l'unité de service"),
"manage_unit_price"=>urlencode(" Gérer le prix unitaire"),
"extra_service_title"=>urlencode("Titre de service supplémentaire"),
"addon_is_booked"=>urlencode("Addon est réservé"),
"delete_this_addon_service"=>urlencode("Supprimer ce service d'addon?"),
"choose_your_addon_image"=>urlencode("Choisissez votre image addon"),
"addon_image"=>urlencode(" Image d'addon"),
"administrator_email"=>urlencode("Email de l'administrateur"),
"admin_profile_address"=>urlencode(" Adresse"),
"default_country_code"=>urlencode("Code postal"),
"cancellation_policy"=>urlencode(" Politique d'annulation"),
"transaction_id"=>urlencode(" identifiant de transaction"),
"sms_reminder"=>urlencode("SMS de rappel"),
"save_sms_settings"=>urlencode(" Enregistrer les paramètres SMS"),
"sms_service"=>urlencode("Service SMS"),
"it_will_send_sms_to_service_provider_and_client_for_appointment_booking"=>urlencode(" Il enverra des sms au fournisseur de service et au client pour la réservation de rendez-vous"),
"twilio_account_settings"=>urlencode(" Twilio Paramètres du compte"),
"plivo_account_settings"=>urlencode("Paramètres du compte Plivo"),
"account_sid"=>urlencode("Compte SID"),
"auth_token"=>urlencode(" Jeton d'authentification"),
"twilio_sender_number"=>urlencode(" Numéro d'expéditeur Twilio"),
"plivo_sender_number"=>urlencode("Numéro d'expéditeur de Plivo"),
"twilio_sms_settings"=>urlencode("Twilio Paramètres SMS"),
"plivo_sms_settings"=>urlencode(" Paramètres SMS Plivo"),
"twilio_sms_gateway"=>urlencode("Twilio SMS Gateway"),
"plivo_sms_gateway"=>urlencode(" Passerelle SMS Plivo"),
"send_sms_to_client"=>urlencode("Envoyer un SMS au client"),
"send_sms_to_admin"=>urlencode("Envoyer un SMS à l'administrateur"),
"admin_phone_number"=>urlencode("Numéro de téléphone de l'administrateur"),
"available_from_within_your_twilio_account"=>urlencode(" Disponible depuis votre compte Twilio."),
"must_be_a_valid_number_associated_with_your_twilio_account"=>urlencode(" Doit être un nombre valide associé à votre compte Twilio."),
"enable_or_disable_send_sms_to_client_for_appointment_booking_info"=>urlencode(" Activer ou désactiver, Envoyer un SMS au client pour les informations de réservation de rendez-vous."),
"enable_or_disable_send_sms_to_admin_for_appointment_booking_info"=>urlencode("Activer ou désactiver, Envoyer un SMS à l'administrateur pour les informations de réservation de rendez-vous."),
"updated_sms_settings"=>urlencode("Paramètres SMS mis à jour"),
"parking_availability_frontend_option_display_status"=>urlencode(" Parking"),
"vaccum_cleaner_frontend_option_display_status"=>urlencode(" Aspirateur"),
"o_n"=>urlencode(" Sur"),
"off"=>urlencode("De"),
"enable"=>urlencode(" Activer"),
"disable"=>urlencode("Désactiver"),
"monthly"=>urlencode("Mensuel"),
"weekly"=>urlencode(" Hebdomadaire"),
"email_template"=>urlencode(" MODÈLE DE COURRIEL"),
"sms_notification"=>urlencode(" NOTIFICATION SMS"),
"sms_template"=>urlencode("SMS MODÈLE"),
"email_template_settings"=>urlencode("Paramètres du modèle de courrier électronique"),
"client_email_templates"=>urlencode("Modèle d'email client"),
"client_sms_templates"=>urlencode(" Modèle de SMS client"),
"admin_email_template"=>urlencode("Modèle d'email de l'administrateur"),
"admin_sms_template"=>urlencode("Modèle de SMS Admin"),
"tags"=>urlencode(" Mots clés"),
"booking_date"=>urlencode("date de réservation"),
"service_name"=>urlencode(" Nom du service"),
"business_logo"=>urlencode(" business_logo"),
"business_logo_alt"=>urlencode(" business_logo_alt"),
"admin_name"=>urlencode(" admin_name"),
"methodname"=>urlencode("nom_méthode"),
"firstname"=>urlencode(" Prénom"),
"lastname"=>urlencode(" nom de famille"),
"client_email"=>urlencode("client_email"),
"vaccum_cleaner_status"=>urlencode(" vaccum_cleaner_status"),
"parking_status"=>urlencode("parking_status"),
"app_remain_time"=>urlencode("app_remain_time"),
"reject_status"=>urlencode("reject_status"),
"save_template"=>urlencode("Enregistrer le modèle"),
"default_template"=>urlencode("Modèle par défaut"),
"sms_template_settings"=>urlencode(" Paramètres du modèle SMS"),
"secret_key"=>urlencode(" Clef secrète"),
"publishable_key"=>urlencode(" Clé publiable"),
"payment_form"=>urlencode("Formulaire de paiement"),
"api_login_id"=>urlencode("ID de connexion API"),
"transaction_key"=>urlencode(" Clé de transaction"),
"sandbox_mode"=>urlencode("Mode bac à sable"),
"available_from_within_your_plivo_account"=>urlencode(" Disponible depuis votre compte Plivo."),
"must_be_a_valid_number_associated_with_your_plivo_account"=>urlencode("Doit être un nombre valide associé à votre compte Plivo."),
"whats_new"=>urlencode(" Quoi de neuf?"),
"company_phone"=>urlencode("Téléphone"),
"company__name"=>urlencode(" Nom de la compagnie"),
"booking_time"=>urlencode("booking_time"),
"company__email"=>urlencode("company_email"),
"company__address"=>urlencode(" Adresse de la société"),
"company__zip"=>urlencode(" company_zip"),
"company__phone"=>urlencode("company_phone"),
"company__state"=>urlencode(" company_state"),
"company__country"=>urlencode(" company_country"),
"company__city"=>urlencode("company_city"),
"page_title"=>urlencode(" Titre de la page"),
"client__zip"=>urlencode("client_zip"),
"client__state"=>urlencode(" État client"),
"client__city"=>urlencode("client_city"),
"client__address"=>urlencode(" adresse_client"),
"client__phone"=>urlencode(" client_phone"),
"company_logo_is_used_for_invoice_purpose"=>urlencode(" Le logo de l'entreprise s'utilise dans l'email et la page de réservation"),
"private_key"=>urlencode(" Clé privée"),
"seller_id"=>urlencode("Identifiant du vendeur"),
"postal_codes_ed"=>urlencode("Vous pouvez activer ou désactiver les codes postaux ou les codes postaux selon les exigences de votre pays, car certains pays comme les EAU n'ont pas de code postal."),
"postal_codes_info"=>urlencode(" Vous pouvez mentionner les codes postaux de deux façons: # 1. Vous pouvez mentionner les codes postaux complets pour le match comme K1A232, L2A334, C3A4C4. # 2. Vous pouvez utiliser des codes postaux partiels pour les entrées de correspondance de caractères génériques, par exemple. K1A, L2A, C3, le système correspondra à ces lettres de début de code postal sur le devant et il vous évitera d'écrire autant de codes postaux."),
"first"=>urlencode(" Premier"),
"second"=>urlencode(" Seconde"),
"third"=>urlencode("Troisième"),
"fourth"=>urlencode("Quatrième"),
"fifth"=>urlencode(" Cinquième"),
"first_week"=>urlencode(" Première semaine"),
"second_week"=>urlencode(" Deuxième semaine"),
"third_week"=>urlencode(" Troisième semaine"),
"fourth_week"=>urlencode(" Quatrième semaine"),
"fifth_week"=>urlencode(" Cinquième semaine"),
"this_week"=>urlencode(" Cette semaine"),
"monday"=>urlencode("Lundi"),
"tuesday"=>urlencode(" Mardi"),
"wednesday"=>urlencode(" Mercredi"),
"thursday"=>urlencode(" Jeudi"),
"friday"=>urlencode(" Vendredi"),
"saturday"=>urlencode("samedi"),
"sunday"=>urlencode("dimanche"),
"appointment_request"=>urlencode(" Demande de rendez-vous"),
"appointment_approved"=>urlencode(" Rendez-vous approuvé"),
"appointment_rejected"=>urlencode(" Rendez-vous rejeté"),
"appointment_cancelled_by_you"=>urlencode("Rendez-vous annulé par vous"),
"appointment_rescheduled_by_you"=>urlencode(" Rendez-vous reporté par vous"),
"client_appointment_reminder"=>urlencode("Rappel de rendez-vous client"),
"new_appointment_request_requires_approval"=>urlencode(" Nouvelle demande de rendez-vous nécessite une approbation"),
"appointment_cancelled_by_customer"=>urlencode(" Rendez-vous annulé par le client"),
"appointment_rescheduled_by_customer"=>urlencode(" Rendez-vous reprogrammé par le client"),
"admin_appointment_reminder"=>urlencode(" Rappel de rendez-vous d'administration"),
"off_days_added_successfully"=>urlencode("Les jours de congé ajoutés avec succès"),
"off_days_deleted_successfully"=>urlencode(" Les jours de congé supprimés avec succès"),
"sorry_not_available"=>urlencode(" Désolé non disponible"),
"success"=>urlencode("Succès"),
"failed"=>urlencode(" Échoué"),
"once"=>urlencode("Une fois que"),
"Bi_Monthly"=>urlencode("Bimensuel"),
"Fortnightly"=>urlencode(" Bimensuel"),
"Recurrence_Type"=>urlencode(" Type de récurrence"),
"bi_weekly"=>urlencode(" Bihebdomadaire"),
"Daily"=>urlencode(" du quotidien"),
"guest_customers_bookings"=>urlencode(" Réservations de clients"),
"existing_and_new_user_checkout"=>urlencode("Vérification de l'utilisateur existant et nouveau"),
"it_will_allow_option_for_user_to_get_booking_with_new_user_or_existing_user"=>urlencode(" Il permettra à l'utilisateur d'obtenir une réservation avec un nouvel utilisateur ou un utilisateur existant"),
"0_1"=>urlencode("01"),
"1_1"=>urlencode("1.1"),
"1_2"=>urlencode("1.2"),
"0_2"=>urlencode("02"),
"free"=>urlencode("Gratuit"),
"show_company_address_in_header"=>urlencode("Afficher l'adresse de l'entreprise en en-tête"),
"calendar_week"=>urlencode(" La semaine"),
"calendar_month"=>urlencode("Mois"),
"calendar_day"=>urlencode("journée"),
"calendar_today"=>urlencode(" Aujourd'hui"),
"restore_default"=>urlencode(" Restaurer par défaut"),
"scrollable_cart"=>urlencode("Chariot défilable"),
"merchant_key"=>urlencode(" Clé du marchand"),
"salt_key"=>urlencode("Clé de sel"),
"textlocal_sms_gateway"=>urlencode(" Passerelle SMS Textlocal"),
"textlocal_sms_settings"=>urlencode("Paramètres SMS Textlocal"),
"textlocal_account_settings"=>urlencode(" Paramètres du compte Textlocal"),
"account_username"=>urlencode(" Nom d'utilisateur du compte"),
"account_hash_id"=>urlencode(" ID de hachage du compte"),
"email_id_registered_with_you_textlocal"=>urlencode(" Fournissez votre email enregistré avec textlocal"),
"hash_id_provided_by_textlocal"=>urlencode(" Hash id fourni par textlocal"),
"bank_transfer"=>urlencode(" Virement"),
"bank_name"=>urlencode(" Nom de banque"),
"account_name"=>urlencode(" Nom du compte"),
"account_number"=>urlencode(" Numéro de compte"),
"branch_code"=>urlencode(" Code de succursale"),
"ifsc_code"=>urlencode("Code IFSC"),
"bank_description"=>urlencode(" Description de la banque"),
"your_cart_items"=>urlencode("Les articles de votre panier"),
"show_how_will_we_get_in"=>urlencode("Show Comment allons-nous entrer"),
"show_description"=>urlencode(" Montrer la description"),
"bank_details"=>urlencode("Coordonnées bancaires"),
"ok_remove_sample_data"=>urlencode("D'accord"),
"book_appointment"=>urlencode("Rendez-vous au livre"),
"remove_sample_data_message"=>urlencode(" Vous essayez de supprimer des données d'échantillon. Si vous supprimez des données d'échantillon, votre réservation liée à des exemples de services sera définitivement supprimée. Pour continuer, cliquez sur 'OK'"),
"recommended_image_type_jpg_jpeg_png_gif"=>urlencode("(Type d'image recommandée jpg, jpeg, png, gif)"),
"authetication"=>urlencode("Authentification"),
"encryption_type"=>urlencode(" Type de chiffrement"),
"plain"=>urlencode(" Plaine"),
"true"=>urlencode(" Vrai"),
"false"=>urlencode(" Faux"),
"change_calculation_policy"=>urlencode("Modifier le calcul"),
"multiply"=>urlencode("Multiplier"),
"equal"=>urlencode(" Égal"),
"warning"=>urlencode(" Attention!"),
"field_name"=>urlencode(" Nom de domaine"),
"enable_disable"=>urlencode(" Activer désactiver"),
"required"=>urlencode("Champs obligatoires"),
"min_length"=>urlencode("Longueur minimale"),
"max_length"=>urlencode("Longueur maximale"),
"appointment_details_section"=>urlencode("Détails sur les rendez-vous"),
"if_you_are_having_booking_system_which_need_the_booking_address_then_please_make_this_field_enable_or_else_it_will_not_able_to_take_the_booking_address_and_display_blank_address_in_the_booking"=>urlencode("Si vous avez un système de réservation qui a besoin de l'adresse de réservation, veuillez activer ce champ sinon il ne pourra pas prendre l'adresse de réservation et afficher une adresse vide dans la réservation"),
"front_language_dropdown"=>urlencode(" Front Dropdown Langue"),
"enabled"=>urlencode("Activée"),
"vaccume_cleaner"=>urlencode(" Aspirateur"),
"staff_members"=>urlencode(" Les membres du personnel"),
"add_new_staff_member"=>urlencode(" Ajouter un nouveau membre du personnel"),
"role"=>urlencode(" Rôle"),
"staff"=>urlencode("Personnel"),
"admin"=>urlencode(" Admin"),
"service_details"=>urlencode("Détails du service"),
"technical_admin"=>urlencode("Admin technique"),
"enable_booking"=>urlencode(" Activer la réservation"),
"flat_commission"=>urlencode(" Commission à plat"),
"manageable_form_fields_front_booking_form"=>urlencode(" Champs de formulaire maniables pour le formulaire de réservation"),
"manageable_form_fields"=>urlencode(" Champs de formulaire maniables"),
"sms"=>urlencode("SMS"),
"crm"=>urlencode("CRM"),
"message"=>urlencode("Message"),
"send_message"=>urlencode(" Envoyer le message"),
"all_messages"=>urlencode("Tous les messages"),
"subject"=>urlencode(" Assujettir"),
"add_attachment"=>urlencode(" Ajouter une pièce jointe"),
"send"=>urlencode("Envoyer"),
"close"=>urlencode("Fermer"),
"delete_this_customer?"=>urlencode(" Supprimer ce client?"),
"yes"=>urlencode("Oui"),
"add_new_customer"=>urlencode(" Ajouter un nouveau client"),
"attachment"=>urlencode("attachement"),
"date"=>urlencode("rendez-vous amoureux"),
"see_attachment"=>urlencode(" Voir pièce jointe"),
"no_attachment"=>urlencode(" Pas d'attachement"),
"ct_special_offer"=>urlencode(" Offre spéciale"),
"ct_special_offer_text"=>urlencode(" Offre spéciale Texte"),
);

$error_labels_fr_FR = array (
"language_status_change_successfully"=>urlencode("Changement de statut de langue avec succès"),
"commission_amount_should_not_be_greater_then_order_amount"=>urlencode("Le montant de la commission ne doit pas être supérieur au montant de la commande"),
"please_enter_merchant_ID"=>urlencode("Veuillez entrer l'identifiant du commerçant"),
"please_enter_secure_key"=>urlencode("Veuillez entrer la clé sécurisée"),
"please_enter_google_calender_admin_url"=>urlencode(" Veuillez entrer l'URL d'administration du calendrier Google"),
"please_enter_google_calender_frontend_url"=>urlencode("Veuillez entrer l'URL frontend du calendrier Google"),
"please_enter_google_calender_client_secret"=>urlencode("Entrez le secret du client google calender"),
"please_enter_google_calender_client_ID"=>urlencode("Veuillez entrer l'ID client du calendrier Google"),
"please_enter_google_calender_ID"=>urlencode(" Veuillez entrer l'identifiant du calendrier Google"),
"you_cannot_book_on_past_date"=>urlencode("Vous ne pouvez pas réserver à une date antérieure"),
"Invalid_Image_Type"=>urlencode(" Type d'image invalide"),
"seo_settings_updated_successfully"=>urlencode("Paramètres SEO mis à jour avec succès"),
"customer_deleted_successfully"=>urlencode(" Client supprimé avec succès"),
"please_enter_below_36_characters"=>urlencode("Veuillez entrer ci-dessous 36 caractères"),
"are_you_sure_you_want_to_delete_client"=>urlencode(" Êtes-vous sûr de vouloir supprimer le client?"),
"please_select_atleast_one_unit"=>urlencode("Veuillez sélectionner au moins une unité"),
"atleast_one_payment_method_should_be_enable"=>urlencode("Au moins une méthode de paiement doit être activée"),
"appointment_booking_confirm"=>urlencode(" La réservation de rendez-vous confirme"),
"appointment_booking_rejected"=>urlencode(" Réservation de rendez-vous rejetée"),
"booking_cancel"=>urlencode(" Boooking annulé"),
"appointment_marked_as_no_show"=>urlencode("Rendez-vous marqué comme non-présentation"),
"appointment_reschedules_successfully"=>urlencode("Rendez-vous avec succès"),
"booking_deleted"=>urlencode(" Réservation supprimée"),
"break_end_time_should_be_greater_than_start_time"=>urlencode(" L'heure de fin de la pause doit être supérieure à l'heure de début"),
"cancel_by_client"=>urlencode(" Annuler par le client"),
"cancelled_by_service_provider"=>urlencode(" Annulé par le fournisseur de services"),
"design_set_successfully"=>urlencode(" Conception définie avec succès"),
"end_break_time_updated"=>urlencode(" Fin du temps de pause mis à jour"),
"enter_alphabets_only"=>urlencode(" Entrer uniquement les alphabets"),
"enter_only_alphabets"=>urlencode(" Entrer seulement les alphabets"),
"enter_only_alphabets_numbers"=>urlencode(" Entrer seulement les alphabets / nombres"),
"enter_only_digits"=>urlencode("Entrer seulement les chiffres"),
"enter_valid_url"=>urlencode("Entrez une URL valide"),
"enter_only_numeric"=>urlencode(" Entrer seulement numérique"),
"enter_proper_country_code"=>urlencode(" Entrez le code de pays approprié"),
"frequently_discount_status_updated"=>urlencode(" Foire état de remise à jour mis à jour"),
"frequently_discount_updated"=>urlencode(" Foire remise à jour"),
"manage_addons_service"=>urlencode("Gérer le service des addons"),
"maximum_file_upload_size_2_mb"=>urlencode(" Taille maximale du téléchargement de fichier 2 Mo"),
"method_deleted_successfully"=>urlencode(" Méthode supprimée avec succès"),
"method_inserted_successfully"=>urlencode(" Méthode insérée avec succès"),
"minimum_file_upload_size_1_kb"=>urlencode(" Taille minimale du téléchargement de fichier 1 Ko"),
"off_time_added_successfully"=>urlencode(" Heure d'arrêt ajoutée avec succès"),
"only_jpeg_png_and_gif_images_allowed"=>urlencode(" Seules les images jpeg, png et gif sont autorisées"),
"only_jpeg_png_gif_zip_and_pdf_allowed"=>urlencode("Seuls les jpeg, png, gif, zip et pdf sont autorisés"),
"please_wait_while_we_send_all_your_message"=>urlencode(" Veuillez patienter pendant que nous envoyons tous vos messages"),
"please_enable_email_to_client"=>urlencode("Veuillez activer les e-mails au client."),
"please_enable_sms_gateway"=>urlencode(" Veuillez activer la passerelle SMS."),
"please_enable_client_notification"=>urlencode(" Veuillez activer la notification du client."),
"password_must_be_8_character_long"=>urlencode(" Le mot de passe doit comporter 8 caractères"),
"password_should_not_exist_more_then_20_characters"=>urlencode(" Le mot de passe ne devrait pas exister plus de 20 caractères"),
"please_assign_base_price_for_unit"=>urlencode("Veuillez assigner le prix de base pour l'unité"),
"please_assign_price"=>urlencode("S'il vous plaît attribuer le prix"),
"please_assign_qty"=>urlencode("S'il vous plaît attribuer la quantité"),
"please_enter_api_password"=>urlencode("Entrez le mot de passe de l'API"),
"please_enter_api_username"=>urlencode(" Veuillez entrer le nom d'utilisateur de l'API"),
"please_enter_color_code"=>urlencode(" Veuillez entrer le code de couleur"),
"please_enter_country"=>urlencode(" S'il vous plaît entrer le pays"),
"please_enter_coupon_limit"=>urlencode(" S'il vous plaît entrer la limite de coupon"),
"please_enter_coupon_value"=>urlencode(" Veuillez entrer la valeur du coupon"),
"please_enter_coupon_code"=>urlencode("Veuillez entrer le code du coupon"),
"please_enter_email"=>urlencode(" S'il vous plaît entrer email"),
"please_enter_fullname"=>urlencode(" Veuillez entrer le nom complet"),
"please_enter_maxlimit"=>urlencode(" Veuillez entrer maxLimit"),
"please_enter_method_title"=>urlencode(" Veuillez entrer le titre de la méthode"),
"please_enter_name"=>urlencode(" S'il vous plaît entrer le nom"),
"please_enter_only_numeric"=>urlencode(" S'il vous plaît entrer seulement numérique"),
"please_enter_proper_base_price"=>urlencode(" Veuillez entrer le prix de base approprié"),
"please_enter_proper_name"=>urlencode(" S'il vous plaît entrer le nom correct"),
"please_enter_proper_title"=>urlencode("Veuillez entrer le titre approprié"),
"please_enter_publishable_key"=>urlencode(" Veuillez entrer la clé publique"),
"please_enter_secret_key"=>urlencode(" Veuillez entrer une clé secrète"),
"please_enter_service_title"=>urlencode(" S'il vous plaît entrer le service Titre"),
"please_enter_signature"=>urlencode(" S'il vous plaît entrer la signature"),
"please_enter_some_qty"=>urlencode(" S'il vous plaît entrer une quantité"),
"please_enter_title"=>urlencode("S'il vous plaît entrer le titre"),
"please_enter_unit_title"=>urlencode(" Veuillez entrer le titre de l'unité"),
"please_enter_valid_country_code"=>urlencode(" Veuillez entrer un code de pays valide"),
"please_enter_valid_service_title"=>urlencode(" Veuillez entrer un titre de service valide"),
"please_enter_valid_price"=>urlencode("Veuillez entrer un prix valide"),
"please_enter_zipcode"=>urlencode(" Veuillez entrer le code postal"),
"please_enter_state"=>urlencode("Veuillez entrer l'état"),
"please_retype_correct_password"=>urlencode("Veuillez retaper le mot de passe correct"),
"please_select_porper_time_slots"=>urlencode("Veuillez sélectionner les créneaux horaires porper"),
"please_select_time_between_day_availability_time"=>urlencode("S'il vous plaît sélectionner l'heure entre la disponibilité du jour"),
"please_valid_value_for_discount"=>urlencode(" S'il vous plaît valide la valeur pour la réduction"),
"please_enter_confirm_password"=>urlencode("Veuillez entrer le mot de passe de confirmation"),
"please_enter_new_password"=>urlencode(" Veuillez entrer un nouveau mot de passe"),
"please_enter_old_password"=>urlencode(" Veuillez entrer l'ancien mot de passe"),
"please_enter_valid_number"=>urlencode("Veuillez entrer un nombre valide"),
"please_enter_valid_number_with_country_code"=>urlencode("Veuillez entrer un numéro valide avec le code du pays"),
"please_select_end_time_greater_than_start_time"=>urlencode(" Veuillez sélectionner l'heure de fin supérieure à l'heure de début"),
"please_select_end_time_less_than_start_time"=>urlencode("Veuillez sélectionner l'heure de fin moins que l'heure de début"),
"please_select_a_crop_region_and_then_press_upload"=>urlencode(" Sélectionnez une région de culture, puis appuyez sur le bouton de téléchargement"),
"please_select_a_valid_image_file_jpg_and_png_are_allowed"=>urlencode(" Veuillez sélectionner un fichier image valide jpg et png sont autorisés"),
"profile_updated_successfully"=>urlencode(" Mise à jour du profil réussie"),
"qty_rule_deleted"=>urlencode(" Quantité de règle supprimée"),
"record_deleted_successfully"=>urlencode(" Enregistrement supprimé avec succès"),
"record_updated_successfully"=>urlencode(" Enregistrement mis à jour avec succès"),
"rescheduled"=>urlencode("Reprogrammé"),
"schedule_updated_to_monthly"=>urlencode("Calendrier mis à jour mensuellement"),
"schedule_updated_to_weekly"=>urlencode(" Calendrier mis à jour à Hebdomadaire"),
"sorry_method_already_exist"=>urlencode(" La méthode Désolé existe déjà"),
"sorry_no_notification"=>urlencode(" Désolé, vous n'avez aucun rendez-vous à venir"),
"sorry_promocode_already_exist"=>urlencode(" Promocode Désolé existe déjà"),
"sorry_unit_already_exist"=>urlencode(" Une unité désolée existe déjà"),
"sorry_we_are_not_available"=>urlencode(" Désolé, nous ne sommes pas disponibles"),
"start_break_time_updated"=>urlencode(" Début du temps de pause mis à jour"),
"status_updated"=>urlencode(" Statut mis à jour"),
"time_slots_updated_successfully"=>urlencode("Les créneaux horaires mis à jour"),
"unit_inserted_successfully"=>urlencode(" Unité insérée avec succès"),
"units_status_updated"=>urlencode("Statut des unités mis à jour"),
"updated_appearance_settings"=>urlencode(" Aetings d'apparence mis à jour"),
"updated_company_details"=>urlencode(" Informations mises à jour"),
"updated_email_settings"=>urlencode(" Paramètres de messagerie mis à jour"),
"updated_general_settings"=>urlencode("Paramètres généraux mis à jour"),
"updated_payments_settings"=>urlencode(" Paramètres de paiement mis à jour"),
"your_old_password_incorrect"=>urlencode(" Ancien mot de passe incorrect"),
"please_enter_minimum_5_chars"=>urlencode("Veuillez entrer au moins 5 caractères"),
"please_enter_maximum_10_chars"=>urlencode("Veuillez entrer 10 caractères maximum"),
"please_enter_postal_code"=>urlencode(" Veuillez entrer le code postal"),
"please_select_a_service"=>urlencode(" Veuillez sélectionner un service"),
"please_select_units_and_addons"=>urlencode(" Veuillez sélectionner les unités et les addons"),
"please_select_units_or_addons"=>urlencode(" Veuillez sélectionner des unités ou des addons"),
"please_login_to_complete_booking"=>urlencode("Veuillez vous connecter pour compléter la réservation"),
"please_select_appointment_date"=>urlencode("Veuillez sélectionner une date de rendez-vous"),
"please_accept_terms_and_conditions"=>urlencode(" Veuillez accepter les termes et conditions"),
"incorrect_email_address_or_password"=>urlencode(" Adresse e-mail ou mot de passe incorrect"),
"please_enter_valid_email_address"=>urlencode(" Veuillez entrer une adresse email valide"),
"please_enter_email_address"=>urlencode(" Veuillez entrer votre adresse e-mail"),
"please_enter_password"=>urlencode(" Veuillez entrer le mot de passe"),
"please_enter_minimum_8_characters"=>urlencode("Veuillez entrer au moins 8 caractères"),
"please_enter_maximum_15_characters"=>urlencode(" Veuillez entrer 15 caractères maximum"),
"please_enter_first_name"=>urlencode(" S'il vous plaît entrer le prénom"),
"please_enter_only_alphabets"=>urlencode(" S'il vous plaît entrer seulement des alphabets"),
"please_enter_minimum_2_characters"=>urlencode(" Veuillez entrer au moins 2 caractères"),
"please_enter_last_name"=>urlencode(" Veuillez entrer le nom de famille"),
"email_already_exists"=>urlencode(" l'email existe déjà"),
"please_enter_phone_number"=>urlencode(" S'il vous plaît entrer le numéro de téléphone"),
"please_enter_only_numerics"=>urlencode(" Veuillez entrer seulement les chiffres"),
"please_enter_minimum_10_digits"=>urlencode("Veuillez entrer au moins 10 chiffres"),
"please_enter_maximum_14_digits"=>urlencode("Veuillez entrer au maximum 14 chiffres"),
"please_enter_address"=>urlencode(" S'il vous plaît entrer l'adresse"),
"please_enter_minimum_20_characters"=>urlencode("Veuillez entrer au moins 20 caractères"),
"please_enter_zip_code"=>urlencode("Veuillez entrer le code postal"),
"please_enter_proper_zip_code"=>urlencode(" Veuillez entrer le bon code postal"),
"please_enter_minimum_5_digits"=>urlencode(" Veuillez entrer au moins 5 chiffres"),
"please_enter_maximum_7_digits"=>urlencode(" Veuillez entrer 7 chiffres maximum"),
"please_enter_city"=>urlencode(" S'il vous plaît entrer la ville"),
"please_enter_proper_city"=>urlencode(" S'il vous plaît entrer la ville appropriée"),
"please_enter_maximum_48_characters"=>urlencode(" Veuillez entrer au maximum 48 caractères"),
"please_enter_proper_state"=>urlencode(" Veuillez entrer l'état correct"),
"please_enter_contact_status"=>urlencode(" Veuillez entrer le statut du contact"),
"please_enter_maximum_100_characters"=>urlencode(" Veuillez entrer au maximum 100 caractères"),
"your_cart_is_empty_please_add_cleaning_services"=>urlencode(" Votre panier est vide s'il vous plaît ajouter des services de nettoyage"),
"coupon_expired"=>urlencode(" Le coupon a expiré"),
"invalid_coupon"=>urlencode(" Coupon invalide"),
"our_service_not_available_at_your_location"=>urlencode(" Notre service n'est pas disponible chez vous"),
"please_enter_proper_postal_code"=>urlencode(" Veuillez entrer le code postal approprié"),
"invalid_email_id_please_register_first"=>urlencode(" Identifiant d'email invalide s'il vous plaît inscrivez-vous d'abord"),
"your_password_send_successfully_at_your_registered_email_id"=>urlencode(" Votre mot de passe envoyé avec succès à votre adresse e-mail enregistrée"),
"your_password_reset_successfully_please_login"=>urlencode(" Votre mot de passe réinitialisé avec succès veuillez vous identifier"),
"new_password_and_retype_new_password_mismatch"=>urlencode(" Nouveau mot de passe et retaper la nouvelle incohérence de mot de passe"),
"new"=>urlencode("Nouveau"),
"your_reset_password_link_expired"=>urlencode(" Votre lien de réinitialisation du mot de passe a expiré"),
"front_display_language_changed"=>urlencode(" Le langage d'affichage avant a changé"),
"updated_front_display_language_and_update_labels"=>urlencode(" Mise à jour du langage d'affichage avant et mise à jour des étiquettes"),
"please_enter_only_7_chars_maximum"=>urlencode(" Veuillez entrer seulement 7 caractères maximum"),
"please_enter_maximum_20_chars"=>urlencode(" Veuillez entrer 20 caractères maximum"),
"record_inserted_successfully"=>urlencode(" Enregistrement inséré avec succès"),
"please_enter_account_sid"=>urlencode(" Veuillez entrer Accout SID"),
"please_enter_auth_token"=>urlencode(" S'il vous plaît entrer Auth Token"),
"please_enter_sender_number"=>urlencode("Veuillez entrer le numéro de l'expéditeur"),
"please_enter_admin_number"=>urlencode(" Veuillez entrer le numéro d'administrateur"),
"sorry_service_already_exist"=>urlencode(" Le service Désolé existe déjà"),
"please_enter_api_login_id"=>urlencode(" Entrez l'identifiant de connexion de l'API"),
"please_enter_transaction_key"=>urlencode(" Veuillez entrer une clé de transaction"),
"please_enter_sms_message"=>urlencode("S'il vous plaît entrer un message SMS"),
"please_enter_email_message"=>urlencode(" Veuillez entrer un message électronique"),
"please_enter_private_key"=>urlencode(" Veuillez entrer une clé privée"),
"please_enter_seller_id"=>urlencode(" Veuillez entrer l'identifiant du vendeur"),
"please_enter_valid_value_for_discount"=>urlencode("Veuillez entrer une valeur valide pour une remise"),
"password_must_be_only_10_characters"=>urlencode(" Le mot de passe ne doit contenir que 10 caractères"),
"password_at_least_have_8_characters"=>urlencode(" Mot de passe au moins ont 8 caractères"),
"please_enter_retype_new_password"=>urlencode(" Veuillez entrer le nouveau mot de passe"),
"your_password_send_successfully_at_your_email_id"=>urlencode(" Votre mot de passe envoyé avec succès à votre adresse e-mail"),
"please_select_expiry_date"=>urlencode(" Veuillez sélectionner la date d'expiration"),
"please_enter_merchant_key"=>urlencode(" Veuillez entrer la clé du marchand"),
"please_enter_salt_key"=>urlencode(" Veuillez entrer Salt Key"),
"please_enter_account_username"=>urlencode("Veuillez entrer le nom d'utilisateur du compte"),
"please_enter_account_hash_id"=>urlencode(" Veuillez entrer l'identifiant de hachage du compte"),
"invalid_values"=>urlencode(" Valeurs invalides"),
"please_select_atleast_one_checkout_method"=>urlencode(" Veuillez sélectionner au moins une méthode de paiement"),
);

$extra_labels_fr_FR = array (
"please_enter_minimum_3_chars"=>urlencode(" Veuillez entrer au moins 3 caractères"),
"invoice"=>urlencode(" FACTURE D'ACHAT"),
"invoice_to"=>urlencode(" FACTURE À"),
"invoice_date"=>urlencode(" Date de facturation"),
"cash"=>urlencode(" EN ESPÈCES"),
"service_name"=>urlencode(" Nom du service"),
"qty"=>urlencode(" Qté"),
"booked_on"=>urlencode("Réservé le"),
);

$front_error_labels_fr_FR = array (
"min_ff_ps"=>urlencode("Veuillez entrer au moins 8 caractères"),
"max_ff_ps"=>urlencode("Veuillez entrer 10 caractères maximum"),
"req_ff_fn"=>urlencode(" S'il vous plaît entrer le prénom"),
"min_ff_fn"=>urlencode("Veuillez entrer au moins 3 caractères"),
"max_ff_fn"=>urlencode(" Veuillez entrer 15 caractères maximum"),
"req_ff_ln"=>urlencode("Veuillez entrer le nom de famille"),
"min_ff_ln"=>urlencode("Veuillez entrer au moins 3 caractères"),
"max_ff_ln"=>urlencode("Veuillez entrer 15 caractères maximum"),
"req_ff_ph"=>urlencode(" S'il vous plaît entrer le numéro de téléphone"),
"min_ff_ph"=>urlencode(" Veuillez entrer au moins 9 caractères"),
"max_ff_ph"=>urlencode(" Veuillez entrer 15 caractères maximum"),
"req_ff_sa"=>urlencode("S'il vous plaît entrer l'adresse"),
"min_ff_sa"=>urlencode("Veuillez entrer au moins 10 caractères"),
"max_ff_sa"=>urlencode(" Veuillez entrer au maximum 40 caractères"),
"req_ff_zp"=>urlencode(" Veuillez entrer le code postal"),
"min_ff_zp"=>urlencode("Veuillez entrer au moins 3 caractères"),
"max_ff_zp"=>urlencode("Veuillez entrer 7 caractères maximum"),
"req_ff_ct"=>urlencode(" S'il vous plaît entrer la ville"),
"min_ff_ct"=>urlencode(" Veuillez entrer au moins 3 caractères"),
"max_ff_ct"=>urlencode(" Veuillez entrer 15 caractères maximum"),
"req_ff_st"=>urlencode(" Veuillez entrer l'état"),
"min_ff_st"=>urlencode("Veuillez entrer au moins 3 caractères"),
"max_ff_st"=>urlencode(" Veuillez entrer 15 caractères maximum"),
"req_ff_srn"=>urlencode(" S'il vous plaît entrer des notes"),
"min_ff_srn"=>urlencode(" Veuillez entrer au moins 10 caractères"),
"max_ff_srn"=>urlencode(" Veuillez entrer au maximum 70 caractères"),
"Transaction_failed_please_try_again"=>urlencode(" La transaction a échoué. Veuillez réessayer."),
"Please_Enter_valid_card_detail"=>urlencode(" Veuillez entrer un détail de carte valide"),
);

$language_front_arr_fr_FR = base64_encode(serialize($label_data_fr_FR));
$language_admin_arr_fr_FR = base64_encode(serialize($admin_labels_fr_FR));
$language_error_arr_fr_FR = base64_encode(serialize($error_labels_fr_FR));
$language_extra_arr_fr_FR = base64_encode(serialize($extra_labels_fr_FR));
$language_form_error_arr_fr_FR = base64_encode(serialize($front_error_labels_fr_FR));

$insert_default_lang_fr_FR = "insert into `ct_languages` (`id`,`label_data`,`language`, `admin_labels`, `error_labels`, `extra_labels`, `front_error_labels`,`language_status`) values(NULL,'" . $language_front_arr_fr_FR . "','fr_FR','" . $language_admin_arr_fr_FR . "','" . $language_error_arr_fr_FR . "','" . $language_extra_arr_fr_FR . "','" . $language_form_error_arr_fr_FR . "','Y')";
mysqli_query($this->conn, $insert_default_lang_fr_FR);

/** Portuguese Language **/
$label_data_pt_PT = array (
"none_available"=>urlencode("Não disponível"),
"appointment_zip"=>urlencode("Compromisso Zip"),
"appointment_city"=>urlencode("Cidade de nomeação"),
"appointment_state"=>urlencode("Estado da nomeação"),
"appointment_address"=>urlencode("Endereço do Compromisso"),
"guest_user"=>urlencode("Usuário convidado"),
"service_usage_methods"=>urlencode("Métodos de uso de serviço"),
"paypal"=>urlencode("Paypal"),
"please_check_for_the_below_missing_information"=>urlencode("Por favor, verifique as informações abaixo."),
"please_provide_company_details_from_the_admin_panel"=>urlencode("Por favor, forneça os detalhes da empresa no painel de administração."),
"please_add_some_services_methods_units_addons_from_the_admin_panel"=>urlencode("Por favor, adicione alguns serviços, métodos, unidades, addons do painel de administração."),
"please_add_time_scheduling_from_the_admin_panel"=>urlencode("Por favor, adicione o agendamento de tempo no painel de administração."),
"please_complete_configurations_before_you_created_website_embed_code"=>urlencode("Por favor, preencha as configurações antes de criar o código de incorporação do site."),
"cvc"=>urlencode("CVC"),
"mm_yyyy"=>urlencode("(MM / AAAA)"),
"expiry_date_or_csv"=>urlencode("Data de validade ou CSV"),
"street_address_placeholder"=>urlencode("por exemplo. Avenida Central"),
"zip_code_placeholder"=>urlencode("por exemplo. 90001"),
"city_placeholder"=>urlencode("por exemplo. Los Angeles"),
"state_placeholder"=>urlencode("por exemplo. CA"),
"payumoney"=>urlencode("PayUmoney"),
"same_as_above"=>urlencode("O mesmo que acima"),
"sun"=>urlencode("dom"),
"mon"=>urlencode("seg"),
"tue"=>urlencode("ter"),
"wed"=>urlencode("qua"),
"thu"=>urlencode("qui"),
"fri"=>urlencode("sex"),
"sat"=>urlencode("Sentou"),
"su"=>urlencode("Su"),
"mo"=>urlencode("Mo"),
"tu"=>urlencode("Tu"),
"we"=>urlencode("Nós"),
"th"=>urlencode("º"),
"fr"=>urlencode("Padre"),
"sa"=>urlencode("Sa"),
"my_bookings"=>urlencode("Minhas reservas"),
"your_postal_code"=>urlencode("CEP ou Código postal"),
"where_would_you_like_us_to_provide_service"=>urlencode("Onde você gostaria de nos fornecer serviço?"),
"choose_service"=>urlencode("Escolha o serviço"),
"how_often_would_you_like_us_provide_service"=>urlencode("Quantas vezes você gostaria de nos fornecer serviço?"),
"when_would_you_like_us_to_come"=>urlencode("Quando você gostaria que viéssemos?"),
"today"=>urlencode("HOJE"),
"your_personal_details"=>urlencode("Seus dados pessoais"),
"existing_user"=>urlencode("Usuário existente"),
"new_user"=>urlencode("Novo usuário"),
"preferred_email"=>urlencode("E-mail preferido"),
"preferred_password"=>urlencode("Senha preferida"),
"your_valid_email_address"=>urlencode("Seu endereço de email válido"),
"first_name"=>urlencode("Primeiro nome"),
"your_first_name"=>urlencode("Seu primeiro nome"),
"last_name"=>urlencode("Último nome"),
"your_last_name"=>urlencode("Seu último Nome"),
"street_address"=>urlencode("Endereço"),
"cleaning_service"=>urlencode("Serviço de limpeza"),
"please_select_method"=>urlencode("Por favor selecione o método"),
"zip_code"=>urlencode("Código postal"),
"city"=>urlencode("Cidade"),
"state"=>urlencode("Estado"),
"special_requests_notes"=>urlencode("Pedidos especiais (notas)"),
"do_you_have_a_vaccum_cleaner"=>urlencode("Você tem um aspirador de pó?"),
"assign_appointment_to_staff"=>urlencode("Atribuir um compromisso ao pessoal"),
"delete_member"=>urlencode("Excluir membro?"),
"yes"=>urlencode("sim"),
"no"=>urlencode("Não"),
"preferred_payment_method"=>urlencode("Método de pagamento preferido"),
"please_select_one_payment_method"=>urlencode("Por favor, selecione um método de pagamento"),
"partial_deposit"=>urlencode("Depósito Parcial"),
"remaining_amount"=>urlencode("Montante restante"),
"please_read_our_terms_and_conditions_carefully"=>urlencode("Por favor, leia nossos termos e condições cuidadosamente"),
"do_you_have_parking"=>urlencode("Você tem estacionamento?"),
"how_will_we_get_in"=>urlencode("Como vamos entrar?"),
"i_will_be_at_home"=>urlencode("Eu estarei em casa"),
"please_call_me"=>urlencode("Por favor me ligue"),
"recurring_discounts_apply_from_the_second_cleaning_onward"=>urlencode("Descontos recorrentes se aplicam a partir da segunda limpeza."),
"please_provide_your_address_and_contact_details"=>urlencode("Por favor, forneça seu endereço e detalhes de contato"),
"you_are_logged_in_as"=>urlencode("Você está logado como"),
"the_key_is_with_the_doorman"=>urlencode("A chave é com o porteiro"),
"other"=>urlencode("De outros"),
"have_a_promocode"=>urlencode("Tem um código promocional?"),
"apply"=>urlencode("Aplique"),
"applied_promocode"=>urlencode("Promocode aplicado"),
"complete_booking"=>urlencode("Reserva completa"),
"cancellation_policy"=>urlencode("Política de cancelamento"),
"cancellation_policy_header"=>urlencode("Cabeçalho da Política de Cancelamento"),
"cancellation_policy_textarea"=>urlencode("Política de Cancelamento Textarea"),
"free_cancellation_before_redemption"=>urlencode("Cancelamento gratuito antes do resgate"),
"show_more"=>urlencode("Mostre mais"),
"please_select_service"=>urlencode("Por favor selecione serviço"),
"choose_your_service_and_property_size"=>urlencode("Escolha o seu serviço e tamanho da propriedade"),
"choose_your_service"=>urlencode("Escolha seu serviço"),
"please_configure_first_cleaning_services_and_settings_in_admin_panel"=>urlencode("Por favor, configure os primeiros serviços de limpeza e configurações no painel de administração"),
"i_have_read_and_accepted_the"=>urlencode("Eu li e aceitei o"),
"terms_and_condition"=>urlencode("termos e Condições"),
"and"=>urlencode("e"),
"updated_labels"=>urlencode("Rótulos atualizados"),
"privacy_policy"=>urlencode("Política de Privacidade"),
"please_fill_all_the_company_informations_and_add_some_services_and_addons"=>urlencode("Configurações necessárias não são concluídas."),
"booking_summary"=>urlencode("Sumário"),
"your_email"=>urlencode("Seu email"),
"enter_email_to_login"=>urlencode("Insira o email para o login"),
"your_password"=>urlencode("Sua senha"),
"enter_your_password"=>urlencode("Coloque sua senha"),
"forget_password"=>urlencode("Esqueceu a senha?"),
"reset_password"=>urlencode("Redefinir Senha"),
"enter_your_email_and_we_send_you_instructions_on_resetting_your_password"=>urlencode("Digite seu e-mail e enviaremos instruções sobre como redefinir sua senha."),
"registered_email"=>urlencode("Email registrado"),
"send_mail"=>urlencode("Enviar correio"),
"back_to_login"=>urlencode("Volte ao login"),
"your"=>urlencode("Seu"),
"your_clean_items"=>urlencode("Seus itens limpos"),
"your_cart_is_empty"=>urlencode("Seu carrinho está vazio"),
"sub_totaltax"=>urlencode("SubTax Total"),
"sub_total"=>urlencode("Subtotal"),
"no_data_available_in_table"=>urlencode("Sem dados disponíveis na tabela"),
"total"=>urlencode("Total"),
"or"=>urlencode("Ou"),
"select_addon_image"=>urlencode("Selecione imagem addon"),
"inside_fridge"=>urlencode("Dentro da geladeira"),
"inside_oven"=>urlencode("Forno Interno"),
"inside_windows"=>urlencode("Dentro do Windows"),
"carpet_cleaning"=>urlencode("Limpeza de carpete"),
"green_cleaning"=>urlencode("Limpeza Verde"),
"pets_care"=>urlencode("Cuidado De Animais De Estimação"),
"tiles_cleaning"=>urlencode("Limpeza de Azulejos"),
"wall_cleaning"=>urlencode("Limpeza de paredes"),
"laundry"=>urlencode("Lavanderia"),
"basement_cleaning"=>urlencode("Limpeza De Porão"),
"basic_price"=>urlencode("Preço básico"),
"max_qty"=>urlencode("Max Qty"),
"multiple_qty"=>urlencode("Qtd Múltiplo"),
"base_price"=>urlencode("Preço base"),
"unit_pricing"=>urlencode("Preço unitário"),
"method_is_booked"=>urlencode("O método é reservado"),
"service_addons_price_rules"=>urlencode("Regras de preço de Service Addons"),
"service_unit_front_dropdown_view"=>urlencode("Unidade de Serviço Front DropDown View"),
"service_unit_front_block_view"=>urlencode("Vista do Bloco Frontal da Unidade de Serviço"),
"service_unit_front_increase_decrease_view"=>urlencode("Exibição de aumento / diminuição frontal da unidade de serviço"),
"are_you_sure"=>urlencode("Are You Sure"),
"service_unit_price_rules"=>urlencode("Regras de preço da unidade de serviço"),
"close"=>urlencode("Fechar"),
"closed"=>urlencode("Fechadas"),
"service_addons"=>urlencode("Addons de serviço"),
"service_enable"=>urlencode("Habilitar Serviço"),
"service_disable"=>urlencode("Desativar Serviço"),
"method_enable"=>urlencode("Habilitar Método"),
"off_time_deleted"=>urlencode("Tempo de Exclusão Excluído"),
"error_in_delete_of_off_time"=>urlencode("Erro na exclusão do tempo de inatividade"),
"method_disable"=>urlencode("Desativar Método"),
"extra_services"=>urlencode("Serviços extras"),
"for_initial_cleaning_only_contact_us_to_apply_to_recurrings"=>urlencode("Apenas para limpeza inicial. Entre em contato conosco para se candidatar a reincidências."),
"number_of"=>urlencode("Número de"),
"extra_services_not_available"=>urlencode("Serviços extras não disponíveis"),
"available"=>urlencode("acessível"),
"selected"=>urlencode("Selecionado"),
"not_available"=>urlencode("Não disponível"),
"none"=>urlencode("Nenhum"),
"none_of_time_slot_available_please_check_another_dates"=>urlencode("Nenhum dos horários disponíveis Por favor, verifique outras datas"),
"availability_is_not_configured_from_admin_side"=>urlencode("A disponibilidade não está configurada do lado do administrador"),
"how_many_intensive"=>urlencode("Quantos intensivos"),
"no_intensive"=>urlencode("Não intensivo"),
"frequently_discount"=>urlencode("Desconto frequente"),
"coupon_discount"=>urlencode("Desconto de cupão"),
"how_many"=>urlencode("Quantos"),
"enter_your_other_option"=>urlencode("Insira sua outra opção"),
"log_out"=>urlencode("Sair"),
"your_added_off_times"=>urlencode("Seus tempos fora adicionados"),
"log_in"=>urlencode("entrar"),
"custom_css"=>urlencode("CSS customizado"),
"success"=>urlencode("Sucesso"),
"failure"=>urlencode("Falha"),
"you_can_only_use_valid_zipcode"=>urlencode("Você só pode usar um CEP válido"),
"minutes"=>urlencode("Minutos"),
"hours"=>urlencode("Horas"),
"days"=>urlencode("Dias"),
"months"=>urlencode("Meses"),
"year"=>urlencode("Ano"),
"default_url_is"=>urlencode("O URL padrão é"),
"card_payment"=>urlencode("Pagamento com cartão"),
"pay_at_venue"=>urlencode("Pague no local"),
"card_details"=>urlencode("Detalhes do cartão"),
"card_number"=>urlencode("Número do cartão"),
"invalid_card_number"=>urlencode("número de cartão inválido"),
"expiry"=>urlencode("Termo"),
"button_preview"=>urlencode("Pré-visualização do botão"),
"thankyou"=>urlencode("Obrigado"),
"thankyou_for_booking_appointment"=>urlencode("Obrigado! para marcação de reserva"),
"you_will_be_notified_by_email_with_details_of_appointment"=>urlencode("Você será notificado por e-mail com detalhes de compromisso"),
"please_enter_firstname"=>urlencode("Por favor, insira firstname"),
"please_enter_lastname"=>urlencode("Por favor insira lastname"),
"remove_applied_coupon"=>urlencode("Remover cupão aplicado"),
"eg_799_e_dragram_suite_5a"=>urlencode("por exemplo. 799 E DRAGRAM SUITE 5A"),
"eg_14114"=>urlencode("por exemplo. 14114"),
"eg_tucson"=>urlencode("por exemplo. TUCSON"),
"eg_az"=>urlencode("por exemplo. AZ"),
"warning"=>urlencode("Atenção"),
"try_later"=>urlencode("Tente depois"),
"choose_your"=>urlencode("Escolha o seu"),
"configure_now_new"=>urlencode("Configurar agora"),
"january"=>urlencode("JANEIRO"),
"february"=>urlencode("FEVEREIRO"),
"march"=>urlencode("MARCHA"),
"april"=>urlencode("ABRIL"),
"may"=>urlencode("PODE"),
"june"=>urlencode("JUNHO"),
"july"=>urlencode("JULHO"),
"august"=>urlencode("AGOSTO"),
"september"=>urlencode("SETEMBRO"),
"october"=>urlencode("OUTUBRO"),
"november"=>urlencode("NOVEMBRO"),
"december"=>urlencode("DEZEMBRO"),
"jan"=>urlencode("JAN"),
"feb"=>urlencode("FEB"),
"mar"=>urlencode("MAR"),
"apr"=>urlencode("Abril"),
"jun"=>urlencode("JUN"),
"jul"=>urlencode("JUL"),
"aug"=>urlencode("AGO"),
"sep"=>urlencode("SEP"),
"oct"=>urlencode("OCT"),
"nov"=>urlencode("NOV"),
"dec"=>urlencode("DEC"),
"pay_locally"=>urlencode("Pague localmente"),
"please_select_provider"=>urlencode("Por favor selecione provedor"),
);

$admin_labels_pt_PT = array (
"payment_status"=>urlencode("Status do pagamento"),
"staff_booking_status"=>urlencode("Status de reserva de pessoal"),
"accept"=>urlencode("Aceitar"),
"accepted"=>urlencode("Aceitaram"),
"decline"=>urlencode("Declínio"),
"paid"=>urlencode("Pago"),
"eway"=>urlencode("Eway"),
"half_section"=>urlencode("Meia seção"),
"option_title"=>urlencode("Título da Opção"),
"merchant_ID"=>urlencode("ID do comerciante"),
"How_it_works"=>urlencode("Como funciona?"),
"Your_currency_should_be_AUD_to_enable_payway_payment_gateway"=>urlencode("Sua moeda deve ser Australia Dollar para ativar o gateway de pagamento de payway"),
"secure_key"=>urlencode("Chave Segura"),
"payway"=>urlencode("Payway"),
"Your_Google_calendar_id_where_you_need_to_get_alerts_its_normaly_your_Gmail_ID"=>urlencode("O código da sua agenda do Google, onde você precisa receber alertas, normalmente é seu ID do Gmail. por exemplo. johndoe@example.com"),
"You_can_get_your_client_ID_from_your_Google_Calendar_Console"=>urlencode("Você pode obter seu ID de cliente no seu Google Calendar Console"),
"You_can_get_your_client_secret_from_your_Google_Calendar_Console"=>urlencode("Você pode obter o segredo do seu cliente no seu Google Calendar Console"),
"its_your_Cleanto_booking_form_page_url"=>urlencode("é o seu URL de página de formulário de reserva Cleanto"),
"Its_your_Cleanto_Google_Settings_page_url"=>urlencode("É o seu URL da página de configurações do Google Cleanto"),
"Add_Manual_booking"=>urlencode("Adicionar Reserva Manual"),
"Google_Calender_Settings"=>urlencode("Configurações do Google Agenda"),
"Add_Appointments_To_Google_Calender"=>urlencode("Adicionar compromissos ao Google Calendar"),
"Google_Calender_Id"=>urlencode("ID do Google Agenda"),
"Google_Calender_Client_Id"=>urlencode("ID do cliente do Google Agenda"),
"Google_Calender_Client_Secret"=>urlencode("Segredo do cliente do Google Agenda"),
"Google_Calender_Frontend_URL"=>urlencode("URL do front end do Google Agenda"),
"Google_Calender_Admin_URL"=>urlencode("URL de administrador do Google Agenda"),
"Google_Calender_Configuration"=>urlencode("Configuração do Google Agenda"),
"Two_Way_Sync"=>urlencode("Sincronização em dois sentidos"),
"Verify_Account"=>urlencode("Verificar conta"),
"Select_Calendar"=>urlencode("Selecione o calendário"),
"Disconnect"=>urlencode("desconectar"),
"Calendar_Fisrt_Day"=>urlencode("Primeiro dia do calendário"),
"Calendar_Default_View"=>urlencode("Visualização padrão do calendário"),
"Show_company_title"=>urlencode("Mostrar título da empresa"),
"front_language_flags_list"=>urlencode("Lista de bandeiras de idiomas da frente"),
"Google_Analytics_Code"=>urlencode("Código do Google Analytics"),
"Page_Meta_Tag"=>urlencode("Página / Meta Tag"),
"SEO_Settings"=>urlencode("Configurações de SEO"),
"Meta_Description"=>urlencode("Meta Descrição"),
"SEO"=>urlencode("SEO"),
"og_tag_image"=>urlencode("og Tag imagem"),
"og_tag_url"=>urlencode("og Tag URL"),
"og_tag_type"=>urlencode("og Tag Type"),
"og_tag_title"=>urlencode("Título da tag"),
"Quantity"=>urlencode("Quantidade"),
"Send_Invoice"=>urlencode("Enviar fatura"),
"Recurrence"=>urlencode("Recorrência"),
"Recurrence_booking"=>urlencode("Reserva de Recorrência"),
"Reset_Color"=>urlencode("Redefinir cor"),
"Loader"=>urlencode("Carregador"),
"CSS_Loader"=>urlencode("Carregador de CSS"),
"GIF_Loader"=>urlencode("Carregador GIF"),
"Default_Loader"=>urlencode("Carregador Padrão"),
"for_a"=>urlencode("para"),
"show_company_logo"=>urlencode("Mostrar logotipo da empresa"),
"on"=>urlencode("em"),
"user_zip_code"=>urlencode("Código postal"),
"delete_this_method"=>urlencode("Excluir este método?"),
"authorize_net"=>urlencode("Autorize.Net"),
"staff_details"=>urlencode("DETALHES DO PESSOAL"),
"client_payments"=>urlencode("Pagamentos do cliente"),
"staff_payments"=>urlencode("Pagamentos da equipe"),
"staff_payments_details"=>urlencode("Detalhes dos Pagamentos da Equipe"),
"advance_paid"=>urlencode("Pagamento antecipado"),
"change_calculation_policyy"=>urlencode("Alterar política de cálculo"),
"frontend_fonts"=>urlencode("Fontes Frontend"),
"favicon_image"=>urlencode("Imagem de Favicon"),
"staff_email_template"=>urlencode("Modelo de e-mail pessoal"),
"staff_details_add_new_and_manage_staff_payments"=>urlencode("Detalhes da equipe, adicionar novos e gerenciar pagamentos de equipe"),
"add_staff"=>urlencode("Adicionar pessoal"),
"staff_bookings_and_payments"=>urlencode("Reservas de pessoal e pagamentos"),
"staff_booking_details_and_payment"=>urlencode("Detalhes de reserva de pessoal e pagamento"),
"select_option_to_show_bookings"=>urlencode("Selecione a opção para mostrar as reservas"),
"select_service"=>urlencode("Selecione o serviço"),
"staff_name"=>urlencode("Nome da equipe"),
"staff_payment"=>urlencode("Pagamento de Pessoal"),
"add_payment_to_staff_account"=>urlencode("Adicionar pagamento à conta pessoal"),
"amount_payable"=>urlencode("Quantidade pagável"),
"save_changes"=>urlencode("Salvar alterações"),
"front_error_labels"=>urlencode("Etiquetas de Erro Frontais"),
"stripe"=>urlencode("Listra"),
"checkout_title"=>urlencode("2Checkout"),
"nexmo_sms_gateway"=>urlencode("Nexmo SMS Gateway"),
"nexmo_sms_setting"=>urlencode("Nexmo SMS Setting"),
"nexmo_api_key"=>urlencode("Chave da API do Nexmo"),
"nexmo_api_secret"=>urlencode("Segredo da API do Nexmo"),
"nexmo_from"=>urlencode("Nexmo De"),
"nexmo_status"=>urlencode("Nexmo Status"),
"nexmo_send_sms_to_client_status"=>urlencode("Nexmo Enviar SMS para o status do cliente"),
"nexmo_send_sms_to_admin_status"=>urlencode("Nexmo Send Sms To admin Status"),
"nexmo_admin_phone_number"=>urlencode("Nexmo Admin Phone Number"),
"save_12_5"=>urlencode("poupe 12,5%"),
"front_tool_tips"=>urlencode("DICAS DE FERRAMENTAS FRONTAIS"),
"front_tool_tips_lower"=>urlencode("Dicas de ferramentas frontais"),
"tool_tip_my_bookings"=>urlencode("Minhas reservas"),
"tool_tip_postal_code"=>urlencode("Código postal"),
"tool_tip_services"=>urlencode("Serviços"),
"tool_tip_extra_service"=>urlencode("Extra service"),
"tool_tip_frequently_discount"=>urlencode("Desconto frequente"),
"tool_tip_when_would_you_like_us_to_come"=>urlencode("Quando você gostaria que viéssemos?"),
"tool_tip_your_personal_details"=>urlencode("Seus dados pessoais"),
"tool_tip_have_a_promocode"=>urlencode("Tem um código promocional"),
"tool_tip_preferred_payment_method"=>urlencode("Método de pagamento preferido"),
"login_page"=>urlencode("Página de login"),
"front_page"=>urlencode("Primeira página"),
"before_e_g_100"=>urlencode("Antes (por exemplo, US $ 100)"),
"after_e_g_100"=>urlencode("Depois (por exemplo, $ 100)"),
"tax_vat"=>urlencode("Imposto / IVA"),
"wrong_url"=>urlencode("URL errada"),
"choose_file"=>urlencode("Escolher arquivo"),
"frontend_labels"=>urlencode("Etiquetas frontend"),
"admin_labels"=>urlencode("Etiquetas de administração"),
"dropdown_design"=>urlencode("DropDown Design"),
"blocks_as_button_design"=>urlencode("Blocos Como Design De Botão"),
"qty_control_design"=>urlencode("Qty Control Design"),
"dropdowns"=>urlencode("DropDowns"),
"big_images_radio"=>urlencode("Rádio de imagens grandes"),
"errors"=>urlencode("Erros"),
"extra_labels"=>urlencode("Etiquetas extras"),
"api_password"=>urlencode("Senha da API"),
"api_username"=>urlencode("Nome de usuário da API"),
"appearance"=>urlencode("APARÊNCIA"),
"action"=>urlencode("Açao"),
"actions"=>urlencode("Ações"),
"add_break"=>urlencode("Adicionar Pausa"),
"add_breaks"=>urlencode("Adicionar pausas"),
"add_cleaning_service"=>urlencode("Adicionar serviço de limpeza"),
"add_method"=>urlencode("Adicionar método"),
"add_new"=>urlencode("Adicionar novo"),
"add_sample_data"=>urlencode("Adicionar dados de amostra"),
"add_unit"=>urlencode("Adicionar unidade"),
"add_your_off_times"=>urlencode("Adicione seus tempos de folga"),
"add_new_off_time"=>urlencode("Adicionar novo tempo de inatividade"),
"add_ons"=>urlencode("Complementos"),
"addons_bookings"=>urlencode("AddOns Bookings"),
"addon_service_front_view"=>urlencode("Visão frontal do Addon-Service"),
"addons"=>urlencode("Addons"),
"service_commission"=>urlencode("Comissão de serviço"),
"commission_total"=>urlencode("Total da Comissão"),
"address"=>urlencode("Endereço"),
"new_appointment_assigned"=>urlencode("Novo compromisso atribuído"),
"admin_email_notifications"=>urlencode("Notificações por email do administrador"),
"all_payment_gateways"=>urlencode("Todos os gateways de pagamento"),
"all_services"=>urlencode("Todos os serviços"),
"allow_multiple_booking_for_same_timeslot"=>urlencode("Permitir várias reservas para o mesmo intervalo de tempo"),
"amount"=>urlencode("Montante"),
"app_date"=>urlencode("Aplicativo. Encontro"),
"appearance_settings"=>urlencode("Configurações de aparência"),
"appointment_completed"=>urlencode("Compromisso concluído"),
"appointment_details"=>urlencode("Detalhes do compromisso"),
"appointment_marked_as_no_show"=>urlencode("Nomeação marcada como não mostrar"),
"mark_as_no_show"=>urlencode("Marcar como não mostrar"),
"appointment_reminder_buffer"=>urlencode("Buffer Lembrete de Compromisso"),
"appointment_auto_confirm"=>urlencode("Compromisso auto confirmar"),
"appointments"=>urlencode("Compromissos"),
"admin_area_color_scheme"=>urlencode("Esquema de cores da área de administração"),
"thankyou_page_url"=>urlencode("Obrigado URL da página"),
"addon_title"=>urlencode("Título Addon"),
"availabilty"=>urlencode("Disponibilidade"),
"background_color"=>urlencode("Cor de fundo"),
"behaviour_on_click_of_button"=>urlencode("Comportamento no clique do botão"),
"book_now"=>urlencode("Livro agora"),
"booking_date_and_time"=>urlencode("Data de reserva e hora"),
"booking_details"=>urlencode("Detalhes da reserva"),
"booking_information"=>urlencode("Informações de reserva"),
"booking_serve_date"=>urlencode("Data de envio da reserva"),
"booking_status"=>urlencode("Status da reserva"),
"booking_notifications"=>urlencode("Notificações de reserva"),
"bookings"=>urlencode("Reservas"),
"button_position"=>urlencode("Posição do botão"),
"button_text"=>urlencode("Botão de texto"),
"company"=>urlencode("EMPRESA"),
"cannot_cancel_now"=>urlencode("Não é possível cancelar agora"),
"cannot_reschedule_now"=>urlencode("Não é possível reprogramar agora"),
"cancel"=>urlencode(" Cancelar"),
"cancellation_buffer_time"=>urlencode("Tempo de buffer de cancelamento"),
"cancelled_by_client"=>urlencode("Cancelado pelo cliente"),
"cancelled_by_service_provider"=>urlencode("Cancelado pelo provedor de serviços"),
"change_password"=>urlencode("Change password"),
"cleaning_service"=>urlencode("Serviço de limpeza"),
"client"=>urlencode("Cliente"),
"client_email_notifications"=>urlencode("Notificações por email do cliente"),
"client_name"=>urlencode("Nome do cliente"),
"color_scheme"=>urlencode("Esquema de cores"),
"color_tag"=>urlencode("Tag de cor"),
"company_address"=>urlencode("Endereço"),
"company_email"=>urlencode("O email"),
"company_logo"=>urlencode("Logotipo da empresa"),
"company_name"=>urlencode("Nome da empresa"),
"company_settings"=>urlencode("Configurações de informações comerciais"),
"companyname"=>urlencode("Nome da empresa"),
"company_info_settings"=>urlencode("Configurações de informações da empresa"),
"completed"=>urlencode("Concluído"),
"confirm"=>urlencode("confirme"),
"confirmed"=>urlencode("Confirmado"),
"contact_status"=>urlencode("Status do contato"),
"country"=>urlencode("País"),
"country_code_phone"=>urlencode("Código do país (telefone)"),
"coupon"=>urlencode("Cupom"),
"coupon_code"=>urlencode("Código do cupom"),
"coupon_limit"=>urlencode("Limite de cupão"),
"coupon_type"=>urlencode("Tipo de cupom"),
"coupon_used"=>urlencode("Cupão Usado"),
"coupon_value"=>urlencode("Valor do Cupão"),
"create_addon_service"=>urlencode("Criar serviço de adição"),
"crop_and_save"=>urlencode("Cortar e Salvar"),
"currency"=>urlencode("Moeda"),
"currency_symbol_position"=>urlencode("Posição do símbolo de moeda"),
"customer"=>urlencode("Cliente"),
"customer_information"=>urlencode("Informação ao Cliente"),
"customers"=>urlencode("clientes"),
"date_and_time"=>urlencode("Data hora"),
"date_picker_date_format"=>urlencode("Data-Picker Date Format"),
"default_design_for_addons"=>urlencode("Design padrão para Addons"),
"default_design_for_methods_with_multiple_units"=>urlencode("Design padrão para métodos com várias unidades"),
"default_design_for_services"=>urlencode("Design padrão para serviços"),
"default_setting"=>urlencode("Configuração padrão"),
"delete"=>urlencode("Excluir"),
"description"=>urlencode("Descrição"),
"discount"=>urlencode("Desconto"),
"download_invoice"=>urlencode("Baixe o Invoice"),
"email_notification"=>urlencode("NOTIFICAÇÃO DE EMAIL"),
"email"=>urlencode("O email"),
"email_settings"=>urlencode("Configurações de email"),
"embed_code"=>urlencode("Código embutido"),
"enter_your_email_and_we_will_send_you_instructions_on_resetting_your_password"=>urlencode("Digite seu e-mail e nós lhe enviaremos instruções sobre como redefinir sua senha."),
"expiry_date"=>urlencode("Data de validade"),
"export"=>urlencode("Exportar"),
"export_your_details"=>urlencode("Exportar seus detalhes"),
"frequently_discount_setting_tabs"=>urlencode("FREQUENTEMENTE DESCONTO"),
"frequently_discount_header"=>urlencode("Desconto frequente"),
"field_is_required"=>urlencode("Campo é obrigatório"),
"file_size"=>urlencode("Tamanho do arquivo"),
"flat_fee"=>urlencode("Taxa fixa"),
"flat"=>urlencode("Plano"),
"freq_discount"=>urlencode("Freq-Discount"),
"frequently_discount_label"=>urlencode("Etiqueta de desconto frequente"),
"frequently_discount_type"=>urlencode("Frequentemente Tipo de Desconto"),
"frequently_discount_value"=>urlencode("Valor de desconto com frequência"),
"front_service_box_view"=>urlencode("Vista frontal da caixa de serviço"),
"front_service_dropdown_view"=>urlencode("Vista suspensa do serviço frontal"),
"front_view_options"=>urlencode("Opções de vista frontal"),
"full_name"=>urlencode("Nome completo"),
"general"=>urlencode("GERAL"),
"general_settings"=>urlencode("Configurações Gerais"),
"get_embed_code_to_show_booking_widget_on_your_website"=>urlencode("Obtenha o código de incorporação para mostrar o widget de reservas em seu site"),
"get_the_embeded_code"=>urlencode("Obtenha o código embeded"),
"guest_customers"=>urlencode("Clientes Convidados"),
"guest_user_checkout"=>urlencode("Checkout de usuário convidado"),
"hide_faded_already_booked_time_slots"=>urlencode("Ocultar espaços de tempo já desbotados"),
"hostname"=>urlencode("nome de anfitrião"),
"labels"=>urlencode("ETIQUETAS"),
"legends"=>urlencode("Legendas"),
"login"=>urlencode("Entrar"),
"maximum_advance_booking_time"=>urlencode("Tempo máximo de reserva antecipada"),
"method"=>urlencode("Método"),
"method_name"=>urlencode("Nome do método"),
"method_title"=>urlencode("Título do Método"),
"method_unit_quantity"=>urlencode("Quantidade de unidades de método"),
"method_unit_quantity_rate"=>urlencode("Taxa de Quantidade por Unidade de Método"),
"method_unit_title"=>urlencode("Título da unidade de método"),
"method_units_front_view"=>urlencode("Unidade de Método Visão Frontal"),
"methods"=>urlencode("Métodos"),
"methods_booking"=>urlencode("Métodos de reserva"),
"methods_bookings"=>urlencode("Métodos Reservas"),
"minimum_advance_booking_time"=>urlencode("Tempo mínimo de reserva antecipada"),
"more"=>urlencode("Mais"),
"more_details"=>urlencode("Mais detalhes"),
"my_appointments"=>urlencode("Minhas nomeações"),
"name"=>urlencode("Nome"),
"net_total"=>urlencode("Total Líquido"),
"new_password"=>urlencode("Nova senha"),
"notes"=>urlencode("Notas"),
"off_days"=>urlencode("Dias de folga"),
"off_time"=>urlencode("Fora do tempo"),
"old_password"=>urlencode("Senha Antiga"),
"online_booking_button_style"=>urlencode("Estilo de botão de reserva on-line"),
"open_widget_in_a_new_page"=>urlencode("Abrir widget em uma nova página"),
"order"=>urlencode("Order"),
"order_date"=>urlencode("Data do pedido"),
"order_time"=>urlencode("Hora do pedido"),
"payments_setting"=>urlencode("FORMA DE PAGAMENTO"),
"promocode"=>urlencode("CÓDIGO PROMOCIONAL"),
"promocode_header"=>urlencode("Código promocional"),
"padding_time_before"=>urlencode("Tempo de preenchimento antes"),
"parking"=>urlencode("Estacionamento"),
"partial_amount"=>urlencode("Montante Parcial"),
"partial_deposit"=>urlencode("Depósito Parcial"),
"partial_deposit_amount"=>urlencode("Montante Parcial de Depósito"),
"partial_deposit_message"=>urlencode("Mensagem de Depósito Parcial"),
"password"=>urlencode("Senha"),
"payment"=>urlencode("Forma de pagamento"),
"payment_date"=>urlencode("Data de pagamento"),
"payment_gateways"=>urlencode("Gateways de pagamento"),
"payment_method"=>urlencode("Método de pagamento"),
"payments"=>urlencode("Pagamentos"),
"payments_history_details"=>urlencode("Detalhes do histórico de pagamentos"),
"paypal_express_checkout"=>urlencode("Paypal Express Checkout"),
"paypal_guest_payment"=>urlencode(" Pagamento de hóspedes Paypal"),
"pending"=>urlencode("Pendente"),
"percentage"=>urlencode("Percentagem"),
"personal_information"=>urlencode("Informação pessoal"),
"phone"=>urlencode("telefone"),
"please_copy_above_code_and_paste_in_your_website"=>urlencode("Por favor, copie o código acima e cole no seu site."),
"please_enable_payment_gateway"=>urlencode("Por favor, ative o gateway de pagamento"),
"please_set_below_values"=>urlencode("Por favor, defina abaixo valores"),
"port"=>urlencode("Porta"),
"postal_codes"=>urlencode("Códigos Postais"),
"price"=>urlencode("Preço"),
"price_calculation_method"=>urlencode("Método de cálculo de preço"),
"price_format_decimal_places"=>urlencode("Formato de preço"),
"pricing"=>urlencode("Preços"),
"primary_color"=>urlencode("Cor primária"),
"privacy_policy_link"=>urlencode("Link da Política de Privacidade"),
"profile"=>urlencode("Perfil"),
"promocodes"=>urlencode("códigos promocionais"),
"promocodes_list"=>urlencode("Lista de promocodes"),
"registered_customers"=>urlencode("Clientes Registrados"),
"registered_customers_bookings"=>urlencode("Reservas de Clientes Registrados"),
"reject"=>urlencode("Rejeitar"),
"rejected"=>urlencode("Rejeitado"),
"remember_me"=>urlencode("Lembre de mim"),
"remove_sample_data"=>urlencode("Remover dados de amostra"),
"reschedule"=>urlencode("Reprogramar"),
"reset"=>urlencode("Restabelecer"),
"reset_password"=>urlencode("Redefinir Senha"),
"reshedule_buffer_time"=>urlencode("Tempo de buffer de reseduação"),
"retype_new_password"=>urlencode("Redigite a nova senha"),
"right_side_description"=>urlencode("Página de Reservas Descrição dos Direitos Autorais"),
"save"=>urlencode("Salve "),
"save_availability"=>urlencode("Salvar disponibilidade"),
"save_setting"=>urlencode("Salvar configuração"),
"save_labels_setting"=>urlencode("Salvar configuração de rótulos"),
"schedule"=>urlencode("Cronograma"),
"schedule_type"=>urlencode("Tipo de agendamento"),
"secondary_color"=>urlencode("Cor secundária"),
"select_language_for_update"=>urlencode("Selecione o idioma para atualização"),
"select_language_to_change_label"=>urlencode("Selecione o idioma para alterar o rótulo"),
"select_language_to_display"=>urlencode("Língua"),
"display_sub_headers_below_headers"=>urlencode("Subtítulos na página de reservas"),
"select_payment_option_export_details"=>urlencode("Selecione detalhes de exportação da opção de pagamento"),
"send_mail"=>urlencode("Enviar email"),
"sender_email_address_cleanto_admin_email"=>urlencode("E-mail do remetente"),
"sender_name"=>urlencode("Sender Name"),
"service"=>urlencode("Serviço"),
"service_add_ons_front_block_view"=>urlencode("Visão de Blocos Frontais de Complementos de Serviços"),
"service_add_ons_front_increase_decrease_view"=>urlencode("Adicionais de serviço Front Increase / Decrease View"),
"service_description"=>urlencode("Descrição do Serviço"),
"service_front_view"=>urlencode("Vista frontal do serviço"),
"service_image"=>urlencode("Imagem de serviço"),
"service_methods"=>urlencode("Métodos de serviço"),
"service_padding_time_after"=>urlencode("Tempo de preenchimento de serviço após"),
"padding_time_after"=>urlencode("Tempo de preenchimento após"),
"service_padding_time_before"=>urlencode("Tempo de preenchimento de serviço antes"),
"service_quantity"=>urlencode("Quantidade de serviço"),
"service_rate"=>urlencode("Taxa de serviço"),
"service_title"=>urlencode("Título de serviço"),
"serviceaddons_name"=>urlencode("ServiceAddOns Name"),
"services"=>urlencode("Serviços"),
"services_information"=>urlencode("Informações sobre serviços"),
"set_email_reminder_buffer"=>urlencode("Definir buffer de lembrete de email"),
"set_language"=>urlencode("Definir idioma"),
"settings"=>urlencode("Configurações"),
"show_all_bookings"=>urlencode("Mostrar todas as reservas"),
"show_button_on_given_embeded_position"=>urlencode("Mostrar botão na posição embebida"),
"show_coupons_input_on_checkout"=>urlencode("Mostrar cupões de entrada no checkout"),
"show_on_a_button_click"=>urlencode("Mostrar em um clique de botão"),
"show_on_page_load"=>urlencode("Mostrar no carregamento da página"),
"signature"=>urlencode("Assinatura"),
"sorry_wrong_email_or_password"=>urlencode("Desculpe E-mail ou Senha Errada"),
"start_date"=>urlencode("Data de início"),
"status"=>urlencode("Status"),
"submit"=>urlencode("Enviar"),
"staff_email_notification"=>urlencode("Notificação de e-mail da equipe"),
"tax"=>urlencode("Imposto"),
"test_mode"=>urlencode("Modo de teste"),
"text_color"=>urlencode("Cor do texto"),
"text_color_on_bg"=>urlencode("Cor do texto em bg"),
"terms_and_condition_link"=>urlencode("Termos e Condição Link"),
"this_week_breaks"=>urlencode("Esta semana quebra"),
"this_week_time_scheduling"=>urlencode("Esta semana agendamento de horário"),
"time_format"=>urlencode("Formato da hora"),
"time_interval"=>urlencode("Intervalo de tempo"),
"timezone"=>urlencode("Fuso horário"),
"units"=>urlencode("Unidades"),
"unit_name"=>urlencode("Nome da unidade"),
"units_of_methods"=>urlencode("Unidades de métodos"),
"update"=>urlencode("Atualizar"),
"update_appointment"=>urlencode("Atualizar compromisso"),
"update_promocode"=>urlencode("Atualizar Promocode"),
"username"=>urlencode("Nome de usuário"),
"vaccum_cleaner"=>urlencode("Aspirador de pó"),
"view_slots_by"=>urlencode("Exibir slots por?"),
"week"=>urlencode("Semana"),
"week_breaks"=>urlencode("Semana Breaks"),
"week_time_scheduling"=>urlencode("Agendamento de horário semanal"),
"widget_loading_style"=>urlencode("Agendamento de horário semanal"),
"zip"=>urlencode("Fecho eclair"),
"logout"=>urlencode("sair"),
"to"=>urlencode("para"),
"add_new_promocode"=>urlencode("Adicionar novo Promocode"),
"create"=>urlencode("Crio"),
"end_date"=>urlencode("Data final"),
"end_time"=>urlencode("Fim do tempo"),
"labels_settings"=>urlencode("Configurações de etiquetas"),
"limit"=>urlencode("limite"),
"max_limit"=>urlencode("Limite máximo"),
"start_time"=>urlencode("Hora de início"),
"value"=>urlencode("Valor"),
"active"=>urlencode("Ativo"),
"appointment_reject_reason"=>urlencode("Razão de Rejeição do Compromisso"),
"search"=>urlencode("Pesquisa"),
"custom_thankyou_page_url"=>urlencode("URL de página personalizada do Thankyou"),
"price_per_unit"=>urlencode("Preço por unidade"),
"confirm_appointment"=>urlencode("Confirmar compromisso"),
"reject_reason"=>urlencode("Rejeitar Razão"),
"delete_this_appointment"=>urlencode("Excluir este compromisso"),
"close_notifications"=>urlencode("Fechar notificações"),
"booking_cancel_reason"=>urlencode("Razão de cancelamento da reserva"),
"service_color_badge"=>urlencode("Distintivo de cor de serviço"),
"manage_price_calculation_methods"=>urlencode("Gerenciar métodos de cálculo de preço"),
"manage_addons_of_this_service"=>urlencode("Gerenciar complementos deste serviço"),
"service_is_booked"=>urlencode("O serviço está reservado"),
"delete_this_service"=>urlencode("O serviço está reservado"),
"delete_service"=>urlencode("Excluir serviço"),
"remove_image"=>urlencode("Remover imagem"),
"remove_service_image"=>urlencode("Remover imagem de serviço"),
"company_name_is_used_for_invoice_purpose"=>urlencode("O nome da empresa é usado para fins de fatura"),
"remove_company_logo"=>urlencode("Remover logotipo da empresa"),
"time_interval_is_helpful_to_show_time_difference_between_availability_time_slots"=>urlencode("O intervalo de tempo é útil para mostrar a diferença de horário entre os intervalos de tempo de disponibilidade"),
"minimum_advance_booking_time_restrict_client_to_book_last_minute_booking_so_that_you_should_have_sufficient_time_before_appointment"=>urlencode("O tempo mínimo de reserva antecipada restringe o cliente a reservar a reserva de última hora, para que você tenha tempo suficiente antes da consulta"),
"cancellation_buffer_helps_service_providers_to_avoid_last_minute_cancellation_by_their_clients"=>urlencode("O buffer de cancelamento ajuda os provedores de serviços a evitar o cancelamento de última hora por seus clientes"),
"partial_payment_option_will_help_you_to_charge_partial_payment_of_total_amount_from_client_and_remaining_you_can_collect_locally"=>urlencode("Opção de pagamento parcial irá ajudá-lo a cobrar o pagamento parcial do montante total do cliente e restante você pode coletar localmente"),
"allow_multiple_appointment_booking_at_same_time_slot_will_allow_you_to_show_availability_time_slot_even_you_have_booking_already_for_that_time"=>urlencode("Permitir a reserva de vários compromissos no mesmo horário, permitirá que você mostre o horário de disponibilidade, mesmo que você já tenha reserva para esse horário"),
"with_Enable_of_this_feature_Appointment_request_from_clients_will_be_auto_confirmed"=>urlencode("Com a ativação deste recurso, a solicitação de compromisso dos clientes será confirmada automaticamente"),
"write_html_code_for_the_right_side_panel"=>urlencode("Escrever código HTML para o painel do lado direito"),
"do_you_want_to_show_subheaders_below_the_headers"=>urlencode("Você quer mostrar subtítulos abaixo dos cabeçalhos"),
"you_can_show_hide_coupon_input_on_checkout_form"=>urlencode("Você pode mostrar / ocultar a entrada de cupom no formulário de checkout"),
"with_this_feature_you_can_allow_a_visitor_to_book_appointment_without_registration"=>urlencode("Com esse recurso, você pode permitir que um visitante agende um compromisso sem registro"),
"paypal_api_username_can_get_easily_from_developer_paypal_com_account"=>urlencode("O nome de usuário da API do Paypal pode ser acessado facilmente na conta developer.paypal.com"),
"paypal_api_password_can_get_easily_from_developer_paypal_com_account"=>urlencode("A senha da API do Paypal pode ser acessada facilmente na conta developer.paypal.com"),
"paypal_api_signature_can_get_easily_from_developer_paypal_com_account"=>urlencode("A assinatura da API do Paypal pode ser acessada facilmente na conta developer.paypal.com"),
"let_user_pay_through_credit_card_without_having_paypal_account"=>urlencode("Deixe o usuário pagar com cartão de crédito sem ter conta no Paypal"),
"you_can_enable_paypal_test_mode_for_sandbox_account_testing"=>urlencode("Você pode ativar o modo de teste do Paypal para testes de conta de sandbox"),
"you_can_enable_authorize_net_test_mode_for_sandbox_account_testing"=>urlencode("Você pode ativar o modo de teste Authorize.Net para testes de conta de sandbox"),
"edit_coupon_code"=>urlencode("Editar código de cupom"),
"delete_promocode"=>urlencode("Excluir Promocode?"),
"coupon_code_will_work_for_such_limit"=>urlencode("O código do cupom funcionará para esse limite"),
"coupon_code_will_work_for_such_date"=>urlencode("O código de cupom funcionará para essa data"),
"coupon_value_would_be_consider_as_percentage_in_percentage_mode_and_in_flat_mode_it_will_be_consider_as_amount_no_need_to_add_percentage_sign_it_will_auto_added"=>urlencode("O valor do cupão seria considerado como percentagem no modo de percentagem e no modo simples será considerado como valor. Não é necessário adicionar um sinal de percentagem que será adicionado automaticamente."),
"unit_is_booked"=>urlencode("A unidade está registrada"),
"delete_this_service_unit"=>urlencode("Excluir esta unidade de serviço?"),
"delete_service_unit"=>urlencode("Excluir unidade de serviço"),
"manage_unit_price"=>urlencode("Gerenciar preço unitário"),
"extra_service_title"=>urlencode("Título de serviço extra"),
"addon_is_booked"=>urlencode("Addon está reservado"),
"delete_this_addon_service"=>urlencode("Excluir este serviço de addon?"),
"choose_your_addon_image"=>urlencode("Escolha sua imagem addon"),
"addon_image"=>urlencode("Addon Image"),
"administrator_email"=>urlencode("E-mail do administrador"),
"admin_profile_address"=>urlencode("Endereço"),
"default_country_code"=>urlencode("Código do país"),
"cancellation_policy"=>urlencode("Política de cancelamento"),
"transaction_id"=>urlencode("Política de cancelamento"),
"sms_reminder"=>urlencode("Lembrete de SMS"),
"save_sms_settings"=>urlencode("Salvar configurações de SMS"),
"sms_service"=>urlencode("Serviço SMS"),
"it_will_send_sms_to_service_provider_and_client_for_appointment_booking"=>urlencode("Ele irá enviar sms para provedor de serviços e cliente para reserva de compromisso"),
"twilio_account_settings"=>urlencode("Configurações da conta do Twilio"),
"plivo_account_settings"=>urlencode("Configurações da conta do Plivo"),
"account_sid"=>urlencode("SID da conta"),
"auth_token"=>urlencode("Token de Autenticação"),
"twilio_sender_number"=>urlencode("Twilio Sender Number"),
"plivo_sender_number"=>urlencode("Número do Remetente Plivo"),
"twilio_sms_settings"=>urlencode("Twilio SMS Settings"),
"plivo_sms_settings"=>urlencode("Configurações do Plivo SMS"),
"twilio_sms_gateway"=>urlencode("Twilio SMS Gateway"),
"plivo_sms_gateway"=>urlencode("Gateway SMS Plivo"),
"send_sms_to_client"=>urlencode("Envie SMS para o cliente"),
"send_sms_to_admin"=>urlencode("Enviar SMS para o administrador"),
"admin_phone_number"=>urlencode("Número de telefone do administrador"),
"available_from_within_your_twilio_account"=>urlencode("Disponível a partir da sua conta do Twilio."),
"must_be_a_valid_number_associated_with_your_twilio_account"=>urlencode("Deve ser um número válido associado à sua conta do Twilio."),
"enable_or_disable_send_sms_to_client_for_appointment_booking_info"=>urlencode("Ativar ou desativar, enviar SMS para o cliente para informações de reserva de compromisso."),
"enable_or_disable_send_sms_to_admin_for_appointment_booking_info"=>urlencode("Habilitar ou desabilitar, enviar SMS para admin para informações de reserva de compromisso."),
"updated_sms_settings"=>urlencode("Configurações SMS atualizadas"),
"parking_availability_frontend_option_display_status"=>urlencode("Estacionamento"),
"vaccum_cleaner_frontend_option_display_status"=>urlencode("Aspirador de pó"),
"o_n"=>urlencode("Em"),
"off"=>urlencode("Fora"),
"enable"=>urlencode("Habilitar"),
"disable"=>urlencode("Desabilitar"),
"monthly"=>urlencode("Por mês"),
"weekly"=>urlencode("Semanal"),
"email_template"=>urlencode("EMAIL MOLDE"),
"sms_notification"=>urlencode("NOTIFICAÇÃO SMS"),
"sms_template"=>urlencode("SMS MODELO"),
"email_template_settings"=>urlencode("Configurações de modelo de email"),
"client_email_templates"=>urlencode("Modelo de email do cliente"),
"client_sms_templates"=>urlencode("Modelo de SMS do cliente"),
"admin_email_template"=>urlencode("Modelo de e-mail de administração"),
"admin_sms_template"=>urlencode("Modelo de SMS do administrador"),
"tags"=>urlencode("Tag"),
"booking_date"=>urlencode("booking_date"),
"service_name"=>urlencode("Nome do Serviço"),
"business_logo"=>urlencode("business_logo"),
"business_logo_alt"=>urlencode("business_logo_alt"),
"admin_name"=>urlencode("admin_name"),
"methodname"=>urlencode("method_name"),
"firstname"=>urlencode("primeiro nome"),
"lastname"=>urlencode("último nome"),
"client_email"=>urlencode("client_email"),
"vaccum_cleaner_status"=>urlencode("vaccum_cleaner_status"),
"parking_status"=>urlencode("parking_status"),
"app_remain_time"=>urlencode("app_remain_time"),
"reject_status"=>urlencode("reject_status"),
"save_template"=>urlencode("Salvar modelo"),
"default_template"=>urlencode("Modelo Padrão"),
"sms_template_settings"=>urlencode("Configurações de modelo de SMS"),
"secret_key"=>urlencode("Chave secreta"),
"publishable_key"=>urlencode("Publicação de Chave"),
"payment_form"=>urlencode("Formulário de pagamento"),
"api_login_id"=>urlencode("ID de login da API"),
"transaction_key"=>urlencode("Chave de Transação"),
"sandbox_mode"=>urlencode("modo caixa de areia"),
"available_from_within_your_plivo_account"=>urlencode("Disponível a partir da sua conta Plivo."),
"must_be_a_valid_number_associated_with_your_plivo_account"=>urlencode("Deve ser um número válido associado à sua conta do Plivo."),
"whats_new"=>urlencode("O que há de novo?."),
"company_phone"=>urlencode("telefone"),
"company__name"=>urlencode("Nome da empresa"),
"booking_time"=>urlencode("booking_time"),
"company__email"=>urlencode("company_email"),
"company__address"=>urlencode("Endereço da companhia"),
"company__zip"=>urlencode("company_zip"),
"company__phone"=>urlencode("company_phone"),
"company__state"=>urlencode("company_state"),
"company__country"=>urlencode("company_country"),
"company__city"=>urlencode("company_city"),
"page_title"=>urlencode("Título da página"),
"client__zip"=>urlencode("client_zip"),
"client__state"=>urlencode("Estado do cliente"),
"client__city"=>urlencode("client_city"),
"client__address"=>urlencode("client_address"),
"client__phone"=>urlencode("client_phone"),
"company_logo_is_used_for_invoice_purpose"=>urlencode("Logo da empresa se acostumar em e-mail e página de reserva"),
"private_key"=>urlencode("Chave privada"),
"seller_id"=>urlencode("ID do vendedor"),
"postal_codes_ed"=>urlencode("Você pode ativar ou desativar o recurso CEP ou CEP de acordo com os requisitos do seu país, pois alguns países, como os EAU, não têm código postal."),
"postal_codes_info"=>urlencode("Você pode mencionar os códigos postais de duas maneiras: # 1. Você pode mencionar códigos postais completos para correspondência como K1A232, L2A334, C3A4C4. Você pode usar códigos postais parciais para entradas de correspondência de curingas, por exemplo, O sistema K1A, L2A, C3 corresponderá às letras iniciais do código postal na frente e evitará que você escreva tantos códigos postais."),
"first"=>urlencode("Primeiro"),
"second"=>urlencode("Segundo"),
"third"=>urlencode("Terceiro"),
"fourth"=>urlencode("Quarto"),
"fifth"=>urlencode("Quinto"),
"first_week"=>urlencode("Primeira semana"),
"second_week"=>urlencode("Segunda semana"),
"third_week"=>urlencode("Terceira semana"),
"fourth_week"=>urlencode("Quarta semana"),
"fifth_week"=>urlencode("Quinta semana"),
"this_week"=>urlencode("Esta semana"),
"monday"=>urlencode("Segunda-feira"),
"tuesday"=>urlencode("terça"),
"wednesday"=>urlencode("Quarta-feira"),
"thursday"=>urlencode("Thursday"),
"friday"=>urlencode("Sexta-feira"),
"saturday"=>urlencode("sábado"),
"sunday"=>urlencode("domingo"),
"appointment_request"=>urlencode("Marcação de um compromisso"),
"appointment_approved"=>urlencode("Nomeação aprovada"),
"appointment_rejected"=>urlencode("Nomeação rejeitada"),
"appointment_cancelled_by_you"=>urlencode("Compromisso Cancelado por você"),
"appointment_rescheduled_by_you"=>urlencode("Nomeação Remarcada por você"),
"client_appointment_reminder"=>urlencode("Lembrete de compromisso do cliente"),
"new_appointment_request_requires_approval"=>urlencode("Solicitação de novo compromisso requer aprovação"),
"appointment_cancelled_by_customer"=>urlencode("Compromisso cancelado pelo cliente"),
"appointment_rescheduled_by_customer"=>urlencode("Nomeação reprogramada pelo cliente"),
"admin_appointment_reminder"=>urlencode("Lembrete de compromisso de administrador"),
"off_days_added_successfully"=>urlencode("Off Days adicionados com sucesso"),
"off_days_deleted_successfully"=>urlencode("Dias Desligados Excluídos com Sucesso"),
"sorry_not_available"=>urlencode("Desculpe não disponível"),
"success"=>urlencode("Sucesso"),
"failed"=>urlencode("Falhou"),
"once"=>urlencode("Uma vez"),
"Bi_Monthly"=>urlencode("Bi-mensal"),
"Fortnightly"=>urlencode("Quinzenal"),
"Recurrence_Type"=>urlencode("Tipo de recorrência"),
"bi_weekly"=>urlencode("Quinzenal"),
"Daily"=>urlencode("Diariamente"),
"guest_customers_bookings"=>urlencode("Reservas de Clientes Convidados"),
"existing_and_new_user_checkout"=>urlencode("Check-out de usuário existente e novo"),
"it_will_allow_option_for_user_to_get_booking_with_new_user_or_existing_user"=>urlencode("Isso permitirá que a opção de usuário seja reservada com um novo usuário ou usuário existente"),
"0_1"=>urlencode("01"),
"1_1"=>urlencode("1,1"),
"1_2"=>urlencode("1,2"),
"0_2"=>urlencode("02"),
"free"=>urlencode("Livre"),
"show_company_address_in_header"=>urlencode("Mostrar endereço da empresa no cabeçalho"),
"calendar_week"=>urlencode("Semana"),
"calendar_month"=>urlencode("Mês"),
"calendar_day"=>urlencode("Dia"),
"calendar_today"=>urlencode("Hoje"),
"restore_default"=>urlencode("Restaurar padrão"),
"scrollable_cart"=>urlencode("Carrinho Scrollable"),
"merchant_key"=>urlencode("Chave do comerciante"),
"salt_key"=>urlencode("Chave de sal"),
"textlocal_sms_gateway"=>urlencode("Gateway SMS Textlocal"),
"textlocal_sms_settings"=>urlencode("Configurações de SMS Textlocal"),
"textlocal_account_settings"=>urlencode("Configurações de conta do Textlocal"),
"account_username"=>urlencode("Nome de usuário da conta"),
"account_hash_id"=>urlencode("ID do hash da conta"),
"email_id_registered_with_you_textlocal"=>urlencode("Forneça seu email registrado com textlocal"),
"hash_id_provided_by_textlocal"=>urlencode("Hash id fornecido por textlocal"),
"bank_transfer"=>urlencode("Transferência bancária"),
"bank_name"=>urlencode("Nome do banco"),
"account_name"=>urlencode("Nome da conta"),
"account_number"=>urlencode("Número da conta"),
"branch_code"=>urlencode("Branch Code"),
"ifsc_code"=>urlencode("Código IFSC"),
"bank_description"=>urlencode("Banco Descrição"),
"your_cart_items"=>urlencode("Seus itens do carrinho"),
"show_how_will_we_get_in"=>urlencode("Show Como vamos entrar"),
"show_description"=>urlencode("Mostre a descrição"),
"bank_details"=>urlencode("Detalhes bancários"),
"ok_remove_sample_data"=>urlencode("Está bem"),
"book_appointment"=>urlencode("Anotação de livro"),
"remove_sample_data_message"=>urlencode("Você está tentando remover dados de amostra. Se você remover dados de amostra, sua reserva relacionada aos serviços de amostra será excluída permanentemente. Para prosseguir, por favor clique em 'OK'"),
"recommended_image_type_jpg_jpeg_png_gif"=>urlencode("(Tipo de imagem recomendada jpg, jpeg, png, gif)"),
"authetication"=>urlencode("Autenticação"),
"encryption_type"=>urlencode("Tipo de encriptação"),
"plain"=>urlencode("Avião"),
"true"=>urlencode("Verdade"),
"false"=>urlencode("Falso"),
"change_calculation_policy"=>urlencode("Alterar cálculo"),
"multiply"=>urlencode("Multiplicar"),
"equal"=>urlencode("Igual"),
"warning"=>urlencode("Atenção!"),
"field_name"=>urlencode("Nome do campo"),
"enable_disable"=>urlencode("Habilitar desabilitar"),
"required"=>urlencode("Requeridos"),
"min_length"=>urlencode("Comprimento Mínimo"),
"max_length"=>urlencode("Comprimento máximo"),
"appointment_details_section"=>urlencode("Seção de detalhes do compromisso"),
"if_you_are_having_booking_system_which_need_the_booking_address_then_please_make_this_field_enable_or_else_it_will_not_able_to_take_the_booking_address_and_display_blank_address_in_the_booking"=>urlencode("Se você está tendo o sistema de reserva que precisa do endereço da reserva, por favor, ative este campo ou então não será possível obter o endereço de reserva e exibir o endereço em branco na reserva."),
"front_language_dropdown"=>urlencode("Dropdown de Idioma Frontal"),
"enabled"=>urlencode("ativado"),
"vaccume_cleaner"=>urlencode("Aspirador de pó"),
"staff_members"=>urlencode("Membros do pessoal"),
"add_new_staff_member"=>urlencode("Adicionar novo membro da equipe"),
"role"=>urlencode("Função"),
"staff"=>urlencode("Funcionários"),
"admin"=>urlencode("Admin"),
"service_details"=>urlencode("Detalhes do serviço"),
"technical_admin"=>urlencode("Administrador Técnico"),
"enable_booking"=>urlencode("Ativar reserva"),
"flat_commission"=>urlencode("Comissão Plana"),
"manageable_form_fields_front_booking_form"=>urlencode("Campos de formulário gerenciáveis ​​para formulário de reserva de frente"),
"manageable_form_fields"=>urlencode("Campos de Formulário Gerenciáveis"),
"sms"=>urlencode("SMS"),
"crm"=>urlencode("CRM"),
"message"=>urlencode("mensagem"),
"send_message"=>urlencode("Enviar mensagem"),
"all_messages"=>urlencode("Todas as mensagens"),
"subject"=>urlencode("Sujeito"),
"add_attachment"=>urlencode("Juntar anexo"),
"send"=>urlencode("Enviar"),
"close"=>urlencode("Fechar"),
"delete_this_customer?"=>urlencode("Excluir este cliente?"),
"yes"=>urlencode("sim"),
"add_new_customer"=>urlencode("Adicionar novo cliente"),
"attachment"=>urlencode("anexo"),
"date"=>urlencode("encontro"),
"see_attachment"=>urlencode("Ver anexo"),
"no_attachment"=>urlencode("Nenhum anexo"),
"ct_special_offer"=>urlencode("Oferta especial"),
"ct_special_offer_text"=>urlencode("Oferta especial Texto"),
);

$error_labels_pt_PT = array (
"language_status_change_successfully"=>urlencode("Mudança de Status de Idioma com Sucesso"),
"commission_amount_should_not_be_greater_then_order_amount"=>urlencode("O valor da comissão não deve ser maior que o valor do pedido"),
"please_enter_merchant_ID"=>urlencode("Por favor, digite o ID do comerciante"),
"please_enter_secure_key"=>urlencode("Por favor, insira a chave segura"),
"please_enter_google_calender_admin_url"=>urlencode("Por favor, insira o URL de administrador do google calendar"),
"please_enter_google_calender_frontend_url"=>urlencode("Por favor, insira o url do frontend do google calendar"),
"please_enter_google_calender_client_secret"=>urlencode("Por favor, insira o segredo do cliente google calender"),
"please_enter_google_calender_client_ID"=>urlencode("Insira o ID do cliente do Google Agenda"),
"please_enter_google_calender_ID"=>urlencode("Por favor insira o google calendar ID"),
"you_cannot_book_on_past_date"=>urlencode("Você não pode reservar na data passada"),
"Invalid_Image_Type"=>urlencode("Tipo de imagem inválido"),
"seo_settings_updated_successfully"=>urlencode("Configurações de SEO atualizadas com sucesso"),
"customer_deleted_successfully"=>urlencode("Cliente excluído com sucesso"),
"please_enter_below_36_characters"=>urlencode("Por favor, insira abaixo de 36 caracteres"),
"are_you_sure_you_want_to_delete_client"=>urlencode("Tem certeza de que deseja excluir o cliente?"),
"please_select_atleast_one_unit"=>urlencode("Por favor selecione pelo menos uma unidade"),
"atleast_one_payment_method_should_be_enable"=>urlencode("Pelo menos um método de pagamento deve ser ativado"),
"appointment_booking_confirm"=>urlencode("Reserva de compromisso confirmar"),
"appointment_booking_rejected"=>urlencode("Reserva de compromisso rejeitada"),
"booking_cancel"=>urlencode("Boooking Cancelado"),
"appointment_marked_as_no_show"=>urlencode("Compromisso marcado como não comparecimento"),
"appointment_reschedules_successfully"=>urlencode("Nomeação reprograma com sucesso"),
"booking_deleted"=>urlencode("Reserva excluída"),
"break_end_time_should_be_greater_than_start_time"=>urlencode("Hora de término da pausa deve ser maior que a hora de início"),
"cancel_by_client"=>urlencode("Cancelar pelo cliente"),
"cancelled_by_service_provider"=>urlencode("Cancelado pelo provedor de serviços"),
"design_set_successfully"=>urlencode("Design definido com sucesso"),
"end_break_time_updated"=>urlencode("Tempo de pausa final atualizado"),
"enter_alphabets_only"=>urlencode("Digite apenas alfabetos"),
"enter_only_alphabets"=>urlencode("Digite apenas alfabetos"),
"enter_only_alphabets_numbers"=>urlencode("Digite apenas Alfabetos / Números"),
"enter_only_digits"=>urlencode("Digite apenas dígitos"),
"enter_valid_url"=>urlencode("Insira um URL válido"),
"enter_only_numeric"=>urlencode("Digite apenas numérico"),
"enter_proper_country_code"=>urlencode("Digite o código do país adequado"),
"frequently_discount_status_updated"=>urlencode("Status de desconto frequente atualizado"),
"frequently_discount_updated"=>urlencode("Desconto com frequência atualizado"),
"manage_addons_service"=>urlencode("Gerenciar o serviço addons"),
"maximum_file_upload_size_2_mb"=>urlencode("Tamanho máximo de upload de arquivo de 2 MB"),
"method_deleted_successfully"=>urlencode("Método excluído com sucesso"),
"method_inserted_successfully"=>urlencode("Método inserido com sucesso"),
"minimum_file_upload_size_1_kb"=>urlencode("Tamanho mínimo de upload de arquivo de 1 KB"),
"off_time_added_successfully"=>urlencode("Tempo de inatividade adicionado com sucesso"),
"only_jpeg_png_and_gif_images_allowed"=>urlencode("Apenas imagens jpeg, png e gif permitidas"),
"only_jpeg_png_gif_zip_and_pdf_allowed"=>urlencode("Apenas jpeg, png, gif, zip e pdf Permitido"),
"please_wait_while_we_send_all_your_message"=>urlencode("Por favor aguarde enquanto enviamos todas as suas mensagens"),
"please_enable_email_to_client"=>urlencode("Por favor, habilite e-mails para o cliente."),
"please_enable_sms_gateway"=>urlencode("Por favor habilite o SMS Gateway."),
"please_enable_client_notification"=>urlencode("Por favor, ative a notificação do cliente."),
"password_must_be_8_character_long"=>urlencode("A senha deve ter 8 caracteres"),
"password_should_not_exist_more_then_20_characters"=>urlencode("A senha não deve existir com mais de 20 caracteres"),
"please_assign_base_price_for_unit"=>urlencode("Por favor, atribua preço base por unidade"),
"please_assign_price"=>urlencode("Por favor, atribua preço"),
"please_assign_qty"=>urlencode("Por favor, atribua quantidade"),
"please_enter_api_password"=>urlencode("Por favor, atribua quantidade"),
"please_enter_api_username"=>urlencode("Por favor, insira o nome de usuário da API"),
"please_enter_color_code"=>urlencode("Por favor, insira o código de cores"),
"please_enter_country"=>urlencode("Por favor, insira o código de cores"),
"please_enter_coupon_limit"=>urlencode("Por favor insira limite de cupão"),
"please_enter_coupon_value"=>urlencode("Por favor, insira o valor do cupom"),
"please_enter_coupon_code"=>urlencode("Por favor insira o código do cupom"),
"please_enter_email"=>urlencode("Por favor insira o email"),
"please_enter_fullname"=>urlencode("Por favor, insira Fullname"),
"please_enter_maxlimit"=>urlencode("Por favor, insira maxLimit"),
"please_enter_method_title"=>urlencode("Por favor insira o título do método"),
"please_enter_name"=>urlencode("Por favor, insira o nome"),
"please_enter_only_numeric"=>urlencode("Por favor, digite apenas numérico"),
"please_enter_proper_base_price"=>urlencode("Por favor, insira o preço base adequado"),
"please_enter_proper_name"=>urlencode("Por favor digite o nome correto"),
"please_enter_proper_title"=>urlencode("Por favor, insira o título adequado"),
"please_enter_publishable_key"=>urlencode("Por favor, digite a chave de publicação"),
"please_enter_secret_key"=>urlencode("Por favor, digite a chave secreta"),
"please_enter_service_title"=>urlencode("Por favor insira o título do serviço"),
"please_enter_signature"=>urlencode("Por favor insira assinatura"),
"please_enter_some_qty"=>urlencode("Por favor insira um pouco"),
"please_enter_title"=>urlencode("Por favor insira o título"),
"please_enter_unit_title"=>urlencode("Por favor, insira o título da unidade"),
"please_enter_valid_country_code"=>urlencode("Por favor insira o código do país válido"),
"please_enter_valid_service_title"=>urlencode("Por favor, insira um título de serviço válido"),
"please_enter_valid_price"=>urlencode("Por favor insira um preço válido"),
"please_enter_zipcode"=>urlencode("Por favor introduza o código postal"),
"please_enter_state"=>urlencode("Por favor, insira o estado"),
"please_retype_correct_password"=>urlencode("Por favor, redigite a senha correta"),
"please_select_porper_time_slots"=>urlencode("Por favor selecione time slots de tempo"),
"please_select_time_between_day_availability_time"=>urlencode("Por favor, selecione o tempo entre o tempo de disponibilidade do dia"),
"please_valid_value_for_discount"=>urlencode("Por favor, valor válido para desconto"),
"please_enter_confirm_password"=>urlencode("Por favor, insira a senha de confirmação"),
"please_enter_new_password"=>urlencode("Por favor insira a nova senha"),
"please_enter_old_password"=>urlencode("Por favor insira a senha antiga"),
"please_enter_valid_number"=>urlencode("Por favor insira um número válido"),
"please_enter_valid_number_with_country_code"=>urlencode("Por favor, insira um número válido com o código do país"),
"please_select_end_time_greater_than_start_time"=>urlencode("Por favor, selecione a hora de término maior que a hora de início"),
"please_select_end_time_less_than_start_time"=>urlencode("Por favor, selecione a hora de término menos do que a hora de início"),
"please_select_a_crop_region_and_then_press_upload"=>urlencode("Por favor, selecione uma região de cultura e, em seguida, pressione"),
"please_select_a_valid_image_file_jpg_and_png_are_allowed"=>urlencode("Por favor, selecione um arquivo de imagem válido jpg e png são permitidos"),
"profile_updated_successfully"=>urlencode("Perfil atualizado com sucesso"),
"qty_rule_deleted"=>urlencode("Regra de quantidade excluída"),
"record_deleted_successfully"=>urlencode("Registro excluído com sucesso"),
"record_updated_successfully"=>urlencode("Registro atualizado com sucesso"),
"rescheduled"=>urlencode("Remarcado"),
"schedule_updated_to_monthly"=>urlencode("Agendamento atualizado para mensal"),
"schedule_updated_to_weekly"=>urlencode("Agendamento atualizado para semanalmente"),
"sorry_method_already_exist"=>urlencode("Desculpe método já existe"),
"sorry_no_notification"=>urlencode("Desculpe, você não tem nenhum compromisso próximo"),
"sorry_promocode_already_exist"=>urlencode("Desculpe, o promocode já existe"),
"sorry_unit_already_exist"=>urlencode("Unidade de desculpa já existe"),
"sorry_we_are_not_available"=>urlencode("Desculpe, não estamos disponíveis"),
"start_break_time_updated"=>urlencode("Tempo de pausa de início atualizado"),
"status_updated"=>urlencode("Status atualizado"),
"time_slots_updated_successfully"=>urlencode("Time slots atualizados com sucesso"),
"unit_inserted_successfully"=>urlencode("Unidade inserida com sucesso"),
"units_status_updated"=>urlencode("Status das unidades atualizado"),
"updated_appearance_settings"=>urlencode("Configurações de aparência atualizadas"),
"updated_company_details"=>urlencode("Detalhes da empresa atualizados"),
"updated_email_settings"=>urlencode("Configurações de email atualizadas"),
"updated_general_settings"=>urlencode("Configurações gerais atualizadas"),
"updated_payments_settings"=>urlencode("Configurações de pagamentos atualizados"),
"your_old_password_incorrect"=>urlencode("Senha antiga incorreta"),
"please_enter_minimum_5_chars"=>urlencode("Por favor, insira no mínimo 5 caracteres"),
"please_enter_maximum_10_chars"=>urlencode("Por favor, insira no máximo 10 caracteres"),
"please_enter_postal_code"=>urlencode("Por favor insira o código postal"),
"please_select_a_service"=>urlencode("Por favor selecione um serviço"),
"please_select_units_and_addons"=>urlencode("Por favor selecione unidades e addons"),
"please_select_units_or_addons"=>urlencode("Por favor, selecione unidades ou addons"),
"please_login_to_complete_booking"=>urlencode("Por favor, faça o login para completar a reserva"),
"please_select_appointment_date"=>urlencode("Por favor selecione data de nomeação"),
"please_accept_terms_and_conditions"=>urlencode("Por favor aceite os termos e condições"),
"incorrect_email_address_or_password"=>urlencode("Endereço de email ou senha incorretos"),
"please_enter_valid_email_address"=>urlencode("Por favor insira o endereço de e-mail válido"),
"please_enter_email_address"=>urlencode("Por favor insira o endereço de email"),
"please_enter_password"=>urlencode("Por favor insira a senha"),
"please_enter_minimum_8_characters"=>urlencode("Por favor, insira no mínimo 8 caracteres"),
"please_enter_maximum_15_characters"=>urlencode("Por favor, insira no máximo 15 caracteres"),
"please_enter_first_name"=>urlencode("Por favor, insira o primeiro nome"),
"please_enter_only_alphabets"=>urlencode("Please enter only alphabets"),
"please_enter_minimum_2_characters"=>urlencode("Por favor, insira no mínimo 2 caracteres"),
"please_enter_last_name"=>urlencode("Por favor, insira o sobrenome"),
"email_already_exists"=>urlencode("e-mail já existe"),
"please_enter_phone_number"=>urlencode("Por favor insira o número de telefone"),
"please_enter_only_numerics"=>urlencode("Por favor, digite apenas numéricos"),
"please_enter_minimum_10_digits"=>urlencode("Por favor, insira no mínimo 10 dígitos"),
"please_enter_maximum_14_digits"=>urlencode("Por favor, insira no máximo 14 dígitos"),
"please_enter_address"=>urlencode("Por favor insira o endereço"),
"please_enter_minimum_20_characters"=>urlencode("Por favor, digite um mínimo de 20 caracteres"),
"please_enter_zip_code"=>urlencode("Por favor, digite o código postal"),
"please_enter_proper_zip_code"=>urlencode("Por favor, digite o código postal adequado"),
"please_enter_minimum_5_digits"=>urlencode("Por favor, insira no mínimo 5 dígitos"),
"please_enter_maximum_7_digits"=>urlencode("Por favor, insira no máximo 7 dígitos"),
"please_enter_city"=>urlencode("Por favor insira a cidade"),
"please_enter_proper_city"=>urlencode("Por favor, insira a cidade apropriada"),
"please_enter_maximum_48_characters"=>urlencode("Por favor, digite no máximo 48 caracteres"),
"please_enter_proper_state"=>urlencode("Por favor, insira o estado apropriado"),
"please_enter_contact_status"=>urlencode("Por favor insira o status do contato"),
"please_enter_maximum_100_characters"=>urlencode("Por favor, insira no máximo 100 caracteres"),
"your_cart_is_empty_please_add_cleaning_services"=>urlencode("Seu carrinho está vazio, por favor, adicione serviços de limpeza"),
"coupon_expired"=>urlencode("Cupão expirado"),
"invalid_coupon"=>urlencode("Cupom inválido"),
"our_service_not_available_at_your_location"=>urlencode("Nosso serviço não está disponível em sua localização"),
"please_enter_proper_postal_code"=>urlencode("Por favor, insira o código postal adequado"),
"invalid_email_id_please_register_first"=>urlencode("ID de e-mail inválido, registre-se primeiro"),
"your_password_send_successfully_at_your_registered_email_id"=>urlencode("Sua senha é enviada com sucesso no seu ID de e-mail cadastrado"),
"your_password_reset_successfully_please_login"=>urlencode("Sua senha redefinida com sucesso por favor login"),
"new_password_and_retype_new_password_mismatch"=>urlencode("Nova senha e redigite a nova incompatibilidade de senha"),
"new"=>urlencode("Novo"),
"your_reset_password_link_expired"=>urlencode("Seu link de senha de redefinição expirou"),
"front_display_language_changed"=>urlencode("Idioma de exibição frontal alterado"),
"updated_front_display_language_and_update_labels"=>urlencode("Idioma de exibição frontal atualizado e rótulos de atualização"),
"please_enter_only_7_chars_maximum"=>urlencode("Por favor digite apenas 7 caracteres no máximo"),
"please_enter_maximum_20_chars"=>urlencode("Por favor, insira no máximo 20 caracteres"),
"record_inserted_successfully"=>urlencode("Registro inserido com sucesso"),
"please_enter_account_sid"=>urlencode("Por favor, insira a conta SID"),
"please_enter_auth_token"=>urlencode("Por favor digite o token de autenticação"),
"please_enter_sender_number"=>urlencode("Por favor, insira o número do remetente"),
"please_enter_admin_number"=>urlencode("Por favor insira o número Admin"),
"sorry_service_already_exist"=>urlencode("Desculpe serviço já existe"),
"please_enter_api_login_id"=>urlencode("Insira o ID de login da API"),
"please_enter_transaction_key"=>urlencode("Por favor, insira a chave de transação"),
"please_enter_sms_message"=>urlencode("Por favor, insira a mensagem sms"),
"please_enter_email_message"=>urlencode("Por favor insira uma mensagem de email"),
"please_enter_private_key"=>urlencode("Por favor, insira a chave privada"),
"please_enter_seller_id"=>urlencode("Por favor, insira o ID do vendedor"),
"please_enter_valid_value_for_discount"=>urlencode("Por favor insira um valor válido para desconto"),
"password_must_be_only_10_characters"=>urlencode("A senha deve ter apenas 10 caracteres"),
"password_at_least_have_8_characters"=>urlencode("Senha, pelo menos, tem 8 caracteres"),
"please_enter_retype_new_password"=>urlencode("Por favor, digite a nova senha"),
"your_password_send_successfully_at_your_email_id"=>urlencode("Sua senha Enviar com sucesso no seu ID de e-mail"),
"please_select_expiry_date"=>urlencode("Por favor selecione a data de vencimento"),
"please_enter_merchant_key"=>urlencode("Por favor, digite Merchant Key"),
"please_enter_salt_key"=>urlencode("Por favor, insira Salt Key"),
"please_enter_account_username"=>urlencode("Por favor digite o nome de usuário da conta"),
"please_enter_account_hash_id"=>urlencode("Por favor, indique o hash da conta"),
"invalid_values"=>urlencode("Valores inválidos"),
"please_select_atleast_one_checkout_method"=>urlencode("Por favor selecione pelo menos um método de checkout"),
);

$extra_labels_pt_PT = array (
"please_enter_minimum_3_chars"=>urlencode("Por favor, insira no mínimo 3 caracteres"),
"invoice"=>urlencode("FATURA"),
"invoice_to"=>urlencode("FATURA PARA"),
"invoice_date"=>urlencode("Data da fatura"),
"cash"=>urlencode("DINHEIRO"),
"service_name"=>urlencode("Nome do Serviço"),
"qty"=>urlencode("Qtd"),
"booked_on"=>urlencode("Reservado em"),
);

$front_error_labels_pt_PT = array (
"min_ff_ps"=>urlencode("Por favor, insira no mínimo 8 caracteres"),
"max_ff_ps"=>urlencode("Por favor, insira no máximo 10 caracteres"),
"req_ff_fn"=>urlencode("Por favor, insira o primeiro nome"),
"min_ff_fn"=>urlencode("Por favor, insira no mínimo 3 caracteres"),
"max_ff_fn"=>urlencode("Por favor, insira no máximo 15 caracteres"),
"req_ff_ln"=>urlencode("Por favor, insira o sobrenome"),
"min_ff_ln"=>urlencode("Por favor, insira no mínimo 3 caracteres"),
"max_ff_ln"=>urlencode("Por favor, insira no máximo 15 caracteres"),
"req_ff_ph"=>urlencode("Por favor insira o número de telefone"),
"min_ff_ph"=>urlencode("Por favor, insira no mínimo 9 caracteres"),
"max_ff_ph"=>urlencode("Por favor, insira no máximo 15 caracteres"),
"req_ff_sa"=>urlencode("Por favor, insira o endereço da rua"),
"min_ff_sa"=>urlencode("Por favor, insira no mínimo 10 caracteres"),
"max_ff_sa"=>urlencode("Por favor, insira no máximo 40 caracteres"),
"req_ff_zp"=>urlencode("Por favor, digite o código postal"),
"min_ff_zp"=>urlencode("Por favor, insira no mínimo 3 caracteres"),
"max_ff_zp"=>urlencode("Por favor, insira no máximo 7 caracteres"),
"req_ff_ct"=>urlencode("Por favor insira a cidade"),
"min_ff_ct"=>urlencode("Por favor, insira no mínimo 3 caracteres"),
"max_ff_ct"=>urlencode("Por favor, insira no máximo 15 caracteres"),
"req_ff_st"=>urlencode("Por favor, insira o estado"),
"min_ff_st"=>urlencode("Por favor, insira no mínimo 3 caracteres"),
"max_ff_st"=>urlencode("Por favor, insira no máximo 15 caracteres"),
"req_ff_srn"=>urlencode("Por favor, insira notas"),
"min_ff_srn"=>urlencode("Por favor, insira no mínimo 10 caracteres"),
"max_ff_srn"=>urlencode("Por favor, insira no máximo 70 caracteres"),
"Transaction_failed_please_try_again"=>urlencode("Falha na transação, tente novamente"),
"Please_Enter_valid_card_detail"=>urlencode("Por favor, insira o detalhe válido do cartão"),
);

$language_front_arr_pt_PT = base64_encode(serialize($label_data_pt_PT));
$language_admin_arr_pt_PT = base64_encode(serialize($admin_labels_pt_PT));
$language_error_arr_pt_PT = base64_encode(serialize($error_labels_pt_PT));
$language_extra_arr_pt_PT = base64_encode(serialize($extra_labels_pt_PT));
$language_form_error_arr_pt_PT = base64_encode(serialize($front_error_labels_pt_PT));

$insert_default_lang_pt_PT = "insert into `ct_languages` (`id`,`label_data`,`language`, `admin_labels`, `error_labels`, `extra_labels`, `front_error_labels`,`language_status`) values(NULL,'" . $language_front_arr_pt_PT . "','pt_PT','" . $language_admin_arr_pt_PT . "','" . $language_error_arr_pt_PT . "','" . $language_extra_arr_pt_PT . "','" . $language_form_error_arr_pt_PT . "','Y')";
mysqli_query($this->conn, $insert_default_lang_pt_PT);

/** Russian Language **/
$label_data_ru_RU = array (
"none_available"=>urlencode(" Нет доступных"),
"appointment_zip"=>urlencode("Назначение Zip"),
"appointment_city"=>urlencode("Город назначения"),
"appointment_state"=>urlencode("Назначение государства"),
"appointment_address"=>urlencode("Адрес назначения"),
"guest_user"=>urlencode("Гость"),
"service_usage_methods"=>urlencode("Способы использования сервиса"),
"paypal"=>urlencode("Paypal"),
"please_check_for_the_below_missing_information"=>urlencode("Пожалуйста, проверьте ниже пропущенную информацию."),
"please_provide_company_details_from_the_admin_panel"=>urlencode("Пожалуйста, предоставьте данные компании с панели администратора."),
"please_add_some_services_methods_units_addons_from_the_admin_panel"=>urlencode("Добавьте некоторые сервисы, методы, юниты и аддоны с панели администратора."),
"please_add_time_scheduling_from_the_admin_panel"=>urlencode("Пожалуйста, добавьте расписание времени с панели администратора."),
"please_complete_configurations_before_you_created_website_embed_code"=>urlencode("Заполните конфигурацию, прежде чем создавать код для встраивания сайта."),
"cvc"=>urlencode("CVC"),
"mm_yyyy"=>urlencode("(ММ / ГГГГ)"),
"expiry_date_or_csv"=>urlencode("Дата истечения срока действия или CSV"),
"street_address_placeholder"=>urlencode("например Центральный пр."),
"zip_code_placeholder"=>urlencode("например 90001"),
"city_placeholder"=>urlencode("например. Лос-Анджелес"),
"state_placeholder"=>urlencode("например. Калифорния"),
"payumoney"=>urlencode("PayUmoney"),
"same_as_above"=>urlencode("То же, что выше"),
"sun"=>urlencode("солнце"),
"mon"=>urlencode("понедельник"),
"tue"=>urlencode("вторник"),
"wed"=>urlencode("Мы б"),
"thu"=>urlencode("коллекция"),
"fri"=>urlencode("пятница"),
"sat"=>urlencode("Сидел"),
"su"=>urlencode("его"),
"mo"=>urlencode("вы"),
"tu"=>urlencode("ваш"),
"we"=>urlencode("Мы"),
"th"=>urlencode("Th"),
"fr"=>urlencode("Fr"),
"sa"=>urlencode("ее"),
"my_bookings"=>urlencode("Мои заказы"),
"your_postal_code"=>urlencode("почтовый индекс"),
"where_would_you_like_us_to_provide_service"=>urlencode("Где бы вы хотели, чтобы мы предоставляли услуги?"),
"choose_service"=>urlencode("Выберите услугу"),
"how_often_would_you_like_us_provide_service"=>urlencode("Как часто вы хотите, чтобы мы предоставляли услуги?"),
"when_would_you_like_us_to_come"=>urlencode("Когда вы хотите, чтобы мы пришли?"),
"today"=>urlencode("CЕГОДНЯ"),
"your_personal_details"=>urlencode("Ваши персональные данные"),
"existing_user"=>urlencode("Существующий пользователь"),
"new_user"=>urlencode("Новый пользователь"),
"preferred_email"=>urlencode("Предпочтительный адрес электронной почты"),
"preferred_password"=>urlencode("Предпочтительный пароль"),
"your_valid_email_address"=>urlencode("Ваш действительный адрес электронной почты"),
"first_name"=>urlencode("Имя"),
"your_first_name"=>urlencode("Твое имя"),
"last_name"=>urlencode("Фамилия"),
"your_last_name"=>urlencode("Ваша фамилия"),
"street_address"=>urlencode("Адрес улицы"),
"cleaning_service"=>urlencode("Уборка"),
"please_select_method"=>urlencode("Выберите метод"),
"zip_code"=>urlencode("Почтовый Индекс"),
"city"=>urlencode("город"),
"state"=>urlencode("состояние"),
"special_requests_notes"=>urlencode("Особые пожелания (примечания)"),
"do_you_have_a_vaccum_cleaner"=>urlencode("У вас есть пылесос?"),
"assign_appointment_to_staff"=>urlencode("Назначить встречу сотрудникам"),
"delete_member"=>urlencode("Удалить пользователя?"),
"yes"=>urlencode("да"),
"no"=>urlencode("нет"),
"preferred_payment_method"=>urlencode("Предпочтительный способ оплаты"),
"please_select_one_payment_method"=>urlencode("Выберите один способ оплаты"),
"partial_deposit"=>urlencode("Частичный депозит"),
"remaining_amount"=>urlencode("Оставшееся количество"),
"please_read_our_terms_and_conditions_carefully"=>urlencode("Пожалуйста, внимательно ознакомьтесь с нашими условиями"),
"do_you_have_parking"=>urlencode("У вас есть парковка?"),
"how_will_we_get_in"=>urlencode("Как мы войдем?"),
"i_will_be_at_home"=>urlencode("Я буду дома"),
"please_call_me"=>urlencode("Пожалуйста, позвони мне"),
"recurring_discounts_apply_from_the_second_cleaning_onward"=>urlencode("Периодические скидки применяются после второй очистки."),
"please_provide_your_address_and_contact_details"=>urlencode("Укажите свой адрес и контактные данные"),
"you_are_logged_in_as"=>urlencode("Вы вошли как"),
"the_key_is_with_the_doorman"=>urlencode("Ключ от швейцара"),
"other"=>urlencode("Другие"),
"have_a_promocode"=>urlencode("Есть промо код?"),
"apply"=>urlencode("Подать заявление"),
"applied_promocode"=>urlencode("Прикладной Promocode"),
"complete_booking"=>urlencode("Полное бронирование"),
"cancellation_policy"=>urlencode("Политика отмены"),
"cancellation_policy_header"=>urlencode("Заголовок политики отмены"),
"cancellation_policy_textarea"=>urlencode("Заголовок политики отмены"),
"free_cancellation_before_redemption"=>urlencode("Бесплатная отмена до выкупа"),
"show_more"=>urlencode("Показать больше"),
"please_select_service"=>urlencode("Выберите услугу"),
"choose_your_service_and_property_size"=>urlencode("Выберите свой сервис и размер собственности"),
"choose_your_service"=>urlencode("Выберите свой сервис"),
"please_configure_first_cleaning_services_and_settings_in_admin_panel"=>urlencode("Сначала настройте службы очистки и настройки в панели администратора"),
"i_have_read_and_accepted_the"=>urlencode("Я прочитал и принял"),
"terms_and_condition"=>urlencode("Условия и положения"),
"and"=>urlencode("а также"),
"updated_labels"=>urlencode("Обновленные этикетки"),
"privacy_policy"=>urlencode("политика конфиденциальности"),
"please_fill_all_the_company_informations_and_add_some_services_and_addons"=>urlencode("Необходимые конфигурации не завершены."),
"booking_summary"=>urlencode("Резюме бронирования"),
"your_email"=>urlencode("Ваш адрес электронной почты"),
"enter_email_to_login"=>urlencode("Введите адрес электронной почты для входа в систему"),
"your_password"=>urlencode("Ваш пароль"),
"enter_your_password"=>urlencode("Введите ваш пароль"),
"forget_password"=>urlencode("Забыть пароль?"),
"reset_password"=>urlencode("Сброс пароля"),
"enter_your_email_and_we_send_you_instructions_on_resetting_your_password"=>urlencode("Введите свой адрес электронной почты, и мы отправим вам инструкции по сбросу пароля."),
"registered_email"=>urlencode("зарегистрированная электронная почта"),
"send_mail"=>urlencode("Отправить письмо"),
"back_to_login"=>urlencode("Вернуться на страницу входа"),
"your"=>urlencode("Ваш"),
"your_clean_items"=>urlencode("Ваши чистые предметы"),
"your_cart_is_empty"=>urlencode("Ваша корзина пуста"),
"sub_totaltax"=>urlencode("Суб общий налог"),
"sub_total"=>urlencode("Промежуточный итог"),
"no_data_available_in_table"=>urlencode("Данные отсутствуют в таблице"),
"total"=>urlencode("Всего"),
"or"=>urlencode("Или"),
"select_addon_image"=>urlencode("Выберите изображение аддона"),
"inside_fridge"=>urlencode("Внутренний холодильник"),
"inside_oven"=>urlencode("Внутренняя духовка"),
"inside_windows"=>urlencode("Внутренние окна"),
"carpet_cleaning"=>urlencode("Очистка ковров"),
"green_cleaning"=>urlencode("Экологическая чистка"),
"pets_care"=>urlencode("Домашние животные"),
"tiles_cleaning"=>urlencode("Очистка плитки"),
"wall_cleaning"=>urlencode("Уборка стен"),
"laundry"=>urlencode("Прачечная"),
"basement_cleaning"=>urlencode("Очистка подвала"),
"basic_price"=>urlencode("Основная цена"),
"max_qty"=>urlencode("Макс. Кол-во"),
"multiple_qty"=>urlencode("Многократное количество"),
"base_price"=>urlencode("Базисная цена"),
"unit_pricing"=>urlencode("Цена единицы товара"),
"method_is_booked"=>urlencode("Метод забронирован"),
"service_addons_price_rules"=>urlencode("Правила цен на услуги"),
"service_unit_front_dropdown_view"=>urlencode("Дисплей с фронтальным дисплеем"),
"service_unit_front_block_view"=>urlencode("Обзор блока передней панели"),
"service_unit_front_increase_decrease_view"=>urlencode("Фронт увеличения / уменьшения"),
"are_you_sure"=>urlencode("Ты уверен"),
"service_unit_price_rules"=>urlencode("Правила цены единицы обслуживания"),
"close"=>urlencode("Закрыть"),
"closed"=>urlencode("Закрыто"),
"service_addons"=>urlencode("Сервисные приложения"),
"service_enable"=>urlencode("Включить услугу"),
"service_disable"=>urlencode("Отключить службу"),
"method_enable"=>urlencode("Включить метод"),
"off_time_deleted"=>urlencode("Время выключения"),
"error_in_delete_of_off_time"=>urlencode("Ошибка удаления времени выключения"),
"method_disable"=>urlencode("Метод отключен"),
"extra_services"=>urlencode("Дополнительные услуги"),
"for_initial_cleaning_only_contact_us_to_apply_to_recurrings"=>urlencode("Только для начальной очистки. Свяжитесь с нами, чтобы подать заявку на повтор."),
"number_of"=>urlencode("Количество"),
"extra_services_not_available"=>urlencode("Дополнительные услуги не доступны"),
"available"=>urlencode("Доступный"),
"selected"=>urlencode("выбранный"),
"not_available"=>urlencode("Недоступен"),
"none"=>urlencode("Никто"),
"none_of_time_slot_available_please_check_another_dates"=>urlencode("Нет доступного временного интервала. Проверьте другие даты."),
"availability_is_not_configured_from_admin_side"=>urlencode("Доступность не настроена с учетной записью администратора"),
"how_many_intensive"=>urlencode("Сколько интенсивных"),
"no_intensive"=>urlencode("Нет интенсивного"),
"frequently_discount"=>urlencode("Часто скидка"),
"coupon_discount"=>urlencode("Скидка на купоны"),
"how_many"=>urlencode("Сколько"),
"enter_your_other_option"=>urlencode("Введите другой вариант"),
"log_out"=>urlencode("Выйти"),
"your_added_off_times"=>urlencode("Ваше время добавления"),
"log_in"=>urlencode("авторизоваться"),
"custom_css"=>urlencode("Пользовательские CSS"),
"success"=>urlencode("успех"),
"failure"=>urlencode("недостаточность"),
"you_can_only_use_valid_zipcode"=>urlencode("Вы можете использовать только действительный почтовый индекс"),
"minutes"=>urlencode("минут"),
"hours"=>urlencode("часов"),
"days"=>urlencode("дней"),
"months"=>urlencode("Месяцы"),
"year"=>urlencode("Год"),
"default_url_is"=>urlencode("URL-адрес по умолчанию:"),
"card_payment"=>urlencode("Оплата карты"),
"pay_at_venue"=>urlencode("Оплатить на месте"),
"card_details"=>urlencode("Детали карты"),
"card_number"=>urlencode("Номер карты"),
"invalid_card_number"=>urlencode("не верный номер карты"),
"expiry"=>urlencode("истечение"),
"button_preview"=>urlencode("Предварительный просмотр кнопки"),
"thankyou"=>urlencode("Спасибо"),
"thankyou_for_booking_appointment"=>urlencode("Спасибо! для бронирования"),
"you_will_be_notified_by_email_with_details_of_appointment"=>urlencode("Вы будете уведомлены по электронной почте с подробной информацией о встрече"),
"please_enter_firstname"=>urlencode("Введите имя"),
"please_enter_lastname"=>urlencode("Введите фамилию"),
"remove_applied_coupon"=>urlencode("Удалить использованный купон"),
"eg_799_e_dragram_suite_5a"=>urlencode("г. 799 E DRAGRAM SUITE 5A"),
"eg_14114"=>urlencode("например. 14114"),
"eg_tucson"=>urlencode("например. TUCSON"),
"eg_az"=>urlencode("например."),
"warning"=>urlencode("Предупреждение"),
"try_later"=>urlencode("Попробуй позже"),
"choose_your"=>urlencode("Выбери свой"),
"configure_now_new"=>urlencode("Настроить сейчас"),
"january"=>urlencode("Январь"),
"february"=>urlencode("февраль"),
"march"=>urlencode("МАРТ"),
"april"=>urlencode("АПРЕЛЬ"),
"may"=>urlencode("МАЙ"),
"june"=>urlencode("июнь"),
"july"=>urlencode("июль"),
"august"=>urlencode("август"),
"september"=>urlencode("СЕНТЯБРЬ"),
"october"=>urlencode("октябрь"),
"november"=>urlencode("ноябрю"),
"december"=>urlencode("ДЕКАБРЬ"),
"jan"=>urlencode("JAN"),
"feb"=>urlencode("февраль"),
"mar"=>urlencode("MAR"),
"apr"=>urlencode("апреле"),
"jun"=>urlencode("июне"),
"jul"=>urlencode("июле"),
"aug"=>urlencode("августе"),
"sep"=>urlencode("сентября"),
"oct"=>urlencode("октябре"),
"nov"=>urlencode("ноябрю"),
"dec"=>urlencode("Декабрь"),
"pay_locally"=>urlencode("Платные услуги"),
"please_select_provider"=>urlencode("Выберите поставщика"),
);

$admin_labels_ru_RU = array (
"payment_status"=>urlencode("Статус платежа"),
"staff_booking_status"=>urlencode("Статус бронирования персонала"),
"accept"=>urlencode("принимать"),
"accepted"=>urlencode("Принято"),
"decline"=>urlencode("снижение"),
"paid"=>urlencode("оплаченный"),
"eway"=>urlencode("Eway"),
"half_section"=>urlencode("Половина раздела"),
"option_title"=>urlencode("Название опции"),
"merchant_ID"=>urlencode("идентификатор продавца"),
"How_it_works"=>urlencode("Как это работает?"),
"Your_currency_should_be_AUD_to_enable_payway_payment_gateway"=>urlencode("Ваша валюта должна быть Австралийским Долларом для включения шлюза оплаты"),
"secure_key"=>urlencode("Защитный ключ"),
"payway"=>urlencode("Payway"),
"Your_Google_calendar_id_where_you_need_to_get_alerts_its_normaly_your_Gmail_ID"=>urlencode("Ваш идентификатор календаря Google, где вам нужно получать оповещения, нормализует ваш идентификатор Gmail. например johndoe@example.com"),
"You_can_get_your_client_ID_from_your_Google_Calendar_Console"=>urlencode("Вы можете получить свой идентификатор клиента из своей консоли Google Calendar"),
"You_can_get_your_client_secret_from_your_Google_Calendar_Console"=>urlencode("Вы можете получить секретную информацию своего клиента из консоли Google Календаря"),
"its_your_Cleanto_booking_form_page_url"=>urlencode("его URL-адрес страницы формы бронирования Cleanto"),
"Its_your_Cleanto_Google_Settings_page_url"=>urlencode("Его URL-адрес страницы настроек Google Cleanto"),
"Add_Manual_booking"=>urlencode("Добавить бронирование вручную"),
"Google_Calender_Settings"=>urlencode("Настройки Google Calender"),
"Add_Appointments_To_Google_Calender"=>urlencode(" Добавить назначения в Google Calender"),
"Google_Calender_Id"=>urlencode("Идентификатор календаря Google"),
"Google_Calender_Client_Id"=>urlencode("Идентификатор календаря Google"),
"Google_Calender_Client_Secret"=>urlencode("Секретный клиент Google Calender"),
"Google_Calender_Frontend_URL"=>urlencode("URL-адрес Google Calender Frontend"),
"Google_Calender_Admin_URL"=>urlencode("URL-адрес Google Calender Admin"),
"Google_Calender_Configuration"=>urlencode("Конфигурация Google Calender"),
"Two_Way_Sync"=>urlencode("Двухсторонняя синхронизация"),
"Verify_Account"=>urlencode("подтвердить учетную запись"),
"Select_Calendar"=>urlencode("Выберите CalendarSelect Calendar"),
"Disconnect"=>urlencode("Отключить"),
"Calendar_Fisrt_Day"=>urlencode("Календарь первого дня"),
"Calendar_Default_View"=>urlencode("Календарь по умолчанию"),
"Show_company_title"=>urlencode("Показать название компании"),
"front_language_flags_list"=>urlencode("Список флагов флагов"),
"Google_Analytics_Code"=>urlencode("Код Google Analytics"),
"Page_Meta_Tag"=>urlencode("Страница / Метатег"),
"SEO_Settings"=>urlencode("Настройки SEO"),
"Meta_Description"=>urlencode("Описание Meta"),
"SEO"=>urlencode("SEO"),
"og_tag_image"=>urlencode("и взять изображение"),
"og_tag_url"=>urlencode("и URL тега"),
"og_tag_type"=>urlencode("и тип тега"),
"og_tag_title"=>urlencode("и название тега"),
"Quantity"=>urlencode("Количество"),
"Send_Invoice"=>urlencode("Отправить счет-фактуру"),
"Recurrence"=>urlencode("рекуррентность"),
"Recurrence_booking"=>urlencode("Повторный заказ"),
"Reset_Color"=>urlencode("Сбросить цвет"),
"Loader"=>urlencode("погрузчик"),
"CSS_Loader"=>urlencode("Погрузчик CSS"),
"GIF_Loader"=>urlencode("GIF-загрузчик"),
"Default_Loader"=>urlencode("Погрузчик по умолчанию"),
"for_a"=>urlencode("для"),
"show_company_logo"=>urlencode("Показать логотип компании"),
"on"=>urlencode("на"),
"user_zip_code"=>urlencode("почтовый индекс"),
"delete_this_method"=>urlencode("Удалить этот метод?"),
"authorize_net"=>urlencode("Authorize.Net"),
"staff_details"=>urlencode("ДЕТАЛИ ПЕРСОНАЛА"),
"client_payments"=>urlencode("Платежи клиентов"),
"staff_payments"=>urlencode("Платежи персонала"),
"staff_payments_details"=>urlencode("Подробная информация о платежах"),
"advance_paid"=>urlencode("Предварительный платеж"),
"change_calculation_policyy"=>urlencode("Изменение политики расчета"),
"frontend_fonts"=>urlencode("Фронтальные шрифты"),
"favicon_image"=>urlencode("Изображение Favicon"),
"staff_email_template"=>urlencode("Шаблон электронной почты для сотрудников"),
"staff_details_add_new_and_manage_staff_payments"=>urlencode("Детали персонала, добавление новых и управление выплатами персонала"),
"add_staff"=>urlencode("Добавить персонал"),
"staff_bookings_and_payments"=>urlencode("Бронирование и оплата персонала"),
"staff_booking_details_and_payment"=>urlencode("Информация о персонале и оплата"),
"select_option_to_show_bookings"=>urlencode("Выберите вариант, чтобы показать бронирование"),
"select_service"=>urlencode("Выберите Сервис"),
"staff_name"=>urlencode("Имя сотрудника"),
"staff_payment"=>urlencode("Оплата персонала"),
"add_payment_to_staff_account"=>urlencode("Добавить платеж в счет персонала"),
"amount_payable"=>urlencode("Подлежащая уплате сумма"),
"save_changes"=>urlencode("Сохранить изменения"),
"front_error_labels"=>urlencode("Фрагменты ошибок на фронте"),
"stripe"=>urlencode("нашивка"),
"checkout_title"=>urlencode("2Checkout"),
"nexmo_sms_gateway"=>urlencode("Nexmo SMS Gateway"),
"nexmo_sms_setting"=>urlencode("Настройка Nexmo SMS"),
"nexmo_api_key"=>urlencode("Ключ API Nexmo"),
"nexmo_api_secret"=>urlencode("Интерфейс API Nexmo"),
"nexmo_from"=>urlencode("Nexmo От"),
"nexmo_status"=>urlencode("Статус Nexmo"),
"nexmo_send_sms_to_client_status"=>urlencode("Nexmo Отправляет смс в состояние клиента"),
"nexmo_send_sms_to_admin_status"=>urlencode("Nexmo Отправить Sms К администратору Статус"),
"nexmo_admin_phone_number"=>urlencode("Номер телефона администратора Nexmo"),
"save_12_5"=>urlencode("сэкономить 12,5%"),
"front_tool_tips"=>urlencode("ПЕРЕДНИЙ ИНСТРУМЕНТ"),
"front_tool_tips_lower"=>urlencode("Советы переднего инструмента"),
"tool_tip_my_bookings"=>urlencode("Мои заказы"),
"tool_tip_postal_code"=>urlencode("Почтовый индекс"),
"tool_tip_services"=>urlencode("Сервисы"),
"tool_tip_extra_service"=>urlencode("Дополнительные услуги"),
"tool_tip_frequently_discount"=>urlencode("Часто скидка"),
"tool_tip_when_would_you_like_us_to_come"=>urlencode("Когда вы хотите, чтобы мы пришли?"),
"tool_tip_your_personal_details"=>urlencode("Ваши персональные данные"),
"tool_tip_have_a_promocode"=>urlencode("Есть промо код"),
"tool_tip_preferred_payment_method"=>urlencode("Предпочтительный способ оплаты"),
"login_page"=>urlencode("Страница авторизации"),
"front_page"=>urlencode("Титульная страница"),
"before_e_g_100"=>urlencode("Перед (например $ 100)"),
"after_e_g_100"=>urlencode("После того, как (e.g.100 $)"),
"tax_vat"=>urlencode("Налоги / НДС"),
"wrong_url"=>urlencode("Неправильный адрес"),
"choose_file"=>urlencode("Выберите файл"),
"frontend_labels"=>urlencode("Фронтальные ярлыки"),
"admin_labels"=>urlencode("Ярлыки администратора"),
"dropdown_design"=>urlencode("Дизайн DropDown"),
"blocks_as_button_design"=>urlencode("Блоки как дизайн кнопок"),
"qty_control_design"=>urlencode("Qty Control Design"),
"dropdowns"=>urlencode("Dropdowns"),
"big_images_radio"=>urlencode("Радио Big Images"),
"errors"=>urlencode("ошибки"),
"extra_labels"=>urlencode("Дополнительные ярлыки"),
"api_password"=>urlencode("Пароль API"),
"api_username"=>urlencode("Имя API"),
"appearance"=>urlencode("ПОЯВЛЕНИЕ"),
"action"=>urlencode("действие"),
"actions"=>urlencode("действия"),
"add_break"=>urlencode("Добавить разрыв"),
"add_breaks"=>urlencode("Добавить перерывы"),
"add_cleaning_service"=>urlencode("Добавить уборку"),
"add_method"=>urlencode("Добавить метод"),
"add_new"=>urlencode("Добавить новое"),
"add_sample_data"=>urlencode("Добавить образцы данных"),
"add_unit"=>urlencode("Добавить блок"),
"add_your_off_times"=>urlencode("Добавить время своего отсутствия"),
"add_new_off_time"=>urlencode("Добавить новое время отключения"),
"add_ons"=>urlencode("Дополнения"),
"addons_bookings"=>urlencode("Заказы AddOns"),
"addon_service_front_view"=>urlencode("Внешний вид Addon-Service"),
"addons"=>urlencode("Addons"),
"service_commission"=>urlencode("Комиссия по обслуживанию"),
"commission_total"=>urlencode("Общая комиссия"),
"address"=>urlencode("Адрес"),
"new_appointment_assigned"=>urlencode("Назначено новое назначение"),
"admin_email_notifications"=>urlencode("Уведомления администратора электронной почты"),
"all_payment_gateways"=>urlencode("Все платежные шлюзы"),
"all_services"=>urlencode("Все услуги"),
"allow_multiple_booking_for_same_timeslot"=>urlencode("Разрешить многократное бронирование для одного и того же таймслота. Множественное бронирование для одного и того же временного интервала."),
"amount"=>urlencode("Количество"),
"app_date"=>urlencode("Приложение. Дата"),
"appearance_settings"=>urlencode("Настройки внешнего вида"),
"appointment_completed"=>urlencode("Назначение завершено"),
"appointment_details"=>urlencode("Детали встречи"),
"appointment_marked_as_no_show"=>urlencode("Назначение, обозначенное как нет шоу"),
"mark_as_no_show"=>urlencode("Отметить как не показывать"),
"appointment_reminder_buffer"=>urlencode("Буфер напоминаний о назначении"),
"appointment_auto_confirm"=>urlencode("Автоматическое подтверждение назначения"),
"appointments"=>urlencode("Назначения"),
"admin_area_color_scheme"=>urlencode("Цветовая схема области администратора"),
"thankyou_page_url"=>urlencode("URL страницы"),
"addon_title"=>urlencode("Название аддона"),
"availabilty"=>urlencode("Доступность"),
"background_color"=>urlencode("Фоновый цвет"),
"behaviour_on_click_of_button"=>urlencode("Поведение при нажатии кнопки"),
"book_now"=>urlencode("Забронируйте сейчас"),
"booking_date_and_time"=>urlencode("Дата и время бронирования"),
"booking_details"=>urlencode("Детали бронирования"),
"booking_information"=>urlencode("Информация о бронировании"),
"booking_serve_date"=>urlencode("Бронирование"),
"booking_status"=>urlencode("Статус бронирования"),
"booking_notifications"=>urlencode("Уведомления о бронировании"),
"bookings"=>urlencode("Бронирование"),
"button_position"=>urlencode("Позиция кнопки"),
"button_text"=>urlencode("Текст кнопки"),
"company"=>urlencode("КОМПАНИЯ"),
"cannot_cancel_now"=>urlencode("Не удается отменить сейчас"),
"cannot_reschedule_now"=>urlencode("Невозможно перепланировать сейчас"),
"cancel"=>urlencode("Отмена"),
"cancellation_buffer_time"=>urlencode("Время буферизации отмены"),
"cancelled_by_client"=>urlencode("Отменено клиентом"),
"cancelled_by_service_provider"=>urlencode("Отменено поставщиком услуг"),
"change_password"=>urlencode("Изменить пароль"),
"cleaning_service"=>urlencode("Уборка"),
"client"=>urlencode("клиент"),
"client_email_notifications"=>urlencode("Уведомления электронной почты клиента"),
"client_name"=>urlencode("имя клиента"),
"color_scheme"=>urlencode("Цветовая схема"),
"color_tag"=>urlencode("Цветной тег"),
"company_address"=>urlencode("Адрес"),
"company_email"=>urlencode("Эл. адрес"),
"company_logo"=>urlencode("Логотип компании"),
"company_name"=>urlencode("Наименование фирмы"),
"company_settings"=>urlencode("Настройки деловой информации"),
"companyname"=>urlencode("название компании"),
"company_info_settings"=>urlencode("Информация о компании Настройки"),
"completed"=>urlencode("Завершенный"),
"confirm"=>urlencode("подтвердить"),
"confirmed"=>urlencode("подтвердил"),
"contact_status"=>urlencode("Статус контакта"),
"country"=>urlencode("Страна"),
"country_code_phone"=>urlencode("Код страны (телефон)"),
"coupon"=>urlencode("Купон"),
"coupon_code"=>urlencode("код купона"),
"coupon_limit"=>urlencode("Предел купона"),
"coupon_type"=>urlencode("Тип купона"),
"coupon_used"=>urlencode("Используемый купон"),
"coupon_value"=>urlencode("Стоимость купона"),
"create_addon_service"=>urlencode(" Создать службу аддона"),
"crop_and_save"=>urlencode("Crop & Save"),
"currency"=>urlencode("валюта"),
"currency_symbol_position"=>urlencode("Позиция символа валюты"),
"customer"=>urlencode("Клиент"),
"customer_information"=>urlencode("Информация для покупателей"),
"customers"=>urlencode("Клиенты"),
"date_and_time"=>urlencode("Дата и время"),
"date_picker_date_format"=>urlencode("Формат даты-выбора"),
"default_design_for_addons"=>urlencode("Дизайн по умолчанию для аддонов"),
"default_design_for_methods_with_multiple_units"=>urlencode("Дизайн по умолчанию для методов с несколькими единицами"),
"default_design_for_services"=>urlencode("Дизайн по умолчанию для служб"),
"default_setting"=>urlencode("Настройки по умолчанию"),
"delete"=>urlencode("Удалить"),
"description"=>urlencode("Описание"),
"discount"=>urlencode("скидка"),
"download_invoice"=>urlencode("Скачать счет-фактуру"),
"email_notification"=>urlencode("УВЕДОМЛЕНИЕ ПО ЭЛЕКТРОННОЙ ПОЧТЕ"),
"email"=>urlencode("Эл. адрес"),
"email_settings"=>urlencode("Настройки электронной почты"),
"embed_code"=>urlencode("Код для вставки"),
"enter_your_email_and_we_will_send_you_instructions_on_resetting_your_password"=>urlencode("Введите свой адрес электронной почты, и мы отправим вам инструкции по сбросу пароля."),
"expiry_date"=>urlencode("Срок годности"),
"export"=>urlencode("экспорт"),
"export_your_details"=>urlencode("Экспортировать ваши данные"),
"frequently_discount_setting_tabs"=>urlencode("ЧАСТОТА СКИДКИ"),
"frequently_discount_header"=>urlencode("Часто скидка"),
"field_is_required"=>urlencode("Поле, обязательное для заполнения"),
"file_size"=>urlencode("Размер файла"),
"flat_fee"=>urlencode("Фиксированная плата"),
"flat"=>urlencode("Квартира"),
"freq_discount"=>urlencode("Част-Discount"),
"frequently_discount_label"=>urlencode("Часто маркировка скидок"),
"frequently_discount_type"=>urlencode("Часто Тип скидки"),
"frequently_discount_value"=>urlencode("Часто скидки"),
"front_service_box_view"=>urlencode("Вид спереди"),
"front_service_dropdown_view"=>urlencode("Вид выпадающего меню"),
"front_view_options"=>urlencode("Опции фронтального просмотра"),
"full_name"=>urlencode("Полное имя"),
"general"=>urlencode("ГЕНЕРАЛЬНАЯ"),
"general_settings"=>urlencode("общие настройки"),
"get_embed_code_to_show_booking_widget_on_your_website"=>urlencode("Получите код для вставки, чтобы показать виджет бронирования на своем веб-сайте"),
"get_the_embeded_code"=>urlencode("Получить встроенный код"),
"guest_customers"=>urlencode("Гостевые клиенты"),
"guest_user_checkout"=>urlencode("Оформить заказ"),
"hide_faded_already_booked_time_slots"=>urlencode("Скрыть исчезнувшие уже зарезервированные временные интервалы"),
"hostname"=>urlencode("Hostname"),
"labels"=>urlencode("ЭТИКЕТКИ"),
"legends"=>urlencode("Легенды"),
"login"=>urlencode("Авторизоваться"),
"maximum_advance_booking_time"=>urlencode("Максимальное время бронирования"),
"method"=>urlencode("метод"),
"method_name"=>urlencode("Имя метода"),
"method_title"=>urlencode("Название метода"),
"method_unit_quantity"=>urlencode("Количество единиц измерения"),
"method_unit_quantity_rate"=>urlencode("Показатель количества единицы измерения метода"),
"method_unit_title"=>urlencode("Название единицы измерения"),
"method_units_front_view"=>urlencode("Единицы измерения"),
"methods"=>urlencode("методы"),
"methods_booking"=>urlencode("Способы бронирования"),
"methods_bookings"=>urlencode("Методы бронирования"),
"minimum_advance_booking_time"=>urlencode("Минимальное время предварительного бронирования"),
"more"=>urlencode("Больше"),
"more_details"=>urlencode("Подробнее"),
"my_appointments"=>urlencode("Мои встречи"),
"name"=>urlencode("имя"),
"net_total"=>urlencode("Чистая сумма"),
"new_password"=>urlencode("новый пароль"),
"notes"=>urlencode("Заметки"),
"off_days"=>urlencode("Неделя"),
"off_time"=>urlencode("Время отключения"),
"old_password"=>urlencode("Старый пароль"),
"online_booking_button_style"=>urlencode("Бронирование онлайн Button Style"),
"open_widget_in_a_new_page"=>urlencode("Открыть виджет на новой странице"),
"order"=>urlencode("порядок"),
"order_date"=>urlencode("Дата заказа"),
"order_time"=>urlencode("Время заказа"),
"payments_setting"=>urlencode("ОПЛАТА"),
"promocode"=>urlencode("ПРОМО КОД"),
"promocode_header"=>urlencode("Промо код"),
"padding_time_before"=>urlencode("Время заполнения до"),
"parking"=>urlencode("Стоянка"),
"partial_amount"=>urlencode("Частичная сумма"),
"partial_deposit"=>urlencode("Частичный депозит"),
"partial_deposit_amount"=>urlencode("Частичная сумма вклада"),
"partial_deposit_message"=>urlencode("Частичное сообщение депозита"),
"password"=>urlencode("пароль"),
"payment"=>urlencode("Оплата"),
"payment_date"=>urlencode("Дата платежа"),
"payment_gateways"=>urlencode("Платежные шлюзы"),
"payment_method"=>urlencode("Способ оплаты"),
"payments"=>urlencode("платежи"),
"payments_history_details"=>urlencode("Подробная информация о платежах"),
"paypal_express_checkout"=>urlencode("Paypal Express Checkout"),
"paypal_guest_payment"=>urlencode("Платная оплата гостей"),
"pending"=>urlencode("в ожидании"),
"percentage"=>urlencode("процент"),
"personal_information"=>urlencode("Личная информация"),
"phone"=>urlencode("Телефон"),
"please_copy_above_code_and_paste_in_your_website"=>urlencode("Скопируйте код выше и вставьте его на свой сайт."),
"please_enable_payment_gateway"=>urlencode("Включите платежный шлюз"),
"please_set_below_values"=>urlencode("Установите ниже значения"),
"port"=>urlencode("порт"),
"postal_codes"=>urlencode("Почтовые коды"),
"price"=>urlencode("Цена"),
"price_calculation_method"=>urlencode("Метод расчета цены"),
"price_format_decimal_places"=>urlencode("Формат цены"),
"pricing"=>urlencode("ценообразование"),
"primary_color"=>urlencode("Основной цвет"),
"privacy_policy_link"=>urlencode("Политика конфиденциальности"),
"profile"=>urlencode("Профиль"),
"promocodes"=>urlencode("Промо-коды"),
"promocodes_list"=>urlencode("Список Promocodes"),
"registered_customers"=>urlencode("Зарегистрированные клиенты"),
"registered_customers_bookings"=>urlencode("Зарегистрированные заказчики"),
"reject"=>urlencode("отклонять"),
"rejected"=>urlencode("Отклонено"),
"remember_me"=>urlencode("Запомни меня"),
"remove_sample_data"=>urlencode("Удаление данных образца"),
"reschedule"=>urlencode("Перепланирование"),
"reset"=>urlencode("Сброс"),
"reset_password"=>urlencode("Сброс пароля"),
"reshedule_buffer_time"=>urlencode("Время буферизации"),
"retype_new_password"=>urlencode("Введите повторно новый пароль"),
"right_side_description"=>urlencode("Резервирование страницы"),
"save"=>urlencode("Сохранить"),
"save_availability"=>urlencode("Сохранить доступность"),
"save_setting"=>urlencode("Сохранить настройки"),
"save_labels_setting"=>urlencode("Сохранить настройки ярлыков"),
"schedule"=>urlencode("График"),
"schedule_type"=>urlencode("Тип расписания"),
"secondary_color"=>urlencode("Вторичный цвет"),
"select_language_for_update"=>urlencode("Выберите язык для обновления"),
"select_language_to_change_label"=>urlencode("Выбрать язык для изменения ярлыка"),
"select_language_to_display"=>urlencode("язык"),
"display_sub_headers_below_headers"=>urlencode("Под заголовками на странице бронирования"),
"select_payment_option_export_details"=>urlencode("Выберите параметры экспорта платежной информации"),
"send_mail"=>urlencode("Отправить письмо"),
"sender_email_address_cleanto_admin_email"=>urlencode("Электронная почта отправителя"),
"sender_name"=>urlencode("Имя отправителя"),
"service"=>urlencode("обслуживание"),
"service_add_ons_front_block_view"=>urlencode("Дополнения к дополнительным блокам"),
"service_add_ons_front_increase_decrease_view"=>urlencode("Расширения / Уменьшение внешнего вида службы"),
"service_description"=>urlencode("Описание услуг"),
"service_front_view"=>urlencode("Вид спереди службы"),
"service_image"=>urlencode("Вид спереди службы"),
"service_methods"=>urlencode("Методы обслуживания"),
"service_padding_time_after"=>urlencode("Время задержки обслуживания"),
"padding_time_after"=>urlencode("Время прокладки после"),
"service_padding_time_before"=>urlencode("Время отложенного обслуживания"),
"service_quantity"=>urlencode("Количество услуг"),
"service_rate"=>urlencode("Стоимость услуги"),
"service_title"=>urlencode("Название службы"),
"serviceaddons_name"=>urlencode("Имя ServiceAddOns"),
"services"=>urlencode("Сервисы"),
"services_information"=>urlencode("Информация об услугах"),
"set_email_reminder_buffer"=>urlencode("Установите буфер напоминаний электронной почты"),
"set_language"=>urlencode("Установить язык"),
"settings"=>urlencode("настройки"),
"show_all_bookings"=>urlencode("Показать все бронирования"),
"show_button_on_given_embeded_position"=>urlencode("Кнопка «Показать» в заданной позиции"),
"show_coupons_input_on_checkout"=>urlencode("Показывать данные о купонах при оформлении заказа"),
"show_on_a_button_click"=>urlencode("Показать на кнопке"),
"show_on_page_load"=>urlencode("Показать на странице загрузки"),
"signature"=>urlencode("Подпись"),
"sorry_wrong_email_or_password"=>urlencode("Извините Неправильная электронная почта или пароль"),
"start_date"=>urlencode("Дата начала"),
"status"=>urlencode("Положение дел"),
"submit"=>urlencode("Отправить"),
"staff_email_notification"=>urlencode("Уведомление по электронной почте персонала"),
"tax"=>urlencode("налог"),
"test_mode"=>urlencode("Тестовый режим"),
"text_color"=>urlencode("Цвет текста"),
"text_color_on_bg"=>urlencode("Цвет текста на bg"),
"terms_and_condition_link"=>urlencode("Ссылка на условия и условия"),
"this_week_breaks"=>urlencode("Эта неделя перерывов"),
"this_week_time_scheduling"=>urlencode("Расписание на эту неделю"),
"time_format"=>urlencode("Формат времени"),
"time_interval"=>urlencode("Временной интервал"),
"timezone"=>urlencode("Часовой пояс"),
"units"=>urlencode("Единицы"),
"unit_name"=>urlencode("Название единицы"),
"units_of_methods"=>urlencode("Единицы методов"),
"update"=>urlencode("Обновить"),
"update_appointment"=>urlencode("Обновить назначение"),
"update_promocode"=>urlencode("Обновить промокод"),
"username"=>urlencode("имя пользователя"),
"vaccum_cleaner"=>urlencode("Пылесос"),
"view_slots_by"=>urlencode("Просмотр слотов?"),
"week"=>urlencode("Неделю"),
"week_breaks"=>urlencode("Неделя"),
"week_time_scheduling"=>urlencode("Недельное расписание"),
"widget_loading_style"=>urlencode("Стиль загрузки виджета"),
"zip"=>urlencode("застежка-молния"),
"logout"=>urlencode("выйти"),
"to"=>urlencode("в"),
"add_new_promocode"=>urlencode("Добавить новый промокод"),
"create"=>urlencode("Создайте"),
"end_date"=>urlencode("Дата окончания"),
"end_time"=>urlencode("Время окончания"),
"labels_settings"=>urlencode("Настройки ярлыков"),
"limit"=>urlencode("предел"),
"max_limit"=>urlencode("Максимальный лимит"),
"start_time"=>urlencode("Время начала"),
"value"=>urlencode("Стоимость"),
"active"=>urlencode("активный"),
"appointment_reject_reason"=>urlencode("Причина отклонения назначений"),
"search"=>urlencode("Поиск"),
"custom_thankyou_page_url"=>urlencode("Пользовательский URL-адрес Thankyou"),
"price_per_unit"=>urlencode("Цена за единицу"),
"confirm_appointment"=>urlencode("Подтвердить встречу"),
"reject_reason"=>urlencode("Отклонить причину"),
"delete_this_appointment"=>urlencode("Удалить эту встречу"),
"close_notifications"=>urlencode("Закрыть Уведомления"),
"booking_cancel_reason"=>urlencode("Бронирование Отмена причины"),
"service_color_badge"=>urlencode("Значок цвета обслуживания"),
"manage_price_calculation_methods"=>urlencode("Управление методами расчета цены"),
"manage_addons_of_this_service"=>urlencode("Управление аддонами этой службы"),
"service_is_booked"=>urlencode("Забронировано обслуживание"),
"delete_this_service"=>urlencode("Удалить эту службу"),
"delete_service"=>urlencode("Удалить службу"),
"remove_image"=>urlencode("Удалить изображение"),
"remove_service_image"=>urlencode("Удаление служебного изображения"),
"company_name_is_used_for_invoice_purpose"=>urlencode("Название компании используется для целей счета-фактуры"),
"remove_company_logo"=>urlencode("Удалить логотип компании"),
"time_interval_is_helpful_to_show_time_difference_between_availability_time_slots"=>urlencode("Временной интервал полезен для отображения разницы во времени между временными интервалами доступности"),
"minimum_advance_booking_time_restrict_client_to_book_last_minute_booking_so_that_you_should_have_sufficient_time_before_appointment"=>urlencode("Минимальное время предварительного бронирования ограничивает клиента бронированием брони в последнюю минуту, так что у вас должно быть достаточно времени до назначения"),
"cancellation_buffer_helps_service_providers_to_avoid_last_minute_cancellation_by_their_clients"=>urlencode("Буфер отмены звонков помогает поставщикам услуг избегать отмены в последнюю минуту своих клиентов"),
"partial_payment_option_will_help_you_to_charge_partial_payment_of_total_amount_from_client_and_remaining_you_can_collect_locally"=>urlencode("Вариант частичной оплаты поможет вам частично оплатить общую сумму от клиента, а оставшаяся сумма может быть получена на месте"),
"allow_multiple_appointment_booking_at_same_time_slot_will_allow_you_to_show_availability_time_slot_even_you_have_booking_already_for_that_time"=>urlencode("Разрешить несколько заказов на бронирование в одном и том же временном интервале, позволит вам показывать доступный временной интервал, даже если вы уже заказываете за это время"),
"with_Enable_of_this_feature_Appointment_request_from_clients_will_be_auto_confirmed"=>urlencode("С включением этой функции запрос на назначение клиентов будет автоматически подтвержден"),
"write_html_code_for_the_right_side_panel"=>urlencode("Напишите HTML-код для правой боковой панели"),
"do_you_want_to_show_subheaders_below_the_headers"=>urlencode("Вы хотите показать подзаголовки ниже заголовков"),
"you_can_show_hide_coupon_input_on_checkout_form"=>urlencode("Вы можете отображать / скрывать вкладку купона в форме выписки"),
"with_this_feature_you_can_allow_a_visitor_to_book_appointment_without_registration"=>urlencode("С помощью этой функции вы можете позволить посетителю записаться на прием без регистрации"),
"paypal_api_username_can_get_easily_from_developer_paypal_com_account"=>urlencode("Имя пользователя API Paypal можно легко получить из учетной записи developer.paypal.com"),
"paypal_api_password_can_get_easily_from_developer_paypal_com_account"=>urlencode("Пароль API Paypal можно легко получить из учетной записи developer.paypal.com"),
"paypal_api_signature_can_get_easily_from_developer_paypal_com_account"=>urlencode("Подпись Paypal API может легко получить от аккаунта developer.paypal.com"),
"let_user_pay_through_credit_card_without_having_paypal_account"=>urlencode("Позвольте пользователю оплатить кредитную карту без учета Paypal"),
"you_can_enable_paypal_test_mode_for_sandbox_account_testing"=>urlencode("Вы можете включить режим проверки Paypal для тестирования учетной записи Sandbox"),
"you_can_enable_authorize_net_test_mode_for_sandbox_account_testing"=>urlencode("Вы можете включить режим авторизации Authorize.Net для тестирования учетной записи Sandbox"),
"edit_coupon_code"=>urlencode("Изменить код купона"),
"delete_promocode"=>urlencode("Удалить Promocode?"),
"coupon_code_will_work_for_such_limit"=>urlencode("Код купона будет работать для такого лимита"),
"coupon_code_will_work_for_such_date"=>urlencode("Код купона будет работать на такую ​​дату"),
"coupon_value_would_be_consider_as_percentage_in_percentage_mode_and_in_flat_mode_it_will_be_consider_as_amount_no_need_to_add_percentage_sign_it_will_auto_added"=>urlencode("Величина купона будет учитываться как процент в процентном режиме, и в плоском режиме это будет рассматриваться как сумма. Нет необходимости добавлять знак процента, он будет автоматически добавлен."),
"unit_is_booked"=>urlencode("Группа заказана"),
"delete_this_service_unit"=>urlencode("Удалить этот служебный блок?"),
"delete_service_unit"=>urlencode("Удалить служебный блок"),
"manage_unit_price"=>urlencode("Управление ценой за единицу"),
"extra_service_title"=>urlencode("Название дополнительной службы"),
"addon_is_booked"=>urlencode("Аддон забронирован"),
"delete_this_addon_service"=>urlencode("Удалить эту службу аддона?"),
"choose_your_addon_image"=>urlencode("Выберите свое изображение аддона"),
"addon_image"=>urlencode("Изображение Addon"),
"administrator_email"=>urlencode("Электронная почта администратора"),
"admin_profile_address"=>urlencode("Адрес"),
"default_country_code"=>urlencode("Код страны"),
"cancellation_policy"=>urlencode("Политика отмены"),
"transaction_id"=>urlencode("ID транзакции"),
"sms_reminder"=>urlencode("Напоминание SMS"),
"save_sms_settings"=>urlencode("Сохранить настройки SMS"),
"sms_service"=>urlencode("Служба SMS"),
"it_will_send_sms_to_service_provider_and_client_for_appointment_booking"=>urlencode("Он отправит sms поставщику услуг и клиенту для бронирования заявки"),
"twilio_account_settings"=>urlencode("Настройки учетной записи Twilio"),
"plivo_account_settings"=>urlencode("Настройки учетной записи Plivo"),
"account_sid"=>urlencode("SID учетной записи"),
"auth_token"=>urlencode("Auth Token"),
"twilio_sender_number"=>urlencode("Номер отправителя Twilio"),
"plivo_sender_number"=>urlencode("Номер отправителя Plivo"),
"twilio_sms_settings"=>urlencode("Настройки Twilio SMS"),
"plivo_sms_settings"=>urlencode("Настройки Plivo SMS"),
"twilio_sms_gateway"=>urlencode("Twilio SMS Gateway"),
"plivo_sms_gateway"=>urlencode("Plivo SMS Gateway"),
"send_sms_to_client"=>urlencode("Отправка SMS клиенту"),
"send_sms_to_admin"=>urlencode("Отправить SMS для администратора"),
"admin_phone_number"=>urlencode("Номер телефона администратора"),
"available_from_within_your_twilio_account"=>urlencode("Доступен из вашей учетной записи Twilio."),
"must_be_a_valid_number_associated_with_your_twilio_account"=>urlencode("Должно быть действительное число, связанное с вашей учетной записью Twilio."),
"enable_or_disable_send_sms_to_client_for_appointment_booking_info"=>urlencode("Включить или отключить, отправить SMS-сообщение клиенту для получения информации о бронировании."),
"enable_or_disable_send_sms_to_admin_for_appointment_booking_info"=>urlencode("Включить или отключить, отправить SMS-сообщение администратору для получения информации о бронировании."),
"updated_sms_settings"=>urlencode("Обновленные настройки SMS"),
"parking_availability_frontend_option_display_status"=>urlencode("Стоянка"),
"vaccum_cleaner_frontend_option_display_status"=>urlencode("Пылесос"),
"o_n"=>urlencode("На"),
"off"=>urlencode("от"),
"enable"=>urlencode("включить"),
"disable"=>urlencode("запрещать"),
"monthly"=>urlencode("ежемесячно"),
"weekly"=>urlencode("еженедельно"),
"email_template"=>urlencode("ШАБЛОН EMAIL"),
"sms_notification"=>urlencode("УВЕДОМЛЕНИЕ СМС"),
"sms_template"=>urlencode("ШАБЛОН SMS"),
"email_template_settings"=>urlencode("Настройки шаблона электронной почты"),
"client_email_templates"=>urlencode("Шаблон электронной почты клиента"),
"client_sms_templates"=>urlencode("Шаблон клиентских SMS"),
"admin_email_template"=>urlencode("Шаблон электронной почты администратора"),
"admin_sms_template"=>urlencode("Шаблон администрирования SMS"),
"tags"=>urlencode("Теги"),
"booking_date"=>urlencode("Дата бронирования"),
"service_name"=>urlencode("наименование услуги"),
"business_logo"=>urlencode("business_logo"),
"business_logo_alt"=>urlencode("business_logo_alt"),
"admin_name"=>urlencode("admin_name"),
"methodname"=>urlencode("method_name"),
"firstname"=>urlencode("имя"),
"lastname"=>urlencode("Фамилия"),
"client_email"=>urlencode("client_email"),
"vaccum_cleaner_status"=>urlencode("vaccum_cleaner_status"),
"parking_status"=>urlencode("parking_status"),
"app_remain_time"=>urlencode("app_remain_time"),
"reject_status"=>urlencode("reject_status"),
"save_template"=>urlencode("Сохранить шаблон"),
"default_template"=>urlencode("Шаблон по умолчанию"),
"sms_template_settings"=>urlencode("Настройки шаблона SMS"),
"secret_key"=>urlencode("Секретный ключ"),
"publishable_key"=>urlencode("Опубликованный ключ"),
"payment_form"=>urlencode("Форма оплаты"),
"api_login_id"=>urlencode("ИД входа в API"),
"transaction_key"=>urlencode("Ключ транзакции"),
"sandbox_mode"=>urlencode("Режим песочницы"),
"available_from_within_your_plivo_account"=>urlencode("Доступен с вашей учетной записи Plivo."),
"must_be_a_valid_number_associated_with_your_plivo_account"=>urlencode("Должно быть действительное число, связанное с вашей учетной записью Plivo."),
"whats_new"=>urlencode("Какие новости?"),
"company_phone"=>urlencode("Телефон"),
"company__name"=>urlencode("название компании"),
"booking_time"=>urlencode("booking_time"),
"company__email"=>urlencode("company_email"),
"company__address"=>urlencode("Адрес компании"),
"company__zip"=>urlencode("company_zip"),
"company__phone"=>urlencode("company_phone"),
"company__state"=>urlencode("company_state"),
"company__country"=>urlencode("company_country"),
"company__city"=>urlencode("company_city"),
"page_title"=>urlencode("Заголовок страницы"),
"client__zip"=>urlencode("client_zip"),
"client__state"=>urlencode("client_state"),
"client__city"=>urlencode("client_city"),
"client__address"=>urlencode("client_address"),
"client__phone"=>urlencode("client_phone"),
"company_logo_is_used_for_invoice_purpose"=>urlencode("Логотип компании используется на странице электронной почты и бронирования"),
"private_key"=>urlencode("Закрытый ключ"),
"seller_id"=>urlencode("ID продавца"),
"postal_codes_ed"=>urlencode("Вы можете включить или отключить функцию почтовых или почтовых кодов в соответствии с требованиями вашей страны, так как некоторые страны, такие как ОАЭ, не имеют почтового индекса."),
"postal_codes_info"=>urlencode("Вы можете указать почтовые коды двумя способами: # 1. Вы можете указать полные почтовые коды для соответствия, например, K1A232, L2A334, C3A4C4. # 2. Вы можете использовать частичные почтовые коды для записей соответствия wild card, например. K1A, L2A, C3, система будет соответствовать тем начальным буквам почтового кода на передней панели, и это позволит вам написать столько почтовых кодов."),
"first"=>urlencode("Первый"),
"second"=>urlencode("второй"),
"third"=>urlencode("В третьих"),
"fourth"=>urlencode("четвертый"),
"fifth"=>urlencode("пятый"),
"first_week"=>urlencode("Первая неделя"),
"second_week"=>urlencode("Вторая неделя"),
"third_week"=>urlencode("Третья неделя"),
"fourth_week"=>urlencode("В-четвертых, неделя"),
"fifth_week"=>urlencode("Пятая неделя-"),
"this_week"=>urlencode("На этой неделе"),
"monday"=>urlencode("понедельник"),
"tuesday"=>urlencode("вторник"),
"wednesday"=>urlencode("среда"),
"thursday"=>urlencode("Четверг"),
"friday"=>urlencode("пятница"),
"saturday"=>urlencode("суббота"),
"sunday"=>urlencode("Воскресенье"),
"appointment_request"=>urlencode("Запрос на назначение"),
"appointment_approved"=>urlencode("Утверждение о назначении"),
"appointment_rejected"=>urlencode("Назначение отклонено"),
"appointment_cancelled_by_you"=>urlencode("Назначение, отмененное вами"),
"appointment_rescheduled_by_you"=>urlencode("Назначение, перенесенное вами"),
"client_appointment_reminder"=>urlencode("Напоминание о назначении клиентов"),
"new_appointment_request_requires_approval"=>urlencode("Новый запрос на назначение требует одобрения"),
"appointment_cancelled_by_customer"=>urlencode("Назначение отменено клиентом"),
"appointment_rescheduled_by_customer"=>urlencode("Назначение, перенесенное клиентом"),
"admin_appointment_reminder"=>urlencode("Напоминание о назначении администратора"),
"off_days_added_successfully"=>urlencode("Удаленные дни успешно"),
"off_days_deleted_successfully"=>urlencode("Удаленные дни успешно удалены"),
"sorry_not_available"=>urlencode("Извините, не найдено"),
"success"=>urlencode("успех"),
"failed"=>urlencode("Не смогли"),
"once"=>urlencode("однажды"),
"Bi_Monthly"=>urlencode("Раз в два месяца"),
"Fortnightly"=>urlencode("двухнедельный"),
"Recurrence_Type"=>urlencode("Тип повторения"),
"bi_weekly"=>urlencode("Bi-Weekly"),
"Daily"=>urlencode("Daily"),
"guest_customers_bookings"=>urlencode("Заказ клиентов"),
"existing_and_new_user_checkout"=>urlencode("Существующий и новый пользовательский чек"),
"it_will_allow_option_for_user_to_get_booking_with_new_user_or_existing_user"=>urlencode("Это позволит пользователю получить заказ с новым пользователем или существующим пользователем"),
"0_1"=>urlencode("01"),
"1_1"=>urlencode("1.1"),
"1_2"=>urlencode("1.2"),
"0_2"=>urlencode("02"),
"free"=>urlencode("Свободно"),
"show_company_address_in_header"=>urlencode("Показать адрес компании в заголовке"),
"calendar_week"=>urlencode("Неделю"),
"calendar_month"=>urlencode("Месяц"),
"calendar_day"=>urlencode("День"),
"calendar_today"=>urlencode("Cегодня"),
"restore_default"=>urlencode("Сброс настроек"),
"scrollable_cart"=>urlencode("Прокручиваемая тележка"),
"merchant_key"=>urlencode("Торговый ключ"),
"salt_key"=>urlencode("Соляной ключ"),
"textlocal_sms_gateway"=>urlencode("Текстовый шлюз SMS"),
"textlocal_sms_settings"=>urlencode("Настройки текстового SMS"),
"textlocal_account_settings"=>urlencode("Настройки учетной записи Textlocal"),
"account_username"=>urlencode("Имя учетной записи"),
"account_hash_id"=>urlencode("Идентификатор учетной записи аккаунта"),
"email_id_registered_with_you_textlocal"=>urlencode("Предоставьте свою электронную почту, зарегистрированную с помощью textlocal"),
"hash_id_provided_by_textlocal"=>urlencode("Идентификатор hash, предоставленный textlocal"),
"bank_transfer"=>urlencode("Банковский перевод"),
"bank_name"=>urlencode("Название банка"),
"account_name"=>urlencode("Имя пользователя"),
"account_number"=>urlencode("Номер аккаунта"),
"branch_code"=>urlencode("Код филиала"),
"ifsc_code"=>urlencode("Код IFSC"),
"bank_description"=>urlencode("Описание банка"),
"your_cart_items"=>urlencode("Ваша корзина"),
"show_how_will_we_get_in"=>urlencode("Покажите, как мы получим"),
"show_description"=>urlencode("Покажите описание"),
"bank_details"=>urlencode("Информация о банке"),
"ok_remove_sample_data"=>urlencode("ОК"),
"book_appointment"=>urlencode("Назначение книги"),
"remove_sample_data_message"=>urlencode("Вы пытаетесь удалить данные образца. Если вы удалите данные образца, ваше бронирование, связанное с образцовыми услугами, будет удалено навсегда. Чтобы продолжить, нажмите «ОК»"),
"recommended_image_type_jpg_jpeg_png_gif"=>urlencode("(Рекомендуемый тип изображения jpg, jpeg, png, gif)"),
"authetication"=>urlencode("Аутентификация"),
"encryption_type"=>urlencode("Тип шифрования"),
"plain"=>urlencode("гладкий"),
"true"=>urlencode("Правда"),
"false"=>urlencode("Ложь"),
"change_calculation_policy"=>urlencode("Изменить расчет"),
"multiply"=>urlencode("Умножение"),
"equal"=>urlencode("равных"),
"warning"=>urlencode("Предупреждение!"),
"field_name"=>urlencode("Имя поля"),
"enable_disable"=>urlencode("Включить выключить"),
"required"=>urlencode("необходимые"),
"min_length"=>urlencode("Минимальная длина"),
"max_length"=>urlencode("Максимальная длина"),
"appointment_details_section"=>urlencode("Раздел сведений о назначении"),
"if_you_are_having_booking_system_which_need_the_booking_address_then_please_make_this_field_enable_or_else_it_will_not_able_to_take_the_booking_address_and_display_blank_address_in_the_booking"=>urlencode("Если у вас есть система бронирования, которой нужен адрес бронирования, пожалуйста, включите это поле, иначе он не сможет принять адрес бронирования и отобразить пустой адрес в бронировании"),
"front_language_dropdown"=>urlencode("Выпадающий язык переднего плана"),
"enabled"=>urlencode("Включено"),
"vaccume_cleaner"=>urlencode("Пылесос"),
"staff_members"=>urlencode("Штатные сотрудники"),
"add_new_staff_member"=>urlencode("Добавить нового сотрудника"),
"role"=>urlencode("Роль"),
"staff"=>urlencode("Сотрудники"),
"admin"=>urlencode("Администратор"),
"service_details"=>urlencode("Сведения о сервисе"),
"technical_admin"=>urlencode("Технический администратор"),
"enable_booking"=>urlencode("Включить бронирование"),
"flat_commission"=>urlencode("Плоская комиссия"),
"manageable_form_fields_front_booking_form"=>urlencode("Поля для управляемых форм для формы бланка заказа"),
"manageable_form_fields"=>urlencode("Управляемые поля форм"),
"sms"=>urlencode("смс"),
"crm"=>urlencode("CRM"),
"message"=>urlencode("Сообщение"),
"send_message"=>urlencode("Отправить сообщение"),
"all_messages"=>urlencode("Все сообщения"),
"subject"=>urlencode("Предмет"),
"add_attachment"=>urlencode("Добавить приложение"),
"send"=>urlencode("послать"),
"close"=>urlencode("Закрыть"),
"delete_this_customer?"=>urlencode("Удалить этого клиента?"),
"yes"=>urlencode("да"),
"add_new_customer"=>urlencode("Добавить нового клиента"),
"attachment"=>urlencode("прикрепление"),
"date"=>urlencode("Дата"),
"see_attachment"=>urlencode("См вложение"),
"no_attachment"=>urlencode("Нет прикрепления"),
"ct_special_offer"=>urlencode("Специальное предложение"),
"ct_special_offer_text"=>urlencode("Текст специального предложения"),
);

$error_labels_ru_RU = array (
"language_status_change_successfully"=>urlencode("Изменение статуса языка успешно"),
"commission_amount_should_not_be_greater_then_order_amount"=>urlencode("Сумма комиссии не должна превышать сумму заказа"),
"please_enter_merchant_ID"=>urlencode("Введите идентификатор продавца"),
"please_enter_secure_key"=>urlencode("Введите безопасный ключ"),
"please_enter_google_calender_admin_url"=>urlencode("Введите URL-адрес Google calender admin"),
"please_enter_google_calender_frontend_url"=>urlencode("Введите URL-адрес URL-адреса Google Calendar"),
"please_enter_google_calender_client_secret"=>urlencode("Введите секретный ключ клиента Google Calender"),
"please_enter_google_calender_client_ID"=>urlencode("Введите идентификатор клиента Google Calender"),
"please_enter_google_calender_ID"=>urlencode("Введите идентификатор календаря Google"),
"you_cannot_book_on_past_date"=>urlencode("Вы не можете забронировать последнюю дату"),
"Invalid_Image_Type"=>urlencode("Недопустимый тип изображения"),
"seo_settings_updated_successfully"=>urlencode("Настройки SEO обновлены успешно"),
"customer_deleted_successfully"=>urlencode("Удаленный клиент удален"),
"please_enter_below_36_characters"=>urlencode("Введите ниже 36 символов"),
"are_you_sure_you_want_to_delete_client"=>urlencode("Вы уверены, что хотите удалить клиента?"),
"please_select_atleast_one_unit"=>urlencode("Пожалуйста, выберите по крайней мере одну единицу"),
"atleast_one_payment_method_should_be_enable"=>urlencode("По крайней мере один способ оплаты должен быть включен"),
"appointment_booking_confirm"=>urlencode("Подтверждение бронирования"),
"appointment_booking_rejected"=>urlencode("Назначение бронирования отклонено"),
"booking_cancel"=>urlencode("Отклонено"),
"appointment_marked_as_no_show"=>urlencode("Назначение, помеченное как нет"),
"appointment_reschedules_successfully"=>urlencode("Успешно назначены сроки"),
"booking_deleted"=>urlencode("Бронирование удалено"),
"break_end_time_should_be_greater_than_start_time"=>urlencode("Время прерывания должно быть больше, чем время начала"),
"cancel_by_client"=>urlencode("Отменить клиентом"),
"cancelled_by_service_provider"=>urlencode("Отменено поставщиком услуг"),
"design_set_successfully"=>urlencode("Дизайн успешно"),
"end_break_time_updated"=>urlencode("Обновлено время окончания"),
"enter_alphabets_only"=>urlencode("Введите только алфавиты"),
"enter_only_alphabets"=>urlencode("Введите только алфавиты"),
"enter_only_alphabets_numbers"=>urlencode("Введите только алфавиты / цифры"),
"enter_only_digits"=>urlencode("Введите только цифры"),
"enter_valid_url"=>urlencode("Введите действительный адрес"),
"enter_only_numeric"=>urlencode("Введите только числовые"),
"enter_proper_country_code"=>urlencode("Введите правильный код страны"),
"frequently_discount_status_updated"=>urlencode("Часто обновляется статус скидки"),
"frequently_discount_updated"=>urlencode("Часто обновляется скидка"),
"manage_addons_service"=>urlencode("Управление дополнительными услугами"),
"maximum_file_upload_size_2_mb"=>urlencode("Максимальный размер загрузки файла 2 МБ"),
"method_deleted_successfully"=>urlencode("Метод удален успешно"),
"method_inserted_successfully"=>urlencode("Метод вставлен успешно"),
"minimum_file_upload_size_1_kb"=>urlencode("Минимальный размер загрузки файла 1 КБ"),
"off_time_added_successfully"=>urlencode("Время добавления добавлено успешно"),
"only_jpeg_png_and_gif_images_allowed"=>urlencode("Разрешены только jpeg, png и gif."),
"only_jpeg_png_gif_zip_and_pdf_allowed"=>urlencode("Разрешены только jpeg, png, gif, zip и pdf"),
"please_wait_while_we_send_all_your_message"=>urlencode("Подождите, пока мы отправим все ваши сообщения"),
"please_enable_email_to_client"=>urlencode("Пожалуйста, включите электронную почту для клиента."),
"please_enable_sms_gateway"=>urlencode("Включите SMS-шлюз."),
"please_enable_client_notification"=>urlencode("Включите уведомление клиента."),
"password_must_be_8_character_long"=>urlencode("Пароль должен иметь длину 8 символов"),
"password_should_not_exist_more_then_20_characters"=>urlencode("Пароль не должен содержать более 20 символов"),
"please_assign_base_price_for_unit"=>urlencode("Пожалуйста, назначьте базовую цену для единицы"),
"please_assign_price"=>urlencode("Укажите цену"),
"please_assign_qty"=>urlencode("Укажите количество"),
"please_enter_api_password"=>urlencode("Введите пароль API"),
"please_enter_api_username"=>urlencode("Введите имя API"),
"please_enter_color_code"=>urlencode("Введите код цвета"),
"please_enter_country"=>urlencode("Пожалуйста, введите страну"),
"please_enter_coupon_limit"=>urlencode("Укажите лимит купона"),
"please_enter_coupon_value"=>urlencode("Укажите сумму купона"),
"please_enter_coupon_code"=>urlencode("Введите код купона"),
"please_enter_email"=>urlencode("Введите email"),
"please_enter_fullname"=>urlencode("Введите имя"),
"please_enter_maxlimit"=>urlencode("Введите maxLimit"),
"please_enter_method_title"=>urlencode("Введите название метода"),
"please_enter_name"=>urlencode("Введите имя"),
"please_enter_only_numeric"=>urlencode("Введите только числовые"),
"please_enter_proper_base_price"=>urlencode("Укажите правильную базовую цену"),
"please_enter_proper_name"=>urlencode("Введите собственное имя"),
"please_enter_proper_title"=>urlencode("Введите правильное название"),
"please_enter_publishable_key"=>urlencode("Пожалуйста, введите опубликованный ключ"),
"please_enter_secret_key"=>urlencode("Введите секретный ключ"),
"please_enter_service_title"=>urlencode("Введите название сервиса"),
"please_enter_signature"=>urlencode("Пожалуйста, введите подпись"),
"please_enter_some_qty"=>urlencode("Введите несколько цифр"),
"please_enter_title"=>urlencode("Введите название"),
"please_enter_unit_title"=>urlencode("Введите название устройства"),
"please_enter_valid_country_code"=>urlencode("Введите действующий код страны"),
"please_enter_valid_service_title"=>urlencode("Введите действительное название сервиса"),
"please_enter_valid_price"=>urlencode("Укажите действительную цену"),
"please_enter_zipcode"=>urlencode("Введите почтовый индекс"),
"please_enter_state"=>urlencode("Введите состояние"),
"please_retype_correct_password"=>urlencode("Пожалуйста, введите правильный пароль"),
"please_select_porper_time_slots"=>urlencode("Выберите порты времени"),
"please_select_time_between_day_availability_time"=>urlencode("Выберите время между днем ​​доступности"),
"please_valid_value_for_discount"=>urlencode("Пожалуйста, действуйте на скидку"),
"please_enter_confirm_password"=>urlencode("Введите пароль подтверждения"),
"please_enter_new_password"=>urlencode("Введите новый пароль"),
"please_enter_old_password"=>urlencode("Введите старый пароль"),
"please_enter_valid_number"=>urlencode("Введите действительный номер"),
"please_enter_valid_number_with_country_code"=>urlencode("Введите действительный номер с кодом страны"),
"please_select_end_time_greater_than_start_time"=>urlencode("Выберите время окончания больше времени начала"),
"please_select_end_time_less_than_start_time"=>urlencode("Выберите время окончания меньше времени начала"),
"please_select_a_crop_region_and_then_press_upload"=>urlencode("Выберите область обрезки, а затем нажмите кнопку"),
"please_select_a_valid_image_file_jpg_and_png_are_allowed"=>urlencode("Выберите допустимый файл изображения jpg и png."),
"profile_updated_successfully"=>urlencode("Профиль обновлен успешно"),
"qty_rule_deleted"=>urlencode("Правило количества удалено"),
"record_deleted_successfully"=>urlencode("Запись успешно удалена"),
"record_updated_successfully"=>urlencode("Запись успешно обновлена"),
"rescheduled"=>urlencode("Перенесенные"),
"schedule_updated_to_monthly"=>urlencode("Расписание обновлено до ежемесячного"),
"schedule_updated_to_weekly"=>urlencode("Расписание обновлено до Weekly"),
"sorry_method_already_exist"=>urlencode("Метод извинения уже существует"),
"sorry_no_notification"=>urlencode("Извините, у вас нет предстоящего назначения"),
"sorry_promocode_already_exist"=>urlencode("Извините, promocode уже существует"),
"sorry_unit_already_exist"=>urlencode("Избыточная единица уже существует"),
"sorry_we_are_not_available"=>urlencode("Извините, мы недоступны"),
"start_break_time_updated"=>urlencode("Время начала обновления"),
"status_updated"=>urlencode("Статус обновлен"),
"time_slots_updated_successfully"=>urlencode("Временные интервалы успешно обновлены"),
"unit_inserted_successfully"=>urlencode("Устройство успешно вставлено"),
"units_status_updated"=>urlencode("Обновлен статус единиц"),
"updated_appearance_settings"=>urlencode("Обновлены настройки внешнего вида"),
"updated_company_details"=>urlencode("Обновленная информация о компании"),
"updated_email_settings"=>urlencode("Обновлены настройки электронной почты"),
"updated_general_settings"=>urlencode("Обновленные общие настройки"),
"updated_payments_settings"=>urlencode("Обновленные настройки платежей"),
"your_old_password_incorrect"=>urlencode("Неверный пароль"),
"please_enter_minimum_5_chars"=>urlencode("Введите не менее 5 символов"),
"please_enter_maximum_10_chars"=>urlencode("Введите максимум 10 символов"),
"please_enter_postal_code"=>urlencode("Введите почтовый индекс"),
"please_select_a_service"=>urlencode("Выберите услугу"),
"please_select_units_and_addons"=>urlencode("Выберите единицы и дополнения"),
"please_select_units_or_addons"=>urlencode("Выберите единицы или дополнения"),
"please_login_to_complete_booking"=>urlencode("Пожалуйста, авторизуйтесь для завершения бронирования"),
"please_select_appointment_date"=>urlencode("Выберите дату встречи"),
"please_accept_terms_and_conditions"=>urlencode("Примите условия"),
"incorrect_email_address_or_password"=>urlencode("Неверный адрес электронной почты или пароль"),
"please_enter_valid_email_address"=>urlencode("Пожалуйста, введите действующий адрес электронной почты"),
"please_enter_email_address"=>urlencode("Введите адрес электронной почты"),
"please_enter_password"=>urlencode("Пожалуйста введите пароль"),
"please_enter_minimum_8_characters"=>urlencode("Введите минимум 8 символов"),
"please_enter_maximum_15_characters"=>urlencode("Введите максимум 15 символов"),
"please_enter_first_name"=>urlencode("Введите имя"),
"please_enter_only_alphabets"=>urlencode("Введите только алфавиты"),
"please_enter_minimum_2_characters"=>urlencode("Введите минимум 2 символа"),
"please_enter_last_name"=>urlencode("Введите фамилию"),
"email_already_exists"=>urlencode("Эл. адрес уже существует"),
"please_enter_phone_number"=>urlencode("Введите номер телефона"),
"please_enter_only_numerics"=>urlencode("Введите только цифры"),
"please_enter_minimum_10_digits"=>urlencode("Введите минимум 10 цифр"),
"please_enter_maximum_14_digits"=>urlencode("Введите максимум 14 цифр"),
"please_enter_address"=>urlencode("Введите адрес"),
"please_enter_minimum_20_characters"=>urlencode("Пожалуйста, введите минимум 20 символов"),
"please_enter_zip_code"=>urlencode("Введите почтовый индекс"),
"please_enter_proper_zip_code"=>urlencode("Введите правильный почтовый индекс"),
"please_enter_minimum_5_digits"=>urlencode("Введите минимум 5 цифр"),
"please_enter_maximum_7_digits"=>urlencode("Введите максимум 7 цифр"),
"please_enter_city"=>urlencode("Пожалуйста, введите город"),
"please_enter_proper_city"=>urlencode("Пожалуйста, введите правильный город"),
"please_enter_maximum_48_characters"=>urlencode("Введите максимум 48 символов"),
"please_enter_proper_state"=>urlencode("Укажите правильное состояние"),
"please_enter_contact_status"=>urlencode("Введите контактный статус"),
"please_enter_maximum_100_characters"=>urlencode("Введите максимум 100 символов"),
"your_cart_is_empty_please_add_cleaning_services"=>urlencode("Ваша корзина пуста, пожалуйста, добавьте услуги по уборке"),
"coupon_expired"=>urlencode("Срок действия купона истек"),
"invalid_coupon"=>urlencode("Недействительный купон"),
"our_service_not_available_at_your_location"=>urlencode("Наш сервис недоступен в вашем регионе"),
"please_enter_proper_postal_code"=>urlencode("Введите правильный почтовый индекс"),
"invalid_email_id_please_register_first"=>urlencode("Недействительный идентификатор электронной почты сначала зарегистрируйтесь"),
"your_password_send_successfully_at_your_registered_email_id"=>urlencode("Ваш пароль будет успешно отправлен на ваш зарегистрированный идентификатор электронной почты"),
"your_password_reset_successfully_please_login"=>urlencode("Ваш пароль будет успешно выполнен"),
"new_password_and_retype_new_password_mismatch"=>urlencode("Новый пароль и повторное сопоставление нового пароля"),
"new"=>urlencode("новый"),
"your_reset_password_link_expired"=>urlencode("Истекает ссылка на ваш пароль для сброса пароля"),
"front_display_language_changed"=>urlencode("Язык фронтального дисплея изменен"),
"updated_front_display_language_and_update_labels"=>urlencode("Обновлены передние языки отображения и метки обновлений"),
"please_enter_only_7_chars_maximum"=>urlencode("Введите максимум 7 символов"),
"please_enter_maximum_20_chars"=>urlencode("Введите максимум 20 символов"),
"record_inserted_successfully"=>urlencode("Запись успешно завершена"),
"please_enter_account_sid"=>urlencode("Пожалуйста, введите Accout SID"),
"please_enter_auth_token"=>urlencode("Введите Auth Token"),
"please_enter_sender_number"=>urlencode("Введите номер отправителя"),
"please_enter_admin_number"=>urlencode("Введите номер администратора"),
"sorry_service_already_exist"=>urlencode("Уже есть сервис"),
"please_enter_api_login_id"=>urlencode("Введите идентификатор входа в систему"),
"please_enter_transaction_key"=>urlencode("Введите ключ транзакции"),
"please_enter_sms_message"=>urlencode("Введите sms-сообщение"),
"please_enter_email_message"=>urlencode("Введите адрес электронной почты"),
"please_enter_private_key"=>urlencode("Введите секретный ключ"),
"please_enter_seller_id"=>urlencode("Введите идентификатор продавца"),
"please_enter_valid_value_for_discount"=>urlencode("Введите действительное значение для скидки"),
"password_must_be_only_10_characters"=>urlencode("Пароль должен быть всего 10 символов"),
"password_at_least_have_8_characters"=>urlencode("Пароль не менее 8 символов"),
"please_enter_retype_new_password"=>urlencode("Введите новый пароль"),
"your_password_send_successfully_at_your_email_id"=>urlencode("Ваш пароль успешно отправляется по электронной почте"),
"please_select_expiry_date"=>urlencode("Выберите дату истечения срока действия"),
"please_enter_merchant_key"=>urlencode("Введите ключ продавца"),
"please_enter_salt_key"=>urlencode("Пожалуйста, введите Соляной ключ"),
"please_enter_account_username"=>urlencode("Введите имя пользователя учетной записи"),
"please_enter_account_hash_id"=>urlencode("Введите идентификатор учетной записи"),
"invalid_values"=>urlencode("Недопустимые значения"),
"please_select_atleast_one_checkout_method"=>urlencode("Пожалуйста, выберите по крайней мере один способ оплаты"),
);

$extra_labels_ru_RU = array (
"please_enter_minimum_3_chars"=>urlencode("Пожалуйста, введите минимум 3 символа"),
"invoice"=>urlencode("ВЫСТАВЛЕННЫЙ СЧЕТ"),
"invoice_to"=>urlencode("СЧЕТ"),
"invoice_date"=>urlencode("Дата счета"),
"cash"=>urlencode("ДЕНЕЖНЫЕ СРЕДСТВА"),
"service_name"=>urlencode("наименование услуги"),
"qty"=>urlencode("Кол-во"),
"booked_on"=>urlencode("Забронировано"),
);

$front_error_labels_ru_RU = array (
"min_ff_ps"=>urlencode("Введите минимум 8 символов"),
"max_ff_ps"=>urlencode("Введите максимум 10 символов"),
"req_ff_fn"=>urlencode("Введите имя"),
"min_ff_fn"=>urlencode("Введите минимум 3 символа"),
"max_ff_fn"=>urlencode("Введите максимум 15 символов"),
"req_ff_ln"=>urlencode("Введите фамилию"),
"min_ff_ln"=>urlencode("Введите минимум 3 символа"),
"max_ff_ln"=>urlencode("Введите максимум 15 символов"),
"req_ff_ph"=>urlencode("Введите номер телефона"),
"min_ff_ph"=>urlencode("Введите не менее 9 символов"),
"max_ff_ph"=>urlencode("Введите максимум 15 символов"),
"req_ff_sa"=>urlencode("Укажите адрес улицы"),
"min_ff_sa"=>urlencode("Введите не менее 10 символов"),
"max_ff_sa"=>urlencode("Введите максимум 40 символов"),
"req_ff_zp"=>urlencode("Введите почтовый индекс"),
"min_ff_zp"=>urlencode("Введите минимум 3 символа"),
"max_ff_zp"=>urlencode("Введите максимум 7 символов"),
"req_ff_ct"=>urlencode("Пожалуйста, введите город"),
"min_ff_ct"=>urlencode("Введите минимум 3 символа"),
"max_ff_ct"=>urlencode("Введите максимум 15 символов"),
"req_ff_st"=>urlencode("Введите состояние"),
"min_ff_st"=>urlencode("Введите минимум 3 символа"),
"max_ff_st"=>urlencode("Введите максимум 15 символов"),
"req_ff_srn"=>urlencode("Введите примечания"),
"min_ff_srn"=>urlencode("Введите не менее 10 символов"),
"max_ff_srn"=>urlencode("Введите максимум 70 символов"),
"Transaction_failed_please_try_again"=>urlencode("Не удалось выполнить транзакцию. Повторите попытку."),
"Please_Enter_valid_card_detail"=>urlencode("Введите действительные данные карты"),
);

$language_front_arr_ru_RU = base64_encode(serialize($label_data_ru_RU));
$language_admin_arr_ru_RU = base64_encode(serialize($admin_labels_ru_RU));
$language_error_arr_ru_RU = base64_encode(serialize($error_labels_ru_RU));
$language_extra_arr_ru_RU = base64_encode(serialize($extra_labels_ru_RU));
$language_form_error_arr_ru_RU = base64_encode(serialize($front_error_labels_ru_RU));

$insert_default_lang_ru_RU = "insert into `ct_languages` (`id`,`label_data`,`language`, `admin_labels`, `error_labels`, `extra_labels`, `front_error_labels`,`language_status`) values(NULL,'" . $language_front_arr_ru_RU . "','ru_RU','" . $language_admin_arr_ru_RU . "','" . $language_error_arr_ru_RU . "','" . $language_extra_arr_ru_RU . "','" . $language_form_error_arr_ru_RU . "','Y')";
mysqli_query($this->conn, $insert_default_lang_ru_RU);

/** Arabic Language **/
$label_data_ar = array (
"none_available"=>urlencode("لا شيء متاح"),
"appointment_zip"=>urlencode("الموعد البريدي"),
"appointment_city"=>urlencode("مدينة التعيين"),
"appointment_state"=>urlencode("دولة تعيين"),
"appointment_address"=>urlencode("عنوان المواعيد"),
"guest_user"=>urlencode("حساب زائر"),
"service_usage_methods"=>urlencode("طرق استخدام الخدمة"),
"paypal"=>urlencode("باي بال"),
"please_check_for_the_below_missing_information"=>urlencode("يرجى التحقق من المعلومات المفقودة أدناه."),
"please_provide_company_details_from_the_admin_panel"=>urlencode("يرجى تقديم تفاصيل الشركة من لوحة الادارة."),
"please_add_some_services_methods_units_addons_from_the_admin_panel"=>urlencode("الرجاء إضافة بعض الخدمات ، والأساليب ، والوحدات ، والإضافات من لوحة الإدارة."),
"please_add_time_scheduling_from_the_admin_panel"=>urlencode("الرجاء إضافة جدولة الوقت من لوحة الادارة."),
"please_complete_configurations_before_you_created_website_embed_code"=>urlencode("يرجى إكمال التهيئة قبل إنشاء شفرة تضمين موقع الويب."),
"cvc"=>urlencode("CVC"),
"mm_yyyy"=>urlencode("(MM / YYYY)"),
"expiry_date_or_csv"=>urlencode("تاريخ انتهاء الصلاحية أو CSV"),
"street_address_placeholder"=>urlencode("مثلا سنترال افي"),
"zip_code_placeholder"=>urlencode("مثلا 90001"),
"city_placeholder"=>urlencode("على سبيل المثال. لوس أنجلوس"),
"state_placeholder"=>urlencode("على سبيل المثال. CA"),
"payumoney"=>urlencode("PayUmoney"),
"same_as_above"=>urlencode("نفس ما سبق"),
"sun"=>urlencode("شمس"),
"mon"=>urlencode("الإثنين"),
"tue"=>urlencode("الثلاثاء"),
"wed"=>urlencode("تزوج"),
"thu"=>urlencode("الخميس"),
"fri"=>urlencode("الجمعة"),
"sat"=>urlencode("جلسنا"),
"su"=>urlencode("سو"),
"mo"=>urlencode("مو"),
"tu"=>urlencode("تو"),
"we"=>urlencode("نحن"),
"th"=>urlencode("ث"),
"fr"=>urlencode("الاب"),
"sa"=>urlencode("سا"),
"my_bookings"=>urlencode("حجوزاتي"),
"your_postal_code"=>urlencode("الرمز البريدي أو الرمز البريدي"),
"where_would_you_like_us_to_provide_service"=>urlencode("أين تريد منا تقديم الخدمة؟"),
"choose_service"=>urlencode("اختر الخدمة"),
"how_often_would_you_like_us_provide_service"=>urlencode("كم مرة تريد منا تقديم الخدمة؟"),
"when_would_you_like_us_to_come"=>urlencode("متى تريد منا أن نأتي؟"),
"today"=>urlencode("اليوم"),
"your_personal_details"=>urlencode("تفاصيلك الشخصية"),
"existing_user"=>urlencode("مستخدم موجود"),
"new_user"=>urlencode("مستخدم جديد"),
"preferred_email"=>urlencode("البريد الإلكتروني المفضل"),
"preferred_password"=>urlencode("كلمة السر المفضلة"),
"your_valid_email_address"=>urlencode("عنوان بريدك الإلكتروني صالح"),
"first_name"=>urlencode("الاسم الاول"),
"your_first_name"=>urlencode("اسمك الأول"),
"last_name"=>urlencode("الكنية"),
"your_last_name"=>urlencode("اسمك الاخير"),
"street_address"=>urlencode("عنوان الشارع"),
"cleaning_service"=>urlencode("خدمة التنظيف"),
"please_select_method"=>urlencode("يرجى اختيار الطريقة"),
"zip_code"=>urlencode("الرمز البريدي"),
"city"=>urlencode("مدينة"),
"state"=>urlencode("حالة"),
"special_requests_notes"=>urlencode("الطلبات الخاصة (ملاحظات)"),
"do_you_have_a_vaccum_cleaner"=>urlencode("هل لديك مكنسة كهربائية؟"),
"assign_appointment_to_staff"=>urlencode("تعيين موعد للموظفين"),
"delete_member"=>urlencode("حذف العضو؟"),
"yes"=>urlencode("نعم فعلا"),
"no"=>urlencode("لا"),
"preferred_payment_method"=>urlencode("يفضل طريقة الدفع"),
"please_select_one_payment_method"=>urlencode("يرجى اختيار طريقة دفع واحدة"),
"partial_deposit"=>urlencode("الوديعة الجزئية"),
"remaining_amount"=>urlencode("الكمية المتبقية"),
"please_read_our_terms_and_conditions_carefully"=>urlencode("يرجى قراءة الشروط والأحكام بعناية"),
"do_you_have_parking"=>urlencode("هل لديك موقف سيارات؟"),
"how_will_we_get_in"=>urlencode("كيف سندخل؟"),
"i_will_be_at_home"=>urlencode("سأكون في المنزل"),
"please_call_me"=>urlencode("اتصل بي من فضلك"),
"recurring_discounts_apply_from_the_second_cleaning_onward"=>urlencode("تنطبق الخصومات المتكررة من التنظيف الثاني فصاعدًا."),
"please_provide_your_address_and_contact_details"=>urlencode("يرجى تقديم عنوانك وتفاصيل الاتصال بك"),
"you_are_logged_in_as"=>urlencode("أنت مسجل دخولك باسم"),
"the_key_is_with_the_doorman"=>urlencode("المفتاح هو مع البواب"),
"other"=>urlencode("آخر"),
"have_a_promocode"=>urlencode("هل يمتلك الرمز الترويجي؟"),
"apply"=>urlencode("تطبيق"),
"applied_promocode"=>urlencode("تطبيق Promocode"),
"complete_booking"=>urlencode("الحجز الكامل"),
"cancellation_policy"=>urlencode("سياسة الإلغاء"),
"cancellation_policy_header"=>urlencode("رأس سياسة الإلغاء"),
"cancellation_policy_textarea"=>urlencode("سياسة الإلغاء"),
"free_cancellation_before_redemption"=>urlencode("إلغاء مجاني قبل الاسترداد"),
"show_more"=>urlencode("أظهر المزيد"),
"please_select_service"=>urlencode("يرجى اختيار الخدمة"),
"choose_your_service_and_property_size"=>urlencode("اختر الخدمة وحجم العقار"),
"choose_your_service"=>urlencode("اختر خدمتك"),
"please_configure_first_cleaning_services_and_settings_in_admin_panel"=>urlencode("يرجى تكوين أول خدمات التنظيف والإعدادات في لوحة الإدارة"),
"i_have_read_and_accepted_the"=>urlencode("لقد قرأت وقبلت"),
"terms_and_condition"=>urlencode("البنود و الظروف"),
"and"=>urlencode("و"),
"updated_labels"=>urlencode("التصنيفات المحدثة"),
"privacy_policy"=>urlencode("سياسة الخصوصية"),
"please_fill_all_the_company_informations_and_add_some_services_and_addons"=>urlencode("التكوينات المطلوبة غير مكتملة."),
"booking_summary"=>urlencode("ملخص الكتاب"),
"your_email"=>urlencode("بريدك الالكتروني"),
"enter_email_to_login"=>urlencode("أدخل البريد الإلكتروني لتسجيل الدخول"),
"your_password"=>urlencode("كلمة السر خاصتك"),
"enter_your_password"=>urlencode("ادخل رقمك السري"),
"forget_password"=>urlencode("نسيت كلمة المرور؟"),
"reset_password"=>urlencode("إعادة ضبط كلمة المرور"),
"enter_your_email_and_we_send_you_instructions_on_resetting_your_password"=>urlencode("أدخل بريدك الإلكتروني وسنرسل لك تعليمات حول إعادة تعيين كلمة المرور الخاصة بك."),
"registered_email"=>urlencode("بريد الكتروني مسجل"),
"send_mail"=>urlencode("ارسل بريد"),
"back_to_login"=>urlencode("العودة إلى تسجيل الدخول"),
"your"=>urlencode("ك"),
"your_clean_items"=>urlencode("العناصر النظيفة الخاصة بك"),
"your_cart_is_empty"=>urlencode("عربة التسوق فارغة"),
"sub_totaltax"=>urlencode("Sub TotalTax"),
"sub_total"=>urlencode("المجموع الفرعي"),
"no_data_available_in_table"=>urlencode("لا توجد بيانات متاحة في الجدول"),
"total"=>urlencode("مجموع"),
"or"=>urlencode("أو"),
"select_addon_image"=>urlencode("حدد صورة إضافية"),
"inside_fridge"=>urlencode("داخل الثلاجة"),
"inside_oven"=>urlencode("داخل الفرن"),
"inside_windows"=>urlencode("داخل ويندوز"),
"carpet_cleaning"=>urlencode("تنظيف السجاد"),
"green_cleaning"=>urlencode("تنظيف أخضر"),
"pets_care"=>urlencode("رعاية الحيوانات الأليفة"),
"tiles_cleaning"=>urlencode("تنظيف البلاط"),
"wall_cleaning"=>urlencode("تنظيف الجدار"),
"laundry"=>urlencode("غسيل ملابس"),
"basement_cleaning"=>urlencode("تنظيف الطابق السفلي"),
"basic_price"=>urlencode("السعر الأساسي"),
"max_qty"=>urlencode("ماكس الكمية"),
"multiple_qty"=>urlencode("الكمية متعددة"),
"base_price"=>urlencode("السعر الأساسي"),
"unit_pricing"=>urlencode("وحدة التسعير"),
"method_is_booked"=>urlencode("يتم حجز الطريقة"),
"service_addons_price_rules"=>urlencode("ققواعد سعر الخدمة Addons"),
"service_unit_front_dropdown_view"=>urlencode("عرض وحدة خدمة DropDown الجبهة"),
"service_unit_front_block_view"=>urlencode("عرض وحدة خدمة كتلة الجبهة"),
"service_unit_front_increase_decrease_view"=>urlencode("زيادة عرض وحدة الخدمة / خفض العرض"),
"are_you_sure"=>urlencode("هل أنت واثق"),
"service_unit_price_rules"=>urlencode("قواعد سعر وحدة الخدمة"),
"close"=>urlencode("قريب"),
"closed"=>urlencode("مغلق"),
"service_addons"=>urlencode("ادونس الخدمة"),
"service_enable"=>urlencode("خدمة تمكين"),
"service_disable"=>urlencode("تعطيل الخدمة"),
"method_enable"=>urlencode("طريقة تمكين"),
"off_time_deleted"=>urlencode("إيقاف الوقت المحذوف"),
"error_in_delete_of_off_time"=>urlencode("خطأ في حذف Off Time"),
"method_disable"=>urlencode("طريقة تعطيل"),
"extra_services"=>urlencode("خدمات إضافية"),
"for_initial_cleaning_only_contact_us_to_apply_to_recurrings"=>urlencode("للتنظيف الأولي فقط. اتصل بنا لتطبيق على التكرار."),
"number_of"=>urlencode("رقم ال"),
"extra_services_not_available"=>urlencode("خدمات إضافية غير متوفرة"),
"available"=>urlencode("متاح"),
"selected"=>urlencode("المحدد"),
"not_available"=>urlencode("غير متاح"),
"none"=>urlencode("لا شيء"),
"none_of_time_slot_available_please_check_another_dates"=>urlencode("لا تتوفر أي فتحة زمنية الرجاء التحقق من تواريخ أخرى"),
"availability_is_not_configured_from_admin_side"=>urlencode("لم يتم تكوين التوافر من جانب المسؤول"),
"how_many_intensive"=>urlencode("كم مكثفة"),
"no_intensive"=>urlencode("لا مكثف"),
"frequently_discount"=>urlencode("الخصم في كثير من الأحيان"),
"coupon_discount"=>urlencode("خصم القسيمة"),
"how_many"=>urlencode("كم العدد"),
"enter_your_other_option"=>urlencode("أدخل خيارك الآخر"),
"log_out"=>urlencode("الخروج"),
"your_added_off_times"=>urlencode("الخاص بك أضيفت خارج الأوقات"),
"log_in"=>urlencode("تسجيل الدخول"),
"custom_css"=>urlencode("لغة تنسيق ويب حسب الطلب"),
"success"=>urlencode("نجاح"),
"failure"=>urlencode("بالفشل"),
"you_can_only_use_valid_zipcode"=>urlencode("يمكنك فقط استخدام الرمز البريدي صالح"),
"minutes"=>urlencode("الدقائق"),
"hours"=>urlencode("ساعات"),
"days"=>urlencode("أيام"),
"months"=>urlencode("الشهور"),
"year"=>urlencode("عام"),
"default_url_is"=>urlencode("عنوان url الافتراضي هو"),
"card_payment"=>urlencode("بطاقه ائتمان"),
"pay_at_venue"=>urlencode("ادفع في المكان"),
"card_details"=>urlencode("معلومات البطاقة"),
"card_number"=>urlencode("رقم البطاقة"),
"invalid_card_number"=>urlencode("رقم البطاقة غير صالحة"),
"expiry"=>urlencode("انقضاء"),
"button_preview"=>urlencode("زر معاينة"),
"thankyou"=>urlencode("شكرا"),
"thankyou_for_booking_appointment"=>urlencode("شكرا! لحجز موعد"),
"you_will_be_notified_by_email_with_details_of_appointment"=>urlencode("سيتم إعلامك عبر البريد الإلكتروني بتفاصيل الموعد"),
"please_enter_firstname"=>urlencode("يرجى إدخال الاسم"),
"please_enter_lastname"=>urlencode("يرجى إدخال اسم العائلة"),
"remove_applied_coupon"=>urlencode("قم بإزالة القسيمة المطبقة"),
"eg_799_e_dragram_suite_5a"=>urlencode("على سبيل المثال. 799 E DRAGRAM SUITE 5A"),
"eg_14114"=>urlencode("على سبيل المثال. 14114"),
"eg_tucson"=>urlencode("على سبيل المثال. TUCSON"),
"eg_az"=>urlencode("على سبيل المثال. من الألف إلى الياء"),
"warning"=>urlencode("تحذير"),
"try_later"=>urlencode("حاول لاحقا"),
"choose_your"=>urlencode("اختر خاصتك"),
"configure_now_new"=>urlencode("تكوين الآن"),
"january"=>urlencode("كانون الثاني"),
"february"=>urlencode("شهر فبراير"),
"march"=>urlencode("مارس"),
"april"=>urlencode("أبريل"),
"may"=>urlencode("قد"),
"june"=>urlencode("يونيو"),
"july"=>urlencode("يوليو"),
"august"=>urlencode("أغسطس"),
"september"=>urlencode("سبتمبر"),
"october"=>urlencode("شهر اكتوبر"),
"november"=>urlencode("شهر نوفمبر"),
"december"=>urlencode("ديسمبر"),
"jan"=>urlencode("JAN"),
"feb"=>urlencode("فبراير"),
"mar"=>urlencode("MAR"),
"apr"=>urlencode("أبريل"),
"jun"=>urlencode("يونيو"),
"jul"=>urlencode("يوليو"),
"aug"=>urlencode("أغسطس"),
"sep"=>urlencode("سبتمبر"),
"oct"=>urlencode("أكتوبر"),
"nov"=>urlencode("نوفمبر"),
"dec"=>urlencode("ديسمبر"),
"pay_locally"=>urlencode("دفع محليا"),
"please_select_provider"=>urlencode("يرجى اختيار المزود"),
);

$admin_labels_ar = array (
"payment_status"=>urlencode("حالة السداد"),
"staff_booking_status"=>urlencode("حالة حجز الموظفين"),
"accept"=>urlencode("قبول"),
"accepted"=>urlencode("قبلت"),
"decline"=>urlencode("انخفاض"),
"paid"=>urlencode("دفع"),
"eway"=>urlencode("Eway"),
"half_section"=>urlencode("نصف القسم"),
"option_title"=>urlencode("عنوان الخيار"),
"merchant_ID"=>urlencode("معرف التاجر"),
"How_it_works"=>urlencode("كيف تعمل؟"),
"Your_currency_should_be_AUD_to_enable_payway_payment_gateway"=>urlencode("يجب أن تكون عملتك هي الدولار الأسترالي لتمكين بوابة الدفع"),
"secure_key"=>urlencode("مفتاح الأمان"),
"payway"=>urlencode("Payway"),
"Your_Google_calendar_id_where_you_need_to_get_alerts_its_normaly_your_Gmail_ID"=>urlencode("معرف تقويم Google الخاص بك ، حيث تحتاج إلى الحصول على تنبيهات ، هو معرف Gmail الخاص بك. مثلا johndoe@example.com"),
"You_can_get_your_client_ID_from_your_Google_Calendar_Console"=>urlencode("يمكنك الحصول على معرف العميل الخاص بك من Google Calendar Console"),
"You_can_get_your_client_secret_from_your_Google_Calendar_Console"=>urlencode("يمكنك الحصول على سر عميلك من Google Calendar Console"),
"its_your_Cleanto_booking_form_page_url"=>urlencode("به صفحة نموذج حجز Cleanto الحجز"),
"Its_your_Cleanto_Google_Settings_page_url"=>urlencode("في الصفحة الخاصة بك URL Cleanto جوجل إعدادات"),
"Add_Manual_booking"=>urlencode("إضافة دليل الحجز"),
"Google_Calender_Settings"=>urlencode("إعدادات تقويم Google"),
"Add_Appointments_To_Google_Calender"=>urlencode("أضف المواعيد إلى تقويم Google"),
"Google_Calender_Id"=>urlencode("معرف تقويم Google"),
"Google_Calender_Client_Id"=>urlencode("معرف عميل Google Calendar"),
"Google_Calender_Client_Secret"=>urlencode("برنامج Google Calender Client Secret"),
"Google_Calender_Frontend_URL"=>urlencode("جوجل تقويم الواجهة الأمامية"),
"Google_Calender_Admin_URL"=>urlencode("جوجل مدير الموقع التقويم"),
"Google_Calender_Configuration"=>urlencode("جوجل تقويم التكوين"),
"Two_Way_Sync"=>urlencode("طريقين المزامنة"),
"Verify_Account"=>urlencode("التحقق من الحساب"),
"Select_Calendar"=>urlencode("حدد التقويم"),
"Disconnect"=>urlencode("قطع الاتصال"),
"Calendar_Fisrt_Day"=>urlencode("التقويم اليوم الأول"),
"Calendar_Default_View"=>urlencode("عرض التقويم الافتراضي"),
"Show_company_title"=>urlencode("إظهار عنوان الشركة"),
"front_language_flags_list"=>urlencode("قائمة علم اللغات الأمامية"),
"Google_Analytics_Code"=>urlencode("مدونة Google Analytics"),
"Page_Meta_Tag"=>urlencode("صفحة / العلامة الوصفية"),
"SEO_Settings"=>urlencode("إعدادات SEO"),
"Meta_Description"=>urlencode("ميتا الوصف"),
"SEO"=>urlencode("SEO"),
"og_tag_image"=>urlencode("صورة العلامة"),
"og_tag_url"=>urlencode("عنوان URL للعلامة"),
"og_tag_type"=>urlencode("نوع العلامة"),
"og_tag_title"=>urlencode("عنوان العلامة"),
"Quantity"=>urlencode("كمية"),
"Send_Invoice"=>urlencode("إرسال الفاتورة"),
"Recurrence"=>urlencode("تكرار"),
"Recurrence_booking"=>urlencode("حجز تكرار"),
"Reset_Color"=>urlencode("إعادة ضبط اللون"),
"Loader"=>urlencode("محمل"),
"CSS_Loader"=>urlencode("تحميل CSS"),
"GIF_Loader"=>urlencode("GIF لودر"),
"Default_Loader"=>urlencode("التحميل الافتراضي"),
"for_a"=>urlencode("ل"),
"show_company_logo"=>urlencode("عرض شعار الشركة"),
"on"=>urlencode("على"),
"user_zip_code"=>urlencode("الرمز البريدي"),
"delete_this_method"=>urlencode("حذف هذه الطريقة؟"),
"authorize_net"=>urlencode("Authorize.Net"),
"staff_details"=>urlencode("بيانات الموظفين"),
"client_payments"=>urlencode("مدفوعات العميل"),
"staff_payments"=>urlencode("مدفوعات الموظفين"),
"staff_payments_details"=>urlencode("تفاصيل مدفوعات الموظفين"),
"advance_paid"=>urlencode("دفع مسبقا"),
"change_calculation_policyy"=>urlencode("تغيير سياسة الحساب"),
"frontend_fonts"=>urlencode("خطوط الواجهة"),
"favicon_image"=>urlencode("صورة Favicon"),
"staff_email_template"=>urlencode("قالب البريد الإلكتروني للموظفين"),
"staff_details_add_new_and_manage_staff_payments"=>urlencode("تفاصيل الموظفين ، إضافة جديدة وإدارة مدفوعات الموظفين"),
"add_staff"=>urlencode("إضافة الموظفين"),
"staff_bookings_and_payments"=>urlencode("حجوزات الموظفين والمدفوعات"),
"staff_booking_details_and_payment"=>urlencode("تفاصيل حجز الموظفين والدفع"),
"select_option_to_show_bookings"=>urlencode("حدد الخيار لعرض الحجوزات"),
"select_service"=>urlencode("اختر الخدمة"),
"staff_name"=>urlencode("اسم الموظفين"),
"staff_payment"=>urlencode("دفع الموظفين"),
"add_payment_to_staff_account"=>urlencode("إضافة الدفع إلى حساب الموظفين"),
"amount_payable"=>urlencode("المبلغ المستحق"),
"save_changes"=>urlencode("حفظ التغييرات"),
"front_error_labels"=>urlencode("تسميات الخطأ الأمامية"),
"stripe"=>urlencode("شريط"),
"checkout_title"=>urlencode("2Checkout"),
"nexmo_sms_gateway"=>urlencode("Nexmo SMS Gateway"),
"nexmo_sms_setting"=>urlencode("Nexmo SMS Setting"),
"nexmo_api_key"=>urlencode("Nexmo API الرئيسية"),
"nexmo_api_secret"=>urlencode("Nexmo API Secret"),
"nexmo_from"=>urlencode("Nexmo From"),
"nexmo_status"=>urlencode("حالة Nexmo"),
"nexmo_send_sms_to_client_status"=>urlencode("Nexmo إرسال الرسائل القصيرة إلى حالة العميل"),
"nexmo_send_sms_to_admin_status"=>urlencode("Nexmo إرسال الرسائل القصيرة إلى المشرف الوضع"),
"nexmo_admin_phone_number"=>urlencode("رقم هاتف المسؤول Nexmo"),
"save_12_5"=>urlencode("وفر 12.5٪"),
"front_tool_tips"=>urlencode("نصائح أداة الجبهة"),
"front_tool_tips_lower"=>urlencode("نصائح أداة الجبهة"),
"tool_tip_my_bookings"=>urlencode("حجوزاتي"),
"tool_tip_postal_code"=>urlencode("الرمز البريدي"),
"tool_tip_services"=>urlencode("خدمات"),
"tool_tip_extra_service"=>urlencode("خدمة إضافية"),
"tool_tip_frequently_discount"=>urlencode("الخصم في كثير من الأحيان"),
"tool_tip_when_would_you_like_us_to_come"=>urlencode("متى تريد منا أن نأتي؟"),
"tool_tip_your_personal_details"=>urlencode("تفاصيلك الشخصية"),
"tool_tip_have_a_promocode"=>urlencode("هل يمتلك الرمز الترويجي"),
"tool_tip_preferred_payment_method"=>urlencode("يفضل طريقة الدفع"),
"login_page"=>urlencode("صفحة تسجيل الدخول"),
"front_page"=>urlencode("الصفحة الأمامية"),
"before_e_g_100"=>urlencode("قبل (على سبيل المثال 100 $)"),
"after_e_g_100"=>urlencode("بعد (e.g.100 $)"),
"tax_vat"=>urlencode("ضريبة القيمة المضافة"),
"wrong_url"=>urlencode("رابط خطأ"),
"choose_file"=>urlencode("اختر ملف"),
"frontend_labels"=>urlencode("تسميات الواجهة الأمامية"),
"admin_labels"=>urlencode("تسميات المسؤول"),
"dropdown_design"=>urlencode("تصميم DropDown"),
"blocks_as_button_design"=>urlencode("كتل وتصميم زر"),
"qty_control_design"=>urlencode("الكمية مراقبة التصميم"),
"dropdowns"=>urlencode("هبوط قطرة"),
"big_images_radio"=>urlencode("راديو صور كبيرة"),
"errors"=>urlencode("أخطاء"),
"extra_labels"=>urlencode("تسميات اضافية"),
"api_password"=>urlencode("كلمة مرور API"),
"api_username"=>urlencode("اسم مستخدم واجهة برمجة التطبيقات"),
"appearance"=>urlencode("مظهر خارجي"),
"action"=>urlencode("عمل"),
"actions"=>urlencode("أفعال"),
"add_break"=>urlencode("أضف فاصل"),
"add_breaks"=>urlencode("أضف فواصل"),
"add_cleaning_service"=>urlencode("إضافة خدمة التنظيف"),
"add_method"=>urlencode("اضافة الطريقة"),
"add_new"=>urlencode("اضف جديد"),
"add_sample_data"=>urlencode("إضافة نموذج البيانات"),
"add_unit"=>urlencode("إضافة وحدة"),
"add_your_off_times"=>urlencode("أضف الأوقات الخاصة بك"),
"add_new_off_time"=>urlencode("أضف وقتًا جديدًا"),
"add_ons"=>urlencode("إضافات"),
"addons_bookings"=>urlencode("حجوزات AddOns"),
"addon_service_front_view"=>urlencode("Addon-Service Front View"),
"addons"=>urlencode("إضافات"),
"service_commission"=>urlencode("لجنة الخدمة"),
"commission_total"=>urlencode("المجموع الكلي"),
"address"=>urlencode("عنوان"),
"new_appointment_assigned"=>urlencode("تعيين جديد معين"),
"admin_email_notifications"=>urlencode("إشعارات البريد الإلكتروني للمشرف"),
"all_payment_gateways"=>urlencode("جميع بوابات الدفع"),
"all_services"=>urlencode("جميع الخدمات"),
"allow_multiple_booking_for_same_timeslot"=>urlencode("السماح للحجز المتعدد لنفس Timeslot"),
"amount"=>urlencode("كمية"),
"app_date"=>urlencode("التطبيق. تاريخ"),
"appearance_settings"=>urlencode("إعدادات المظهر"),
"appointment_completed"=>urlencode("الموعد اكتمل"),
"appointment_details"=>urlencode("تفاصيل المواعيد"),
"appointment_marked_as_no_show"=>urlencode("تعيين موعد كما لا تظهر"),
"mark_as_no_show"=>urlencode("علامة لا تظهر"),
"appointment_reminder_buffer"=>urlencode("تذكير موعد المؤقت"),
"appointment_auto_confirm"=>urlencode("تأكيد موعد السيارات"),
"appointments"=>urlencode("تعيينات"),
"admin_area_color_scheme"=>urlencode("نظام ألوان منطقة المسؤول"),
"thankyou_page_url"=>urlencode("الشكر صفحة العنوان"),
"addon_title"=>urlencode("عنوان الملحق"),
"availabilty"=>urlencode("توفر"),
"background_color"=>urlencode("لون الخلفية"),
"behaviour_on_click_of_button"=>urlencode("السلوك على نقرة زر"),
"book_now"=>urlencode("احجز الآن"),
"booking_date_and_time"=>urlencode("تاريخ الحجز والوقت"),
"booking_details"=>urlencode("تفاصيل الحجز"),
"booking_information"=>urlencode("معلومات الحجز"),
"booking_serve_date"=>urlencode("الحجز تاريخ الخدمة"),
"booking_status"=>urlencode("وضع الحجز"),
"booking_notifications"=>urlencode("إخطارات الحجز"),
"bookings"=>urlencode("حجوزات"),
"button_position"=>urlencode("موقف زر"),
"button_text"=>urlencode("زر كتابة"),
"company"=>urlencode("شركة"),
"cannot_cancel_now"=>urlencode("لا يمكن إلغاء الآن"),
"cannot_reschedule_now"=>urlencode("لا يمكن إعادة جدولة الآن"),
"cancel"=>urlencode("إلغاء"),
"cancellation_buffer_time"=>urlencode("إلغاء الوقت المخزن المؤقت"),
"cancelled_by_client"=>urlencode("ملغى من قبل العميل"),
"cancelled_by_service_provider"=>urlencode("ملغى من قبل مزود الخدمة"),
"change_password"=>urlencode("تغيير كلمة السر"),
"cleaning_service"=>urlencode("خدمة التنظيف"),
"client"=>urlencode("زبون"),
"client_email_notifications"=>urlencode("إعلامات البريد الإلكتروني للعميل"),
"client_name"=>urlencode("اسم العميل"),
"color_scheme"=>urlencode("نظام الألوان"),
"color_tag"=>urlencode("علامة اللون"),
"company_address"=>urlencode("عنوان"),
"company_email"=>urlencode("البريد الإلكتروني"),
"company_logo"=>urlencode("شعار الشركة"),
"company_name"=>urlencode("الاسم التجاري"),
"company_settings"=>urlencode("إعدادات معلومات الأعمال"),
"companyname"=>urlencode("اسم الشركة"),
"company_info_settings"=>urlencode("معلومات الشركة إعدادات"),
"completed"=>urlencode("منجز"),
"confirm"=>urlencode("تؤكد"),
"confirmed"=>urlencode("تم تأكيد"),
"contact_status"=>urlencode("حالة الاتصال"),
"country"=>urlencode("بلد"),
"country_code_phone"=>urlencode("كود البلد (الهاتف)"),
"coupon"=>urlencode("كوبون"),
"coupon_code"=>urlencode("كود القسيمة"),
"coupon_limit"=>urlencode("حد القسيمة"),
"coupon_type"=>urlencode("نوع القسيمة"),
"coupon_used"=>urlencode("الكوبون مستعملة"),
"coupon_value"=>urlencode("قيمة القسيمة"),
"create_addon_service"=>urlencode("إنشاء خدمة Addon"),
"crop_and_save"=>urlencode("المحاصيل والحفظ"),
"currency"=>urlencode("دقة"),
"currency_symbol_position"=>urlencode("موقف رمز العملة"),
"customer"=>urlencode("زبون"),
"customer_information"=>urlencode("معلومات العميل"),
"customers"=>urlencode("الزبائن"),
"date_and_time"=>urlencode("التاريخ والوقت"),
"date_picker_date_format"=>urlencode("تنسيق تاريخ منتقي التاريخ"),
"default_design_for_addons"=>urlencode("التصميم الافتراضي للحصول على أدونس"),
"default_design_for_methods_with_multiple_units"=>urlencode("التصميم الافتراضي للطرق مع وحدات متعددة"),
"default_design_for_services"=>urlencode("التصميم الافتراضي للخدمات"),
"default_setting"=>urlencode("الإعدادات الإفتراضية"),
"delete"=>urlencode("حذف"),
"description"=>urlencode("وصف"),
"discount"=>urlencode("خصم"),
"download_invoice"=>urlencode("تحميل فاتورة"),
"email_notification"=>urlencode("إشعار البريد الإلكتروني"),
"email"=>urlencode("البريد الإلكتروني"),
"email_settings"=>urlencode("إعدادات البريد الإلكتروني"),
"embed_code"=>urlencode("تضمين كود"),
"enter_your_email_and_we_will_send_you_instructions_on_resetting_your_password"=>urlencode("أدخل بريدك الإلكتروني وسنرسل لك تعليمات حول إعادة تعيين كلمة المرور الخاصة بك."),
"expiry_date"=>urlencode("تاريخ الانتهاء"),
"export"=>urlencode("تصدير"),
"export_your_details"=>urlencode("تصدير التفاصيل الخاصة بك"),
"frequently_discount_setting_tabs"=>urlencode("الخصم بشكل كبير"),
"frequently_discount_header"=>urlencode("الخصم في كثير من الأحيان"),
"field_is_required"=>urlencode("الحقل مطلوب"),
"file_size"=>urlencode("حجم الملف"),
"flat_fee"=>urlencode("رسم موحد"),
"flat"=>urlencode("مسطحة"),
"freq_discount"=>urlencode("التكرار الخصم"),
"frequently_discount_label"=>urlencode("تسمية الخصم في كثير من الأحيان"),
"frequently_discount_type"=>urlencode("كثيرا ما نوع الخصم"),
"frequently_discount_value"=>urlencode("قيمة الخصم في كثير من الأحيان"),
"front_service_box_view"=>urlencode("عرض صندوق الخدمة الأمامية"),
"front_service_dropdown_view"=>urlencode("عرض الخدمة الأمامية المنسدلة"),
"front_view_options"=>urlencode("خيارات المشاهدة الأمامية"),
"full_name"=>urlencode("الاسم الكامل"),
"general"=>urlencode("جنرال لواء"),
"general_settings"=>urlencode("الاعدادات العامة"),
"get_embed_code_to_show_booking_widget_on_your_website"=>urlencode("الحصول على رمز تضمين لإظهار القطعة الحجز على موقع الويب الخاص بك"),
"get_the_embeded_code"=>urlencode("الحصول على رمز Embeded"),
"guest_customers"=>urlencode("زبائن الضيوف"),
"guest_user_checkout"=>urlencode("تسجيل مستخدم ضيف"),
"hide_faded_already_booked_time_slots"=>urlencode("إخفاء تلاشى الفواصل الزمنية المحجوزة بالفعل"),
"hostname"=>urlencode("اسم المضيف"),
"labels"=>urlencode("تسميات"),
"legends"=>urlencode("أساطير"),
"login"=>urlencode("تسجيل الدخول"),
"maximum_advance_booking_time"=>urlencode("أقصى وقت الحجز مقدما"),
"method"=>urlencode("طريقة"),
"method_name"=>urlencode("اسم الطريقة"),
"method_title"=>urlencode("عنوان الطريقة"),
"method_unit_quantity"=>urlencode("كمية وحدة الطريقة"),
"method_unit_quantity_rate"=>urlencode("طريقة وحدة كمية معدل"),
"method_unit_title"=>urlencode("عنوان وحدة الطريقة"),
"method_units_front_view"=>urlencode("وحدات طريقة عرض الجبهة"),
"methods"=>urlencode("أساليب"),
"methods_booking"=>urlencode("طرق الحجز"),
"methods_bookings"=>urlencode("حجوزات الطرق"),
"minimum_advance_booking_time"=>urlencode("الحد الأدنى وقت الحجز مسبقا"),
"more"=>urlencode("أكثر من"),
"more_details"=>urlencode("المزيد من التفاصيل"),
"my_appointments"=>urlencode("بلدي المواعيد"),
"name"=>urlencode("اسم"),
"net_total"=>urlencode("صافي المجموع"),
"new_password"=>urlencode("كلمة السر الجديدة"),
"notes"=>urlencode("ملاحظات"),
"off_days"=>urlencode("خارج يوم"),
"off_time"=>urlencode("إيقاف الوقت"),
"old_password"=>urlencode("كلمة المرور القديمة"),
"online_booking_button_style"=>urlencode("الحجز عبر الإنترنت Button Button"),
"open_widget_in_a_new_page"=>urlencode("افتح الأداة في صفحة جديدة"),
"order"=>urlencode("طلب"),
"order_date"=>urlencode("تاريخ الطلب"),
"order_time"=>urlencode("وقت الطلب"),
"payments_setting"=>urlencode("دفع"),
"promocode"=>urlencode("رمز ترويجي"),
"promocode_header"=>urlencode("رمز ترويجي"),
"padding_time_before"=>urlencode("وقت الحشو من قبل"),
"parking"=>urlencode("موقف سيارات"),
"partial_amount"=>urlencode("مبلغ جزئي"),
"partial_deposit"=>urlencode("الوديعة الجزئية"),
"partial_deposit_amount"=>urlencode("مبلغ الوديعة الجزئي"),
"partial_deposit_message"=>urlencode("رسالة الإيداع الجزئي"),
"password"=>urlencode("كلمه السر"),
"payment"=>urlencode("دفع"),
"payment_date"=>urlencode("تاريخ الدفع"),
"payment_gateways"=>urlencode("بوابات الدفع"),
"payment_method"=>urlencode("طريقة الدفع او السداد"),
"payments"=>urlencode("المدفوعات"),
"payments_history_details"=>urlencode("المدفوعات التاريخ التفاصيل"),
"paypal_express_checkout"=>urlencode("اكسبرس باي بال الدفع"),
"paypal_guest_payment"=>urlencode("دفع ضيف باي بال"),
"pending"=>urlencode("قيد الانتظار"),
"percentage"=>urlencode("النسبة المئوية"),
"personal_information"=>urlencode("معلومات شخصية"),
"phone"=>urlencode("هاتف"),
"please_copy_above_code_and_paste_in_your_website"=>urlencode("يرجى نسخ أعلاه رمز ولصق في موقع الويب الخاص بك."),
"please_enable_payment_gateway"=>urlencode("يرجى تمكين بوابة الدفع"),
"please_set_below_values"=>urlencode("يرجى تحديد القيم أدناه"),
"port"=>urlencode("ميناء"),
"postal_codes"=>urlencode("الرموز البريدية"),
"price"=>urlencode("السعر"),
"price_calculation_method"=>urlencode("طريقة حساب السعر"),
"price_format_decimal_places"=>urlencode("تنسيق السعر"),
"pricing"=>urlencode("التسعير"),
"primary_color"=>urlencode("لون أصلي"),
"privacy_policy_link"=>urlencode("سياسة الخصوصية وصلة"),
"profile"=>urlencode("الملف الشخصي"),
"promocodes"=>urlencode("Promocodes"),
"promocodes_list"=>urlencode("قائمة Promocodes"),
"registered_customers"=>urlencode("العملاء المسجلين"),
"registered_customers_bookings"=>urlencode("حجوزات العملاء المسجلين"),
"reject"=>urlencode("رفض"),
"rejected"=>urlencode("مرفوض"),
"remember_me"=>urlencode("تذكرنى"),
"remove_sample_data"=>urlencode("إزالة نموذج البيانات"),
"reschedule"=>urlencode("إعادة جدولة"),
"reset"=>urlencode("إعادة تعيين"),
"reset_password"=>urlencode("إعادة ضبط كلمة المرور"),
"reshedule_buffer_time"=>urlencode("Reshedule العازلة الوقت"),
"retype_new_password"=>urlencode("أعد كتابة كلمة السر الجديدة"),
"right_side_description"=>urlencode("وصف حجز الحجز على الصفحة"),
"save"=>urlencode("حفظ"),
"save_availability"=>urlencode("حفظ التوافر"),
"save_setting"=>urlencode("حفظ الإعداد"),
"save_labels_setting"=>urlencode("حفظ تسميات التسميات"),
"schedule"=>urlencode("جدول"),
"schedule_type"=>urlencode("نوع الجدول"),
"secondary_color"=>urlencode("اللون الثانوي"),
"select_language_for_update"=>urlencode("اختر لغة للتحديث"),
"select_language_to_change_label"=>urlencode("اختر لغة لتغيير التسمية"),
"select_language_to_display"=>urlencode("لغة"),
"display_sub_headers_below_headers"=>urlencode("العناوين الفرعية في صفحة الحجز"),
"select_payment_option_export_details"=>urlencode("حدد تفاصيل تصدير خيار الدفع"),
"send_mail"=>urlencode("ارسل بريد"),
"sender_email_address_cleanto_admin_email"=>urlencode("البريد الإلكتروني المرسل"),
"sender_name"=>urlencode("اسم المرسل"),
"service"=>urlencode("الخدمات"),
"service_add_ons_front_block_view"=>urlencode("إضافات الخدمة عرض بلوك أمامي"),
"service_add_ons_front_increase_decrease_view"=>urlencode("إضافات الخدمة الأمامية زيادة / تقليل العرض"),
"service_description"=>urlencode("وصف الخدمة"),
"service_front_view"=>urlencode("خدمة الجبهة الرأي"),
"service_image"=>urlencode("صورة الخدمة"),
"service_methods"=>urlencode("طرق الخدمة"),
"service_padding_time_after"=>urlencode("خدمة الحشو بعد الوقت"),
"padding_time_after"=>urlencode("وقت الحشو بعد"),
"service_padding_time_before"=>urlencode("خدمة الحشو الوقت قبل"),
"service_quantity"=>urlencode("كمية الخدمة"),
"service_rate"=>urlencode("معدل خدمة"),
"service_title"=>urlencode("عنوان الخدمة"),
"serviceaddons_name"=>urlencode("ServiceAddOns Name"),
"services"=>urlencode("خدمات"),
"services_information"=>urlencode("معلومات الخدمات"),
"set_email_reminder_buffer"=>urlencode("تعيين البريد الإلكتروني تذكير العازلة"),
"set_language"=>urlencode("لغة مجموعة"),
"settings"=>urlencode("إعدادات"),
"show_all_bookings"=>urlencode("عرض جميع الحجوزات"),
"show_button_on_given_embeded_position"=>urlencode("عرض زر على موقف مدمج معين"),
"show_coupons_input_on_checkout"=>urlencode("عرض كوبونات المدخلات على الخروج"),
"show_on_a_button_click"=>urlencode("تظهر على زر فوق"),
"show_on_page_load"=>urlencode("عرض على تحميل الصفحة"),
"signature"=>urlencode("التوقيع"),
"sorry_wrong_email_or_password"=>urlencode("عذرا خاطئ البريد الإلكتروني أو كلمة المرور"),
"start_date"=>urlencode("تاريخ البدء"),
"status"=>urlencode("الحالة"),
"submit"=>urlencode("خضع"),
"staff_email_notification"=>urlencode("إشعار البريد الإلكتروني للموظفين"),
"tax"=>urlencode("ضريبة"),
"test_mode"=>urlencode("وضع الاختبار"),
"text_color"=>urlencode("لون الخط"),
"text_color_on_bg"=>urlencode("لون النص على bg"),
"terms_and_condition_link"=>urlencode("الشروط والأحكام وصلة"),
"this_week_breaks"=>urlencode("هذا الاسبوع فواصل"),
"this_week_time_scheduling"=>urlencode("هذا الجدول الزمني للوقت الأسبوعي"),
"time_format"=>urlencode("تنسيق الوقت"),
"time_interval"=>urlencode("الفاصل الزمني"),
"timezone"=>urlencode("وحدة زمنية"),
"units"=>urlencode("وحدات"),
"unit_name"=>urlencode("إسم الوحدة"),
"units_of_methods"=>urlencode("وحدات طرق"),
"update"=>urlencode("تحديث"),
"update_appointment"=>urlencode("تحديث موعد"),
"update_promocode"=>urlencode("تحديث Promocode"),
"username"=>urlencode("اسم المستخدم"),
"vaccum_cleaner"=>urlencode("فراغ منظف"),
"view_slots_by"=>urlencode("عرض الشرائح بواسطة؟"),
"week"=>urlencode("أسبوع"),
"week_breaks"=>urlencode("فواصل الأسبوع"),
"week_time_scheduling"=>urlencode("الجدول الزمني للوقت الأسبوعي"),
"widget_loading_style"=>urlencode("نمط تحميل القطعة"),
"zip"=>urlencode("الرمز البريدي"),
"logout"=>urlencode("الخروج"),
"to"=>urlencode("إلى"),
"add_new_promocode"=>urlencode("إضافة جديد Promocode"),
"create"=>urlencode("خلق"),
"end_date"=>urlencode("تاريخ الانتهاء"),
"end_time"=>urlencode("وقت النهاية"),
"labels_settings"=>urlencode("إعدادات التسميات"),
"limit"=>urlencode("حد"),
"max_limit"=>urlencode("الحد الأقصى"),
"start_time"=>urlencode("وقت البدء"),
"value"=>urlencode("القيمة"),
"active"=>urlencode("نشيط"),
"appointment_reject_reason"=>urlencode("موعد رفض سبب"),
"search"=>urlencode("بحث"),
"custom_thankyou_page_url"=>urlencode("عرف Thankyou الصفحة رابط"),
"price_per_unit"=>urlencode("السعر لكل وحدة"),
"confirm_appointment"=>urlencode("تأكيد موعد"),
"reject_reason"=>urlencode("رفض السبب"),
"delete_this_appointment"=>urlencode("حذف هذا الموعد"),
"close_notifications"=>urlencode("إغلاق الإخطارات"),
"booking_cancel_reason"=>urlencode("حجز إلغاء السبب"),
"service_color_badge"=>urlencode("شارة لون الخدمة"),
"manage_price_calculation_methods"=>urlencode("إدارة طرق حساب السعر"),
"manage_addons_of_this_service"=>urlencode("إدارة الأدوار من هذه الخدمة"),
"service_is_booked"=>urlencode("الخدمة محجوزة"),
"delete_this_service"=>urlencode("حذف هذه الخدمة"),
"delete_service"=>urlencode("حذف الخدمة"),
"remove_image"=>urlencode("إزالة الصورة"),
"remove_service_image"=>urlencode("قم بإزالة صورة الخدمة"),
"company_name_is_used_for_invoice_purpose"=>urlencode("اسم الشركة يستخدم لغرض الفاتورة"),
"remove_company_logo"=>urlencode("قم بإزالة شعار الشركة"),
"time_interval_is_helpful_to_show_time_difference_between_availability_time_slots"=>urlencode("الفاصل الزمني مفيد لإظهار فرق التوقيت بين فتحات وقت التوفر"),
"minimum_advance_booking_time_restrict_client_to_book_last_minute_booking_so_that_you_should_have_sufficient_time_before_appointment"=>urlencode("الحد الأدنى من وقت الحجز المسبق يقيد العميل لحجز حجز في اللحظة الأخيرة ، بحيث يكون لديك الوقت الكافي قبل الموعد"),
"cancellation_buffer_helps_service_providers_to_avoid_last_minute_cancellation_by_their_clients"=>urlencode("يساعد المخزن المؤقت للإلغاء مقدمي الخدمة على تجنب الإلغاء في اللحظة الأخيرة من قبل عملائهم"),
"partial_payment_option_will_help_you_to_charge_partial_payment_of_total_amount_from_client_and_remaining_you_can_collect_locally"=>urlencode("سيساعدك خيار الدفع الجزئي على تحصيل دفعة جزئية من المبلغ الإجمالي من العميل والبقاء يمكنك تحصيلها محليًا"),
"allow_multiple_appointment_booking_at_same_time_slot_will_allow_you_to_show_availability_time_slot_even_you_have_booking_already_for_that_time"=>urlencode("السماح لحجز موعد متعدد في نفس الوقت ، سيسمح لك بإظهار وقت التوافر حتى لو كان لديك حجز بالفعل في ذلك الوقت"),
"with_Enable_of_this_feature_Appointment_request_from_clients_will_be_auto_confirmed"=>urlencode("مع تمكين هذه الميزة ، سيتم تأكيد طلب موعد من العملاء تلقائيًا"),
"write_html_code_for_the_right_side_panel"=>urlencode("اكتب كود HTML للوحة اليمنى"),
"do_you_want_to_show_subheaders_below_the_headers"=>urlencode("هل تريد إظهار العناوين الفرعية أسفل الرؤوس"),
"you_can_show_hide_coupon_input_on_checkout_form"=>urlencode("يمكنك إظهار / إخفاء إدخال القسيمة في نموذج الدفع"),
"with_this_feature_you_can_allow_a_visitor_to_book_appointment_without_registration"=>urlencode("مع هذه الميزة يمكنك السماح للزائر لحجز موعد دون تسجيل"),
"paypal_api_username_can_get_easily_from_developer_paypal_com_account"=>urlencode("يمكن الحصول على اسم مستخدم API PayPal بسهولة من حساب developer.paypal.com"),
"paypal_api_password_can_get_easily_from_developer_paypal_com_account"=>urlencode("كلمة مرور بايبال API يمكن الحصول عليها بسهولة من حساب developer.paypal.com"),
"paypal_api_signature_can_get_easily_from_developer_paypal_com_account"=>urlencode("يمكن الحصول على توقيع Paypal API بسهولة من حساب developer.paypal.com"),
"let_user_pay_through_credit_card_without_having_paypal_account"=>urlencode("دع المستخدم يدفع من خلال بطاقة الائتمان دون أن يكون لديه حساب Paypal"),
"you_can_enable_paypal_test_mode_for_sandbox_account_testing"=>urlencode("يمكنك تمكين وضع اختبار Paypal لاختبار حساب Sandbox"),
"you_can_enable_authorize_net_test_mode_for_sandbox_account_testing"=>urlencode("يمكنك تمكين وضع اختبار Authorize.Net لاختبار حساب وضع الحماية"),
"edit_coupon_code"=>urlencode("تعديل كود القسيمة"),
"delete_promocode"=>urlencode("حذف Promocode؟"),
"coupon_code_will_work_for_such_limit"=>urlencode("سيعمل رمز القسيمة لمثل هذا الحد"),
"coupon_code_will_work_for_such_date"=>urlencode("سيعمل رمز القسيمة لهذا التاريخ"),
"coupon_value_would_be_consider_as_percentage_in_percentage_mode_and_in_flat_mode_it_will_be_consider_as_amount_no_need_to_add_percentage_sign_it_will_auto_added"=>urlencode("سيتم اعتبار قيمة الكوبون كنسبة مئوية في وضع النسبة المئوية وفي الوضع المسطح سيتم اعتبارها كمبلغ. لا حاجة لإضافة علامة النسبة المئوية التي سيتم إضافتها تلقائيًا."),
"unit_is_booked"=>urlencode("الوحدة محجوزة"),
"delete_this_service_unit"=>urlencode("حذف وحدة الخدمة هذه؟"),
"delete_service_unit"=>urlencode("حذف وحدة الخدمة"),
"manage_unit_price"=>urlencode("إدارة سعر الوحدة"),
"extra_service_title"=>urlencode("عنوان الخدمة الإضافية"),
"addon_is_booked"=>urlencode("يتم حجز الملحق"),
"delete_this_addon_service"=>urlencode("حذف هذه الخدمة الإضافية؟"),
"choose_your_addon_image"=>urlencode("اختر صورتك الإضافية"),
"addon_image"=>urlencode("صورة الملحق"),
"administrator_email"=>urlencode("البريد الإلكتروني المسؤول"),
"admin_profile_address"=>urlencode("عنوان"),
"default_country_code"=>urlencode("الرقم الدولي"),
"cancellation_policy"=>urlencode("سياسة الإلغاء"),
"transaction_id"=>urlencode("معرف المعاملة"),
"sms_reminder"=>urlencode("تذكير الرسائل القصيرة"),
"save_sms_settings"=>urlencode("حفظ إعدادات الرسائل القصيرة"),
"sms_service"=>urlencode("خدمة الرسائل القصيرة"),
"it_will_send_sms_to_service_provider_and_client_for_appointment_booking"=>urlencode("وسوف ترسل الرسائل القصيرة إلى مزود الخدمة والعميل لحجز موعد"),
"twilio_account_settings"=>urlencode("Twilio إعدادات الحساب"),
"plivo_account_settings"=>urlencode("إعدادات حساب بليفو"),
"account_sid"=>urlencode("حساب SID"),
"auth_token"=>urlencode("رقم المصادقة"),
"twilio_sender_number"=>urlencode("Twilio Sender Number"),
"plivo_sender_number"=>urlencode("رقم بليفو المرسل"),
"twilio_sms_settings"=>urlencode("Twilio إعدادات SMS"),
"plivo_sms_settings"=>urlencode("إعدادات Plivo SMS"),
"twilio_sms_gateway"=>urlencode("Twilio SMS بوابة"),
"plivo_sms_gateway"=>urlencode("بوابة Plivo SMS"),
"send_sms_to_client"=>urlencode("إرسال الرسائل القصيرة إلى العميل"),
"send_sms_to_admin"=>urlencode("إرسال SMS إلى المسؤول"),
"admin_phone_number"=>urlencode("رقم هاتف المسؤول"),
"available_from_within_your_twilio_account"=>urlencode("متاح من داخل حساب Twilio الخاص بك."),
"must_be_a_valid_number_associated_with_your_twilio_account"=>urlencode("يجب أن يكون رقمًا صحيحًا مرتبط بحساب Twilio الخاص بك."),
"enable_or_disable_send_sms_to_client_for_appointment_booking_info"=>urlencode("تمكين أو تعطيل ، أرسل رسالة قصيرة إلى العميل للحصول على معلومات حجز الموعد."),
"enable_or_disable_send_sms_to_admin_for_appointment_booking_info"=>urlencode("تمكين أو تعطيل ، أرسل رسالة قصيرة SMS إلى المسؤول للحصول على معلومات حجز الموعد."),
"updated_sms_settings"=>urlencode("تحديث إعدادات الرسائل القصيرة"),
"parking_availability_frontend_option_display_status"=>urlencode("موقف سيارات"),
"vaccum_cleaner_frontend_option_display_status"=>urlencode("مكنسة كهربائية"),
"o_n"=>urlencode("On"),
"off"=>urlencode("إيقاف"),
"enable"=>urlencode("مكن"),
"disable"=>urlencode("تعطيل"),
"monthly"=>urlencode("شهريا"),
"weekly"=>urlencode("Weekly"),
"email_template"=>urlencode("البريد الإلكتروني TEMPLATE"),
"sms_notification"=>urlencode("البريد الإلكتروني TEMPLATE"),
"sms_template"=>urlencode("نموذج SMS"),
"email_template_settings"=>urlencode("إعدادات قالب البريد الإلكتروني"),
"client_email_templates"=>urlencode("قالب البريد الإلكتروني للعميل"),
"client_sms_templates"=>urlencode("قالب SMS العميل"),
"admin_email_template"=>urlencode("نموذج البريد الإلكتروني للمشرف"),
"admin_sms_template"=>urlencode("قالب SMS المسؤول"),
"tags"=>urlencode("الكلمات"),
"booking_date"=>urlencode("تاريخ الحجز"),
"service_name"=>urlencode("اسم الخدمة"),
"business_logo"=>urlencode("business_logo"),
"business_logo_alt"=>urlencode("business_logo_alt"),
"admin_name"=>urlencode("ADMIN_NAME"),
"methodname"=>urlencode("METHOD_NAME"),
"firstname"=>urlencode("الاسم الاول"),
"lastname"=>urlencode("الكنية"),
"client_email"=>urlencode("client_email"),
"vaccum_cleaner_status"=>urlencode("vaccum_cleaner_status"),
"parking_status"=>urlencode("parking_status"),
"app_remain_time"=>urlencode("app_remain_time"),
"reject_status"=>urlencode("reject_status"),
"save_template"=>urlencode("حفظ القالب"),
"default_template"=>urlencode("القالب الافتراضي"),
"sms_template_settings"=>urlencode("إعدادات قالب SMS"),
"secret_key"=>urlencode("مفتاح سر"),
"publishable_key"=>urlencode("مفتاح Publishable"),
"payment_form"=>urlencode("نموذج الدفع"),
"api_login_id"=>urlencode("معرف تسجيل دخول واجهة برمجة التطبيقات"),
"transaction_key"=>urlencode("مفتاح المعاملة"),
"sandbox_mode"=>urlencode("وضع الحماية"),
"available_from_within_your_plivo_account"=>urlencode("المتاحة من داخل حسابك بليفو."),
"must_be_a_valid_number_associated_with_your_plivo_account"=>urlencode("يجب أن يكون رقمًا صحيحًا مرتبطًا بحسابك في Plivo."),
"whats_new"=>urlencode("ما هو الجديد؟"),
"company_phone"=>urlencode("هاتف"),
"company__name"=>urlencode("اسم الشركة"),
"booking_time"=>urlencode("booking_time"),
"company__email"=>urlencode("company_email"),
"company__address"=>urlencode("عنوان الشركة"),
"company__zip"=>urlencode("company_zip"),
"company__phone"=>urlencode("هاتف الشركة"),
"company__state"=>urlencode("company_state"),
"company__country"=>urlencode("company_country"),
"company__city"=>urlencode("company_city"),
"page_title"=>urlencode("عنوان الصفحة"),
"client__zip"=>urlencode("client_zip"),
"client__state"=>urlencode("دولة عميلة"),
"client__city"=>urlencode("client_city"),
"client__address"=>urlencode("client_address"),
"client__phone"=>urlencode("client_phone"),
"company_logo_is_used_for_invoice_purpose"=>urlencode("يتم استخدام شعار الشركة في البريد الإلكتروني وصفحة الحجز"),
"private_key"=>urlencode("مفتاح سري"),
"seller_id"=>urlencode("معرف البائع"),
"postal_codes_ed"=>urlencode("يمكنك تمكين ميزة الرموز البريدية أو الرموز البريدية أو تعطيلها وفقًا لمتطلبات البلد ، نظرًا لأن بعض البلدان مثل الإمارات العربية المتحدة لا تحتوي على رمز بريدي."),
"postal_codes_info"=>urlencode("يمكنك ذكر الرموز البريدية بطريقتين: # 1. يمكنك ذكر الرموز الكاملة للمباريات مثل K1A232 و L2A334 و C3A4C4 # 2. يمكنك استخدام الرموز البريدية الجزئية لإدخالات مطابقة البطاقات البرية ، على سبيل المثال. سوف يتطابق نظام K1A، L2A، C3 مع تلك الأحرف البادئة للرمز البريدي في المقدمة وسيتجنبك كتابة العديد من الرموز البريدية."),
"first"=>urlencode("أول"),
"second"=>urlencode("ثانيا"),
"third"=>urlencode("الثالث"),
"fourth"=>urlencode("رابع"),
"fifth"=>urlencode("خامس"),
"first_week"=>urlencode("الأسبوع الأول"),
"second_week"=>urlencode("Second-Week"),
"third_week"=>urlencode("الاسبوع الثالث"),
"fourth_week"=>urlencode("الأسبوع الرابع"),
"fifth_week"=>urlencode("خامس أسبوع"),
"this_week"=>urlencode("هذا الاسبوع"),
"monday"=>urlencode("الإثنين"),
"tuesday"=>urlencode("الثلاثاء"),
"wednesday"=>urlencode("الأربعاء"),
"thursday"=>urlencode("الخميس"),
"friday"=>urlencode("Friday"),
"saturday"=>urlencode("يوم السبت"),
"sunday"=>urlencode("الأحد"),
"appointment_request"=>urlencode("طلب موعد"),
"appointment_approved"=>urlencode("المواعيد المعتمدة"),
"appointment_rejected"=>urlencode("تم رفض الموعد"),
"appointment_cancelled_by_you"=>urlencode("الموعد ألغيت من قبلك"),
"appointment_rescheduled_by_you"=>urlencode("موعد إعادة جدولة من قبلك"),
"client_appointment_reminder"=>urlencode("تذكير موعد العميل"),
"new_appointment_request_requires_approval"=>urlencode("طلب موعد جديد يتطلب موافقة"),
"appointment_cancelled_by_customer"=>urlencode("الموعد ملغى من قبل العميل"),
"appointment_rescheduled_by_customer"=>urlencode("موعد إعادة جدولة من قبل العميل"),
"admin_appointment_reminder"=>urlencode("تذكير موعد المسؤول"),
"off_days_added_successfully"=>urlencode("تمت إضافة أيام بنجاح بنجاح"),
"off_days_deleted_successfully"=>urlencode("تم تعطيل Off Days بنجاح"),
"sorry_not_available"=>urlencode("آسف غير متوفر"),
"success"=>urlencode("نجاح"),
"failed"=>urlencode("فشل"),
"once"=>urlencode("ذات مرة"),
"Bi_Monthly"=>urlencode("نصف شهرية"),
"Fortnightly"=>urlencode("مرة كل اسبوعين"),
"Recurrence_Type"=>urlencode("تكرار نوع"),
"bi_weekly"=>urlencode("مرة كل أسبوعين"),
"Daily"=>urlencode("اليومي"),
"guest_customers_bookings"=>urlencode("عملاء زبائن الحجز"),
"existing_and_new_user_checkout"=>urlencode("تسجيل المستخدم الحالي والجديد"),
"it_will_allow_option_for_user_to_get_booking_with_new_user_or_existing_user"=>urlencode("سيسمح الخيار للمستخدم بالحصول على حجز مستخدم جديد أو مستخدم حالي"),
"0_1"=>urlencode("01"),
"1_1"=>urlencode("1.1"),
"1_2"=>urlencode("1.2"),
"0_2"=>urlencode("02"),
"free"=>urlencode("حر"),
"show_company_address_in_header"=>urlencode("إظهار عنوان الشركة في رأس الصفحة"),
"calendar_week"=>urlencode("أسبوع"),
"calendar_month"=>urlencode("شهر"),
"calendar_day"=>urlencode("يوم"),
"calendar_today"=>urlencode("اليوم"),
"restore_default"=>urlencode("استعادة الافتراضي"),
"scrollable_cart"=>urlencode("عربة قابلة للتمرير"),
"merchant_key"=>urlencode("مفتاح التاجر"),
"salt_key"=>urlencode("مفتاح السلط"),
"textlocal_sms_gateway"=>urlencode("بوابة SMS Textlocal"),
"textlocal_sms_settings"=>urlencode("إعدادات SMS Textlocal"),
"textlocal_account_settings"=>urlencode("إعدادات حساب Textlocal"),
"account_username"=>urlencode("اسم صاحب الحساب"),
"account_hash_id"=>urlencode("معرف تجزئة الحساب"),
"email_id_registered_with_you_textlocal"=>urlencode("تقديم البريد الإلكتروني الخاص بك مسجل مع textlocal"),
"hash_id_provided_by_textlocal"=>urlencode("معرف التجزئة المقدم من قبل textlocal"),
"bank_transfer"=>urlencode("التحويل المصرفي"),
"bank_name"=>urlencode("اسم البنك"),
"account_name"=>urlencode("أسم الحساب"),
"account_number"=>urlencode("رقم حساب"),
"branch_code"=>urlencode("رمز الفرع"),
"ifsc_code"=>urlencode("رمز IFSC"),
"bank_description"=>urlencode("وصف البنك"),
"your_cart_items"=>urlencode("البنود العربة الخاصة بك"),
"show_how_will_we_get_in"=>urlencode("عرض كيف سندخل"),
"show_description"=>urlencode("إظهار الوصف"),
"bank_details"=>urlencode("التفاصيل المصرفية"),
"ok_remove_sample_data"=>urlencode("حسنا"),
"book_appointment"=>urlencode("موعد الكتاب"),
"remove_sample_data_message"=>urlencode("أنت تحاول إزالة نموذج البيانات. إذا أزلت نموذجًا من البيانات ، فسيتم حذف الحجز المتعلق بنموذج من الخدمات بشكل دائم. للمتابعة ، يرجى النقر فوق موافق"),
"recommended_image_type_jpg_jpeg_png_gif"=>urlencode("(نوع الصورة الموصى به jpg و jpeg و png و gif)"),
"authetication"=>urlencode("المصادقة"),
"encryption_type"=>urlencode("نوع التشفير"),
"plain"=>urlencode("عادي"),
"true"=>urlencode("صحيح"),
"false"=>urlencode("خاطئة"),
"change_calculation_policy"=>urlencode("تغيير الحساب"),
"multiply"=>urlencode("تتضاعف"),
"equal"=>urlencode("Equal"),
"warning"=>urlencode("تحذير!"),
"field_name"=>urlencode("اسم الحقل"),
"enable_disable"=>urlencode("مفعل وغير مفعل"),
"required"=>urlencode("مطلوب"),
"min_length"=>urlencode("طول دقيقة"),
"max_length"=>urlencode("الحد الاقصى للطول"),
"appointment_details_section"=>urlencode("قسم تفاصيل المواعيد"),
"if_you_are_having_booking_system_which_need_the_booking_address_then_please_make_this_field_enable_or_else_it_will_not_able_to_take_the_booking_address_and_display_blank_address_in_the_booking"=>urlencode("إذا كان لديك نظام حجز والذي يحتاج إلى عنوان الحجز ، فيرجى التأكد من هذا الحقل وإلا فلن يتمكن من أخذ عنوان الحجز وعرض العنوان الفارغ في الحجز"),
"front_language_dropdown"=>urlencode("المنسدلة اللغة الأمامية"),
"enabled"=>urlencode("تمكين"),
"vaccume_cleaner"=>urlencode("مكنسة كهربائية"),
"staff_members"=>urlencode("طاقم العمل"),
"add_new_staff_member"=>urlencode("إضافة عضو جديد"),
"role"=>urlencode("وظيفة"),
"staff"=>urlencode("العاملين"),
"admin"=>urlencode("مشرف"),
"service_details"=>urlencode("تفاصيل الخدمة"),
"technical_admin"=>urlencode("المدير الفني"),
"enable_booking"=>urlencode("تمكين الحجز"),
"flat_commission"=>urlencode("لجنة مسطحة"),
"manageable_form_fields_front_booking_form"=>urlencode("حقول نموذج يمكن إدارتها لنموذج الحجز الأمامي"),
"manageable_form_fields"=>urlencode("حقول نموذج يمكن إدارتها"),
"sms"=>urlencode("رسالة قصيرة"),
"crm"=>urlencode("CRM"),
"message"=>urlencode("رسالة"),
"send_message"=>urlencode("ارسل رسالة"),
"all_messages"=>urlencode("جميع الرسائل"),
"subject"=>urlencode("موضوع"),
"add_attachment"=>urlencode("إضافة مرفق"),
"send"=>urlencode("إرسال"),
"close"=>urlencode("قريب"),
"delete_this_customer?"=>urlencode("حذف هذا الزبون؟"),
"yes"=>urlencode("نعم فعلا"),
"add_new_customer"=>urlencode("أضف زبون جديد"),
"attachment"=>urlencode("المرفق"),
"date"=>urlencode("تاريخ"),
"see_attachment"=>urlencode("انظر المرفق"),
"no_attachment"=>urlencode("لا يوجد مرفق"),
"ct_special_offer"=>urlencode("عرض خاص"),
"ct_special_offer_text"=>urlencode("عرض خاص نص"),
);

$error_labels_ar = array (
"language_status_change_successfully"=>urlencode("تغيير حالة اللغة بنجاح"),
"commission_amount_should_not_be_greater_then_order_amount"=>urlencode("يجب ألا يكون مبلغ العمولة أكبر من مبلغ الطلب"),
"please_enter_merchant_ID"=>urlencode("الرجاء إدخال معرّف التاجر"),
"please_enter_secure_key"=>urlencode("يرجى إدخال مفتاح الأمان"),
"please_enter_google_calender_admin_url"=>urlencode("الرجاء إدخال google calender admin url"),
"please_enter_google_calender_frontend_url"=>urlencode("الرجاء إدخال عنوان url الخاص بـ google calender frontal"),
"please_enter_google_calender_client_secret"=>urlencode("الرجاء إدخال سر عميل برنامج تقويم Google"),
"please_enter_google_calender_client_ID"=>urlencode("الرجاء إدخال معرف عميل Google Calendar"),
"please_enter_google_calender_ID"=>urlencode("الرجاء إدخال معرف تقويم Google"),
"you_cannot_book_on_past_date"=>urlencode("لا يمكنك الحجز في تاريخ الماضي"),
"Invalid_Image_Type"=>urlencode("نوع الصورة غير صالح"),
"seo_settings_updated_successfully"=>urlencode("تم تحديث إعدادات تحسين محركات البحث بنجاح"),
"customer_deleted_successfully"=>urlencode("العميل حذف بنجاح"),
"please_enter_below_36_characters"=>urlencode("يرجى إدخال 36 حرفًا أدناه"),
"are_you_sure_you_want_to_delete_client"=>urlencode("هل أنت متأكد من أنك تريد حذف عميل؟"),
"please_select_atleast_one_unit"=>urlencode("يرجى تحديد وحدة واحدة على الأقل"),
"atleast_one_payment_method_should_be_enable"=>urlencode("يجب تمكين طريقة دفع واحدة على الأقل"),
"appointment_booking_confirm"=>urlencode("تأكيد حجز موعد"),
"appointment_booking_rejected"=>urlencode("حجز موعد رفض"),
"booking_cancel"=>urlencode("تم إلغاء الحجز"),
"appointment_marked_as_no_show"=>urlencode("تم وضع علامة على تعيين عدم وجود عرض"),
"appointment_reschedules_successfully"=>urlencode("موعد إعادة جدولة بنجاح"),
"booking_deleted"=>urlencode("حجز محذوف"),
"break_end_time_should_be_greater_than_start_time"=>urlencode("يجب أن يكون وقت نهاية الفصل أكبر من وقت البدء"),
"cancel_by_client"=>urlencode("إلغاء من قبل العميل"),
"cancelled_by_service_provider"=>urlencode("ملغى من قبل مزود الخدمة"),
"design_set_successfully"=>urlencode("تصميم مجموعة بنجاح"),
"end_break_time_updated"=>urlencode("نهاية كسر الوقت المحدث"),
"enter_alphabets_only"=>urlencode("أدخل الحروف الهجائية فقط"),
"enter_only_alphabets"=>urlencode("أدخل الحروف الهجائية فقط"),
"enter_only_alphabets_numbers"=>urlencode("أدخل فقط الحروف الهجائية / الأرقام"),
"enter_only_digits"=>urlencode("أدخل فقط أرقام"),
"enter_valid_url"=>urlencode("أدخل عنوان Url صالح"),
"enter_only_numeric"=>urlencode("أدخل فقط الأرقام"),
"enter_proper_country_code"=>urlencode("أدخل رمز البلد الصحيح"),
"frequently_discount_status_updated"=>urlencode("كثيرا ما يتم تحديث حالة الخصم"),
"frequently_discount_updated"=>urlencode("تخفيض في كثير من الأحيان تحديثها"),
"manage_addons_service"=>urlencode("إدارة الخدمات الإضافية"),
"maximum_file_upload_size_2_mb"=>urlencode("الحد الأقصى لحجم تحميل الملف 2 ميغابايت"),
"method_deleted_successfully"=>urlencode("تم حذف الطريقة بنجاح"),
"method_inserted_successfully"=>urlencode("الطريقة التي تم إدخالها بنجاح"),
"minimum_file_upload_size_1_kb"=>urlencode("الحد الأدنى لحجم تحميل الملف 1 كيلوبايت"),
"off_time_added_successfully"=>urlencode("إيقاف الوقت الذي تمت إضافته بنجاح"),
"only_jpeg_png_and_gif_images_allowed"=>urlencode("فقط صور jpeg، png و gif مسموح بها"),
"only_jpeg_png_gif_zip_and_pdf_allowed"=>urlencode("فقط jpeg و png و gif و zip و pdf مسموح به"),
"please_wait_while_we_send_all_your_message"=>urlencode("يرجى الانتظار بينما نرسل كل رسائلك"),
"please_enable_email_to_client"=>urlencode("يرجى تمكين رسائل البريد الإلكتروني إلى العميل."),
"please_enable_sms_gateway"=>urlencode("يرجى تمكين بوابة الرسائل القصيرة."),
"please_enable_client_notification"=>urlencode("يرجى تمكين إخطار العميل."),
"password_must_be_8_character_long"=>urlencode("يجب أن تكون كلمة المرور 8 أحرف"),
"password_should_not_exist_more_then_20_characters"=>urlencode("يجب ألا تتواجد كلمة المرور أكثر من 20 حرفًا"),
"please_assign_base_price_for_unit"=>urlencode("يرجى تحديد السعر الأساسي للوحدة"),
"please_assign_price"=>urlencode("يرجى تحديد السعر"),
"please_assign_qty"=>urlencode("يرجى تعيين الكمية"),
"please_enter_api_password"=>urlencode("الرجاء إدخال كلمة مرور API"),
"please_enter_api_username"=>urlencode("الرجاء إدخال اسم مستخدم واجهة برمجة التطبيقات"),
"please_enter_color_code"=>urlencode("يرجى إدخال كود اللون"),
"please_enter_country"=>urlencode("من فضلك ادخل البلد"),
"please_enter_coupon_limit"=>urlencode("يرجى إدخال حد القسيمة"),
"please_enter_coupon_value"=>urlencode("يرجى إدخال قيمة القسيمة"),
"please_enter_coupon_code"=>urlencode("يرجى إدخال كود القسيمة"),
"please_enter_email"=>urlencode("يرجى إدخال البريد الإلكتروني"),
"please_enter_fullname"=>urlencode("يرجى إدخال Fullname"),
"please_enter_maxlimit"=>urlencode("يرجى إدخال maxLimit"),
"please_enter_method_title"=>urlencode("يرجى إدخال عنوان الطريقة"),
"please_enter_name"=>urlencode("يرجى إدخال الاسم"),
"please_enter_only_numeric"=>urlencode("الرجاء إدخال الأرقام فقط"),
"please_enter_proper_base_price"=>urlencode("يرجى إدخال السعر الأساسي المناسب"),
"please_enter_proper_name"=>urlencode("يرجى إدخال الاسم الصحيح"),
"please_enter_proper_title"=>urlencode("يرجى إدخال العنوان الصحيح"),
"please_enter_publishable_key"=>urlencode("الرجاء إدخال مفتاح Publishable"),
"please_enter_secret_key"=>urlencode("يرجى إدخال مفتاح سر"),
"please_enter_service_title"=>urlencode("يرجى إدخال عنوان الخدمة"),
"please_enter_signature"=>urlencode("يرجى إدخال التوقيع"),
"please_enter_some_qty"=>urlencode("يرجى إدخال بعض الكمية"),
"please_enter_title"=>urlencode("يرجى إدخال العنوان"),
"please_enter_unit_title"=>urlencode("يرجى إدخال عنوان الوحدة"),
"please_enter_valid_country_code"=>urlencode("يرجى إدخال رمز البلد الصحيح"),
"please_enter_valid_service_title"=>urlencode("يرجى إدخال عنوان خدمة صالح"),
"please_enter_valid_price"=>urlencode("يرجى إدخال سعر صالح"),
"please_enter_zipcode"=>urlencode("يرجى إدخال الرمز البريدي"),
"please_enter_state"=>urlencode("يرجى إدخال الدولة"),
"please_retype_correct_password"=>urlencode("الرجاء إعادة كتابة كلمة المرور الصحيحة"),
"please_select_porper_time_slots"=>urlencode("يرجى تحديد فتحات الوقت porper"),
"please_select_time_between_day_availability_time"=>urlencode("يرجى تحديد الوقت بين وقت توافر اليوم"),
"please_valid_value_for_discount"=>urlencode("يرجى قيمة صالحة للخصم"),
"please_enter_confirm_password"=>urlencode("من فضلك أدخل كلمة مرور تأكيد"),
"please_enter_new_password"=>urlencode("يرجى إدخال كلمة المرور الجديدة"),
"please_enter_old_password"=>urlencode("يرجى إدخال كلمة المرور القديمة"),
"please_enter_valid_number"=>urlencode("يرجى إدخال رقم صحيح"),
"please_enter_valid_number_with_country_code"=>urlencode("يرجى إدخال رقم صالح مع رمز البلد"),
"please_select_end_time_greater_than_start_time"=>urlencode("يرجى تحديد وقت النهاية أكبر من وقت البدء"),
"please_select_end_time_less_than_start_time"=>urlencode("يرجى تحديد وقت الانتهاء أقل من وقت البدء"),
"please_select_a_crop_region_and_then_press_upload"=>urlencode("يرجى تحديد منطقة الاقتصاص ثم الضغط على تحميل"),
"please_select_a_valid_image_file_jpg_and_png_are_allowed"=>urlencode("يرجى تحديد ملف صور صالح jpg و png مسموح به"),
"profile_updated_successfully"=>urlencode("تم تحديث الملف الشخصي بنجاح"),
"qty_rule_deleted"=>urlencode("تم حذف قاعدة الكمية"),
"record_deleted_successfully"=>urlencode("تم حذف السجل بنجاح"),
"record_updated_successfully"=>urlencode("تم تحديث السجل بنجاح"),
"rescheduled"=>urlencode("إعادة جدولتها"),
"schedule_updated_to_monthly"=>urlencode("جدول تحديثها إلى الشهرية"),
"schedule_updated_to_weekly"=>urlencode("جدولة تحديث إلى أسبوعي"),
"sorry_method_already_exist"=>urlencode("طريقة عذرا موجودة بالفعل"),
"sorry_no_notification"=>urlencode("عذرا ، ليس لديك أي موعد قادم"),
"sorry_promocode_already_exist"=>urlencode("آسف رمز التواجد بالفعل"),
"sorry_unit_already_exist"=>urlencode("عذرا وحدة موجودة بالفعل"),
"sorry_we_are_not_available"=>urlencode("عذرا نحن لسنا متاحين"),
"start_break_time_updated"=>urlencode("ابدأ وقت الكسر محدثًا"),
"status_updated"=>urlencode("الحالة محدثة"),
"time_slots_updated_successfully"=>urlencode("تم تحديث الفترات الزمنية بنجاح"),
"unit_inserted_successfully"=>urlencode("تم إدراج الوحدة بنجاح"),
"units_status_updated"=>urlencode("تم تحديث حالة الوحدات"),
"updated_appearance_settings"=>urlencode("تحديث مظهر الإعدادات"),
"updated_company_details"=>urlencode("تفاصيل الشركة المحدثة"),
"updated_email_settings"=>urlencode("تحديث إعدادات البريد الإلكتروني"),
"updated_general_settings"=>urlencode("الإعدادات العامة المحدثة"),
"updated_payments_settings"=>urlencode("تحديث إعدادات الدفعات"),
"your_old_password_incorrect"=>urlencode("كلمة المرور القديمة غير صحيحة"),
"please_enter_minimum_5_chars"=>urlencode("يرجى إدخال 5 أحرف كحد أدنى"),
"please_enter_maximum_10_chars"=>urlencode("يرجى إدخال 10 أحرف كحد أقصى"),
"please_enter_postal_code"=>urlencode("يرجى إدخال الرمز البريدي"),
"please_select_a_service"=>urlencode("يرجى اختيار خدمة"),
"please_select_units_and_addons"=>urlencode("يرجى تحديد الوحدات والإضافات"),
"please_select_units_or_addons"=>urlencode("يرجى تحديد الوحدات أو الأدوات الإضافية"),
"please_login_to_complete_booking"=>urlencode("الرجاء تسجيل الدخول لإكمال الحجز"),
"please_select_appointment_date"=>urlencode("يرجى تحديد موعد الموعد"),
"please_accept_terms_and_conditions"=>urlencode("يرجى قبول الشروط والأحكام"),
"incorrect_email_address_or_password"=>urlencode("عنوان بريد إلكتروني غير صحيح أو كلمة مرور غير صحيحة"),
"please_enter_valid_email_address"=>urlencode("الرجاء إدخال عنوان بريد إلكتروني صالح"),
"please_enter_email_address"=>urlencode("الرجاء إدخال عنوان البريد الإلكتروني"),
"please_enter_password"=>urlencode("يرجى إدخال كلمة المرور"),
"please_enter_minimum_8_characters"=>urlencode("يرجى إدخال 8 أحرف كحد أدنى"),
"please_enter_maximum_15_characters"=>urlencode("يرجى إدخال 15 حرفًا كحد أقصى"),
"please_enter_first_name"=>urlencode("يرجى إدخال الاسم الأول"),
"please_enter_only_alphabets"=>urlencode("الرجاء إدخال الحروف الهجائية فقط"),
"please_enter_minimum_2_characters"=>urlencode("يرجى إدخال الحد الأدنى من حرفين"),
"please_enter_last_name"=>urlencode("يرجى إدخال الاسم الأخير"),
"email_already_exists"=>urlencode("البريد الالكتروني موجود بالفعل"),
"please_enter_phone_number"=>urlencode("يرجى إدخال رقم الهاتف"),
"please_enter_only_numerics"=>urlencode("الرجاء إدخال الأعداد فقط"),
"please_enter_minimum_10_digits"=>urlencode("يرجى إدخال 10 أرقام على الأقل"),
"please_enter_maximum_14_digits"=>urlencode("يرجى إدخال 14 رقمًا كحد أقصى"),
"please_enter_address"=>urlencode("يرجى إدخال العنوان"),
"please_enter_minimum_20_characters"=>urlencode("يرجى إدخال 20 حرفًا كحد أدنى"),
"please_enter_zip_code"=>urlencode("يرجى إدخال الرمز البريدي"),
"please_enter_proper_zip_code"=>urlencode("يرجى إدخال الرمز البريدي الصحيح"),
"please_enter_minimum_5_digits"=>urlencode("الرجاء إدخال 5 أرقام كحد أدنى"),
"please_enter_maximum_7_digits"=>urlencode("يرجى إدخال 7 أرقام كحد أقصى"),
"please_enter_city"=>urlencode("من فضلك ادخل المدينة"),
"please_enter_proper_city"=>urlencode("الرجاء إدخال المدينة المناسبة"),
"please_enter_maximum_48_characters"=>urlencode("يرجى إدخال 48 حرفًا كحد أقصى"),
"please_enter_proper_state"=>urlencode("يرجى إدخال الحالة الصحيحة"),
"please_enter_contact_status"=>urlencode("يرجى إدخال حالة الاتصال"),
"please_enter_maximum_100_characters"=>urlencode("يرجى إدخال 100 حرفًا كحد أقصى"),
"your_cart_is_empty_please_add_cleaning_services"=>urlencode("عربة التسوق فارغة ، يرجى إضافة خدمات التنظيف"),
"coupon_expired"=>urlencode("انتهت صلاحية الكوبون"),
"invalid_coupon"=>urlencode("قسيمة غير صالحة"),
"our_service_not_available_at_your_location"=>urlencode("خدمتنا غير متوفرة في موقعك"),
"please_enter_proper_postal_code"=>urlencode("الرجاء إدخال الرمز البريدي الصحيح"),
"invalid_email_id_please_register_first"=>urlencode("معرف البريد الإلكتروني غير صالح يرجى التسجيل أولاً"),
"your_password_send_successfully_at_your_registered_email_id"=>urlencode("كلمة المرور الخاصة بك ترسل بنجاح على معرف البريد الإلكتروني المسجل الخاص بك"),
"your_password_reset_successfully_please_login"=>urlencode("إعادة تعيين كلمة المرور الخاصة بك بنجاح يرجى تسجيل الدخول"),
"new_password_and_retype_new_password_mismatch"=>urlencode("كلمة المرور الجديدة وإعادة كتابة عدم تطابق كلمة المرور الجديدة"),
"new"=>urlencode("الجديد"),
"your_reset_password_link_expired"=>urlencode("انتهت صلاحية رابط إعادة تعيين كلمة المرور الخاصة بك"),
"front_display_language_changed"=>urlencode("تغيير لغة العرض"),
"updated_front_display_language_and_update_labels"=>urlencode("تحديث لغة العرض الأمامية وتحديث الملصقات"),
"please_enter_only_7_chars_maximum"=>urlencode("يرجى إدخال 7 أحرف فقط كحد أقصى"),
"please_enter_maximum_20_chars"=>urlencode("يرجى إدخال 20 حرفًا كحد أقصى"),
"record_inserted_successfully"=>urlencode("سجل تدرج بنجاح"),
"please_enter_account_sid"=>urlencode("الرجاء إدخال Accout SID"),
"please_enter_auth_token"=>urlencode("يرجى إدخال Auth Token"),
"please_enter_sender_number"=>urlencode("يرجى إدخال رقم المرسل"),
"please_enter_admin_number"=>urlencode("يرجى إدخال رقم المسؤول"),
"sorry_service_already_exist"=>urlencode("عذرا خدمة موجودة بالفعل"),
"please_enter_api_login_id"=>urlencode("يرجى إدخال معرف تسجيل دخول واجهة برمجة التطبيقات"),
"please_enter_transaction_key"=>urlencode("يرجى إدخال مفتاح المعاملة"),
"please_enter_sms_message"=>urlencode("الرجاء إدخال رسالة sms"),
"please_enter_email_message"=>urlencode("يرجى إدخال رسالة البريد الإلكتروني"),
"please_enter_private_key"=>urlencode("يرجى إدخال مفتاح خاص"),
"please_enter_seller_id"=>urlencode("يرجى إدخال معرف البائع"),
"please_enter_valid_value_for_discount"=>urlencode("الرجاء إدخال قيمة صالحة للخصم"),
"password_must_be_only_10_characters"=>urlencode("كلمة المرور يجب أن تكون 10 أحرف فقط"),
"password_at_least_have_8_characters"=>urlencode("كلمة المرور على الأقل لديك 8 أحرف"),
"please_enter_retype_new_password"=>urlencode("يرجى إدخال إعادة كتابة كلمة المرور الجديدة"),
"your_password_send_successfully_at_your_email_id"=>urlencode("كلمة المرور الخاصة بك ترسل بنجاح في معرف البريد الإلكتروني الخاص بك"),
"please_select_expiry_date"=>urlencode("يرجى تحديد تاريخ انتهاء الصلاحية"),
"please_enter_merchant_key"=>urlencode("يرجى إدخال مفتاح التاجر"),
"please_enter_salt_key"=>urlencode("يرجى إدخال Salt Key"),
"please_enter_account_username"=>urlencode("يرجى إدخال اسم مستخدم الحساب"),
"please_enter_account_hash_id"=>urlencode("الرجاء إدخال معرف تجزئة الحساب"),
"invalid_values"=>urlencode("قيم غير صالحة"),
"please_select_atleast_one_checkout_method"=>urlencode("يرجى اختيار واحد على الأقل وسيلة الخروج"),
);

$extra_labels_ar = array (
"please_enter_minimum_3_chars"=>urlencode("يرجى إدخال 3 أحرف كحد أدنى"),
"invoice"=>urlencode("فاتورة"),
"invoice_to"=>urlencode("فاتورة إلى"),
"invoice_date"=>urlencode("تاريخ الفاتورة"),
"cash"=>urlencode("السيولة النقدية"),
"service_name"=>urlencode("اسم الخدمة"),
"qty"=>urlencode("الكمية"),
"booked_on"=>urlencode("تم الحجز"),
);

$front_error_labels_ar = array (
"min_ff_ps"=>urlencode("يرجى إدخال 8 أحرف كحد أدنى"),
"max_ff_ps"=>urlencode("يرجى إدخال 10 أحرف كحد أقصى"),
"req_ff_fn"=>urlencode("يرجى إدخال الاسم الأول"),
"min_ff_fn"=>urlencode("يرجى إدخال 3 أحرف كحد أدنى"),
"max_ff_fn"=>urlencode("يرجى إدخال 15 حرفًا كحد أقصى"),
"req_ff_ln"=>urlencode("يرجى إدخال الاسم الأخير"),
"min_ff_ln"=>urlencode("يرجى إدخال 3 أحرف كحد أدنى"),
"max_ff_ln"=>urlencode("يرجى إدخال 15 حرفًا كحد أقصى"),
"req_ff_ph"=>urlencode("يرجى إدخال رقم الهاتف"),
"min_ff_ph"=>urlencode("يرجى إدخال 9 أحرف كحد أدنى"),
"max_ff_ph"=>urlencode("يرجى إدخال 15 حرفًا كحد أقصى"),
"req_ff_sa"=>urlencode("يرجى إدخال عنوان الشارع"),
"min_ff_sa"=>urlencode("يرجى إدخال 10 أحرف كحد أدنى"),
"max_ff_sa"=>urlencode("يرجى إدخال 40 حرفًا كحد أقصى"),
"req_ff_zp"=>urlencode("يرجى إدخال الرمز البريدي"),
"min_ff_zp"=>urlencode("يرجى إدخال 3 أحرف كحد أدنى"),
"max_ff_zp"=>urlencode("يرجى إدخال 7 أحرف كحد أقصى"),
"req_ff_ct"=>urlencode("من فضلك ادخل المدينة"),
"min_ff_ct"=>urlencode("يرجى إدخال 3 أحرف كحد أدنى"),
"max_ff_ct"=>urlencode("يرجى إدخال 15 حرفًا كحد أقصى"),
"req_ff_st"=>urlencode("يرجى إدخال الدولة"),
"min_ff_st"=>urlencode("يرجى إدخال 3 أحرف كحد أدنى"),
"max_ff_st"=>urlencode("يرجى إدخال 15 حرفًا كحد أقصى"),
"req_ff_srn"=>urlencode("يرجى إدخال الملاحظات"),
"min_ff_srn"=>urlencode("يرجى إدخال 10 أحرف كحد أدنى"),
"max_ff_srn"=>urlencode("يرجى إدخال 70 حرفًا كحد أقصى"),
"Transaction_failed_please_try_again"=>urlencode("فشلت الصفقة يرجى المحاولة مرة أخرى"),
"Please_Enter_valid_card_detail"=>urlencode("يرجى إدخال تفاصيل بطاقة صالحة"),
);

$language_front_arr_ar = base64_encode(serialize($label_data_ar));
$language_admin_arr_ar = base64_encode(serialize($admin_labels_ar));
$language_error_arr_ar = base64_encode(serialize($error_labels_ar));
$language_extra_arr_ar = base64_encode(serialize($extra_labels_ar));
$language_form_error_arr_ar = base64_encode(serialize($front_error_labels_ar));

$insert_default_lang_ar = "insert into `ct_languages` (`id`,`label_data`,`language`, `admin_labels`, `error_labels`, `extra_labels`, `front_error_labels`,`language_status`) values(NULL,'" . $language_front_arr_ar . "','ar','" . $language_admin_arr_ar . "','" . $language_error_arr_ar . "','" . $language_extra_arr_ar . "','" . $language_form_error_arr_ar . "','Y')";
mysqli_query($this->conn, $insert_default_lang_ar);

/** Chinese Language **/
$label_data_zh_CN = array (
"none_available"=>urlencode(" 没有可用"),
"appointment_zip"=>urlencode("预约邮编"),
"appointment_city"=>urlencode("预约城市"),
"appointment_state"=>urlencode("任命国"),
"appointment_address"=>urlencode("预约地址"),
"guest_user"=>urlencode("来宾用户"),
"service_usage_methods"=>urlencode("服务使用方法"),
"paypal"=>urlencode("贝宝"),
"please_check_for_the_below_missing_information"=>urlencode("请检查以下缺失的信息.."),
"please_provide_company_details_from_the_admin_panel"=>urlencode("请从管理面板提供公司详细信息。"),
"please_add_some_services_methods_units_addons_from_the_admin_panel"=>urlencode("请从管理面板添加一些服务，方法，单位，插件。"),
"please_add_time_scheduling_from_the_admin_panel"=>urlencode("请从管理面板添加时间安排。"),
"please_complete_configurations_before_you_created_website_embed_code"=>urlencode("请在创建网站嵌入代码之前完成配置。"),
"cvc"=>urlencode("CVC"),
"mm_yyyy"=>urlencode("(MM/YYYY)"),
"expiry_date_or_csv"=>urlencode("到期日期或CSV"),
"street_address_placeholder"=>urlencode("例如中央大道"),
"zip_code_placeholder"=>urlencode("例如90001"),
"city_placeholder"=>urlencode("例如。洛杉矶"),
"state_placeholder"=>urlencode("例如。 CA"),
"payumoney"=>urlencode("PayUmoney"),
"same_as_above"=>urlencode("与上面相同"),
"sun"=>urlencode("太阳"),
"mon"=>urlencode("周一"),
"tue"=>urlencode("星期二"),
"wed"=>urlencode("星期三"),
"thu"=>urlencode("集"),
"fri"=>urlencode("周五"),
"sat"=>urlencode("星期六"),
"su"=>urlencode("他的"),
"mo"=>urlencode("您"),
"tu"=>urlencode("您的"),
"we"=>urlencode("我们"),
"th"=>urlencode("钍"),
"fr"=>urlencode("神父"),
"sa"=>urlencode("她的"),
"my_bookings"=>urlencode("我的预订"),
"your_postal_code"=>urlencode("邮政编码"),
"where_would_you_like_us_to_provide_service"=>urlencode("您希望我们在哪里提供服务？"),
"choose_service"=>urlencode("选择服务"),
"how_often_would_you_like_us_provide_service"=>urlencode("您希望我们多久提供一次服务？"),
"when_would_you_like_us_to_come"=>urlencode("你什么时候想要我们来？"),
"today"=>urlencode("今天"),
"your_personal_details"=>urlencode("你个人详细资料"),
"existing_user"=>urlencode("现有用户"),
"new_user"=>urlencode("新用户"),
"preferred_email"=>urlencode("首选电邮"),
"preferred_password"=>urlencode("首选密码"),
"your_valid_email_address"=>urlencode("您的有效电子邮件地址"),
"first_name"=>urlencode("名字"),
"your_first_name"=>urlencode("你的名字"),
"last_name"=>urlencode("姓"),
"your_last_name"=>urlencode("你的姓氏"),
"street_address"=>urlencode("街道地址"),
"cleaning_service"=>urlencode("清洁服务"),
"please_select_method"=>urlencode("请选择方法"),
"zip_code"=>urlencode("邮政编码"),
"city"=>urlencode("市"),
"state"=>urlencode("州"),
"special_requests_notes"=>urlencode("特殊要求（备注）"),
"do_you_have_a_vaccum_cleaner"=>urlencode("你有吸尘器吗？"),
"assign_appointment_to_staff"=>urlencode("为员工分配任命"),
"delete_member"=>urlencode("删除会员？"),
"yes"=>urlencode("是"),
"no"=>urlencode("没有"),
"preferred_payment_method"=>urlencode("首选付款方式"),
"please_select_one_payment_method"=>urlencode("请选择一种付款方式"),
"partial_deposit"=>urlencode("部分存款"),
"remaining_amount"=>urlencode("剩余数量"),
"please_read_our_terms_and_conditions_carefully"=>urlencode("请仔细阅读我们的条款和条件"),
"do_you_have_parking"=>urlencode("你有停车吗？"),
"how_will_we_get_in"=>urlencode("我们将如何进入？"),
"i_will_be_at_home"=>urlencode("我会在家里"),
"please_call_me"=>urlencode("请给我打电话"),
"recurring_discounts_apply_from_the_second_cleaning_onward"=>urlencode("从第二次清洁开始，经常性折扣适用。"),
"please_provide_your_address_and_contact_details"=>urlencode("请提供您的地址和联系方式"),
"you_are_logged_in_as"=>urlencode("您登录为"),
"the_key_is_with_the_doorman"=>urlencode("关键在于门卫"),
"other"=>urlencode("其他"),
"have_a_promocode"=>urlencode("有促销码吗？"),
"apply"=>urlencode("应用"),
"applied_promocode"=>urlencode("应用Promocode"),
"complete_booking"=>urlencode("完成预订"),
"cancellation_policy"=>urlencode("取消政策"),
"cancellation_policy_header"=>urlencode("取消政策标题"),
"cancellation_policy_textarea"=>urlencode("取消政策Textarea"),
"free_cancellation_before_redemption"=>urlencode("兑换前免费取消"),
"show_more"=>urlencode("展示更多"),
"please_select_service"=>urlencode("请选择服务"),
"choose_your_service_and_property_size"=>urlencode("选择您的服务和房产大小"),
"choose_your_service"=>urlencode("选择你的服务"),
"please_configure_first_cleaning_services_and_settings_in_admin_panel"=>urlencode("请在管理面板中配置第一个清洁服务和设置"),
"i_have_read_and_accepted_the"=>urlencode("我已阅读并接受了"),
"terms_and_condition"=>urlencode("条款和条件"),
"and"=>urlencode("和"),
"updated_labels"=>urlencode("更新了标签"),
"privacy_policy"=>urlencode("隐私政策"),
"please_fill_all_the_company_informations_and_add_some_services_and_addons"=>urlencode("所需的配置未完成。"),
"booking_summary"=>urlencode("预订摘要"),
"your_email"=>urlencode("你的邮件"),
"enter_email_to_login"=>urlencode("输入电子邮件登录"),
"your_password"=>urlencode("你的密码"),
"enter_your_password"=>urlencode("输入您的密码"),
"forget_password"=>urlencode("忘记密码？"),
"reset_password"=>urlencode("重设密码"),
"enter_your_email_and_we_send_you_instructions_on_resetting_your_password"=>urlencode("输入您的电子邮件，我们会向您发送有关重置密码的说明。"),
"registered_email"=>urlencode("注册的电子邮件"),
"send_mail"=>urlencode("注册的电子邮件"),
"back_to_login"=>urlencode("回到登入"),
"your"=>urlencode("你的"),
"your_clean_items"=>urlencode("你干净的物品"),
"your_cart_is_empty"=>urlencode("您的购物车是空的"),
"sub_totaltax"=>urlencode("Sub TotalTax"),
"sub_total"=>urlencode("小计"),
"no_data_available_in_table"=>urlencode("表中没有数据"),
"total"=>urlencode("总"),
"or"=>urlencode("要么"),
"select_addon_image"=>urlencode("选择插件图像"),
"inside_fridge"=>urlencode("里面的冰箱"),
"inside_oven"=>urlencode("在烤箱里面"),
"inside_windows"=>urlencode("在Windows内部"),
"carpet_cleaning"=>urlencode("地毯清洁"),
"green_cleaning"=>urlencode("绿色清洁"),
"pets_care"=>urlencode("宠物护理"),
"tiles_cleaning"=>urlencode("瓷砖清洁"),
"wall_cleaning"=>urlencode("墙面清洁"),
"laundry"=>urlencode("洗衣店"),
"basement_cleaning"=>urlencode("地下室清洁"),
"basic_price"=>urlencode("基本价格"),
"max_qty"=>urlencode("最大数量"),
"multiple_qty"=>urlencode("多个数量"),
"base_price"=>urlencode("基本价格"),
"unit_pricing"=>urlencode("单位定价"),
"method_is_booked"=>urlencode("方法已预订"),
"service_addons_price_rules"=>urlencode("服务插件价格规则"),
"service_unit_front_dropdown_view"=>urlencode("服务单位前DropDown视图"),
"service_unit_front_block_view"=>urlencode("服务单元前块视图"),
"service_unit_front_increase_decrease_view"=>urlencode("服务单位前面增加/减少视图"),
"are_you_sure"=>urlencode("你确定"),
"service_unit_price_rules"=>urlencode("服务单价格规则"),
"close"=>urlencode("关"),
"closed"=>urlencode("关闭"),
"service_addons"=>urlencode("服务插件"),
"service_enable"=>urlencode("服务启用"),
"service_disable"=>urlencode("服务禁用"),
"method_enable"=>urlencode("方法启用"),
"off_time_deleted"=>urlencode("关闭时间已删除"),
"error_in_delete_of_off_time"=>urlencode("删除关闭时出错"),
"method_disable"=>urlencode("方法禁用"),
"extra_services"=>urlencode("额外服务"),
"for_initial_cleaning_only_contact_us_to_apply_to_recurrings"=>urlencode("仅适用于初次清洁。联系我们申请重复出现。"),
"number_of"=>urlencode("数量"),
"extra_services_not_available"=>urlencode("额外服务不可用"),
"available"=>urlencode("可得到"),
"selected"=>urlencode("选"),
"not_available"=>urlencode("无法使用"),
"none"=>urlencode("没有"),
"none_of_time_slot_available_please_check_another_dates"=>urlencode("没有可用的时段请检查其他日期"),
"availability_is_not_configured_from_admin_side"=>urlencode("管理员端未配置可用性"),
"how_many_intensive"=>urlencode("多少密集"),
"no_intensive"=>urlencode("没有强化"),
"frequently_discount"=>urlencode("经常折扣"),
"coupon_discount"=>urlencode("优惠券折扣"),
"how_many"=>urlencode("多少"),
"enter_your_other_option"=>urlencode("输入您的其他选项"),
"log_out"=>urlencode("登出"),
"your_added_off_times"=>urlencode("您的已关闭时间"),
"log_in"=>urlencode("登录"),
"custom_css"=>urlencode("自定义Css"),
"success"=>urlencode("成功"),
"failure"=>urlencode("失败"),
"you_can_only_use_valid_zipcode"=>urlencode("您只能使用有效的邮政编码"),
"minutes"=>urlencode("分钟"),
"hours"=>urlencode("小时"),
"days"=>urlencode("天"),
"months"=>urlencode("月"),
"year"=>urlencode("年"),
"default_url_is"=>urlencode("默认网址是"),
"card_payment"=>urlencode("Card payment"),
"pay_at_venue"=>urlencode("在地点付款"),
"card_details"=>urlencode("卡详细信息"),
"card_number"=>urlencode("卡号"),
"invalid_card_number"=>urlencode("无效卡号"),
"expiry"=>urlencode("到期"),
"button_preview"=>urlencode("按钮预览"),
"thankyou"=>urlencode("谢谢"),
"thankyou_for_booking_appointment"=>urlencode("谢谢！预约"),
"you_will_be_notified_by_email_with_details_of_appointment"=>urlencode("我们将通过电子邮件通知您，并提供预约详情"),
"please_enter_firstname"=>urlencode("请输入名字"),
"please_enter_lastname"=>urlencode("请输入姓氏"),
"remove_applied_coupon"=>urlencode("删除应用的优惠券"),
"eg_799_e_dragram_suite_5a"=>urlencode("例如。 799 E DRAGRAM SUITE 5A"),
"eg_14114"=>urlencode("例如。 14114"),
"eg_tucson"=>urlencode("例如。 TUCSON"),
"eg_az"=>urlencode("例如。 AZ"),
"warning"=>urlencode("警告"),
"try_later"=>urlencode("稍后再试"),
"choose_your"=>urlencode("选择你的"),
"configure_now_new"=>urlencode("立即配置"),
"january"=>urlencode("一月"),
"february"=>urlencode("二月"),
"march"=>urlencode("游行"),
"april"=>urlencode("四月"),
"may"=>urlencode("可能"),
"june"=>urlencode("六月"),
"july"=>urlencode("七月"),
"august"=>urlencode("八月"),
"september"=>urlencode("九月"),
"october"=>urlencode("十月"),
"november"=>urlencode("十一月"),
"december"=>urlencode("十二月"),
"jan"=>urlencode("JAN"),
"feb"=>urlencode("FEB"),
"mar"=>urlencode("MAR"),
"apr"=>urlencode("APR"),
"jun"=>urlencode("JUN"),
"jul"=>urlencode("JUL"),
"aug"=>urlencode("AUG"),
"sep"=>urlencode("SEP"),
"oct"=>urlencode("OCT"),
"nov"=>urlencode("NOV"),
"dec"=>urlencode("DEC"),
"pay_locally"=>urlencode("在当地支付"),
"please_select_provider"=>urlencode("请选择提供商"),
);

$admin_labels_zh_CN = array (
"payment_status"=>urlencode("支付状态"),
"staff_booking_status"=>urlencode("员工预订状态"),
"accept"=>urlencode("接受"),
"accepted"=>urlencode("公认"),
"decline"=>urlencode("下降"),
"paid"=>urlencode("付费"),
"eway"=>urlencode("易纬"),
"half_section"=>urlencode("半节"),
"option_title"=>urlencode("选项标题"),
"merchant_ID"=>urlencode("商家ID"),
"How_it_works"=>urlencode("怎么运行的？"),
"Your_currency_should_be_AUD_to_enable_payway_payment_gateway"=>urlencode("您的货币应该是澳大利亚元以启用付款通道支付网关"),
"secure_key"=>urlencode("安全密钥"),
"payway"=>urlencode("Payway"),
"Your_Google_calendar_id_where_you_need_to_get_alerts_its_normaly_your_Gmail_ID"=>urlencode("您的Google日历ID，您需要获取提醒，其通常是您的Gmail ID。例如johndoe@example.com"),
"You_can_get_your_client_ID_from_your_Google_Calendar_Console"=>urlencode("您可以从Google日历控制台获取客户ID"),
"You_can_get_your_client_secret_from_your_Google_Calendar_Console"=>urlencode("您可以通过Google日历控制台获取客户机密"),
"its_your_Cleanto_booking_form_page_url"=>urlencode("它是您的Cleanto预订表单页面网址"),
"Its_your_Cleanto_Google_Settings_page_url"=>urlencode("它是您的Cleanto Google设置页面网址"),
"Add_Manual_booking"=>urlencode("添加手动预订"),
"Google_Calender_Settings"=>urlencode("Google日历设置"),
"Add_Appointments_To_Google_Calender"=>urlencode("将约会添加到Google日历"),
"Google_Calender_Id"=>urlencode("Google日历ID"),
"Google_Calender_Client_Id"=>urlencode("Google日历客户ID"),
"Google_Calender_Client_Secret"=>urlencode("谷歌日历客户端秘密"),
"Google_Calender_Frontend_URL"=>urlencode("Google日历前端网址"),
"Google_Calender_Admin_URL"=>urlencode("Google日历管理员网址"),
"Google_Calender_Configuration"=>urlencode("Google日历配置"),
"Two_Way_Sync"=>urlencode("双向同步"),
"Verify_Account"=>urlencode("验证账户"),
"Select_Calendar"=>urlencode("选择日历"),
"Disconnect"=>urlencode("断开"),
"Calendar_Fisrt_Day"=>urlencode("日历第一天"),
"Calendar_Default_View"=>urlencode("日历默认视图"),
"Show_company_title"=>urlencode("显示公司名称"),
"front_language_flags_list"=>urlencode("前语标志列表"),
"Google_Analytics_Code"=>urlencode("Google Analytics代码"),
"Page_Meta_Tag"=>urlencode("页面/元标记"),
"SEO_Settings"=>urlencode("SEO设置"),
"Meta_Description"=>urlencode("元描述"),
"SEO"=>urlencode("SEO"),
"og_tag_image"=>urlencode("og Tag Image"),
"og_tag_url"=>urlencode("og标记URL"),
"og_tag_type"=>urlencode("og标签类型"),
"og_tag_title"=>urlencode("og标题"),
"Quantity"=>urlencode("数量"),
"Send_Invoice"=>urlencode("发送发票"),
"Recurrence"=>urlencode("循环"),
"Recurrence_booking"=>urlencode("重复预订"),
"Reset_Color"=>urlencode("重置颜色"),
"Loader"=>urlencode("装载机"),
"CSS_Loader"=>urlencode("CSS Loader"),
"GIF_Loader"=>urlencode("GIF装载机"),
"Default_Loader"=>urlencode("默认加载器"),
"for_a"=>urlencode("为一个"),
"show_company_logo"=>urlencode("显示公司徽标"),
"on"=>urlencode("上"),
"user_zip_code"=>urlencode("邮政编码"),
"delete_this_method"=>urlencode("删除此方法？"),
"authorize_net"=>urlencode("Authorize.Net"),
"staff_details"=>urlencode("员工详情"),
"client_payments"=>urlencode("客户付款"),
"staff_payments"=>urlencode("员工付款"),
"staff_payments_details"=>urlencode("员工付款详情"),
"advance_paid"=>urlencode("提前支付"),
"change_calculation_policyy"=>urlencode("变更计算政策"),
"frontend_fonts"=>urlencode("前端字体"),
"favicon_image"=>urlencode("Favicon图像"),
"staff_email_template"=>urlencode("员工电邮模板"),
"staff_details_add_new_and_manage_staff_payments"=>urlencode("员工详细信息，添加新员工并管理员工付款"),
"add_staff"=>urlencode("加上员工"),
"staff_bookings_and_payments"=>urlencode("员工预订和付款"),
"staff_booking_details_and_payment"=>urlencode("员工预订详情和付款"),
"select_option_to_show_bookings"=>urlencode("选择显示预订的选项"),
"select_service"=>urlencode("选择服务"),
"staff_name"=>urlencode("员工姓名"),
"staff_payment"=>urlencode("员工付款"),
"add_payment_to_staff_account"=>urlencode("将付款添加到员工帐户"),
"amount_payable"=>urlencode("应付金额"),
"save_changes"=>urlencode("保存更改"),
"front_error_labels"=>urlencode("前错误标签"),
"stripe"=>urlencode("条纹"),
"checkout_title"=>urlencode("的2Checkout"),
"nexmo_sms_gateway"=>urlencode("Nexmo SMS Gateway"),
"nexmo_sms_setting"=>urlencode("Nexmo短信设置"),
"nexmo_api_key"=>urlencode("Nexmo API密钥"),
"nexmo_api_secret"=>urlencode("Nexmo API Secret"),
"nexmo_from"=>urlencode("Nexmo来自"),
"nexmo_status"=>urlencode("Nexmo状态"),
"nexmo_send_sms_to_client_status"=>urlencode("Nexmo发送短信到客户端状态"),
"nexmo_send_sms_to_admin_status"=>urlencode("Nexmo发送短信到管理状态"),
"nexmo_admin_phone_number"=>urlencode("Nexmo管理员电话号码"),
"save_12_5"=>urlencode("节省12.5％"),
"front_tool_tips"=>urlencode("前工具提示"),
"front_tool_tips_lower"=>urlencode("前工具提示"),
"tool_tip_my_bookings"=>urlencode("我的预订"),
"tool_tip_postal_code"=>urlencode("邮政编码"),
"tool_tip_services"=>urlencode("服务"),
"tool_tip_extra_service"=>urlencode("额外服务"),
"tool_tip_frequently_discount"=>urlencode("经常打折"),
"tool_tip_when_would_you_like_us_to_come"=>urlencode("你什么时候想要我们来？"),
"tool_tip_your_personal_details"=>urlencode("你个人详细资料"),
"tool_tip_have_a_promocode"=>urlencode("有促销码吗"),
"tool_tip_preferred_payment_method"=>urlencode("首选付款方式"),
"login_page"=>urlencode("登录页面"),
"front_page"=>urlencode("首页"),
"before_e_g_100"=>urlencode("前（例如\$100）"),
"after_e_g_100"=>urlencode("后（e.g.100\$）"),
"tax_vat"=>urlencode("税/增值税"),
"wrong_url"=>urlencode("网址错误"),
"choose_file"=>urlencode("选择文件"),
"frontend_labels"=>urlencode("前端标签"),
"admin_labels"=>urlencode("管理标签"),
"dropdown_design"=>urlencode("DropDown设计"),
"blocks_as_button_design"=>urlencode("作为按钮设计的块"),
"qty_control_design"=>urlencode("数量控制设计"),
"dropdowns"=>urlencode("下拉菜单"),
"big_images_radio"=>urlencode("大图像广播"),
"errors"=>urlencode("错误"),
"extra_labels"=>urlencode("额外标签"),
"api_password"=>urlencode("API密码"),
"api_username"=>urlencode("API用户名"),
"appearance"=>urlencode("出现"),
"action"=>urlencode("行动"),
"actions"=>urlencode("操作"),
"add_break"=>urlencode("添加休息"),
"add_breaks"=>urlencode("添加休息时间"),
"add_cleaning_service"=>urlencode("添加清洁服务"),
"add_method"=>urlencode("添加方法"),
"add_new"=>urlencode("添新"),
"add_sample_data"=>urlencode("添加样本数据"),
"add_unit"=>urlencode("添加单位"),
"add_your_off_times"=>urlencode("添加你的休息时间"),
"add_new_off_time"=>urlencode("添加新的关闭时间"),
"add_ons"=>urlencode("附加组件"),
"addons_bookings"=>urlencode("AddOns预订"),
"addon_service_front_view"=>urlencode("插件服务前视图"),
"addons"=>urlencode("插件"),
"service_commission"=>urlencode("服务委员会"),
"commission_total"=>urlencode("佣金总额"),
"address"=>urlencode("地址"),
"new_appointment_assigned"=>urlencode("新任命已分配"),
"admin_email_notifications"=>urlencode("管理员电子邮件通知"),
"all_payment_gateways"=>urlencode("所有支付网关"),
"all_services"=>urlencode("所有服务"),
"allow_multiple_booking_for_same_timeslot"=>urlencode("允许同一时间段的多个预订"),
"amount"=>urlencode("量"),
"app_date"=>urlencode("应用。日期"),
"appearance_settings"=>urlencode("外观设置"),
"appointment_completed"=>urlencode("任命完成"),
"appointment_details"=>urlencode("预约详情"),
"appointment_marked_as_no_show"=>urlencode("任命标记为没有显示"),
"mark_as_no_show"=>urlencode("标记为没有显示"),
"appointment_reminder_buffer"=>urlencode("预约提醒缓冲区"),
"appointment_auto_confirm"=>urlencode("预约自动确认"),
"appointments"=>urlencode("约会"),
"admin_area_color_scheme"=>urlencode("管理区域配色方案"),
"thankyou_page_url"=>urlencode("谢谢页面URL"),
"addon_title"=>urlencode("插件标题"),
"availabilty"=>urlencode("可用性"),
"background_color"=>urlencode("背景颜色"),
"behaviour_on_click_of_button"=>urlencode("单击按钮时的行为"),
"book_now"=>urlencode("现在预订"),
"booking_date_and_time"=>urlencode("预订日期和时间"),
"booking_details"=>urlencode("预订详情"),
"booking_information"=>urlencode("预订信息"),
"booking_serve_date"=>urlencode("预订服务日期"),
"booking_status"=>urlencode("预订状态"),
"booking_notifications"=>urlencode("预订通知"),
"bookings"=>urlencode("预订"),
"button_position"=>urlencode("按钮位置"),
"button_text"=>urlencode("按钮文字"),
"company"=>urlencode("公司"),
"cannot_cancel_now"=>urlencode("现在无法取消"),
"cannot_reschedule_now"=>urlencode("现在不能重新安排"),
"cancel"=>urlencode("取消"),
"cancellation_buffer_time"=>urlencode("取消缓冲时间"),
"cancelled_by_client"=>urlencode("客户取消"),
"cancelled_by_service_provider"=>urlencode("由服务提供商取消"),
"change_password"=>urlencode("更改密码"),
"cleaning_service"=>urlencode("清洁服务"),
"client"=>urlencode("客户"),
"client_email_notifications"=>urlencode("客户电子邮件通知"),
"client_name"=>urlencode("client_name"),
"color_scheme"=>urlencode("配色方案"),
"color_tag"=>urlencode("颜色标记"),
"company_address"=>urlencode("地址"),
"company_email"=>urlencode("电子邮件"),
"company_logo"=>urlencode("公司标志"),
"company_name"=>urlencode("商家名称"),
"company_settings"=>urlencode("商家信息设置"),
"companyname"=>urlencode("公司名"),
"company_info_settings"=>urlencode("公司信息设置"),
"completed"=>urlencode("已完成"),
"confirm"=>urlencode("确认"),
"confirmed"=>urlencode("确认"),
"contact_status"=>urlencode("联系状态"),
"country"=>urlencode("国家"),
"country_code_phone"=>urlencode("国家代码（电话）"),
"coupon"=>urlencode("优惠券"),
"coupon_code"=>urlencode("优惠券代码"),
"coupon_limit"=>urlencode("优惠券限额"),
"coupon_type"=>urlencode("优惠券类型"),
"coupon_used"=>urlencode("使用的优惠券"),
"coupon_value"=>urlencode("优惠券价值"),
"create_addon_service"=>urlencode("创建插件服务"),
"crop_and_save"=>urlencode("裁剪和保存"),
"currency"=>urlencode("货币"),
"currency_symbol_position"=>urlencode("货币符号位置"),
"customer"=>urlencode("顾客"),
"customer_information"=>urlencode("客户信息"),
"customers"=>urlencode("顾客"),
"date_and_time"=>urlencode("约会时间"),
"date_picker_date_format"=>urlencode("日期选择器日期格式"),
"default_design_for_addons"=>urlencode("插件的默认设计"),
"default_design_for_methods_with_multiple_units"=>urlencode("具有多个单位的方法的默认设计"),
"default_design_for_services"=>urlencode("服务的默认设计"),
"default_setting"=>urlencode("默认设置"),
"delete"=>urlencode("删除"),
"description"=>urlencode("描述"),
"discount"=>urlencode("折扣"),
"download_invoice"=>urlencode("下载发票"),
"email_notification"=>urlencode("电子邮件通知"),
"email"=>urlencode("电子邮件"),
"email_settings"=>urlencode("电邮设定"),
"embed_code"=>urlencode("嵌入代码"),
"enter_your_email_and_we_will_send_you_instructions_on_resetting_your_password"=>urlencode("输入您的电子邮件，我们会向您发送有关重置密码的说明。"),
"expiry_date"=>urlencode("到期日"),
"export"=>urlencode("出口"),
"export_your_details"=>urlencode("导出详细信息"),
"frequently_discount_setting_tabs"=>urlencode("经常折扣"),
"frequently_discount_header"=>urlencode("经常折扣"),
"field_is_required"=>urlencode("现场是必需的"),
"file_size"=>urlencode("文件大小"),
"flat_fee"=>urlencode("固定费用"),
"flat"=>urlencode("平面"),
"freq_discount"=>urlencode("频率，折扣"),
"frequently_discount_label"=>urlencode("经常折扣标签"),
"frequently_discount_type"=>urlencode("经常折扣类型"),
"frequently_discount_value"=>urlencode("经常折扣价值"),
"front_service_box_view"=>urlencode("前台服务箱视图"),
"front_service_dropdown_view"=>urlencode("前台服务下拉视图"),
"front_view_options"=>urlencode("前视图选项"),
"full_name"=>urlencode("全名"),
"general"=>urlencode("一般"),
"general_settings"=>urlencode("常规设置"),
"get_embed_code_to_show_booking_widget_on_your_website"=>urlencode("获取嵌入代码以在您的网站上显示预订小部件"),
"get_the_embeded_code"=>urlencode("获取嵌入代码"),
"guest_customers"=>urlencode("来宾客户"),
"guest_user_checkout"=>urlencode("访客用户结帐"),
"hide_faded_already_booked_time_slots"=>urlencode("隐藏已经预订的已落入时间段"),
"hostname"=>urlencode("主机名"),
"labels"=>urlencode("标签"),
"legends"=>urlencode("传奇"),
"login"=>urlencode("登录"),
"maximum_advance_booking_time"=>urlencode("最长提前预订时间"),
"method"=>urlencode("方法"),
"method_name"=>urlencode("方法名称"),
"method_title"=>urlencode("方法标题"),
"method_unit_quantity"=>urlencode("方法单位数量"),
"method_unit_quantity_rate"=>urlencode("方法单位数量率"),
"method_unit_title"=>urlencode("方法单位标题"),
"method_units_front_view"=>urlencode("方法单位前视图"),
"methods"=>urlencode("方法"),
"methods_booking"=>urlencode("方法预订"),
"methods_bookings"=>urlencode("方法预订"),
"minimum_advance_booking_time"=>urlencode("最短提前预订时间"),
"more"=>urlencode("更多"),
"more_details"=>urlencode("更多细节"),
"my_appointments"=>urlencode("我的约会"),
"name"=>urlencode("名称"),
"net_total"=>urlencode("净总值"),
"new_password"=>urlencode("新密码"),
"notes"=>urlencode("笔记"),
"off_days"=>urlencode("休息日"),
"off_time"=>urlencode("关闭时间"),
"old_password"=>urlencode("旧密码"),
"online_booking_button_style"=>urlencode("在线预订按钮样式"),
"open_widget_in_a_new_page"=>urlencode("在新页面中打开小部件"),
"order"=>urlencode("订购"),
"order_date"=>urlencode("订购日期"),
"order_time"=>urlencode("订单时间"),
"payments_setting"=>urlencode("付款"),
"promocode"=>urlencode("促销代码"),
"promocode_header"=>urlencode("促销代码"),
"padding_time_before"=>urlencode("填充时间之前"),
"parking"=>urlencode("停車處"),
"partial_amount"=>urlencode("部分金额"),
"partial_deposit"=>urlencode("部分存款"),
"partial_deposit_amount"=>urlencode("部分存款金额"),
"partial_deposit_message"=>urlencode("部分存款消息"),
"password"=>urlencode("密码"),
"payment"=>urlencode("付款"),
"payment_date"=>urlencode("付款日期"),
"payment_gateways"=>urlencode("支付网关"),
"payment_method"=>urlencode("付款方法"),
"payments"=>urlencode("支付"),
"payments_history_details"=>urlencode("付款历史详细信息"),
"paypal_express_checkout"=>urlencode("Paypal快速结账"),
"paypal_guest_payment"=>urlencode("Paypal客人付款"),
"pending"=>urlencode("有待"),
"percentage"=>urlencode("百分比"),
"personal_information"=>urlencode("个人信息"),
"phone"=>urlencode("电话"),
"please_copy_above_code_and_paste_in_your_website"=>urlencode("请复制上面的代码并粘贴到您的网站。"),
"please_enable_payment_gateway"=>urlencode("请启用付款网关"),
"please_set_below_values"=>urlencode("请设置低于值"),
"port"=>urlencode("港口"),
"postal_codes"=>urlencode("邮政编码"),
"price"=>urlencode("价钱"),
"price_calculation_method"=>urlencode("价格计算方法"),
"price_format_decimal_places"=>urlencode("价格格式"),
"pricing"=>urlencode("价钱"),
"primary_color"=>urlencode("原色"),
"privacy_policy_link"=>urlencode("隐私政策链接"),
"profile"=>urlencode("轮廓"),
"promocodes"=>urlencode("促销代码"),
"promocodes_list"=>urlencode("Promocodes列表"),
"registered_customers"=>urlencode("注册客户"),
"registered_customers_bookings"=>urlencode("注册客户预订"),
"reject"=>urlencode("拒绝"),
"rejected"=>urlencode("拒绝"),
"remember_me"=>urlencode("记住我"),
"remove_sample_data"=>urlencode("删除示例数据"),
"reschedule"=>urlencode("改期"),
"reset"=>urlencode("重启"),
"reset_password"=>urlencode("重设密码"),
"reshedule_buffer_time"=>urlencode("重新缓冲时间"),
"retype_new_password"=>urlencode("重新输入新密码"),
"right_side_description"=>urlencode("预订页面右侧描述"),
"save"=>urlencode("保存"),
"save_availability"=>urlencode("保存可用性"),
"save_setting"=>urlencode("保存设置"),
"save_labels_setting"=>urlencode("保存标签设置"),
"schedule"=>urlencode("时间表"),
"schedule_type"=>urlencode("时间表类型"),
"secondary_color"=>urlencode("二次色"),
"select_language_for_update"=>urlencode("选择语言进行更新"),
"select_language_to_change_label"=>urlencode("选择语言以更改标签"),
"select_language_to_display"=>urlencode("语言"),
"display_sub_headers_below_headers"=>urlencode("预订页面上的子标题"),
"select_payment_option_export_details"=>urlencode("选择付款选项导出详细信息"),
"send_mail"=>urlencode("发送邮件"),
"sender_email_address_cleanto_admin_email"=>urlencode("发件人电子邮件"),
"sender_name"=>urlencode("发件人名称"),
"service"=>urlencode("服务"),
"service_add_ons_front_block_view"=>urlencode("服务附加组件前块视图"),
"service_add_ons_front_increase_decrease_view"=>urlencode("服务附加组件前面增加/减少视图"),
"service_description"=>urlencode("服务说明"),
"service_front_view"=>urlencode("服务前视图"),
"service_image"=>urlencode("服务形象"),
"service_methods"=>urlencode("服务方法"),
"service_padding_time_after"=>urlencode("服务填充时间之后"),
"padding_time_after"=>urlencode("填充后的时间"),
"service_padding_time_before"=>urlencode("服务填充时间之前"),
"service_quantity"=>urlencode("服务数量"),
"service_rate"=>urlencode("服务率"),
"service_title"=>urlencode("服务标题"),
"serviceaddons_name"=>urlencode("ServiceAddOns名称"),
"services"=>urlencode("服务"),
"services_information"=>urlencode("服务信息"),
"set_email_reminder_buffer"=>urlencode("设置电子邮件提醒缓冲区"),
"set_language"=>urlencode("设置语言"),
"settings"=>urlencode("设置"),
"show_all_bookings"=>urlencode("显示所有预订"),
"show_button_on_given_embeded_position"=>urlencode("在给定的嵌入位置显示按钮"),
"show_coupons_input_on_checkout"=>urlencode("在结账时显示优惠券输入"),
"show_on_a_button_click"=>urlencode("单击按钮显示"),
"show_on_page_load"=>urlencode("在页面加载时显示"),
"signature"=>urlencode("签名"),
"sorry_wrong_email_or_password"=>urlencode("对不起错误的电子邮件或密码"),
"start_date"=>urlencode("开始日期"),
"status"=>urlencode("状态"),
"submit"=>urlencode("提交"),
"staff_email_notification"=>urlencode("员工电子邮件通知"),
"tax"=>urlencode("税"),
"test_mode"=>urlencode("测试模式"),
"text_color"=>urlencode("文字颜色"),
"text_color_on_bg"=>urlencode("bg上的文字颜色"),
"terms_and_condition_link"=>urlencode("条款和条件链接"),
"this_week_breaks"=>urlencode("本周休息"),
"this_week_time_scheduling"=>urlencode("本周时间安排"),
"time_format"=>urlencode("时间格式"),
"time_interval"=>urlencode("时间间隔"),
"timezone"=>urlencode("时区"),
"units"=>urlencode("单位"),
"unit_name"=>urlencode("单位名称"),
"units_of_methods"=>urlencode("方法单位"),
"update"=>urlencode("更新"),
"update_appointment"=>urlencode("更新约会"),
"update_promocode"=>urlencode("更新Promocode"),
"username"=>urlencode("用户名"),
"vaccum_cleaner"=>urlencode("吸尘器"),
"view_slots_by"=>urlencode("查看插槽？"),
"week"=>urlencode("周"),
"week_breaks"=>urlencode("周休息"),
"week_time_scheduling"=>urlencode("周时间安排"),
"widget_loading_style"=>urlencode("小部件加载样式"),
"zip"=>urlencode("压缩"),
"logout"=>urlencode("登出"),
"to"=>urlencode("至"),
"add_new_promocode"=>urlencode("添加新的Promocode"),
"create"=>urlencode("创建"),
"end_date"=>urlencode("结束日期"),
"end_time"=>urlencode("时间结束"),
"labels_settings"=>urlencode("标签设置"),
"limit"=>urlencode("限制"),
"max_limit"=>urlencode("最大限额"),
"start_time"=>urlencode("开始时间"),
"value"=>urlencode("值"),
"active"=>urlencode("活性"),
"appointment_reject_reason"=>urlencode("任命拒绝原因"),
"search"=>urlencode("搜索"),
"custom_thankyou_page_url"=>urlencode("自定义Thankyou页面网址"),
"price_per_unit"=>urlencode("每单位价格"),
"confirm_appointment"=>urlencode("确认预约"),
"reject_reason"=>urlencode("拒绝原因"),
"delete_this_appointment"=>urlencode("删除此约会"),
"close_notifications"=>urlencode("关闭通知"),
"booking_cancel_reason"=>urlencode("预订取消原因"),
"service_color_badge"=>urlencode("服务颜色徽章"),
"manage_price_calculation_methods"=>urlencode("管理价格计算方法"),
"manage_addons_of_this_service"=>urlencode("管理此服务的插件"),
"service_is_booked"=>urlencode("服务已预订"),
"delete_this_service"=>urlencode("删除此服务"),
"delete_service"=>urlencode("删除服务"),
"remove_image"=>urlencode("删除图片"),
"remove_service_image"=>urlencode("删除服务图像"),
"company_name_is_used_for_invoice_purpose"=>urlencode("公司名称用于发票目的"),
"remove_company_logo"=>urlencode("删除公司徽标"),
"time_interval_is_helpful_to_show_time_difference_between_availability_time_slots"=>urlencode("时间间隔有助于显示可用性时隙之间的时间差"),
"minimum_advance_booking_time_restrict_client_to_book_last_minute_booking_so_that_you_should_have_sufficient_time_before_appointment"=>urlencode("最短提前预订时间限制客户预订最后一分钟预订，以便您在预约前有足够的时间"),
"cancellation_buffer_helps_service_providers_to_avoid_last_minute_cancellation_by_their_clients"=>urlencode("取消缓冲区可帮助服务提供商避免客户最后一刻取消"),
"partial_payment_option_will_help_you_to_charge_partial_payment_of_total_amount_from_client_and_remaining_you_can_collect_locally"=>urlencode("部分付款选项将帮助您从客户收取部分总金额，您可以在当地收取剩余金额"),
"allow_multiple_appointment_booking_at_same_time_slot_will_allow_you_to_show_availability_time_slot_even_you_have_booking_already_for_that_time"=>urlencode("允许在同一时间段预订多个预约，即使您已预订了该时间，也可以显示可用时间段"),
"with_Enable_of_this_feature_Appointment_request_from_clients_will_be_auto_confirmed"=>urlencode("启用此功能后，将自动确认来自客户的约会请求"),
"write_html_code_for_the_right_side_panel"=>urlencode("编写右侧面板的HTML代码"),
"do_you_want_to_show_subheaders_below_the_headers"=>urlencode("是否要在标题下方显示子标题"),
"you_can_show_hide_coupon_input_on_checkout_form"=>urlencode("您可以在结帐表单上显示/隐藏优惠券输入"),
"with_this_feature_you_can_allow_a_visitor_to_book_appointment_without_registration"=>urlencode("使用此功能，您可以允许访客预约而无需注册"),
"paypal_api_username_can_get_easily_from_developer_paypal_com_account"=>urlencode("Paypal API用户名可以从developer.paypal.com帐户轻松获得"),
"paypal_api_password_can_get_easily_from_developer_paypal_com_account"=>urlencode("Paypal API密码可以从developer.paypal.com帐户轻松获得"),
"paypal_api_signature_can_get_easily_from_developer_paypal_com_account"=>urlencode("Paypal API签名可以从developer.paypal.com帐户轻松获得"),
"let_user_pay_through_credit_card_without_having_paypal_account"=>urlencode("让用户通过信用卡付款而无需Paypal帐户"),
"you_can_enable_paypal_test_mode_for_sandbox_account_testing"=>urlencode("您可以为沙盒帐户测试启用Paypal测试模式"),
"you_can_enable_authorize_net_test_mode_for_sandbox_account_testing"=>urlencode("您可以为沙盒帐户测试启用Authorize.Net测试模式"),
"edit_coupon_code"=>urlencode("修改优惠券代码"),
"delete_promocode"=>urlencode("删除Promocode？"),
"coupon_code_will_work_for_such_limit"=>urlencode("优惠券代码适用于此类限制"),
"coupon_code_will_work_for_such_date"=>urlencode("优惠券代码适用于此日期"),
"coupon_value_would_be_consider_as_percentage_in_percentage_mode_and_in_flat_mode_it_will_be_consider_as_amount_no_need_to_add_percentage_sign_it_will_auto_added"=>urlencode("优惠券价值将被视为百分比模式的百分比，在平面模式下，它将被视为金额。无需添加百分比符号，它将自动添加。"),
"unit_is_booked"=>urlencode("单位已预订"),
"delete_this_service_unit"=>urlencode("删除此服务单位？"),
"delete_service_unit"=>urlencode("删除服务单位"),
"manage_unit_price"=>urlencode("管理单价"),
"extra_service_title"=>urlencode("额外服务标题"),
"addon_is_booked"=>urlencode("Addon已预订"),
"delete_this_addon_service"=>urlencode("删除此插件服务？"),
"choose_your_addon_image"=>urlencode("选择你的插件图片"),
"addon_image"=>urlencode("插件图片"),
"administrator_email"=>urlencode("管理员电邮"),
"admin_profile_address"=>urlencode("地址"),
"default_country_code"=>urlencode("国家代码"),
"cancellation_policy"=>urlencode("取消政策"),
"transaction_id"=>urlencode("交易ID"),
"sms_reminder"=>urlencode("短信提醒"),
"save_sms_settings"=>urlencode("保存短信设置"),
"sms_service"=>urlencode("短信服务"),
"it_will_send_sms_to_service_provider_and_client_for_appointment_booking"=>urlencode("它会向服务提供商和客户发送短信以进行预约"),
"twilio_account_settings"=>urlencode("Twilio帐户设置"),
"plivo_account_settings"=>urlencode("Plivo帐户设置"),
"account_sid"=>urlencode("帐户SID"),
"auth_token"=>urlencode("验证令牌"),
"twilio_sender_number"=>urlencode("Twilio发件人号码"),
"plivo_sender_number"=>urlencode("Plivo发件人编号"),
"twilio_sms_settings"=>urlencode("Twilio短信设置"),
"plivo_sms_settings"=>urlencode("Plivo短信设置"),
"twilio_sms_gateway"=>urlencode("Twilio短信网关"),
"plivo_sms_gateway"=>urlencode("Plivo短信网关"),
"send_sms_to_client"=>urlencode("发送短信给客户"),
"send_sms_to_admin"=>urlencode("发送短信给管理员"),
"admin_phone_number"=>urlencode("管理员电话号码"),
"available_from_within_your_twilio_account"=>urlencode("可从Twilio帐户中获取。"),
"must_be_a_valid_number_associated_with_your_twilio_account"=>urlencode("必须是与您的Twilio帐户关联的有效号码。"),
"enable_or_disable_send_sms_to_client_for_appointment_booking_info"=>urlencode("启用或禁用，向客户发送短信以获取预约信息。"),
"enable_or_disable_send_sms_to_admin_for_appointment_booking_info"=>urlencode("启用或禁用，向管理员发送短信以获取预约信息。"),
"updated_sms_settings"=>urlencode("更新了短信设置"),
"parking_availability_frontend_option_display_status"=>urlencode("停車處"),
"vaccum_cleaner_frontend_option_display_status"=>urlencode("吸尘器"),
"o_n"=>urlencode("上"),
"off"=>urlencode("离"),
"enable"=>urlencode("启用"),
"disable"=>urlencode("禁用"),
"monthly"=>urlencode("每月一次"),
"weekly"=>urlencode("每周"),
"email_template"=>urlencode("电子邮件模板"),
"sms_notification"=>urlencode("短信通知"),
"sms_template"=>urlencode("短信模板"),
"email_template_settings"=>urlencode("电子邮件模板设置"),
"client_email_templates"=>urlencode("客户电邮模板"),
"client_sms_templates"=>urlencode("客户端短信模板"),
"admin_email_template"=>urlencode("管理电子邮件模板"),
"admin_sms_template"=>urlencode("管理员短信模板"),
"tags"=>urlencode("标签"),
"booking_date"=>urlencode("预定日期"),
"service_name"=>urlencode("服务名称"),
"business_logo"=>urlencode("business_logo"),
"business_logo_alt"=>urlencode("business_logo_alt"),
"admin_name"=>urlencode("ADMIN_NAME"),
"methodname"=>urlencode("METHOD_NAME"),
"firstname"=>urlencode("名字"),
"lastname"=>urlencode("姓"),
"client_email"=>urlencode("client_email"),
"vaccum_cleaner_status"=>urlencode("vaccum_cleaner_status"),
"parking_status"=>urlencode("parking_status"),
"app_remain_time"=>urlencode("app_remain_time"),
"reject_status"=>urlencode("reject_status"),
"save_template"=>urlencode("保存模板"),
"default_template"=>urlencode("默认模板"),
"sms_template_settings"=>urlencode("短信模板设置"),
"secret_key"=>urlencode("密钥"),
"publishable_key"=>urlencode("可发布的密钥"),
"payment_form"=>urlencode("付款表格"),
"api_login_id"=>urlencode("API登录ID"),
"transaction_key"=>urlencode("交易密钥"),
"sandbox_mode"=>urlencode("沙盒模式"),
"available_from_within_your_plivo_account"=>urlencode("可从您的Plivo帐户中获取。"),
"must_be_a_valid_number_associated_with_your_plivo_account"=>urlencode("必须是与您的Plivo帐户关联的有效号码。"),
"whats_new"=>urlencode("什么是新的？"),
"company_phone"=>urlencode("电话"),
"company__name"=>urlencode("公司名"),
"booking_time"=>urlencode("booking_time"),
"company__email"=>urlencode("company_email"),
"company__address"=>urlencode("公司地址"),
"company__zip"=>urlencode("company_zip"),
"company__phone"=>urlencode("company_phone"),
"company__state"=>urlencode("company_state"),
"company__country"=>urlencode("company_country"),
"company__city"=>urlencode("company_city"),
"page_title"=>urlencode("页面标题"),
"client__zip"=>urlencode("client_zip"),
"client__state"=>urlencode("client_state"),
"client__city"=>urlencode("client_city"),
"client__address"=>urlencode("client_address"),
"client__phone"=>urlencode("client_phone"),
"company_logo_is_used_for_invoice_purpose"=>urlencode("公司徽标用于电子邮件和预订页面"),
"private_key"=>urlencode("私钥"),
"seller_id"=>urlencode("卖家ID"),
"postal_codes_ed"=>urlencode("您可以根据您所在国家/地区的要求启用或禁用邮政编码或邮政编码功能，因为某些国家/地区（如阿联酋）没有邮政编码。"),
"postal_codes_info"=>urlencode("您可以通过两种方式提及邮政编码：＃1。您可以提及匹配的完整邮政编码，如K1A232，L2A334，C3A4C4。＃2。您可以将部分邮政编码用于通配符匹配条目，例如。 K1A，L2A，C3，系统将匹配前面的邮政编码的起始字母，它将避免你写这么多的邮政编码。"),
"first"=>urlencode("第一"),
"second"=>urlencode("第二"),
"third"=>urlencode("第三"),
"fourth"=>urlencode("第四"),
"fifth"=>urlencode("第五"),
"first_week"=>urlencode("第一周"),
"second_week"=>urlencode("第二周"),
"third_week"=>urlencode("第三周"),
"fourth_week"=>urlencode("第四周"),
"fifth_week"=>urlencode("第五周"),
"this_week"=>urlencode("本星期"),
"monday"=>urlencode("星期一"),
"tuesday"=>urlencode("星期二"),
"wednesday"=>urlencode("星期三"),
"thursday"=>urlencode("星期四"),
"friday"=>urlencode("星期五"),
"saturday"=>urlencode("星期六"),
"sunday"=>urlencode("星期日"),
"appointment_request"=>urlencode("预约请求"),
"appointment_approved"=>urlencode("任命批准"),
"appointment_rejected"=>urlencode("任命被拒绝"),
"appointment_cancelled_by_you"=>urlencode("预约由您取消"),
"appointment_rescheduled_by_you"=>urlencode("预约由您重新安排"),
"client_appointment_reminder"=>urlencode("客户预约提醒"),
"new_appointment_request_requires_approval"=>urlencode("新的预约请求需要批准"),
"appointment_cancelled_by_customer"=>urlencode("客户取消预约"),
"appointment_rescheduled_by_customer"=>urlencode("预约由客户重新安排"),
"admin_appointment_reminder"=>urlencode("管理员预约提醒"),
"off_days_added_successfully"=>urlencode("关闭天数成功添加"),
"off_days_deleted_successfully"=>urlencode("关闭天数已成功删除"),
"sorry_not_available"=>urlencode("抱歉不可用"),
"success"=>urlencode("成功"),
"failed"=>urlencode("失败"),
"once"=>urlencode("一旦"),
"Bi_Monthly"=>urlencode("双月"),
"Fortnightly"=>urlencode("半月刊"),
"Recurrence_Type"=>urlencode("复发类型"),
"bi_weekly"=>urlencode("双周"),
"Daily"=>urlencode("日常"),
"guest_customers_bookings"=>urlencode("客户预订"),
"existing_and_new_user_checkout"=>urlencode("现有和新用户结帐"),
"it_will_allow_option_for_user_to_get_booking_with_new_user_or_existing_user"=>urlencode("它将允许用户选择与新用户或现有用户进行预订"),
"0_1"=>urlencode("01"),
"1_1"=>urlencode("1.1"),
"1_2"=>urlencode("1.2"),
"0_2"=>urlencode("02"),
"free"=>urlencode("自由"),
"show_company_address_in_header"=>urlencode("在标题中显示公司地址"),
"calendar_week"=>urlencode("周"),
"calendar_month"=>urlencode("月"),
"calendar_day"=>urlencode("天"),
"calendar_today"=>urlencode("今天"),
"restore_default"=>urlencode("恢复默认"),
"scrollable_cart"=>urlencode("可滚动的购物车"),
"merchant_key"=>urlencode("商家钥匙"),
"salt_key"=>urlencode("盐键"),
"textlocal_sms_gateway"=>urlencode("Textlocal短信网关"),
"textlocal_sms_settings"=>urlencode("Textlocal短信设置"),
"textlocal_account_settings"=>urlencode("Textlocal帐户设置"),
"account_username"=>urlencode("帐户用户名"),
"account_hash_id"=>urlencode("帐户哈希ID"),
"email_id_registered_with_you_textlocal"=>urlencode("提供使用textlocal注册的电子邮件"),
"hash_id_provided_by_textlocal"=>urlencode("由textlocal提供的哈希id"),
"bank_transfer"=>urlencode("银行转帐"),
"bank_name"=>urlencode("银行名"),
"account_name"=>urlencode("用户名"),
"account_number"=>urlencode("帐号"),
"branch_code"=>urlencode("分行代码"),
"ifsc_code"=>urlencode("IFSC准则"),
"bank_description"=>urlencode("银行描述"),
"your_cart_items"=>urlencode("你的购物车商品"),
"show_how_will_we_get_in"=>urlencode("显示我们将如何进入"),
"show_description"=>urlencode("显示说明"),
"bank_details"=>urlencode("银行明细"),
"ok_remove_sample_data"=>urlencode("好"),
"book_appointment"=>urlencode("预约"),
"remove_sample_data_message"=>urlencode("您正在尝试删除示例数据。如果您删除样本数据，将永久删除与样本服务相关的预订。要继续，请点击“确定”"),
"recommended_image_type_jpg_jpeg_png_gif"=>urlencode("（推荐图片类型jpg，jpeg，png，gif）"),
"authetication"=>urlencode("认证"),
"encryption_type"=>urlencode("加密类型"),
"plain"=>urlencode("川"),
"true"=>urlencode("真正"),
"false"=>urlencode("假"),
"change_calculation_policy"=>urlencode("变更计算"),
"multiply"=>urlencode("乘"),
"equal"=>urlencode("等于"),
"warning"=>urlencode("警告！"),
"field_name"=>urlencode("字段名称"),
"enable_disable"=>urlencode("启用/禁用"),
"required"=>urlencode("需要"),
"min_length"=>urlencode("最小长度"),
"max_length"=>urlencode("最长长度"),
"appointment_details_section"=>urlencode("预约详情部分"),
"if_you_are_having_booking_system_which_need_the_booking_address_then_please_make_this_field_enable_or_else_it_will_not_able_to_take_the_booking_address_and_display_blank_address_in_the_booking"=>urlencode("如果您的预订系统需要预订地址，请启用此栏位，否则将无法取得预订地址并在预订时显示空白地址"),
"front_language_dropdown"=>urlencode("前语下拉菜单"),
"enabled"=>urlencode("启用"),
"vaccume_cleaner"=>urlencode("吸尘器"),
"staff_members"=>urlencode("工作人员"),
"add_new_staff_member"=>urlencode("添加新员工"),
"role"=>urlencode("角色"),
"staff"=>urlencode("员工"),
"admin"=>urlencode("管理员"),
"service_details"=>urlencode("服务细节"),
"technical_admin"=>urlencode("技术管理员"),
"enable_booking"=>urlencode("启用预订"),
"flat_commission"=>urlencode("扁平委员会"),
"manageable_form_fields_front_booking_form"=>urlencode("前台预订表格的可管理表格字段"),
"manageable_form_fields"=>urlencode("可管理的表单字段"),
"sms"=>urlencode("短信"),
"crm"=>urlencode("CRM"),
"message"=>urlencode("信息"),
"send_message"=>urlencode("发信息"),
"all_messages"=>urlencode("所有消息"),
"subject"=>urlencode("学科"),
"add_attachment"=>urlencode("添加附件"),
"send"=>urlencode("发送"),
"close"=>urlencode("关"),
"delete_this_customer?"=>urlencode("删除此客户？"),
"yes"=>urlencode("是"),
"add_new_customer"=>urlencode("添加新客户"),
"attachment"=>urlencode("附件"),
"date"=>urlencode("日期"),
"see_attachment"=>urlencode("见附件"),
"no_attachment"=>urlencode("没有附件"),
"ct_special_offer"=>urlencode("特价"),
"ct_special_offer_text"=>urlencode("特价文字"),
);

$error_labels_zh_CN = array (
"language_status_change_successfully"=>urlencode("语言状态变化成功"),
"commission_amount_should_not_be_greater_then_order_amount"=>urlencode("佣金金额不应大于订单金额"),
"please_enter_merchant_ID"=>urlencode("请输入商家ID"),
"please_enter_secure_key"=>urlencode("请输入安全密钥"),
"please_enter_google_calender_admin_url"=>urlencode("请输入google calender admin url"),
"please_enter_google_calender_frontend_url"=>urlencode("请输入google calender frontend url"),
"please_enter_google_calender_client_secret"=>urlencode("请输入谷歌日历客户端密码"),
"please_enter_google_calender_client_ID"=>urlencode("请输入谷歌日历客户端ID"),
"please_enter_google_calender_ID"=>urlencode("请输入谷歌日历ID"),
"you_cannot_book_on_past_date"=>urlencode("您无法预订过去的日期"),
"Invalid_Image_Type"=>urlencode("图像类型无效"),
"seo_settings_updated_successfully"=>urlencode("SEO设置已成功更新"),
"customer_deleted_successfully"=>urlencode("客户已成功删除"),
"please_enter_below_36_characters"=>urlencode("请输入以下36个字符"),
"are_you_sure_you_want_to_delete_client"=>urlencode("您确定要删除客户端吗？"),
"please_select_atleast_one_unit"=>urlencode("请选择至少一个单位"),
"atleast_one_payment_method_should_be_enable"=>urlencode("应启用至少一种付款方式"),
"appointment_booking_confirm"=>urlencode("预约确认"),
"appointment_booking_rejected"=>urlencode("预约被拒绝"),
"booking_cancel"=>urlencode("预订已取消"),
"appointment_marked_as_no_show"=>urlencode("约会被标记为没有显示"),
"appointment_reschedules_successfully"=>urlencode("预约成功重新安排"),
"booking_deleted"=>urlencode("预订已删除"),
"break_end_time_should_be_greater_than_start_time"=>urlencode("中断结束时间应大于开始时间"),
"cancel_by_client"=>urlencode("由客户取消"),
"cancelled_by_service_provider"=>urlencode("由服务提供商取消"),
"design_set_successfully"=>urlencode("设计成功"),
"end_break_time_updated"=>urlencode("结束休息时间更新"),
"enter_alphabets_only"=>urlencode("仅输入字母"),
"enter_only_alphabets"=>urlencode("只输入字母"),
"enter_only_alphabets_numbers"=>urlencode("只输入字母/数字"),
"enter_only_digits"=>urlencode("仅输入数字"),
"enter_valid_url"=>urlencode("输入有效的网址"),
"enter_only_numeric"=>urlencode("只输入数字"),
"enter_proper_country_code"=>urlencode("输入正确的国家代码"),
"frequently_discount_status_updated"=>urlencode("经常更新折扣状态"),
"frequently_discount_updated"=>urlencode("经常折扣更新"),
"manage_addons_service"=>urlencode("管理附加服务"),
"maximum_file_upload_size_2_mb"=>urlencode("最大文件上载大小2 MB"),
"method_deleted_successfully"=>urlencode("方法已成功删除"),
"method_inserted_successfully"=>urlencode("方法已成功插入"),
"minimum_file_upload_size_1_kb"=>urlencode("最小文件上载大小1 KB"),
"off_time_added_successfully"=>urlencode("关闭时间成功添加"),
"only_jpeg_png_and_gif_images_allowed"=>urlencode("只有jpeg，png和gif图像允许"),
"only_jpeg_png_gif_zip_and_pdf_allowed"=>urlencode("只有jpeg，png，gif，zip和pdf允许"),
"please_wait_while_we_send_all_your_message"=>urlencode("我们发送所有邮件时请等待"),
"please_enable_email_to_client"=>urlencode("请启用电子邮件到客户端。"),
"please_enable_sms_gateway"=>urlencode("请启用SMS网关。"),
"please_enable_client_notification"=>urlencode("请启用客户端通知。"),
"password_must_be_8_character_long"=>urlencode("密码长度必须为8个字符"),
"password_should_not_exist_more_then_20_characters"=>urlencode("密码不应该超过20个字符"),
"please_assign_base_price_for_unit"=>urlencode("请指定单位的基本价格"),
"please_assign_price"=>urlencode("请指定价格"),
"please_assign_qty"=>urlencode("请指定数量"),
"please_enter_api_password"=>urlencode("请输入API密码"),
"please_enter_api_username"=>urlencode("请输入API用户名"),
"please_enter_color_code"=>urlencode("请输入颜色代码"),
"please_enter_country"=>urlencode("请输入国家"),
"please_enter_coupon_limit"=>urlencode("请输入优惠券限额"),
"please_enter_coupon_value"=>urlencode("请输入优惠券价值"),
"please_enter_coupon_code"=>urlencode("请输入优惠券代码"),
"please_enter_email"=>urlencode("请输入电子邮件"),
"please_enter_fullname"=>urlencode("请输入全名"),
"please_enter_maxlimit"=>urlencode("请输入maxLimit"),
"please_enter_method_title"=>urlencode("请输入方法标题"),
"please_enter_name"=>urlencode("请输入姓名"),
"please_enter_only_numeric"=>urlencode("请只输入数字"),
"please_enter_proper_base_price"=>urlencode("请输入正确的基价"),
"please_enter_proper_name"=>urlencode("请输入正确的名称"),
"please_enter_proper_title"=>urlencode("请输入正确的标题"),
"please_enter_publishable_key"=>urlencode("请输入Publishable Key"),
"please_enter_secret_key"=>urlencode("请输入密钥"),
"please_enter_service_title"=>urlencode("请输入服务标题"),
"please_enter_signature"=>urlencode("请输入签名"),
"please_enter_some_qty"=>urlencode("请输入一些数量"),
"please_enter_title"=>urlencode("请输入标题"),
"please_enter_unit_title"=>urlencode("请输入单位标题"),
"please_enter_valid_country_code"=>urlencode("请输入有效的国家代码"),
"please_enter_valid_service_title"=>urlencode("请输入有效的服务标题"),
"please_enter_valid_price"=>urlencode("请输入有效价格"),
"please_enter_zipcode"=>urlencode("请输入邮政编码"),
"please_enter_state"=>urlencode("请输入州"),
"please_retype_correct_password"=>urlencode("请重新输入正确的密码"),
"please_select_porper_time_slots"=>urlencode("请选择porper时段"),
"please_select_time_between_day_availability_time"=>urlencode("请选择日间可用时间"),
"please_valid_value_for_discount"=>urlencode("请提供有效的折扣价"),
"please_enter_confirm_password"=>urlencode("请输入确认密码"),
"please_enter_new_password"=>urlencode("请输入新密码"),
"please_enter_old_password"=>urlencode("请输入旧密码"),
"please_enter_valid_number"=>urlencode("请输入有效号码"),
"please_enter_valid_number_with_country_code"=>urlencode("请输入带国家代码的有效号码"),
"please_select_end_time_greater_than_start_time"=>urlencode("请选择大于开始时间的结束时间"),
"please_select_end_time_less_than_start_time"=>urlencode("请选择小于开始时间的结束时间"),
"please_select_a_crop_region_and_then_press_upload"=>urlencode("请选择裁剪区域，然后按上传"),
"please_select_a_valid_image_file_jpg_and_png_are_allowed"=>urlencode("请选择一个有效的图像文件jpg和png是允许的"),
"profile_updated_successfully"=>urlencode("资料更新成功"),
"qty_rule_deleted"=>urlencode("数量规则已删除"),
"record_deleted_successfully"=>urlencode("记录删除成功"),
"record_updated_successfully"=>urlencode("记录更新成功"),
"rescheduled"=>urlencode("改期"),
"schedule_updated_to_monthly"=>urlencode("计划更新为每月"),
"schedule_updated_to_weekly"=>urlencode("计划更新为每周"),
"sorry_method_already_exist"=>urlencode("抱歉方法已存在"),
"sorry_no_notification"=>urlencode("对不起，您还没有即将到来的预约"),
"sorry_promocode_already_exist"=>urlencode("对不起promocode已经存在"),
"sorry_unit_already_exist"=>urlencode("抱歉单位已经存在"),
"sorry_we_are_not_available"=>urlencode("抱歉，我们无法使用"),
"start_break_time_updated"=>urlencode("开始休息时间更新"),
"status_updated"=>urlencode("状态已更新"),
"time_slots_updated_successfully"=>urlencode("时隙已成功更新"),
"unit_inserted_successfully"=>urlencode("单位插入成功"),
"units_status_updated"=>urlencode("单位状态已更新"),
"updated_appearance_settings"=>urlencode("更新了外观设置"),
"updated_company_details"=>urlencode("更新公司详情"),
"updated_email_settings"=>urlencode("更新了电子邮件设置"),
"updated_general_settings"=>urlencode("更新了常规设置"),
"updated_payments_settings"=>urlencode("更新的付款设置"),
"your_old_password_incorrect"=>urlencode("旧密码不正确"),
"please_enter_minimum_5_chars"=>urlencode("请输入最少5个字符"),
"please_enter_maximum_10_chars"=>urlencode("请输入最多10个字符"),
"please_enter_postal_code"=>urlencode("请输入邮政编码"),
"please_select_a_service"=>urlencode("请选择一项服务"),
"please_select_units_and_addons"=>urlencode("请选择单位和插件"),
"please_select_units_or_addons"=>urlencode("请选择单位或插件"),
"please_login_to_complete_booking"=>urlencode("请登录以完成预订"),
"please_select_appointment_date"=>urlencode("请选择预约日期"),
"please_accept_terms_and_conditions"=>urlencode("请接受条款和条件"),
"incorrect_email_address_or_password"=>urlencode("电子邮件地址或密码不正确"),
"please_enter_valid_email_address"=>urlencode("请输入有效的电子邮件地址"),
"please_enter_email_address"=>urlencode("请输入电子邮件地址"),
"please_enter_password"=>urlencode("请输入密码"),
"please_enter_minimum_8_characters"=>urlencode("请输入最少8个字符"),
"please_enter_maximum_15_characters"=>urlencode("请输入最多15个字符"),
"please_enter_first_name"=>urlencode("请输入名字"),
"please_enter_only_alphabets"=>urlencode("请只输入字母"),
"please_enter_minimum_2_characters"=>urlencode("请输入最少2个字符"),
"please_enter_last_name"=>urlencode("请输入姓氏"),
"email_already_exists"=>urlencode("电子邮件已经存在"),
"please_enter_phone_number"=>urlencode("请输入电话号码"),
"please_enter_only_numerics"=>urlencode("请输入数字"),
"please_enter_minimum_10_digits"=>urlencode("请输入最少10位数字"),
"please_enter_maximum_14_digits"=>urlencode("请输入最多14位数字"),
"please_enter_address"=>urlencode("请输入地址"),
"please_enter_minimum_20_characters"=>urlencode("请输入最少20个字符"),
"please_enter_zip_code"=>urlencode("请输入邮政编码"),
"please_enter_proper_zip_code"=>urlencode("请输入正确的邮政编码"),
"please_enter_minimum_5_digits"=>urlencode("请输入最少5位数"),
"please_enter_maximum_7_digits"=>urlencode("请输入最多7位数字"),
"please_enter_city"=>urlencode("请输入城市"),
"please_enter_proper_city"=>urlencode("请输入正确的城市"),
"please_enter_maximum_48_characters"=>urlencode("请输入最多48个字符"),
"please_enter_proper_state"=>urlencode("请输入正确的状态"),
"please_enter_contact_status"=>urlencode("请输入联系状态"),
"please_enter_maximum_100_characters"=>urlencode("请输入最多100个字符"),
"your_cart_is_empty_please_add_cleaning_services"=>urlencode("您的购物车是空的请添加清洁服务"),
"coupon_expired"=>urlencode("优惠券已过期"),
"invalid_coupon"=>urlencode("优惠券无效"),
"our_service_not_available_at_your_location"=>urlencode("我们的服务不在您所在地"),
"please_enter_proper_postal_code"=>urlencode("请输入正确的邮政编码"),
"invalid_email_id_please_register_first"=>urlencode("无效的电子邮件ID请先注册"),
"your_password_send_successfully_at_your_registered_email_id"=>urlencode("您的密码已通过注册的电子邮件ID成功发送"),
"your_password_reset_successfully_please_login"=>urlencode("您的密码重置成功请登录"),
"new_password_and_retype_new_password_mismatch"=>urlencode("新密码并重新键入新密码不匹配"),
"new"=>urlencode("新"),
"your_reset_password_link_expired"=>urlencode("您的重置密码链接已过期"),
"front_display_language_changed"=>urlencode("前显示语言改变了"),
"updated_front_display_language_and_update_labels"=>urlencode("更新了前显示语言和更新标签"),
"please_enter_only_7_chars_maximum"=>urlencode("请最多输入7个字符"),
"please_enter_maximum_20_chars"=>urlencode("请输入最多20个字符"),
"record_inserted_successfully"=>urlencode("记录已成功插入"),
"please_enter_account_sid"=>urlencode("请输入Accout SID"),
"please_enter_auth_token"=>urlencode("请输入验证令牌"),
"please_enter_sender_number"=>urlencode("请输入发件人编号"),
"please_enter_admin_number"=>urlencode("请输入管理员号码"),
"sorry_service_already_exist"=>urlencode("抱歉服务已经存在"),
"please_enter_api_login_id"=>urlencode("请输入API登录ID"),
"please_enter_transaction_key"=>urlencode("请输入交易密钥"),
"please_enter_sms_message"=>urlencode("请输入短信"),
"please_enter_email_message"=>urlencode("请输入电子邮件"),
"please_enter_private_key"=>urlencode("请输入私钥"),
"please_enter_seller_id"=>urlencode("请输入卖家ID"),
"please_enter_valid_value_for_discount"=>urlencode("请输入折扣的有效值"),
"password_must_be_only_10_characters"=>urlencode("密码必须只有10个字符"),
"password_at_least_have_8_characters"=>urlencode("密码至少有8个字符"),
"please_enter_retype_new_password"=>urlencode("请输入重新输入新密码"),
"your_password_send_successfully_at_your_email_id"=>urlencode("您的密码成功发送到您的电子邮件ID"),
"please_select_expiry_date"=>urlencode("请选择有效期"),
"please_enter_merchant_key"=>urlencode("请输入商家密钥"),
"please_enter_salt_key"=>urlencode("请输入Salt Key"),
"please_enter_account_username"=>urlencode("请输入帐户用户名"),
"please_enter_account_hash_id"=>urlencode("请输入帐户哈希ID"),
"invalid_values"=>urlencode("无效的值"),
"please_select_atleast_one_checkout_method"=>urlencode("请选择至少一种结账方式"),
);

$extra_labels_zh_CN = array (
"please_enter_minimum_3_chars"=>urlencode("请输入最少3个字符"),
"invoice"=>urlencode("发票"),
"invoice_to"=>urlencode("开发票"),
"invoice_date"=>urlencode("发票日期"),
"cash"=>urlencode("现金"),
"service_name"=>urlencode("服务名称"),
"qty"=>urlencode("数量"),
"booked_on"=>urlencode("预订"),
);

$front_error_labels_zh_CN = array (
"min_ff_ps"=>urlencode("请输入最少8个字符"),
"max_ff_ps"=>urlencode("请输入最多10个字符"),
"req_ff_fn"=>urlencode("请输入名字"),
"min_ff_fn"=>urlencode("请输入最少3个字符"),
"max_ff_fn"=>urlencode("请输入最多15个字符"),
"req_ff_ln"=>urlencode("请输入姓氏"),
"min_ff_ln"=>urlencode("请输入最少3个字符"),
"max_ff_ln"=>urlencode("请输入最多15个字符"),
"req_ff_ph"=>urlencode("请输入电话号码"),
"min_ff_ph"=>urlencode("请输入最少9个字符"),
"max_ff_ph"=>urlencode("请输入最多15个字符"),
"req_ff_sa"=>urlencode("请输入街道地址"),
"min_ff_sa"=>urlencode("请输入最少10个字符"),
"max_ff_sa"=>urlencode("请输入最多40个字符"),
"req_ff_zp"=>urlencode("请输入邮政编码"),
"min_ff_zp"=>urlencode("请输入最少3个字符"),
"max_ff_zp"=>urlencode("请输入最多7个字符"),
"req_ff_ct"=>urlencode("请输入城市"),
"min_ff_ct"=>urlencode("请输入最少3个字符"),
"max_ff_ct"=>urlencode("请输入最多15个字符"),
"req_ff_st"=>urlencode("请输入州"),
"min_ff_st"=>urlencode("请输入最少3个字符"),
"max_ff_st"=>urlencode("请输入最多15个字符"),
"req_ff_srn"=>urlencode("请输入备注"),
"min_ff_srn"=>urlencode("请输入最少10个字符"),
"max_ff_srn"=>urlencode("请输入最多70个字符"),
"Transaction_failed_please_try_again"=>urlencode("交易失败请再试一次"),
"Please_Enter_valid_card_detail"=>urlencode("请输入有效的卡详细信息"),
);

$language_front_arr_zh_CN = base64_encode(serialize($label_data_zh_CN));
$language_admin_arr_zh_CN = base64_encode(serialize($admin_labels_zh_CN));
$language_error_arr_zh_CN = base64_encode(serialize($error_labels_zh_CN));
$language_extra_arr_zh_CN = base64_encode(serialize($extra_labels_zh_CN));
$language_form_error_arr_zh_CN = base64_encode(serialize($front_error_labels_zh_CN));

$insert_default_lang_zh_CN = "insert into `ct_languages` (`id`,`label_data`,`language`, `admin_labels`, `error_labels`, `extra_labels`, `front_error_labels`,`language_status`) values(NULL,'" . $language_front_arr_zh_CN . "','zh_CN','" . $language_admin_arr_zh_CN . "','" . $language_error_arr_zh_CN . "','" . $language_extra_arr_zh_CN . "','" . $language_form_error_arr_zh_CN . "','Y')";
mysqli_query($this->conn, $insert_default_lang_zh_CN);

    }
    public function q26()
    {
        $update_sender_email = "UPDATE `ct_settings` SET `option_value` = '".$this->email."' WHERE `option_name` = 'ct_email_sender_address';";
        mysqli_query($this->conn, $update_sender_email);
    }
    /*ADDED NEWLY*/
    public function array_push_assoc($array, $key, $value){
        $array[$key] = $value;
        return $array;
    }
    public function get_all_languages()
    {
        $query = "select * from ct_languages";
        $result = mysqli_query($this->conn, $query);
        return $result;
    }
    public function get_all_labelsbyid($lang)
    {
        $query = "select * from ct_languages where language='" . $lang . "'";
        $result = mysqli_query($this->conn, $query);
        $ress = @mysqli_fetch_row($result);
        return $ress;
    }
    public function q27(){		
		/* new version update */
		$remove_fk_constraints_ct_services_addon = "ALTER TABLE ct_services_addon
DROP FOREIGN KEY services_addon_ibfk_1;";
		$remove_fk_constraints_ct_services_addon_rate = "ALTER TABLE ct_addon_service_rate
DROP FOREIGN KEY addon_service_rate_ibfk_1;";
		$remove_fk_constraints_ct_services_method = "ALTER TABLE ct_services_method
DROP FOREIGN KEY services_method_ibfk_1;";
		$remove_fk_constraints_ct_service_methods_units = "ALTER TABLE ct_service_methods_units
DROP FOREIGN KEY service_methods_units_ibfk_1;";
		$remove_fk_constraints_ct_service_methods_units_2 = "ALTER TABLE ct_service_methods_units
DROP FOREIGN KEY service_methods_units_ibfk_2;";
		$remove_fk_constraints_ct_services_methods_units_rate = "ALTER TABLE ct_services_methods_units_rate
DROP FOREIGN KEY services_methods_units_rate_ibfk_1;";
		$remove_fk_constraints_ct_service_methods_design = "ALTER TABLE ct_service_methods_design
DROP FOREIGN KEY service_methods_design_ibfk_1;";
		mysqli_query($this->conn,$remove_fk_constraints_ct_services_addon);
        mysqli_query($this->conn,$remove_fk_constraints_ct_services_addon_rate);
		mysqli_query($this->conn,$remove_fk_constraints_ct_services_method);
		mysqli_query($this->conn,$remove_fk_constraints_ct_service_methods_units);
        mysqli_query($this->conn,$remove_fk_constraints_ct_service_methods_units_2);
        mysqli_query($this->conn,$remove_fk_constraints_ct_services_methods_units_rate);
        mysqli_query($this->conn,$remove_fk_constraints_ct_service_methods_design);
		$add_options = "ALTER TABLE `ct_services_addon` ADD `predefine_image` TEXT NOT NULL";	
        mysqli_query($this->conn,$add_options);
		$add_options = "ALTER TABLE `ct_services_addon` ADD `predefine_image_title` TEXT NOT NULL";  
		mysqli_query($this->conn,$add_options);
		
		/* newly added in 2.1 */
		$query_staff_payment = "CREATE TABLE `ct_staff_commission` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `order_id` int(11) NOT NULL,
		  `staff_id` int(11) NOT NULL,
		  `amt_payable` double NOT NULL,
		  `advance_paid` double NOT NULL,
		  `net_total` double NOT NULL,
		  `payment_method` varchar(50) NOT NULL,
		  `transaction_id` varchar(100) NOT NULL,
		  `payment_date` date NOT NULL,
		  PRIMARY KEY (`id`)
		)ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";
		mysqli_query($this->conn,$query_staff_payment);
		/* newly added in 2.1 */
		
        $sms_template = "CREATE TABLE IF NOT EXISTS `ct_sms_templates` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `sms_subject` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
		  `sms_message` text COLLATE utf8_unicode_ci NOT NULL,
		  `default_message` text COLLATE utf8_unicode_ci NOT NULL,
		  `sms_template_status` enum('E','D') COLLATE utf8_unicode_ci NOT NULL,
		  `sms_template_type` enum('A','C','R','CC','RS','RM') COLLATE utf8_unicode_ci NOT NULL COMMENT 'A=active, C=confirm, R=Reject, CC=Cancel by Client, RS=Reschedule, RM=Reminder',
		  `user_type` enum('A','C') COLLATE utf8_unicode_ci NOT NULL COMMENT 'A=Admin,C=client',
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=30 ;";
        mysqli_query($this->conn,$sms_template);
        $sms_template_insert = "INSERT INTO `ct_sms_templates` (`id`, `sms_subject`, `sms_message`, `default_message`, `sms_template_status`, `sms_template_type`, `user_type`) VALUES
(1, 'Appointment Request', '', 'RGVhciB7e2NsaWVudF9uYW1lfX0sCllvdSBoYXZlIGFuIGFwcG9pbnRtZW50IG9uIHt7Ym9va2luZ19kYXRlfX0gZm9yIHt7c2VydmljZV9uYW1lfX0=', 'E', 'A', 'C'),
(2, 'New Appointment Request Requires Approval', '', 'RGVhciB7e2FkbWluX25hbWV9fSwKWW91IGhhdmUgYW4gYXBwb2ludG1lbnQgb24ge3tib29raW5nX2RhdGV9fSBmb3Ige3tzZXJ2aWNlX25hbWV9fQ==', 'E', 'A', 'A'),
(3, 'Appointment Approved', '', 'RGVhciB7e2NsaWVudF9uYW1lfX0sDQpZb3VyIGFwcG9pbnRtZW50IG9uIHt7Ym9va2luZ19kYXRlfX0gZm9yIHt7c2VydmljZV9uYW1lfX0gaGFzIGJlZW4gY29uZmlybWVkLg', 'E', 'C', 'C'),
(4, 'Appointment Approved', '', 'RGVhciB7e2FkbWluX25hbWV9fSwNCllvdXIgYXBwb2ludG1lbnQgd2l0aCB7e2NsaWVudF9uYW1lfX0gb24ge3tib29raW5nX2RhdGV9fSBmb3Ige3tzZXJ2aWNlX25hbWV9fSBoYXMgYmVlbiBjb25maXJtZWQu', 'E', 'C', 'A'),
(5, 'Appointment Rejected', '', 'RGVhciB7e2NsaWVudF9uYW1lfX0sDQpZb3VyIGFwcG9pbnRtZW50IG9uIHt7Ym9va2luZ19kYXRlfX0gZm9yIHt7c2VydmljZV9uYW1lfX0gaGFzIGJlZW4gcmVqZWN0ZWQu', 'E', 'R', 'C'),
(6, 'Appointment Rejected', '', 'RGVhciB7e2FkbWluX25hbWV9fSwNCllvdXIgYXBwb2ludG1lbnQgd2l0aCB7e2NsaWVudF9uYW1lfX0gb24ge3tib29raW5nX2RhdGV9fSBmb3Ige3tzZXJ2aWNlX25hbWV9fSBoYXMgYmVlbiByZWplY3RlZC4=', 'E', 'R', 'A'),
(7, 'Appointment Cancelled by you', '', 'RGVhciB7e2NsaWVudF9uYW1lfX0sDQpZb3VyIGFwcG9pbnRtZW50IG9uIHt7Ym9va2luZ19kYXRlfX0gZm9yIHt7c2VydmljZV9uYW1lfX0gaGFzIGJlZW4gY2FuY2VsbGVkLg==', 'E', 'CC', 'C'),
(8, 'Appointment Cancelled By Customer', '', 'RGVhciB7e2FkbWluX25hbWV9fSwNCllvdXIgYXBwb2ludG1lbnQgd2l0aCB7e2NsaWVudF9uYW1lfX0gb24ge3tib29raW5nX2RhdGV9fSBmb3Ige3tzZXJ2aWNlX25hbWV9fSBoYXMgYmVlbiBjYW5jZWxsZWQu', 'E', 'CC', 'A'),
(9, 'Appointment Rescheduled by you', '', 'RGVhciB7e2NsaWVudF9uYW1lfX0sDQpZb3VyIGFwcG9pbnRtZW50IG9uIHt7Ym9va2luZ19kYXRlfX0gZm9yIHt7c2VydmljZV9uYW1lfX0gaGFzIGJlZW4gcmVzY2hlZHVsZWQu', 'E', 'RS', 'C'),
(10, 'Appointment Rescheduled By Customer', '', 'RGVhciB7e2FkbWluX25hbWV9fSwNCllvdXIgYXBwb2ludG1lbnQgd2l0aCB7e2NsaWVudF9uYW1lfX0gb24ge3tib29raW5nX2RhdGV9fSBmb3Ige3tzZXJ2aWNlX25hbWV9fSBoYXMgYmVlbiByZXNjaGVkdWxlZC4=', 'E', 'RS', 'A'),
(11, 'Client Appointment Reminder', '', 'RGVhciB7e2NsaWVudF9uYW1lfX0sCllvdXIgYXBwb2ludG1lbnQgd2l0aCB7e2FkbWluX25hbWV9fSBpcyBzY2hlZHVsZWQgaW4ge3thcHBfcmVtYWluX3RpbWV9fSBob3Vycy4=', 'E', 'RM', 'C'),
(12, 'Admin Appointment Reminder', '', 'RGVhciB7e2FkbWluX25hbWV9fSwKWW91ciBhcHBvaW50bWVudCB3aXRoIHt7Y2xpZW50X25hbWV9fSBpcyBzY2hlZHVsZWQgaW4ge3thcHBfcmVtYWluX3RpbWV9fSBob3Vycy4=', 'E', 'RM', 'A');";
        mysqli_query($this->conn,$sms_template_insert);
        $email_template = "CREATE TABLE IF NOT EXISTS `ct_email_templates` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `email_subject` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
		  `email_message` text COLLATE utf8_unicode_ci NOT NULL,
		  `default_message` text COLLATE utf8_unicode_ci NOT NULL,
		  `email_template_status` enum('E','D') COLLATE utf8_unicode_ci NOT NULL,
		  `email_template_type` enum('A','C','R','CC','RS','RM') COLLATE utf8_unicode_ci NOT NULL COMMENT 'A=active, C=confirm, R=Reject, CC=Cancel by Client, RS=Reschedule, RM=Reminder',
		  `user_type` enum('A','C','S') COLLATE utf8_unicode_ci NOT NULL COMMENT 'A=Admin,C=client,S=Staff',
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=30 ;";
        mysqli_query($this->conn,$email_template);
        $email_template_insert = "
INSERT INTO `ct_email_templates` (`id`, `email_subject`, `email_message`, `default_message`, `email_template_status`, `email_template_type`, `user_type`) VALUES
(1, 'Appointment Request', '', 'PGh0bWw+DQo8aGVhZD4NCgk8bWV0YSBuYW1lPSJ2aWV3cG9ydCIgY29udGVudD0id2lkdGg9ZGV2aWNlLXdpZHRoLCBpbml0aWFsLXNjYWxlPTEuMCIvPg0KCTxtZXRhIGh0dHAtZXF1aXY9IkNvbnRlbnQtVHlwZSIgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PVVURi04IiAvPg0KCTx0aXRsZT5TdWJqZWN0OiB7e3NlcnZpY2VfbmFtZX19IG9uIHt7Ym9va2luZ19kYXRlfX08L3RpdGxlPg0KCTxsaW5rIGhyZWY9Imh0dHBzOi8vZm9udHMuZ29vZ2xlYXBpcy5jb20vY3NzP2ZhbWlseT1Nb250c2VycmF0IiByZWw9InN0eWxlc2hlZXQiPg0KPC9oZWFkPg0KPGJvZHk+CQkNCgk8ZGl2IHN0eWxlPSJtYXJnaW46IDA7cGFkZGluZzogMDtmb250LWZhbWlseTogSGVsdmV0aWNhIE5ldWUsIEhlbHZldGljYSwgSGVsdmV0aWNhLCBBcmlhbCwgc2Fucy1zZXJpZjtmb250LXNpemU6IDEwMCU7bGluZS1oZWlnaHQ6IDEuNjtib3gtc2l6aW5nOiBib3JkZXItYm94OyI+CQ0KCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBibG9jayAhaW1wb3J0YW50O21heC13aWR0aDogNjAwcHggIWltcG9ydGFudDttYXJnaW46IDAgYXV0byAhaW1wb3J0YW50O2NsZWFyOiBib3RoICFpbXBvcnRhbnQ7Ij4NCgkJCTx0YWJsZSBzdHlsZT0iYm9yZGVyOiAxcHggc29saWQgI2MyYzJjMjt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDttYXJnaW46IDMwcHggMHB4Oy13ZWJraXQtYm9yZGVyLXJhZGl1czogNXB4Oy1tb3otYm9yZGVyLXJhZGl1czogNXB4Oy1vLWJvcmRlci1yYWRpdXM6IDVweDtib3JkZXItcmFkaXVzOiA1cHg7Ij4NCgkJCQk8dGJvZHk+DQoJCQkJCTx0ciBzdHlsZT0iYm9yZGVyLWJvdHRvbTogMXB4IHNvbGlkICNlNmU2ZTY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7Ij4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDU5JTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7dGV4dC1hbGlnbjogbGVmdDtmb250LWZhbWlseTogTW9udHNlcnJhdCwgc2Fucy1zZXJpZjsiPg0KCQkJCQkJCQl7e2NvbXBhbnlfbmFtZX19PGJyIC8+e3tjb21wYW55X2FkZHJlc3N9fTxiciAvPnt7Y29tcGFueV9jaXR5fX0sIHt7Y29tcGFueV9zdGF0ZX19LCB7e2NvbXBhbnlfemlwfX08YnIgLz57e2NvbXBhbnlfY291bnRyeX19PGJyIC8+e3tjb21wYW55X3Bob25lfX08YnIgLz57e2NvbXBhbnlfZW1haWx9fQ0KCQkJCQkJCTwvZGl2Pg0KCQkJCQkJPC90ZD4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDQwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7Ij4NCgkJCQkJCQkJPGRpdiBzdHlsZT0id2lkdGg6IDEzMHB4O2hlaWdodDogMTAwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO21hcmdpbjogMHB4IGF1dG87Ij4NCgkJCQkJCQkJCTxpbWcgc3R5bGU9IndpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0O2Rpc3BsYXk6IGlubGluZS1ibG9jaztoZWlnaHQ6IDEwMCU7IiBzcmM9Int7YnVzaW5lc3NfbG9nb319IiAvPg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQk8L2Rpdj4NCgkJCQkJCTwvdGQ+DQoJCQkJCQkNCgkJCQkJCQ0KCQkJCQk8L3RyPg0KCQkJCQk8dHI+DQoJCQkJCQk8dGQ+DQoJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMjVweCAzMHB4O2JhY2tncm91bmQ6ICNmZmY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDkwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQkJPGg2IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bWFyZ2luOiAxMHB4IDBweCAxMHB4O2ZvbnQtd2VpZ2h0OiA2MDA7Ij5EZWFyIHt7Y2xpZW50X25hbWV9fSw8L2g2Pg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDttYXJnaW46IDEwcHggMHB4IDE1cHg7Ij5Zb3UndmUgc2V0IGEgbmV3IGFwcG9pbnRtZW50IHdpdGggZm9sbG93aW5nIGRldGFpbHM6PC9wPgkJCQkJCQkNCgkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCTxkaXYgc3R5bGU9ImZsb2F0OiBsZWZ0O3dpZHRoOiAxMDAlO2Rpc3BsYXk6IGJsb2NrO3BhZGRpbmc6IDEwcHggMHB4OyI+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5XaGVuOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7Ym9va2luZ19kYXRlfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkZvcjogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3NlcnZpY2VfbmFtZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5NZXRob2RzIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e21ldGhvZG5hbWV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+VW5pdHMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7dW5pdHN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+QWRkLW9ucyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3thZGRvbnN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UHJpY2UgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7cHJpY2V9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJDQoJCQkJCQkJCQkNCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPk5hbWUgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7Zmlyc3RuYW1lfX0ge3tsYXN0bmFtZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5FbWFpbCA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tjbGllbnRfZW1haWx9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UGhvbmUgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7cGhvbmV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UGF5bWVudCA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twYXltZW50X21ldGhvZH19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5WYWNjdW0gQ2xlYW5lciA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3t2YWNjdW1fY2xlYW5lcl9zdGF0dXN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UGFya2luZyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twYXJraW5nX3N0YXR1c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5BZGRyZXNzIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2FkZHJlc3N9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+Tm90ZXMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7bm90ZXN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+Q29udGFjdCBTdGF0dXMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7Y29udGFjdF9zdGF0dXN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMTVweCAwcHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7Ym9yZGVyLWJvdHRvbTogMXB4IHNvbGlkICNlNmU2ZTY7Ij4NCgkJCQkJCQkJCTxwIHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bGluZS1oZWlnaHQ6IDIycHg7bWFyZ2luOiAxMHB4IDBweCAxNXB4O2Zsb2F0OiBsZWZ0OyI+WW91ciBhcHBvaW50bWVudCBpcyB0ZW50YXRpdmUgYW5kIHlvdSB3aWxsIGJlIG5vdGlmaWVkIGFzIHdlIHdpbGwgY29uZmlybSB0aGlzIGJvb2tpbmcuPC9wPg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMTBweCAwcHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7dGV4dC1hbGlnbjogY2VudGVyOyI+DQoJCQkJCQkJCQk8aDUgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTNweDttYXJnaW46IDBweCAwcHggNXB4OyI+VGhhbmsgeW91PC9oNT4NCgkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJPC9kaXY+DQoJCQkJCQk8L3RkPgkJCQkJDQoJCQkJCTwvdHI+CQkJCQ0KCQkJCTwvdGJvZHk+DQoJCQk8L3RhYmxlPgkNCgkJPC9kaXY+DQoJPC9kaXY+CQ0KPC9ib2R5Pg0KPC9odG1sPg==', 'E', 'A', 'C'),
(2, 'New Appointment Request Requires Approval', '', 'PGh0bWw+DQo8aGVhZD4NCgk8bWV0YSBuYW1lPSJ2aWV3cG9ydCIgY29udGVudD0id2lkdGg9ZGV2aWNlLXdpZHRoLCBpbml0aWFsLXNjYWxlPTEuMCIvPg0KCTxtZXRhIGh0dHAtZXF1aXY9IkNvbnRlbnQtVHlwZSIgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PVVURi04IiAvPg0KCTx0aXRsZT5TdWJqZWN0OiB7e3NlcnZpY2VfbmFtZX19IG9uIHt7Ym9va2luZ19kYXRlfX08L3RpdGxlPg0KCTxsaW5rIGhyZWY9Imh0dHBzOi8vZm9udHMuZ29vZ2xlYXBpcy5jb20vY3NzP2ZhbWlseT1Nb250c2VycmF0IiByZWw9InN0eWxlc2hlZXQiPg0KPC9oZWFkPg0KPGJvZHk+CQkNCgk8ZGl2IHN0eWxlPSJtYXJnaW46IDA7cGFkZGluZzogMDtmb250LWZhbWlseTogSGVsdmV0aWNhIE5ldWUsIEhlbHZldGljYSwgSGVsdmV0aWNhLCBBcmlhbCwgc2Fucy1zZXJpZjtmb250LXNpemU6IDEwMCU7bGluZS1oZWlnaHQ6IDEuNjtib3gtc2l6aW5nOiBib3JkZXItYm94OyI+CQ0KCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBibG9jayAhaW1wb3J0YW50O21heC13aWR0aDogNjAwcHggIWltcG9ydGFudDttYXJnaW46IDAgYXV0byAhaW1wb3J0YW50O2NsZWFyOiBib3RoICFpbXBvcnRhbnQ7Ij4NCgkJCTx0YWJsZSBzdHlsZT0iYm9yZGVyOiAxcHggc29saWQgI2MyYzJjMjt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDttYXJnaW46IDMwcHggMHB4Oy13ZWJraXQtYm9yZGVyLXJhZGl1czogNXB4Oy1tb3otYm9yZGVyLXJhZGl1czogNXB4Oy1vLWJvcmRlci1yYWRpdXM6IDVweDtib3JkZXItcmFkaXVzOiA1cHg7Ij4NCgkJCQk8dGJvZHk+DQoJCQkJCTx0ciBzdHlsZT0iYm9yZGVyLWJvdHRvbTogMXB4IHNvbGlkICNlNmU2ZTY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7Ij4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDU5JTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7dGV4dC1hbGlnbjogbGVmdDtmb250LWZhbWlseTogTW9udHNlcnJhdCwgc2Fucy1zZXJpZjsiPg0KCQkJCQkJCQl7e2NvbXBhbnlfbmFtZX19PGJyIC8+e3tjb21wYW55X2FkZHJlc3N9fTxiciAvPnt7Y29tcGFueV9jaXR5fX0sIHt7Y29tcGFueV9zdGF0ZX19LCB7e2NvbXBhbnlfemlwfX08YnIgLz57e2NvbXBhbnlfY291bnRyeX19PGJyIC8+e3tjb21wYW55X3Bob25lfX08YnIgLz57e2NvbXBhbnlfZW1haWx9fQ0KCQkJCQkJCTwvZGl2Pg0KCQkJCQkJPC90ZD4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDQwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7Ij4NCgkJCQkJCQkJPGRpdiBzdHlsZT0id2lkdGg6IDEzMHB4O2hlaWdodDogMTAwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO21hcmdpbjogMHB4IGF1dG87Ij4NCgkJCQkJCQkJCTxpbWcgc3R5bGU9IndpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0O2Rpc3BsYXk6IGlubGluZS1ibG9jaztoZWlnaHQ6IDEwMCU7IiBzcmM9Int7YnVzaW5lc3NfbG9nb319IiAvPg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQk8L2Rpdj4NCgkJCQkJCTwvdGQ+DQoJCQkJCQkNCgkJCQkJCQ0KCQkJCQk8L3RyPg0KCQkJCQk8dHI+DQoJCQkJCQk8dGQ+DQoJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMjVweCAzMHB4O2JhY2tncm91bmQ6ICNmZmY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDkwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQkJPGg2IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bWFyZ2luOiAxMHB4IDBweCAxMHB4O2ZvbnQtd2VpZ2h0OiA2MDA7Ij5EZWFyIHt7YWRtaW5fbmFtZX19LCA8L2g2Pg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDttYXJnaW46IDEwcHggMHB4IDE1cHg7Ij5Zb3UndmUgbmV3IGFwcG9pbnRtZW50IHdpdGgge3tjbGllbnRfbmFtZX19IHdpdGggZm9sbG93aW5nIGRldGFpbHM6PC9wPgkJCQkJCQkNCgkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCTxkaXYgc3R5bGU9ImZsb2F0OiBsZWZ0O3dpZHRoOiAxMDAlO2Rpc3BsYXk6IGJsb2NrO3BhZGRpbmc6IDEwcHggMHB4OyI+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5XaGVuOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7Ym9va2luZ19kYXRlfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkZvcjogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3NlcnZpY2VfbmFtZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5NZXRob2RzIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e21ldGhvZG5hbWV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+VW5pdHMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7dW5pdHN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+QWRkLW9ucyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3thZGRvbnN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UHJpY2UgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7cHJpY2V9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJDQoJCQkJCQkJCQkNCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPk5hbWUgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7Zmlyc3RuYW1lfX0ge3tsYXN0bmFtZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5FbWFpbCA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tjbGllbnRfZW1haWx9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UGhvbmUgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7cGhvbmV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UGF5bWVudCA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twYXltZW50X21ldGhvZH19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5WYWNjdW0gQ2xlYW5lciA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3t2YWNjdW1fY2xlYW5lcl9zdGF0dXN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UGFya2luZyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twYXJraW5nX3N0YXR1c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5BZGRyZXNzIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2FkZHJlc3N9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+Tm90ZXMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7bm90ZXN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+Q29udGFjdCBTdGF0dXMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7Y29udGFjdF9zdGF0dXN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMTVweCAwcHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7Ym9yZGVyLWJvdHRvbTogMXB4IHNvbGlkICNlNmU2ZTY7Ij4NCgkJCQkJCQkJCTxwIHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bGluZS1oZWlnaHQ6IDIycHg7bWFyZ2luOiAxMHB4IDBweCAxNXB4O2Zsb2F0OiBsZWZ0OyI+VGhpcyBhcHBvaW50bWVudCBpcyBpbiBwZW5kaW5nLjwvcD4NCgkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCTxkaXYgc3R5bGU9InBhZGRpbmc6IDEwcHggMHB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiAxMDAlO2Rpc3BsYXk6IGJsb2NrO3RleHQtYWxpZ246IGNlbnRlcjsiPg0KCQkJCQkJCQkJPGg1IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDEzcHg7bWFyZ2luOiAwcHggMHB4IDVweDsiPlRoYW5rIHlvdTwvaDU+DQoJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCTwvZGl2Pg0KCQkJCQkJPC90ZD4JCQkJCQ0KCQkJCQk8L3RyPgkJCQkNCgkJCQk8L3Rib2R5Pg0KCQkJPC90YWJsZT4JDQoJCTwvZGl2Pg0KCTwvZGl2PgkNCjwvYm9keT4NCjwvaHRtbD4=', 'E', 'A', 'A'),
(3, 'New Appointment Assigned', '', 'PGh0bWw+DQo8aGVhZD4NCgk8bWV0YSBuYW1lPSJ2aWV3cG9ydCIgY29udGVudD0id2lkdGg9ZGV2aWNlLXdpZHRoLCBpbml0aWFsLXNjYWxlPTEuMCIvPg0KCTxtZXRhIGh0dHAtZXF1aXY9IkNvbnRlbnQtVHlwZSIgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PVVURi04IiAvPg0KCTx0aXRsZT5TdWJqZWN0OiB7e3NlcnZpY2VfbmFtZX19IG9uIHt7Ym9va2luZ19kYXRlfX08L3RpdGxlPg0KCTxsaW5rIGhyZWY9Imh0dHBzOi8vZm9udHMuZ29vZ2xlYXBpcy5jb20vY3NzP2ZhbWlseT1Nb250c2VycmF0IiByZWw9InN0eWxlc2hlZXQiPg0KPC9oZWFkPg0KPGJvZHk+CQkNCgk8ZGl2IHN0eWxlPSJtYXJnaW46IDA7cGFkZGluZzogMDtmb250LWZhbWlseTogSGVsdmV0aWNhIE5ldWUsIEhlbHZldGljYSwgSGVsdmV0aWNhLCBBcmlhbCwgc2Fucy1zZXJpZjtmb250LXNpemU6IDEwMCU7bGluZS1oZWlnaHQ6IDEuNjtib3gtc2l6aW5nOiBib3JkZXItYm94OyI+CQ0KCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBibG9jayAhaW1wb3J0YW50O21heC13aWR0aDogNjAwcHggIWltcG9ydGFudDttYXJnaW46IDAgYXV0byAhaW1wb3J0YW50O2NsZWFyOiBib3RoICFpbXBvcnRhbnQ7Ij4NCgkJCTx0YWJsZSBzdHlsZT0iYm9yZGVyOiAxcHggc29saWQgI2MyYzJjMjt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDttYXJnaW46IDMwcHggMHB4Oy13ZWJraXQtYm9yZGVyLXJhZGl1czogNXB4Oy1tb3otYm9yZGVyLXJhZGl1czogNXB4Oy1vLWJvcmRlci1yYWRpdXM6IDVweDtib3JkZXItcmFkaXVzOiA1cHg7Ij4NCgkJCQk8dGJvZHk+DQoJCQkJCTx0ciBzdHlsZT0iYm9yZGVyLWJvdHRvbTogMXB4IHNvbGlkICNlNmU2ZTY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7Ij4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDU5JTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7dGV4dC1hbGlnbjogbGVmdDtmb250LWZhbWlseTogTW9udHNlcnJhdCwgc2Fucy1zZXJpZjsiPg0KCQkJCQkJCQl7e2NvbXBhbnlfbmFtZX19PGJyIC8+e3tjb21wYW55X2FkZHJlc3N9fTxiciAvPnt7Y29tcGFueV9jaXR5fX0sIHt7Y29tcGFueV9zdGF0ZX19LCB7e2NvbXBhbnlfemlwfX08YnIgLz57e2NvbXBhbnlfY291bnRyeX19PGJyIC8+e3tjb21wYW55X3Bob25lfX08YnIgLz57e2NvbXBhbnlfZW1haWx9fQ0KCQkJCQkJCTwvZGl2Pg0KCQkJCQkJPC90ZD4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDQwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7Ij4NCgkJCQkJCQkJPGRpdiBzdHlsZT0id2lkdGg6IDEzMHB4O2hlaWdodDogMTAwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO21hcmdpbjogMHB4IGF1dG87Ij4NCgkJCQkJCQkJCTxpbWcgc3R5bGU9IndpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0O2Rpc3BsYXk6IGlubGluZS1ibG9jaztoZWlnaHQ6IDEwMCU7IiBzcmM9Int7YnVzaW5lc3NfbG9nb319IiAvPg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQk8L2Rpdj4NCgkJCQkJCTwvdGQ+DQoJCQkJCQkNCgkJCQkJCQ0KCQkJCQk8L3RyPg0KCQkJCQk8dHI+DQoJCQkJCQk8dGQ+DQoJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMjVweCAzMHB4O2JhY2tncm91bmQ6ICNmZmY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDkwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQkJPGg2IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bWFyZ2luOiAxMHB4IDBweCAxMHB4O2ZvbnQtd2VpZ2h0OiA2MDA7Ij5EZWFyIHt7YWRtaW5fbmFtZX19LCA8L2g2Pg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDttYXJnaW46IDEwcHggMHB4IDE1cHg7Ij5Zb3UndmUgbmV3IGFwcG9pbnRtZW50IHdpdGgge3tjbGllbnRfbmFtZX19IHdpdGggZm9sbG93aW5nIGRldGFpbHM6PC9wPgkJCQkJCQkNCgkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCTxkaXYgc3R5bGU9ImZsb2F0OiBsZWZ0O3dpZHRoOiAxMDAlO2Rpc3BsYXk6IGJsb2NrO3BhZGRpbmc6IDEwcHggMHB4OyI+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5XaGVuOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7Ym9va2luZ19kYXRlfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkZvcjogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3NlcnZpY2VfbmFtZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5NZXRob2RzIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e21ldGhvZG5hbWV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+VW5pdHMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7dW5pdHN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+QWRkLW9ucyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3thZGRvbnN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UHJpY2UgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7cHJpY2V9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJDQoJCQkJCQkJCQkNCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPk5hbWUgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7Zmlyc3RuYW1lfX0ge3tsYXN0bmFtZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5FbWFpbCA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tjbGllbnRfZW1haWx9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UGhvbmUgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7cGhvbmV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UGF5bWVudCA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twYXltZW50X21ldGhvZH19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5WYWNjdW0gQ2xlYW5lciA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3t2YWNjdW1fY2xlYW5lcl9zdGF0dXN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UGFya2luZyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twYXJraW5nX3N0YXR1c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5BZGRyZXNzIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2FkZHJlc3N9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+Tm90ZXMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7bm90ZXN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+Q29udGFjdCBTdGF0dXMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7Y29udGFjdF9zdGF0dXN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMTVweCAwcHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7Ym9yZGVyLWJvdHRvbTogMXB4IHNvbGlkICNlNmU2ZTY7Ij4NCgkJCQkJCQkJCTxwIHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bGluZS1oZWlnaHQ6IDIycHg7bWFyZ2luOiAxMHB4IDBweCAxNXB4O2Zsb2F0OiBsZWZ0OyI+VGhpcyBhcHBvaW50bWVudCBpcyBpbiBwZW5kaW5nLjwvcD4NCgkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCTxkaXYgc3R5bGU9InBhZGRpbmc6IDEwcHggMHB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiAxMDAlO2Rpc3BsYXk6IGJsb2NrO3RleHQtYWxpZ246IGNlbnRlcjsiPg0KCQkJCQkJCQkJPGg1IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDEzcHg7bWFyZ2luOiAwcHggMHB4IDVweDsiPlRoYW5rIHlvdTwvaDU+DQoJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCTwvZGl2Pg0KCQkJCQkJPC90ZD4JCQkJCQ0KCQkJCQk8L3RyPgkJCQkNCgkJCQk8L3Rib2R5Pg0KCQkJPC90YWJsZT4JDQoJCTwvZGl2Pg0KCTwvZGl2PgkNCjwvYm9keT4NCjwvaHRtbD4=', 'E', 'A', 'S'),
(4, 'Appointment Approved', '', 'PGh0bWw+DQo8aGVhZD4NCgk8bWV0YSBuYW1lPSJ2aWV3cG9ydCIgY29udGVudD0id2lkdGg9ZGV2aWNlLXdpZHRoLCBpbml0aWFsLXNjYWxlPTEuMCIvPg0KCTxtZXRhIGh0dHAtZXF1aXY9IkNvbnRlbnQtVHlwZSIgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PVVURi04IiAvPg0KCTx0aXRsZT5TdWJqZWN0OiB7e3NlcnZpY2VfbmFtZX19IG9uIHt7Ym9va2luZ19kYXRlfX08L3RpdGxlPg0KCTxsaW5rIGhyZWY9Imh0dHBzOi8vZm9udHMuZ29vZ2xlYXBpcy5jb20vY3NzP2ZhbWlseT1Nb250c2VycmF0IiByZWw9InN0eWxlc2hlZXQiPg0KPC9oZWFkPg0KPGJvZHk+CQkNCgk8ZGl2IHN0eWxlPSJtYXJnaW46IDA7cGFkZGluZzogMDtmb250LWZhbWlseTogSGVsdmV0aWNhIE5ldWUsIEhlbHZldGljYSwgSGVsdmV0aWNhLCBBcmlhbCwgc2Fucy1zZXJpZjtmb250LXNpemU6IDEwMCU7bGluZS1oZWlnaHQ6IDEuNjtib3gtc2l6aW5nOiBib3JkZXItYm94OyI+CQ0KCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBibG9jayAhaW1wb3J0YW50O21heC13aWR0aDogNjAwcHggIWltcG9ydGFudDttYXJnaW46IDAgYXV0byAhaW1wb3J0YW50O2NsZWFyOiBib3RoICFpbXBvcnRhbnQ7Ij4NCgkJCTx0YWJsZSBzdHlsZT0iYm9yZGVyOiAxcHggc29saWQgI2MyYzJjMjt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDttYXJnaW46IDMwcHggMHB4Oy13ZWJraXQtYm9yZGVyLXJhZGl1czogNXB4Oy1tb3otYm9yZGVyLXJhZGl1czogNXB4Oy1vLWJvcmRlci1yYWRpdXM6IDVweDtib3JkZXItcmFkaXVzOiA1cHg7Ij4NCgkJCQk8dGJvZHk+DQoJCQkJCTx0ciBzdHlsZT0iYm9yZGVyLWJvdHRvbTogMXB4IHNvbGlkICNlNmU2ZTY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7Ij4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDU5JTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7dGV4dC1hbGlnbjogbGVmdDtmb250LWZhbWlseTogTW9udHNlcnJhdCwgc2Fucy1zZXJpZjsiPg0KCQkJCQkJCQl7e2NvbXBhbnlfbmFtZX19PGJyIC8+e3tjb21wYW55X2FkZHJlc3N9fTxiciAvPnt7Y29tcGFueV9jaXR5fX0sIHt7Y29tcGFueV9zdGF0ZX19LCB7e2NvbXBhbnlfemlwfX08YnIgLz57e2NvbXBhbnlfY291bnRyeX19PGJyIC8+e3tjb21wYW55X3Bob25lfX08YnIgLz57e2NvbXBhbnlfZW1haWx9fQ0KCQkJCQkJCTwvZGl2Pg0KCQkJCQkJPC90ZD4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDQwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7Ij4NCgkJCQkJCQkJPGRpdiBzdHlsZT0id2lkdGg6IDEzMHB4O2hlaWdodDogMTAwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO21hcmdpbjogMHB4IGF1dG87Ij4NCgkJCQkJCQkJCTxpbWcgc3R5bGU9IndpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0O2Rpc3BsYXk6IGlubGluZS1ibG9jaztoZWlnaHQ6IDEwMCU7IiBzcmM9Int7YnVzaW5lc3NfbG9nb319IiAvPg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQk8L2Rpdj4NCgkJCQkJCTwvdGQ+DQoJCQkJCQkNCgkJCQkJCQ0KCQkJCQk8L3RyPg0KCQkJCQk8dHI+DQoJCQkJCQk8dGQ+DQoJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMjVweCAzMHB4O2JhY2tncm91bmQ6ICNmZmY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDkwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQkJPGg2IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bWFyZ2luOiAxMHB4IDBweCAxMHB4O2ZvbnQtd2VpZ2h0OiA2MDA7Ij5EZWFyIHt7Y2xpZW50X25hbWV9fSw8L2g2Pg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDttYXJnaW46IDEwcHggMHB4IDE1cHg7Ij5Zb3VyIGFwcG9pbnRtZW50IHdpdGggZm9sbG93aW5nIGRldGFpbHM6PC9wPgkJCQkJCQkNCgkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCTxkaXYgc3R5bGU9ImZsb2F0OiBsZWZ0O3dpZHRoOiAxMDAlO2Rpc3BsYXk6IGJsb2NrO3BhZGRpbmc6IDEwcHggMHB4OyI+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5XaGVuOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7Ym9va2luZ19kYXRlfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkZvcjogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3NlcnZpY2VfbmFtZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5NZXRob2RzIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e21ldGhvZG5hbWV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+VW5pdHMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7dW5pdHN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+QWRkLW9ucyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3thZGRvbnN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UHJpY2UgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7cHJpY2V9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJDQoJCQkJCQkJCQkNCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPk5hbWUgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7Zmlyc3RuYW1lfX0ge3tsYXN0bmFtZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5FbWFpbCA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tjbGllbnRfZW1haWx9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UGhvbmUgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7cGhvbmV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UGF5bWVudCA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twYXltZW50X21ldGhvZH19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5WYWNjdW0gQ2xlYW5lciA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3t2YWNjdW1fY2xlYW5lcl9zdGF0dXN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UGFya2luZyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twYXJraW5nX3N0YXR1c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5BZGRyZXNzIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2FkZHJlc3N9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+Tm90ZXMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7bm90ZXN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+Q29udGFjdCBTdGF0dXMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7Y29udGFjdF9zdGF0dXN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMTVweCAwcHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7Ym9yZGVyLWJvdHRvbTogMXB4IHNvbGlkICNlNmU2ZTY7Ij4NCgkJCQkJCQkJCTxwIHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bGluZS1oZWlnaHQ6IDIycHg7bWFyZ2luOiAxMHB4IDBweCAxNXB4O2Zsb2F0OiBsZWZ0OyI+aGFzIGJlZW4gY29uZmlybWVkLjwvcD4NCgkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCTxkaXYgc3R5bGU9InBhZGRpbmc6IDEwcHggMHB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiAxMDAlO2Rpc3BsYXk6IGJsb2NrO3RleHQtYWxpZ246IGNlbnRlcjsiPg0KCQkJCQkJCQkJPGg1IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDEzcHg7bWFyZ2luOiAwcHggMHB4IDVweDsiPlRoYW5rIHlvdTwvaDU+DQoJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCTwvZGl2Pg0KCQkJCQkJPC90ZD4JCQkJCQ0KCQkJCQk8L3RyPgkJCQkNCgkJCQk8L3Rib2R5Pg0KCQkJPC90YWJsZT4JDQoJCTwvZGl2Pg0KCTwvZGl2PgkNCjwvYm9keT4NCjwvaHRtbD4=', 'E', 'C', 'C'),
(5, 'Appointment Approved', '', 'PGh0bWw+DQo8aGVhZD4NCgk8bWV0YSBuYW1lPSJ2aWV3cG9ydCIgY29udGVudD0id2lkdGg9ZGV2aWNlLXdpZHRoLCBpbml0aWFsLXNjYWxlPTEuMCIvPg0KCTxtZXRhIGh0dHAtZXF1aXY9IkNvbnRlbnQtVHlwZSIgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PVVURi04IiAvPg0KCTx0aXRsZT5TdWJqZWN0OiB7e3NlcnZpY2VfbmFtZX19IG9uIHt7Ym9va2luZ19kYXRlfX08L3RpdGxlPg0KCTxsaW5rIGhyZWY9Imh0dHBzOi8vZm9udHMuZ29vZ2xlYXBpcy5jb20vY3NzP2ZhbWlseT1Nb250c2VycmF0IiByZWw9InN0eWxlc2hlZXQiPg0KPC9oZWFkPg0KPGJvZHk+CQkNCgk8ZGl2IHN0eWxlPSJtYXJnaW46IDA7cGFkZGluZzogMDtmb250LWZhbWlseTogSGVsdmV0aWNhIE5ldWUsIEhlbHZldGljYSwgSGVsdmV0aWNhLCBBcmlhbCwgc2Fucy1zZXJpZjtmb250LXNpemU6IDEwMCU7bGluZS1oZWlnaHQ6IDEuNjtib3gtc2l6aW5nOiBib3JkZXItYm94OyI+CQ0KCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBibG9jayAhaW1wb3J0YW50O21heC13aWR0aDogNjAwcHggIWltcG9ydGFudDttYXJnaW46IDAgYXV0byAhaW1wb3J0YW50O2NsZWFyOiBib3RoICFpbXBvcnRhbnQ7Ij4NCgkJCTx0YWJsZSBzdHlsZT0iYm9yZGVyOiAxcHggc29saWQgI2MyYzJjMjt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDttYXJnaW46IDMwcHggMHB4Oy13ZWJraXQtYm9yZGVyLXJhZGl1czogNXB4Oy1tb3otYm9yZGVyLXJhZGl1czogNXB4Oy1vLWJvcmRlci1yYWRpdXM6IDVweDtib3JkZXItcmFkaXVzOiA1cHg7Ij4NCgkJCQk8dGJvZHk+DQoJCQkJCTx0ciBzdHlsZT0iYm9yZGVyLWJvdHRvbTogMXB4IHNvbGlkICNlNmU2ZTY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7Ij4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDU5JTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7dGV4dC1hbGlnbjogbGVmdDtmb250LWZhbWlseTogTW9udHNlcnJhdCwgc2Fucy1zZXJpZjsiPg0KCQkJCQkJCQl7e2NvbXBhbnlfbmFtZX19PGJyIC8+e3tjb21wYW55X2FkZHJlc3N9fTxiciAvPnt7Y29tcGFueV9jaXR5fX0sIHt7Y29tcGFueV9zdGF0ZX19LCB7e2NvbXBhbnlfemlwfX08YnIgLz57e2NvbXBhbnlfY291bnRyeX19PGJyIC8+e3tjb21wYW55X3Bob25lfX08YnIgLz57e2NvbXBhbnlfZW1haWx9fQ0KCQkJCQkJCTwvZGl2Pg0KCQkJCQkJPC90ZD4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDQwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7Ij4NCgkJCQkJCQkJPGRpdiBzdHlsZT0id2lkdGg6IDEzMHB4O2hlaWdodDogMTAwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO21hcmdpbjogMHB4IGF1dG87Ij4NCgkJCQkJCQkJCTxpbWcgc3R5bGU9IndpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0O2Rpc3BsYXk6IGlubGluZS1ibG9jaztoZWlnaHQ6IDEwMCU7IiBzcmM9Int7YnVzaW5lc3NfbG9nb319IiAvPg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQk8L2Rpdj4NCgkJCQkJCTwvdGQ+DQoJCQkJCQkNCgkJCQkJCQ0KCQkJCQk8L3RyPg0KCQkJCQk8dHI+DQoJCQkJCQk8dGQ+DQoJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMjVweCAzMHB4O2JhY2tncm91bmQ6ICNmZmY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDkwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQkJPGg2IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bWFyZ2luOiAxMHB4IDBweCAxMHB4O2ZvbnQtd2VpZ2h0OiA2MDA7Ij5EZWFyIHt7YWRtaW5fbmFtZX19LCA8L2g2Pg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDttYXJnaW46IDEwcHggMHB4IDE1cHg7Ij5UaGUgYXBwb2ludG1lbnQgd2l0aCB7e2NsaWVudF9uYW1lfX0gd2l0aCBmb2xsb3dpbmcgZGV0YWlsczo8L3A+CQkJCQkJCQ0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJPGRpdiBzdHlsZT0iZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7cGFkZGluZzogMTBweCAwcHg7Ij4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPldoZW46IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tib29raW5nX2RhdGV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+Rm9yOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7c2VydmljZV9uYW1lfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPk1ldGhvZHMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7bWV0aG9kbmFtZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Vbml0cyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3t1bml0c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5BZGQtb25zIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2FkZG9uc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QcmljZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twcmljZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQkNCgkJCQkJCQkJCQ0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+TmFtZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tmaXJzdG5hbWV9fSB7e2xhc3RuYW1lfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkVtYWlsIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2NsaWVudF9lbWFpbH19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QaG9uZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twaG9uZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QYXltZW50IDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3BheW1lbnRfbWV0aG9kfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPlZhY2N1bSBDbGVhbmVyIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3ZhY2N1bV9jbGVhbmVyX3N0YXR1c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QYXJraW5nIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3Bhcmtpbmdfc3RhdHVzfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkFkZHJlc3MgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7YWRkcmVzc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Ob3RlcyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tub3Rlc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Db250YWN0IFN0YXR1cyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tjb250YWN0X3N0YXR1c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJwYWRkaW5nOiAxNXB4IDBweDtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjsiPg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDtsaW5lLWhlaWdodDogMjJweDttYXJnaW46IDEwcHggMHB4IDE1cHg7ZmxvYXQ6IGxlZnQ7Ij5oYXMgYmVlbiBjb25maXJtZWQuPC9wPg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMTBweCAwcHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7dGV4dC1hbGlnbjogY2VudGVyOyI+DQoJCQkJCQkJCQk8aDUgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTNweDttYXJnaW46IDBweCAwcHggNXB4OyI+VGhhbmsgeW91PC9oNT4NCgkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJPC9kaXY+DQoJCQkJCQk8L3RkPgkJCQkJDQoJCQkJCTwvdHI+CQkJCQ0KCQkJCTwvdGJvZHk+DQoJCQk8L3RhYmxlPgkNCgkJPC9kaXY+DQoJPC9kaXY+CQ0KPC9ib2R5Pg0KPC9odG1sPg==', 'E', 'C', 'A'),
(6, 'Appointment Approved', '', 'PGh0bWw+DQo8aGVhZD4NCgk8bWV0YSBuYW1lPSJ2aWV3cG9ydCIgY29udGVudD0id2lkdGg9ZGV2aWNlLXdpZHRoLCBpbml0aWFsLXNjYWxlPTEuMCIvPg0KCTxtZXRhIGh0dHAtZXF1aXY9IkNvbnRlbnQtVHlwZSIgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PVVURi04IiAvPg0KCTx0aXRsZT5TdWJqZWN0OiB7e3NlcnZpY2VfbmFtZX19IG9uIHt7Ym9va2luZ19kYXRlfX08L3RpdGxlPg0KCTxsaW5rIGhyZWY9Imh0dHBzOi8vZm9udHMuZ29vZ2xlYXBpcy5jb20vY3NzP2ZhbWlseT1Nb250c2VycmF0IiByZWw9InN0eWxlc2hlZXQiPg0KPC9oZWFkPg0KPGJvZHk+CQkNCgk8ZGl2IHN0eWxlPSJtYXJnaW46IDA7cGFkZGluZzogMDtmb250LWZhbWlseTogSGVsdmV0aWNhIE5ldWUsIEhlbHZldGljYSwgSGVsdmV0aWNhLCBBcmlhbCwgc2Fucy1zZXJpZjtmb250LXNpemU6IDEwMCU7bGluZS1oZWlnaHQ6IDEuNjtib3gtc2l6aW5nOiBib3JkZXItYm94OyI+CQ0KCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBibG9jayAhaW1wb3J0YW50O21heC13aWR0aDogNjAwcHggIWltcG9ydGFudDttYXJnaW46IDAgYXV0byAhaW1wb3J0YW50O2NsZWFyOiBib3RoICFpbXBvcnRhbnQ7Ij4NCgkJCTx0YWJsZSBzdHlsZT0iYm9yZGVyOiAxcHggc29saWQgI2MyYzJjMjt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDttYXJnaW46IDMwcHggMHB4Oy13ZWJraXQtYm9yZGVyLXJhZGl1czogNXB4Oy1tb3otYm9yZGVyLXJhZGl1czogNXB4Oy1vLWJvcmRlci1yYWRpdXM6IDVweDtib3JkZXItcmFkaXVzOiA1cHg7Ij4NCgkJCQk8dGJvZHk+DQoJCQkJCTx0ciBzdHlsZT0iYm9yZGVyLWJvdHRvbTogMXB4IHNvbGlkICNlNmU2ZTY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7Ij4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDU5JTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7dGV4dC1hbGlnbjogbGVmdDtmb250LWZhbWlseTogTW9udHNlcnJhdCwgc2Fucy1zZXJpZjsiPg0KCQkJCQkJCQl7e2NvbXBhbnlfbmFtZX19PGJyIC8+e3tjb21wYW55X2FkZHJlc3N9fTxiciAvPnt7Y29tcGFueV9jaXR5fX0sIHt7Y29tcGFueV9zdGF0ZX19LCB7e2NvbXBhbnlfemlwfX08YnIgLz57e2NvbXBhbnlfY291bnRyeX19PGJyIC8+e3tjb21wYW55X3Bob25lfX08YnIgLz57e2NvbXBhbnlfZW1haWx9fQ0KCQkJCQkJCTwvZGl2Pg0KCQkJCQkJPC90ZD4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDQwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7Ij4NCgkJCQkJCQkJPGRpdiBzdHlsZT0id2lkdGg6IDEzMHB4O2hlaWdodDogMTAwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO21hcmdpbjogMHB4IGF1dG87Ij4NCgkJCQkJCQkJCTxpbWcgc3R5bGU9IndpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0O2Rpc3BsYXk6IGlubGluZS1ibG9jaztoZWlnaHQ6IDEwMCU7IiBzcmM9Int7YnVzaW5lc3NfbG9nb319IiAvPg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQk8L2Rpdj4NCgkJCQkJCTwvdGQ+DQoJCQkJCQkNCgkJCQkJCQ0KCQkJCQk8L3RyPg0KCQkJCQk8dHI+DQoJCQkJCQk8dGQ+DQoJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMjVweCAzMHB4O2JhY2tncm91bmQ6ICNmZmY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDkwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQkJPGg2IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bWFyZ2luOiAxMHB4IDBweCAxMHB4O2ZvbnQtd2VpZ2h0OiA2MDA7Ij5EZWFyIHt7YWRtaW5fbmFtZX19LCA8L2g2Pg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDttYXJnaW46IDEwcHggMHB4IDE1cHg7Ij5UaGUgYXBwb2ludG1lbnQgd2l0aCB7e2NsaWVudF9uYW1lfX0gd2l0aCBmb2xsb3dpbmcgZGV0YWlsczo8L3A+CQkJCQkJCQ0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJPGRpdiBzdHlsZT0iZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7cGFkZGluZzogMTBweCAwcHg7Ij4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPldoZW46IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tib29raW5nX2RhdGV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+Rm9yOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7c2VydmljZV9uYW1lfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPk1ldGhvZHMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7bWV0aG9kbmFtZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Vbml0cyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3t1bml0c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5BZGQtb25zIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2FkZG9uc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QcmljZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twcmljZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQkNCgkJCQkJCQkJCQ0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+TmFtZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tmaXJzdG5hbWV9fSB7e2xhc3RuYW1lfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkVtYWlsIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2NsaWVudF9lbWFpbH19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QaG9uZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twaG9uZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QYXltZW50IDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3BheW1lbnRfbWV0aG9kfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPlZhY2N1bSBDbGVhbmVyIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3ZhY2N1bV9jbGVhbmVyX3N0YXR1c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QYXJraW5nIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3Bhcmtpbmdfc3RhdHVzfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkFkZHJlc3MgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7YWRkcmVzc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Ob3RlcyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tub3Rlc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Db250YWN0IFN0YXR1cyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tjb250YWN0X3N0YXR1c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJwYWRkaW5nOiAxNXB4IDBweDtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjsiPg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDtsaW5lLWhlaWdodDogMjJweDttYXJnaW46IDEwcHggMHB4IDE1cHg7ZmxvYXQ6IGxlZnQ7Ij5oYXMgYmVlbiBjb25maXJtZWQuPC9wPg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMTBweCAwcHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7dGV4dC1hbGlnbjogY2VudGVyOyI+DQoJCQkJCQkJCQk8aDUgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTNweDttYXJnaW46IDBweCAwcHggNXB4OyI+VGhhbmsgeW91PC9oNT4NCgkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJPC9kaXY+DQoJCQkJCQk8L3RkPgkJCQkJDQoJCQkJCTwvdHI+CQkJCQ0KCQkJCTwvdGJvZHk+DQoJCQk8L3RhYmxlPgkNCgkJPC9kaXY+DQoJPC9kaXY+CQ0KPC9ib2R5Pg0KPC9odG1sPg==', 'E', 'C', 'S'),
(7, 'Appointment Rejected', '', 'PGh0bWw+DQo8aGVhZD4NCgk8bWV0YSBuYW1lPSJ2aWV3cG9ydCIgY29udGVudD0id2lkdGg9ZGV2aWNlLXdpZHRoLCBpbml0aWFsLXNjYWxlPTEuMCIvPg0KCTxtZXRhIGh0dHAtZXF1aXY9IkNvbnRlbnQtVHlwZSIgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PVVURi04IiAvPg0KCTx0aXRsZT5TdWJqZWN0OiB7e3NlcnZpY2VfbmFtZX19IG9uIHt7Ym9va2luZ19kYXRlfX08L3RpdGxlPg0KCTxsaW5rIGhyZWY9Imh0dHBzOi8vZm9udHMuZ29vZ2xlYXBpcy5jb20vY3NzP2ZhbWlseT1Nb250c2VycmF0IiByZWw9InN0eWxlc2hlZXQiPg0KPC9oZWFkPg0KPGJvZHk+CQkNCgk8ZGl2IHN0eWxlPSJtYXJnaW46IDA7cGFkZGluZzogMDtmb250LWZhbWlseTogSGVsdmV0aWNhIE5ldWUsIEhlbHZldGljYSwgSGVsdmV0aWNhLCBBcmlhbCwgc2Fucy1zZXJpZjtmb250LXNpemU6IDEwMCU7bGluZS1oZWlnaHQ6IDEuNjtib3gtc2l6aW5nOiBib3JkZXItYm94OyI+CQ0KCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBibG9jayAhaW1wb3J0YW50O21heC13aWR0aDogNjAwcHggIWltcG9ydGFudDttYXJnaW46IDAgYXV0byAhaW1wb3J0YW50O2NsZWFyOiBib3RoICFpbXBvcnRhbnQ7Ij4NCgkJCTx0YWJsZSBzdHlsZT0iYm9yZGVyOiAxcHggc29saWQgI2MyYzJjMjt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDttYXJnaW46IDMwcHggMHB4Oy13ZWJraXQtYm9yZGVyLXJhZGl1czogNXB4Oy1tb3otYm9yZGVyLXJhZGl1czogNXB4Oy1vLWJvcmRlci1yYWRpdXM6IDVweDtib3JkZXItcmFkaXVzOiA1cHg7Ij4NCgkJCQk8dGJvZHk+DQoJCQkJCTx0ciBzdHlsZT0iYm9yZGVyLWJvdHRvbTogMXB4IHNvbGlkICNlNmU2ZTY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7Ij4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDU5JTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7dGV4dC1hbGlnbjogbGVmdDtmb250LWZhbWlseTogTW9udHNlcnJhdCwgc2Fucy1zZXJpZjsiPg0KCQkJCQkJCQl7e2NvbXBhbnlfbmFtZX19PGJyIC8+e3tjb21wYW55X2FkZHJlc3N9fTxiciAvPnt7Y29tcGFueV9jaXR5fX0sIHt7Y29tcGFueV9zdGF0ZX19LCB7e2NvbXBhbnlfemlwfX08YnIgLz57e2NvbXBhbnlfY291bnRyeX19PGJyIC8+e3tjb21wYW55X3Bob25lfX08YnIgLz57e2NvbXBhbnlfZW1haWx9fQ0KCQkJCQkJCTwvZGl2Pg0KCQkJCQkJPC90ZD4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDQwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7Ij4NCgkJCQkJCQkJPGRpdiBzdHlsZT0id2lkdGg6IDEzMHB4O2hlaWdodDogMTAwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO21hcmdpbjogMHB4IGF1dG87Ij4NCgkJCQkJCQkJCTxpbWcgc3R5bGU9IndpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0O2Rpc3BsYXk6IGlubGluZS1ibG9jaztoZWlnaHQ6IDEwMCU7IiBzcmM9Int7YnVzaW5lc3NfbG9nb319IiAvPg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQk8L2Rpdj4NCgkJCQkJCTwvdGQ+DQoJCQkJCQkNCgkJCQkJCQ0KCQkJCQk8L3RyPg0KCQkJCQk8dHI+DQoJCQkJCQk8dGQ+DQoJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMjVweCAzMHB4O2JhY2tncm91bmQ6ICNmZmY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDkwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQkJPGg2IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bWFyZ2luOiAxMHB4IDBweCAxMHB4O2ZvbnQtd2VpZ2h0OiA2MDA7Ij5EZWFyIHt7Y2xpZW50X25hbWV9fSw8L2g2Pg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDttYXJnaW46IDEwcHggMHB4IDE1cHg7Ij5Zb3VyIGFwcG9pbnRtZW50IHdpdGggZm9sbG93aW5nIGRldGFpbHM6PC9wPgkJCQkJCQkNCgkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCTxkaXYgc3R5bGU9ImZsb2F0OiBsZWZ0O3dpZHRoOiAxMDAlO2Rpc3BsYXk6IGJsb2NrO3BhZGRpbmc6IDEwcHggMHB4OyI+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5XaGVuOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7Ym9va2luZ19kYXRlfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkZvcjogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3NlcnZpY2VfbmFtZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5NZXRob2RzIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e21ldGhvZG5hbWV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+VW5pdHMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7dW5pdHN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+QWRkLW9ucyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3thZGRvbnN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UHJpY2UgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7cHJpY2V9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJDQoJCQkJCQkJCQkNCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPk5hbWUgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7Zmlyc3RuYW1lfX0ge3tsYXN0bmFtZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5FbWFpbCA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tjbGllbnRfZW1haWx9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UGhvbmUgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7cGhvbmV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UGF5bWVudCA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twYXltZW50X21ldGhvZH19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5WYWNjdW0gQ2xlYW5lciA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3t2YWNjdW1fY2xlYW5lcl9zdGF0dXN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UGFya2luZyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twYXJraW5nX3N0YXR1c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5BZGRyZXNzIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2FkZHJlc3N9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+Tm90ZXMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7bm90ZXN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+Q29udGFjdCBTdGF0dXMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7Y29udGFjdF9zdGF0dXN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMTVweCAwcHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7Ym9yZGVyLWJvdHRvbTogMXB4IHNvbGlkICNlNmU2ZTY7Ij4NCgkJCQkJCQkJCTxwIHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bGluZS1oZWlnaHQ6IDIycHg7bWFyZ2luOiAxMHB4IDBweCAxNXB4O2Zsb2F0OiBsZWZ0OyI+aGFzIGJlZW4gcmVqZWN0ZWQuPC9wPg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMTBweCAwcHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7dGV4dC1hbGlnbjogY2VudGVyOyI+DQoJCQkJCQkJCQk8aDUgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTNweDttYXJnaW46IDBweCAwcHggNXB4OyI+VGhhbmsgeW91PC9oNT4NCgkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJPC9kaXY+DQoJCQkJCQk8L3RkPgkJCQkJDQoJCQkJCTwvdHI+CQkJCQ0KCQkJCTwvdGJvZHk+DQoJCQk8L3RhYmxlPgkNCgkJPC9kaXY+DQoJPC9kaXY+CQ0KPC9ib2R5Pg0KPC9odG1sPg==', 'E', 'R', 'C'),
(8, 'Appointment Rejected', '', 'PGh0bWw+DQo8aGVhZD4NCgk8bWV0YSBuYW1lPSJ2aWV3cG9ydCIgY29udGVudD0id2lkdGg9ZGV2aWNlLXdpZHRoLCBpbml0aWFsLXNjYWxlPTEuMCIvPg0KCTxtZXRhIGh0dHAtZXF1aXY9IkNvbnRlbnQtVHlwZSIgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PVVURi04IiAvPg0KCTx0aXRsZT5TdWJqZWN0OiB7e3NlcnZpY2VfbmFtZX19IG9uIHt7Ym9va2luZ19kYXRlfX08L3RpdGxlPg0KCTxsaW5rIGhyZWY9Imh0dHBzOi8vZm9udHMuZ29vZ2xlYXBpcy5jb20vY3NzP2ZhbWlseT1Nb250c2VycmF0IiByZWw9InN0eWxlc2hlZXQiPg0KPC9oZWFkPg0KPGJvZHk+CQkNCgk8ZGl2IHN0eWxlPSJtYXJnaW46IDA7cGFkZGluZzogMDtmb250LWZhbWlseTogSGVsdmV0aWNhIE5ldWUsIEhlbHZldGljYSwgSGVsdmV0aWNhLCBBcmlhbCwgc2Fucy1zZXJpZjtmb250LXNpemU6IDEwMCU7bGluZS1oZWlnaHQ6IDEuNjtib3gtc2l6aW5nOiBib3JkZXItYm94OyI+CQ0KCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBibG9jayAhaW1wb3J0YW50O21heC13aWR0aDogNjAwcHggIWltcG9ydGFudDttYXJnaW46IDAgYXV0byAhaW1wb3J0YW50O2NsZWFyOiBib3RoICFpbXBvcnRhbnQ7Ij4NCgkJCTx0YWJsZSBzdHlsZT0iYm9yZGVyOiAxcHggc29saWQgI2MyYzJjMjt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDttYXJnaW46IDMwcHggMHB4Oy13ZWJraXQtYm9yZGVyLXJhZGl1czogNXB4Oy1tb3otYm9yZGVyLXJhZGl1czogNXB4Oy1vLWJvcmRlci1yYWRpdXM6IDVweDtib3JkZXItcmFkaXVzOiA1cHg7Ij4NCgkJCQk8dGJvZHk+DQoJCQkJCTx0ciBzdHlsZT0iYm9yZGVyLWJvdHRvbTogMXB4IHNvbGlkICNlNmU2ZTY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7Ij4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDU5JTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7dGV4dC1hbGlnbjogbGVmdDtmb250LWZhbWlseTogTW9udHNlcnJhdCwgc2Fucy1zZXJpZjsiPg0KCQkJCQkJCQl7e2NvbXBhbnlfbmFtZX19PGJyIC8+e3tjb21wYW55X2FkZHJlc3N9fTxiciAvPnt7Y29tcGFueV9jaXR5fX0sIHt7Y29tcGFueV9zdGF0ZX19LCB7e2NvbXBhbnlfemlwfX08YnIgLz57e2NvbXBhbnlfY291bnRyeX19PGJyIC8+e3tjb21wYW55X3Bob25lfX08YnIgLz57e2NvbXBhbnlfZW1haWx9fQ0KCQkJCQkJCTwvZGl2Pg0KCQkJCQkJPC90ZD4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDQwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7Ij4NCgkJCQkJCQkJPGRpdiBzdHlsZT0id2lkdGg6IDEzMHB4O2hlaWdodDogMTAwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO21hcmdpbjogMHB4IGF1dG87Ij4NCgkJCQkJCQkJCTxpbWcgc3R5bGU9IndpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0O2Rpc3BsYXk6IGlubGluZS1ibG9jaztoZWlnaHQ6IDEwMCU7IiBzcmM9Int7YnVzaW5lc3NfbG9nb319IiAvPg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQk8L2Rpdj4NCgkJCQkJCTwvdGQ+DQoJCQkJCQkNCgkJCQkJCQ0KCQkJCQk8L3RyPg0KCQkJCQk8dHI+DQoJCQkJCQk8dGQ+DQoJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMjVweCAzMHB4O2JhY2tncm91bmQ6ICNmZmY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDkwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQkJPGg2IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bWFyZ2luOiAxMHB4IDBweCAxMHB4O2ZvbnQtd2VpZ2h0OiA2MDA7Ij5EZWFyIHt7YWRtaW5fbmFtZX19LCA8L2g2Pg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDttYXJnaW46IDEwcHggMHB4IDE1cHg7Ij5UaGUgYXBwb2ludG1lbnQgd2l0aCB7e2NsaWVudF9uYW1lfX0gd2l0aCBmb2xsb3dpbmcgZGV0YWlsczo8L3A+CQkJCQkJCQ0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJPGRpdiBzdHlsZT0iZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7cGFkZGluZzogMTBweCAwcHg7Ij4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPldoZW46IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tib29raW5nX2RhdGV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+Rm9yOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7c2VydmljZV9uYW1lfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPk1ldGhvZHMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7bWV0aG9kbmFtZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Vbml0cyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3t1bml0c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5BZGQtb25zIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2FkZG9uc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QcmljZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twcmljZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQkNCgkJCQkJCQkJCQ0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+TmFtZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tmaXJzdG5hbWV9fSB7e2xhc3RuYW1lfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkVtYWlsIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2NsaWVudF9lbWFpbH19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QaG9uZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twaG9uZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QYXltZW50IDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3BheW1lbnRfbWV0aG9kfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPlZhY2N1bSBDbGVhbmVyIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3ZhY2N1bV9jbGVhbmVyX3N0YXR1c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QYXJraW5nIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3Bhcmtpbmdfc3RhdHVzfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkFkZHJlc3MgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7YWRkcmVzc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Ob3RlcyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tub3Rlc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Db250YWN0IFN0YXR1cyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tjb250YWN0X3N0YXR1c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJwYWRkaW5nOiAxNXB4IDBweDtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjsiPg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDtsaW5lLWhlaWdodDogMjJweDttYXJnaW46IDEwcHggMHB4IDE1cHg7ZmxvYXQ6IGxlZnQ7Ij5oYXMgYmVlbiByZWplY3RlZC48L3A+DQoJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJwYWRkaW5nOiAxMHB4IDBweDtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jazt0ZXh0LWFsaWduOiBjZW50ZXI7Ij4NCgkJCQkJCQkJCTxoNSBzdHlsZT0iY29sb3I6ICM2MDYwNjA7Zm9udC1zaXplOiAxM3B4O21hcmdpbjogMHB4IDBweCA1cHg7Ij5UaGFuayB5b3U8L2g1Pg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQk8L2Rpdj4NCgkJCQkJCTwvdGQ+CQkJCQkNCgkJCQkJPC90cj4JCQkJDQoJCQkJPC90Ym9keT4NCgkJCTwvdGFibGU+CQ0KCQk8L2Rpdj4NCgk8L2Rpdj4JDQo8L2JvZHk+DQo8L2h0bWw+', 'E', 'R', 'A'),
(9, 'Appointment Rejected', '', 'PGh0bWw+DQo8aGVhZD4NCgk8bWV0YSBuYW1lPSJ2aWV3cG9ydCIgY29udGVudD0id2lkdGg9ZGV2aWNlLXdpZHRoLCBpbml0aWFsLXNjYWxlPTEuMCIvPg0KCTxtZXRhIGh0dHAtZXF1aXY9IkNvbnRlbnQtVHlwZSIgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PVVURi04IiAvPg0KCTx0aXRsZT5TdWJqZWN0OiB7e3NlcnZpY2VfbmFtZX19IG9uIHt7Ym9va2luZ19kYXRlfX08L3RpdGxlPg0KCTxsaW5rIGhyZWY9Imh0dHBzOi8vZm9udHMuZ29vZ2xlYXBpcy5jb20vY3NzP2ZhbWlseT1Nb250c2VycmF0IiByZWw9InN0eWxlc2hlZXQiPg0KPC9oZWFkPg0KPGJvZHk+CQkNCgk8ZGl2IHN0eWxlPSJtYXJnaW46IDA7cGFkZGluZzogMDtmb250LWZhbWlseTogSGVsdmV0aWNhIE5ldWUsIEhlbHZldGljYSwgSGVsdmV0aWNhLCBBcmlhbCwgc2Fucy1zZXJpZjtmb250LXNpemU6IDEwMCU7bGluZS1oZWlnaHQ6IDEuNjtib3gtc2l6aW5nOiBib3JkZXItYm94OyI+CQ0KCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBibG9jayAhaW1wb3J0YW50O21heC13aWR0aDogNjAwcHggIWltcG9ydGFudDttYXJnaW46IDAgYXV0byAhaW1wb3J0YW50O2NsZWFyOiBib3RoICFpbXBvcnRhbnQ7Ij4NCgkJCTx0YWJsZSBzdHlsZT0iYm9yZGVyOiAxcHggc29saWQgI2MyYzJjMjt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDttYXJnaW46IDMwcHggMHB4Oy13ZWJraXQtYm9yZGVyLXJhZGl1czogNXB4Oy1tb3otYm9yZGVyLXJhZGl1czogNXB4Oy1vLWJvcmRlci1yYWRpdXM6IDVweDtib3JkZXItcmFkaXVzOiA1cHg7Ij4NCgkJCQk8dGJvZHk+DQoJCQkJCTx0ciBzdHlsZT0iYm9yZGVyLWJvdHRvbTogMXB4IHNvbGlkICNlNmU2ZTY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7Ij4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDU5JTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7dGV4dC1hbGlnbjogbGVmdDtmb250LWZhbWlseTogTW9udHNlcnJhdCwgc2Fucy1zZXJpZjsiPg0KCQkJCQkJCQl7e2NvbXBhbnlfbmFtZX19PGJyIC8+e3tjb21wYW55X2FkZHJlc3N9fTxiciAvPnt7Y29tcGFueV9jaXR5fX0sIHt7Y29tcGFueV9zdGF0ZX19LCB7e2NvbXBhbnlfemlwfX08YnIgLz57e2NvbXBhbnlfY291bnRyeX19PGJyIC8+e3tjb21wYW55X3Bob25lfX08YnIgLz57e2NvbXBhbnlfZW1haWx9fQ0KCQkJCQkJCTwvZGl2Pg0KCQkJCQkJPC90ZD4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDQwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7Ij4NCgkJCQkJCQkJPGRpdiBzdHlsZT0id2lkdGg6IDEzMHB4O2hlaWdodDogMTAwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO21hcmdpbjogMHB4IGF1dG87Ij4NCgkJCQkJCQkJCTxpbWcgc3R5bGU9IndpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0O2Rpc3BsYXk6IGlubGluZS1ibG9jaztoZWlnaHQ6IDEwMCU7IiBzcmM9Int7YnVzaW5lc3NfbG9nb319IiAvPg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQk8L2Rpdj4NCgkJCQkJCTwvdGQ+DQoJCQkJCQkNCgkJCQkJCQ0KCQkJCQk8L3RyPg0KCQkJCQk8dHI+DQoJCQkJCQk8dGQ+DQoJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMjVweCAzMHB4O2JhY2tncm91bmQ6ICNmZmY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDkwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQkJPGg2IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bWFyZ2luOiAxMHB4IDBweCAxMHB4O2ZvbnQtd2VpZ2h0OiA2MDA7Ij5EZWFyIHt7YWRtaW5fbmFtZX19LCA8L2g2Pg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDttYXJnaW46IDEwcHggMHB4IDE1cHg7Ij5UaGUgYXBwb2ludG1lbnQgd2l0aCB7e2NsaWVudF9uYW1lfX0gd2l0aCBmb2xsb3dpbmcgZGV0YWlsczo8L3A+CQkJCQkJCQ0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJPGRpdiBzdHlsZT0iZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7cGFkZGluZzogMTBweCAwcHg7Ij4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPldoZW46IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tib29raW5nX2RhdGV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+Rm9yOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7c2VydmljZV9uYW1lfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPk1ldGhvZHMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7bWV0aG9kbmFtZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Vbml0cyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3t1bml0c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5BZGQtb25zIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2FkZG9uc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QcmljZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twcmljZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQkNCgkJCQkJCQkJCQ0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+TmFtZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tmaXJzdG5hbWV9fSB7e2xhc3RuYW1lfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkVtYWlsIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2NsaWVudF9lbWFpbH19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QaG9uZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twaG9uZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QYXltZW50IDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3BheW1lbnRfbWV0aG9kfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPlZhY2N1bSBDbGVhbmVyIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3ZhY2N1bV9jbGVhbmVyX3N0YXR1c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QYXJraW5nIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3Bhcmtpbmdfc3RhdHVzfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkFkZHJlc3MgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7YWRkcmVzc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Ob3RlcyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tub3Rlc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Db250YWN0IFN0YXR1cyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tjb250YWN0X3N0YXR1c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJwYWRkaW5nOiAxNXB4IDBweDtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjsiPg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDtsaW5lLWhlaWdodDogMjJweDttYXJnaW46IDEwcHggMHB4IDE1cHg7ZmxvYXQ6IGxlZnQ7Ij5oYXMgYmVlbiByZWplY3RlZC48L3A+DQoJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJwYWRkaW5nOiAxMHB4IDBweDtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jazt0ZXh0LWFsaWduOiBjZW50ZXI7Ij4NCgkJCQkJCQkJCTxoNSBzdHlsZT0iY29sb3I6ICM2MDYwNjA7Zm9udC1zaXplOiAxM3B4O21hcmdpbjogMHB4IDBweCA1cHg7Ij5UaGFuayB5b3U8L2g1Pg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQk8L2Rpdj4NCgkJCQkJCTwvdGQ+CQkJCQkNCgkJCQkJPC90cj4JCQkJDQoJCQkJPC90Ym9keT4NCgkJCTwvdGFibGU+CQ0KCQk8L2Rpdj4NCgk8L2Rpdj4JDQo8L2JvZHk+DQo8L2h0bWw+', 'E', 'R', 'S'),
(10, 'Appointment Cancelled by you', '', 'PGh0bWw+DQo8aGVhZD4NCgk8bWV0YSBuYW1lPSJ2aWV3cG9ydCIgY29udGVudD0id2lkdGg9ZGV2aWNlLXdpZHRoLCBpbml0aWFsLXNjYWxlPTEuMCIvPg0KCTxtZXRhIGh0dHAtZXF1aXY9IkNvbnRlbnQtVHlwZSIgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PVVURi04IiAvPg0KCTx0aXRsZT5TdWJqZWN0OiB7e3NlcnZpY2VfbmFtZX19IG9uIHt7Ym9va2luZ19kYXRlfX08L3RpdGxlPg0KCTxsaW5rIGhyZWY9Imh0dHBzOi8vZm9udHMuZ29vZ2xlYXBpcy5jb20vY3NzP2ZhbWlseT1Nb250c2VycmF0IiByZWw9InN0eWxlc2hlZXQiPg0KPC9oZWFkPg0KPGJvZHk+CQkNCgk8ZGl2IHN0eWxlPSJtYXJnaW46IDA7cGFkZGluZzogMDtmb250LWZhbWlseTogSGVsdmV0aWNhIE5ldWUsIEhlbHZldGljYSwgSGVsdmV0aWNhLCBBcmlhbCwgc2Fucy1zZXJpZjtmb250LXNpemU6IDEwMCU7bGluZS1oZWlnaHQ6IDEuNjtib3gtc2l6aW5nOiBib3JkZXItYm94OyI+CQ0KCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBibG9jayAhaW1wb3J0YW50O21heC13aWR0aDogNjAwcHggIWltcG9ydGFudDttYXJnaW46IDAgYXV0byAhaW1wb3J0YW50O2NsZWFyOiBib3RoICFpbXBvcnRhbnQ7Ij4NCgkJCTx0YWJsZSBzdHlsZT0iYm9yZGVyOiAxcHggc29saWQgI2MyYzJjMjt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDttYXJnaW46IDMwcHggMHB4Oy13ZWJraXQtYm9yZGVyLXJhZGl1czogNXB4Oy1tb3otYm9yZGVyLXJhZGl1czogNXB4Oy1vLWJvcmRlci1yYWRpdXM6IDVweDtib3JkZXItcmFkaXVzOiA1cHg7Ij4NCgkJCQk8dGJvZHk+DQoJCQkJCTx0ciBzdHlsZT0iYm9yZGVyLWJvdHRvbTogMXB4IHNvbGlkICNlNmU2ZTY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7Ij4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDU5JTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7dGV4dC1hbGlnbjogbGVmdDtmb250LWZhbWlseTogTW9udHNlcnJhdCwgc2Fucy1zZXJpZjsiPg0KCQkJCQkJCQl7e2NvbXBhbnlfbmFtZX19PGJyIC8+e3tjb21wYW55X2FkZHJlc3N9fTxiciAvPnt7Y29tcGFueV9jaXR5fX0sIHt7Y29tcGFueV9zdGF0ZX19LCB7e2NvbXBhbnlfemlwfX08YnIgLz57e2NvbXBhbnlfY291bnRyeX19PGJyIC8+e3tjb21wYW55X3Bob25lfX08YnIgLz57e2NvbXBhbnlfZW1haWx9fQ0KCQkJCQkJCTwvZGl2Pg0KCQkJCQkJPC90ZD4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDQwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7Ij4NCgkJCQkJCQkJPGRpdiBzdHlsZT0id2lkdGg6IDEzMHB4O2hlaWdodDogMTAwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO21hcmdpbjogMHB4IGF1dG87Ij4NCgkJCQkJCQkJCTxpbWcgc3R5bGU9IndpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0O2Rpc3BsYXk6IGlubGluZS1ibG9jaztoZWlnaHQ6IDEwMCU7IiBzcmM9Int7YnVzaW5lc3NfbG9nb319IiAvPg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQk8L2Rpdj4NCgkJCQkJCTwvdGQ+DQoJCQkJCQkNCgkJCQkJCQ0KCQkJCQk8L3RyPg0KCQkJCQk8dHI+DQoJCQkJCQk8dGQ+DQoJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMjVweCAzMHB4O2JhY2tncm91bmQ6ICNmZmY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDkwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQkJPGg2IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bWFyZ2luOiAxMHB4IDBweCAxMHB4O2ZvbnQtd2VpZ2h0OiA2MDA7Ij5EZWFyIHt7Y2xpZW50X25hbWV9fSw8L2g2Pg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDttYXJnaW46IDEwcHggMHB4IDE1cHg7Ij5Zb3VyIGFwcG9pbnRtZW50IHdpdGggZm9sbG93aW5nIGRldGFpbHM6PC9wPgkJCQkJCQkNCgkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCTxkaXYgc3R5bGU9ImZsb2F0OiBsZWZ0O3dpZHRoOiAxMDAlO2Rpc3BsYXk6IGJsb2NrO3BhZGRpbmc6IDEwcHggMHB4OyI+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5XaGVuOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7Ym9va2luZ19kYXRlfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkZvcjogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3NlcnZpY2VfbmFtZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5NZXRob2RzIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e21ldGhvZG5hbWV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+VW5pdHMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7dW5pdHN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+QWRkLW9ucyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3thZGRvbnN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UHJpY2UgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7cHJpY2V9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJDQoJCQkJCQkJCQkNCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPk5hbWUgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7Zmlyc3RuYW1lfX0ge3tsYXN0bmFtZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5FbWFpbCA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tjbGllbnRfZW1haWx9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UGhvbmUgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7cGhvbmV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UGF5bWVudCA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twYXltZW50X21ldGhvZH19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5WYWNjdW0gQ2xlYW5lciA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3t2YWNjdW1fY2xlYW5lcl9zdGF0dXN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UGFya2luZyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twYXJraW5nX3N0YXR1c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5BZGRyZXNzIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2FkZHJlc3N9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+Tm90ZXMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7bm90ZXN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+Q29udGFjdCBTdGF0dXMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7Y29udGFjdF9zdGF0dXN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMTVweCAwcHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7Ym9yZGVyLWJvdHRvbTogMXB4IHNvbGlkICNlNmU2ZTY7Ij4NCgkJCQkJCQkJCTxwIHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bGluZS1oZWlnaHQ6IDIycHg7bWFyZ2luOiAxMHB4IDBweCAxNXB4O2Zsb2F0OiBsZWZ0OyI+aGFzIGJlZW4gY2FuY2VsbGVkLjwvcD4NCgkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCTxkaXYgc3R5bGU9InBhZGRpbmc6IDEwcHggMHB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiAxMDAlO2Rpc3BsYXk6IGJsb2NrO3RleHQtYWxpZ246IGNlbnRlcjsiPg0KCQkJCQkJCQkJPGg1IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDEzcHg7bWFyZ2luOiAwcHggMHB4IDVweDsiPlRoYW5rIHlvdTwvaDU+DQoJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCTwvZGl2Pg0KCQkJCQkJPC90ZD4JCQkJCQ0KCQkJCQk8L3RyPgkJCQkNCgkJCQk8L3Rib2R5Pg0KCQkJPC90YWJsZT4JDQoJCTwvZGl2Pg0KCTwvZGl2PgkNCjwvYm9keT4NCjwvaHRtbD4=', 'E', 'CC', 'C'),
(11, 'Appointment Cancelled By Customer', '', 'PGh0bWw+DQo8aGVhZD4NCgk8bWV0YSBuYW1lPSJ2aWV3cG9ydCIgY29udGVudD0id2lkdGg9ZGV2aWNlLXdpZHRoLCBpbml0aWFsLXNjYWxlPTEuMCIvPg0KCTxtZXRhIGh0dHAtZXF1aXY9IkNvbnRlbnQtVHlwZSIgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PVVURi04IiAvPg0KCTx0aXRsZT5TdWJqZWN0OiB7e3NlcnZpY2VfbmFtZX19IG9uIHt7Ym9va2luZ19kYXRlfX08L3RpdGxlPg0KCTxsaW5rIGhyZWY9Imh0dHBzOi8vZm9udHMuZ29vZ2xlYXBpcy5jb20vY3NzP2ZhbWlseT1Nb250c2VycmF0IiByZWw9InN0eWxlc2hlZXQiPg0KPC9oZWFkPg0KPGJvZHk+CQkNCgk8ZGl2IHN0eWxlPSJtYXJnaW46IDA7cGFkZGluZzogMDtmb250LWZhbWlseTogSGVsdmV0aWNhIE5ldWUsIEhlbHZldGljYSwgSGVsdmV0aWNhLCBBcmlhbCwgc2Fucy1zZXJpZjtmb250LXNpemU6IDEwMCU7bGluZS1oZWlnaHQ6IDEuNjtib3gtc2l6aW5nOiBib3JkZXItYm94OyI+CQ0KCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBibG9jayAhaW1wb3J0YW50O21heC13aWR0aDogNjAwcHggIWltcG9ydGFudDttYXJnaW46IDAgYXV0byAhaW1wb3J0YW50O2NsZWFyOiBib3RoICFpbXBvcnRhbnQ7Ij4NCgkJCTx0YWJsZSBzdHlsZT0iYm9yZGVyOiAxcHggc29saWQgI2MyYzJjMjt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDttYXJnaW46IDMwcHggMHB4Oy13ZWJraXQtYm9yZGVyLXJhZGl1czogNXB4Oy1tb3otYm9yZGVyLXJhZGl1czogNXB4Oy1vLWJvcmRlci1yYWRpdXM6IDVweDtib3JkZXItcmFkaXVzOiA1cHg7Ij4NCgkJCQk8dGJvZHk+DQoJCQkJCTx0ciBzdHlsZT0iYm9yZGVyLWJvdHRvbTogMXB4IHNvbGlkICNlNmU2ZTY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7Ij4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDU5JTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7dGV4dC1hbGlnbjogbGVmdDtmb250LWZhbWlseTogTW9udHNlcnJhdCwgc2Fucy1zZXJpZjsiPg0KCQkJCQkJCQl7e2NvbXBhbnlfbmFtZX19PGJyIC8+e3tjb21wYW55X2FkZHJlc3N9fTxiciAvPnt7Y29tcGFueV9jaXR5fX0sIHt7Y29tcGFueV9zdGF0ZX19LCB7e2NvbXBhbnlfemlwfX08YnIgLz57e2NvbXBhbnlfY291bnRyeX19PGJyIC8+e3tjb21wYW55X3Bob25lfX08YnIgLz57e2NvbXBhbnlfZW1haWx9fQ0KCQkJCQkJCTwvZGl2Pg0KCQkJCQkJPC90ZD4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDQwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7Ij4NCgkJCQkJCQkJPGRpdiBzdHlsZT0id2lkdGg6IDEzMHB4O2hlaWdodDogMTAwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO21hcmdpbjogMHB4IGF1dG87Ij4NCgkJCQkJCQkJCTxpbWcgc3R5bGU9IndpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0O2Rpc3BsYXk6IGlubGluZS1ibG9jaztoZWlnaHQ6IDEwMCU7IiBzcmM9Int7YnVzaW5lc3NfbG9nb319IiAvPg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQk8L2Rpdj4NCgkJCQkJCTwvdGQ+DQoJCQkJCQkNCgkJCQkJCQ0KCQkJCQk8L3RyPg0KCQkJCQk8dHI+DQoJCQkJCQk8dGQ+DQoJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMjVweCAzMHB4O2JhY2tncm91bmQ6ICNmZmY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDkwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQkJPGg2IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bWFyZ2luOiAxMHB4IDBweCAxMHB4O2ZvbnQtd2VpZ2h0OiA2MDA7Ij5EZWFyIHt7YWRtaW5fbmFtZX19LCA8L2g2Pg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDttYXJnaW46IDEwcHggMHB4IDE1cHg7Ij5UaGUgYXBwb2ludG1lbnQgd2l0aCB7e2NsaWVudF9uYW1lfX0gd2l0aCBmb2xsb3dpbmcgZGV0YWlsczo8L3A+CQkJCQkJCQ0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJPGRpdiBzdHlsZT0iZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7cGFkZGluZzogMTBweCAwcHg7Ij4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPldoZW46IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tib29raW5nX2RhdGV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+Rm9yOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7c2VydmljZV9uYW1lfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPk1ldGhvZHMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7bWV0aG9kbmFtZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Vbml0cyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3t1bml0c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5BZGQtb25zIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2FkZG9uc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QcmljZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twcmljZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQkNCgkJCQkJCQkJCQ0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+TmFtZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tmaXJzdG5hbWV9fSB7e2xhc3RuYW1lfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkVtYWlsIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2NsaWVudF9lbWFpbH19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QaG9uZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twaG9uZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QYXltZW50IDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3BheW1lbnRfbWV0aG9kfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPlZhY2N1bSBDbGVhbmVyIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3ZhY2N1bV9jbGVhbmVyX3N0YXR1c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QYXJraW5nIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3Bhcmtpbmdfc3RhdHVzfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkFkZHJlc3MgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7YWRkcmVzc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Ob3RlcyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tub3Rlc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Db250YWN0IFN0YXR1cyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tjb250YWN0X3N0YXR1c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJwYWRkaW5nOiAxNXB4IDBweDtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjsiPg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDtsaW5lLWhlaWdodDogMjJweDttYXJnaW46IDEwcHggMHB4IDE1cHg7ZmxvYXQ6IGxlZnQ7Ij5oYXMgYmVlbiBjYW5jZWxsZWQuPC9wPg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMTBweCAwcHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7dGV4dC1hbGlnbjogY2VudGVyOyI+DQoJCQkJCQkJCQk8aDUgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTNweDttYXJnaW46IDBweCAwcHggNXB4OyI+VGhhbmsgeW91PC9oNT4NCgkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJPC9kaXY+DQoJCQkJCQk8L3RkPgkJCQkJDQoJCQkJCTwvdHI+CQkJCQ0KCQkJCTwvdGJvZHk+DQoJCQk8L3RhYmxlPgkNCgkJPC9kaXY+DQoJPC9kaXY+CQ0KPC9ib2R5Pg0KPC9odG1sPg==', 'E', 'CC', 'A'),
(12, 'Appointment Cancelled By Customer', '', 'PGh0bWw+DQo8aGVhZD4NCgk8bWV0YSBuYW1lPSJ2aWV3cG9ydCIgY29udGVudD0id2lkdGg9ZGV2aWNlLXdpZHRoLCBpbml0aWFsLXNjYWxlPTEuMCIvPg0KCTxtZXRhIGh0dHAtZXF1aXY9IkNvbnRlbnQtVHlwZSIgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PVVURi04IiAvPg0KCTx0aXRsZT5TdWJqZWN0OiB7e3NlcnZpY2VfbmFtZX19IG9uIHt7Ym9va2luZ19kYXRlfX08L3RpdGxlPg0KCTxsaW5rIGhyZWY9Imh0dHBzOi8vZm9udHMuZ29vZ2xlYXBpcy5jb20vY3NzP2ZhbWlseT1Nb250c2VycmF0IiByZWw9InN0eWxlc2hlZXQiPg0KPC9oZWFkPg0KPGJvZHk+CQkNCgk8ZGl2IHN0eWxlPSJtYXJnaW46IDA7cGFkZGluZzogMDtmb250LWZhbWlseTogSGVsdmV0aWNhIE5ldWUsIEhlbHZldGljYSwgSGVsdmV0aWNhLCBBcmlhbCwgc2Fucy1zZXJpZjtmb250LXNpemU6IDEwMCU7bGluZS1oZWlnaHQ6IDEuNjtib3gtc2l6aW5nOiBib3JkZXItYm94OyI+CQ0KCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBibG9jayAhaW1wb3J0YW50O21heC13aWR0aDogNjAwcHggIWltcG9ydGFudDttYXJnaW46IDAgYXV0byAhaW1wb3J0YW50O2NsZWFyOiBib3RoICFpbXBvcnRhbnQ7Ij4NCgkJCTx0YWJsZSBzdHlsZT0iYm9yZGVyOiAxcHggc29saWQgI2MyYzJjMjt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDttYXJnaW46IDMwcHggMHB4Oy13ZWJraXQtYm9yZGVyLXJhZGl1czogNXB4Oy1tb3otYm9yZGVyLXJhZGl1czogNXB4Oy1vLWJvcmRlci1yYWRpdXM6IDVweDtib3JkZXItcmFkaXVzOiA1cHg7Ij4NCgkJCQk8dGJvZHk+DQoJCQkJCTx0ciBzdHlsZT0iYm9yZGVyLWJvdHRvbTogMXB4IHNvbGlkICNlNmU2ZTY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7Ij4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDU5JTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7dGV4dC1hbGlnbjogbGVmdDtmb250LWZhbWlseTogTW9udHNlcnJhdCwgc2Fucy1zZXJpZjsiPg0KCQkJCQkJCQl7e2NvbXBhbnlfbmFtZX19PGJyIC8+e3tjb21wYW55X2FkZHJlc3N9fTxiciAvPnt7Y29tcGFueV9jaXR5fX0sIHt7Y29tcGFueV9zdGF0ZX19LCB7e2NvbXBhbnlfemlwfX08YnIgLz57e2NvbXBhbnlfY291bnRyeX19PGJyIC8+e3tjb21wYW55X3Bob25lfX08YnIgLz57e2NvbXBhbnlfZW1haWx9fQ0KCQkJCQkJCTwvZGl2Pg0KCQkJCQkJPC90ZD4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDQwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7Ij4NCgkJCQkJCQkJPGRpdiBzdHlsZT0id2lkdGg6IDEzMHB4O2hlaWdodDogMTAwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO21hcmdpbjogMHB4IGF1dG87Ij4NCgkJCQkJCQkJCTxpbWcgc3R5bGU9IndpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0O2Rpc3BsYXk6IGlubGluZS1ibG9jaztoZWlnaHQ6IDEwMCU7IiBzcmM9Int7YnVzaW5lc3NfbG9nb319IiAvPg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQk8L2Rpdj4NCgkJCQkJCTwvdGQ+DQoJCQkJCQkNCgkJCQkJCQ0KCQkJCQk8L3RyPg0KCQkJCQk8dHI+DQoJCQkJCQk8dGQ+DQoJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMjVweCAzMHB4O2JhY2tncm91bmQ6ICNmZmY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDkwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQkJPGg2IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bWFyZ2luOiAxMHB4IDBweCAxMHB4O2ZvbnQtd2VpZ2h0OiA2MDA7Ij5EZWFyIHt7YWRtaW5fbmFtZX19LCA8L2g2Pg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDttYXJnaW46IDEwcHggMHB4IDE1cHg7Ij5UaGUgYXBwb2ludG1lbnQgd2l0aCB7e2NsaWVudF9uYW1lfX0gd2l0aCBmb2xsb3dpbmcgZGV0YWlsczo8L3A+CQkJCQkJCQ0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJPGRpdiBzdHlsZT0iZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7cGFkZGluZzogMTBweCAwcHg7Ij4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPldoZW46IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tib29raW5nX2RhdGV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+Rm9yOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7c2VydmljZV9uYW1lfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPk1ldGhvZHMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7bWV0aG9kbmFtZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Vbml0cyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3t1bml0c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5BZGQtb25zIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2FkZG9uc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QcmljZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twcmljZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQkNCgkJCQkJCQkJCQ0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+TmFtZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tmaXJzdG5hbWV9fSB7e2xhc3RuYW1lfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkVtYWlsIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2NsaWVudF9lbWFpbH19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QaG9uZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twaG9uZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QYXltZW50IDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3BheW1lbnRfbWV0aG9kfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPlZhY2N1bSBDbGVhbmVyIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3ZhY2N1bV9jbGVhbmVyX3N0YXR1c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QYXJraW5nIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3Bhcmtpbmdfc3RhdHVzfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkFkZHJlc3MgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7YWRkcmVzc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Ob3RlcyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tub3Rlc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Db250YWN0IFN0YXR1cyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tjb250YWN0X3N0YXR1c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJwYWRkaW5nOiAxNXB4IDBweDtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjsiPg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDtsaW5lLWhlaWdodDogMjJweDttYXJnaW46IDEwcHggMHB4IDE1cHg7ZmxvYXQ6IGxlZnQ7Ij5oYXMgYmVlbiBjYW5jZWxsZWQuPC9wPg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMTBweCAwcHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7dGV4dC1hbGlnbjogY2VudGVyOyI+DQoJCQkJCQkJCQk8aDUgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTNweDttYXJnaW46IDBweCAwcHggNXB4OyI+VGhhbmsgeW91PC9oNT4NCgkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJPC9kaXY+DQoJCQkJCQk8L3RkPgkJCQkJDQoJCQkJCTwvdHI+CQkJCQ0KCQkJCTwvdGJvZHk+DQoJCQk8L3RhYmxlPgkNCgkJPC9kaXY+DQoJPC9kaXY+CQ0KPC9ib2R5Pg0KPC9odG1sPg==', 'E', 'CC', 'S'),
(13, 'Appointment Rescheduled by you', '', 'PGh0bWw+DQo8aGVhZD4NCgk8bWV0YSBuYW1lPSJ2aWV3cG9ydCIgY29udGVudD0id2lkdGg9ZGV2aWNlLXdpZHRoLCBpbml0aWFsLXNjYWxlPTEuMCIvPg0KCTxtZXRhIGh0dHAtZXF1aXY9IkNvbnRlbnQtVHlwZSIgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PVVURi04IiAvPg0KCTx0aXRsZT5TdWJqZWN0OiB7e3NlcnZpY2VfbmFtZX19IG9uIHt7Ym9va2luZ19kYXRlfX08L3RpdGxlPg0KCTxsaW5rIGhyZWY9Imh0dHBzOi8vZm9udHMuZ29vZ2xlYXBpcy5jb20vY3NzP2ZhbWlseT1Nb250c2VycmF0IiByZWw9InN0eWxlc2hlZXQiPg0KPC9oZWFkPg0KPGJvZHk+CQkNCgk8ZGl2IHN0eWxlPSJtYXJnaW46IDA7cGFkZGluZzogMDtmb250LWZhbWlseTogSGVsdmV0aWNhIE5ldWUsIEhlbHZldGljYSwgSGVsdmV0aWNhLCBBcmlhbCwgc2Fucy1zZXJpZjtmb250LXNpemU6IDEwMCU7bGluZS1oZWlnaHQ6IDEuNjtib3gtc2l6aW5nOiBib3JkZXItYm94OyI+CQ0KCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBibG9jayAhaW1wb3J0YW50O21heC13aWR0aDogNjAwcHggIWltcG9ydGFudDttYXJnaW46IDAgYXV0byAhaW1wb3J0YW50O2NsZWFyOiBib3RoICFpbXBvcnRhbnQ7Ij4NCgkJCTx0YWJsZSBzdHlsZT0iYm9yZGVyOiAxcHggc29saWQgI2MyYzJjMjt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDttYXJnaW46IDMwcHggMHB4Oy13ZWJraXQtYm9yZGVyLXJhZGl1czogNXB4Oy1tb3otYm9yZGVyLXJhZGl1czogNXB4Oy1vLWJvcmRlci1yYWRpdXM6IDVweDtib3JkZXItcmFkaXVzOiA1cHg7Ij4NCgkJCQk8dGJvZHk+DQoJCQkJCTx0ciBzdHlsZT0iYm9yZGVyLWJvdHRvbTogMXB4IHNvbGlkICNlNmU2ZTY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7Ij4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDU5JTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7dGV4dC1hbGlnbjogbGVmdDtmb250LWZhbWlseTogTW9udHNlcnJhdCwgc2Fucy1zZXJpZjsiPg0KCQkJCQkJCQl7e2NvbXBhbnlfbmFtZX19PGJyIC8+e3tjb21wYW55X2FkZHJlc3N9fTxiciAvPnt7Y29tcGFueV9jaXR5fX0sIHt7Y29tcGFueV9zdGF0ZX19LCB7e2NvbXBhbnlfemlwfX08YnIgLz57e2NvbXBhbnlfY291bnRyeX19PGJyIC8+e3tjb21wYW55X3Bob25lfX08YnIgLz57e2NvbXBhbnlfZW1haWx9fQ0KCQkJCQkJCTwvZGl2Pg0KCQkJCQkJPC90ZD4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDQwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7Ij4NCgkJCQkJCQkJPGRpdiBzdHlsZT0id2lkdGg6IDEzMHB4O2hlaWdodDogMTAwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO21hcmdpbjogMHB4IGF1dG87Ij4NCgkJCQkJCQkJCTxpbWcgc3R5bGU9IndpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0O2Rpc3BsYXk6IGlubGluZS1ibG9jaztoZWlnaHQ6IDEwMCU7IiBzcmM9Int7YnVzaW5lc3NfbG9nb319IiAvPg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQk8L2Rpdj4NCgkJCQkJCTwvdGQ+DQoJCQkJCQkNCgkJCQkJCQ0KCQkJCQk8L3RyPg0KCQkJCQk8dHI+DQoJCQkJCQk8dGQ+DQoJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMjVweCAzMHB4O2JhY2tncm91bmQ6ICNmZmY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDkwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQkJPGg2IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bWFyZ2luOiAxMHB4IDBweCAxMHB4O2ZvbnQtd2VpZ2h0OiA2MDA7Ij5EZWFyIHt7Y2xpZW50X25hbWV9fSw8L2g2Pg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDttYXJnaW46IDEwcHggMHB4IDE1cHg7Ij5Zb3VyIGFwcG9pbnRtZW50IHdpdGggZm9sbG93aW5nIGRldGFpbHM6PC9wPgkJCQkJCQkNCgkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCTxkaXYgc3R5bGU9ImZsb2F0OiBsZWZ0O3dpZHRoOiAxMDAlO2Rpc3BsYXk6IGJsb2NrO3BhZGRpbmc6IDEwcHggMHB4OyI+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5XaGVuOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7Ym9va2luZ19kYXRlfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkZvcjogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3NlcnZpY2VfbmFtZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5NZXRob2RzIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e21ldGhvZG5hbWV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+VW5pdHMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7dW5pdHN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+QWRkLW9ucyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3thZGRvbnN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UHJpY2UgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7cHJpY2V9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJDQoJCQkJCQkJCQkNCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPk5hbWUgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7Zmlyc3RuYW1lfX0ge3tsYXN0bmFtZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5FbWFpbCA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tjbGllbnRfZW1haWx9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UGhvbmUgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7cGhvbmV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UGF5bWVudCA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twYXltZW50X21ldGhvZH19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5WYWNjdW0gQ2xlYW5lciA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3t2YWNjdW1fY2xlYW5lcl9zdGF0dXN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+UGFya2luZyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twYXJraW5nX3N0YXR1c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5BZGRyZXNzIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2FkZHJlc3N9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+Tm90ZXMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7bm90ZXN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+Q29udGFjdCBTdGF0dXMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7Y29udGFjdF9zdGF0dXN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMTVweCAwcHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7Ym9yZGVyLWJvdHRvbTogMXB4IHNvbGlkICNlNmU2ZTY7Ij4NCgkJCQkJCQkJCTxwIHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bGluZS1oZWlnaHQ6IDIycHg7bWFyZ2luOiAxMHB4IDBweCAxNXB4O2Zsb2F0OiBsZWZ0OyI+aGFzIGJlZW4gcmVzY2hlZHVsZWQuPC9wPg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMTBweCAwcHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7dGV4dC1hbGlnbjogY2VudGVyOyI+DQoJCQkJCQkJCQk8aDUgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTNweDttYXJnaW46IDBweCAwcHggNXB4OyI+VGhhbmsgeW91PC9oNT4NCgkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJPC9kaXY+DQoJCQkJCQk8L3RkPgkJCQkJDQoJCQkJCTwvdHI+CQkJCQ0KCQkJCTwvdGJvZHk+DQoJCQk8L3RhYmxlPgkNCgkJPC9kaXY+DQoJPC9kaXY+CQ0KPC9ib2R5Pg0KPC9odG1sPg==', 'E', 'RS', 'C'),
(14, 'Appointment Rescheduled By Customer', '', 'PGh0bWw+DQo8aGVhZD4NCgk8bWV0YSBuYW1lPSJ2aWV3cG9ydCIgY29udGVudD0id2lkdGg9ZGV2aWNlLXdpZHRoLCBpbml0aWFsLXNjYWxlPTEuMCIvPg0KCTxtZXRhIGh0dHAtZXF1aXY9IkNvbnRlbnQtVHlwZSIgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PVVURi04IiAvPg0KCTx0aXRsZT5TdWJqZWN0OiB7e3NlcnZpY2VfbmFtZX19IG9uIHt7Ym9va2luZ19kYXRlfX08L3RpdGxlPg0KCTxsaW5rIGhyZWY9Imh0dHBzOi8vZm9udHMuZ29vZ2xlYXBpcy5jb20vY3NzP2ZhbWlseT1Nb250c2VycmF0IiByZWw9InN0eWxlc2hlZXQiPg0KPC9oZWFkPg0KPGJvZHk+CQkNCgk8ZGl2IHN0eWxlPSJtYXJnaW46IDA7cGFkZGluZzogMDtmb250LWZhbWlseTogSGVsdmV0aWNhIE5ldWUsIEhlbHZldGljYSwgSGVsdmV0aWNhLCBBcmlhbCwgc2Fucy1zZXJpZjtmb250LXNpemU6IDEwMCU7bGluZS1oZWlnaHQ6IDEuNjtib3gtc2l6aW5nOiBib3JkZXItYm94OyI+CQ0KCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBibG9jayAhaW1wb3J0YW50O21heC13aWR0aDogNjAwcHggIWltcG9ydGFudDttYXJnaW46IDAgYXV0byAhaW1wb3J0YW50O2NsZWFyOiBib3RoICFpbXBvcnRhbnQ7Ij4NCgkJCTx0YWJsZSBzdHlsZT0iYm9yZGVyOiAxcHggc29saWQgI2MyYzJjMjt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDttYXJnaW46IDMwcHggMHB4Oy13ZWJraXQtYm9yZGVyLXJhZGl1czogNXB4Oy1tb3otYm9yZGVyLXJhZGl1czogNXB4Oy1vLWJvcmRlci1yYWRpdXM6IDVweDtib3JkZXItcmFkaXVzOiA1cHg7Ij4NCgkJCQk8dGJvZHk+DQoJCQkJCTx0ciBzdHlsZT0iYm9yZGVyLWJvdHRvbTogMXB4IHNvbGlkICNlNmU2ZTY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7Ij4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDU5JTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7dGV4dC1hbGlnbjogbGVmdDtmb250LWZhbWlseTogTW9udHNlcnJhdCwgc2Fucy1zZXJpZjsiPg0KCQkJCQkJCQl7e2NvbXBhbnlfbmFtZX19PGJyIC8+e3tjb21wYW55X2FkZHJlc3N9fTxiciAvPnt7Y29tcGFueV9jaXR5fX0sIHt7Y29tcGFueV9zdGF0ZX19LCB7e2NvbXBhbnlfemlwfX08YnIgLz57e2NvbXBhbnlfY291bnRyeX19PGJyIC8+e3tjb21wYW55X3Bob25lfX08YnIgLz57e2NvbXBhbnlfZW1haWx9fQ0KCQkJCQkJCTwvZGl2Pg0KCQkJCQkJPC90ZD4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDQwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7Ij4NCgkJCQkJCQkJPGRpdiBzdHlsZT0id2lkdGg6IDEzMHB4O2hlaWdodDogMTAwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO21hcmdpbjogMHB4IGF1dG87Ij4NCgkJCQkJCQkJCTxpbWcgc3R5bGU9IndpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0O2Rpc3BsYXk6IGlubGluZS1ibG9jaztoZWlnaHQ6IDEwMCU7IiBzcmM9Int7YnVzaW5lc3NfbG9nb319IiAvPg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQk8L2Rpdj4NCgkJCQkJCTwvdGQ+DQoJCQkJCQkNCgkJCQkJCQ0KCQkJCQk8L3RyPg0KCQkJCQk8dHI+DQoJCQkJCQk8dGQ+DQoJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMjVweCAzMHB4O2JhY2tncm91bmQ6ICNmZmY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDkwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQkJPGg2IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bWFyZ2luOiAxMHB4IDBweCAxMHB4O2ZvbnQtd2VpZ2h0OiA2MDA7Ij5EZWFyIHt7YWRtaW5fbmFtZX19LCA8L2g2Pg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDttYXJnaW46IDEwcHggMHB4IDE1cHg7Ij5UaGUgYXBwb2ludG1lbnQgd2l0aCB7e2NsaWVudF9uYW1lfX0gd2l0aCBmb2xsb3dpbmcgZGV0YWlsczo8L3A+CQkJCQkJCQ0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJPGRpdiBzdHlsZT0iZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7cGFkZGluZzogMTBweCAwcHg7Ij4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPldoZW46IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tib29raW5nX2RhdGV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+Rm9yOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7c2VydmljZV9uYW1lfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPk1ldGhvZHMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7bWV0aG9kbmFtZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Vbml0cyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3t1bml0c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5BZGQtb25zIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2FkZG9uc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QcmljZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twcmljZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQkNCgkJCQkJCQkJCQ0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+TmFtZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tmaXJzdG5hbWV9fSB7e2xhc3RuYW1lfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkVtYWlsIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2NsaWVudF9lbWFpbH19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QaG9uZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twaG9uZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QYXltZW50IDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3BheW1lbnRfbWV0aG9kfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPlZhY2N1bSBDbGVhbmVyIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3ZhY2N1bV9jbGVhbmVyX3N0YXR1c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QYXJraW5nIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3Bhcmtpbmdfc3RhdHVzfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkFkZHJlc3MgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7YWRkcmVzc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Ob3RlcyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tub3Rlc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Db250YWN0IFN0YXR1cyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tjb250YWN0X3N0YXR1c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJwYWRkaW5nOiAxNXB4IDBweDtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjsiPg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDtsaW5lLWhlaWdodDogMjJweDttYXJnaW46IDEwcHggMHB4IDE1cHg7ZmxvYXQ6IGxlZnQ7Ij5oYXMgYmVlbiByZXNjaGVkdWxlZC48L3A+DQoJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJwYWRkaW5nOiAxMHB4IDBweDtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jazt0ZXh0LWFsaWduOiBjZW50ZXI7Ij4NCgkJCQkJCQkJCTxoNSBzdHlsZT0iY29sb3I6ICM2MDYwNjA7Zm9udC1zaXplOiAxM3B4O21hcmdpbjogMHB4IDBweCA1cHg7Ij5UaGFuayB5b3U8L2g1Pg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQk8L2Rpdj4NCgkJCQkJCTwvdGQ+CQkJCQkNCgkJCQkJPC90cj4JCQkJDQoJCQkJPC90Ym9keT4NCgkJCTwvdGFibGU+CQ0KCQk8L2Rpdj4NCgk8L2Rpdj4JDQo8L2JvZHk+DQo8L2h0bWw+', 'E', 'RS', 'A'),
(15, 'Appointment Rescheduled By Customer', '', 'PGh0bWw+DQo8aGVhZD4NCgk8bWV0YSBuYW1lPSJ2aWV3cG9ydCIgY29udGVudD0id2lkdGg9ZGV2aWNlLXdpZHRoLCBpbml0aWFsLXNjYWxlPTEuMCIvPg0KCTxtZXRhIGh0dHAtZXF1aXY9IkNvbnRlbnQtVHlwZSIgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PVVURi04IiAvPg0KCTx0aXRsZT5TdWJqZWN0OiB7e3NlcnZpY2VfbmFtZX19IG9uIHt7Ym9va2luZ19kYXRlfX08L3RpdGxlPg0KCTxsaW5rIGhyZWY9Imh0dHBzOi8vZm9udHMuZ29vZ2xlYXBpcy5jb20vY3NzP2ZhbWlseT1Nb250c2VycmF0IiByZWw9InN0eWxlc2hlZXQiPg0KPC9oZWFkPg0KPGJvZHk+CQkNCgk8ZGl2IHN0eWxlPSJtYXJnaW46IDA7cGFkZGluZzogMDtmb250LWZhbWlseTogSGVsdmV0aWNhIE5ldWUsIEhlbHZldGljYSwgSGVsdmV0aWNhLCBBcmlhbCwgc2Fucy1zZXJpZjtmb250LXNpemU6IDEwMCU7bGluZS1oZWlnaHQ6IDEuNjtib3gtc2l6aW5nOiBib3JkZXItYm94OyI+CQ0KCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBibG9jayAhaW1wb3J0YW50O21heC13aWR0aDogNjAwcHggIWltcG9ydGFudDttYXJnaW46IDAgYXV0byAhaW1wb3J0YW50O2NsZWFyOiBib3RoICFpbXBvcnRhbnQ7Ij4NCgkJCTx0YWJsZSBzdHlsZT0iYm9yZGVyOiAxcHggc29saWQgI2MyYzJjMjt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDttYXJnaW46IDMwcHggMHB4Oy13ZWJraXQtYm9yZGVyLXJhZGl1czogNXB4Oy1tb3otYm9yZGVyLXJhZGl1czogNXB4Oy1vLWJvcmRlci1yYWRpdXM6IDVweDtib3JkZXItcmFkaXVzOiA1cHg7Ij4NCgkJCQk8dGJvZHk+DQoJCQkJCTx0ciBzdHlsZT0iYm9yZGVyLWJvdHRvbTogMXB4IHNvbGlkICNlNmU2ZTY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7Ij4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDU5JTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7dGV4dC1hbGlnbjogbGVmdDtmb250LWZhbWlseTogTW9udHNlcnJhdCwgc2Fucy1zZXJpZjsiPg0KCQkJCQkJCQl7e2NvbXBhbnlfbmFtZX19PGJyIC8+e3tjb21wYW55X2FkZHJlc3N9fTxiciAvPnt7Y29tcGFueV9jaXR5fX0sIHt7Y29tcGFueV9zdGF0ZX19LCB7e2NvbXBhbnlfemlwfX08YnIgLz57e2NvbXBhbnlfY291bnRyeX19PGJyIC8+e3tjb21wYW55X3Bob25lfX08YnIgLz57e2NvbXBhbnlfZW1haWx9fQ0KCQkJCQkJCTwvZGl2Pg0KCQkJCQkJPC90ZD4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDQwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7Ij4NCgkJCQkJCQkJPGRpdiBzdHlsZT0id2lkdGg6IDEzMHB4O2hlaWdodDogMTAwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO21hcmdpbjogMHB4IGF1dG87Ij4NCgkJCQkJCQkJCTxpbWcgc3R5bGU9IndpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0O2Rpc3BsYXk6IGlubGluZS1ibG9jaztoZWlnaHQ6IDEwMCU7IiBzcmM9Int7YnVzaW5lc3NfbG9nb319IiAvPg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQk8L2Rpdj4NCgkJCQkJCTwvdGQ+DQoJCQkJCQkNCgkJCQkJCQ0KCQkJCQk8L3RyPg0KCQkJCQk8dHI+DQoJCQkJCQk8dGQ+DQoJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMjVweCAzMHB4O2JhY2tncm91bmQ6ICNmZmY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDkwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQkJPGg2IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bWFyZ2luOiAxMHB4IDBweCAxMHB4O2ZvbnQtd2VpZ2h0OiA2MDA7Ij5EZWFyIHt7YWRtaW5fbmFtZX19LCA8L2g2Pg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDttYXJnaW46IDEwcHggMHB4IDE1cHg7Ij5UaGUgYXBwb2ludG1lbnQgd2l0aCB7e2NsaWVudF9uYW1lfX0gd2l0aCBmb2xsb3dpbmcgZGV0YWlsczo8L3A+CQkJCQkJCQ0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJPGRpdiBzdHlsZT0iZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7cGFkZGluZzogMTBweCAwcHg7Ij4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPldoZW46IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tib29raW5nX2RhdGV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+Rm9yOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7c2VydmljZV9uYW1lfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPk1ldGhvZHMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7bWV0aG9kbmFtZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Vbml0cyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3t1bml0c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5BZGQtb25zIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2FkZG9uc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QcmljZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twcmljZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQkNCgkJCQkJCQkJCQ0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+TmFtZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tmaXJzdG5hbWV9fSB7e2xhc3RuYW1lfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkVtYWlsIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2NsaWVudF9lbWFpbH19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QaG9uZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twaG9uZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QYXltZW50IDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3BheW1lbnRfbWV0aG9kfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPlZhY2N1bSBDbGVhbmVyIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3ZhY2N1bV9jbGVhbmVyX3N0YXR1c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QYXJraW5nIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3Bhcmtpbmdfc3RhdHVzfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkFkZHJlc3MgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7YWRkcmVzc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Ob3RlcyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tub3Rlc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Db250YWN0IFN0YXR1cyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tjb250YWN0X3N0YXR1c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJwYWRkaW5nOiAxNXB4IDBweDtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjsiPg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDtsaW5lLWhlaWdodDogMjJweDttYXJnaW46IDEwcHggMHB4IDE1cHg7ZmxvYXQ6IGxlZnQ7Ij5oYXMgYmVlbiByZXNjaGVkdWxlZC48L3A+DQoJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJwYWRkaW5nOiAxMHB4IDBweDtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jazt0ZXh0LWFsaWduOiBjZW50ZXI7Ij4NCgkJCQkJCQkJCTxoNSBzdHlsZT0iY29sb3I6ICM2MDYwNjA7Zm9udC1zaXplOiAxM3B4O21hcmdpbjogMHB4IDBweCA1cHg7Ij5UaGFuayB5b3U8L2g1Pg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQk8L2Rpdj4NCgkJCQkJCTwvdGQ+CQkJCQkNCgkJCQkJPC90cj4JCQkJDQoJCQkJPC90Ym9keT4NCgkJCTwvdGFibGU+CQ0KCQk8L2Rpdj4NCgk8L2Rpdj4JDQo8L2JvZHk+DQo8L2h0bWw+', 'E', 'RS', 'S'),
(16, 'Client Appointment Reminder', '', 'PGh0bWw+DQo8aGVhZD4NCgk8bWV0YSBuYW1lPSJ2aWV3cG9ydCIgY29udGVudD0id2lkdGg9ZGV2aWNlLXdpZHRoLCBpbml0aWFsLXNjYWxlPTEuMCIvPg0KCTxtZXRhIGh0dHAtZXF1aXY9IkNvbnRlbnQtVHlwZSIgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PVVURi04IiAvPg0KCTx0aXRsZT5TdWJqZWN0OiB7e3NlcnZpY2VfbmFtZX19IG9uIHt7Ym9va2luZ19kYXRlfX08L3RpdGxlPg0KCTxsaW5rIGhyZWY9Imh0dHBzOi8vZm9udHMuZ29vZ2xlYXBpcy5jb20vY3NzP2ZhbWlseT1Nb250c2VycmF0IiByZWw9InN0eWxlc2hlZXQiPg0KPC9oZWFkPg0KPGJvZHk+CQkNCgk8ZGl2IHN0eWxlPSJtYXJnaW46IDA7cGFkZGluZzogMDtmb250LWZhbWlseTogSGVsdmV0aWNhIE5ldWUsIEhlbHZldGljYSwgSGVsdmV0aWNhLCBBcmlhbCwgc2Fucy1zZXJpZjtmb250LXNpemU6IDEwMCU7bGluZS1oZWlnaHQ6IDEuNjtib3gtc2l6aW5nOiBib3JkZXItYm94OyI+CQ0KCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBibG9jayAhaW1wb3J0YW50O21heC13aWR0aDogNjAwcHggIWltcG9ydGFudDttYXJnaW46IDAgYXV0byAhaW1wb3J0YW50O2NsZWFyOiBib3RoICFpbXBvcnRhbnQ7Ij4NCgkJCTx0YWJsZSBzdHlsZT0iYm9yZGVyOiAxcHggc29saWQgI2MyYzJjMjt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDttYXJnaW46IDMwcHggMHB4Oy13ZWJraXQtYm9yZGVyLXJhZGl1czogNXB4Oy1tb3otYm9yZGVyLXJhZGl1czogNXB4Oy1vLWJvcmRlci1yYWRpdXM6IDVweDtib3JkZXItcmFkaXVzOiA1cHg7Ij4NCgkJCQk8dGJvZHk+DQoJCQkJCTx0ciBzdHlsZT0iYm9yZGVyLWJvdHRvbTogMXB4IHNvbGlkICNlNmU2ZTY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7Ij4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDU5JTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7dGV4dC1hbGlnbjogbGVmdDtmb250LWZhbWlseTogTW9udHNlcnJhdCwgc2Fucy1zZXJpZjsiPg0KCQkJCQkJCQl7e2NvbXBhbnlfbmFtZX19PGJyIC8+e3tjb21wYW55X2FkZHJlc3N9fTxiciAvPnt7Y29tcGFueV9jaXR5fX0sIHt7Y29tcGFueV9zdGF0ZX19LCB7e2NvbXBhbnlfemlwfX08YnIgLz57e2NvbXBhbnlfY291bnRyeX19PGJyIC8+e3tjb21wYW55X3Bob25lfX08YnIgLz57e2NvbXBhbnlfZW1haWx9fQ0KCQkJCQkJCTwvZGl2Pg0KCQkJCQkJPC90ZD4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDQwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7Ij4NCgkJCQkJCQkJPGRpdiBzdHlsZT0id2lkdGg6IDEzMHB4O2hlaWdodDogMTAwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO21hcmdpbjogMHB4IGF1dG87Ij4NCgkJCQkJCQkJCTxpbWcgc3R5bGU9IndpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0O2Rpc3BsYXk6IGlubGluZS1ibG9jaztoZWlnaHQ6IDEwMCU7IiBzcmM9Int7YnVzaW5lc3NfbG9nb319IiAvPg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQk8L2Rpdj4NCgkJCQkJCTwvdGQ+DQoJCQkJCQkNCgkJCQkJCQ0KCQkJCQk8L3RyPg0KCQkJCQk8dHI+DQoJCQkJCQk8dGQ+DQoJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMjVweCAzMHB4O2JhY2tncm91bmQ6ICNmZmY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDkwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQkJPGg2IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bWFyZ2luOiAxMHB4IDBweCAxMHB4O2ZvbnQtd2VpZ2h0OiA2MDA7Ij5EZWFyIHt7Y2xpZW50X25hbWV9fSw8L2g2Pg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDttYXJnaW46IDEwcHggMHB4IDE1cHg7Ij5XZSBqdXN0IHdhbnRlZCB0byByZW1pbmQgeW91IHRoYXQgeW91ciBhcHBvaW50bWVudCB3aXRoIHt7YWRtaW5fbmFtZX19IGlzIHNjaGVkdWxlZCBpbiA8Yj57e2FwcF9yZW1haW5fdGltZX19PC9iPiBob3Vycy48L3A+CQkJCQkJCQ0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJPGRpdiBzdHlsZT0iZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7cGFkZGluZzogMTBweCAwcHg7Ij4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPldoZW46IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tib29raW5nX2RhdGV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+Rm9yOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7c2VydmljZV9uYW1lfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPk1ldGhvZHMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7bWV0aG9kbmFtZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Vbml0cyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3t1bml0c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5BZGQtb25zIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2FkZG9uc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QcmljZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twcmljZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQkNCgkJCQkJCQkJCQ0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+TmFtZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tmaXJzdG5hbWV9fSB7e2xhc3RuYW1lfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkVtYWlsIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2NsaWVudF9lbWFpbH19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QaG9uZSA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3twaG9uZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QYXltZW50IDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3BheW1lbnRfbWV0aG9kfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPlZhY2N1bSBDbGVhbmVyIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3ZhY2N1bV9jbGVhbmVyX3N0YXR1c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5QYXJraW5nIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3Bhcmtpbmdfc3RhdHVzfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkFkZHJlc3MgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7YWRkcmVzc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Ob3RlcyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tub3Rlc319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Db250YWN0IFN0YXR1cyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tjb250YWN0X3N0YXR1c319PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJwYWRkaW5nOiAxNXB4IDBweDtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjsiPg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDtsaW5lLWhlaWdodDogMjJweDttYXJnaW46IDEwcHggMHB4IDE1cHg7ZmxvYXQ6IGxlZnQ7Ij48L3A+DQoJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJwYWRkaW5nOiAxMHB4IDBweDtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jazt0ZXh0LWFsaWduOiBjZW50ZXI7Ij4NCgkJCQkJCQkJCTxoNSBzdHlsZT0iY29sb3I6ICM2MDYwNjA7Zm9udC1zaXplOiAxM3B4O21hcmdpbjogMHB4IDBweCA1cHg7Ij5UaGFuayB5b3U8L2g1Pg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQk8L2Rpdj4NCgkJCQkJCTwvdGQ+CQkJCQkNCgkJCQkJPC90cj4JCQkJDQoJCQkJPC90Ym9keT4NCgkJCTwvdGFibGU+CQ0KCQk8L2Rpdj4NCgk8L2Rpdj4JDQo8L2JvZHk+DQo8L2h0bWw+', 'E', 'RM', 'C'),
(17, 'Admin Appointment Reminder', '', 'PGh0bWw+DQo8aGVhZD4NCgk8bWV0YSBuYW1lPSJ2aWV3cG9ydCIgY29udGVudD0id2lkdGg9ZGV2aWNlLXdpZHRoLCBpbml0aWFsLXNjYWxlPTEuMCIvPg0KCTxtZXRhIGh0dHAtZXF1aXY9IkNvbnRlbnQtVHlwZSIgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PVVURi04IiAvPg0KCTx0aXRsZT5TdWJqZWN0OiB7e3NlcnZpY2VfbmFtZX19IG9uIHt7Ym9va2luZ19kYXRlfX08L3RpdGxlPg0KCTxsaW5rIGhyZWY9Imh0dHBzOi8vZm9udHMuZ29vZ2xlYXBpcy5jb20vY3NzP2ZhbWlseT1Nb250c2VycmF0IiByZWw9InN0eWxlc2hlZXQiPg0KPC9oZWFkPg0KPGJvZHk+CQkNCgk8ZGl2IHN0eWxlPSJtYXJnaW46IDA7cGFkZGluZzogMDtmb250LWZhbWlseTogSGVsdmV0aWNhIE5ldWUsIEhlbHZldGljYSwgSGVsdmV0aWNhLCBBcmlhbCwgc2Fucy1zZXJpZjtmb250LXNpemU6IDEwMCU7bGluZS1oZWlnaHQ6IDEuNjtib3gtc2l6aW5nOiBib3JkZXItYm94OyI+CQ0KCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBibG9jayAhaW1wb3J0YW50O21heC13aWR0aDogNjAwcHggIWltcG9ydGFudDttYXJnaW46IDAgYXV0byAhaW1wb3J0YW50O2NsZWFyOiBib3RoICFpbXBvcnRhbnQ7Ij4NCgkJCTx0YWJsZSBzdHlsZT0iYm9yZGVyOiAxcHggc29saWQgI2MyYzJjMjt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDttYXJnaW46IDMwcHggMHB4Oy13ZWJraXQtYm9yZGVyLXJhZGl1czogNXB4Oy1tb3otYm9yZGVyLXJhZGl1czogNXB4Oy1vLWJvcmRlci1yYWRpdXM6IDVweDtib3JkZXItcmFkaXVzOiA1cHg7Ij4NCgkJCQk8dGJvZHk+DQoJCQkJCTx0ciBzdHlsZT0iYm9yZGVyLWJvdHRvbTogMXB4IHNvbGlkICNlNmU2ZTY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7Ij4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDU5JTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7dGV4dC1hbGlnbjogbGVmdDtmb250LWZhbWlseTogTW9udHNlcnJhdCwgc2Fucy1zZXJpZjsiPg0KCQkJCQkJCQl7e2NvbXBhbnlfbmFtZX19PGJyIC8+e3tjb21wYW55X2FkZHJlc3N9fTxiciAvPnt7Y29tcGFueV9jaXR5fX0sIHt7Y29tcGFueV9zdGF0ZX19LCB7e2NvbXBhbnlfemlwfX08YnIgLz57e2NvbXBhbnlfY291bnRyeX19PGJyIC8+e3tjb21wYW55X3Bob25lfX08YnIgLz57e2NvbXBhbnlfZW1haWx9fQ0KCQkJCQkJCTwvZGl2Pg0KCQkJCQkJPC90ZD4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDQwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7Ij4NCgkJCQkJCQkJPGRpdiBzdHlsZT0id2lkdGg6IDEzMHB4O2hlaWdodDogMTAwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO21hcmdpbjogMHB4IGF1dG87Ij4NCgkJCQkJCQkJCTxpbWcgc3R5bGU9IndpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0O2Rpc3BsYXk6IGlubGluZS1ibG9jaztoZWlnaHQ6IDEwMCU7IiBzcmM9Int7YnVzaW5lc3NfbG9nb319IiAvPg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQk8L2Rpdj4NCgkJCQkJCTwvdGQ+DQoJCQkJCQkNCgkJCQkJCQ0KCQkJCQk8L3RyPg0KCQkJCQk8dHI+DQoJCQkJCQk8dGQ+DQoJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMjVweCAzMHB4O2JhY2tncm91bmQ6ICNmZmY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDkwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQkJPGg2IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bWFyZ2luOiAxMHB4IDBweCAxMHB4O2ZvbnQtd2VpZ2h0OiA2MDA7Ij5EZWFyIHt7YWRtaW5fbmFtZX19LCA8L2g2Pg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDttYXJnaW46IDEwcHggMHB4IDE1cHg7Ij5XZSBqdXN0IHdhbnRlZCB0byByZW1pbmQgeW91IHRoYXQgeW91IGhhdmUgYXBwb2ludG1lbnQgd2l0aCB7e2NsaWVudF9uYW1lfX0gaXMgc2NoZWR1bGVkIGluIDxiPnt7YXBwX3JlbWFpbl90aW1lfX08L2I+IGhvdXJzLjwvcD4JCQkJCQkJDQoJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jaztwYWRkaW5nOiAxMHB4IDBweDsiPg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+V2hlbjogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2Jvb2tpbmdfZGF0ZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Gb3I6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tzZXJ2aWNlX25hbWV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+TWV0aG9kcyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3ttZXRob2RuYW1lfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPlVuaXRzIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3VuaXRzfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkFkZC1vbnMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7YWRkb25zfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPlByaWNlIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3ByaWNlfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCQ0KCQkJCQkJCQkJDQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5OYW1lIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2ZpcnN0bmFtZX19IHt7bGFzdG5hbWV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+RW1haWwgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7Y2xpZW50X2VtYWlsfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPlBob25lIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3Bob25lfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPlBheW1lbnQgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7cGF5bWVudF9tZXRob2R9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+VmFjY3VtIENsZWFuZXIgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7dmFjY3VtX2NsZWFuZXJfc3RhdHVzfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPlBhcmtpbmcgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7cGFya2luZ19zdGF0dXN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+QWRkcmVzcyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3thZGRyZXNzfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPk5vdGVzIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e25vdGVzfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkNvbnRhY3QgU3RhdHVzIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2NvbnRhY3Rfc3RhdHVzfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCTxkaXYgc3R5bGU9InBhZGRpbmc6IDE1cHggMHB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiAxMDAlO2JvcmRlci1ib3R0b206IDFweCBzb2xpZCAjZTZlNmU2OyI+DQoJCQkJCQkJCQk8cCBzdHlsZT0iY29sb3I6ICM2MDYwNjA7Zm9udC1zaXplOiAxNXB4O2xpbmUtaGVpZ2h0OiAyMnB4O21hcmdpbjogMTBweCAwcHggMTVweDtmbG9hdDogbGVmdDsiPjwvcD4NCgkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCTxkaXYgc3R5bGU9InBhZGRpbmc6IDEwcHggMHB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiAxMDAlO2Rpc3BsYXk6IGJsb2NrO3RleHQtYWxpZ246IGNlbnRlcjsiPg0KCQkJCQkJCQkJPGg1IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDEzcHg7bWFyZ2luOiAwcHggMHB4IDVweDsiPlRoYW5rIHlvdTwvaDU+DQoJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCTwvZGl2Pg0KCQkJCQkJPC90ZD4JCQkJCQ0KCQkJCQk8L3RyPgkJCQkNCgkJCQk8L3Rib2R5Pg0KCQkJPC90YWJsZT4JDQoJCTwvZGl2Pg0KCTwvZGl2PgkNCjwvYm9keT4NCjwvaHRtbD4=', 'E', 'RM', 'A'),
(18, 'Admin Appointment Reminder', '', 'PGh0bWw+DQo8aGVhZD4NCgk8bWV0YSBuYW1lPSJ2aWV3cG9ydCIgY29udGVudD0id2lkdGg9ZGV2aWNlLXdpZHRoLCBpbml0aWFsLXNjYWxlPTEuMCIvPg0KCTxtZXRhIGh0dHAtZXF1aXY9IkNvbnRlbnQtVHlwZSIgY29udGVudD0idGV4dC9odG1sOyBjaGFyc2V0PVVURi04IiAvPg0KCTx0aXRsZT5TdWJqZWN0OiB7e3NlcnZpY2VfbmFtZX19IG9uIHt7Ym9va2luZ19kYXRlfX08L3RpdGxlPg0KCTxsaW5rIGhyZWY9Imh0dHBzOi8vZm9udHMuZ29vZ2xlYXBpcy5jb20vY3NzP2ZhbWlseT1Nb250c2VycmF0IiByZWw9InN0eWxlc2hlZXQiPg0KPC9oZWFkPg0KPGJvZHk+CQkNCgk8ZGl2IHN0eWxlPSJtYXJnaW46IDA7cGFkZGluZzogMDtmb250LWZhbWlseTogSGVsdmV0aWNhIE5ldWUsIEhlbHZldGljYSwgSGVsdmV0aWNhLCBBcmlhbCwgc2Fucy1zZXJpZjtmb250LXNpemU6IDEwMCU7bGluZS1oZWlnaHQ6IDEuNjtib3gtc2l6aW5nOiBib3JkZXItYm94OyI+CQ0KCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBibG9jayAhaW1wb3J0YW50O21heC13aWR0aDogNjAwcHggIWltcG9ydGFudDttYXJnaW46IDAgYXV0byAhaW1wb3J0YW50O2NsZWFyOiBib3RoICFpbXBvcnRhbnQ7Ij4NCgkJCTx0YWJsZSBzdHlsZT0iYm9yZGVyOiAxcHggc29saWQgI2MyYzJjMjt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDttYXJnaW46IDMwcHggMHB4Oy13ZWJraXQtYm9yZGVyLXJhZGl1czogNXB4Oy1tb3otYm9yZGVyLXJhZGl1czogNXB4Oy1vLWJvcmRlci1yYWRpdXM6IDVweDtib3JkZXItcmFkaXVzOiA1cHg7Ij4NCgkJCQk8dGJvZHk+DQoJCQkJCTx0ciBzdHlsZT0iYm9yZGVyLWJvdHRvbTogMXB4IHNvbGlkICNlNmU2ZTY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDEwMCU7ZGlzcGxheTogYmxvY2s7Ij4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDU5JTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7dGV4dC1hbGlnbjogbGVmdDtmb250LWZhbWlseTogTW9udHNlcnJhdCwgc2Fucy1zZXJpZjsiPg0KCQkJCQkJCQl7e2NvbXBhbnlfbmFtZX19PGJyIC8+e3tjb21wYW55X2FkZHJlc3N9fTxiciAvPnt7Y29tcGFueV9jaXR5fX0sIHt7Y29tcGFueV9zdGF0ZX19LCB7e2NvbXBhbnlfemlwfX08YnIgLz57e2NvbXBhbnlfY291bnRyeX19PGJyIC8+e3tjb21wYW55X3Bob25lfX08YnIgLz57e2NvbXBhbnlfZW1haWx9fQ0KCQkJCQkJCTwvZGl2Pg0KCQkJCQkJPC90ZD4NCgkJCQkJCTx0ZCBzdHlsZT0id2lkdGg6IDQwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJPGRpdiBzdHlsZT0idmVydGljYWwtYWxpZ246IHRvcDtmbG9hdDogbGVmdDtwYWRkaW5nOjE1cHg7d2lkdGg6IDEwMCU7Ym94LXNpemluZzogYm9yZGVyLWJveDstd2Via2l0LWJveC1zaXppbmc6IGJvcmRlci1ib3g7Y2xlYXI6IGxlZnQ7Ij4NCgkJCQkJCQkJPGRpdiBzdHlsZT0id2lkdGg6IDEzMHB4O2hlaWdodDogMTAwJTt2ZXJ0aWNhbC1hbGlnbjogdG9wO21hcmdpbjogMHB4IGF1dG87Ij4NCgkJCQkJCQkJCTxpbWcgc3R5bGU9IndpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0O2Rpc3BsYXk6IGlubGluZS1ibG9jaztoZWlnaHQ6IDEwMCU7IiBzcmM9Int7YnVzaW5lc3NfbG9nb319IiAvPg0KCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQk8L2Rpdj4NCgkJCQkJCTwvdGQ+DQoJCQkJCQkNCgkJCQkJCQ0KCQkJCQk8L3RyPg0KCQkJCQk8dHI+DQoJCQkJCQk8dGQ+DQoJCQkJCQkJPGRpdiBzdHlsZT0icGFkZGluZzogMjVweCAzMHB4O2JhY2tncm91bmQ6ICNmZmY7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDkwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJib3JkZXItYm90dG9tOiAxcHggc29saWQgI2U2ZTZlNjtmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jazsiPg0KCQkJCQkJCQkJPGg2IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDE1cHg7bWFyZ2luOiAxMHB4IDBweCAxMHB4O2ZvbnQtd2VpZ2h0OiA2MDA7Ij5EZWFyIHt7YWRtaW5fbmFtZX19LCA8L2g2Pg0KCQkJCQkJCQkJPHAgc3R5bGU9ImNvbG9yOiAjNjA2MDYwO2ZvbnQtc2l6ZTogMTVweDttYXJnaW46IDEwcHggMHB4IDE1cHg7Ij5XZSBqdXN0IHdhbnRlZCB0byByZW1pbmQgeW91IHRoYXQgeW91IGhhdmUgYXBwb2ludG1lbnQgd2l0aCB7e2NsaWVudF9uYW1lfX0gaXMgc2NoZWR1bGVkIGluIDxiPnt7YXBwX3JlbWFpbl90aW1lfX08L2I+IGhvdXJzLjwvcD4JCQkJCQkJDQoJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQk8ZGl2IHN0eWxlPSJmbG9hdDogbGVmdDt3aWR0aDogMTAwJTtkaXNwbGF5OiBibG9jaztwYWRkaW5nOiAxMHB4IDBweDsiPg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+V2hlbjogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2Jvb2tpbmdfZGF0ZX19PC9zcGFuPg0KCQkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5Gb3I6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3tzZXJ2aWNlX25hbWV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+TWV0aG9kcyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3ttZXRob2RuYW1lfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPlVuaXRzIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3VuaXRzfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkFkZC1vbnMgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7YWRkb25zfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPlByaWNlIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3ByaWNlfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCQ0KCQkJCQkJCQkJDQoJCQkJCQkJCQk8ZGl2IHN0eWxlPSJkaXNwbGF5OiBpbmxpbmUtYmxvY2s7d2lkdGg6IDEwMCU7ZmxvYXQ6IGxlZnQ7Ij4NCgkJCQkJCQkJCQk8bGFiZWwgc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtjb2xvcjogIzk5OTk5OTtwYWRkaW5nLXJpZ2h0OiA1cHg7bWluLXdpZHRoOiA5NXB4O3doaXRlLXNwYWNlOiBub3dyYXA7ZmxvYXQ6IGxlZnQ7bGluZS1oZWlnaHQ6IDI1cHg7Ij5OYW1lIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2ZpcnN0bmFtZX19IHt7bGFzdG5hbWV9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+RW1haWwgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7Y2xpZW50X2VtYWlsfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPlBob25lIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e3Bob25lfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPlBheW1lbnQgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7cGF5bWVudF9tZXRob2R9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+VmFjY3VtIENsZWFuZXIgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7dmFjY3VtX2NsZWFuZXJfc3RhdHVzfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPlBhcmtpbmcgOiA8L2xhYmVsPg0KCQkJCQkJCQkJCTxzcGFuIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Zm9udC13ZWlnaHQ6IDQwMDtjb2xvcjogIzYwNjA2MDtsaW5lLWhlaWdodDogMjVweDtmbG9hdDogbGVmdDt3aWR0aDogNzYlO3dvcmQtd3JhcDogYnJlYWstd29yZDttYXgtaGVpZ2h0OiA3MHB4O292ZXJmbG93OiBhdXRvOyI+IHt7cGFya2luZ19zdGF0dXN9fTwvc3Bhbj4NCgkJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCQkJPGRpdiBzdHlsZT0iZGlzcGxheTogaW5saW5lLWJsb2NrO3dpZHRoOiAxMDAlO2Zsb2F0OiBsZWZ0OyI+DQoJCQkJCQkJCQkJPGxhYmVsIHN0eWxlPSJmb250LXNpemU6IDE1cHg7Y29sb3I6ICM5OTk5OTk7cGFkZGluZy1yaWdodDogNXB4O21pbi13aWR0aDogOTVweDt3aGl0ZS1zcGFjZTogbm93cmFwO2Zsb2F0OiBsZWZ0O2xpbmUtaGVpZ2h0OiAyNXB4OyI+QWRkcmVzcyA6IDwvbGFiZWw+DQoJCQkJCQkJCQkJPHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMTVweDtmb250LXdlaWdodDogNDAwO2NvbG9yOiAjNjA2MDYwO2xpbmUtaGVpZ2h0OiAyNXB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiA3NiU7d29yZC13cmFwOiBicmVhay13b3JkO21heC1oZWlnaHQ6IDcwcHg7b3ZlcmZsb3c6IGF1dG87Ij4ge3thZGRyZXNzfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPk5vdGVzIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e25vdGVzfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJCTxkaXYgc3R5bGU9ImRpc3BsYXk6IGlubGluZS1ibG9jazt3aWR0aDogMTAwJTtmbG9hdDogbGVmdDsiPg0KCQkJCQkJCQkJCTxsYWJlbCBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2NvbG9yOiAjOTk5OTk5O3BhZGRpbmctcmlnaHQ6IDVweDttaW4td2lkdGg6IDk1cHg7d2hpdGUtc3BhY2U6IG5vd3JhcDtmbG9hdDogbGVmdDtsaW5lLWhlaWdodDogMjVweDsiPkNvbnRhY3QgU3RhdHVzIDogPC9sYWJlbD4NCgkJCQkJCQkJCQk8c3BhbiBzdHlsZT0iZm9udC1zaXplOiAxNXB4O2ZvbnQtd2VpZ2h0OiA0MDA7Y29sb3I6ICM2MDYwNjA7bGluZS1oZWlnaHQ6IDI1cHg7ZmxvYXQ6IGxlZnQ7d2lkdGg6IDc2JTt3b3JkLXdyYXA6IGJyZWFrLXdvcmQ7bWF4LWhlaWdodDogNzBweDtvdmVyZmxvdzogYXV0bzsiPiB7e2NvbnRhY3Rfc3RhdHVzfX08L3NwYW4+DQoJCQkJCQkJCQk8L2Rpdj4NCgkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCTxkaXYgc3R5bGU9InBhZGRpbmc6IDE1cHggMHB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiAxMDAlO2JvcmRlci1ib3R0b206IDFweCBzb2xpZCAjZTZlNmU2OyI+DQoJCQkJCQkJCQk8cCBzdHlsZT0iY29sb3I6ICM2MDYwNjA7Zm9udC1zaXplOiAxNXB4O2xpbmUtaGVpZ2h0OiAyMnB4O21hcmdpbjogMTBweCAwcHggMTVweDtmbG9hdDogbGVmdDsiPjwvcD4NCgkJCQkJCQkJPC9kaXY+DQoJCQkJCQkJCTxkaXYgc3R5bGU9InBhZGRpbmc6IDEwcHggMHB4O2Zsb2F0OiBsZWZ0O3dpZHRoOiAxMDAlO2Rpc3BsYXk6IGJsb2NrO3RleHQtYWxpZ246IGNlbnRlcjsiPg0KCQkJCQkJCQkJPGg1IHN0eWxlPSJjb2xvcjogIzYwNjA2MDtmb250LXNpemU6IDEzcHg7bWFyZ2luOiAwcHggMHB4IDVweDsiPlRoYW5rIHlvdTwvaDU+DQoJCQkJCQkJCTwvZGl2Pg0KCQkJCQkJCTwvZGl2Pg0KCQkJCQkJPC90ZD4JCQkJCQ0KCQkJCQk8L3RyPgkJCQkNCgkJCQk8L3Rib2R5Pg0KCQkJPC90YWJsZT4JDQoJCTwvZGl2Pg0KCTwvZGl2PgkNCjwvYm9keT4NCjwvaHRtbD4=', 'E', 'RM', 'S');";
        mysqli_query($this->conn,$email_template_insert);
    }
    /*ADDED NEWLY END*/
	public function q30()
    {
        $query = "CREATE TABLE IF NOT EXISTS `ct_staff_status` (
		  `id` int(50) NOT NULL AUTO_INCREMENT,
		  `staff_id` int(50) NOT NULL,
		  `order_id` int(50) NOT NULL,
		  `status` enum('A','D') NOT NULL DEFAULT 'D',
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=200;" ;
			
        mysqli_query($this->conn, $query);
    }
}
