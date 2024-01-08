-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: Jun 06, 2023 at 03:40 AM
-- Server version: 5.7.34
-- PHP Version: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `release_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `balance_pending` decimal(24,2) NOT NULL DEFAULT '0.00',
  `received_balance` decimal(24,2) NOT NULL DEFAULT '0.00',
  `account_payable` decimal(24,2) NOT NULL DEFAULT '0.00',
  `account_receivable` decimal(24,2) NOT NULL DEFAULT '0.00',
  `total_withdrawn` decimal(24,2) NOT NULL DEFAULT '0.00',
  `total_expense` decimal(24,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `added_to_carts`
--

CREATE TABLE `added_to_carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `count` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bank_details`
--

CREATE TABLE `bank_details` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bank_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `branch_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acc_no` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `acc_holder_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `routing_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banners`
--

CREATE TABLE `banners` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `banner_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resource_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resource_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'def.png',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `readable_id` bigint(20) NOT NULL,
  `customer_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zone_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `is_paid` tinyint(1) NOT NULL DEFAULT '0',
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cash',
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_booking_amount` decimal(24,3) NOT NULL DEFAULT '0.000',
  `total_tax_amount` decimal(24,3) NOT NULL DEFAULT '0.000',
  `total_discount_amount` decimal(24,3) NOT NULL DEFAULT '0.000',
  `service_schedule` datetime DEFAULT NULL,
  `service_address_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_category_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `serviceman_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_campaign_discount_amount` decimal(24,3) NOT NULL DEFAULT '0.000',
  `total_coupon_discount_amount` decimal(24,3) NOT NULL DEFAULT '0.000',
  `coupon_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_checked` tinyint(1) NOT NULL DEFAULT '0',
  `additional_charge` decimal(24,2) NOT NULL DEFAULT '0.00',
  `additional_tax_amount` decimal(24,2) NOT NULL DEFAULT '0.00',
  `additional_discount_amount` decimal(24,2) NOT NULL DEFAULT '0.00',
  `additional_campaign_discount_amount` decimal(24,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_details`
--

CREATE TABLE `booking_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `variant_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_cost` decimal(24,3) NOT NULL DEFAULT '0.000',
  `quantity` int(11) NOT NULL DEFAULT '1',
  `discount_amount` decimal(24,3) NOT NULL DEFAULT '0.000',
  `tax_amount` decimal(24,3) NOT NULL DEFAULT '0.000',
  `total_cost` decimal(24,3) NOT NULL DEFAULT '0.000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `campaign_discount_amount` decimal(24,3) NOT NULL DEFAULT '0.000',
  `overall_coupon_discount_amount` decimal(24,3) NOT NULL DEFAULT '0.000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_details_amounts`
--

CREATE TABLE `booking_details_amounts` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_details_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_unit_cost` decimal(24,2) NOT NULL DEFAULT '0.00',
  `service_quantity` int(11) NOT NULL DEFAULT '0',
  `service_tax` decimal(24,2) NOT NULL DEFAULT '0.00',
  `discount_by_admin` decimal(24,2) NOT NULL DEFAULT '0.00',
  `discount_by_provider` decimal(24,2) NOT NULL DEFAULT '0.00',
  `coupon_discount_by_admin` decimal(24,2) NOT NULL DEFAULT '0.00',
  `coupon_discount_by_provider` decimal(24,2) NOT NULL DEFAULT '0.00',
  `campaign_discount_by_admin` decimal(24,2) NOT NULL DEFAULT '0.00',
  `campaign_discount_by_provider` decimal(24,2) NOT NULL DEFAULT '0.00',
  `admin_commission` decimal(24,2) NOT NULL DEFAULT '0.00',
  `provider_earning` decimal(24,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_schedule_histories`
--

CREATE TABLE `booking_schedule_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `changed_by` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `schedule` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_status_histories`
--

CREATE TABLE `booking_status_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `changed_by` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `business_settings`
--

CREATE TABLE `business_settings` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:guid)',
  `key_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `live_values` longtext COLLATE utf8mb4_unicode_ci,
  `test_values` longtext COLLATE utf8mb4_unicode_ci,
  `settings_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mode` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'live',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `business_settings`
--

INSERT INTO `business_settings` (`id`, `key_name`, `live_values`, `test_values`, `settings_type`, `mode`, `is_active`, `created_at`, `updated_at`) VALUES
('0098459d-9115-4c58-a6f8-becc65d3cb97', 'service_section_image', '\"2022-10-04-633bfb7862d95.png\"', '\"2022-10-04-633bfb7862d95.png\"', 'landing_images', 'live', 0, '2022-10-03 17:37:21', '2022-10-04 16:23:04'),
('01b2c108-18fe-4ad0-8693-04be1bfa59aa', 'cancellation_policy', '\"<p>Privacy and Confidentialit<\\/p>\\r\\n\\r\\n<p>Test12345hhhh jhjhjhjh<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>Welcome to the daraz.com.bd website (the \\\\&quot;Site\\\\&quot;) operated by Daraz Bangladesh Ltd. , We respect your privacy and want to protect your personal information. To learn more, please read this Privacy Policy.<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>&nbsp;<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<ol>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>This Privacy Policy explains how we collect, use and (under certain conditions) disclose your personal information. This Privacy Policy also explains the steps we have taken to secure your personal information. Finally, this Privacy Policy explains your options regarding the collection, use and disclosure of your personal information. By visiting the Site directly or through another site, you accept the practices described in this Policy.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Data protection is a matter of trust and your privacy is important to us. We shall therefore only use your name and other information which relates to you in the manner set out in this Privacy Policy. We will only collect information where it is necessary for us to do so and we will only collect information if it is relevant to our dealings with you.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>We will only keep your information for as long as we are either required to by law or as is relevant for the purposes for which it was collected.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>We will cease to retain your personal data, or remove the means by which the data can be associated with you, as soon as it is reasonable to assume that such retention no longer serves the purposes for which the personal data was collected, and is no longer necessary for any legal or business purpose.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>You can visit the Site and browse without having to provide personal details. During your visit to the Site you remain anonymous and at no time can we identify you unless you have an account on the Site and log on with your user name and password.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Data that we collect\\\\n\\r\\n\\t<ol>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>We may collect various pieces of information if you seek to place an order for a product with us on the Site.<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>We collect, store and process your data for processing your purchase on the Site and any possible later claims, and to provide you with our services. We may collect personal information including, but not limited to, your title, name, gender, date of birth, email address, postal address, delivery address (if different), telephone number, mobile number, fax number, payment details, payment card details or bank account details.\\\\n\\r\\n\\t\\t<ol>\\r\\n\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t<li>Daraz shall collect the following information where you are a buyer:\\\\n\\r\\n\\t\\t\\t<ol>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Identity data, such as your name, gender, profile picture, and date of birth;<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Contact data, such as billing address, delivery address\\/location, email address and phone numbers;<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Biometric data, such as voice files and face recognition when you use our voice search function, and your facial features of when you use the Site;<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Billing account information: bank account details, credit card account and payment information (such account data may also be collected directly by our affiliates and\\/or third party payment service providers);<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Transaction records\\/data, such as details about orders and payments, user clicks, and other details of products and Services related to you;<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Technical data, such as Internet protocol (IP) address, your login data, browser type and version, time zone setting and location, device information, browser plug-in types and versions, operating system and platform, international mobile equipment identity, device identifier, IMEI, MAC address, cookies (where applicable) and other information and technology on the devices you use to access the Site;<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Profile data, such as your username and password, account settings, orders related to you, user research, your interests, preferences, feedback and survey responses;<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Usage data, such as information on how you use the Site, products and Services or view any content on the Site, including the time spent on the Site, items and data searched for on the Site, access times and dates, as well as websites you were visiting before you came to the Site and other similar statistics;<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Location data, such as when you capture and share your location with us in the form of photographs or videos and upload such content to the Site;<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Marketing and communications data, such as your preferences in receiving marketing from us and our third parties, your communication preferences and your chat, email or call history on the Site or with third party customer service providers; and<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Additional information we may request you to submit for due diligence checks or required by relevant authorities as required for identity verification (such as copies of government issued identification, e.g. passport, ID cards, etc.) or if we believe you are violating our Privacy Policy or our Customer Terms and Conditions.<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t<\\/ol>\\r\\n\\t\\t\\t\\\\n<\\/li>\\r\\n\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t<li>Daraz shall collect the following information where you are a seller:\\\\n\\r\\n\\t\\t\\t<ol>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Identity and contact data, such as your name, date of birth or incorporation, company name, address, email address, phone number and other business-related information (e.g. company registration number, business licence, tax information, shareholder and director information, etc.);<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Account data, such as bank account details, bank statements, credit card details and payment details (such account data may also be collected directly by our affiliates and\\/or third party payment service providers);<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Transaction data, such as details about orders and payments, and other details of products and Services related to you;<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Technical data, such as Internet protocol (IP) address, your login data, browser type and version, time zone setting and location, browser plug-in types and versions, operating system and platform, international mobile equipment identity, device identifier, IMEI, MAC address, cookies (where applicable) and other information and technology on the devices you use to access the Site;<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Profile data, such as your username and password, orders related to you, your interests, preferences, feedback and survey responses;<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Usage data, such as information on how you use the Site, products and Services or view any content on the Site, including the time spent on the Site, items and data searched for on the Site, access times and dates, as well as websites you were visiting before you came to the Site and other similar statistics;<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Location data, such as when you capture and share your location with us in the form of photographs or videos and upload such content to the Site;<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Marketing and communications data, such as your preferences in receiving marketing from us and our third parties and your communication preferences and your chat, email or call history on the Site or with our third party seller service providers; and<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Additional information we may request you to submit for authentication (such as copies of government issued identification, e.g. passport, ID cards, etc.) or if we believe you are violating our Privacy Policy or our Terms of Use.<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t<\\/ol>\\r\\n\\t\\t\\t\\\\n<\\/li>\\r\\n\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<\\/ol>\\r\\n\\t\\t\\\\n<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>We will use the information you provide to enable us to process your orders and to provide you with the services and information offered through our website and which you request in the following ways:.\\\\n\\r\\n\\t\\t<ol>\\r\\n\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t<li>If you are a buyer:\\\\n\\r\\n\\t\\t\\t<ol>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Processing your orders for products:\\\\n\\r\\n\\t\\t\\t\\t<ol>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Process orders you submit through the Site;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Deliver the products you have purchased through the Site for which we may pass your personal information on to a third party (e.g. our logistics partner) in order to make delivery of the product to you;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Update you on the delivery of the products;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Provide customer support for your orders;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Verify and carry out payment transactions (including any credit card payments, bank transfers, offline payments, remittances, or e-wallet transactions) in relation to payments related to you and\\/or services used by you. In order to verify and carry out such payment transactions, payment information, which may include personal data, will be transferred to third parties such as our payment service providers;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<\\/ol>\\r\\n\\t\\t\\t\\t\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Providing services\\\\n\\r\\n\\t\\t\\t\\t<ol>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Facilitate your use of the services or access to the Site;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Administer your account with us;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Display your name, username or profile on the Site (including on any reviews you may post);<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Respond to your queries, feedback, claims or disputes, whether directly or through our third party service providers; and<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Display on scoreboards on the Site in relation to campaigns, mobile games or any other activity;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<\\/ol>\\r\\n\\t\\t\\t\\t\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Marketing and advertising:\\\\n\\r\\n\\t\\t\\t\\t<ol>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Provide you with information we think you may find useful or which you have requested from us (provided you have opted to receive such information);<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Send you marketing or promotional information about \\\\\\\\ products and services on the Site from time to time (provided you have opted to receive such information); and<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Help us conduct marketing and advertising;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<\\/ol>\\r\\n\\t\\t\\t\\t\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Legal and operational purposes:\\\\n\\r\\n\\t\\t\\t\\t<ol>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Ascertain your identity in connection with fraud detection purposes;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Compare information, and verify with third parties in order to ensure that the information is accurate;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Process any complaints, feedback, enforcement action you may have lodged with us;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Produce statistics and research for internal and statutory reporting and\\/or record-keeping requirements;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Store, host, back up your personal data;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Prevent or investigate any actual or suspected violations of our Terms of Use, Privacy Policy, fraud, unlawful activity, omission or misconduct, whether relating to your use of Site or any other matter arising from your relationship with us;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Perform due diligence checks;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Comply with legal and regulatory requirements (including, where applicable, the display of your name, contact details and company details), including any law enforcement requests, in connection with any legal proceedings, or otherwise deemed necessary by us; and<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Where necessary to prevent a threat to life, health or safety.<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<\\/ol>\\r\\n\\t\\t\\t\\t\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Analytics, research, business and development:\\\\n\\r\\n\\t\\t\\t\\t<ol>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Understand your user experience on the Site;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Improve the layout or content of the pages of the Site and customize them for users;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Identify visitors on the Site;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Conduct surveys, including carrying out research on our users&rsquo; demographics and behavior;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Derive further attributes relating to you based on personal data provided by you (whether to us or third parties), in order to provide you with more targeted and\\/or relevant information;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Conduct data analysis, testing and research, monitoring and analyzing usage and activity trends;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Further develop our products and services; and<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<\\/ol>\\r\\n\\t\\t\\t\\t\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Other\\\\n\\r\\n\\t\\t\\t\\t<ol>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Any other purpose to which your consent has been obtained; and<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Conduct automated decision-making processes in accordance with any of the above purposes.<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<\\/ol>\\r\\n\\t\\t\\t\\t\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t<\\/ol>\\r\\n\\t\\t\\t\\\\n<\\/li>\\r\\n\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t<li>If you are a seller:\\\\n\\r\\n\\t\\t\\t<ol>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Providing Services\\\\n\\r\\n\\t\\t\\t\\t<ol>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To facilitate your use of the Site;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To ship or deliver the products you have listed or sold through the Site. We may pass your personal information on to a third party (e.g. our logistics partners) or relevant regulatory authority (e.g. customs) in order to carry out shipping or delivery of the products listed or sold by you;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To respond to your queries, feedback, claims or disputes, whether directly or through our third party service agents;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To verify your documentation submitted to us facilitate your onboarding with us as a seller on the Site, including the testing of technologies to enable faster and more efficient onboarding;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To administer your account (if any) with us;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To display your name, username or profile on the Site;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To verify and carry out financial transactions (including any credit card payments, bank transfers, offline payments, remittances, or e-wallet transactions) in relation to payments related to you and\\/or Services used by you. In order to verify and carry out such payment transactions, payment information, which may include personal data, will be transferred to third parties such as our payment service providers;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To assess your application for loan facilities and\\/or to perform credit risk assessments in relation to your application for seller financing (where applicable);<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To provide you with ancillary logistics services to protect against risks of failed deliveries or customer returns; and<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To facilitate the return of products to you (which may be through our logistics partner).<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<\\/ol>\\r\\n\\t\\t\\t\\t\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Marketing and advertising\\\\n\\r\\n\\t\\t\\t\\t<ol>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To send you marketing or promotional materials about our or third-party sellers&rsquo; products and services on our Site from time to time (provided you have opted to receive such information); and<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To help us conduct marketing and advertising.<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<\\/ol>\\r\\n\\t\\t\\t\\t\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Legal and operational purposes\\\\n\\r\\n\\t\\t\\t\\t<ol>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To produce statistics and research for internal and statutory reporting and\\/or record-keeping requirements;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To store, host, back up your personal data;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To prevent or investigate any actual or suspected violations of our Terms of Use, Privacy Policy, fraud, unlawful activity, omission or misconduct, whether relating to your use of our Services or any other matter arising from your relationship with us;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To comply with legal and regulatory requirements (including, where applicable, the display of your name, contact details and company details), including any law enforcement requests, in connection with any legal proceedings or otherwise deemed necessary by us;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Where necessary to prevent a threat to life, health or safety;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To process any complaints, feedback, enforcement action and take-down requests in relation to any content you have uploaded to the Site;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To compare information, and verify with third parties in order to ensure that the information is accurate;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To ascertain your identity in connection with fraud detection purposes; and<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To facilitate the takedown of prohibited and controlled items from our Site.<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<\\/ol>\\r\\n\\t\\t\\t\\t\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Analytics, research, business and development\\\\n\\r\\n\\t\\t\\t\\t<ol>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To audit the downloading of data from the Site;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To understand the user experience with the Services and the Site;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To improve the layout or content of the pages of the Site and customise them for users;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To conduct surveys, including carrying out research on our users&rsquo; demographics and behaviour to improve our current technology (e.g. voice recognition tech, etc) via machine learning or other means;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To derive further attributes relating to you based on personal data provided by you (whether to us or third parties), in order to provide you with more targeted and\\/or relevant information;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To conduct data analysis, testing and research, monitoring and analysing usage and activity trends;<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To further develop our products and services; and<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To know our sellers better.<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<\\/ol>\\r\\n\\t\\t\\t\\t\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>Other\\\\n\\r\\n\\t\\t\\t\\t<ol>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>Any other purpose to which your consent has been obtained; and<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>To conduct automated decision-making processes in accordance with any of these purposes.<\\/li>\\r\\n\\t\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<\\/ol>\\r\\n\\t\\t\\t\\t\\\\n<\\/li>\\r\\n\\t\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t<\\/ol>\\r\\n\\t\\t\\t\\\\n<\\/li>\\r\\n\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<\\/ol>\\r\\n\\t\\t\\\\n<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>Further, we will use the information you provide to administer your account with us; verify and carry out financial transactions in relation to payments you make; audit the downloading of data from our website; improve the layout and\\/or content of the pages of our website and customize them for users; identify visitors on our website; carry out research on our users&#39; demographics; send you information we think you may find useful or which you have requested from us, including information about our products and services, provided you have indicated that you have not objected to being contacted for these purposes.<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>Subject to obtaining your consent we may contact you by email with details of other products and services. You may unsubscribe from receiving marketing information at any time in our mobile application settings or by using the unsubscribe function within the electronic marketing material. We may use your contact information to send newsletters from us and from our related companies. If you prefer not to receive any marketing communications from us, you can opt out at any time.<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>We may pass your name and address on to a third party in order to make delivery of the product to you (for example to our courier or supplier). You must only submit to us the Site information which is accurate and not misleading and you must keep it up to date and are responsible for informing us of changes to your personal data, or in the event you believe that the personal data we have about you is inaccurate, incomplete, misleading or out of date.inform us of changes. You can update your personal data anytime by accessing your account on the Site.<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>Your actual order details may be stored with us but for security reasons cannot be retrieved directly by us. However, you may access this information by logging into your account on the Site. Here you can view the details of your orders that have been completed, those which are open and those which are shortly to be dispatched and administer your address details, bank details ( for refund purposes) and any newsletter to which you may have subscribed. You undertake to treat the personal access data confidentially and not make it available to unauthorized third parties. We cannot assume any liability for misuse of passwords unless this misuse is our fault.<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t<\\/ol>\\r\\n\\t\\\\n<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Other uses of your Personal Information\\\\n\\r\\n\\t<ol>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>We may use your personal information for opinion and market research. Your details are anonymous and will only be used for statistical purposes. You can choose to opt out of this at any time. Any answers to surveys or opinion polls we may ask you to complete will not be forwarded on to third parties. Disclosing your email address is only necessary if you would like to take part in competitions. We save the answers to our surveys separately from your email address.<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>We may also send you other information about us, the Site, our other websites, our products, sales promotions, our newsletters, anything relating to other companies in our group or our business partners. If you would prefer not to receive any of this additional information as detailed in this paragraph (or any part of it) please click the &#39;unsubscribe&#39; link in any email that we send to you. Within 7 working days (days which are neither (i) a Sunday, nor (ii) a public holiday anywhere in Bangladesh) of receipt of your instruction we will cease to send you information as requested. If your instruction is unclear we will contact you for clarification.<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>We may further anonymize data about users of the Site generally and use it for various purposes, including ascertaining the general location of the users and usage of certain aspects of the Site or a link contained in an email to those registered to receive them, and supplying that anonymized data to third parties such as publishers. However, that anonymized data will not be capable of identifying you personally.<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t<\\/ol>\\r\\n\\t\\\\n<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Competitions\\\\n\\r\\n\\t<ol>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>For any competition we use the data to notify winners and advertise our offers. You can find more details where applicable in our participation terms for the respective competition.<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t<\\/ol>\\r\\n\\t\\\\n<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Third Parties and Links\\\\n\\r\\n\\t<ol>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>We may pass your details to other companies in our group. We may also pass your details to our agents and subcontractors to help us with any of our uses of your data set out in our Privacy Policy. For example, we may use third parties to assist us with delivering products to you, to help us to collect payments from you, to analyze data and to provide us with marketing or customer service assistance. We may also exchange information with third parties for the purposes of fraud protection and credit risk reduction.<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>We may share (or permit the sharing of) your personal data with and\\/or transfer your personal data to third parties and\\/or our affiliates for the above-mentioned purposes. These third parties and affiliates, which may be located inside or outside your jurisdiction, include but are not limited to:\\\\n\\r\\n\\t\\t<ol>\\r\\n\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t<li>Service providers (such as agents, vendors, contractors and partners) in areas such as payment services, logistics and shipping, marketing, data analytics, market or consumer research, survey, social media, customer service, installation services, information technology and website hosting;<\\/li>\\r\\n\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t<li>Their service providers and related companies; and<\\/li>\\r\\n\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t\\t<li>Other users of the Site.<\\/li>\\r\\n\\t\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<\\/ol>\\r\\n\\t\\t\\\\n<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>We may transfer our databases containing your personal information if we sell our business or part of it, provided that we satisfy the requirements of applicable data protection law when disclosing your personal data. Other than as set out in this Privacy Policy, we shall NOT sell or disclose your personal data to third parties without obtaining your prior consent unless this is necessary for the purposes set out in this Privacy Policy or unless we are required to do so by law. The Site may contain advertising of third parties and links to other sites or frames of other sites. Please be aware that we are not responsible for the privacy practices or content of those third parties or other sites, nor for any third party to whom we transfer your data in accordance with our Privacy Policy. You are advised to check on the applicable privacy policies of those websites to determine how they will handle any information they collect from you.<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>In disclosing your personal data to third parties, we endeavor to ensure that the third parties and our affiliates keep your personal data secure from unauthorized access, collection, use, disclosure, processing or similar risks and retain your personal data only for as long as your personal data helps with any of the uses of your data as set out in our Privacy Policy.<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>We may transfer or permit the transfer of your personal data outside of Bangladesh for any of the purposes set out in this Privacy Policy. However, we will not transfer or permit any of your personal data to be transferred outside of Bangladesh unless the transfer is in compliance with applicable laws and this Privacy Policy.<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>We may share your personal data with our third party service providers or affiliates (e.g. payment service providers) in order for them to offer services to you other than those related to your use of the Site. Your acceptance and use of the third party service provider&rsquo;s or our affiliate&rsquo;s services shall be subject to terms and conditions as may be agreed between you and the third party service provider or our affiliate. Upon your acceptance of the third party service provider&rsquo;s or our affiliate&rsquo;s service offering, the collection, use, disclosure, storage, transfer and processing of your data (including your personal data and any data disclosed by us to such third party service provider or affiliate) shall be subject to the applicable privacy policy of the third party service provider or our affiliate, which shall be the data controller of such data. You agree that any queries or complaints relating to your acceptance or use of the third party service provider&rsquo;s or our affiliate&rsquo;s services shall be directed to the party named in the applicable privacy policy.<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t<\\/ol>\\r\\n\\t\\\\n<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Cookies\\\\n\\r\\n\\t<ol>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>We or our authorised service providers may use cookies, web beacons, and other similar technologies in connection with your use of the Site.<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>The acceptance of cookies is not a requirement for visiting the Site. However, we would like to point out that the use of the &#39;basket&#39; functionality on the Site and ordering is only possible with the activation of cookies. Cookies are small text files (typically made up of letters and numbers) placed in the memory of your browser or device when you visit a website or view a message. They allow us to recognise a particular device or browser. Web beacons are small graphic images that may be included on the Site. They allow us to count users who have viewed these pages so that we can better understand your preference and interests. Cookies are tiny text files which identify your computer to our server as a unique user when you visit certain pages on the Site and they are stored by your Internet browser on your computer&#39;s hard drive. Cookies can be used to recognize your Internet Protocol address, saving you time while you are on, or want to enter, the Site. We only use cookies for your convenience in using the Site (for example to remember who you are when you want to amend your shopping cart without having to re-enter your email address) and not for obtaining or using any other information about you (for example targeted advertising). However, certain cookies are required to enable core functionality (such as adding items to your shopping basket), so please note that changing and deleting cookies may affect the functionality available on the Sit. Your browser can be set to not accept cookies, but this would restrict your use of the Site. Please accept our assurance that our use of cookies does not contain any personal or private details and are free from viruses. If you want to find out more information about cookies, go to&nbsp;<a href=\\\"%5C%22https:\\/\\/www.allaboutcookies.org\\/%5C%22\\\" target=\\\"\\\\\\\">all-about-cookies<\\/a> or to find out about removing them from your browser, go to&nbsp;<a href=\\\"%5C%22https:\\/\\/www.allaboutcookies.org\\/manage-cookies\\/index.html%5C%22\\\" target=\\\"\\\\\\\">here<\\/a>.<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>This website uses Google Analytics, a web analytics service provided by Google, Inc. (\\\\&quot;Google\\\\&quot;). Google Analytics uses cookies, which are text files placed on your computer, to help the website analyze how users use the site. The information generated by the cookie about your use of the website (including your IP address) will be transmitted to and stored by Google on servers in the United States. Google will use this information for the purpose of evaluating your use of the website, compiling reports on website activity for website operators and providing other services relating to website activity and internet usage. Google may also transfer this information to third parties where required to do so by law, or where such third parties process the information on Google&#39;s behalf. Google will not associate your IP address with any other data held by Google. You may refuse the use of cookies by selecting the appropriate settings on your browser, however please note that if you do this you may not be able to use the full functionality of this website. By using this website, you consent to the processing of data about you by Google in the manner and for the purposes set out above.<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t<\\/ol>\\r\\n\\t\\\\n<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Security\\\\n\\r\\n\\t<ol>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>We have in place appropriate technical and security measures to prevent unauthorized or unlawful access to or accidental loss of or destruction or damage to your information. When we collect data through the Site, we collect your personal details on a secure server. We use firewalls on our servers. Our security procedures mean that we may occasionally request proof of identity before we disclose personal information to you. You are responsible for protecting against unauthorized access to your password and to your computer.<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>You should be aware, however, that no method of transmission over the Internet or method of electronic storage is completely secure. While security cannot be guaranteed, we strive to protect the security of your information and are constantly reviewing and enhancing our information security measures.<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t<\\/ol>\\r\\n\\t\\\\n<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Your rights\\\\n\\r\\n\\t<ol>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>If you are concerned about your data, you have the right to request access to the personal data which we may hold or process about you. You have the right to require us to correct any inaccuracies in your data free of charge. At any stage you also have the right to ask us to stop using your personal data for direct marketing purposes.<br \\/>\\r\\n\\t\\tWhere permitted by applicable data protection laws, we reserve the right to charge a reasonable administrative fee for retrieving your personal data records. If so, we will inform you of the fee before processing your request.<br \\/>\\r\\n\\t\\tYou may communicate the withdrawal of your consent to the continued use, disclosure, storing and\\/or processing of your personal data by contacting our customer services, subject to the conditions and\\/or limitations imposed by applicable laws or regulations. Please note that if you communicate your withdrawal of your consent to our use, disclosure, storing or processing of your personal data for the purposes and in the manner as stated above or exercise your other rights as available under applicable local laws, we may not be in a position to continue to provide the Services to you or perform any contract we have with you, and we will not be liable in the event that we do not continue to provide the Services to, or perform our contract with you. Our legal rights and remedies are expressly reserved in such an event.<br \\/>\\r\\n\\t\\t<br \\/>\\r\\n\\t\\tFurthermore, you also have the right to ask us to delete your data. If you would like to have your data deleted, fill out the&nbsp;<a href=\\\"%5C%22https:\\/\\/ai.alimebot.daraz.com.bd\\/intl\\/index.htm?from=0zKpjMUW7x&amp;attemptquery=account_deactivation_form%5C%22\\\">Account Deactivation\\/Deletion Request Form&nbsp;<\\/a>(&ldquo;Deletion Request&rdquo;) or email your request to&nbsp;<strong>customer.bd@care.daraz.com<\\/strong>. Once your request is received, we follow an internal deletion process to make sure that your data is safely removed in the next fifteen (15) working days. You&#39;ll be contacted for verification and your account will be deleted after necessary protocols are conformed to. Read more about the deletion process&nbsp;<a href=\\\"%5C%22https:\\/\\/helpcenter.daraz.com.bd\\/page\\/knowledge?pageId=40&amp;knowledge=1000005458%5C%22\\\">here<\\/a>.<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t<\\/ol>\\r\\n\\t\\\\n<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Minors\\\\n\\r\\n\\t<ol>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>We do not sell products to minors, i.e. individuals below the age of 18, on the Site and we do not knowingly collect any personal data relating to minors. You hereby confirm and warrant that you are above the age of 18 and are capable of understanding and accepting the terms of this Privacy Policy.<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>If you allow a minor to access the Site and buy products from the Site by using your account, you hereby consent to the processing of the minor&rsquo;s personal data and accept and agree to be bound by this Privacy Policy and take responsibility for his or her actions.<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li>We will not be responsible for any unauthorized use of your account on the Site by yourself, users who act on your behalf or any unauthorized users. It is your responsibility to make your own informed decisions about the use of the Site and take necessary steps to prevent any misuse of the Site.<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t<\\/ol>\\r\\n\\t\\\\n<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n<\\/ol>\\r\\n\\r\\n<p>&quot;<\\/p>\"', NULL, 'pages_setup', 'live', 0, '2022-08-06 03:54:38', '2022-10-04 11:10:03'),
('053c7a7b-75f0-4e01-9961-5e81fdd001f6', 'email_verification', '1', '1', 'service_setup', 'live', 1, '2022-07-21 11:59:22', '2022-08-13 07:35:03'),
('078c60ff-a05f-4386-88c4-9b48e98dc7dd', 'releans', '{\"gateway\":\"releans\",\"mode\":\"live\",\"status\":\"1\",\"api_key\":\"data\",\"from\":\"data\",\"otp_template\":\"data\"}', '{\"gateway\":\"releans\",\"mode\":\"live\",\"status\":\"1\",\"api_key\":\"data\",\"from\":\"data\",\"otp_template\":\"data\"}', 'sms_config', 'live', 1, '2022-06-08 06:44:58', '2022-10-04 16:26:11'),
('0a2e5ef2-4e4e-40b1-8ea6-9455a3549891', 'forget_password_verification_method', '\"email\"', '\"email\"', 'business_information', 'live', 1, '2023-05-29 16:22:38', '2023-05-29 16:22:38'),
('0b0ac068-a661-4e13-ab25-858b9f9bab9d', 'currency_code', '\"USD\"', '\"USD\"', 'business_information', 'live', 1, '2022-06-14 09:39:24', '2022-10-04 16:21:24'),
('0d08217a-f52d-4ce2-a63c-88cd1445193f', 'provider_can_cancel_booking', '\"0\"', '\"0\"', 'service_setup', 'live', 0, '2022-07-20 06:04:17', '2022-10-04 16:00:16'),
('14290db3-1876-44a3-a70d-1410d753d673', 'campaign_cost_bearer', '{\"bearer\":\"provider\",\"admin_percentage\":0,\"provider_percentage\":100,\"type\":\"campaign\"}', '{\"bearer\":\"provider\",\"admin_percentage\":0,\"provider_percentage\":100,\"type\":\"campaign\"}', 'promotional_setup', 'live', 1, '2023-01-22 17:33:48', '2023-01-22 17:33:48'),
('14eaf75b-68f2-412b-8062-189cff9582cc', 'app_url_appstore', '\"\\/\"', '\"\\/\"', 'landing_button_and_links', 'live', 1, '2022-10-03 16:00:01', '2022-10-04 16:22:24'),
('16212625-a1cb-428e-b201-1975495a32cc', 'provider_self_registration', '\"0\"', '\"0\"', 'service_setup', 'live', 0, '2022-07-21 11:59:22', '2022-10-04 16:00:26'),
('18ff5091-1416-4b68-8a11-4a4db54d94bc', 'rating_review', '{\"push_notification_rating_review\":\"1\",\"email_rating_review\":\"1\"}', '{\"push_notification_rating_review\":\"1\",\"email_rating_review\":\"1\"}', 'notification_settings', 'live', 1, '2022-06-06 12:41:28', '2022-08-16 07:43:35'),
('193e005b-a715-4f6f-97cc-2554377b1f28', 'app_url_playstore', '\"\\/\"', '\"\\/\"', 'landing_button_and_links', 'live', 1, '2022-10-03 16:00:01', '2022-10-04 16:22:24'),
('1acfd678-38f4-4aab-8431-7f066e8de7f2', 'phone_verification', '0', '0', 'service_setup', 'live', 1, '2023-05-29 16:22:38', '2023-05-29 16:22:38'),
('1bc292a4-4244-4eb2-8760-5c0bd4d5e236', 'default_commission', '\"20\"', '\"20\"', 'business_information', 'live', 1, '2022-08-18 09:14:58', '2022-08-24 02:53:43'),
('1c7e7c69-dd9d-4e7b-9379-b5314bb6ec58', 'testimonial', '[{\"id\":\"978915c4-0bfd-4a1d-9dee-04c3852bfabd\",\"name\":\"Mike\",\"designation\":\"Designer\",\"review\":\"Thank you! That was very helpful! The Service men were very professionals & very caution about safety\",\"image\":\"2022-10-03-633ab162070fe.png\"}]', '[{\"id\":\"978915c4-0bfd-4a1d-9dee-04c3852bfabd\",\"name\":\"Mike\",\"designation\":\"Designer\",\"review\":\"Thank you! That was very helpful! The Service men were very professionals & very caution about safety\",\"image\":\"2022-10-03-633ab162070fe.png\"}]', 'landing_testimonial', 'live', 0, '2022-10-03 16:54:42', '2022-10-03 16:54:42'),
('1e8316f2-660a-44c5-b24c-97320ae212d0', 'booking_service_complete', '{\"booking_service_complete_status\":\"1\",\"booking_service_complete_message\":\"Booking Service successfully complete done\"}', '{\"booking_service_complete_status\":\"1\",\"booking_service_complete_message\":\"Booking Service successfully complete done\"}', 'notification_messages', 'live', 1, '2022-06-06 12:41:28', '2022-09-14 17:44:04'),
('2dd9ca52-ebd2-45b4-9d38-30a6a16b44ca', 'top_title', '\"Customer Statisfaciton is our main moto\"', '\"Customer Statisfaciton is our main moto\"', 'landing_text_setup', 'live', 0, '2022-10-03 15:36:11', '2022-10-03 15:36:11'),
('31c5e759-3d31-4522-8ed7-2a067c623c68', 'booking_place', '{\"booking_place_status\":\"1\",\"booking_place_message\":\"Booking Service successfully placed\"}', '{\"booking_place_status\":\"1\",\"booking_place_message\":\"Booking Service successfully placed\"}', 'notification_messages', 'live', 1, '2022-06-06 12:41:28', '2022-10-04 16:23:49'),
('35fdbc13-5505-4f08-a480-9a7922fde375', 'senang_pay', '{\"gateway\":\"senang_pay\",\"mode\":\"live\",\"status\":\"0\",\"callback_url\":\"https:\\/\\/url\\/return-senang-pay\",\"secret_key\":\"data\",\"merchant_id\":\"data\"}', '{\"gateway\":\"senang_pay\",\"mode\":\"live\",\"status\":\"0\",\"callback_url\":\"https:\\/\\/url\\/return-senang-pay\",\"secret_key\":\"data\",\"merchant_id\":\"data\"}', 'payment_config', 'live', 0, '2022-06-09 07:21:16', '2022-10-04 16:28:53'),
('382abfc4-4742-4080-9f17-350bbc57d813', 'booking_cancel', '{\"booking_cancel_status\":\"0\",\"booking_cancel_message\":\"Booking Cancel Successfully\"}', '{\"booking_cancel_status\":\"0\",\"booking_cancel_message\":\"Booking Cancel Successfully\"}', 'notification_messages', 'live', 0, '2022-06-06 12:41:28', '2022-09-14 20:11:36'),
('3a9cf40c-c7ec-481c-8a79-5f33b154a561', 'email_config', '{\"mailer_name\":\"name\",\"host\":\"mail.host.com\",\"driver\":\"smtp\",\"port\":\"587\",\"user_name\":\"demo@6am.one\",\"email_id\":\"email@email.com\",\"encryption\":\"tls\",\"password\":\"password\"}', '{\"mailer_name\":\"name\",\"host\":\"mail.host.com\",\"driver\":\"smtp\",\"port\":\"587\",\"user_name\":\"demo@6am.one\",\"email_id\":\"email@email.com\",\"encryption\":\"tls\",\"password\":\"password\"}', 'email_config', 'live', 1, '2022-06-07 12:32:47', '2022-10-04 16:25:45'),
('3b0b4644-9ba9-48e9-8622-59426198e3b9', 'time_zone', '\"Pacific\\/Fakaofo\"', '\"Pacific\\/Fakaofo\"', 'business_information', 'live', 1, '2022-06-14 09:39:24', '2022-09-14 06:58:36'),
('3dd386b8-066c-48c3-af22-f3f197347ea3', 'customer_wallet', '0', '0', 'customer_config', 'live', 1, '2023-02-23 00:25:16', '2023-02-23 00:25:16'),
('3e0fe0fa-e697-437e-a0a9-9ff4316f4d39', 'about_us_description', '\"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s,\"', '\"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s,\"', 'landing_text_setup', 'live', 0, '2022-10-03 15:36:11', '2022-10-03 15:36:11'),
('3e4c8b9f-28a2-4f72-b3b5-8b5808121dc8', 'recaptcha', '{\"party_name\":\"recaptcha\",\"status\":\"0\",\"site_key\":\"apikey\",\"secret_key\":\"apikey\"}', '{\"party_name\":\"recaptcha\",\"status\":\"0\",\"site_key\":\"apikey\",\"secret_key\":\"apikey\"}', 'third_party', 'live', 0, '2022-07-25 10:57:25', '2022-10-04 16:24:50'),
('4007291b-5078-42ba-9051-fe9c991b9b2f', 'mid_title', '\"SERVICE WE PROVIDE FOR YOU\"', '\"SERVICE WE PROVIDE FOR YOU\"', 'landing_text_setup', 'live', 0, '2022-10-03 15:36:11', '2022-10-03 15:36:11'),
('43c89209-172b-47a1-851a-efb90f848aa0', 'top_image_1', '\"2022-10-04-633bfb5d90bd6.png\"', '\"2022-10-04-633bfb5d90bd6.png\"', 'landing_images', 'live', 0, '2022-10-03 16:06:10', '2022-10-04 16:22:37'),
('45721749-c637-498c-ad0b-5d369a2d6425', 'cookies_text', '\"\"', '\"\"', 'business_information', 'live', 1, '2023-02-23 00:25:16', '2023-02-23 00:25:16'),
('47817d69-8ec9-4730-b81f-838c4fc9d533', 'top_image_3', '\"2022-10-04-633bfb6720e16.png\"', '\"2022-10-04-633bfb6720e16.png\"', 'landing_images', 'live', 0, '2022-10-03 16:06:15', '2022-10-04 16:22:47'),
('4ad0c0ce-157c-46b2-b93d-261ca4f1a575', 'top_image_4', '\"2022-10-04-633bfb6b5e0c0.png\"', '\"2022-10-04-633bfb6b5e0c0.png\"', 'landing_images', 'live', 0, '2022-10-03 16:07:26', '2022-10-04 16:22:51'),
('4cdc5c2e-054d-485b-a906-f0c6032880db', 'subscription', '{\"push_notification_subscription\":\"1\",\"email_subscription\":\"1\"}', '{\"push_notification_subscription\":\"1\",\"email_subscription\":\"1\"}', 'notification_settings', 'live', 1, '2022-06-06 12:41:28', '2022-08-16 07:43:35'),
('4dcb5aae-3a76-4754-b23a-286726a60d61', 'business_logo', '\"2022-10-04-633bfb2164e63.png\"', '\"2022-10-04-633bfb2164e63.png\"', 'business_information', 'live', 1, '2022-06-14 09:39:24', '2022-10-04 16:21:37'),
('4ddd1410-e290-4e3f-a66c-ee70f86368db', 'bottom_title', '\"GET ALL UPDATES & EXCITING NEWS\"', '\"GET ALL UPDATES & EXCITING NEWS\"', 'landing_text_setup', 'live', 0, '2022-10-03 15:36:11', '2022-10-03 16:14:45');
INSERT INTO `business_settings` (`id`, `key_name`, `live_values`, `test_values`, `settings_type`, `mode`, `is_active`, `created_at`, `updated_at`) VALUES
('4e1af097-855e-43b8-ae10-0a0039039706', 'booking_accepted', '{\"booking_accepted_status\":\"0\",\"booking_accepted_message\":\"Booking Service successfully complete done\"}', '{\"booking_accepted_status\":\"0\",\"booking_accepted_message\":\"Booking Service successfully complete done\"}', 'notification_messages', 'live', 0, '2022-06-06 12:41:28', '2022-10-04 16:23:59'),
('4e6fda04-d5b3-4ddd-a086-3c6567346e5c', 'tnc_update', '{\"push_notification_tnc_update\":\"0\",\"email_tnc_update\":0}', '{\"push_notification_tnc_update\":\"0\",\"email_tnc_update\":0}', 'notification_settings', 'live', 1, '2022-06-06 12:41:28', '2022-10-04 16:23:28'),
('539b1d42-0730-418d-83b0-46911e42cc39', 'privacy_policy', '\"\\\"<p>Test 12345<\\/p>\\\\n<p>testv asdfghjk test 12334 l;\' ok hyhyhyh dfgdgdgdg<\\/p>\\\"\"', NULL, 'pages_setup', 'live', 1, '2022-08-06 04:00:09', '2022-09-08 14:37:37'),
('54b42ef4-2f17-4424-aebc-7e5490b97557', 'bottom_description', '\"Subcribe to out newsletters to receive all the latest activty we provide for you\"', '\"Subcribe to out newsletters to receive all the latest activty we provide for you\"', 'landing_text_setup', 'live', 0, '2022-10-03 15:36:11', '2022-10-03 16:14:45'),
('5675c00e-9686-4032-96f9-ce4631b9960a', 'speciality', '[{\"id\":\"5bbe5dbf-d056-4d01-b203-cb7a67db77cb\",\"title\":\"srfasv\",\"description\":\"sadvasvdas\",\"image\":\"2022-10-04-633ac3e50ea3e.png\"}]', '[{\"id\":\"5bbe5dbf-d056-4d01-b203-cb7a67db77cb\",\"title\":\"srfasv\",\"description\":\"sadvasvdas\",\"image\":\"2022-10-04-633ac3e50ea3e.png\"}]', 'landing_speciality', 'live', 0, '2022-10-03 18:13:41', '2022-10-03 18:13:41'),
('57b3d923-139a-430b-a7b8-2c0797110cfd', 'sms_verification', '1', '1', 'service_setup', 'live', 1, '2022-07-21 11:59:22', '2022-08-13 07:35:03'),
('59d855e6-06ad-487a-9ca1-bbd8d3db2dfa', 'stripe', '{\"gateway\":\"stripe\",\"mode\":\"test\",\"status\":\"1\",\"api_key\":\"data\",\"published_key\":\"data\"}', '{\"gateway\":\"stripe\",\"mode\":\"test\",\"status\":\"1\",\"api_key\":\"data\",\"published_key\":\"data\"}', 'payment_config', 'test', 1, '2022-06-09 05:41:48', '2022-10-04 16:28:57'),
('59f6e3d4-382f-47e6-b973-2c1cb2054016', 'digital_payment', '1', '1', 'service_setup', 'live', 1, '2023-05-29 16:22:38', '2023-05-29 16:22:38'),
('5e470acd-7935-4394-ab56-008fdeb65029', 'third_party', '{\"party_name\":\"push_notification\",\"server_key\":\"56789fghjk\"}', '{\"party_name\":\"push_notification\",\"server_key\":\"56789fghjk\"}', 'third_party', 'live', 1, '2022-06-08 10:57:43', '2022-06-08 10:57:43'),
('6170b133-2556-4eb0-b5bf-3352566e5c83', 'booking_ongoing', '{\"booking_ongoing_status\":\"0\",\"booking_ongoing_message\":\"Booking Service successfully complete done\"}', '{\"booking_ongoing_status\":\"0\",\"booking_ongoing_message\":\"Booking Service successfully complete done\"}', 'notification_messages', 'live', 0, '2022-10-04 16:24:02', '2022-10-04 16:24:02'),
('668cdb3b-63a4-4ad4-ac35-407c811576b6', 'flutterwave', '{\"gateway\":\"flutterwave\",\"mode\":\"test\",\"status\":\"1\",\"secret_key\":\"data\",\"public_key\":\"data\",\"hash\":\"data\"}', '{\"gateway\":\"flutterwave\",\"mode\":\"test\",\"status\":\"1\",\"secret_key\":\"data\",\"public_key\":\"data\",\"hash\":\"data\"}', 'payment_config', 'test', 1, '2022-09-03 08:47:46', '2022-10-04 16:29:07'),
('6d3c9600-0fd9-4ebe-a740-22c21c90f619', 'pp_update', '{\"push_notification_pp_update\":\"0\",\"email_pp_update\":0}', '{\"push_notification_pp_update\":\"0\",\"email_pp_update\":0}', 'notification_settings', 'live', 1, '2022-06-06 12:41:28', '2022-10-04 16:23:30'),
('6fcf21de-e1c8-4bd4-ab2c-dff708e79277', 'currency_symbol_position', '\"left\"', '\"left\"', 'business_information', 'live', 1, '2022-06-14 09:39:24', '2022-09-14 06:07:12'),
('7014ab21-7c89-4f6f-9e63-05806292802f', 'customer_loyalty_point', '0', '0', 'customer_config', 'live', 1, '2023-02-23 00:25:16', '2023-02-23 00:25:16'),
('749dc8fa-b2f0-4236-a400-f52fe3b8193b', 'refund_policy', '\"\\\"<div class=\\\\\\\"clearfix\\\\\\\">\\\\n<h4>Test123456<\\/h4>\\\\n<h4>Issuance of Refunds<\\/h4>\\\\n<ul>\\\\n<li>1. The processing time of your refund depends on the type of refund and the payment method you used.<\\/li>\\\\n<li>2. The refund period \\/ process starts when Daraz has processed your refund according to your refund type.<\\/li>\\\\n<li>3. The refund amogunt covers the item price and shipping fee for your returned product.<\\/li>\\\\n<\\/ul>\\\\n<\\/div>\\\\n<div class=\\\\\\\"clearfix\\\\\\\">\\\\n<h4>Refund Types<\\/h4>\\\\n<p>Daraz will process your refund according to the following refund types<\\/p>\\\\n<ul>\\\\n<li>1. Refund from returns - Refund is processed once your item is returned to the warehouse and QC is completed (successful). To learn how to return an item, read our Return Policy.<\\/li>\\\\n<li>2. Refunds from cancelled orders - Refund is automatically triggered once cancelation is successfully processed.<\\/li>\\\\n<li>3. Refunds from failed deliveries - Refund process starts when the item has reached the seller. Please take note that this may take more time depending on the area of your shipping address. Screen reader support enabled.<\\/li>\\\\n<\\/ul>\\\\n<\\/div>\\\\n<div class=\\\\\\\"panel-group\\\\\\\">\\\\n<div class=\\\\\\\"panel panel-default\\\\\\\">\\\\n<table class=\\\\\\\"table table-bordered\\\\\\\">\\\\n<tbody>\\\\n<tr>\\\\n<th class=\\\\\\\"th\\\\\\\">Payment Method<\\/th>\\\\n<th class=\\\\\\\"th\\\\\\\">Refund Option<\\/th>\\\\n<th class=\\\\\\\"th\\\\\\\">Refund Time<\\/th>\\\\n<\\/tr>\\\\n<tr>\\\\n<td width=\\\\\\\"208\\\\\\\">\\\\n<p>Debit or Credit Card<\\/p>\\\\n<\\/td>\\\\n<td width=\\\\\\\"208\\\\\\\">\\\\n<p>Debit or Credit Card Payment Reversal<\\/p>\\\\n<\\/td>\\\\n<td width=\\\\\\\"208\\\\\\\">\\\\n<p>10 working days<\\/p>\\\\n<\\/td>\\\\n<\\/tr>\\\\n<tr>\\\\n<td width=\\\\\\\"208\\\\\\\">\\\\n<p>Equated Monthly Installments<\\/p>\\\\n<\\/td>\\\\n<td width=\\\\\\\"208\\\\\\\">\\\\n<p>Debit or Credit Card<\\/p>\\\\n<\\/td>\\\\n<td width=\\\\\\\"208\\\\\\\">\\\\n<p>10 working days<\\/p>\\\\n<\\/td>\\\\n<\\/tr>\\\\n<tr>\\\\n<td width=\\\\\\\"208\\\\\\\">\\\\n<p>Rocket (Wallet DBBL)<\\/p>\\\\n<\\/td>\\\\n<td width=\\\\\\\"208\\\\\\\">\\\\n<p>Mobile Wallet Reversal \\/ Rocket<\\/p>\\\\n<\\/td>\\\\n<td width=\\\\\\\"208\\\\\\\">\\\\n<p>7 working days<\\/p>\\\\n<\\/td>\\\\n<\\/tr>\\\\n<tr>\\\\n<td width=\\\\\\\"208\\\\\\\">\\\\n<p>DBBL Nexus (Online Banking)<\\/p>\\\\n<\\/td>\\\\n<td width=\\\\\\\"208\\\\\\\">\\\\n<p>Card Payment Reversal (Nexus)<\\/p>\\\\n<\\/td>\\\\n<td width=\\\\\\\"208\\\\\\\">\\\\n<p>7 working days<\\/p>\\\\n<\\/td>\\\\n<\\/tr>\\\\n<tr>\\\\n<td width=\\\\\\\"208\\\\\\\">\\\\n<p>bKash<\\/p>\\\\n<\\/td>\\\\n<td width=\\\\\\\"208\\\\\\\">\\\\n<p>Mobile Wallet Reversal \\/ bKash<\\/p>\\\\n<\\/td>\\\\n<td width=\\\\\\\"208\\\\\\\">\\\\n<p>5 working days<\\/p>\\\\n<\\/td>\\\\n<\\/tr>\\\\n<tr>\\\\n<td rowspan=\\\\\\\"2\\\\\\\" width=\\\\\\\"208\\\\\\\">\\\\n<p>Cash on Delivery (COD)<\\/p>\\\\n<\\/td>\\\\n<td width=\\\\\\\"208\\\\\\\">\\\\n<p>Bank Deposit<\\/p>\\\\n<\\/td>\\\\n<td width=\\\\\\\"208\\\\\\\">\\\\n<p>5 working days<\\/p>\\\\n<\\/td>\\\\n<\\/tr>\\\\n<tr>\\\\n<td>\\\\n<p>Daraz Refund Voucher<\\/p>\\\\n<\\/td>\\\\n<td>\\\\n<p>1 working day<\\/p>\\\\n<\\/td>\\\\n<\\/tr>\\\\n<tr>\\\\n<td width=\\\\\\\"208\\\\\\\">\\\\n<p>Daraz Voucher<\\/p>\\\\n<\\/td>\\\\n<td width=\\\\\\\"208\\\\\\\">\\\\n<p>Refund Voucher<\\/p>\\\\n<\\/td>\\\\n<td width=\\\\\\\"208\\\\\\\">\\\\n<p>1 working day<\\/p>\\\\n<\\/td>\\\\n<\\/tr>\\\\n<\\/tbody>\\\\n<\\/table>\\\\n<\\/div>\\\\n<p><strong>Note:<\\/strong>&nbsp;Maximum refund timeline excludes weekends and public holidays.<\\/p>\\\\n<div class=\\\\\\\"panel-group\\\\\\\">\\\\n<div class=\\\\\\\"panel panel-default\\\\\\\">\\\\n<table class=\\\\\\\"table table-bordered\\\\\\\">\\\\n<tbody>\\\\n<tr>\\\\n<th class=\\\\\\\"th\\\\\\\">Modes of Refund<\\/th>\\\\n<th class=\\\\\\\"th\\\\\\\">Description<\\/th>\\\\n<\\/tr>\\\\n<tr>\\\\n<td width=\\\\\\\"208\\\\\\\">\\\\n<p>Bank Deposit<\\/p>\\\\n<\\/td>\\\\n<td width=\\\\\\\"416\\\\\\\" data-spm-anchor-id=\\\\\\\"a2a0e.11887082.4745536990.i2.6b6b18ceSYU3Um\\\\\\\">\\\\n<p>The bank account details provided must be correct. The account must be active and should hold some balance.<\\/p>\\\\n<\\/td>\\\\n<\\/tr>\\\\n<tr>\\\\n<td width=\\\\\\\"208\\\\\\\">\\\\n<p>Debit Card or Credit Card<\\/p>\\\\n<\\/td>\\\\n<td width=\\\\\\\"416\\\\\\\">\\\\n<p>If the refunded amount is not reflecting in your card statement after the refund is completed and you have received a notification by Daraz, please contact your personal bank.<\\/p>\\\\n<\\/td>\\\\n<\\/tr>\\\\n<tr>\\\\n<td width=\\\\\\\"208\\\\\\\">\\\\n<p>bKash \\/ Rocket Mobile Wallet<\\/p>\\\\n<\\/td>\\\\n<td width=\\\\\\\"416\\\\\\\" data-spm-anchor-id=\\\\\\\"a2a0e.11887082.4745536990.i1.6b6b18ceSYU3Um\\\\\\\">\\\\n<p>Similar to bank deposit, the amount will be refunded to the same mobile account details which you inserted at the time of payment.<\\/p>\\\\n<\\/td>\\\\n<\\/tr>\\\\n<tr>\\\\n<td width=\\\\\\\"208\\\\\\\">\\\\n<p>Refund Voucher<\\/p>\\\\n<\\/td>\\\\n<td width=\\\\\\\"416\\\\\\\" data-spm-anchor-id=\\\\\\\"a2a0e.11887082.4745536990.i4.6b6b18ceSYU3Um\\\\\\\">\\\\n<p>Vouchers will be sent to the customer registered email ID on Daraz and can be redeemed against the same email ID.<\\/p>\\\\n<\\/td>\\\\n<\\/tr>\\\\n<\\/tbody>\\\\n<\\/table>\\\\n<\\/div>\\\\n<p><strong>Important Note:&nbsp;<\\/strong>The Voucher discount code can only be applied once. The leftover amount will not be refunded or used for next purchase even if the value of order is smaller than voucher value<\\/p>\\\\n<\\/div>\\\\n<\\/div>\\\"\"', NULL, 'pages_setup', 'live', 1, '2022-08-06 04:02:38', '2022-09-08 14:37:27'),
('7f266d17-6867-499f-bad6-9a8b55ab3ff1', 'order', '{\"push_notification_order\":\"1\",\"email_order\":\"1\"}', '{\"push_notification_order\":\"1\",\"email_order\":\"1\"}', 'notification_settings', 'live', 1, '2022-06-06 12:41:28', '2022-07-23 07:08:34'),
('848492c2-b2c9-4fed-b486-77db4ff141ae', 'msg91', '{\"gateway\":\"msg91\",\"mode\":\"live\",\"status\":\"0\",\"template_id\":\"data\",\"auth_key\":\"data\"}', '{\"gateway\":\"msg91\",\"mode\":\"live\",\"status\":\"0\",\"template_id\":\"data\",\"auth_key\":\"data\"}', 'sms_config', 'live', 0, '2022-06-08 09:06:49', '2022-10-04 16:26:16'),
('873986dd-541b-4648-bd32-edac7504143e', 'nexmo', '{\"gateway\":\"nexmo\",\"mode\":\"live\",\"status\":\"0\",\"api_key\":\"data\",\"api_secret\":\"data\",\"token\":\"data\",\"from\":\"data\",\"otp_template\":\"data\"}', '{\"gateway\":\"nexmo\",\"mode\":\"live\",\"status\":\"0\",\"api_key\":\"data\",\"api_secret\":\"data\",\"token\":\"data\",\"from\":\"data\",\"otp_template\":\"data\"}', 'sms_config', 'live', 0, '2022-06-08 07:19:18', '2022-10-04 16:26:27'),
('89d237b1-861e-4066-9cb6-c9adfd672726', 'schedule_booking', '1', '1', 'service_setup', 'live', 1, '2022-07-20 06:04:14', '2022-08-13 07:35:03'),
('8c296af0-65c5-43f9-aa4a-854cbbf19148', 'pagination_limit', '\"20\"', '\"20\"', 'business_information', 'live', 1, '2022-09-05 10:06:02', '2022-10-04 16:21:24'),
('8d6b0e16-f753-4833-8f79-7d049a50deb7', 'registration_description', '\"Become e provider & Start your own business online with on demand service platform\"', '\"Become e provider & Start your own business online with on demand service platform\"', 'landing_text_setup', 'live', 0, '2022-10-03 15:36:11', '2022-10-03 15:36:11'),
('8dddf0eb-2021-4d16-9d84-687603f71285', 'coupon_cost_bearer', '{\"bearer\":\"provider\",\"admin_percentage\":0,\"provider_percentage\":100,\"type\":\"coupon\"}', '{\"bearer\":\"provider\",\"admin_percentage\":0,\"provider_percentage\":100,\"type\":\"coupon\"}', 'promotional_setup', 'live', 1, '2023-01-22 17:33:48', '2023-01-22 17:33:48'),
('92043fa7-f54f-4733-9dbf-3719970a5b62', 'paytm', '{\"gateway\":\"paytm\",\"mode\":\"test\",\"status\":\"1\",\"merchant_key\":\"data\",\"merchant_id\":\"data\",\"merchant_website_link\":\"data\"}', '{\"gateway\":\"paytm\",\"mode\":\"test\",\"status\":\"1\",\"merchant_key\":\"data\",\"merchant_id\":\"data\",\"merchant_website_link\":\"data\"}', 'payment_config', 'test', 1, '2022-06-09 07:21:49', '2022-10-04 16:29:15'),
('9288358a-cab7-4c5c-b342-75b7ab17c29a', 'business_phone', '\"000000\"', '\"000000\"', 'business_information', 'live', 1, '2022-06-14 09:39:24', '2022-10-04 16:21:24'),
('93670ab4-e54d-46ea-a8ed-101cb968208e', 'about_us_title', '\"WHO WE ARE\"', '\"WHO WE ARE\"', 'landing_text_setup', 'live', 0, '2022-10-03 15:36:11', '2022-10-03 15:36:11'),
('94f715e7-164d-4e05-bb64-e3547d94a5e4', 'business_address', '\"Dhaka Bangladesh\"', '\"Dhaka Bangladesh\"', 'business_information', 'live', 1, '2022-06-14 09:39:24', '2022-10-04 16:21:24'),
('973e0fe3-b6ff-4156-aa9a-78e52e069527', 'booking', '{\"push_notification_booking\":\"0\",\"email_booking\":0}', '{\"push_notification_booking\":\"0\",\"email_booking\":0}', 'notification_settings', 'live', 1, '2022-07-28 04:31:15', '2022-10-04 16:23:32'),
('98a973f9-0ec8-4fe5-b31d-d04c045bab41', 'referral_value_per_currency_unit', '0', '0', 'customer_config', 'live', 1, '2023-02-23 00:25:16', '2023-02-23 00:25:16'),
('9a961fd3-b032-4260-a9dd-93ad7c0b76b8', 'paystack', '{\"gateway\":\"paystack\",\"mode\":\"test\",\"status\":\"1\",\"callback_url\":\"https:\\/\\/api.paystack.co\",\"public_key\":\"data\",\"secret_key\":\"data\",\"merchant_email\":\"data\"}', '{\"gateway\":\"paystack\",\"mode\":\"test\",\"status\":\"1\",\"callback_url\":\"https:\\/\\/api.paystack.co\",\"public_key\":\"data\",\"secret_key\":\"data\",\"merchant_email\":\"data\"}', 'payment_config', 'test', 1, '2022-06-09 06:12:45', '2022-10-04 16:29:25'),
('9ec59242-4fd2-4f54-898c-4b4c60270ecd', 'business_favicon', '\"2022-10-04-633bfb21640b9.png\"', '\"2022-10-04-633bfb21640b9.png\"', 'business_information', 'live', 1, '2022-06-14 09:39:24', '2022-10-04 16:21:37'),
('9ff4b51f-0edd-4a33-ab15-bc79f7542ecd', 'about_us', '\"<p>hello world hero greatth weh fvaaafawefdsdsdsd<\\/p>\"', NULL, 'pages_setup', 'live', 1, '2022-08-04 13:04:19', '2022-10-04 11:57:25'),
('a38599a7-fb8f-4f3c-a955-b975cdd8fae5', 'customer_referral_earning', '0', '0', 'customer_config', 'live', 1, '2023-02-23 00:25:16', '2023-02-23 00:25:16'),
('a6cd4791-0276-4fa4-b2a1-13d3d5a8f232', 'twilio', '{\"gateway\":\"twilio\",\"mode\":\"live\",\"status\":\"0\",\"sid\":\"data\",\"messaging_service_sid\":\"data\",\"token\":\"data\",\"from\":\"data\",\"otp_template\":\"data\"}', '{\"gateway\":\"twilio\",\"mode\":\"live\",\"status\":\"0\",\"sid\":\"data\",\"messaging_service_sid\":\"data\",\"token\":\"data\",\"from\":\"data\",\"otp_template\":\"data\"}', 'sms_config', 'live', 0, '2022-06-08 07:03:02', '2022-10-04 16:26:39'),
('a8c1f49a-be3b-4609-8242-37142ff05acd', 'currency_decimal_point', '\"2\"', '\"2\"', 'business_information', 'live', 1, '2022-06-14 09:39:24', '2022-10-04 16:21:24'),
('a9c93141-a7c9-473b-ac46-b3dadc7b067f', 'razor_pay', '{\"gateway\":\"razor_pay\",\"mode\":\"live\",\"status\":\"1\",\"api_key\":\"data\",\"api_secret\":\"data\"}', '{\"gateway\":\"razor_pay\",\"mode\":\"live\",\"status\":\"1\",\"api_key\":\"data\",\"api_secret\":\"data\"}', 'payment_config', 'live', 1, '2022-06-09 07:46:29', '2022-10-04 16:29:32'),
('af729332-d8ec-4822-854a-5f54e10a9061', 'sslcommerz', '{\"gateway\":\"sslcommerz\",\"mode\":\"test\",\"status\":\"1\",\"store_id\":\"data\",\"store_password\":\"data\"}', '{\"gateway\":\"sslcommerz\",\"mode\":\"test\",\"status\":\"1\",\"store_id\":\"data\",\"store_password\":\"data\"}', 'payment_config', 'test', 1, '2022-06-09 03:19:38', '2022-10-04 16:29:39'),
('b004a67d-df30-4a85-9ec0-54774cc2e616', 'min_loyalty_point_to_transfer', '0', '0', 'customer_config', 'live', 1, '2023-02-23 00:25:16', '2023-02-23 00:25:16'),
('b0bf5be1-183a-4dbe-86f6-bd3ff2b9c68a', 'push_notification', '{\"party_name\":\"push_notification\",\"server_key\":\"apikey\"}', '{\"party_name\":\"push_notification\",\"server_key\":\"apikey\"}', 'third_party', 'live', 0, '2022-07-16 04:56:01', '2022-10-04 16:24:43'),
('b9525ca3-9c9e-432c-a2bb-a9f41c8a6b68', 'time_format', '\"24h\"', '\"24h\"', 'business_information', 'live', 1, '2022-06-14 09:39:24', '2022-08-18 10:28:09'),
('ba08f7c0-a233-43f5-a801-cd727e3e7de9', 'admin_order_notification', '1', '1', 'service_setup', 'live', 1, '2022-07-20 06:04:23', '2022-08-13 07:35:03'),
('bbfd087c-eaa5-4868-8a69-3be87720ae86', 'top_description', '\"LARGEST BOOKING SERVICE PLATEFORM\"', '\"LARGEST BOOKING SERVICE PLATEFORM\"', 'landing_text_setup', 'live', 0, '2022-10-03 15:36:11', '2022-10-03 15:36:11'),
('bc4ed6e1-2c7e-4c95-8c7f-fe547a317c1e', 'about_us_image', '\"2022-10-04-633bfb711ac8f.png\"', '\"2022-10-04-633bfb711ac8f.png\"', 'landing_images', 'live', 0, '2022-10-03 17:37:45', '2022-10-04 16:22:57');
INSERT INTO `business_settings` (`id`, `key_name`, `live_values`, `test_values`, `settings_type`, `mode`, `is_active`, `created_at`, `updated_at`) VALUES
('c58ecce7-598f-4568-aa15-d875dfa12232', 'terms_and_conditions', '\"<p>&quot;<\\/p>\\r\\n\\r\\n<p>\\\\n\\\\n\\\\n<\\/p>\\r\\n\\r\\n<p>1. INTRODUCTION<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>test12345655<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>terms and condition<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>Welcome to Daraz.com.bd also hereby known as &ldquo;we\\\\&quot;, \\\\&quot;us\\\\&quot; or \\\\&quot;Daraz\\\\&quot;. We are an online marketplace and these are the terms and conditions governing your access and use of Daraz along with its related sub-domains, sites, mobile app, services and tools (the \\\\&quot;Site\\\\&quot;). By using the Site, you hereby accept these terms and conditions (including the linked information herein) and represent that you agree to comply with these terms and conditions (the \\\\&quot;User Agreement\\\\&quot;). This User Agreement is deemed effective upon your use of the Site which signifies your acceptance of these terms. If you do not agree to be bound by this User Agreement please do not access, register with or use this Site. This Site is owned and operated by&nbsp;<strong>Daraz Bangladesh Limited, a company incorporated under the Companies Act, 1994, (Registration Number: 117773\\/14).<\\/strong><br \\/>\\r\\n<br \\/>\\r\\nThe Site reserves the right to change, modify, add, or remove portions of these Terms and Conditions at any time without any prior notification. Changes will be effective when posted on the Site with no other notice provided. Please check these Terms and Conditions regularly for updates. Your continued use of the Site following the posting of changes to Terms and Conditions of use constitutes your acceptance of those changes.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>2. CONDITIONS OF USE<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>A. YOUR ACCOUNT<\\/p>\\r\\n\\r\\n<p>\\\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>To access certain services offered by the platform, we may require that you create an account with us or provide personal information to complete the creation of an account. We may at any time in our sole and absolute discretion, invalidate the username and\\/or password without giving any reason or prior notice and shall not be liable or responsible for any losses suffered by, caused by, arising out of, in connection with or by reason of such request or invalidation.<br \\/>\\r\\n<br \\/>\\r\\nYou are responsible for maintaining the confidentiality of your user identification, password, account details and related private information. You agree to accept this responsibility and ensure your account and its related details are maintained securely at all times and all necessary steps are taken to prevent misuse of your account. You should inform us immediately if you have any reason to believe that your password has become known to anyone else, or if the password is being, or is likely to be, used in an unauthorized manner. You agree and acknowledge that any use of the Site and related services offered and\\/or any access to private information, data or communications using your account and password shall be deemed to be either performed by you or authorized by you as the case may be. You agree to be bound by any access of the Site and\\/or use of any services offered by the Site (whether such access or use are authorized by you or not). You agree that we shall be entitled (but not obliged) to act upon, rely on or hold you solely responsible and liable in respect thereof as if the same were carried out or transmitted by you. You further agree and acknowledge that you shall be bound by and agree to fully indemnify us against any and all losses arising from the use of or access to the Site through your account.<br \\/>\\r\\n<br \\/>\\r\\nPlease ensure that the details you provide us with are correct and complete at all times. You are obligated to update details about your account in real time by accessing your account online. For pieces of information you are not able to update by accessing Your Account on the Site, you must inform us via our customer service communication channels to assist you with these changes. We reserve the right to refuse access to the Site, terminate accounts, remove or edit content at any time without prior notice to you. We may at any time in our sole and absolute discretion, request that you update your Personal Data or forthwith invalidate the account or related details without giving any reason or prior notice and shall not be liable or responsible for any losses suffered by or caused by you or arising out of or in connection with or by reason of such request or invalidation. You hereby agree to change your password from time to time and to keep your account secure and also shall be responsible for the confidentiality of your account and liable for any disclosure or use (whether such use is authorised or not) of the username and\\/or password.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>B. PRIVACY<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>Please review our Privacy Agreement, which also governs your visit to the Site. The personal information \\/ data provided to us by you or your use of the Site will be treated as strictly confidential, in accordance with the Privacy Agreement and applicable laws and regulations. If you object to your information being transferred or used in the manner specified in the Privacy Agreement, please do not use the Site.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>C. PLATFORM FOR COMMUNICATION<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>You agree, understand and acknowledge that the Site is an online platform that enables you to purchase products listed at the price indicated therein at any time from any location using a payment method of your choice. You further agree and acknowledge that we are only a facilitator and cannot be a party to or control in any manner any transactions on the Site or on a payment gateway as made available to you by an independent service provider. Accordingly, the contract of sale of products on the Site shall be a strictly bipartite contract between you and the sellers on our Site while the payment processing occurs between you, the service provider and in case of prepayments with electronic cards your issuer bank. Accordingly, the contract of payment on the Site shall be strictly a bipartite contract between you and the service provider as listed on our Site.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>D. CONTINUED AVAILABILITY OF THE SITE<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>We will do our utmost to ensure that access to the Site is consistently available and is uninterrupted and error-free. However, due to the nature of the Internet and the nature of the Site, this cannot be guaranteed. Additionally, your access to the Site may also be occasionally suspended or restricted to allow for repairs, maintenance, or the introduction of new facilities or services at any time without prior notice. We will attempt to limit the frequency and duration of any such suspension or restriction.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>E. LICENSE TO ACCESS THE SITE<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>We require that by accessing the Site, you confirm that you can form legally binding contracts and therefore you confirm that you are at least 18 years of age or are accessing the Site under the supervision of a parent or legal guardian. We grant you a non-transferable, revocable and non-exclusive license to use the Site, in accordance with the Terms and Conditions described herein, for the purposes of shopping for personal items and services as listed to be sold on the Site. Commercial use or use on behalf of any third party is prohibited, except as explicitly permitted by us in advance. If you are registering as a business entity, you represent that you have the authority to bind that entity to this User Agreement and that you and the business entity will comply with all applicable laws relating to online trading. No person or business entity may register as a member of the Site more than once. Any breach of these Terms and Conditions shall result in the immediate revocation of the license granted in this paragraph without notice to you.<br \\/>\\r\\n<br \\/>\\r\\nContent provided on this Site is solely for informational purposes. Product representations including price, available stock, features, add-ons and any other details as expressed on this Site are the responsibility of the vendors displaying them and is not guaranteed as completely accurate by us. Submissions or opinions expressed on this Site are those of the individual(s) posting such content and may not reflect our opinions.<br \\/>\\r\\n<br \\/>\\r\\nWe grant you a limited license to access and make personal use of this Site, but not to download (excluding page caches) or modify the Site or any portion of it in any manner. This license does not include any resale or commercial use of this Site or its contents; any collection and use of any product listings, descriptions, or prices; any derivative use of this Site or its contents; any downloading or copying of account information for the benefit of another seller; or any use of data mining, robots, or similar data gathering and extraction tools.<br \\/>\\r\\n<br \\/>\\r\\nThis Site or any portion of it (including but not limited to any copyrighted material, trademarks, or other proprietary information) may not be reproduced, duplicated, copied, sold, resold, visited, distributed or otherwise exploited for any commercial purpose without express written consent by us as may be applicable.<br \\/>\\r\\n<br \\/>\\r\\nYou may not frame or use framing techniques to enclose any trademark, logo, or other proprietary information (including images, text, page layout, or form) without our express written consent. You may not use any meta tags or any other text utilizing our name or trademark without our express written consent, as applicable. Any unauthorized use terminates the permission or license granted by us to you for access to the Site with no prior notice. You may not use our logo or other proprietary graphic or trademark as part of an external link for commercial or other purposes without our express written consent, as may be applicable.<br \\/>\\r\\n<br \\/>\\r\\nYou agree and undertake not to perform restricted activities listed within this section; undertaking these activities will result in an immediate cancellation of your account, services, reviews, orders or any existing incomplete transaction with us and in severe cases may also result in legal action:<br \\/>\\r\\n&nbsp;<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<ul>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Refusal to comply with the Terms and Conditions described herein or any other guidelines and policies related to the use of the Site as available on the Site at all times.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Impersonate any person or entity or to falsely state or otherwise misrepresent your affiliation with any person or entity.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Use the Site for illegal purposes.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Attempt to gain unauthorized access to or otherwise interfere or disrupt other computer systems or networks connected to the Platform or Services.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Interfere with another&rsquo;s utilization and enjoyment of the Site;<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Post, promote or transmit through the Site any prohibited materials as deemed illegal by The People&#39;s Republic of Bangladesh.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Use or upload, in any way, any software or material that contains, or which you have reason to suspect that contains, viruses, damaging components, malicious code or harmful components which may impair or corrupt the Site&rsquo;s data or damage or interfere with the operation of another Customer&rsquo;s computer or mobile device or the Site and use the Site other than in conformance with the acceptable use policies of any connected computer networks, any applicable Internet standards and any other applicable laws.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n<\\/ul>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>F. YOUR CONDUCT<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>You must not use the website in any way that causes, or is likely to cause, the Site or access to it to be interrupted, damaged or impaired in any way. You must not engage in activities that could harm or potentially harm the Site, its employees, officers, representatives, stakeholders or any other party directly or indirectly associated with the Site or access to it to be interrupted, damaged or impaired in any way. You understand that you, and not us, are responsible for all electronic communications and content sent from your computer to us and you must use the Site for lawful purposes only. You are strictly prohibited from using the Site<br \\/>\\r\\n&nbsp;<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<ul>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>for fraudulent purposes, or in connection with a criminal offense or other unlawful activity<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>to send, use or reuse any material that does not belong to you; or is illegal, offensive (including but not limited to material that is sexually explicit content or which promotes racism, bigotry, hatred or physical harm), deceptive, misleading, abusive, indecent, harassing, blasphemous, defamatory, libellous, obscene, pornographic, paedophilic or menacing; ethnically objectionable, disparaging or in breach of copyright, trademark, confidentiality, privacy or any other proprietary information or right; or is otherwise injurious to third parties; or relates to or promotes money laundering or gambling; or is harmful to minors in any way; or impersonates another person; or threatens the unity, integrity, security or sovereignty of Bangladesh or friendly relations with foreign States; or objectionable or otherwise unlawful in any manner whatsoever; or which consists of or contains software viruses, political campaigning, commercial solicitation, chain letters, mass mailings or any \\\\&quot;spam&rdquo;<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Use the Site for illegal purposes.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>to cause annoyance, inconvenience or needless anxiety<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>for any other purposes that is other than what is intended by us<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n<\\/ul>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>&nbsp;<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>G. YOUR SUBMISSION<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>Anything that you submit to the Site and\\/or provide to us, including but not limited to, questions, reviews, comments, and suggestions (collectively, \\\\&quot;Submissions\\\\&quot;) will become our sole and exclusive property and shall not be returned to you. In addition to the rights applicable to any Submission, when you post comments or reviews to the Site, you also grant us the right to use the name that you submit, in connection with such review, comment, or other content. You shall not use a false e-mail address, pretend to be someone other than yourself or otherwise mislead us or third parties as to the origin of any Submissions. We may, but shall not be obligated to, remove or edit any Submissions without any notice or legal course applicable to us in this regard.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n\\\\n<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>\\\\n\\\\n\\\\n<\\/p>\\r\\n\\r\\n<p>H. CLAIMS AGAINST OBJECTIONABLE CONTENT<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>We list thousands of products for sale offered by numerous sellers on the Site and host multiple comments on listings, it is not possible for us to be aware of the contents of each product listed for sale, or each comment or review that is displayed. Accordingly, we operate on a \\\\&quot;claim, review and takedown\\\\&quot; basis. If you believe that any content on the Site is illegal, offensive (including but not limited to material that is sexually explicit content or which promotes racism, bigotry, hatred or physical harm), deceptive, misleading, abusive, indecent, harassing, blasphemous, defamatory, libellous, obscene, pornographic, paedophilic or menacing; ethnically objectionable, disparaging; or is otherwise injurious to third parties; or relates to or promotes money laundering or gambling; or is harmful to minors in any way; or impersonates another person; or threatens the unity, integrity, security or sovereignty of Bangladesh or friendly relations with foreign States; or objectionable or otherwise unlawful in any manner whatsoever; or which consists of or contains software viruses, (\\\\&quot; objectionable content \\\\&quot;), please notify us immediately by following by writing to us on legal@daraz.com.bd. We will make all practical endeavours to investigate and remove valid objectionable content complained about within a reasonable amount of time.<br \\/>\\r\\n<br \\/>\\r\\nPlease ensure to provide your name, address, contact information and as many relevant details of the claim including name of objectionable content party, instances of objection, proof of objection amongst other. Please note that providing incomplete details will render your claim invalid and unusable for legal purposes.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>I. CLAIMS AGAINST INFRINGING CONTENT<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>We respect the intellectual property of others. If you believe that your intellectual property rights have been used in a way that gives rise to concerns of infringement, please write to us at legal@daraz.com.bd and we will make all reasonable efforts to address your concern within a reasonable amount of time. Please ensure to provide your name, address, contact information and as many relevant details of the claim including name of infringing party, instances of infringement, proof of infringement amongst other. Please note that providing incomplete details will render your claim invalid and unusable for legal purposes. In addition, providing false or misleading information may be considered a legal offense and may be followed by legal proceedings.<br \\/>\\r\\n<br \\/>\\r\\nWe also respect a manufacturer&#39;s right to enter into exclusive distribution or resale agreements for its products. However, violations of such agreements do not constitute intellectual property rights infringement. As the enforcement of these agreements is a matter between the manufacturer, distributor and\\/or respective reseller, it would not be appropriate for us to assist in the enforcement of such activities. While we cannot provide legal advice, nor share private information as protected by the law, we recommend that any questions or concerns regarding your rights may be routed to a legal specialist.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>J. TRADEMARKS AND COPYRIGHTS<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>Daraz.com.bd, Daraz logo, D for Daraz logo, Daraz, Daraz Fashion, Daraz Basics and other marks indicated on our Site are trademarks or registered trademarks in the relevant jurisdiction(s). Our graphics, logos, page headers, button icons, scripts and service names are the trademarks or trade dress and may not be used in connection with any product or service that does not belong to us or in any manner that is likely to cause confusion among customers, or in any manner that disparages or discredits us. All other trademarks that appear on this Site are the property of their respective owners, who may or may not be affiliated with, connected to, or sponsored by us.<br \\/>\\r\\n<br \\/>\\r\\nAll intellectual property rights, whether registered or unregistered, in the Site, information content on the Site and all the website design, including, but not limited to text, graphics, software, photos, video, music, sound, and their selection and arrangement, and all software compilations, underlying source code and software shall remain our property. The entire contents of the Site also are protected by copyright as a collective work under Bangladeshi copyright laws and international conventions. All rights are reserved.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>K. DISCLAIMER<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>You acknowledge and undertake that you are accessing the services on the Site and transacting at your own risk and are using your best and prudent judgment before entering into any transactions through the Site. We shall neither be liable nor responsible for any actions or inactions of sellers nor any breach of conditions, representations or warranties by the sellers or manufacturers of the products and hereby expressly disclaim and any all responsibility and liability in that regard. We shall not mediate or resolve any dispute or disagreement between you and the sellers or manufacturers of the products.<br \\/>\\r\\n<br \\/>\\r\\nWe further expressly disclaim any warranties or representations (express or implied) in respect of quality, suitability, accuracy, reliability, completeness, timeliness, performance, safety, merchantability, fitness for a particular purpose, or legality of the products listed or displayed or transacted or the content (including product or pricing information and\\/or specifications) on the Site. While we have taken precautions to avoid inaccuracies in content, this Site, all content, information (including the price of products), software, products, services and related graphics are provided as is basis, without warranty of any kind. We do not implicitly or explicitly support or endorse the sale or purchase of any products on the Site. At no time shall any right, title or interest in the products sold through or displayed on the Site vest with us nor shall Daraz have any obligations or liabilities in respect of any transactions on the Site.<br \\/>\\r\\n<br \\/>\\r\\nWe shall neither be liable or responsible for any actions or inactions of any other service provider as listed on our Site which includes but is not limited to payment providers, instalment offerings, warranty services amongst others.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>L. INDEMNITY<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>You shall indemnify and hold harmless Daraz as owned by Daraz Singapore Private Limited, its subsidiaries, affiliates and their respective officers, directors, agents and employees, from any claim or demand, or actions including reasonable attorney&#39;s fees, made by any third party or penalty imposed due to or arising out of your breach of these Terms and Conditions or any document incorporated by reference, or your violation of any law, rules, regulations or the rights of a third party.<br \\/>\\r\\n<br \\/>\\r\\nYou hereby expressly release Daraz as owned by Daraz Singapore Private Limited and\\/or its affiliates and\\/or any of its officers and representatives from any cost, damage, liability or other consequence of any of the actions\\/inactions of the sellers or other service providers and specifically waiver any claims or demands that you may have in this behalf under any statute, contract or otherwise.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>M. THIRD PARTY BUSINESSES<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>Parties other than Daraz and its affiliates may operate stores, provide services, or sell product lines on the Site. For example, businesses and individuals offer products via Marketplace. In addition, we provide links to the websites of affiliated companies and certain other businesses. We are not responsible for examining or evaluating, and we do not warrant or endorse the offerings of any of these businesses or individuals, or the content of their websites. We do not assume any responsibility or liability for the actions, products, and content of any of these and any other third-parties. You can tell when a third-party is involved in your transactions by reviewing your transaction carefully, and we may share customer information related to those transactions with that third-party. You should carefully review their privacy statements and related terms and conditions.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>N. COMMUNICATING WITH US<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>When you visit the Site, or send e-mails to us, you are communicating with us electronically. You will be required to provide a valid phone number while placing an order with us. We may communicate with you by e-mail, SMS, phone call or by posting notices on the Site or by any other mode of communication we choose to employ. For contractual purposes, you consent to receive communications (including transactional, promotional and\\/or commercial messages), from us with respect to your use of the website (and\\/or placement of your order) and agree to treat all modes of communication with the same importance.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>O. LOSSES<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>We will not be responsible for any business or personal losses (including but not limited to loss of profits, revenue, contracts, anticipated savings, data, goodwill, or wasted expenditure) or any other indirect or consequential loss that is not reasonably foreseeable to both you and us when you commenced using the Site.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>P. AMENDMENTS TO CONDITIONS OR ALTERATIONS OF SERVICE AND RELATED PROMISE<\\/p>\\r\\n\\r\\n<p>\\\\n\\\\n\\\\n<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>\\\\n\\\\n\\\\n<\\/p>\\r\\n\\r\\n<p>We reserve the right to make changes to the Site, its policies, these terms and conditions and any other publicly displayed condition or service promise at any time. You will be subject to the policies and terms and conditions in force at the time you used the Site unless any change to those policies or these conditions is required to be made by law or government authority (in which case it will apply to orders previously placed by you). If any of these conditions is deemed invalid, void, or for any reason unenforceable, that condition will be deemed severable and will not affect the validity and enforceability of any remaining condition.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>Q. EVENTS BEYOND OUR CONTROL<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>We will not be held responsible for any delay or failure to comply with our obligations under these conditions if the delay or failure arises from any cause which is beyond our reasonable control. This condition does not affect your statutory rights.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>R. WAIVER<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>You acknowledge and recognize that we are a private commercial enterprise and reserve the right to conduct business to achieve our objectives in a manner we deem fit. You also acknowledge that if you breach the conditions stated on our Site and we take no action, we are still entitled to use our rights and remedies in any other situation where you breach these conditions.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>S. TERMINATION<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>In addition to any other legal or equitable remedies, we may, without prior notice to you, immediately terminate the Terms and Conditions or revoke any or all of your rights granted under the Terms and Conditions. Upon any termination of this Agreement, you shall immediately cease all access to and use of the Site and we shall, in addition to any other legal or equitable remedies, immediately revoke all password(s) and account identification issued to you and deny your access to and use of this Site in whole or in part. Any termination of this agreement shall not affect the respective rights and obligations (including without limitation, payment obligations) of the parties arising before the date of termination. You furthermore agree that the Site shall not be liable to you or to any other person as a result of any such suspension or termination. If you are dissatisfied with the Site or with any terms, conditions, rules, policies, guidelines, or practices in operating the Site, your sole and exclusive remedy is to discontinue using the Site.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>T. GOVERNING LAW AND JURISDICTION<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>These terms and conditions are governed by and construed in accordance with the laws of The People&#39;s Republic of Bangladesh. You agree that the courts, tribunals and\\/or quasi-judicial bodies located in Dhaka, Bangladesh shall have the exclusive jurisdiction on any dispute arising inside Bangladesh under this Agreement.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>U. CONTACT US<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>You may reach us&nbsp;<a href=\\\"\\\\\\\">here<\\/a><\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>V. OUR SOFTWARE<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>Our software includes any software (including any updates or upgrades to the software and any related documentation) that we make available to you from time to time for your use in connection with the Site (the \\\\&quot;Software\\\\&quot;).<br \\/>\\r\\n<br \\/>\\r\\nYou may use the software solely for purposes of enabling you to use and enjoy our services as permitted by the Terms and Conditions and any related applicable terms as available on the Site. You may not incorporate any portion of the Software into your own programs or compile any portion of it in combination with your own programs, transfer it for use with another service, or sell, rent, lease, lend, loan, distribute or sub-license the Software or otherwise assign any rights to the Software in whole or in part. You may not use the Software for any illegal purpose. We may cease providing you service and we may terminate your right to use the Software at any time. Your rights to use the Software will automatically terminate without notice from us if you fail to comply with any of the Terms and Conditions listed here or across the Site. Additional third party terms contained within the Site or distributed as such that are specifically identified in related documentation may apply and will govern the use of such software in the event of a conflict with these Terms and Conditions. All software used in any of our services is our property and\\/or our affiliates or its software suppliers and protected by the laws of Bangladesh including but not limited to any other applicable copyright laws.<br \\/>\\r\\n<br \\/>\\r\\nWhen you use the Site, you may also be using the services of one or more third parties, such as a wireless carrier or a mobile platform provider. Your use of these third party services may be subject to separate policies, terms of use, and fees of these third parties.<br \\/>\\r\\n<br \\/>\\r\\nYou may not, and you will not encourage, assist or authorize any other person to copy, modify, reverse engineer, decompile or disassemble, or otherwise tamper with our software whether in whole or in part, or create any derivative works from or of the Software.<br \\/>\\r\\n<br \\/>\\r\\nIn order to keep the Software up-to-date, we may offer automatic or manual updates at any time and without notice to you.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>3. CONDITIONS OF SALE (BETWEEN SELLERS AND CUSTOMERS)<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>Please read these conditions carefully before placing an order for any products with the Sellers (&ldquo;We&rdquo; or &ldquo;Our&rdquo; or &ldquo;Us&rdquo;, wherever applicable) on the Site. These conditions signify your agreement to be bound by these conditions.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>A. CONDITIONS RELATED TO SALE OF THE PRODUCT OR SERVICE<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>This section deals with conditions relating to the sale of products or services on the Site.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>B. THE CONTRACT<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>Your order is a legal offer to the seller to buy the product or service displayed on our Site. When you place an order to purchase a product, any confirmations or status updates received prior to the dispatch of your order serves purely to validate the order details provided and in no way implies the confirmation of the order itself. The acceptance of your order is considered confirmed when the product is dispatched to you. If your order is dispatched in more than one package, you may receive separate dispatch confirmations. Upon time of placing the order, we indicate an approximate timeline that the processing of your order will take however we cannot guarantee this timeline to be rigorously precise in every instance as we are dependent on third party service providers to preserve this commitment. We commit to you to make every reasonable effort to ensure that the indicative timeline is met. All commercial\\/contractual terms are offered by and agreed to between you and the sellers alone. The commercial\\/contractual terms include without limitation price, shipping costs, payment methods, payment terms, date, period and mode of delivery, warranties related to products and services and after sales services related to products and services. Daraz does not have any control or does not determine or advise or in any way involve itself in the offering or acceptance of such commercial\\/contractual terms between the you and the Sellers. The seller retains the right to cancel any order at its sole discretion prior to dispatch. We will ensure that there is timely intimation to you of such cancellation via an email or sms. Any prepayments made in case of such cancellation(s), shall be refunded to you within the time frames stipulated&nbsp;<a href=\\\"\\\\\\\">here<\\/a>.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n\\\\n<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>\\\\n\\\\n\\\\n<\\/p>\\r\\n\\r\\n<p>D. RETURNS<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>Please review our Returns Policy&nbsp;<a href=\\\"\\\\\\\">here<\\/a>.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>E. PRICING, AVAILABILITY AND ORDER PROCESSING<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>All prices are listed in Bangladeshi Taka (BDT) and are inclusive of VAT and are listed on the Site by the seller that is selling the product or service. Items in your Shopping Cart will always reflect the most recent price displayed on the item&#39;s product detail page. Please note that this price may differ from the price shown for the item when you first placed it in your cart. Placing an item in your cart does not reserve the price shown at that time. It is also possible that an item&#39;s price may decrease between the time you place it in your basket and the time you purchase it.<br \\/>\\r\\n<br \\/>\\r\\nWe do not offer price matching for any items sold by any seller on our Site or other websites.<br \\/>\\r\\n<br \\/>\\r\\nWe are determined to provide the most accurate pricing information on the Site to our users; however, errors may still occur, such as cases when the price of an item is not displayed correctly on the Site. As such, we reserve the right to refuse or cancel any order. In the event that an item is mispriced, we may, at our own discretion, either contact you for instructions or cancel your order and notify you of such cancellation. We shall have the right to refuse or cancel any such orders whether or not the order has been confirmed and your prepayment processed. If such a cancellation occurs on your prepaid order, our policies for refund will apply. Please note that Daraz posses 100% right on the refund amount. Usually refund amount is calculated based on the customer paid price after deducting any sort of discount and shipping fee.<br \\/>\\r\\n<br \\/>\\r\\nWe list availability information for products listed on the Site, including on each product information page. Beyond what we say on that page or otherwise on the Site, we cannot be more specific about availability. Please note that dispatch estimates are just that. They are not guaranteed dispatch times and should not be relied upon as such. As we process your order, you will be informed by e-mail or sms if any products you order turn out to be unavailable.<br \\/>\\r\\n<br \\/>\\r\\nPlease note that there are cases when an order cannot be processed for various reasons. The Site reserves the right to refuse or cancel any order for any reason at any given time. You may be asked to provide additional verifications or information, including but not limited to phone number and address, before we accept the order.<br \\/>\\r\\n<br \\/>\\r\\nIn order to avoid any fraud with credit or debit cards, we reserve the right to obtain validation of your payment details before providing you with the product and to verify the personal information you shared with us. This verification can take the shape of an identity, place of residence, or banking information check. The absence of an answer following such an inquiry will automatically cause the cancellation of the order within a reasonable timeline. We reserve the right to proceed to direct cancellation of an order for which we suspect a risk of fraudulent use of banking instruments or other reasons without prior notice or any subsequent legal liability.<br \\/>\\r\\n<br \\/>\\r\\n<strong>Refund Voucher<\\/strong><\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<ul>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Refund voucher can be redeemed on our Website, as full or part payment of products from our Website within the given timeline.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Refund voucher cannot be used from different account.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Vouchers are not replaceable if expired.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Refund Voucher code can be applied only once. The residual amount of the Refund Voucher after applying it once, if any, will not be refunded and cannot be used for next purchases even if the value of order is smaller than remaining voucher value.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n<\\/ul>\\r\\n\\r\\n<p>\\\\n<strong>Promotional Vouchers<\\/strong>\\\\n<\\/p>\\r\\n\\r\\n<ul>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Each issued promotional voucher (App voucher and New customer voucher) will be valid for use by a customer only once. Multiple usages changing the identity is illegal.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Both promotional voucher and cart rule discount may not be added at the same time.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Promotional voucher is non-refundable and cannot be exchanged for cash in part or full and is valid for a single transaction only.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Promotional voucher may not be valid during sale or in conjunction with any special promotion.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Voucher will work only if minimum purchase amount and other conditions are met.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Daraz reserves the right to vary or terminate the operation of any voucher at any time without notice.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Daraz shall not be liable to any customer or household for any financial loss arising out of the refusal, cancellation or withdrawal of any voucher or any failure or inability of a customer to use a voucher for any reason.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Vouchers are not replaceable if expired.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>No promotional offer can be made for baby nutrition products.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n<\\/ul>\\r\\n\\r\\n<p>\\\\n<strong>Reward Vouchers<\\/strong>\\\\n<\\/p>\\r\\n\\r\\n<ul>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>Customers who have already been listed in Daraz for fraudulent activities will not be eligible to avail any voucher and will not be eligible to participate in any campaign.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>A customer shall not operate more than one account in a single device.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n<\\/ul>\\r\\n\\r\\n<p>\\\\n<strong>Promotional Items<\\/strong>\\\\n<\\/p>\\r\\n\\r\\n<ul>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>One customer will be able to purchase one 11tk deal and mystery box during the promotional period.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n<\\/ul>\\r\\n\\r\\n<p>\\\\n<strong>Security and Fraud<\\/strong>\\\\n<\\/p>\\r\\n\\r\\n<ul>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>When you use a voucher, you warrant to Daraz that you are the duly authorized recipient of the voucher and that you are using it in good faith.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>If you redeem, attempt to redeem or encourage the redemption of voucher to obtain discounts to which you are not entitled you may be committing a civil or criminal offence<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li>If we reasonably believe that any voucher is being used unlawfully or illegally we may reject or cancel any voucher\\/order and you agree that you will have no claim against us in respect of any rejection or cancellation. Daraz reserves the right to take any further action it deems appropriate in such instances<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n<\\/ul>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>F. RESELLING DARAZ PRODUCTS<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>Reselling Daraz products for business purpose is strictly prohibited. If any unauthorized personnel is found committing the above act, legal action may be taken against him\\/her.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>G. TAXES<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>You shall be responsible for payment of all fees\\/costs\\/charges associated with the purchase of products from the Site and you agree to bear any and all applicable taxes as per prevailing law.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>H. REPRESENTATIONS AND WARRANTIES<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<p>We do not make any representation or warranty as to specifics (such as quality, value, saleability, etc) of the products or services listed to be sold on the Site when products or services are sold by third parties. We do not implicitly or explicitly support or endorse the sale or purchase of any products or services on the Site. We accept no liability for any errors or omissions, whether on behalf of itself or third parties.<br \\/>\\r\\n<br \\/>\\r\\nWe are not responsible for any non-performance or breach of any contract entered into between you and the sellers. We cannot and do not guarantee your actions or those of the sellers as they conclude transactions on the Site. We are not required to mediate or resolve any dispute or disagreement arising from transactions occurring on our Site.<br \\/>\\r\\n<br \\/>\\r\\nWe do not at any point of time during any transaction as entered into by you with a third party on our Site, gain title to or have any rights or claims over the products or services offered by a seller. Therefore, we do not have any obligations or liabilities in respect of such contract(s) entered into between you and the seller(s). We are not responsible for unsatisfactory or delayed performance of services or damages or delays as a result of products which are out of stock, unavailable or back ordered.<br \\/>\\r\\n<br \\/>\\r\\nPricing on any product(s) or related information as reflected on the Site may due to some technical issue, typographical error or other reason by incorrect as published and as a result you accept that in such conditions the seller or the Site may cancel your order without prior notice or any liability arising as a result. Any prepayments made for such orders will be refunded to you per our refund policy as stipulated&nbsp;<a href=\\\"\\\\\\\">here<\\/a>.<\\/p>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<p>I. OTHERS<\\/p>\\r\\n\\r\\n<p>\\\\n<\\/p>\\r\\n\\r\\n<ul>\\r\\n\\t<li>\\\\n\\r\\n\\t<ul>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li><strong>Stock availability:<\\/strong>&nbsp;The orders are subject to availability of stock.<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t<\\/ul>\\r\\n\\t\\\\n<\\/li>\\r\\n<\\/ul>\\r\\n\\r\\n<p>\\\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<ul>\\r\\n\\t<li>\\\\n\\r\\n\\t<ul>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li><strong>Delivery Timeline:<\\/strong>&nbsp;The delivery might take longer than usual timeframe\\/line to be followed by Daraz.<br \\/>\\r\\n\\t\\tDelivery might be delayed due to force majeure event which includes, but not limited to, political unrest, political event, national\\/public holidays,etc<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t<\\/ul>\\r\\n\\t\\\\n<\\/li>\\r\\n<\\/ul>\\r\\n\\r\\n<p>\\\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<ul>\\r\\n\\t<li>\\\\n\\r\\n\\t<ul>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t\\t<li><strong>Cancellation:<\\/strong>&nbsp;Daraz retains unqualified right to cancel any order at its sole discretion prior to dispatch and for any reason which may include, but not limited to, the product being mispriced, out of stock, expired, defective, malfunctioned, and containing incorrect information or description arising out of technical or typographical error or for any other reason.<\\/li>\\r\\n\\t\\t<li>\\\\n<\\/li>\\r\\n\\t<\\/ul>\\r\\n\\t\\\\n<\\/li>\\r\\n<\\/ul>\\r\\n\\r\\n<p>\\\\n<br \\/>\\r\\n\\\\n<\\/p>\\r\\n\\r\\n<ul>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n\\t<li><strong>Refund Timeline:<\\/strong>&nbsp;If any order is canceled, the payment against such order shall be refunded within 10 to 15 working days, but it may take longer time in exceptional cases. Provided that received cash back amount, if any, will be adjusted with the refund amount.<\\/li>\\r\\n\\t<li>\\\\n<\\/li>\\r\\n<\\/ul>\\r\\n\\r\\n<p>\\\\n<a href=\\\"\\\\\\\">Back to Top<\\/a><br \\/>\\r\\n<br \\/>\\r\\nYou confirm that the product(s) or service(s) ordered by you are purchased for your internal \\/ personal consumption and not for commercial re-sale. You authorize us to declare and provide declaration to any governmental authority on your behalf stating the aforesaid purpose for your orders on the Site. The Seller or the Site may cancel an order wherein the quantities exceed the typical individual consumption. This applies both to the number of products ordered within a single order and the placing of several orders for the same product where the individual orders comprise a quantity that exceeds the typical individual consumption. What comprises a typical individual&#39;s consumption quantity limit shall be based on various factors and at the sole discretion of the Seller or ours and may vary from individual to individual.<br \\/>\\r\\n<br \\/>\\r\\nYou may cancel your order at no cost any time before the item is dispatched to you.<br \\/>\\r\\n<br \\/>\\r\\nPlease note that we sell products only in quantities which correspond to the typical needs of an average household. This applies both to the number of products ordered within a single order and the placing of several orders for the same product where the individual orders comprise a quantity typical for a normal household.Please review our Refund Policy&nbsp;<a href=\\\"\\\\\\\">here<\\/a>.<br \\/>\\r\\n<br \\/>\\r\\n<a href=\\\"\\\\\\\">Back to Top<\\/a>\\\\n\\\\n<\\/p>\\r\\n\\r\\n<p>&quot;<\\/p>\"', NULL, 'pages_setup', 'live', 0, '2022-08-06 04:02:24', '2022-10-04 11:11:05');
INSERT INTO `business_settings` (`id`, `key_name`, `live_values`, `test_values`, `settings_type`, `mode`, `is_active`, `created_at`, `updated_at`) VALUES
('c68a9f47-7504-4fc9-b4f6-6a5aa274e4b8', 'business_name', '\"company\"', '\"company\"', 'business_information', 'live', 1, '2022-06-14 09:39:24', '2022-10-04 16:19:55'),
('c8dd2bcf-44e5-41e4-a121-fe4cc6f44e33', 'discount_cost_bearer', '{\"bearer\":\"provider\",\"admin_percentage\":0,\"provider_percentage\":100,\"type\":\"discount\"}', '{\"bearer\":\"provider\",\"admin_percentage\":0,\"provider_percentage\":100,\"type\":\"coupon\"}', 'promotional_setup', 'live', 1, '2023-01-22 17:33:48', '2023-01-22 17:33:48'),
('cd206dd3-6d91-4608-8bc5-9be80cbf2e42', 'top_sub_title', '\"Get all services from one App.\"', '\"Get all services from one App.\"', 'landing_text_setup', 'live', 0, '2022-10-03 15:36:11', '2022-10-03 15:36:11'),
('cfb57339-dc26-48f0-9815-896aebee243f', 'features', '[{\"id\":\"c02f1efe-1cc8-41fc-952f-4f111613be19\",\"title\":\"GET YOUR SERVICE 24\\/7\",\"sub_title\":\"Visit our app and select your location to see available services near you\",\"image_1\":\"2022-10-03-633ab8f119f43.png\",\"image_2\":\"2022-10-03-633ab8f11a2b9.png\"}]', '[{\"id\":\"c02f1efe-1cc8-41fc-952f-4f111613be19\",\"title\":\"GET YOUR SERVICE 24\\/7\",\"sub_title\":\"Visit our app and select your location to see available services near you\",\"image_1\":\"2022-10-03-633ab8f119f43.png\",\"image_2\":\"2022-10-03-633ab8f11a2b9.png\"}]', 'landing_features', 'live', 0, '2022-10-03 17:26:57', '2022-10-03 17:26:57'),
('d27c7746-520f-470d-a3e0-6ac0b427ae61', 'registration_title', '\"REGISTER AS PROVIDER\"', '\"REGISTER AS PROVIDER\"', 'landing_text_setup', 'live', 0, '2022-10-03 15:36:11', '2022-10-03 15:36:11'),
('d2b531d9-4cf1-4f7c-b96f-395856f5003d', 'google_map', '{\"party_name\":\"google_map\",\"map_api_key_client\":\"apikey\",\"map_api_key_server\":\"apikey\"}', '{\"party_name\":\"google_map\",\"map_api_key_client\":\"apikey\",\"map_api_key_server\":\"apikey\"}', 'third_party', 'live', 0, '2022-09-14 19:49:51', '2022-10-04 16:24:39'),
('d5ff36a8-634b-42a1-ab49-08375eeb21ac', 'footer_text', '\"All rights reserved By @ company\"', '\"All rights reserved By @ company\"', 'business_information', 'live', 1, '2022-09-05 10:06:02', '2022-10-04 16:21:24'),
('d8a4c244-0e6c-4511-a156-93db22a66b7e', 'phone_number_visibility_for_chatting', '0', '0', 'business_information', 'live', 1, '2023-02-23 00:25:16', '2023-02-23 00:25:16'),
('db386429-6982-4f46-82f9-08ec70f13f57', 'maximum_withdraw_amount', '0', '0', 'business_information', 'live', 1, '2023-01-22 17:33:48', '2023-01-22 17:33:48'),
('dbd7d22a-299e-49be-869e-efdcaf8ee7e4', 'web_url', '\"\\/\"', '\"\\/\"', 'landing_button_and_links', 'live', 1, '2022-10-03 16:00:01', '2022-10-04 16:22:24'),
('dbf71089-a769-4025-b971-307c08a6b455', 'service_man_can_cancel_booking', '\"0\"', '\"0\"', 'service_setup', 'live', 0, '2022-07-20 06:04:21', '2022-10-04 16:00:21'),
('e28b7af4-2284-40bf-b28b-84baad4dc1a7', 'top_image_2', '\"2022-10-04-633bfb6314d59.png\"', '\"2022-10-04-633bfb6314d59.png\"', 'landing_images', 'live', 0, '2022-10-03 16:06:00', '2022-10-04 16:22:43'),
('e80a7c3d-9371-4959-8463-c67973a42e56', 'business_email', '\"email@tech.com\"', '\"email@tech.com\"', 'business_information', 'live', 1, '2022-06-14 09:39:24', '2022-10-04 16:21:24'),
('e82e188c-d7de-478b-a83b-26c086b0576d', '2factor', '{\"gateway\":\"2factor\",\"mode\":\"live\",\"status\":\"0\",\"api_key\":\"data\"}', '{\"gateway\":\"2factor\",\"mode\":\"live\",\"status\":\"0\",\"api_key\":\"data\"}', 'sms_config', 'live', 0, '2022-06-08 08:56:14', '2022-10-04 16:26:44'),
('ea0c3ccd-6db7-4b34-8f21-d0eb637cc47c', 'minimum_withdraw_amount', '0', '0', 'business_information', 'live', 1, '2023-01-22 17:33:48', '2023-01-22 17:33:48'),
('ea71998b-2399-44cc-8949-1786e753eb9c', 'country_code', '\"AS\"', '\"AS\"', 'business_information', 'live', 1, '2022-06-14 09:39:24', '2022-10-04 16:21:24'),
('eb2430a8-8d49-4bbe-b19d-1de8bd80be64', 'cash_after_service', '1', '1', 'service_setup', 'live', 1, '2023-05-29 16:22:38', '2023-05-29 16:22:38'),
('eb59a509-e7c2-499b-9c44-57554cbfe015', 'language_code', '[\"Bengali\",\"English\",\"Arabic\",\"Abkhaz\",\"Afar\",\"Akan\",\"Albanian\",\"Amharic\"]', '[\"Bengali\",\"English\",\"Arabic\",\"Abkhaz\",\"Afar\",\"Akan\",\"Albanian\",\"Amharic\"]', 'business_information', 'live', 1, '2022-06-14 09:39:24', '2022-07-23 07:26:01'),
('eeca1881-9a28-4be9-9c27-95f4e84e6aca', 'loyalty_point_value_per_currency_unit', '0', '0', 'customer_config', 'live', 1, '2023-02-23 00:25:16', '2023-02-23 00:25:16'),
('f99e20b3-dfb1-431f-891b-7d78c413e964', 'booking_refund', '{\"booking_refund_status\":1,\"booking_refund_message\":\"Booking Refund Successfully\"}', '{\"booking_refund_status\":1,\"booking_refund_message\":\"Booking Refund Successfully\"}', 'notification_messages', 'live', 1, '2022-06-06 12:41:28', '2022-09-05 15:17:05'),
('fc9ca7b4-4f21-4d45-a0cb-04693ebee4dc', 'provider_section_image', '\"2022-10-04-633bfb7cc79de.png\"', '\"2022-10-04-633bfb7cc79de.png\"', 'landing_images', 'live', 0, '2022-10-03 17:17:01', '2022-10-04 16:23:08'),
('fe386570-fee3-46bd-a9eb-1b34be33b7d6', 'loyalty_point_percentage_per_booking', '0', '0', 'customer_config', 'live', 1, '2023-02-23 00:25:16', '2023-02-23 00:25:16');

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `campaign_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'def.png',
  `thumbnail` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'def.png',
  `discount_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_category_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `variant_key` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_cost` decimal(24,2) NOT NULL DEFAULT '0.00',
  `quantity` int(11) NOT NULL DEFAULT '1',
  `discount_amount` decimal(24,2) NOT NULL DEFAULT '0.00',
  `coupon_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_discount` decimal(24,2) NOT NULL DEFAULT '0.00',
  `campaign_discount` decimal(24,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(24,2) NOT NULL DEFAULT '0.00',
  `total_cost` decimal(24,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `category_zone`
--

CREATE TABLE `category_zone` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zone_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `channel_conversations`
--

CREATE TABLE `channel_conversations` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `channel_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `channel_lists`
--

CREATE TABLE `channel_lists` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '(DC2Type:guid)',
  `reference_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `channel_users`
--

CREATE TABLE `channel_users` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `channel_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `conversation_files`
--

CREATE TABLE `conversation_files` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `conversation_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coupon_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coupon_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_customers`
--

CREATE TABLE `coupon_customers` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coupon_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE `discounts` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_amount` decimal(24,3) NOT NULL DEFAULT '0.000',
  `discount_amount_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'percent',
  `min_purchase` decimal(24,3) NOT NULL DEFAULT '0.000',
  `max_discount_amount` decimal(24,3) NOT NULL DEFAULT '0.000',
  `limit_per_user` int(11) NOT NULL DEFAULT '0',
  `promotion_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'discount',
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `start_date` date NOT NULL DEFAULT '2022-04-04',
  `end_date` date NOT NULL DEFAULT '2022-04-04',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `discount_types`
--

CREATE TABLE `discount_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `discount_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_wise_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci,
  `answer` text COLLATE utf8mb4_unicode_ci,
  `service_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ignored_posts`
--

CREATE TABLE `ignored_posts` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loyalty_point_transactions`
--

CREATE TABLE `loyalty_point_transactions` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `credit` decimal(24,2) NOT NULL DEFAULT '0.00',
  `debit` decimal(24,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(24,2) NOT NULL DEFAULT '0.00',
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `transaction_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_100000_create_password_resets_table', 1),
(2, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(3, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(4, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(5, '2016_06_01_000004_create_oauth_clients_table', 1),
(6, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(7, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(8, '2022_02_28_094005_create_users_table', 1),
(9, '2022_02_28_094802_create_roles_table', 1),
(10, '2022_02_28_094823_create_user_roles_table', 1),
(11, '2022_03_01_092248_create_modules_table', 1),
(12, '2022_03_01_093500_create_role_modules_table', 1),
(13, '2022_03_05_085155_create_zones_table', 1),
(14, '2022_03_06_035439_create_categories_table', 1),
(15, '2022_03_06_042053_create_category_zone_table', 1),
(16, '2022_03_06_091813_create_discounts_table', 1),
(17, '2022_03_06_092202_create_services_table', 1),
(18, '2022_03_06_094413_create_variations_table', 1),
(19, '2022_03_07_063157_create_discount_types_table', 1),
(21, '2022_03_07_065305_create_provider_sub_category_table', 1),
(22, '2022_03_07_090055_create_coupons_table', 1),
(23, '2022_03_07_110744_create_campaigns_table', 1),
(24, '2022_03_08_052530_create_banners_table', 1),
(25, '2022_03_08_090735_create_transactions_table', 1),
(26, '2022_03_10_074138_create_accounts_table', 1),
(27, '2022_05_09_122054_add_variant_key_in_variation', 2),
(28, '2022_05_12_100348_create_faqs_table', 3),
(29, '2022_05_18_041330_discount_table_col_modify', 4),
(30, '2022_05_21_035041_add_coupon_type', 5),
(31, '2022_05_22_120123_add_banner_redirection_link', 6),
(33, '2022_05_24_043332_remove_and_reformat_urder_table_col', 8),
(34, '2022_03_07_064337_create_providers_table', 9),
(35, '2022_05_25_054015_create_business_settings_table', 10),
(36, '2022_06_05_061932_create_bookings_table', 11),
(37, '2022_06_05_063828_create_booking_details_table', 11),
(38, '2022_06_05_065027_create_booking_status_histories_table', 11),
(39, '2022_06_05_065040_create_booking_schedule_histories_table', 11),
(40, '2022_06_08_070555_add_status_col_toRole', 12),
(41, '2022_06_11_074614_category_sub_added_booking', 13),
(42, '2022_06_11_110610_create_user_zones_table', 13),
(43, '2022_06_12_034552_create_user_addresses_table', 13),
(44, '2022_06_13_120346_add_column_is_approved_to_provider_table', 14),
(45, '2022_06_14_104816_create_bank_details_table', 15),
(46, '2022_06_15_025832_role_table_customization', 16),
(47, '2022_06_15_043227_create_subscribed_services_table', 16),
(48, '2022_06_16_060054_tnx_add', 17),
(49, '2022_06_16_060137_acc_add', 18),
(51, '2022_06_18_052537_create_reviews_table', 19),
(52, '2022_06_18_095222_create_withdraw_requests_table', 20),
(53, '2022_06_16_094936_create_servicemen_table', 21),
(54, '2022_06_19_063119_add_serviceman_col', 22),
(55, '2022_06_20_085647_add_col_to_serviceman', 23),
(56, '2022_06_22_082434_create_carts_table', 24),
(57, '2022_06_22_121556_create_cart_service_infos_table', 24),
(58, '2022_06_22_090257_column_add_to_withdraw_request_table', 25),
(59, '2022_07_03_065118_add_zone_id_in_providers', 26),
(61, '2022_07_17_064031_add_addres_type', 27),
(62, '2022_07_17_071324_add_addres_type1', 27),
(63, '2022_07_19_040550_change-col-name', 28),
(64, '2022_07_03_095424_create_push_notifications_table', 29),
(65, '2022_07_21_050907_pass_reset_table_col_add', 30),
(66, '2022_07_21_054008_pass_reset_table_col_add1', 30),
(67, '2022_07_21_104205_add_booking_id_col', 31),
(68, '2022_07_24_051517_add_cus_col_in_review', 32),
(69, '2022_07_31_093836_create_channel_lists_table', 33),
(70, '2022_07_31_093916_create_channel_users_table', 33),
(71, '2022_07_31_094036_create_channel_conversations_table', 33),
(72, '2022_07_31_104246_create_conversation_files_table', 33),
(73, '2022_07_31_113436_add_new_col_campaign', 33),
(74, '2022_08_02_054322_update_col_type', 34),
(75, '2022_08_06_031433_add_col_in_booking_table', 35),
(76, '2022_08_06_031649_add_col_in_booking_details_table', 35),
(77, '2022_08_06_045001_remove_col_from_user', 36),
(78, '2022_08_21_031258_add_col_to_channel_list', 37),
(79, '2022_08_21_033729_add_col_to_channel_user_table', 37),
(80, '2022_08_23_060744_col_add_to_tnx_table', 38),
(81, '2022_08_28_044249_col_change_to_business_settings_table', 39),
(82, '2022_08_31_070329_col_add_to_booking_details_table', 40),
(83, '2022_09_01_135800_create_user_verifications_table', 41),
(84, '2022_09_12_062925_col_add_to_booking_table', 42),
(85, '2022_09_17_185044_add_col_to_bank_destails', 43),
(86, '2022_09_21_235326_col_add_to_withdraw_requests_table', 44),
(87, '2022_10_03_175305_add_zone_id_in_address', 44),
(88, '2022_11_21_175412_add_col_to_withdraw_requests_table', 45),
(89, '2022_11_21_230747_create_withdrawal_methods_table', 45),
(90, '2022_11_29_232809_create_booking_details_amounts_table', 45),
(91, '2022_12_05_184417_col_add_to_services_table', 45),
(92, '2022_12_06_002432_create_recent_views_table', 45),
(93, '2022_12_08_201359_create_recent_searches_table', 45),
(94, '2022_12_26_115139_add_col_to_accounts_table', 45),
(95, '2023_01_16_152849_add_col_to_booking_details_amounts_table', 45),
(96, '2023_01_24_230519_add_col_to_users_table', 46),
(97, '2023_01_25_195038_add_col_to_transactions_table', 46),
(98, '2023_01_26_174101_Create_loyalty_point_transactions_table', 46),
(99, '2023_01_27_001826_add_col_to_categories_table', 46),
(100, '2023_01_29_011739_create_tags_table', 46),
(101, '2023_01_29_162753_create_table_service_tag', 46),
(102, '2023_02_02_231012_create_service_requests_table', 46),
(103, '2023_02_05_200352_create_added_to_carts_table', 46),
(104, '2023_02_05_214409_create_visited_services_table', 46),
(105, '2023_02_05_225314_create_searched_data_table', 46),
(106, '2023_02_08_174014_add_provider_id_to_carts_table', 46),
(107, '2023_04_29_185100_create_posts_table', 47),
(108, '2023_04_29_185107_create_post_additional_instructions_table', 47),
(109, '2023_04_29_185114_create_post_bids_table', 47),
(110, '2023_04_29_185127_create_ignored_posts_table', 47),
(111, '2023_05_08_161525_add_col_to_services_table', 47),
(112, '2023_05_16_130004_add_col_to_providers_table', 47),
(113, '2023_05_16_231127_create_coupon_customers_table', 47),
(115, '2023_05_21_095745_add_col_to_users_table', 47),
(116, '2023_05_21_101102_add_col_to_user_verifications_table', 47),
(117, '2023_05_29_184809_add_col_to_posts_table', 48),
(118, '2023_05_30_102205_add_additional_charge_col_to_bookings_table', 48),
(119, '2023_05_31_103005_add_col_to_bookings_table', 49),
(120, '2023_05_17_144146_add_col_to_posts_table', 50);

-- --------------------------------------------------------

--
-- Table structure for table `modules`
--

CREATE TABLE `modules` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `module_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `module_display_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

CREATE TABLE `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `client_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

CREATE TABLE `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `client_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

CREATE TABLE `oauth_clients` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_clients`
--

INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
('95faaac6-c1d2-4d4c-beb1-04196dd2fa8e', NULL, 'Laravel Personal Access Client', '75kQskqekdipFpesfWZZv85qPo2cT8aMsyWgsIrQ', NULL, 'http://localhost', 1, 0, 0, '2022-04-04 02:13:15', '2022-04-04 02:13:15'),
('95faaac6-c56a-4873-a880-79d252d65ab1', NULL, 'Laravel Password Grant Client', 'hnFqAvObupsF3BXW4T6MxD4IhvKCZPRzyIqEFciB', 'users', 'http://localhost', 0, 1, 0, '2022-04-04 02:13:15', '2022-04-04 02:13:15');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

CREATE TABLE `oauth_personal_access_clients` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `client_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `oauth_personal_access_clients`
--

INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
(1, '95faaac6-c1d2-4d4c-beb1-04196dd2fa8e', '2022-04-04 02:13:15', '2022-04-04 02:13:15');

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

CREATE TABLE `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_description` text COLLATE utf8mb4_unicode_ci,
  `booking_schedule` datetime DEFAULT NULL,
  `is_booked` tinyint(1) NOT NULL DEFAULT '0',
  `is_checked` tinyint(1) NOT NULL DEFAULT '0',
  `customer_user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_category_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_address_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zone_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_additional_instructions`
--

CREATE TABLE `post_additional_instructions` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `post_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `post_bids`
--

CREATE TABLE `post_bids` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `offered_price` decimal(24,2) NOT NULL DEFAULT '0.00',
  `provider_note` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `providers`
--

CREATE TABLE `providers` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_phone` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_phone` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_count` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `service_man_count` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `service_capacity_per_day` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `rating_count` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `avg_rating` double(8,4) NOT NULL DEFAULT '0.0000',
  `commission_status` tinyint(1) NOT NULL DEFAULT '0',
  `commission_percentage` double(8,4) NOT NULL DEFAULT '0.0000',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `zone_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `coordinates` json DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provider_sub_category`
--

CREATE TABLE `provider_sub_category` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `provider_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_category_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `push_notifications`
--

CREATE TABLE `push_notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zone_ids` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_users` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '["customer"]',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recent_searches`
--

CREATE TABLE `recent_searches` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recent_views`
--

CREATE TABLE `recent_views` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_service_view` int(11) NOT NULL DEFAULT '0',
  `category_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_category_view` int(11) NOT NULL DEFAULT '0',
  `sub_category_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `total_sub_category_view` int(11) NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `booking_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `service_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `review_rating` int(11) NOT NULL DEFAULT '1',
  `review_comment` text COLLATE utf8mb4_unicode_ci,
  `review_images` text COLLATE utf8mb4_unicode_ci,
  `booking_date` datetime DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `customer_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `create` tinyint(1) NOT NULL DEFAULT '0',
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `update` tinyint(1) NOT NULL DEFAULT '0',
  `delete` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `modules` text COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_modules`
--

CREATE TABLE `role_modules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `module_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `searched_data`
--

CREATE TABLE `searched_data` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zone_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `attribute` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attribute_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `response_data_count` int(11) NOT NULL DEFAULT '0',
  `volume` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `servicemen`
--

CREATE TABLE `servicemen` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `short_description` text COLLATE utf8mb4_unicode_ci,
  `description` text COLLATE utf8mb4_unicode_ci,
  `cover_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `thumbnail` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `category_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_category_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax` decimal(24,3) NOT NULL DEFAULT '0.000',
  `order_count` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `rating_count` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `avg_rating` double(8,4) NOT NULL DEFAULT '0.0000',
  `min_bidding_price` decimal(24,3) NOT NULL DEFAULT '0.000',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'pending,accepted,denied',
  `admin_feedback` text COLLATE utf8mb4_unicode_ci,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_tag`
--

CREATE TABLE `service_tag` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `service_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tag_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subscribed_services`
--

CREATE TABLE `subscribed_services` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sub_category_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_subscribed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tag` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ref_trx_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `booking_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `trx_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `debit` decimal(24,2) NOT NULL DEFAULT '0.00',
  `credit` decimal(24,2) NOT NULL DEFAULT '0.00',
  `balance` decimal(24,2) NOT NULL DEFAULT '0.00',
  `from_user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `from_user_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_user_account` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_note` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identification_number` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identification_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'nid',
  `identification_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '[]',
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'male',
  `profile_image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default.png',
  `fcm_token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_phone_verified` tinyint(1) NOT NULL DEFAULT '0',
  `is_email_verified` tinyint(1) NOT NULL DEFAULT '0',
  `phone_verified_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `user_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'customer',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `wallet_balance` decimal(24,3) NOT NULL DEFAULT '0.000',
  `loyalty_point` decimal(24,3) NOT NULL DEFAULT '0.000',
  `ref_code` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referred_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `login_hit_count` tinyint(4) NOT NULL DEFAULT '0',
  `is_temp_blocked` tinyint(1) NOT NULL DEFAULT '0',
  `temp_block_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_addresses`
--

CREATE TABLE `user_addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lat` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lon` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `street` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `address_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_person_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_label` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zone_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_roles`
--

CREATE TABLE `user_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_verifications`
--

CREATE TABLE `user_verifications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `identity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `identity_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expires_at` datetime NOT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `hit_count` tinyint(4) NOT NULL DEFAULT '0',
  `is_temp_blocked` tinyint(1) NOT NULL DEFAULT '0',
  `temp_block_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_zones`
--

CREATE TABLE `user_zones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zone_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `variations`
--

CREATE TABLE `variations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `variant` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `variant_key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zone_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(24,3) NOT NULL DEFAULT '0.000',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visited_services`
--

CREATE TABLE `visited_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `count` int(11) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdrawal_methods`
--

CREATE TABLE `withdrawal_methods` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method_fields` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_requests`
--

CREATE TABLE `withdraw_requests` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_updated_by` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(24,2) NOT NULL DEFAULT '0.00',
  `request_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_paid` tinyint(1) NOT NULL DEFAULT '0',
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `admin_note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `withdrawal_method_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `withdrawal_method_fields` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `zones`
--

CREATE TABLE `zones` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `coordinates` polygon DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `added_to_carts`
--
ALTER TABLE `added_to_carts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_details`
--
ALTER TABLE `bank_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_details`
--
ALTER TABLE `booking_details`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_details_amounts`
--
ALTER TABLE `booking_details_amounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_schedule_histories`
--
ALTER TABLE `booking_schedule_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `booking_status_histories`
--
ALTER TABLE `booking_status_histories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `business_settings`
--
ALTER TABLE `business_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category_zone`
--
ALTER TABLE `category_zone`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `channel_conversations`
--
ALTER TABLE `channel_conversations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `channel_lists`
--
ALTER TABLE `channel_lists`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `channel_users`
--
ALTER TABLE `channel_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `conversation_files`
--
ALTER TABLE `conversation_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupon_customers`
--
ALTER TABLE `coupon_customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discounts`
--
ALTER TABLE `discounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `discount_types`
--
ALTER TABLE `discount_types`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ignored_posts`
--
ALTER TABLE `ignored_posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `loyalty_point_transactions`
--
ALTER TABLE `loyalty_point_transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_access_tokens`
--
ALTER TABLE `oauth_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_access_tokens_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_auth_codes`
--
ALTER TABLE `oauth_auth_codes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_auth_codes_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_clients`
--
ALTER TABLE `oauth_clients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_clients_user_id_index` (`user_id`);

--
-- Indexes for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_refresh_tokens`
--
ALTER TABLE `oauth_refresh_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post_additional_instructions`
--
ALTER TABLE `post_additional_instructions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `post_bids`
--
ALTER TABLE `post_bids`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `providers`
--
ALTER TABLE `providers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `provider_sub_category`
--
ALTER TABLE `provider_sub_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `push_notifications`
--
ALTER TABLE `push_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recent_searches`
--
ALTER TABLE `recent_searches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `recent_views`
--
ALTER TABLE `recent_views`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `role_modules`
--
ALTER TABLE `role_modules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `searched_data`
--
ALTER TABLE `searched_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `servicemen`
--
ALTER TABLE `servicemen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_tag`
--
ALTER TABLE `service_tag`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribed_services`
--
ALTER TABLE `subscribed_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_addresses`
--
ALTER TABLE `user_addresses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_roles`
--
ALTER TABLE `user_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_verifications`
--
ALTER TABLE `user_verifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_zones`
--
ALTER TABLE `user_zones`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `variations`
--
ALTER TABLE `variations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visited_services`
--
ALTER TABLE `visited_services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdrawal_methods`
--
ALTER TABLE `withdrawal_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `withdraw_requests`
--
ALTER TABLE `withdraw_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `zones`
--
ALTER TABLE `zones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `zones_name_unique` (`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `added_to_carts`
--
ALTER TABLE `added_to_carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_details`
--
ALTER TABLE `booking_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_schedule_histories`
--
ALTER TABLE `booking_schedule_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_status_histories`
--
ALTER TABLE `booking_status_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `category_zone`
--
ALTER TABLE `category_zone`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `discount_types`
--
ALTER TABLE `discount_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `oauth_personal_access_clients`
--
ALTER TABLE `oauth_personal_access_clients`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provider_sub_category`
--
ALTER TABLE `provider_sub_category`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role_modules`
--
ALTER TABLE `role_modules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_tag`
--
ALTER TABLE `service_tag`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_addresses`
--
ALTER TABLE `user_addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_roles`
--
ALTER TABLE `user_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_verifications`
--
ALTER TABLE `user_verifications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_zones`
--
ALTER TABLE `user_zones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `variations`
--
ALTER TABLE `variations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visited_services`
--
ALTER TABLE `visited_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
