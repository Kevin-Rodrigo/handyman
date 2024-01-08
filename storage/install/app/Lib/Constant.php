<?php

//passport address type
const ADMIN_PANEL_ACCESS = "AccessToAdmin";
const PROVIDER_PANEL_ACCESS = "AccessToProvider";
const CUSTOMER_PANEL_ACCESS = "AccessToCustomer";
const SERVICEMAN_APP_ACCESS = "AccessToServicemanApp";

//users
const ADMIN_USER_TYPES = ['super-admin', 'admin-employee'];
const PROVIDER_USER_TYPES = ['provider-admin', 'provider-employee', 'provider-serviceman'];
const CUSTOMER_USER_TYPES = ['customer'];
const SERVICEMAN_USER_TYPES = PROVIDER_USER_TYPES[2];

const USER_TYPES = [
    ['key' => 'super-admin', 'value' => ADMIN_USER_TYPES[0]],
    ['key' => 'admin-employee', 'value' => ADMIN_USER_TYPES[1]],
    ['key' => 'provider-admin', 'value' => PROVIDER_USER_TYPES[0]],
    ['key' => 'provider-serviceman', 'value' => PROVIDER_USER_TYPES[2]],
    ['key' => 'customer', 'value' => CUSTOMER_USER_TYPES[0]],
];

//booking statuses
const BOOKING_STATUSES = [
    ['key' => 'pending', 'value' => 'Pending'],
    ['key' => 'accepted', 'value' => 'Accepted'],
    ['key' => 'ongoing', 'value' => 'Ongoing'],
    ['key' => 'completed', 'value' => 'Completed'],
    ['key' => 'canceled', 'value' => 'Canceled'],
//    ['key' => 'refund_request', 'value' => 'Refund Request'],
//    ['key' => 'refunded', 'value' => 'Refunded'],
];

//system defaults
const DEFAULT_PAGINATION = 25;

//coupon types
const COUPON_TYPES = [
    'default' => 'Default',
    'first_booking' => 'First Booking',
    'customer_wise' => 'Customer Wise'
];

const COUPON_TYPES_REACT_FORMAT = [
    ['value' => 'default', 'name' => 'Default'],
    ['value' => 'first_order', 'name' => 'First Order']
];

//payment methods
const PAYMENT_METHODS = [
    ['key' => 'cash_after_service', 'value' => 'Cash After Service'],
    ['key' => 'ssl_commerz', 'value' => 'SSLCOMMERZ'],//
    ['key' => 'stripe', 'value' => 'Stripe'],//
//    ['key' => 'paypal', 'value' => 'PayPal'],
    ['key' => 'paytm', 'value' => 'Paytm'],//
    ['key' => 'razor_pay', 'value' => 'Razor Pay'],//
    ['key' => 'paystack', 'value' => 'Paystack'],//
    ['key' => 'senang_pay', 'value' => 'Senang Pay'],//
    ['key' => 'flutterwave', 'value' => 'Flutter Wave'],//
    ['key' => 'wallet_payment', 'value' => 'wallet payment'],
    ['key' => 'offline_payment', 'value' => 'offline payment'],
];

const DIGITAL_PAYMENT_METHODS = [
    ['key' => 'ssl_commerz', 'value' => 'SSLCOMMERZ'],
    ['key' => 'stripe', 'value' => 'Stripe'],
    ['key' => 'paytm', 'value' => 'Paytm'],
    ['key' => 'razor_pay', 'value' => 'Razor Pay'],
    ['key' => 'paystack', 'value' => 'Paystack'],
    ['key' => 'senang_pay', 'value' => 'Senang Pay'],
    ['key' => 'flutterwave', 'value' => 'Flutter Wave'],
];

//Sms Gateway
const SMS_GATEWAY = [
    ['key' => 'msg91', 'value' => 'Msg91'],
    ['key' => '2factor', 'value' => '2factor'],
    ['key' => 'twilio', 'value' => 'Twilio'],
    ['key' => 'nexmo', 'value' => 'Nexmo'],
    ['key' => 'releans', 'value' => 'Releans'],
];

//currencies
const CURRENCIES = [
    ["code" => "AED", "symbol" => "د.إ", "name" => "UAE dirham"],
    ["code" => "AFN", "symbol" => "Afs", "name" => "Afghan afghani"],
    ["code" => "ALL", "symbol" => "L", "name" => "Albanian lek"],
    ["code" => "AMD", "symbol" => "AMD", "name" => "Armenian dram"],
    ["code" => "ANG", "symbol" => "NAƒ", "name" => "Netherlands Antillean gulden"],
    ["code" => "AOA", "symbol" => "Kz", "name" => "Angolan kwanza"],
    ["code" => "ARS", "symbol" => "$", "name" => "Argentine peso"],
    ["code" => "AUD", "symbol" => "$", "name" => "Australian dollar"],
    ["code" => "AWG", "symbol" => "ƒ", "name" => "Aruban florin"],
    ["code" => "AZN", "symbol" => "AZN", "name" => "Azerbaijani manat"],
    ["code" => "BAM", "symbol" => "KM", "name" => "Bosnia and Herzegovina konvertibilna marka"],
    ["code" => "BBD", "symbol" => "Bds$", "name" => "Barbadian dollar"],
    ["code" => "BDT", "symbol" => "৳", "name" => "Bangladeshi taka"],
    ["code" => "BGN", "symbol" => "BGN", "name" => "Bulgarian lev"],
    ["code" => "BHD", "symbol" => ".د.ب", "name" => "Bahraini dinar"],
    ["code" => "BIF", "symbol" => "FBu", "name" => "Burundi franc"],
    ["code" => "BMD", "symbol" => "BD$", "name" => "Bermudian dollar"],
    ["code" => "BND", "symbol" => "B$", "name" => "Brunei dollar"],
    ["code" => "BOB", "symbol" => "Bs.", "name" => "Bolivian boliviano"],
    ["code" => "BRL", "symbol" => "R$", "name" => "Brazilian real"],
    ["code" => "BSD", "symbol" => "B$", "name" => "Bahamian dollar"],
    ["code" => "BTN", "symbol" => "Nu.", "name" => "Bhutanese ngultrum"],
    ["code" => "BWP", "symbol" => "P", "name" => "Botswana pula"],
    ["code" => "BYR", "symbol" => "Br", "name" => "Belarusian ruble"],
    ["code" => "BZD", "symbol" => "BZ$", "name" => "Belize dollar"],
    ["code" => "CAD", "symbol" => "$", "name" => "Canadian dollar"],
    ["code" => "CDF", "symbol" => "F", "name" => "Congolese franc"],
    ["code" => "CHF", "symbol" => "Fr.", "name" => "Swiss franc"],
    ["code" => "CLP", "symbol" => "$", "name" => "Chilean peso"],
    ["code" => "CNY", "symbol" => "¥", "name" => "Chinese/Yuan renminbi"],
    ["code" => "COP", "symbol" => "Col$", "name" => "Colombian peso"],
    ["code" => "CRC", "symbol" => "₡", "name" => "Costa Rican colon"],
    ["code" => "CUC", "symbol" => "$", "name" => "Cuban peso"],
    ["code" => "CVE", "symbol" => "Esc", "name" => "Cape Verdean escudo"],
    ["code" => "CZK", "symbol" => "Kč", "name" => "Czech koruna"],
    ["code" => "DJF", "symbol" => "Fdj", "name" => "Djiboutian franc"],
    ["code" => "DKK", "symbol" => "Kr", "name" => "Danish krone"],
    ["code" => "DOP", "symbol" => "RD$", "name" => "Dominican peso"],
    ["code" => "DZD", "symbol" => "دج", "name" => "Algerian dinar"],
    ["code" => "EEK", "symbol" => "KR", "name" => "Estonian kroon"],
    ["code" => "EGP", "symbol" => "e£", "name" => "Egyptian pound"],
    ["code" => "ERN", "symbol" => "Nfa", "name" => "Eritrean nakfa"],
    ["code" => "ETB", "symbol" => "Br", "name" => "Ethiopian birr"],
    ["code" => "EUR", "symbol" => "€", "name" => "European Euro"],
    ["code" => "FJD", "symbol" => "FJ$", "name" => "Fijian dollar"],
    ["code" => "FKP", "symbol" => "£", "name" => "Falkland Islands pound"],
    ["code" => "GBP", "symbol" => "£", "name" => "British pound"],
    ["code" => "GEL", "symbol" => "GEL", "name" => "Georgian lari"],
    ["code" => "GHS", "symbol" => "GH¢", "name" => "Ghanaian cedi"],
    ["code" => "GIP", "symbol" => "£", "name" => "Gibraltar pound"],
    ["code" => "GMD", "symbol" => "D", "name" => "Gambian dalasi"],
    ["code" => "GNF", "symbol" => "FG", "name" => "Guinean franc"],
    ["code" => "GQE", "symbol" => "CFA", "name" => "Central African CFA franc"],
    ["code" => "GTQ", "symbol" => "Q", "name" => "Guatemalan quetzal"],
    ["code" => "GYD", "symbol" => "GY$", "name" => "Guyanese dollar"],
    ["code" => "HKD", "symbol" => "HK$", "name" => "Hong Kong dollar"],
    ["code" => "HNL", "symbol" => "L", "name" => "Honduran lempira"],
    ["code" => "HRK", "symbol" => "kn", "name" => "Croatian kuna"],
    ["code" => "HTG", "symbol" => "G", "name" => "Haitian gourde"],
    ["code" => "HUF", "symbol" => "Ft", "name" => "Hungarian forint"],
    ["code" => "IDR", "symbol" => "Rp", "name" => "Indonesian rupiah"],
    ["code" => "ILS", "symbol" => "₪", "name" => "Israeli new sheqel"],
    ["code" => "INR", "symbol" => "₹", "name" => "Indian rupee"],
    ["code" => "IQD", "symbol" => "ع.د", "name" => "Iraqi dinar"],
    ["code" => "IRR", "symbol" => "IRR", "name" => "Iranian rial"],
    ["code" => "ISK", "symbol" => "kr", "name" => "Icelandic kr\u00f3na"],
    ["code" => "JMD", "symbol" => "J$", "name" => "Jamaican dollar"],
    ["code" => "JOD", "symbol" => "JOD", "name" => "Jordanian dinar"],
    ["code" => "JPY", "symbol" => "¥", "name" => "Japanese yen"],
    ["code" => "KES", "symbol" => "KSh", "name" => "Kenyan shilling"],
    ["code" => "KGS", "symbol" => "Лв", "name" => "Kyrgyzstani som"],
    ["code" => "KHR", "symbol" => "៛", "name" => "Cambodian riel"],
    ["code" => "KMF", "symbol" => "KMF", "name" => "Comorian franc"],
    ["code" => "KPW", "symbol" => "W", "name" => "North Korean won"],
    ["code" => "KRW", "symbol" => "W", "name" => "South Korean won"],
    ["code" => "KWD", "symbol" => "KWD", "name" => "Kuwaiti dinar"],
    ["code" => "KYD", "symbol" => "KY$", "name" => "Cayman Islands dollar"],
    ["code" => "KZT", "symbol" => "T", "name" => "Kazakhstani tenge"],
    ["code" => "LAK", "symbol" => "KN", "name" => "Lao kip"],
    ["code" => "LBP", "symbol" => ".ل.ل", "name" => "Lebanese lira"],
    ["code" => "LKR", "symbol" => "Rs", "name" => "Sri Lankan rupee"],
    ["code" => "LRD", "symbol" => "L$", "name" => "Liberian dollar"],
    ["code" => "LSL", "symbol" => "M", "name" => "Lesotho loti"],
    ["code" => "LTL", "symbol" => "Lt", "name" => "Lithuanian litas"],
    ["code" => "LVL", "symbol" => "Ls", "name" => "Latvian lats"],
    ["code" => "LYD", "symbol" => "LD", "name" => "Libyan dinar"],
    ["code" => "MAD", "symbol" => "MAD", "name" => "Morocodean dirham"],
    ["code" => "MDL", "symbol" => "MDL", "name" => "Moldovan leu"],
    ["code" => "MGA", "symbol" => "FMG", "name" => "Malagasy ariary"],
    ["code" => "MKD", "symbol" => "MKD", "name" => "Macedonian denar"],
    ["code" => "MMK", "symbol" => "K", "name" => "Myanma kyat"],
    ["code" => "MNT", "symbol" => "₮", "name" => "Mongolian tugrik"],
    ["code" => "MOP", "symbol" => "P", "name" => "Macanese pataca"],
    ["code" => "MRO", "symbol" => "UM", "name" => "Mauritanian ouguiya"],
    ["code" => "MUR", "symbol" => "Rs", "name" => "Mauritian rupee"],
    ["code" => "MVR", "symbol" => "Rf", "name" => "Maldivian rufiyaa"],
    ["code" => "MWK", "symbol" => "MK", "name" => "Malawian kwacha"],
    ["code" => "MXN", "symbol" => "$", "name" => "Mexican peso"],
    ["code" => "MYR", "symbol" => "RM", "name" => "Malaysian ringgit"],
    ["code" => "MZM", "symbol" => "MTn", "name" => "Mozambican metical"],
    ["code" => "NAD", "symbol" => "N$", "name" => "Namibian dollar"],
    ["code" => "NGN", "symbol" => "₦", "name" => "Nigerian naira"],
    ["code" => "NIO", "symbol" => "C$", "name" => "Nicaraguan c\u00f3rdoba"],
    ["code" => "NOK", "symbol" => "kr", "name" => "Norwegian krone"],
    ["code" => "NPR", "symbol" => "NRs", "name" => "Nepalese rupee"],
    ["code" => "NZD", "symbol" => "NZ$", "name" => "New Zealand dollar"],
    ["code" => "OMR", "symbol" => "OMR", "name" => "Omani rial"],
    ["code" => "PAB", "symbol" => "B/.", "name" => "Panamanian balboa"],
    ["code" => "PEN", "symbol" => "S/.", "name" => "Peruvian nuevo sol"],
    ["code" => "PGK", "symbol" => "K", "name" => "Papua New Guinean kina"],
    ["code" => "PHP", "symbol" => "₱", "name" => "Philippine peso"],
    ["code" => "PKR", "symbol" => "Rs.", "name" => "Pakistani rupee"],
    ["code" => "PLN", "symbol" => "zł", "name" => "Polish zloty"],
    ["code" => "PYG", "symbol" => "₲", "name" => "Paraguayan guarani"],
    ["code" => "QAR", "symbol" => "QR", "name" => "Qatari riyal"],
    ["code" => "RON", "symbol" => "L", "name" => "Romanian leu"],
    ["code" => "RSD", "symbol" => "din.", "name" => "Serbian dinar"],
    ["code" => "RUB", "symbol" => "R", "name" => "Russian ruble"],
    ["code" => "SAR", "symbol" => "SR", "name" => "Saudi riyal"],
    ["code" => "SBD", "symbol" => "SI$", "name" => "Solomon Islands dollar"],
    ["code" => "SCR", "symbol" => "SR", "name" => "Seychellois rupee"],
    ["code" => "SDG", "symbol" => "SDG", "name" => "Sudanese pound"],
    ["code" => "SEK", "symbol" => "kr", "name" => "Swedish krona"],
    ["code" => "SGD", "symbol" => "S$", "name" => "Singapore dollar"],
    ["code" => "SHP", "symbol" => "£", "name" => "Saint Helena pound"],
    ["code" => "SLL", "symbol" => "Le", "name" => "Sierra Leonean leone"],
    ["code" => "SOS", "symbol" => "Sh.", "name" => "Somali shilling"],
    ["code" => "SRD", "symbol" => "$", "name" => "Surinamese dollar"],
    ["code" => "SYP", "symbol" => "LS", "name" => "Syrian pound"],
    ["code" => "SZL", "symbol" => "E", "name" => "Swazi lilangeni"],
    ["code" => "THB", "symbol" => "฿", "name" => "Thai baht"],
    ["code" => "TJS", "symbol" => "TJS", "name" => "Tajikistani somoni"],
    ["code" => "TMT", "symbol" => "m", "name" => "Turkmen manat"],
    ["code" => "TND", "symbol" => "DT", "name" => "Tunisian dinar"],
    ["code" => "TRY", "symbol" => "TRY", "name" => "Turkish new lira"],
    ["code" => "TTD", "symbol" => "TT$", "name" => "Trinidad and Tobago dollar"],
    ["code" => "TWD", "symbol" => "NT$", "name" => "New Taiwan dollar"],
    ["code" => "TZS", "symbol" => "TZS", "name" => "Tanzanian shilling"],
    ["code" => "UAH", "symbol" => "UAH", "name" => "Ukrainian hryvnia"],
    ["code" => "UGX", "symbol" => "USh", "name" => "Ugandan shilling"],
    ["code" => "USD", "symbol" => "$", "name" => "United States dollar"],
    ["code" => "UYU", "symbol" => '$U', "name" => "Uruguayan peso"],
    ["code" => "UZS", "symbol" => "UZS", "name" => "Uzbekistani som"],
    ["code" => "VEB", "symbol" => "Bs", "name" => "Venezuelan bolivar"],
    ["code" => "VND", "symbol" => "₫", "name" => "Vietnamese dong"],
    ["code" => "VUV", "symbol" => "VT", "name" => "Vanuatu vatu"],
    ["code" => "WST", "symbol" => "WS$", "name" => "Samoan tala"],
    ["code" => "XAF", "symbol" => "CFA", "name" => "Central African CFA franc"],
    ["code" => "XCD", "symbol" => "EC$", "name" => "East Caribbean dollar"],
    ["code" => "XDR", "symbol" => "SDR", "name" => "Special Drawing Rights"],
    ["code" => "XOF", "symbol" => "CFA", "name" => "West African CFA franc"],
    ["code" => "XPF", "symbol" => "F", "name" => "CFP franc"],
    ["code" => "YER", "symbol" => "YER", "name" => "Yemeni rial"],
    ["code" => "ZAR", "symbol" => "R", "name" => "South African rand"],
    ["code" => "ZMK", "symbol" => "ZK", "name" => "Zambian kwacha"],
    ["code" => "ZWR", "symbol" => "Z$", "name" => "Zimbabwean dollar"]
];

//countries
const COUNTRIES = [
    ["name" => 'Afghanistan', "code" => 'AF'],
    ["name" => 'Åland Islands', "code" => 'AX'],
    ["name" => 'Albania', "code" => 'AL'],
    ["name" => 'Algeria', "code" => 'DZ'],
    ["name" => 'American Samoa', "code" => 'AS'],
    ["name" => 'AndorrA', "code" => 'AD'],
    ["name" => 'Angola', "code" => 'AO'],
    ["name" => 'Anguilla', "code" => 'AI'],
    ["name" => 'Antarctica', "code" => 'AQ'],
    ["name" => 'Antigua and Barbuda', "code" => 'AG'],
    ["name" => 'Argentina', "code" => 'AR'],
    ["name" => 'Armenia', "code" => 'AM'],
    ["name" => 'Aruba', "code" => 'AW'],
    ["name" => 'Australia', "code" => 'AU'],
    ["name" => 'Austria', "code" => 'AT'],
    ["name" => 'Azerbaijan', "code" => 'AZ'],
    ["name" => 'Bahamas', "code" => 'BS'],
    ["name" => 'Bahrain', "code" => 'BH'],
    ["name" => 'Bangladesh', "code" => 'BD'],
    ["name" => 'Barbados', "code" => 'BB'],
    ["name" => 'Belarus', "code" => 'BY'],
    ["name" => 'Belgium', "code" => 'BE'],
    ["name" => 'Belize', "code" => 'BZ'],
    ["name" => 'Benin', "code" => 'BJ'],
    ["name" => 'Bermuda', "code" => 'BM'],
    ["name" => 'Bhutan', "code" => 'BT'],
    ["name" => 'Bolivia', "code" => 'BO'],
    ["name" => 'Bosnia and Herzegovina', "code" => 'BA'],
    ["name" => 'Botswana', "code" => 'BW'],
    ["name" => 'Bouvet Island', "code" => 'BV'],
    ["name" => 'Brazil', "code" => 'BR'],
    ["name" => 'British Indian Ocean Territory', "code" => 'IO'],
    ["name" => 'Brunei Darussalam', "code" => 'BN'],
    ["name" => 'Bulgaria', "code" => 'BG'],
    ["name" => 'Burkina Faso', "code" => 'BF'],
    ["name" => 'Burundi', "code" => 'BI'],
    ["name" => 'Cambodia', "code" => 'KH'],
    ["name" => 'Cameroon', "code" => 'CM'],
    ["name" => 'Canada', "code" => 'CA'],
    ["name" => 'Cape Verde', "code" => 'CV'],
    ["name" => 'Cayman Islands', "code" => 'KY'],
    ["name" => 'Central African Republic', "code" => 'CF'],
    ["name" => 'Chad', "code" => 'TD'],
    ["name" => 'Chile', "code" => 'CL'],
    ["name" => 'China', "code" => 'CN'],
    ["name" => 'Christmas Island', "code" => 'CX'],
    ["name" => 'Cocos (Keeling) Islands', "code" => 'CC'],
    ["name" => 'Colombia', "code" => 'CO'],
    ["name" => 'Comoros', "code" => 'KM'],
    ["name" => 'Congo', "code" => 'CG'],
    ["name" => 'Congo, The Democratic Republic of the', "code" => 'CD'],
    ["name" => 'Cook Islands', "code" => 'CK'],
    ["name" => 'Costa Rica', "code" => 'CR'],
    ["name" => 'Cote D\'Ivoire', "code" => 'CI'],
    ["name" => 'Croatia', "code" => 'HR'],
    ["name" => 'Cuba', "code" => 'CU'],
    ["name" => 'Cyprus', "code" => 'CY'],
    ["name" => 'Czech Republic', "code" => 'CZ'],
    ["name" => 'Denmark', "code" => 'DK'],
    ["name" => 'Djibouti', "code" => 'DJ'],
    ["name" => 'Dominica', "code" => 'DM'],
    ["name" => 'Dominican Republic', "code" => 'DO'],
    ["name" => 'Ecuador', "code" => 'EC'],
    ["name" => 'Egypt', "code" => 'EG'],
    ["name" => 'El Salvador', "code" => 'SV'],
    ["name" => 'Equatorial Guinea', "code" => 'GQ'],
    ["name" => 'Eritrea', "code" => 'ER'],
    ["name" => 'Estonia', "code" => 'EE'],
    ["name" => 'Ethiopia', "code" => 'ET'],
    ["name" => 'Falkland Islands (Malvinas)', "code" => 'FK'],
    ["name" => 'Faroe Islands', "code" => 'FO'],
    ["name" => 'Fiji', "code" => 'FJ'],
    ["name" => 'Finland', "code" => 'FI'],
    ["name" => 'France', "code" => 'FR'],
    ["name" => 'French Guiana', "code" => 'GF'],
    ["name" => 'French Polynesia', "code" => 'PF'],
    ["name" => 'French Southern Territories', "code" => 'TF'],
    ["name" => 'Gabon', "code" => 'GA'],
    ["name" => 'Gambia', "code" => 'GM'],
    ["name" => 'Georgia', "code" => 'GE'],
    ["name" => 'Germany', "code" => 'DE'],
    ["name" => 'Ghana', "code" => 'GH'],
    ["name" => 'Gibraltar', "code" => 'GI'],
    ["name" => 'Greece', "code" => 'GR'],
    ["name" => 'Greenland', "code" => 'GL'],
    ["name" => 'Grenada', "code" => 'GD'],
    ["name" => 'Guadeloupe', "code" => 'GP'],
    ["name" => 'Guam', "code" => 'GU'],
    ["name" => 'Guatemala', "code" => 'GT'],
    ["name" => 'Guernsey', "code" => 'GG'],
    ["name" => 'Guinea', "code" => 'GN'],
    ["name" => 'Guinea-Bissau', "code" => 'GW'],
    ["name" => 'Guyana', "code" => 'GY'],
    ["name" => 'Haiti', "code" => 'HT'],
    ["name" => 'Heard Island and Mcdonald Islands', "code" => 'HM'],
    ["name" => 'Holy See (Vatican City State)', "code" => 'VA'],
    ["name" => 'Honduras', "code" => 'HN'],
    ["name" => 'Hong Kong', "code" => 'HK'],
    ["name" => 'Hungary', "code" => 'HU'],
    ["name" => 'Iceland', "code" => 'IS'],
    ["name" => 'India', "code" => 'IN'],
    ["name" => 'Indonesia', "code" => 'ID'],
    ["name" => 'Iran, Islamic Republic Of', "code" => 'IR'],
    ["name" => 'Iraq', "code" => 'IQ'],
    ["name" => 'Ireland', "code" => 'IE'],
    ["name" => 'Isle of Man', "code" => 'IM'],
    ["name" => 'Israel', "code" => 'IL'],
    ["name" => 'Italy', "code" => 'IT'],
    ["name" => 'Jamaica', "code" => 'JM'],
    ["name" => 'Japan', "code" => 'JP'],
    ["name" => 'Jersey', "code" => 'JE'],
    ["name" => 'Jordan', "code" => 'JO'],
    ["name" => 'Kazakhstan', "code" => 'KZ'],
    ["name" => 'Kenya', "code" => 'KE'],
    ["name" => 'Kiribati', "code" => 'KI'],
    ["name" => 'Korea, Democratic People\'S Republic of', "code" => 'KP'],
    ["name" => 'Korea, Republic of', "code" => 'KR'],
    ["name" => 'Kuwait', "code" => 'KW'],
    ["name" => 'Kyrgyzstan', "code" => 'KG'],
    ["name" => 'Lao People\'S Democratic Republic', "code" => 'LA'],
    ["name" => 'Latvia', "code" => 'LV'],
    ["name" => 'Lebanon', "code" => 'LB'],
    ["name" => 'Lesotho', "code" => 'LS'],
    ["name" => 'Liberia', "code" => 'LR'],
    ["name" => 'Libyan Arab Jamahiriya', "code" => 'LY'],
    ["name" => 'Liechtenstein', "code" => 'LI'],
    ["name" => 'Lithuania', "code" => 'LT'],
    ["name" => 'Luxembourg', "code" => 'LU'],
    ["name" => 'Macao', "code" => 'MO'],
    ["name" => 'Macedonia, The Former Yugoslav Republic of', "code" => 'MK'],
    ["name" => 'Madagascar', "code" => 'MG'],
    ["name" => 'Malawi', "code" => 'MW'],
    ["name" => 'Malaysia', "code" => 'MY'],
    ["name" => 'Maldives', "code" => 'MV'],
    ["name" => 'Mali', "code" => 'ML'],
    ["name" => 'Malta', "code" => 'MT'],
    ["name" => 'Marshall Islands', "code" => 'MH'],
    ["name" => 'Martinique', "code" => 'MQ'],
    ["name" => 'Mauritania', "code" => 'MR'],
    ["name" => 'Mauritius', "code" => 'MU'],
    ["name" => 'Mayotte', "code" => 'YT'],
    ["name" => 'Mexico', "code" => 'MX'],
    ["name" => 'Micronesia, Federated States of', "code" => 'FM'],
    ["name" => 'Moldova, Republic of', "code" => 'MD'],
    ["name" => 'Monaco', "code" => 'MC'],
    ["name" => 'Mongolia', "code" => 'MN'],
    ["name" => 'Montserrat', "code" => 'MS'],
    ["name" => 'Morocco', "code" => 'MA'],
    ["name" => 'Mozambique', "code" => 'MZ'],
    ["name" => 'Myanmar', "code" => 'MM'],
    ["name" => 'Namibia', "code" => 'NA'],
    ["name" => 'Nauru', "code" => 'NR'],
    ["name" => 'Nepal', "code" => 'NP'],
    ["name" => 'Netherlands', "code" => 'NL'],
    ["name" => 'Netherlands Antilles', "code" => 'AN'],
    ["name" => 'New Caledonia', "code" => 'NC'],
    ["name" => 'New Zealand', "code" => 'NZ'],
    ["name" => 'Nicaragua', "code" => 'NI'],
    ["name" => 'Niger', "code" => 'NE'],
    ["name" => 'Nigeria', "code" => 'NG'],
    ["name" => 'Niue', "code" => 'NU'],
    ["name" => 'Norfolk Island', "code" => 'NF'],
    ["name" => 'Northern Mariana Islands', "code" => 'MP'],
    ["name" => 'Norway', "code" => 'NO'],
    ["name" => 'Oman', "code" => 'OM'],
    ["name" => 'Pakistan', "code" => 'PK'],
    ["name" => 'Palau', "code" => 'PW'],
    ["name" => 'Palestinian Territory, Occupied', "code" => 'PS'],
    ["name" => 'Panama', "code" => 'PA'],
    ["name" => 'Papua New Guinea', "code" => 'PG'],
    ["name" => 'Paraguay', "code" => 'PY'],
    ["name" => 'Peru', "code" => 'PE'],
    ["name" => 'Philippines', "code" => 'PH'],
    ["name" => 'Pitcairn', "code" => 'PN'],
    ["name" => 'Poland', "code" => 'PL'],
    ["name" => 'Portugal', "code" => 'PT'],
    ["name" => 'Puerto Rico', "code" => 'PR'],
    ["name" => 'Qatar', "code" => 'QA'],
    ["name" => 'Reunion', "code" => 'RE'],
    ["name" => 'Romania', "code" => 'RO'],
    ["name" => 'Russian Federation', "code" => 'RU'],
    ["name" => 'RWANDA', "code" => 'RW'],
    ["name" => 'Saint Helena', "code" => 'SH'],
    ["name" => 'Saint Kitts and Nevis', "code" => 'KN'],
    ["name" => 'Saint Lucia', "code" => 'LC'],
    ["name" => 'Saint Pierre and Miquelon', "code" => 'PM'],
    ["name" => 'Saint Vincent and the Grenadines', "code" => 'VC'],
    ["name" => 'Samoa', "code" => 'WS'],
    ["name" => 'San Marino', "code" => 'SM'],
    ["name" => 'Sao Tome and Principe', "code" => 'ST'],
    ["name" => 'Saudi Arabia', "code" => 'SA'],
    ["name" => 'Senegal', "code" => 'SN'],
    ["name" => 'Serbia and Montenegro', "code" => 'CS'],
    ["name" => 'Seychelles', "code" => 'SC'],
    ["name" => 'Sierra Leone', "code" => 'SL'],
    ["name" => 'Singapore', "code" => 'SG'],
    ["name" => 'Slovakia', "code" => 'SK'],
    ["name" => 'Slovenia', "code" => 'SI'],
    ["name" => 'Solomon Islands', "code" => 'SB'],
    ["name" => 'Somalia', "code" => 'SO'],
    ["name" => 'South Africa', "code" => 'ZA'],
    ["name" => 'South Georgia and the South Sandwich Islands', "code" => 'GS'],
    ["name" => 'Spain', "code" => 'ES'],
    ["name" => 'Sri Lanka', "code" => 'LK'],
    ["name" => 'Sudan', "code" => 'SD'],
    ["name" => 'Suriname', "code" => 'SR'],
    ["name" => 'Svalbard and Jan Mayen', "code" => 'SJ'],
    ["name" => 'Swaziland', "code" => 'SZ'],
    ["name" => 'Sweden', "code" => 'SE'],
    ["name" => 'Switzerland', "code" => 'CH'],
    ["name" => 'Syrian Arab Republic', "code" => 'SY'],
    ["name" => 'Taiwan, Province of China', "code" => 'TW'],
    ["name" => 'Tajikistan', "code" => 'TJ'],
    ["name" => 'Tanzania, United Republic of', "code" => 'TZ'],
    ["name" => 'Thailand', "code" => 'TH'],
    ["name" => 'Timor-Leste', "code" => 'TL'],
    ["name" => 'Togo', "code" => 'TG'],
    ["name" => 'Tokelau', "code" => 'TK'],
    ["name" => 'Tonga', "code" => 'TO'],
    ["name" => 'Trinidad and Tobago', "code" => 'TT'],
    ["name" => 'Tunisia', "code" => 'TN'],
    ["name" => 'Turkey', "code" => 'TR'],
    ["name" => 'Turkmenistan', "code" => 'TM'],
    ["name" => 'Turks and Caicos Islands', "code" => 'TC'],
    ["name" => 'Tuvalu', "code" => 'TV'],
    ["name" => 'Uganda', "code" => 'UG'],
    ["name" => 'Ukraine', "code" => 'UA'],
    ["name" => 'United Arab Emirates', "code" => 'AE'],
    ["name" => 'United Kingdom', "code" => 'GB'],
    ["name" => 'United States', "code" => 'US'],
    ["name" => 'United States Minor Outlying Islands', "code" => 'UM'],
    ["name" => 'Uruguay', "code" => 'UY'],
    ["name" => 'Uzbekistan', "code" => 'UZ'],
    ["name" => 'Vanuatu', "code" => 'VU'],
    ["name" => 'Venezuela', "code" => 'VE'],
    ["name" => 'Viet Nam', "code" => 'VN'],
    ["name" => 'Virgin Islands, British', "code" => 'VG'],
    ["name" => 'Virgin Islands, U.S.', "code" => 'VI'],
    ["name" => 'Wallis and Futuna', "code" => 'WF'],
    ["name" => 'Western Sahara', "code" => 'EH'],
    ["name" => 'Yemen', "code" => 'YE'],
    ["name" => 'Zambia', "code" => 'ZM'],
    ["name" => 'Zimbabwe', "code" => 'ZW']
];

//languages
const LANGUAGES = [
    ["code" => "ab", "name" => "Abkhaz", "nativeName" => "аҧсуа"],
    ["code" => "aa", "name" => "Afar", "nativeName" => "Afaraf"],
    ["code" => "af", "name" => "Afrikaans", "nativeName" => "Afrikaans"],
    ["code" => "ak", "name" => "Akan", "nativeName" => "Akan"],
    ["code" => "sq", "name" => "Albanian", "nativeName" => "Shqip"],
    ["code" => "am", "name" => "Amharic", "nativeName" => "አማርኛ"],
    ["code" => "ar", "name" => "Arabic", "nativeName" => "العربية"],
    ["code" => "an", "name" => "Aragonese", "nativeName" => "Aragonés"],
    ["code" => "hy", "name" => "Armenian", "nativeName" => "Հայերեն"],
    ["code" => "as", "name" => "Assamese", "nativeName" => "অসমীয়া"],
    ["code" => "av", "name" => "Avaric", "nativeName" => "авар мацӀ, магӀарул мацӀ"],
    ["code" => "ae", "name" => "Avestan", "nativeName" => "avesta"],
    ["code" => "ay", "name" => "Aymara", "nativeName" => "aymar aru"],
    ["code" => "az", "name" => "Azerbaijani", "nativeName" => "azərbaycan dili"],
    ["code" => "bm", "name" => "Bambara", "nativeName" => "bamanankan"],
    ["code" => "ba", "name" => "Bashkir", "nativeName" => "башҡорт теле"],
    ["code" => "eu", "name" => "Basque", "nativeName" => "euskara, euskera"],
    ["code" => "be", "name" => "Belarusian", "nativeName" => "Беларуская"],
    ["code" => "bn", "name" => "Bengali", "nativeName" => "বাংলা"],
    ["code" => "bh", "name" => "Bihari", "nativeName" => "भोजपुरी"],
    ["code" => "bi", "name" => "Bislama", "nativeName" => "Bislama"],
    ["code" => "bs", "name" => "Bosnian", "nativeName" => "bosanski jezik"],
    ["code" => "br", "name" => "Breton", "nativeName" => "brezhoneg"],
    ["code" => "bg", "name" => "Bulgarian", "nativeName" => "български език"],
    ["code" => "my", "name" => "Burmese", "nativeName" => "ဗမာစာ"],
    ["code" => "ca", "name" => "Catalan; Valencian", "nativeName" => "Català"],
    ["code" => "ch", "name" => "Chamorro", "nativeName" => "Chamoru"],
    ["code" => "ce", "name" => "Chechen", "nativeName" => "нохчийн мотт"],
    ["code" => "ny", "name" => "Chichewa; Chewa; Nyanja", "nativeName" => "chiCheŵa, chinyanja"],
    ["code" => "zh", "name" => "Chinese", "nativeName" => "中文 (Zhōngwén), 汉语, 漢語"],
    ["code" => "cv", "name" => "Chuvash", "nativeName" => "чӑваш чӗлхи"],
    ["code" => "kw", "name" => "Cornish", "nativeName" => "Kernewek"],
    ["code" => "co", "name" => "Corsican", "nativeName" => "corsu, lingua corsa"],
    ["code" => "cr", "name" => "Cree", "nativeName" => "ᓀᐦᐃᔭᐍᐏᐣ"],
    ["code" => "hr", "name" => "Croatian", "nativeName" => "hrvatski"],
    ["code" => "cs", "name" => "Czech", "nativeName" => "česky, čeština"],
    ["code" => "da", "name" => "Danish", "nativeName" => "dansk"],
    ["code" => "dv", "name" => "Divehi; Dhivehi; Maldivian;", "nativeName" => "ދިވެހި"],
    ["code" => "nl", "name" => "Dutch", "nativeName" => "Nederlands, Vlaams"],
    ["code" => "en", "name" => "English", "nativeName" => "English"],
    ["code" => "eo", "name" => "Esperanto", "nativeName" => "Esperanto"],
    ["code" => "et", "name" => "Estonian", "nativeName" => "eesti, eesti keel"],
    ["code" => "ee", "name" => "Ewe", "nativeName" => "Eʋegbe"],
    ["code" => "fo", "name" => "Faroese", "nativeName" => "føroyskt"],
    ["code" => "fj", "name" => "Fijian", "nativeName" => "vosa Vakaviti"],
    ["code" => "fi", "name" => "Finnish", "nativeName" => "suomi, suomen kieli"],
    ["code" => "fr", "name" => "French", "nativeName" => "français, langue française"],
    ["code" => "ff", "name" => "Fula; Fulah; Pulaar; Pular", "nativeName" => "Fulfulde, Pulaar, Pular"],
    ["code" => "gl", "name" => "Galician", "nativeName" => "Galego"],
    ["code" => "ka", "name" => "Georgian", "nativeName" => "ქართული"],
    ["code" => "de", "name" => "German", "nativeName" => "Deutsch"],
    ["code" => "el", "name" => "Greek, Modern", "nativeName" => "Ελληνικά"],
    ["code" => "gn", "name" => "Guaraní", "nativeName" => "Avañeẽ"],
    ["code" => "gu", "name" => "Gujarati", "nativeName" => "ગુજરાતી"],
    ["code" => "ht", "name" => "Haitian; Haitian Creole", "nativeName" => "Kreyòl ayisyen"],
    ["code" => "ha", "name" => "Hausa", "nativeName" => "Hausa, هَوُسَ"],
    ["code" => "he", "name" => "Hebrew (modern)", "nativeName" => "עברית"],
    ["code" => "hz", "name" => "Herero", "nativeName" => "Otjiherero"],
    ["code" => "hi", "name" => "Hindi", "nativeName" => "हिन्दी, हिंदी"],
    ["code" => "ho", "name" => "Hiri Motu", "nativeName" => "Hiri Motu"],
    ["code" => "hu", "name" => "Hungarian", "nativeName" => "Magyar"],
    ["code" => "ia", "name" => "Interlingua", "nativeName" => "Interlingua"],
    ["code" => "id", "name" => "Indonesian", "nativeName" => "Bahasa Indonesia"],
    ["code" => "ie", "name" => "Interlingue", "nativeName" => "Originally called Occidental; then Interlingue after WWII"],
    ["code" => "ga", "name" => "Irish", "nativeName" => "Gaeilge"],
    ["code" => "ig", "name" => "Igbo", "nativeName" => "Asụsụ Igbo"],
    ["code" => "ik", "name" => "Inupiaq", "nativeName" => "Iñupiaq, Iñupiatun"],
    ["code" => "io", "name" => "Ido", "nativeName" => "Ido"],
    ["code" => "is", "name" => "Icelandic", "nativeName" => "Íslenska"],
    ["code" => "it", "name" => "Italian", "nativeName" => "Italiano"],
    ["code" => "iu", "name" => "Inuktitut", "nativeName" => "ᐃᓄᒃᑎᑐᑦ"],
    ["code" => "ja", "name" => "Japanese", "nativeName" => "日本語 (にほんご／にっぽんご)"],
    ["code" => "jv", "name" => "Javanese", "nativeName" => "basa Jawa"],
    ["code" => "kl", "name" => "Kalaallisut, Greenlandic", "nativeName" => "kalaallisut, kalaallit oqaasii"],
    ["code" => "kn", "name" => "Kannada", "nativeName" => "ಕನ್ನಡ"],
    ["code" => "kr", "name" => "Kanuri", "nativeName" => "Kanuri"],
    ["code" => "ks", "name" => "Kashmiri", "nativeName" => "कश्मीरी, كشميري‎"],
    ["code" => "kk", "name" => "Kazakh", "nativeName" => "Қазақ тілі"],
    ["code" => "km", "name" => "Khmer", "nativeName" => "ភាសាខ្មែរ"],
    ["code" => "ki", "name" => "Kikuyu, Gikuyu", "nativeName" => "Gĩkũyũ"],
    ["code" => "rw", "name" => "Kinyarwanda", "nativeName" => "Ikinyarwanda"],
    ["code" => "ky", "name" => "Kirghiz, Kyrgyz", "nativeName" => "кыргыз тили"],
    ["code" => "kv", "name" => "Komi", "nativeName" => "коми кыв"],
    ["code" => "kg", "name" => "Kongo", "nativeName" => "KiKongo"],
    ["code" => "ko", "name" => "Korean", "nativeName" => "한국어 (韓國語), 조선말 (朝鮮語)"],
    ["code" => "ku", "name" => "Kurdish", "nativeName" => "Kurdî, كوردی‎"],
    ["code" => "kj", "name" => "Kwanyama, Kuanyama", "nativeName" => "Kuanyama"],
    ["code" => "la", "name" => "Latin", "nativeName" => "latine, lingua latina"],
    ["code" => "lb", "name" => "Luxembourgish, Letzeburgesch", "nativeName" => "Lëtzebuergesch"],
    ["code" => "lg", "name" => "Luganda", "nativeName" => "Luganda"],
    ["code" => "li", "name" => "Limburgish, Limburgan, Limburger", "nativeName" => "Limburgs"],
    ["code" => "ln", "name" => "Lingala", "nativeName" => "Lingála"],
    ["code" => "lo", "name" => "Lao", "nativeName" => "ພາສາລາວ"],
    ["code" => "lt", "name" => "Lithuanian", "nativeName" => "lietuvių kalba"],
    ["code" => "lu", "name" => "Luba-Katanga", "nativeName" => ""],
    ["code" => "lv", "name" => "Latvian", "nativeName" => "latviešu valoda"],
    ["code" => "gv", "name" => "Manx", "nativeName" => "Gaelg, Gailck"],
    ["code" => "mk", "name" => "Macedonian", "nativeName" => "македонски јазик"],
    ["code" => "mg", "name" => "Malagasy", "nativeName" => "Malagasy fiteny"],
    ["code" => "ms", "name" => "Malay", "nativeName" => "bahasa Melayu, بهاس ملايو‎"],
    ["code" => "ml", "name" => "Malayalam", "nativeName" => "മലയാളം"],
    ["code" => "mt", "name" => "Maltese", "nativeName" => "Malti"],
    ["code" => "mi", "name" => "Māori", "nativeName" => "te reo Māori"],
    ["code" => "mr", "name" => "Marathi (Marāṭhī)", "nativeName" => "मराठी"],
    ["code" => "mh", "name" => "Marshallese", "nativeName" => "Kajin M̧ajeļ"],
    ["code" => "mn", "name" => "Mongolian", "nativeName" => "монгол"],
    ["code" => "na", "name" => "Nauru", "nativeName" => "Ekakairũ Naoero"],
    ["code" => "nv", "name" => "Navajo, Navaho", "nativeName" => "Diné bizaad, Dinékʼehǰí"],
    ["code" => "nb", "name" => "Norwegian Bokmål", "nativeName" => "Norsk bokmål"],
    ["code" => "nd", "name" => "North Ndebele", "nativeName" => "isiNdebele"],
    ["code" => "ne", "name" => "Nepali", "nativeName" => "नेपाली"],
    ["code" => "ng", "name" => "Ndonga", "nativeName" => "Owambo"],
    ["code" => "nn", "name" => "Norwegian Nynorsk", "nativeName" => "Norsk nynorsk"],
    ["code" => "no", "name" => "Norwegian", "nativeName" => "Norsk"],
    ["code" => "ii", "name" => "Nuosu", "nativeName" => "ꆈꌠ꒿ Nuosuhxop"],
    ["code" => "nr", "name" => "South Ndebele", "nativeName" => "isiNdebele"],
    ["code" => "oc", "name" => "Occitan", "nativeName" => "Occitan"],
    ["code" => "oj", "name" => "Ojibwe, Ojibwa", "nativeName" => "ᐊᓂᔑᓈᐯᒧᐎᓐ"],
    ["code" => "cu", "name" => "Old Church Slavonic, Church Slavic, Church Slavonic, Old Bulgarian, Old Slavonic", "nativeName" => "ѩзыкъ словѣньскъ"],
    ["code" => "om", "name" => "Oromo", "nativeName" => "Afaan Oromoo"],
    ["code" => "or", "name" => "Oriya", "nativeName" => "ଓଡ଼ିଆ"],
    ["code" => "os", "name" => "Ossetian, Ossetic", "nativeName" => "ирон æвзаг"],
    ["code" => "pa", "name" => "Panjabi, Punjabi", "nativeName" => "ਪੰਜਾਬੀ, پنجابی‎"],
    ["code" => "pi", "name" => "Pāli", "nativeName" => "पाऴि"],
    ["code" => "fa", "name" => "Persian", "nativeName" => "فارسی"],
    ["code" => "pl", "name" => "Polish", "nativeName" => "polski"],
    ["code" => "ps", "name" => "Pashto, Pushto", "nativeName" => "پښتو"],
    ["code" => "pt", "name" => "Portuguese", "nativeName" => "Português"],
    ["code" => "qu", "name" => "Quechua", "nativeName" => "Runa Simi, Kichwa"],
    ["code" => "rm", "name" => "Romansh", "nativeName" => "rumantsch grischun"],
    ["code" => "rn", "name" => "Kirundi", "nativeName" => "kiRundi"],
    ["code" => "ro", "name" => "Romanian, Moldavian, Moldovan", "nativeName" => "română"],
    ["code" => "ru", "name" => "Russian", "nativeName" => "русский язык"],
    ["code" => "sa", "name" => "Sanskrit (Saṁskṛta)", "nativeName" => "संस्कृतम्"],
    ["code" => "sc", "name" => "Sardinian", "nativeName" => "sardu"],
    ["code" => "sd", "name" => "Sindhi", "nativeName" => "सिन्धी, سنڌي، سندھی‎"],
    ["code" => "se", "name" => "Northern Sami", "nativeName" => "Davvisámegiella"],
    ["code" => "sm", "name" => "Samoan", "nativeName" => "gagana faa Samoa"],
    ["code" => "sg", "name" => "Sango", "nativeName" => "yângâ tî sängö"],
    ["code" => "sr", "name" => "Serbian", "nativeName" => "српски језик"],
    ["code" => "gd", "name" => "Scottish Gaelic; Gaelic", "nativeName" => "Gàidhlig"],
    ["code" => "sn", "name" => "Shona", "nativeName" => "chiShona"],
    ["code" => "si", "name" => "Sinhala, Sinhalese", "nativeName" => "සිංහල"],
    ["code" => "sk", "name" => "Slovak", "nativeName" => "slovenčina"],
    ["code" => "sl", "name" => "Slovene", "nativeName" => "slovenščina"],
    ["code" => "so", "name" => "Somali", "nativeName" => "Soomaaliga, af Soomaali"],
    ["code" => "st", "name" => "Southern Sotho", "nativeName" => "Sesotho"],
    ["code" => "es", "name" => "Spanish; Castilian", "nativeName" => "español, castellano"],
    ["code" => "su", "name" => "Sundanese", "nativeName" => "Basa Sunda"],
    ["code" => "sw", "name" => "Swahili", "nativeName" => "Kiswahili"],
    ["code" => "ss", "name" => "Swati", "nativeName" => "SiSwati"],
    ["code" => "sv", "name" => "Swedish", "nativeName" => "svenska"],
    ["code" => "ta", "name" => "Tamil", "nativeName" => "தமிழ்"],
    ["code" => "te", "name" => "Telugu", "nativeName" => "తెలుగు"],
    ["code" => "tg", "name" => "Tajik", "nativeName" => "тоҷикӣ, toğikī, تاجیکی‎"],
    ["code" => "th", "name" => "Thai", "nativeName" => "ไทย"],
    ["code" => "ti", "name" => "Tigrinya", "nativeName" => "ትግርኛ"],
    ["code" => "bo", "name" => "Tibetan Standard, Tibetan, Central", "nativeName" => "བོད་ཡིག"],
    ["code" => "tk", "name" => "Turkmen", "nativeName" => "Türkmen, Түркмен"],
    ["code" => "tl", "name" => "Tagalog", "nativeName" => "Wikang Tagalog, ᜏᜒᜃᜅ᜔ ᜆᜄᜎᜓᜄ᜔"],
    ["code" => "tn", "name" => "Tswana", "nativeName" => "Setswana"],
    ["code" => "to", "name" => "Tonga (Tonga Islands)", "nativeName" => "faka Tonga"],
    ["code" => "tr", "name" => "Turkish", "nativeName" => "Türkçe"],
    ["code" => "ts", "name" => "Tsonga", "nativeName" => "Xitsonga"],
    ["code" => "tt", "name" => "Tatar", "nativeName" => "татарча, tatarça, تاتارچا‎"],
    ["code" => "tw", "name" => "Twi", "nativeName" => "Twi"],
    ["code" => "ty", "name" => "Tahitian", "nativeName" => "Reo Tahiti"],
    ["code" => "ug", "name" => "Uighur, Uyghur", "nativeName" => "Uyƣurqə, ئۇيغۇرچە‎"],
    ["code" => "uk", "name" => "Ukrainian", "nativeName" => "українська"],
    ["code" => "ur", "name" => "Urdu", "nativeName" => "اردو"],
    ["code" => "uz", "name" => "Uzbek", "nativeName" => "zbek, Ўзбек, أۇزبېك‎"],
    ["code" => "ve", "name" => "Venda", "nativeName" => "Tshivenḓa"],
    ["code" => "vi", "name" => "Vietnamese", "nativeName" => "Tiếng Việt"],
    ["code" => "vo", "name" => "Volapük", "nativeName" => "Volapük"],
    ["code" => "wa", "name" => "Walloon", "nativeName" => "Walon"],
    ["code" => "cy", "name" => "Welsh", "nativeName" => "Cymraeg"],
    ["code" => "wo", "name" => "Wolof", "nativeName" => "Wollof"],
    ["code" => "fy", "name" => "Western Frisian", "nativeName" => "Frysk"],
    ["code" => "xh", "name" => "Xhosa", "nativeName" => "isiXhosa"],
    ["code" => "yi", "name" => "Yiddish", "nativeName" => "ייִדיש"],
    ["code" => "yo", "name" => "Yoruba", "nativeName" => "Yorùbá"],
    ["code" => "za", "name" => "Zhuang, Chuang", "nativeName" => "Saɯ cueŋƅ, Saw cuengh"]
];

//system Modules
const SYSTEM_MODULES = [
    ['key' => 'dashboard', 'value' => 'Dashboard'],
    ['key' => 'service_management', 'value' => 'Service Management'],
    ['key' => 'customer_management', 'value' => 'Customer Management'],
    ['key' => 'employee_management', 'value' => 'Employee Management'],
    ['key' => 'promotion_management', 'value' => 'Promotion Management'],
    ['key' => 'booking_management', 'value' => 'Booking Management'],
    ['key' => 'provider_management', 'value' => 'Provider Management'],
    ['key' => 'system_management', 'value' => 'System Management'],
    ['key' => 'transaction_management', 'value' => 'Transaction Management'],
    ['key' => 'report_management', 'value' => 'Report Management']
];

//file type
const FILE_TYPE = [
    ['key' => 'png', 'value' => 'png'],
    ['key' => 'jpg', 'value' => 'jpg'],
    ['key' => 'jpeg', 'value' => 'jpeg'],
    ['key' => 'csv', 'value' => 'csv'],
    ['key' => 'txt', 'value' => 'txt'],
    ['key' => 'xlx', 'value' => 'xlx'],
    ['key' => 'xls', 'value' => 'xls'],
    ['key' => 'pdf', 'value' => 'pdf'],
];

//User accounts
const ACCOUNT_STATES = [
    ['key' => 'balance_pending', 'value' => 'balance_pending'],         // 0
    ['key' => 'received_balance', 'value' => 'received_balance'],       // 1
    ['key' => 'account_payable', 'value' => 'account_payable'],         // 2
    ['key' => 'account_receivable', 'value' => 'account_receivable'],   // 3
    ['key' => 'total_withdrawn', 'value' => 'total_withdrawn']        // 4
];

const TRX_TYPE = [
    'booking_amount' => 'booking_amount',
    'pending_amount' => 'pending_amount',
    'receivable_amount' => 'receivable_amount',
    'received_commission' => 'received_commission',
    'received_extra_fee' => 'received_extra_fee',
    'payable_amount' => 'payable_amount',
    'received_amount' => 'received_amount',
    'payable_commission' => 'payable_commission',
    'payable_extra_fee' => 'payable_extra_fee',
    'receivable_commission' => 'receivable_commission',
    'receivable_extra_fee' => 'receivable_extra_fee',
    'paid_commission' => 'paid_commission',
    'withdrawable_amount' => 'withdrawable_amount',
    'paid_amount' => 'paid_amount',
    'fund_by_admin' => 'fund_by_admin',
    'loyalty_point_earning' => 'loyalty_point_earning',
    'referral_earning' => 'referral_earning',
    'booking_placed_by_customer_wallet' => 'booking_placed_by_customer_wallet',
    'add_fund' => 'add_fund',
    'add_fund_bonus' => 'add_fund_bonus',
    'booking_refund' => 'booking_refund',
];

const WALLET_TRX_TYPE = [
    'fund_by_admin' => 'fund_by_admin',
    'loyalty_point_earning' => 'loyalty_point_earning',
    'referral_earning' => 'referral_earning',
//    'booking_placed_by_customer_wallet' => 'booking_placed_by_customer_wallet',
    'wallet_payment' => 'wallet_payment',
    'booking_refund' => 'booking_refund',
    'add_fund' => 'add_fund',
    'add_fund_bonus' => 'add_fund_bonus'
];



//transaction type
const TRANSACTION_TYPE = [
    ['key' => 'collect_cash', 'value' => 'collect_cash'],
    ['key' => 'withdraw', 'value' => 'withdraw'],
    ['key' => 'payment', 'value' => 'payment'],
    ['key' => 'commission', 'value' => 'commission'],
];

//time zones

const TIME_ZONES = [
    [
        "label" => "Pacific/Midway (GMT-11:00)",
        "tzCode" => "Pacific/Midway",
        "name" => "(GMT-11:00) Midway",
        "utc" => "-11:00"
    ],
    [
        "label" => "Pacific/Niue (GMT-11:00)",
        "tzCode" => "Pacific/Niue",
        "name" => "(GMT-11:00) Alofi",
        "utc" => "-11:00"
    ],
    [
        "label" => "Pacific/Pago_Pago (GMT-11:00)",
        "tzCode" => "Pacific/Pago_Pago",
        "name" => "(GMT-11:00) Pago Pago, Tāfuna, Ta`ū, Taulaga",
        "utc" => "-11:00"
    ],
    [
        "label" => "America/Adak (GMT-10:00)",
        "tzCode" => "America/Adak",
        "name" => "(GMT-10:00) Adak",
        "utc" => "-10:00"
    ],
    [
        "label" => "Pacific/Honolulu (GMT-10:00)",
        "tzCode" => "Pacific/Honolulu",
        "name" => "(GMT-10:00) Honolulu, East Honolulu, Pearl City, Hilo, Kailua",
        "utc" => "-10:00"
    ],
    [
        "label" => "Pacific/Rarotonga (GMT-10:00)",
        "tzCode" => "Pacific/Rarotonga",
        "name" => "(GMT-10:00) Avarua",
        "utc" => "-10:00"
    ],
    [
        "label" => "Pacific/Tahiti (GMT-10:00)",
        "tzCode" => "Pacific/Tahiti",
        "name" => "(GMT-10:00) Faaa, Papeete, Punaauia, Pirae, Mahina",
        "utc" => "-10:00"
    ],
    [
        "label" => "Pacific/Marquesas (GMT-09:30)",
        "tzCode" => "Pacific/Marquesas",
        "name" => "(GMT-09:30) Taiohae",
        "utc" => "-09:30"
    ],
    [
        "label" => "America/Anchorage (GMT-09:00)",
        "tzCode" => "America/Anchorage",
        "name" => "(GMT-09:00) Anchorage, Fairbanks, Eagle River, Badger, Knik-Fairview",
        "utc" => "-09:00"
    ],
    [
        "label" => "America/Juneau (GMT-09:00)",
        "tzCode" => "America/Juneau",
        "name" => "(GMT-09:00) Juneau",
        "utc" => "-09:00"
    ],
    [
        "label" => "America/Metlakatla (GMT-09:00)",
        "tzCode" => "America/Metlakatla",
        "name" => "(GMT-09:00) Metlakatla",
        "utc" => "-09:00"
    ],
    [
        "label" => "America/Nome (GMT-09:00)",
        "tzCode" => "America/Nome",
        "name" => "(GMT-09:00) Nome",
        "utc" => "-09:00"
    ],
    [
        "label" => "America/Sitka (GMT-09:00)",
        "tzCode" => "America/Sitka",
        "name" => "(GMT-09:00) Sitka, Ketchikan",
        "utc" => "-09:00"
    ],
    [
        "label" => "America/Yakutat (GMT-09:00)",
        "tzCode" => "America/Yakutat",
        "name" => "(GMT-09:00) Yakutat",
        "utc" => "-09:00"
    ],
    [
        "label" => "Pacific/Gambier (GMT-09:00)",
        "tzCode" => "Pacific/Gambier",
        "name" => "(GMT-09:00) Gambier",
        "utc" => "-09:00"
    ],
    [
        "label" => "America/Los_Angeles (GMT-08:00)",
        "tzCode" => "America/Los_Angeles",
        "name" => "(GMT-08:00) Los Angeles, San Diego, San Jose, San Francisco, Seattle",
        "utc" => "-08:00"
    ],
    [
        "label" => "America/Tijuana (GMT-08:00)",
        "tzCode" => "America/Tijuana",
        "name" => "(GMT-08:00) Tijuana, Mexicali, Ensenada, Rosarito, Tecate",
        "utc" => "-08:00"
    ],
    [
        "label" => "America/Vancouver (GMT-08:00)",
        "tzCode" => "America/Vancouver",
        "name" => "(GMT-08:00) Vancouver, Surrey, Okanagan, Victoria, Burnaby",
        "utc" => "-08:00"
    ],
    [
        "label" => "Pacific/Pitcairn (GMT-08:00)",
        "tzCode" => "Pacific/Pitcairn",
        "name" => "(GMT-08:00) Adamstown",
        "utc" => "-08:00"
    ],
    [
        "label" => "America/Boise (GMT-07:00)",
        "tzCode" => "America/Boise",
        "name" => "(GMT-07:00) Boise, Meridian, Nampa, Idaho Falls, Pocatello",
        "utc" => "-07:00"
    ],
    [
        "label" => "America/Cambridge_Bay (GMT-07:00)",
        "tzCode" => "America/Cambridge_Bay",
        "name" => "(GMT-07:00) Cambridge Bay",
        "utc" => "-07:00"
    ],
    [
        "label" => "America/Chihuahua (GMT-07:00)",
        "tzCode" => "America/Chihuahua",
        "name" => "(GMT-07:00) Chihuahua, Ciudad Delicias, Cuauhtémoc, Parral, Nuevo Casas Grandes",
        "utc" => "-07:00"
    ],
    [
        "label" => "America/Creston (GMT-07:00)",
        "tzCode" => "America/Creston",
        "name" => "(GMT-07:00) Creston",
        "utc" => "-07:00"
    ],
    [
        "label" => "America/Dawson (GMT-07:00)",
        "tzCode" => "America/Dawson",
        "name" => "(GMT-07:00) Dawson",
        "utc" => "-07:00"
    ],
    [
        "label" => "America/Dawson_Creek (GMT-07:00)",
        "tzCode" => "America/Dawson_Creek",
        "name" => "(GMT-07:00) Fort St. John, Dawson Creek",
        "utc" => "-07:00"
    ],
    [
        "label" => "America/Denver (GMT-07:00)",
        "tzCode" => "America/Denver",
        "name" => "(GMT-07:00) Denver, El Paso, Albuquerque, Colorado Springs, Aurora",
        "utc" => "-07:00"
    ],
    [
        "label" => "America/Edmonton (GMT-07:00)",
        "tzCode" => "America/Edmonton",
        "name" => "(GMT-07:00) Calgary, Edmonton, Fort McMurray, Red Deer, Lethbridge",
        "utc" => "-07:00"
    ],
    [
        "label" => "America/Fort_Nelson (GMT-07:00)",
        "tzCode" => "America/Fort_Nelson",
        "name" => "(GMT-07:00) Fort Nelson",
        "utc" => "-07:00"
    ],
    [
        "label" => "America/Hermosillo (GMT-07:00)",
        "tzCode" => "America/Hermosillo",
        "name" => "(GMT-07:00) Hermosillo, Ciudad Obregón, Nogales, San Luis Río Colorado, Navojoa",
        "utc" => "-07:00"
    ],
    [
        "label" => "America/Inuvik (GMT-07:00)",
        "tzCode" => "America/Inuvik",
        "name" => "(GMT-07:00) Inuvik",
        "utc" => "-07:00"
    ],
    [
        "label" => "America/Mazatlan (GMT-07:00)",
        "tzCode" => "America/Mazatlan",
        "name" => "(GMT-07:00) Culiacán, Mazatlán, Tepic, Los Mochis, La Paz",
        "utc" => "-07:00"
    ],
    [
        "label" => "America/Ojinaga (GMT-07:00)",
        "tzCode" => "America/Ojinaga",
        "name" => "(GMT-07:00) Ciudad Juárez, Manuel Ojinaga, Ojinaga",
        "utc" => "-07:00"
    ],
    [
        "label" => "America/Phoenix (GMT-07:00)",
        "tzCode" => "America/Phoenix",
        "name" => "(GMT-07:00) Phoenix, Tucson, Mesa, Chandler, Gilbert",
        "utc" => "-07:00"
    ],
    [
        "label" => "America/Whitehorse (GMT-07:00)",
        "tzCode" => "America/Whitehorse",
        "name" => "(GMT-07:00) Whitehorse",
        "utc" => "-07:00"
    ],
    [
        "label" => "America/Yellowknife (GMT-07:00)",
        "tzCode" => "America/Yellowknife",
        "name" => "(GMT-07:00) Yellowknife",
        "utc" => "-07:00"
    ],
    [
        "label" => "America/Bahia_Banderas (GMT-06:00)",
        "tzCode" => "America/Bahia_Banderas",
        "name" => "(GMT-06:00) Mezcales, San Vicente, Bucerías, Valle de Banderas",
        "utc" => "-06:00"
    ],
    [
        "label" => "America/Belize (GMT-06:00)",
        "tzCode" => "America/Belize",
        "name" => "(GMT-06:00) Belize City, San Ignacio, Orange Walk, Belmopan, Dangriga",
        "utc" => "-06:00"
    ],
    [
        "label" => "America/Chicago (GMT-06:00)",
        "tzCode" => "America/Chicago",
        "name" => "(GMT-06:00) Chicago, Houston, San Antonio, Dallas, Austin",
        "utc" => "-06:00"
    ],
    [
        "label" => "America/Costa_Rica (GMT-06:00)",
        "tzCode" => "America/Costa_Rica",
        "name" => "(GMT-06:00) San José, Limón, San Francisco, Alajuela, Liberia",
        "utc" => "-06:00"
    ],
    [
        "label" => "America/El_Salvador (GMT-06:00)",
        "tzCode" => "America/El_Salvador",
        "name" => "(GMT-06:00) San Salvador, Soyapango, Santa Ana, San Miguel, Mejicanos",
        "utc" => "-06:00"
    ],
    [
        "label" => "America/Guatemala (GMT-06:00)",
        "tzCode" => "America/Guatemala",
        "name" => "(GMT-06:00) Guatemala City, Mixco, Villa Nueva, Petapa, San Juan Sacatepéquez",
        "utc" => "-06:00"
    ],
    [
        "label" => "America/Indiana/Knox (GMT-06:00)",
        "tzCode" => "America/Indiana/Knox",
        "name" => "(GMT-06:00) Knox",
        "utc" => "-06:00"
    ],
    [
        "label" => "America/Indiana/Tell_City (GMT-06:00)",
        "tzCode" => "America/Indiana/Tell_City",
        "name" => "(GMT-06:00) Tell City",
        "utc" => "-06:00"
    ],
    [
        "label" => "America/Managua (GMT-06:00)",
        "tzCode" => "America/Managua",
        "name" => "(GMT-06:00) Managua, León, Masaya, Chinandega, Matagalpa",
        "utc" => "-06:00"
    ],
    [
        "label" => "America/Matamoros (GMT-06:00)",
        "tzCode" => "America/Matamoros",
        "name" => "(GMT-06:00) Reynosa, Heroica Matamoros, Nuevo Laredo, Piedras Negras, Ciudad Acuña",
        "utc" => "-06:00"
    ],
    [
        "label" => "America/Menominee (GMT-06:00)",
        "tzCode" => "America/Menominee",
        "name" => "(GMT-06:00) Menominee, Iron Mountain, Kingsford, Ironwood, Iron River",
        "utc" => "-06:00"
    ],
    [
        "label" => "America/Merida (GMT-06:00)",
        "tzCode" => "America/Merida",
        "name" => "(GMT-06:00) Mérida, Campeche, Ciudad del Carmen, Kanasín, Valladolid",
        "utc" => "-06:00"
    ],
    [
        "label" => "America/Mexico_City (GMT-06:00)",
        "tzCode" => "America/Mexico_City",
        "name" => "(GMT-06:00) Mexico City, Iztapalapa, Ecatepec de Morelos, Guadalajara, Puebla",
        "utc" => "-06:00"
    ],
    [
        "label" => "America/Monterrey (GMT-06:00)",
        "tzCode" => "America/Monterrey",
        "name" => "(GMT-06:00) Monterrey, Saltillo, Guadalupe, Torreón, Victoria de Durango",
        "utc" => "-06:00"
    ],
    [
        "label" => "America/North_Dakota/Beulah (GMT-06:00)",
        "tzCode" => "America/North_Dakota/Beulah",
        "name" => "(GMT-06:00) Beulah",
        "utc" => "-06:00"
    ],
    [
        "label" => "America/North_Dakota/Center (GMT-06:00)",
        "tzCode" => "America/North_Dakota/Center",
        "name" => "(GMT-06:00) Center",
        "utc" => "-06:00"
    ],
    [
        "label" => "America/North_Dakota/New_Salem (GMT-06:00)",
        "tzCode" => "America/North_Dakota/New_Salem",
        "name" => "(GMT-06:00) Mandan",
        "utc" => "-06:00"
    ],
    [
        "label" => "America/Rainy_River (GMT-06:00)",
        "tzCode" => "America/Rainy_River",
        "name" => "(GMT-06:00) Rainy River",
        "utc" => "-06:00"
    ],
    [
        "label" => "America/Rankin_Inlet (GMT-06:00)",
        "tzCode" => "America/Rankin_Inlet",
        "name" => "(GMT-06:00) Rankin Inlet",
        "utc" => "-06:00"
    ],
    [
        "label" => "America/Regina (GMT-06:00)",
        "tzCode" => "America/Regina",
        "name" => "(GMT-06:00) Saskatoon, Regina, Prince Albert, Moose Jaw, North Battleford",
        "utc" => "-06:00"
    ],
    [
        "label" => "America/Resolute (GMT-06:00)",
        "tzCode" => "America/Resolute",
        "name" => "(GMT-06:00) Resolute",
        "utc" => "-06:00"
    ],
    [
        "label" => "America/Swift_Current (GMT-06:00)",
        "tzCode" => "America/Swift_Current",
        "name" => "(GMT-06:00) Swift Current",
        "utc" => "-06:00"
    ],
    [
        "label" => "America/Tegucigalpa (GMT-06:00)",
        "tzCode" => "America/Tegucigalpa",
        "name" => "(GMT-06:00) Tegucigalpa, San Pedro Sula, Choloma, La Ceiba, El Progreso",
        "utc" => "-06:00"
    ],
    [
        "label" => "America/Winnipeg (GMT-06:00)",
        "tzCode" => "America/Winnipeg",
        "name" => "(GMT-06:00) Winnipeg, Brandon, Kenora, Portage la Prairie, Thompson",
        "utc" => "-06:00"
    ],
    [
        "label" => "Pacific/Easter (GMT-06:00)",
        "tzCode" => "Pacific/Easter",
        "name" => "(GMT-06:00) Easter",
        "utc" => "-06:00"
    ],
    [
        "label" => "Pacific/Galapagos (GMT-06:00)",
        "tzCode" => "Pacific/Galapagos",
        "name" => "(GMT-06:00) Puerto Ayora, Puerto Baquerizo Moreno",
        "utc" => "-06:00"
    ],
    [
        "label" => "America/Atikokan (GMT-05:00)",
        "tzCode" => "America/Atikokan",
        "name" => "(GMT-05:00) Atikokan",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Bogota (GMT-05:00)",
        "tzCode" => "America/Bogota",
        "name" => "(GMT-05:00) Bogotá, Cali, Medellín, Barranquilla, Cartagena",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Cancun (GMT-05:00)",
        "tzCode" => "America/Cancun",
        "name" => "(GMT-05:00) Cancún, Chetumal, Playa del Carmen, Cozumel, Felipe Carrillo Puerto",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Cayman (GMT-05:00)",
        "tzCode" => "America/Cayman",
        "name" => "(GMT-05:00) George Town, West Bay, Bodden Town, East End, North Side",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Detroit (GMT-05:00)",
        "tzCode" => "America/Detroit",
        "name" => "(GMT-05:00) Detroit, Grand Rapids, Warren, Sterling Heights, Ann Arbor",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Eirunepe (GMT-05:00)",
        "tzCode" => "America/Eirunepe",
        "name" => "(GMT-05:00) Eirunepé, Benjamin Constant, Envira",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Grand_Turk (GMT-05:00)",
        "tzCode" => "America/Grand_Turk",
        "name" => "(GMT-05:00) Cockburn Town",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Guayaquil (GMT-05:00)",
        "tzCode" => "America/Guayaquil",
        "name" => "(GMT-05:00) Guayaquil, Quito, Cuenca, Santo Domingo de los Colorados, Machala",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Havana (GMT-05:00)",
        "tzCode" => "America/Havana",
        "name" => "(GMT-05:00) Havana, Santiago de Cuba, Camagüey, Holguín, Guantánamo",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Indiana/Indianapolis (GMT-05:00)",
        "tzCode" => "America/Indiana/Indianapolis",
        "name" => "(GMT-05:00) Indianapolis, Fort Wayne, South Bend, Carmel, Bloomington",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Indiana/Marengo (GMT-05:00)",
        "tzCode" => "America/Indiana/Marengo",
        "name" => "(GMT-05:00) Marengo",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Indiana/Petersburg (GMT-05:00)",
        "tzCode" => "America/Indiana/Petersburg",
        "name" => "(GMT-05:00) Petersburg",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Indiana/Vevay (GMT-05:00)",
        "tzCode" => "America/Indiana/Vevay",
        "name" => "(GMT-05:00) Vevay",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Indiana/Vincennes (GMT-05:00)",
        "tzCode" => "America/Indiana/Vincennes",
        "name" => "(GMT-05:00) Vincennes, Jasper, Washington, Huntingburg",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Indiana/Winamac (GMT-05:00)",
        "tzCode" => "America/Indiana/Winamac",
        "name" => "(GMT-05:00) Winamac",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Iqaluit (GMT-05:00)",
        "tzCode" => "America/Iqaluit",
        "name" => "(GMT-05:00) Iqaluit",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Jamaica (GMT-05:00)",
        "tzCode" => "America/Jamaica",
        "name" => "(GMT-05:00) Kingston, New Kingston, Spanish Town, Portmore, Montego Bay",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Kentucky/Louisville (GMT-05:00)",
        "tzCode" => "America/Kentucky/Louisville",
        "name" => "(GMT-05:00) Louisville, Jeffersonville, New Albany, Jeffersontown, Pleasure Ridge Park",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Kentucky/Monticello (GMT-05:00)",
        "tzCode" => "America/Kentucky/Monticello",
        "name" => "(GMT-05:00) Monticello",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Lima (GMT-05:00)",
        "tzCode" => "America/Lima",
        "name" => "(GMT-05:00) Lima, Arequipa, Callao, Trujillo, Chiclayo",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Nassau (GMT-05:00)",
        "tzCode" => "America/Nassau",
        "name" => "(GMT-05:00) Nassau, Lucaya, Freeport, West End, Cooper’s Town",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/New_York (GMT-05:00)",
        "tzCode" => "America/New_York",
        "name" => "(GMT-05:00) New York City, Brooklyn, Queens, Philadelphia, Manhattan",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Nipigon (GMT-05:00)",
        "tzCode" => "America/Nipigon",
        "name" => "(GMT-05:00) Nipigon",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Panama (GMT-05:00)",
        "tzCode" => "America/Panama",
        "name" => "(GMT-05:00) Panamá, San Miguelito, Juan Díaz, David, Arraiján",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Pangnirtung (GMT-05:00)",
        "tzCode" => "America/Pangnirtung",
        "name" => "(GMT-05:00) Pangnirtung",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Port-au-Prince (GMT-05:00)",
        "tzCode" => "America/Port-au-Prince",
        "name" => "(GMT-05:00) Port-au-Prince, Carrefour, Delmas 73, Pétionville, Port-de-Paix",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Rio_Branco (GMT-05:00)",
        "tzCode" => "America/Rio_Branco",
        "name" => "(GMT-05:00) Rio Branco, Cruzeiro do Sul, Sena Madureira, Tarauacá, Feijó",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Thunder_Bay (GMT-05:00)",
        "tzCode" => "America/Thunder_Bay",
        "name" => "(GMT-05:00) Thunder Bay",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/Toronto (GMT-05:00)",
        "tzCode" => "America/Toronto",
        "name" => "(GMT-05:00) Toronto, Montréal, Ottawa, Mississauga, Québec",
        "utc" => "-05:00"
    ],
    [
        "label" => "America/AnguillaSandy Hill (GMT-04:00)",
        "tzCode" => "America/AnguillaSandy Hill",
        "name" => "(GMT-04:00) The Valley, Blowing Point Village, Sandy Ground Village, The Quarter, Sandy Hill",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Antigua (GMT-04:00)",
        "tzCode" => "America/Antigua",
        "name" => "(GMT-04:00) Saint John’s, Piggotts, Bolands, Codrington, Parham",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Aruba (GMT-04:00)",
        "tzCode" => "America/Aruba",
        "name" => "(GMT-04:00) Oranjestad, Tanki Leendert, San Nicolas, Santa Cruz, Paradera",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Asuncion (GMT-04:00)",
        "tzCode" => "America/Asuncion",
        "name" => "(GMT-04:00) Asunción, Ciudad del Este, San Lorenzo, Capiatá, Lambaré",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Barbados (GMT-04:00)",
        "tzCode" => "America/Barbados",
        "name" => "(GMT-04:00) Bridgetown, Speightstown, Oistins, Bathsheba, Holetown",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Blanc-Sablon (GMT-04:00)",
        "tzCode" => "America/Blanc-Sablon",
        "name" => "(GMT-04:00) Lévis",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Boa_Vista (GMT-04:00)",
        "tzCode" => "America/Boa_Vista",
        "name" => "(GMT-04:00) Boa Vista",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Campo_Grande (GMT-04:00)",
        "tzCode" => "America/Campo_Grande",
        "name" => "(GMT-04:00) Campo Grande, Dourados, Corumbá, Três Lagoas, Ponta Porã",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Caracas (GMT-04:00)",
        "tzCode" => "America/Caracas",
        "name" => "(GMT-04:00) Caracas, Maracaibo, Maracay, Valencia, Barquisimeto",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Cuiaba (GMT-04:00)",
        "tzCode" => "America/Cuiaba",
        "name" => "(GMT-04:00) Cuiabá, Várzea Grande, Rondonópolis, Sinop, Barra do Garças",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Curacao (GMT-04:00)",
        "tzCode" => "America/Curacao",
        "name" => "(GMT-04:00) Willemstad, Sint Michiel Liber",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Dominica (GMT-04:00)",
        "tzCode" => "America/Dominica",
        "name" => "(GMT-04:00) Roseau, Portsmouth, Berekua, Saint Joseph, Wesley",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Glace_Bay (GMT-04:00)",
        "tzCode" => "America/Glace_Bay",
        "name" => "(GMT-04:00) Sydney, Glace Bay, Sydney Mines",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Goose_Bay (GMT-04:00)",
        "tzCode" => "America/Goose_Bay",
        "name" => "(GMT-04:00) Labrador City, Happy Valley-Goose Bay",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Grenada (GMT-04:00)",
        "tzCode" => "America/Grenada",
        "name" => "(GMT-04:00) Saint George's, Gouyave, Grenville, Victoria, Saint David’s",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Guadeloupe (GMT-04:00)",
        "tzCode" => "America/Guadeloupe",
        "name" => "(GMT-04:00) Les Abymes, Baie-Mahault, Le Gosier, Petit-Bourg, Sainte-Anne",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Guyana (GMT-04:00)",
        "tzCode" => "America/Guyana",
        "name" => "(GMT-04:00) Georgetown, Linden, New Amsterdam, Anna Regina, Bartica",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Halifax (GMT-04:00)",
        "tzCode" => "America/Halifax",
        "name" => "(GMT-04:00) Halifax, Dartmouth, Charlottetown, Lower Sackville, Truro",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Kralendijk (GMT-04:00)",
        "tzCode" => "America/Kralendijk",
        "name" => "(GMT-04:00) Kralendijk, Oranjestad, The Bottom",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/La_Paz (GMT-04:00)",
        "tzCode" => "America/La_Paz",
        "name" => "(GMT-04:00) Santa Cruz de la Sierra, Cochabamba, La Paz, Sucre, Oruro",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Lower_Princes (GMT-04:00)",
        "tzCode" => "America/Lower_Princes",
        "name" => "(GMT-04:00) Cul de Sac, Lower Prince’s Quarter, Koolbaai, Philipsburg",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Manaus (GMT-04:00)",
        "tzCode" => "America/Manaus",
        "name" => "(GMT-04:00) Manaus, Itacoatiara, Parintins, Manacapuru, Coari",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Marigot (GMT-04:00)",
        "tzCode" => "America/Marigot",
        "name" => "(GMT-04:00) Marigot",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Martinique (GMT-04:00)",
        "tzCode" => "America/Martinique",
        "name" => "(GMT-04:00) Fort-de-France, Le Lamentin, Le Robert, Sainte-Marie, Le François",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Moncton (GMT-04:00)",
        "tzCode" => "America/Moncton",
        "name" => "(GMT-04:00) Moncton, Saint John, Fredericton, Dieppe, Miramichi",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Montserrat (GMT-04:00)",
        "tzCode" => "America/Montserrat",
        "name" => "(GMT-04:00) Brades, Saint Peters, Plymouth",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Porto_Velho (GMT-04:00)",
        "tzCode" => "America/Porto_Velho",
        "name" => "(GMT-04:00) Porto Velho, Ji Paraná, Vilhena, Ariquemes, Cacoal",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Port_of_Spain (GMT-04:00)",
        "tzCode" => "America/Port_of_Spain",
        "name" => "(GMT-04:00) Chaguanas, Mon Repos, San Fernando, Port of Spain, Rio Claro",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Puerto_Rico (GMT-04:00)",
        "tzCode" => "America/Puerto_Rico",
        "name" => "(GMT-04:00) San Juan, Bayamón, Carolina, Ponce, Caguas",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Santiago (GMT-04:00)",
        "tzCode" => "America/Santiago",
        "name" => "(GMT-04:00) Santiago, Puente Alto, Antofagasta, Viña del Mar, Valparaíso",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Santo_Domingo (GMT-04:00)",
        "tzCode" => "America/Santo_Domingo",
        "name" => "(GMT-04:00) Santo Domingo, Santiago de los Caballeros, Santo Domingo Oeste, Santo Domingo Este, San Pedro de Macorís",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/St_Barthelemy (GMT-04:00)",
        "tzCode" => "America/St_Barthelemy",
        "name" => "(GMT-04:00) Gustavia",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/St_Kitts (GMT-04:00)",
        "tzCode" => "America/St_Kitts",
        "name" => "(GMT-04:00) Basseterre, Fig Tree, Market Shop, Saint Paul’s, Middle Island",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/St_Lucia (GMT-04:00)",
        "tzCode" => "America/St_Lucia",
        "name" => "(GMT-04:00) Castries, Bisee, Vieux Fort, Micoud, Soufrière",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/St_Thomas (GMT-04:00)",
        "tzCode" => "America/St_Thomas",
        "name" => "(GMT-04:00) Saint Croix, Charlotte Amalie, Cruz Bay",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/St_Vincent (GMT-04:00)",
        "tzCode" => "America/St_Vincent",
        "name" => "(GMT-04:00) Kingstown, Kingstown Park, Georgetown, Barrouallie, Port Elizabeth",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Thule (GMT-04:00)",
        "tzCode" => "America/Thule",
        "name" => "(GMT-04:00) Thule",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/Tortola (GMT-04:00)",
        "tzCode" => "America/Tortola",
        "name" => "(GMT-04:00) Road Town",
        "utc" => "-04:00"
    ],
    [
        "label" => "Atlantic/Bermuda (GMT-04:00)",
        "tzCode" => "Atlantic/Bermuda",
        "name" => "(GMT-04:00) Hamilton",
        "utc" => "-04:00"
    ],
    [
        "label" => "America/St_Johns (GMT-03:30)",
        "tzCode" => "America/St_Johns",
        "name" => "(GMT-03:30) St. John's, Mount Pearl, Corner Brook, Conception Bay South, Bay Roberts",
        "utc" => "-03:30"
    ],
    [
        "label" => "America/Araguaina (GMT-03:00)",
        "tzCode" => "America/Araguaina",
        "name" => "(GMT-03:00) Palmas, Araguaína, Gurupi, Miracema do Tocantins, Porto Franco",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Argentina/Buenos_Aires (GMT-03:00)",
        "tzCode" => "America/Argentina/Buenos_Aires",
        "name" => "(GMT-03:00) Buenos Aires, La Plata, Mar del Plata, Morón, Bahía Blanca",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Argentina/Catamarca (GMT-03:00)",
        "tzCode" => "America/Argentina/Catamarca",
        "name" => "(GMT-03:00) San Fernando del Valle de Catamarca, Trelew, Puerto Madryn, Esquel, Rawson",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Argentina/Cordoba (GMT-03:00)",
        "tzCode" => "America/Argentina/Cordoba",
        "name" => "(GMT-03:00) Córdoba, Rosario, Santa Fe, Resistencia, Santiago del Estero",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Argentina/Jujuy (GMT-03:00)",
        "tzCode" => "America/Argentina/Jujuy",
        "name" => "(GMT-03:00) San Salvador de Jujuy, San Pedro de Jujuy, Libertador General San Martín, Palpalá, La Quiaca",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Argentina/La_Rioja (GMT-03:00)",
        "tzCode" => "America/Argentina/La_Rioja",
        "name" => "(GMT-03:00) La Rioja, Chilecito, Arauco, Chamical",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Argentina/Mendoza (GMT-03:00)",
        "tzCode" => "America/Argentina/Mendoza",
        "name" => "(GMT-03:00) Mendoza, San Rafael, San Martín",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Argentina/Rio_Gallegos (GMT-03:00)",
        "tzCode" => "America/Argentina/Rio_Gallegos",
        "name" => "(GMT-03:00) Comodoro Rivadavia, Río Gallegos, Caleta Olivia, Pico Truncado, Puerto Deseado",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Argentina/Salta (GMT-03:00)",
        "tzCode" => "America/Argentina/Salta",
        "name" => "(GMT-03:00) Salta, Neuquén, Santa Rosa, San Carlos de Bariloche, Cipolletti",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Argentina/San_Juan (GMT-03:00)",
        "tzCode" => "America/Argentina/San_Juan",
        "name" => "(GMT-03:00) San Juan, Chimbas, Santa Lucía, Pocito, Caucete",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Argentina/San_Luis (GMT-03:00)",
        "tzCode" => "America/Argentina/San_Luis",
        "name" => "(GMT-03:00) San Luis, Villa Mercedes, La Punta, Merlo, Justo Daract",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Argentina/Tucuman (GMT-03:00)",
        "tzCode" => "America/Argentina/Tucuman",
        "name" => "(GMT-03:00) San Miguel de Tucumán, Yerba Buena, Tafí Viejo, Alderetes, Aguilares",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Argentina/Ushuaia (GMT-03:00)",
        "tzCode" => "America/Argentina/Ushuaia",
        "name" => "(GMT-03:00) Ushuaia, Río Grande",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Bahia (GMT-03:00)",
        "tzCode" => "America/Bahia",
        "name" => "(GMT-03:00) Salvador, Feira de Santana, Vitória da Conquista, Itabuna, Camaçari",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Belem (GMT-03:00)",
        "tzCode" => "America/Belem",
        "name" => "(GMT-03:00) Belém, Ananindeua, Macapá, Parauapebas, Marabá",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Cayenne (GMT-03:00)",
        "tzCode" => "America/Cayenne",
        "name" => "(GMT-03:00) Cayenne, Matoury, Saint-Laurent-du-Maroni, Kourou, Rémire-Montjoly",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Fortaleza (GMT-03:00)",
        "tzCode" => "America/Fortaleza",
        "name" => "(GMT-03:00) Fortaleza, São Luís, Natal, Teresina, João Pessoa",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Godthab (GMT-03:00)",
        "tzCode" => "America/Godthab",
        "name" => "(GMT-03:00) Nuuk, Sisimiut, Ilulissat, Qaqortoq, Aasiaat",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Maceio (GMT-03:00)",
        "tzCode" => "America/Maceio",
        "name" => "(GMT-03:00) Maceió, Aracaju, Arapiraca, Nossa Senhora do Socorro, São Cristóvão",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Miquelon (GMT-03:00)",
        "tzCode" => "America/Miquelon",
        "name" => "(GMT-03:00) Saint-Pierre, Miquelon",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Montevideo (GMT-03:00)",
        "tzCode" => "America/Montevideo",
        "name" => "(GMT-03:00) Montevideo, Salto, Paysandú, Las Piedras, Rivera",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Paramaribo (GMT-03:00)",
        "tzCode" => "America/Paramaribo",
        "name" => "(GMT-03:00) Paramaribo, Lelydorp, Brokopondo, Nieuw Nickerie, Moengo",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Punta_Arenas (GMT-03:00)",
        "tzCode" => "America/Punta_Arenas",
        "name" => "(GMT-03:00) Punta Arenas, Puerto Natales",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Recife (GMT-03:00)",
        "tzCode" => "America/Recife",
        "name" => "(GMT-03:00) Recife, Jaboatão, Jaboatão dos Guararapes, Olinda, Paulista",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Santarem (GMT-03:00)",
        "tzCode" => "America/Santarem",
        "name" => "(GMT-03:00) Santarém, Altamira, Itaituba, Oriximiná, Alenquer",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Sao_Paulo (GMT-03:00)",
        "tzCode" => "America/Sao_Paulo",
        "name" => "(GMT-03:00) São Paulo, Rio de Janeiro, Belo Horizonte, Brasília, Curitiba",
        "utc" => "-03:00"
    ],
    [
        "label" => "Antarctica/Palmer (GMT-03:00)",
        "tzCode" => "Antarctica/Palmer",
        "name" => "(GMT-03:00) Palmer",
        "utc" => "-03:00"
    ],
    [
        "label" => "Antarctica/Rothera (GMT-03:00)",
        "tzCode" => "Antarctica/Rothera",
        "name" => "(GMT-03:00) Rothera",
        "utc" => "-03:00"
    ],
    [
        "label" => "Atlantic/Stanley (GMT-03:00)",
        "tzCode" => "Atlantic/Stanley",
        "name" => "(GMT-03:00) Stanley",
        "utc" => "-03:00"
    ],
    [
        "label" => "America/Noronha (GMT-02:00)",
        "tzCode" => "America/Noronha",
        "name" => "(GMT-02:00) Itamaracá",
        "utc" => "-02:00"
    ],
    [
        "label" => "Atlantic/South_Georgia (GMT-02:00)",
        "tzCode" => "Atlantic/South_Georgia",
        "name" => "(GMT-02:00) Grytviken",
        "utc" => "-02:00"
    ],
    [
        "label" => "America/Scoresbysund (GMT-01:00)",
        "tzCode" => "America/Scoresbysund",
        "name" => "(GMT-01:00) Scoresbysund",
        "utc" => "-01:00"
    ],
    [
        "label" => "Atlantic/Azores (GMT-01:00)",
        "tzCode" => "Atlantic/Azores",
        "name" => "(GMT-01:00) Ponta Delgada, Lagoa, Angra do Heroísmo, Rosto de Cão, Rabo de Peixe",
        "utc" => "-01:00"
    ],
    [
        "label" => "Atlantic/Cape_Verde (GMT-01:00)",
        "tzCode" => "Atlantic/Cape_Verde",
        "name" => "(GMT-01:00) Praia, Mindelo, Santa Maria, Cova Figueira, Santa Cruz",
        "utc" => "-01:00"
    ],
    [
        "label" => "Africa/Abidjan (GMT+00:00)",
        "tzCode" => "Africa/Abidjan",
        "name" => "(GMT+00:00) Abidjan, Abobo, Bouaké, Daloa, San-Pédro",
        "utc" => "+00:00"
    ],
    [
        "label" => "Africa/Accra (GMT+00:00)",
        "tzCode" => "Africa/Accra",
        "name" => "(GMT+00:00) Accra, Kumasi, Tamale, Takoradi, Atsiaman",
        "utc" => "+00:00"
    ],
    [
        "label" => "Africa/Bamako (GMT+00:00)",
        "tzCode" => "Africa/Bamako",
        "name" => "(GMT+00:00) Bamako, Sikasso, Mopti, Koutiala, Ségou",
        "utc" => "+00:00"
    ],
    [
        "label" => "Africa/Banjul (GMT+00:00)",
        "tzCode" => "Africa/Banjul",
        "name" => "(GMT+00:00) Serekunda, Brikama, Bakau, Banjul, Farafenni",
        "utc" => "+00:00"
    ],
    [
        "label" => "Africa/Bissau (GMT+00:00)",
        "tzCode" => "Africa/Bissau",
        "name" => "(GMT+00:00) Bissau, Bafatá, Gabú, Bissorã, Bolama",
        "utc" => "+00:00"
    ],
    [
        "label" => "Africa/Casablanca (GMT+00:00)",
        "tzCode" => "Africa/Casablanca",
        "name" => "(GMT+00:00) Casablanca, Rabat, Fès, Sale, Marrakesh",
        "utc" => "+00:00"
    ],
    [
        "label" => "Africa/Conakry (GMT+00:00)",
        "tzCode" => "Africa/Conakry",
        "name" => "(GMT+00:00) Camayenne, Conakry, Nzérékoré, Kindia, Kankan",
        "utc" => "+00:00"
    ],
    [
        "label" => "Africa/Dakar (GMT+00:00)",
        "tzCode" => "Africa/Dakar",
        "name" => "(GMT+00:00) Dakar, Pikine, Touba, Thiès, Thiès Nones",
        "utc" => "+00:00"
    ],
    [
        "label" => "Africa/El_Aaiun (GMT+00:00)",
        "tzCode" => "Africa/El_Aaiun",
        "name" => "(GMT+00:00) Laayoune, Dakhla, Laayoune Plage",
        "utc" => "+00:00"
    ],
    [
        "label" => "Africa/Freetown (GMT+00:00)",
        "tzCode" => "Africa/Freetown",
        "name" => "(GMT+00:00) Freetown, Bo, Kenema, Koidu, Makeni",
        "utc" => "+00:00"
    ],
    [
        "label" => "Africa/Lome (GMT+00:00)",
        "tzCode" => "Africa/Lome",
        "name" => "(GMT+00:00) Lomé, Sokodé, Kara, Atakpamé, Kpalimé",
        "utc" => "+00:00"
    ],
    [
        "label" => "Africa/Monrovia (GMT+00:00)",
        "tzCode" => "Africa/Monrovia",
        "name" => "(GMT+00:00) Monrovia, Gbarnga, Kakata, Bensonville, Harper",
        "utc" => "+00:00"
    ],
    [
        "label" => "Africa/Nouakchott (GMT+00:00)",
        "tzCode" => "Africa/Nouakchott",
        "name" => "(GMT+00:00) Nouakchott, Nouadhibou, Néma, Kaédi, Rosso",
        "utc" => "+00:00"
    ],
    [
        "label" => "Africa/Ouagadougou (GMT+00:00)",
        "tzCode" => "Africa/Ouagadougou",
        "name" => "(GMT+00:00) Ouagadougou, Bobo-Dioulasso, Koudougou, Ouahigouya, Banfora",
        "utc" => "+00:00"
    ],
    [
        "label" => "Africa/Sao_Tome (GMT+00:00)",
        "tzCode" => "Africa/Sao_Tome",
        "name" => "(GMT+00:00) São Tomé, Santo António",
        "utc" => "+00:00"
    ],
    [
        "label" => "America/Danmarkshavn (GMT+00:00)",
        "tzCode" => "America/Danmarkshavn",
        "name" => "(GMT+00:00) Danmarkshavn",
        "utc" => "+00:00"
    ],
    [
        "label" => "Antarctica/Troll (GMT+00:00)",
        "tzCode" => "Antarctica/Troll",
        "name" => "(GMT+00:00) Troll",
        "utc" => "+00:00"
    ],
    [
        "label" => "Atlantic/Canary (GMT+00:00)",
        "tzCode" => "Atlantic/Canary",
        "name" => "(GMT+00:00) Las Palmas de Gran Canaria, Santa Cruz de Tenerife, La Laguna, Telde, Arona",
        "utc" => "+00:00"
    ],
    [
        "label" => "Atlantic/Faroe (GMT+00:00)",
        "tzCode" => "Atlantic/Faroe",
        "name" => "(GMT+00:00) Tórshavn, Klaksvík, Fuglafjørður, Tvøroyri, Miðvágur",
        "utc" => "+00:00"
    ],
    [
        "label" => "Atlantic/Madeira (GMT+00:00)",
        "tzCode" => "Atlantic/Madeira",
        "name" => "(GMT+00:00) Funchal, Câmara de Lobos, São Martinho, Caniço, Machico",
        "utc" => "+00:00"
    ],
    [
        "label" => "Atlantic/Reykjavik (GMT+00:00)",
        "tzCode" => "Atlantic/Reykjavik",
        "name" => "(GMT+00:00) Reykjavík, Kópavogur, Hafnarfjörður, Akureyri, Garðabær",
        "utc" => "+00:00"
    ],
    [
        "label" => "Atlantic/St_Helena (GMT+00:00)",
        "tzCode" => "Atlantic/St_Helena",
        "name" => "(GMT+00:00) Jamestown, Georgetown, Edinburgh of the Seven Seas",
        "utc" => "+00:00"
    ],
    [
        "label" => "Europe/Dublin (GMT+00:00)",
        "tzCode" => "Europe/Dublin",
        "name" => "(GMT+00:00) Dublin, Cork, Luimneach, Gaillimh, Tallaght",
        "utc" => "+00:00"
    ],
    [
        "label" => "Europe/Guernsey (GMT+00:00)",
        "tzCode" => "Europe/Guernsey",
        "name" => "(GMT+00:00) Saint Peter Port, St Martin, Saint Sampson, St Anne, Saint Saviour",
        "utc" => "+00:00"
    ],
    [
        "label" => "Europe/Isle_of_Man (GMT+00:00)",
        "tzCode" => "Europe/Isle_of_Man",
        "name" => "(GMT+00:00) Douglas, Ramsey, Peel, Port Erin, Castletown",
        "utc" => "+00:00"
    ],
    [
        "label" => "Europe/Jersey (GMT+00:00)",
        "tzCode" => "Europe/Jersey",
        "name" => "(GMT+00:00) Saint Helier, Le Hocq",
        "utc" => "+00:00"
    ],
    [
        "label" => "Europe/Lisbon (GMT+00:00)",
        "tzCode" => "Europe/Lisbon",
        "name" => "(GMT+00:00) Lisbon, Porto, Amadora, Braga, Setúbal",
        "utc" => "+00:00"
    ],
    [
        "label" => "Europe/London (GMT+00:00)",
        "tzCode" => "Europe/London",
        "name" => "(GMT+00:00) London, Birmingham, Liverpool, Sheffield, Bristol",
        "utc" => "+00:00"
    ],
    [
        "label" => "Africa/Algiers (GMT+01:00)",
        "tzCode" => "Africa/Algiers",
        "name" => "(GMT+01:00) Algiers, Boumerdas, Oran, Tébessa, Constantine",
        "utc" => "+01:00"
    ],
    [
        "label" => "Africa/Bangui (GMT+01:00)",
        "tzCode" => "Africa/Bangui",
        "name" => "(GMT+01:00) Bangui, Bimbo, Mbaïki, Berbérati, Kaga Bandoro",
        "utc" => "+01:00"
    ],
    [
        "label" => "Africa/Brazzaville (GMT+01:00)",
        "tzCode" => "Africa/Brazzaville",
        "name" => "(GMT+01:00) Brazzaville, Pointe-Noire, Dolisie, Kayes, Owando",
        "utc" => "+01:00"
    ],
    [
        "label" => "Africa/Ceuta (GMT+01:00)",
        "tzCode" => "Africa/Ceuta",
        "name" => "(GMT+01:00) Ceuta, Melilla",
        "utc" => "+01:00"
    ],
    [
        "label" => "Africa/Douala (GMT+01:00)",
        "tzCode" => "Africa/Douala",
        "name" => "(GMT+01:00) Douala, Yaoundé, Garoua, Kousséri, Bamenda",
        "utc" => "+01:00"
    ],
    [
        "label" => "Africa/Kinshasa (GMT+01:00)",
        "tzCode" => "Africa/Kinshasa",
        "name" => "(GMT+01:00) Kinshasa, Masina, Kikwit, Mbandaka, Matadi",
        "utc" => "+01:00"
    ],
    [
        "label" => "Africa/Lagos (GMT+01:00)",
        "tzCode" => "Africa/Lagos",
        "name" => "(GMT+01:00) Lagos, Kano, Ibadan, Kaduna, Port Harcourt",
        "utc" => "+01:00"
    ],
    [
        "label" => "Africa/Libreville (GMT+01:00)",
        "tzCode" => "Africa/Libreville",
        "name" => "(GMT+01:00) Libreville, Port-Gentil, Franceville, Oyem, Moanda",
        "utc" => "+01:00"
    ],
    [
        "label" => "Africa/Luanda (GMT+01:00)",
        "tzCode" => "Africa/Luanda",
        "name" => "(GMT+01:00) Luanda, N’dalatando, Huambo, Lobito, Benguela",
        "utc" => "+01:00"
    ],
    [
        "label" => "Africa/Malabo (GMT+01:00)",
        "tzCode" => "Africa/Malabo",
        "name" => "(GMT+01:00) Bata, Malabo, Ebebiyin, Aconibe, Añisoc",
        "utc" => "+01:00"
    ],
    [
        "label" => "Africa/Ndjamena (GMT+01:00)",
        "tzCode" => "Africa/Ndjamena",
        "name" => "(GMT+01:00) N'Djamena, Moundou, Sarh, Abéché, Kelo",
        "utc" => "+01:00"
    ],
    [
        "label" => "Africa/Niamey (GMT+01:00)",
        "tzCode" => "Africa/Niamey",
        "name" => "(GMT+01:00) Niamey, Zinder, Maradi, Agadez, Alaghsas",
        "utc" => "+01:00"
    ],
    [
        "label" => "Africa/Porto-Novo (GMT+01:00)",
        "tzCode" => "Africa/Porto-Novo",
        "name" => "(GMT+01:00) Cotonou, Abomey-Calavi, Djougou, Porto-Novo, Parakou",
        "utc" => "+01:00"
    ],
    [
        "label" => "Africa/Tunis (GMT+01:00)",
        "tzCode" => "Africa/Tunis",
        "name" => "(GMT+01:00) Tunis, Sfax, Sousse, Kairouan, Bizerte",
        "utc" => "+01:00"
    ],
    [
        "label" => "Africa/Windhoek (GMT+01:00)",
        "tzCode" => "Africa/Windhoek",
        "name" => "(GMT+01:00) Windhoek, Rundu, Walvis Bay, Oshakati, Swakopmund",
        "utc" => "+01:00"
    ],
    [
        "label" => "Arctic/Longyearbyen (GMT+01:00)",
        "tzCode" => "Arctic/Longyearbyen",
        "name" => "(GMT+01:00) Longyearbyen, Olonkinbyen",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Amsterdam (GMT+01:00)",
        "tzCode" => "Europe/Amsterdam",
        "name" => "(GMT+01:00) Amsterdam, Rotterdam, The Hague, Utrecht, Eindhoven",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Andorra (GMT+01:00)",
        "tzCode" => "Europe/Andorra",
        "name" => "(GMT+01:00) Andorra la Vella, les Escaldes, Encamp, Sant Julià de Lòria, la Massana",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Belgrade (GMT+01:00)",
        "tzCode" => "Europe/Belgrade",
        "name" => "(GMT+01:00) Belgrade, Pristina, Niš, Novi Sad, Prizren",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Berlin (GMT+01:00)",
        "tzCode" => "Europe/Berlin",
        "name" => "(GMT+01:00) Berlin, Hamburg, Munich, Köln, Frankfurt am Main",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Bratislava (GMT+01:00)",
        "tzCode" => "Europe/Bratislava",
        "name" => "(GMT+01:00) Bratislava, Košice, Prešov, Nitra, Žilina",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Brussels (GMT+01:00)",
        "tzCode" => "Europe/Brussels",
        "name" => "(GMT+01:00) Brussels, Antwerpen, Gent, Charleroi, Liège",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Budapest (GMT+01:00)",
        "tzCode" => "Europe/Budapest",
        "name" => "(GMT+01:00) Budapest, Debrecen, Miskolc, Szeged, Pécs",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Copenhagen (GMT+01:00)",
        "tzCode" => "Europe/Copenhagen",
        "name" => "(GMT+01:00) Copenhagen, Århus, Odense, Aalborg, Frederiksberg",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Gibraltar (GMT+01:00)",
        "tzCode" => "Europe/Gibraltar",
        "name" => "(GMT+01:00) Gibraltar",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Ljubljana (GMT+01:00)",
        "tzCode" => "Europe/Ljubljana",
        "name" => "(GMT+01:00) Ljubljana, Maribor, Celje, Kranj, Velenje",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Luxembourg (GMT+01:00)",
        "tzCode" => "Europe/Luxembourg",
        "name" => "(GMT+01:00) Luxembourg, Esch-sur-Alzette, Dudelange, Schifflange, Bettembourg",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Madrid (GMT+01:00)",
        "tzCode" => "Europe/Madrid",
        "name" => "(GMT+01:00) Madrid, Barcelona, Valencia, Sevilla, Zaragoza",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Malta (GMT+01:00)",
        "tzCode" => "Europe/Malta",
        "name" => "(GMT+01:00) Birkirkara, Qormi, Mosta, Żabbar, San Pawl il-Baħar",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Monaco (GMT+01:00)",
        "tzCode" => "Europe/Monaco",
        "name" => "(GMT+01:00) Monaco, Monte-Carlo, La Condamine",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Oslo (GMT+01:00)",
        "tzCode" => "Europe/Oslo",
        "name" => "(GMT+01:00) Oslo, Bergen, Trondheim, Stavanger, Drammen",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Paris (GMT+01:00)",
        "tzCode" => "Europe/Paris",
        "name" => "(GMT+01:00) Paris, Marseille, Lyon, Toulouse, Nice",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Podgorica (GMT+01:00)",
        "tzCode" => "Europe/Podgorica",
        "name" => "(GMT+01:00) Podgorica, Nikšić, Herceg Novi, Pljevlja, Budva",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Prague (GMT+01:00)",
        "tzCode" => "Europe/Prague",
        "name" => "(GMT+01:00) Prague, Brno, Ostrava, Pilsen, Olomouc",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Rome (GMT+01:00)",
        "tzCode" => "Europe/Rome",
        "name" => "(GMT+01:00) Rome, Milan, Naples, Turin, Palermo",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/San_Marino (GMT+01:00)",
        "tzCode" => "Europe/San_Marino",
        "name" => "(GMT+01:00) Serravalle, Borgo Maggiore, San Marino, Domagnano, Fiorentino",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Sarajevo (GMT+01:00)",
        "tzCode" => "Europe/Sarajevo",
        "name" => "(GMT+01:00) Sarajevo, Banja Luka, Zenica, Tuzla, Mostar",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Skopje (GMT+01:00)",
        "tzCode" => "Europe/Skopje",
        "name" => "(GMT+01:00) Skopje, Bitola, Kumanovo, Prilep, Tetovo",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Stockholm (GMT+01:00)",
        "tzCode" => "Europe/Stockholm",
        "name" => "(GMT+01:00) Stockholm, Göteborg, Malmö, Uppsala, Sollentuna",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Tirane (GMT+01:00)",
        "tzCode" => "Europe/Tirane",
        "name" => "(GMT+01:00) Tirana, Durrës, Elbasan, Vlorë, Shkodër",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Vaduz (GMT+01:00)",
        "tzCode" => "Europe/Vaduz",
        "name" => "(GMT+01:00) Schaan, Vaduz, Triesen, Balzers, Eschen",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Vatican (GMT+01:00)",
        "tzCode" => "Europe/Vatican",
        "name" => "(GMT+01:00) Vatican City",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Vienna (GMT+01:00)",
        "tzCode" => "Europe/Vienna",
        "name" => "(GMT+01:00) Vienna, Graz, Linz, Favoriten, Donaustadt",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Warsaw (GMT+01:00)",
        "tzCode" => "Europe/Warsaw",
        "name" => "(GMT+01:00) Warsaw, Łódź, Kraków, Wrocław, Poznań",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Zagreb (GMT+01:00)",
        "tzCode" => "Europe/Zagreb",
        "name" => "(GMT+01:00) Zagreb, Split, Rijeka, Osijek, Zadar",
        "utc" => "+01:00"
    ],
    [
        "label" => "Europe/Zurich (GMT+01:00)",
        "tzCode" => "Europe/Zurich",
        "name" => "(GMT+01:00) Zürich, Genève, Basel, Lausanne, Bern",
        "utc" => "+01:00"
    ],
    [
        "label" => "Africa/Blantyre (GMT+02:00)",
        "tzCode" => "Africa/Blantyre",
        "name" => "(GMT+02:00) Lilongwe, Blantyre, Mzuzu, Zomba, Kasungu",
        "utc" => "+02:00"
    ],
    [
        "label" => "Africa/Bujumbura (GMT+02:00)",
        "tzCode" => "Africa/Bujumbura",
        "name" => "(GMT+02:00) Bujumbura, Muyinga, Gitega, Ruyigi, Ngozi",
        "utc" => "+02:00"
    ],
    [
        "label" => "Africa/Cairo (GMT+02:00)",
        "tzCode" => "Africa/Cairo",
        "name" => "(GMT+02:00) Cairo, Alexandria, Giza, Port Said, Suez",
        "utc" => "+02:00"
    ],
    [
        "label" => "Africa/Gaborone (GMT+02:00)",
        "tzCode" => "Africa/Gaborone",
        "name" => "(GMT+02:00) Gaborone, Francistown, Molepolole, Selebi-Phikwe, Maun",
        "utc" => "+02:00"
    ],
    [
        "label" => "Africa/Harare (GMT+02:00)",
        "tzCode" => "Africa/Harare",
        "name" => "(GMT+02:00) Harare, Bulawayo, Chitungwiza, Mutare, Gweru",
        "utc" => "+02:00"
    ],
    [
        "label" => "Africa/Johannesburg (GMT+02:00)",
        "tzCode" => "Africa/Johannesburg",
        "name" => "(GMT+02:00) Cape Town, Durban, Johannesburg, Soweto, Pretoria",
        "utc" => "+02:00"
    ],
    [
        "label" => "Africa/Juba (GMT+02:00)",
        "tzCode" => "Africa/Juba",
        "name" => "(GMT+02:00) Juba, Winejok, Malakal, Wau, Kuacjok",
        "utc" => "+02:00"
    ],
    [
        "label" => "Africa/Khartoum (GMT+02:00)",
        "tzCode" => "Africa/Khartoum",
        "name" => "(GMT+02:00) Khartoum, Omdurman, Nyala, Port Sudan, Kassala",
        "utc" => "+02:00"
    ],
    [
        "label" => "Africa/Kigali (GMT+02:00)",
        "tzCode" => "Africa/Kigali",
        "name" => "(GMT+02:00) Kigali, Butare, Gitarama, Musanze, Gisenyi",
        "utc" => "+02:00"
    ],
    [
        "label" => "Africa/Lubumbashi (GMT+02:00)",
        "tzCode" => "Africa/Lubumbashi",
        "name" => "(GMT+02:00) Lubumbashi, Mbuji-Mayi, Kisangani, Kananga, Likasi",
        "utc" => "+02:00"
    ],
    [
        "label" => "Africa/Lusaka (GMT+02:00)",
        "tzCode" => "Africa/Lusaka",
        "name" => "(GMT+02:00) Lusaka, Kitwe, Ndola, Kabwe, Chingola",
        "utc" => "+02:00"
    ],
    [
        "label" => "Africa/Maputo (GMT+02:00)",
        "tzCode" => "Africa/Maputo",
        "name" => "(GMT+02:00) Maputo, Matola, Beira, Nampula, Chimoio",
        "utc" => "+02:00"
    ],
    [
        "label" => "Africa/Maseru (GMT+02:00)",
        "tzCode" => "Africa/Maseru",
        "name" => "(GMT+02:00) Maseru, Mafeteng, Leribe, Maputsoe, Mohale’s Hoek",
        "utc" => "+02:00"
    ],
    [
        "label" => "Africa/Mbabane (GMT+02:00)",
        "tzCode" => "Africa/Mbabane",
        "name" => "(GMT+02:00) Manzini, Mbabane, Big Bend, Malkerns, Nhlangano",
        "utc" => "+02:00"
    ],
    [
        "label" => "Africa/Tripoli (GMT+02:00)",
        "tzCode" => "Africa/Tripoli",
        "name" => "(GMT+02:00) Tripoli, Benghazi, Mişrātah, Tarhuna, Al Khums",
        "utc" => "+02:00"
    ],
    [
        "label" => "Asia/Amman (GMT+02:00)",
        "tzCode" => "Asia/Amman",
        "name" => "(GMT+02:00) Amman, Zarqa, Irbid, Russeifa, Wādī as Sīr",
        "utc" => "+02:00"
    ],
    [
        "label" => "Asia/Beirut (GMT+02:00)",
        "tzCode" => "Asia/Beirut",
        "name" => "(GMT+02:00) Beirut, Ra’s Bayrūt, Tripoli, Sidon, Tyre",
        "utc" => "+02:00"
    ],
    [
        "label" => "Asia/Damascus (GMT+02:00)",
        "tzCode" => "Asia/Damascus",
        "name" => "(GMT+02:00) Aleppo, Damascus, Homs, Ḩamāh, Latakia",
        "utc" => "+02:00"
    ],
    [
        "label" => "Asia/Famagusta (GMT+02:00)",
        "tzCode" => "Asia/Famagusta",
        "name" => "(GMT+02:00) Famagusta, Kyrenia, Protaras, Paralímni, Lápithos",
        "utc" => "+02:00"
    ],
    [
        "label" => "Asia/Gaza (GMT+02:00)",
        "tzCode" => "Asia/Gaza",
        "name" => "(GMT+02:00) Gaza, Khān Yūnis, Jabālyā, Rafaḩ, Dayr al Balaḩ",
        "utc" => "+02:00"
    ],
    [
        "label" => "Asia/Hebron (GMT+02:00)",
        "tzCode" => "Asia/Hebron",
        "name" => "(GMT+02:00) East Jerusalem, Hebron, Nablus, Battir, Ţūlkarm",
        "utc" => "+02:00"
    ],
    [
        "label" => "Asia/Jerusalem (GMT+02:00)",
        "tzCode" => "Asia/Jerusalem",
        "name" => "(GMT+02:00) Jerusalem, Tel Aviv, West Jerusalem, Haifa, Ashdod",
        "utc" => "+02:00"
    ],
    [
        "label" => "Asia/Nicosia (GMT+02:00)",
        "tzCode" => "Asia/Nicosia",
        "name" => "(GMT+02:00) Nicosia, Limassol, Larnaca, Stróvolos, Paphos",
        "utc" => "+02:00"
    ],
    [
        "label" => "Europe/Athens (GMT+02:00)",
        "tzCode" => "Europe/Athens",
        "name" => "(GMT+02:00) Athens, Thessaloníki, Pátra, Piraeus, Lárisa",
        "utc" => "+02:00"
    ],
    [
        "label" => "Europe/Bucharest (GMT+02:00)",
        "tzCode" => "Europe/Bucharest",
        "name" => "(GMT+02:00) Bucharest, Sector 3, Sector 6, Sector 2, Iaşi",
        "utc" => "+02:00"
    ],
    [
        "label" => "Europe/Chisinau (GMT+02:00)",
        "tzCode" => "Europe/Chisinau",
        "name" => "(GMT+02:00) Chisinau, Tiraspol, Bălţi, Bender, Rîbniţa",
        "utc" => "+02:00"
    ],
    [
        "label" => "Europe/Helsinki (GMT+02:00)",
        "tzCode" => "Europe/Helsinki",
        "name" => "(GMT+02:00) Helsinki, Espoo, Tampere, Vantaa, Turku",
        "utc" => "+02:00"
    ],
    [
        "label" => "Europe/Kaliningrad (GMT+02:00)",
        "tzCode" => "Europe/Kaliningrad",
        "name" => "(GMT+02:00) Kaliningrad, Chernyakhovsk, Sovetsk, Baltiysk, Gusev",
        "utc" => "+02:00"
    ],
    [
        "label" => "Europe/Kiev (GMT+02:00)",
        "tzCode" => "Europe/Kiev",
        "name" => "(GMT+02:00) Kyiv, Kharkiv, Donetsk, Odessa, Dnipro",
        "utc" => "+02:00"
    ],
    [
        "label" => "Europe/Mariehamn (GMT+02:00)",
        "tzCode" => "Europe/Mariehamn",
        "name" => "(GMT+02:00) Mariehamn",
        "utc" => "+02:00"
    ],
    [
        "label" => "Europe/Riga (GMT+02:00)",
        "tzCode" => "Europe/Riga",
        "name" => "(GMT+02:00) Riga, Daugavpils, Liepāja, Jelgava, Jūrmala",
        "utc" => "+02:00"
    ],
    [
        "label" => "Europe/Sofia (GMT+02:00)",
        "tzCode" => "Europe/Sofia",
        "name" => "(GMT+02:00) Sofia, Plovdiv, Varna, Burgas, Ruse",
        "utc" => "+02:00"
    ],
    [
        "label" => "Europe/Tallinn (GMT+02:00)",
        "tzCode" => "Europe/Tallinn",
        "name" => "(GMT+02:00) Tallinn, Tartu, Narva, Kohtla-Järve, Pärnu",
        "utc" => "+02:00"
    ],
    [
        "label" => "Europe/Uzhgorod (GMT+02:00)",
        "tzCode" => "Europe/Uzhgorod",
        "name" => "(GMT+02:00) Uzhgorod, Mukachevo, Khust, Berehove, Tyachiv",
        "utc" => "+02:00"
    ],
    [
        "label" => "Europe/Vilnius (GMT+02:00)",
        "tzCode" => "Europe/Vilnius",
        "name" => "(GMT+02:00) Vilnius, Kaunas, Klaipėda, Šiauliai, Panevėžys",
        "utc" => "+02:00"
    ],
    [
        "label" => "Europe/Zaporozhye (GMT+02:00)",
        "tzCode" => "Europe/Zaporozhye",
        "name" => "(GMT+02:00) Luhansk, Sevastopol, Sievierodonetsk, Alchevsk, Lysychansk",
        "utc" => "+02:00"
    ],
    [
        "label" => "Africa/Addis_Ababa (GMT+03:00)",
        "tzCode" => "Africa/Addis_Ababa",
        "name" => "(GMT+03:00) Addis Ababa, Dire Dawa, Mek'ele, Nazrēt, Bahir Dar",
        "utc" => "+03:00"
    ],
    [
        "label" => "Africa/Asmara (GMT+03:00)",
        "tzCode" => "Africa/Asmara",
        "name" => "(GMT+03:00) Asmara, Keren, Massawa, Assab, Mendefera",
        "utc" => "+03:00"
    ],
    [
        "label" => "Africa/Dar_es_Salaam (GMT+03:00)",
        "tzCode" => "Africa/Dar_es_Salaam",
        "name" => "(GMT+03:00) Dar es Salaam, Mwanza, Zanzibar, Arusha, Mbeya",
        "utc" => "+03:00"
    ],
    [
        "label" => "Africa/Djibouti (GMT+03:00)",
        "tzCode" => "Africa/Djibouti",
        "name" => "(GMT+03:00) Djibouti, 'Ali Sabieh, Tadjourah, Obock, Dikhil",
        "utc" => "+03:00"
    ],
    [
        "label" => "Africa/Kampala (GMT+03:00)",
        "tzCode" => "Africa/Kampala",
        "name" => "(GMT+03:00) Kampala, Gulu, Lira, Mbarara, Jinja",
        "utc" => "+03:00"
    ],
    [
        "label" => "Africa/Mogadishu (GMT+03:00)",
        "tzCode" => "Africa/Mogadishu",
        "name" => "(GMT+03:00) Mogadishu, Hargeysa, Berbera, Kismayo, Marka",
        "utc" => "+03:00"
    ],
    [
        "label" => "Africa/Nairobi (GMT+03:00)",
        "tzCode" => "Africa/Nairobi",
        "name" => "(GMT+03:00) Nairobi, Mombasa, Nakuru, Eldoret, Kisumu",
        "utc" => "+03:00"
    ],
    [
        "label" => "Antarctica/Syowa (GMT+03:00)",
        "tzCode" => "Antarctica/Syowa",
        "name" => "(GMT+03:00) Syowa",
        "utc" => "+03:00"
    ],
    [
        "label" => "Asia/Aden (GMT+03:00)",
        "tzCode" => "Asia/Aden",
        "name" => "(GMT+03:00) Sanaa, Al Ḩudaydah, Taiz, Aden, Mukalla",
        "utc" => "+03:00"
    ],
    [
        "label" => "Asia/Baghdad (GMT+03:00)",
        "tzCode" => "Asia/Baghdad",
        "name" => "(GMT+03:00) Baghdad, Basrah, Al Mawşil al Jadīdah, Al Başrah al Qadīmah, Mosul",
        "utc" => "+03:00"
    ],
    [
        "label" => "Asia/Bahrain (GMT+03:00)",
        "tzCode" => "Asia/Bahrain",
        "name" => "(GMT+03:00) Manama, Al Muharraq, Ar Rifā‘, Dār Kulayb, Madīnat Ḩamad",
        "utc" => "+03:00"
    ],
    [
        "label" => "Asia/Kuwait (GMT+03:00)",
        "tzCode" => "Asia/Kuwait",
        "name" => "(GMT+03:00) Al Aḩmadī, Ḩawallī, As Sālimīyah, Şabāḩ as Sālim, Al Farwānīyah",
        "utc" => "+03:00"
    ],
    [
        "label" => "Asia/Qatar (GMT+03:00)",
        "tzCode" => "Asia/Qatar",
        "name" => "(GMT+03:00) Doha, Ar Rayyān, Umm Şalāl Muḩammad, Al Wakrah, Al Khawr",
        "utc" => "+03:00"
    ],
    [
        "label" => "Asia/Riyadh (GMT+03:00)",
        "tzCode" => "Asia/Riyadh",
        "name" => "(GMT+03:00) Riyadh, Jeddah, Mecca, Medina, Sulţānah",
        "utc" => "+03:00"
    ],
    [
        "label" => "Europe/Istanbul (GMT+03:00)",
        "tzCode" => "Europe/Istanbul",
        "name" => "(GMT+03:00) Istanbul, Ankara, İzmir, Bursa, Adana",
        "utc" => "+03:00"
    ],
    [
        "label" => "Europe/Kirov (GMT+03:00)",
        "tzCode" => "Europe/Kirov",
        "name" => "(GMT+03:00) Kirov, Kirovo-Chepetsk, Vyatskiye Polyany, Slobodskoy, Kotel’nich",
        "utc" => "+03:00"
    ],
    [
        "label" => "Europe/Minsk (GMT+03:00)",
        "tzCode" => "Europe/Minsk",
        "name" => "(GMT+03:00) Minsk, Homyel', Mahilyow, Vitebsk, Hrodna",
        "utc" => "+03:00"
    ],
    [
        "label" => "Europe/Moscow (GMT+03:00)",
        "tzCode" => "Europe/Moscow",
        "name" => "(GMT+03:00) Moscow, Saint Petersburg, Nizhniy Novgorod, Kazan, Rostov-na-Donu",
        "utc" => "+03:00"
    ],
    [
        "label" => "Europe/Simferopol (GMT+03:00)",
        "tzCode" => "Europe/Simferopol",
        "name" => "(GMT+03:00) Simferopol, Kerch, Yevpatoriya, Yalta, Feodosiya",
        "utc" => "+03:00"
    ],
    [
        "label" => "Europe/Volgograd (GMT+03:00)",
        "tzCode" => "Europe/Volgograd",
        "name" => "(GMT+03:00) Volgograd, Volzhskiy, Kamyshin, Mikhaylovka, Uryupinsk",
        "utc" => "+03:00"
    ],
    [
        "label" => "Indian/Antananarivo (GMT+03:00)",
        "tzCode" => "Indian/Antananarivo",
        "name" => "(GMT+03:00) Antananarivo, Toamasina, Antsirabe, Fianarantsoa, Mahajanga",
        "utc" => "+03:00"
    ],
    [
        "label" => "Indian/Comoro (GMT+03:00)",
        "tzCode" => "Indian/Comoro",
        "name" => "(GMT+03:00) Moroni, Moutsamoudou, Fomboni, Domoni, Tsimbeo",
        "utc" => "+03:00"
    ],
    [
        "label" => "Indian/Mayotte (GMT+03:00)",
        "tzCode" => "Indian/Mayotte",
        "name" => "(GMT+03:00) Mamoudzou, Koungou, Dzaoudzi, Dembeni, Sada",
        "utc" => "+03:00"
    ],
    [
        "label" => "Asia/Tehran (GMT+03:30)",
        "tzCode" => "Asia/Tehran",
        "name" => "(GMT+03:30) Tehran, Mashhad, Isfahan, Karaj, Tabriz",
        "utc" => "+03:30"
    ],
    [
        "label" => "Asia/Baku (GMT+04:00)",
        "tzCode" => "Asia/Baku",
        "name" => "(GMT+04:00) Baku, Ganja, Sumqayıt, Lankaran, Yevlakh",
        "utc" => "+04:00"
    ],
    [
        "label" => "Asia/Dubai (GMT+04:00)",
        "tzCode" => "Asia/Dubai",
        "name" => "(GMT+04:00) Dubai, Sharjah, Abu Dhabi, Ajman City, Ras Al Khaimah City",
        "utc" => "+04:00"
    ],
    [
        "label" => "Asia/Muscat (GMT+04:00)",
        "tzCode" => "Asia/Muscat",
        "name" => "(GMT+04:00) Muscat, Seeb, Şalālah, Bawshar, Sohar",
        "utc" => "+04:00"
    ],
    [
        "label" => "Asia/Tbilisi (GMT+04:00)",
        "tzCode" => "Asia/Tbilisi",
        "name" => "(GMT+04:00) Tbilisi, Kutaisi, Batumi, Sokhumi, Zugdidi",
        "utc" => "+04:00"
    ],
    [
        "label" => "Asia/Yerevan (GMT+04:00)",
        "tzCode" => "Asia/Yerevan",
        "name" => "(GMT+04:00) Yerevan, Gyumri, Vanadzor, Vagharshapat, Hrazdan",
        "utc" => "+04:00"
    ],
    [
        "label" => "Europe/Astrakhan (GMT+04:00)",
        "tzCode" => "Europe/Astrakhan",
        "name" => "(GMT+04:00) Astrakhan, Akhtubinsk, Znamensk, Kharabali, Kamyzyak",
        "utc" => "+04:00"
    ],
    [
        "label" => "Europe/Samara (GMT+04:00)",
        "tzCode" => "Europe/Samara",
        "name" => "(GMT+04:00) Samara, Togliatti-on-the-Volga, Izhevsk, Syzran’, Novokuybyshevsk",
        "utc" => "+04:00"
    ],
    [
        "label" => "Europe/Saratov (GMT+04:00)",
        "tzCode" => "Europe/Saratov",
        "name" => "(GMT+04:00) Saratov, Balakovo, Engel’s, Balashov, Vol’sk",
        "utc" => "+04:00"
    ],
    [
        "label" => "Europe/Ulyanovsk (GMT+04:00)",
        "tzCode" => "Europe/Ulyanovsk",
        "name" => "(GMT+04:00) Ulyanovsk, Dimitrovgrad, Inza, Barysh, Novoul’yanovsk",
        "utc" => "+04:00"
    ],
    [
        "label" => "Indian/Mahe (GMT+04:00)",
        "tzCode" => "Indian/Mahe",
        "name" => "(GMT+04:00) Victoria, Anse Boileau, Bel Ombre, Beau Vallon, Cascade",
        "utc" => "+04:00"
    ],
    [
        "label" => "Indian/Mauritius (GMT+04:00)",
        "tzCode" => "Indian/Mauritius",
        "name" => "(GMT+04:00) Port Louis, Beau Bassin-Rose Hill, Vacoas, Curepipe, Quatre Bornes",
        "utc" => "+04:00"
    ],
    [
        "label" => "Indian/Reunion (GMT+04:00)",
        "tzCode" => "Indian/Reunion",
        "name" => "(GMT+04:00) Saint-Denis, Saint-Paul, Saint-Pierre, Le Tampon, Saint-André",
        "utc" => "+04:00"
    ],
    [
        "label" => "Asia/Kabul (GMT+04:30)",
        "tzCode" => "Asia/Kabul",
        "name" => "(GMT+04:30) Kabul, Kandahār, Mazār-e Sharīf, Herāt, Jalālābād",
        "utc" => "+04:30"
    ],
    [
        "label" => "Antarctica/Mawson (GMT+05:00)",
        "tzCode" => "Antarctica/Mawson",
        "name" => "(GMT+05:00) Mawson",
        "utc" => "+05:00"
    ],
    [
        "label" => "Asia/Aqtau (GMT+05:00)",
        "tzCode" => "Asia/Aqtau",
        "name" => "(GMT+05:00) Shevchenko, Zhanaozen, Beyneu, Shetpe, Yeraliyev",
        "utc" => "+05:00"
    ],
    [
        "label" => "Asia/Aqtobe (GMT+05:00)",
        "tzCode" => "Asia/Aqtobe",
        "name" => "(GMT+05:00) Aktobe, Kandyagash, Shalqar, Khromtau, Embi",
        "utc" => "+05:00"
    ],
    [
        "label" => "Asia/Ashgabat (GMT+05:00)",
        "tzCode" => "Asia/Ashgabat",
        "name" => "(GMT+05:00) Ashgabat, Türkmenabat, Daşoguz, Mary, Balkanabat",
        "utc" => "+05:00"
    ],
    [
        "label" => "Asia/Atyrau (GMT+05:00)",
        "tzCode" => "Asia/Atyrau",
        "name" => "(GMT+05:00) Atyrau, Qulsary, Shalkar, Balykshi, Maqat",
        "utc" => "+05:00"
    ],
    [
        "label" => "Asia/Dushanbe (GMT+05:00)",
        "tzCode" => "Asia/Dushanbe",
        "name" => "(GMT+05:00) Dushanbe, Khujand, Kŭlob, Bokhtar, Istaravshan",
        "utc" => "+05:00"
    ],
    [
        "label" => "Asia/Karachi (GMT+05:00)",
        "tzCode" => "Asia/Karachi",
        "name" => "(GMT+05:00) Karachi, Lahore, Faisalabad, Rawalpindi, Multan",
        "utc" => "+05:00"
    ],
    [
        "label" => "Asia/Oral (GMT+05:00)",
        "tzCode" => "Asia/Oral",
        "name" => "(GMT+05:00) Oral, Aqsay, Zhänibek, Tasqala, Zhumysker",
        "utc" => "+05:00"
    ],
    [
        "label" => "Asia/Qyzylorda (GMT+05:00)",
        "tzCode" => "Asia/Qyzylorda",
        "name" => "(GMT+05:00) Kyzylorda, Baikonur, Novokazalinsk, Aral, Chiili",
        "utc" => "+05:00"
    ],
    [
        "label" => "Asia/Samarkand (GMT+05:00)",
        "tzCode" => "Asia/Samarkand",
        "name" => "(GMT+05:00) Samarkand, Bukhara, Nukus, Qarshi, Jizzax",
        "utc" => "+05:00"
    ],
    [
        "label" => "Asia/Tashkent (GMT+05:00)",
        "tzCode" => "Asia/Tashkent",
        "name" => "(GMT+05:00) Tashkent, Namangan, Andijon, Qo‘qon, Chirchiq",
        "utc" => "+05:00"
    ],
    [
        "label" => "Asia/Yekaterinburg (GMT+05:00)",
        "tzCode" => "Asia/Yekaterinburg",
        "name" => "(GMT+05:00) Yekaterinburg, Chelyabinsk, Ufa, Perm, Orenburg",
        "utc" => "+05:00"
    ],
    [
        "label" => "Indian/Kerguelen (GMT+05:00)",
        "tzCode" => "Indian/Kerguelen",
        "name" => "(GMT+05:00) Port-aux-Français",
        "utc" => "+05:00"
    ],
    [
        "label" => "Indian/Maldives (GMT+05:00)",
        "tzCode" => "Indian/Maldives",
        "name" => "(GMT+05:00) Male, Fuvahmulah, Hithadhoo, Kulhudhuffushi, Thinadhoo",
        "utc" => "+05:00"
    ],
    [
        "label" => "Asia/Colombo (GMT+05:30)",
        "tzCode" => "Asia/Colombo",
        "name" => "(GMT+05:30) Colombo, Dehiwala-Mount Lavinia, Moratuwa, Jaffna, Negombo",
        "utc" => "+05:30"
    ],
    [
        "label" => "Asia/Kolkata (GMT+05:30)",
        "tzCode" => "Asia/Kolkata",
        "name" => "(GMT+05:30) Mumbai, Delhi, Bengaluru, Kolkata, Chennai",
        "utc" => "+05:30"
    ],
    [
        "label" => "Asia/Kathmandu (GMT+05:45)",
        "tzCode" => "Asia/Kathmandu",
        "name" => "(GMT+05:45) Kathmandu, Pokhara, Pātan, Biratnagar, Birgañj",
        "utc" => "+05:45"
    ],
    [
        "label" => "Antarctica/Vostok (GMT+06:00)",
        "tzCode" => "Antarctica/Vostok",
        "name" => "(GMT+06:00) Vostok",
        "utc" => "+06:00"
    ],
    [
        "label" => "Asia/Almaty (GMT+06:00)",
        "tzCode" => "Asia/Almaty",
        "name" => "(GMT+06:00) Almaty, Karagandy, Shymkent, Taraz, Nur-Sultan",
        "utc" => "+06:00"
    ],
    [
        "label" => "Asia/Bishkek (GMT+06:00)",
        "tzCode" => "Asia/Bishkek",
        "name" => "(GMT+06:00) Bishkek, Osh, Jalal-Abad, Karakol, Tokmok",
        "utc" => "+06:00"
    ],
    [
        "label" => "Asia/Dhaka (GMT+06:00)",
        "tzCode" => "Asia/Dhaka",
        "name" => "(GMT+06:00) Dhaka, Chattogram, Khulna, Rājshāhi, Comilla",
        "utc" => "+06:00"
    ],
    [
        "label" => "Asia/Omsk (GMT+06:00)",
        "tzCode" => "Asia/Omsk",
        "name" => "(GMT+06:00) Omsk, Tara, Kalachinsk, Znamenskoye, Tavricheskoye",
        "utc" => "+06:00"
    ],
    [
        "label" => "Asia/Qostanay (GMT+06:00)",
        "tzCode" => "Asia/Qostanay",
        "name" => "(GMT+06:00) Kostanay, Rudnyy, Dzhetygara, Arkalyk, Lisakovsk",
        "utc" => "+06:00"
    ],
    [
        "label" => "Asia/Thimphu (GMT+06:00)",
        "tzCode" => "Asia/Thimphu",
        "name" => "(GMT+06:00) himphu, Punākha, Tsirang, Phuntsholing, Pemagatshel",
        "utc" => "+06:00"
    ],
    [
        "label" => "Asia/Urumqi (GMT+06:00)",
        "tzCode" => "Asia/Urumqi",
        "name" => "(GMT+06:00) Zhongshan, Ürümqi, Zhanjiang, Shihezi, Huocheng",
        "utc" => "+06:00"
    ],
    [
        "label" => "Indian/Chagos (GMT+06:00)",
        "tzCode" => "Indian/Chagos",
        "name" => "(GMT+06:00) British Indian Ocean Territory",
        "utc" => "+06:00"
    ],
    [
        "label" => "Asia/Yangon (GMT+06:30)",
        "tzCode" => "Asia/Yangon",
        "name" => "(GMT+06:30) Yangon, Mandalay, Nay Pyi Taw, Mawlamyine, Kyain Seikgyi Township",
        "utc" => "+06:30"
    ],
    [
        "label" => "Indian/Cocos (GMT+06:30)",
        "tzCode" => "Indian/Cocos",
        "name" => "(GMT+06:30) West Island",
        "utc" => "+06:30"
    ],
    [
        "label" => "Antarctica/Davis (GMT+07:00)",
        "tzCode" => "Antarctica/Davis",
        "name" => "(GMT+07:00) Davis",
        "utc" => "+07:00"
    ],
    [
        "label" => "Asia/Bangkok (GMT+07:00)",
        "tzCode" => "Asia/Bangkok",
        "name" => "(GMT+07:00) Bangkok, Hanoi, Haiphong, Samut Prakan, Mueang Nonthaburi",
        "utc" => "+07:00"
    ],
    [
        "label" => "Asia/Barnaul (GMT+07:00)",
        "tzCode" => "Asia/Barnaul",
        "name" => "(GMT+07:00) Barnaul, Biysk, Rubtsovsk, Novoaltaysk, Gorno-Altaysk",
        "utc" => "+07:00"
    ],
    [
        "label" => "Asia/Hovd (GMT+07:00)",
        "tzCode" => "Asia/Hovd",
        "name" => "(GMT+07:00) Khovd, Ölgii, Ulaangom, Uliastay, Altai",
        "utc" => "+07:00"
    ],
    [
        "label" => "Asia/Ho_Chi_Minh (GMT+07:00)",
        "tzCode" => "Asia/Ho_Chi_Minh",
        "name" => "(GMT+07:00) Ho Chi Minh City, Da Nang, Biên Hòa, Nha Trang, Cần Thơ",
        "utc" => "+07:00"
    ],
    [
        "label" => "Asia/Jakarta (GMT+07:00)",
        "tzCode" => "Asia/Jakarta",
        "name" => "(GMT+07:00) Jakarta, Surabaya, Medan, Bandung, Bekasi",
        "utc" => "+07:00"
    ],
    [
        "label" => "Asia/Krasnoyarsk (GMT+07:00)",
        "tzCode" => "Asia/Krasnoyarsk",
        "name" => "(GMT+07:00) Krasnoyarsk, Abakan, Norilsk, Achinsk, Kyzyl",
        "utc" => "+07:00"
    ],
    [
        "label" => "Asia/Novokuznetsk (GMT+07:00)",
        "tzCode" => "Asia/Novokuznetsk",
        "name" => "(GMT+07:00) Novokuznetsk, Kemerovo, Prokop’yevsk, Leninsk-Kuznetsky, Kiselëvsk",
        "utc" => "+07:00"
    ],
    [
        "label" => "Asia/Novosibirsk (GMT+07:00)",
        "tzCode" => "Asia/Novosibirsk",
        "name" => "(GMT+07:00) Novosibirsk, Berdsk, Iskitim, Akademgorodok, Kuybyshev",
        "utc" => "+07:00"
    ],
    [
        "label" => "Asia/Phnom_Penh (GMT+07:00)",
        "tzCode" => "Asia/Phnom_Penh",
        "name" => "(GMT+07:00) Phnom Penh, Takeo, Sihanoukville, Battambang, Siem Reap",
        "utc" => "+07:00"
    ],
    [
        "label" => "Asia/Pontianak (GMT+07:00)",
        "tzCode" => "Asia/Pontianak",
        "name" => "(GMT+07:00) Pontianak, Tanjung Pinang, Palangkaraya, Singkawang, Sampit",
        "utc" => "+07:00"
    ],
    [
        "label" => "Asia/Tomsk (GMT+07:00)",
        "tzCode" => "Asia/Tomsk",
        "name" => "(GMT+07:00) Tomsk, Seversk, Strezhevoy, Kolpashevo, Asino",
        "utc" => "+07:00"
    ],
    [
        "label" => "Asia/Vientiane (GMT+07:00)",
        "tzCode" => "Asia/Vientiane",
        "name" => "(GMT+07:00) Vientiane, Pakse, Thakhèk, Savannakhet, Luang Prabang",
        "utc" => "+07:00"
    ],
    [
        "label" => "Indian/Christmas (GMT+07:00)",
        "tzCode" => "Indian/Christmas",
        "name" => "(GMT+07:00) Flying Fish Cove",
        "utc" => "+07:00"
    ],
    [
        "label" => "Asia/Brunei (GMT+08:00)",
        "tzCode" => "Asia/Brunei",
        "name" => "(GMT+08:00) Bandar Seri Begawan, Kuala Belait, Seria, Tutong, Bangar",
        "utc" => "+08:00"
    ],
    [
        "label" => "Asia/Choibalsan (GMT+08:00)",
        "tzCode" => "Asia/Choibalsan",
        "name" => "(GMT+08:00) Baruun-Urt, Choibalsan",
        "utc" => "+08:00"
    ],
    [
        "label" => "Asia/Hong_Kong (GMT+08:00)",
        "tzCode" => "Asia/Hong_Kong",
        "name" => "(GMT+08:00) Hong Kong, Kowloon, Tsuen Wan, Yuen Long Kau Hui, Tung Chung",
        "utc" => "+08:00"
    ],
    [
        "label" => "Asia/Irkutsk (GMT+08:00)",
        "tzCode" => "Asia/Irkutsk",
        "name" => "(GMT+08:00) Irkutsk, Ulan-Ude, Bratsk, Angarsk, Ust’-Ilimsk",
        "utc" => "+08:00"
    ],
    [
        "label" => "Asia/Kuala_Lumpur (GMT+08:00)",
        "tzCode" => "Asia/Kuala_Lumpur",
        "name" => "(GMT+08:00) Kota Bharu, Kuala Lumpur, Klang, Kampung Baru Subang, Johor Bahru",
        "utc" => "+08:00"
    ],
    [
        "label" => "Asia/Kuching (GMT+08:00)",
        "tzCode" => "Asia/Kuching",
        "name" => "(GMT+08:00) Kuching, Kota Kinabalu, Sandakan, Tawau, Miri",
        "utc" => "+08:00"
    ],
    [
        "label" => "Asia/Macau (GMT+08:00)",
        "tzCode" => "Asia/Macau",
        "name" => "(GMT+08:00) Macau",
        "utc" => "+08:00"
    ],
    [
        "label" => "Asia/Makassar (GMT+08:00)",
        "tzCode" => "Asia/Makassar",
        "name" => "(GMT+08:00) Makassar, Denpasar, City of Balikpapan, Banjarmasin, Manado",
        "utc" => "+08:00"
    ],
    [
        "label" => "Asia/Manila (GMT+08:00)",
        "tzCode" => "Asia/Manila",
        "name" => "(GMT+08:00) Quezon City, Manila, Caloocan City, Budta, Davao",
        "utc" => "+08:00"
    ],
    [
        "label" => "Asia/Shanghai (GMT+08:00)",
        "tzCode" => "Asia/Shanghai",
        "name" => "(GMT+08:00) Shanghai, Beijing, Tianjin, Guangzhou, Shenzhen",
        "utc" => "+08:00"
    ],
    [
        "label" => "Asia/Singapore (GMT+08:00)",
        "tzCode" => "Asia/Singapore",
        "name" => "(GMT+08:00) Singapore, Woodlands",
        "utc" => "+08:00"
    ],
    [
        "label" => "Asia/Taipei (GMT+08:00)",
        "tzCode" => "Asia/Taipei",
        "name" => "(GMT+08:00) Taipei, Kaohsiung, Taichung, Tainan, Banqiao",
        "utc" => "+08:00"
    ],
    [
        "label" => "Asia/Ulaanbaatar (GMT+08:00)",
        "tzCode" => "Asia/Ulaanbaatar",
        "name" => "(GMT+08:00) Ulan Bator, Erdenet, Darhan, Hovd, Mörön",
        "utc" => "+08:00"
    ],
    [
        "label" => "Australia/Perth (GMT+08:00)",
        "tzCode" => "Australia/Perth",
        "name" => "(GMT+08:00) Perth, Rockingham, Mandurah, Bunbury, Albany",
        "utc" => "+08:00"
    ],
    [
        "label" => "Australia/Eucla (GMT+08:45)",
        "tzCode" => "Australia/Eucla",
        "name" => "(GMT+08:45) Eucla",
        "utc" => "+08:45"
    ],
    [
        "label" => "Asia/Chita (GMT+09:00)",
        "tzCode" => "Asia/Chita",
        "name" => "(GMT+09:00) Chita, Krasnokamensk, Borzya, Petrovsk-Zabaykal’skiy, Aginskoye",
        "utc" => "+09:00"
    ],
    [
        "label" => "Asia/Dili (GMT+09:00)",
        "tzCode" => "Asia/Dili",
        "name" => "(GMT+09:00) Dili, Maliana, Suai, Likisá, Aileu",
        "utc" => "+09:00"
    ],
    [
        "label" => "Asia/Jayapura (GMT+09:00)",
        "tzCode" => "Asia/Jayapura",
        "name" => "(GMT+09:00) Ambon, Jayapura, Sorong, Ternate, Abepura",
        "utc" => "+09:00"
    ],
    [
        "label" => "Asia/Khandyga (GMT+09:00)",
        "tzCode" => "Asia/Khandyga",
        "name" => "(GMT+09:00) Khandyga",
        "utc" => "+09:00"
    ],
    [
        "label" => "Asia/Pyongyang (GMT+09:00)",
        "tzCode" => "Asia/Pyongyang",
        "name" => "(GMT+09:00) Pyongyang, Hamhŭng, Namp’o, Sunch’ŏn, Hŭngnam",
        "utc" => "+09:00"
    ],
    [
        "label" => "Asia/Seoul (GMT+09:00)",
        "tzCode" => "Asia/Seoul",
        "name" => "(GMT+09:00) Seoul, Busan, Incheon, Daegu, Daejeon",
        "utc" => "+09:00"
    ],
    [
        "label" => "Asia/Tokyo (GMT+09:00)",
        "tzCode" => "Asia/Tokyo",
        "name" => "(GMT+09:00) Tokyo, Yokohama, Osaka, Nagoya, Sapporo",
        "utc" => "+09:00"
    ],
    [
        "label" => "Asia/Yakutsk (GMT+09:00)",
        "tzCode" => "Asia/Yakutsk",
        "name" => "(GMT+09:00) Yakutsk, Blagoveshchensk, Belogorsk, Neryungri, Svobodnyy",
        "utc" => "+09:00"
    ],
    [
        "label" => "Pacific/Palau (GMT+09:00)",
        "tzCode" => "Pacific/Palau",
        "name" => "(GMT+09:00) Koror, Koror Town, Kloulklubed, Ulimang, Mengellang",
        "utc" => "+09:00"
    ],
    [
        "label" => "Australia/Adelaide (GMT+09:30)",
        "tzCode" => "Australia/Adelaide",
        "name" => "(GMT+09:30) Adelaide, Adelaide Hills, Mount Gambier, Morphett Vale, Gawler",
        "utc" => "+09:30"
    ],
    [
        "label" => "Australia/Broken_Hill (GMT+09:30)",
        "tzCode" => "Australia/Broken_Hill",
        "name" => "(GMT+09:30) Broken Hill",
        "utc" => "+09:30"
    ],
    [
        "label" => "Australia/Darwin (GMT+09:30)",
        "tzCode" => "Australia/Darwin",
        "name" => "(GMT+09:30) Darwin, Alice Springs, Palmerston, Howard Springs",
        "utc" => "+09:30"
    ],
    [
        "label" => "Antarctica/DumontDUrville (GMT+10:00)",
        "tzCode" => "Antarctica/DumontDUrville",
        "name" => "(GMT+10:00) DumontDUrville",
        "utc" => "+10:00"
    ],
    [
        "label" => "Antarctica/Macquarie (GMT+10:00)",
        "tzCode" => "Antarctica/Macquarie",
        "name" => "(GMT+10:00) Macquarie",
        "utc" => "+10:00"
    ],
    [
        "label" => "Asia/Ust-Nera (GMT+10:00)",
        "tzCode" => "Asia/Ust-Nera",
        "name" => "(GMT+10:00) Ust-Nera",
        "utc" => "+10:00"
    ],
    [
        "label" => "Asia/Vladivostok (GMT+10:00)",
        "tzCode" => "Asia/Vladivostok",
        "name" => "(GMT+10:00) Vladivostok, Khabarovsk, Khabarovsk Vtoroy, Komsomolsk-on-Amur, Ussuriysk",
        "utc" => "+10:00"
    ],
    [
        "label" => "Australia/Brisbane (GMT+10:00)",
        "tzCode" => "Australia/Brisbane",
        "name" => "(GMT+10:00) Brisbane, Gold Coast, Logan City, Townsville, Cairns",
        "utc" => "+10:00"
    ],
    [
        "label" => "Australia/Currie (GMT+10:00)",
        "tzCode" => "Australia/Currie",
        "name" => "(GMT+10:00) Currie",
        "utc" => "+10:00"
    ],
    [
        "label" => "Australia/Hobart (GMT+10:00)",
        "tzCode" => "Australia/Hobart",
        "name" => "(GMT+10:00) Hobart, Launceston, Burnie, Devonport, Sandy Bay",
        "utc" => "+10:00"
    ],
    [
        "label" => "Australia/Lindeman (GMT+10:00)",
        "tzCode" => "Australia/Lindeman",
        "name" => "(GMT+10:00) Lindeman",
        "utc" => "+10:00"
    ],
    [
        "label" => "Australia/Melbourne (GMT+10:00)",
        "tzCode" => "Australia/Melbourne",
        "name" => "(GMT+10:00) Melbourne, Geelong, Bendigo, Ballarat, Melbourne City Centre",
        "utc" => "+10:00"
    ],
    [
        "label" => "Australia/Sydney (GMT+10:00)",
        "tzCode" => "Australia/Sydney",
        "name" => "(GMT+10:00) Sydney, Canberra, Newcastle, Wollongong, Maitland",
        "utc" => "+10:00"
    ],
    [
        "label" => "Pacific/Chuuk (GMT+10:00)",
        "tzCode" => "Pacific/Chuuk",
        "name" => "(GMT+10:00) Weno, Colonia",
        "utc" => "+10:00"
    ],
    [
        "label" => "Pacific/GuamVillage (GMT+10:00)",
        "tzCode" => "Pacific/GuamVillage",
        "name" => "(GMT+10:00) Dededo Village, Yigo Village, Tamuning, Tamuning-Tumon-Harmon Village, Mangilao Village",
        "utc" => "+10:00"
    ],
    [
        "label" => "Pacific/Port_Moresby (GMT+10:00)",
        "tzCode" => "Pacific/Port_Moresby",
        "name" => "(GMT+10:00) Port Moresby, Lae, Mount Hagen, Popondetta, Madang",
        "utc" => "+10:00"
    ],
    [
        "label" => "Pacific/Saipan (GMT+10:00)",
        "tzCode" => "Pacific/Saipan",
        "name" => "(GMT+10:00) Saipan, San Jose Village",
        "utc" => "+10:00"
    ],
    [
        "label" => "Australia/Lord_Howe (GMT+10:30)",
        "tzCode" => "Australia/Lord_Howe",
        "name" => "(GMT+10:30) Lord Howe",
        "utc" => "+10:30"
    ],
    [
        "label" => "Antarctica/Casey (GMT+11:00)",
        "tzCode" => "Antarctica/Casey",
        "name" => "(GMT+11:00) Casey",
        "utc" => "+11:00"
    ],
    [
        "label" => "Asia/Magadan (GMT+11:00)",
        "tzCode" => "Asia/Magadan",
        "name" => "(GMT+11:00) Magadan, Ust-Nera, Susuman, Ola",
        "utc" => "+11:00"
    ],
    [
        "label" => "Asia/Sakhalin (GMT+11:00)",
        "tzCode" => "Asia/Sakhalin",
        "name" => "(GMT+11:00) Yuzhno-Sakhalinsk, Korsakov, Kholmsk, Okha, Nevel’sk",
        "utc" => "+11:00"
    ],
    [
        "label" => "Asia/Srednekolymsk (GMT+11:00)",
        "tzCode" => "Asia/Srednekolymsk",
        "name" => "(GMT+11:00) Srednekolymsk",
        "utc" => "+11:00"
    ],
    [
        "label" => "Pacific/Bougainville (GMT+11:00)",
        "tzCode" => "Pacific/Bougainville",
        "name" => "(GMT+11:00) Arawa, Buka",
        "utc" => "+11:00"
    ],
    [
        "label" => "Pacific/Efate (GMT+11:00)",
        "tzCode" => "Pacific/Efate",
        "name" => "(GMT+11:00) Port-Vila, Luganville, Isangel, Sola, Lakatoro",
        "utc" => "+11:00"
    ],
    [
        "label" => "Pacific/Guadalcanal (GMT+11:00)",
        "tzCode" => "Pacific/Guadalcanal",
        "name" => "(GMT+11:00) Honiara, Malango, Auki, Gizo, Buala",
        "utc" => "+11:00"
    ],
    [
        "label" => "Pacific/Kosrae (GMT+11:00)",
        "tzCode" => "Pacific/Kosrae",
        "name" => "(GMT+11:00) Tofol",
        "utc" => "+11:00"
    ],
    [
        "label" => "Pacific/Norfolk (GMT+11:00)",
        "tzCode" => "Pacific/Norfolk",
        "name" => "(GMT+11:00) Kingston",
        "utc" => "+11:00"
    ],
    [
        "label" => "Pacific/Noumea (GMT+11:00)",
        "tzCode" => "Pacific/Noumea",
        "name" => "(GMT+11:00) Nouméa, Mont-Dore, Dumbéa, Païta, Wé",
        "utc" => "+11:00"
    ],
    [
        "label" => "Pacific/Pohnpei (GMT+11:00)",
        "tzCode" => "Pacific/Pohnpei",
        "name" => "(GMT+11:00) Kolonia, Kolonia Town, Palikir - National Government Center",
        "utc" => "+11:00"
    ],
    [
        "label" => "Antarctica/McMurdo (GMT+12:00)",
        "tzCode" => "Antarctica/McMurdo",
        "name" => "(GMT+12:00) McMurdo",
        "utc" => "+12:00"
    ],
    [
        "label" => "Asia/Anadyr (GMT+12:00)",
        "tzCode" => "Asia/Anadyr",
        "name" => "(GMT+12:00) Anadyr, Bilibino",
        "utc" => "+12:00"
    ],
    [
        "label" => "Asia/Kamchatka (GMT+12:00)",
        "tzCode" => "Asia/Kamchatka",
        "name" => "(GMT+12:00) Petropavlovsk-Kamchatsky, Yelizovo, Vilyuchinsk, Klyuchi, Mil’kovo",
        "utc" => "+12:00"
    ],
    [
        "label" => "Pacific/Auckland (GMT+12:00)",
        "tzCode" => "Pacific/Auckland",
        "name" => "(GMT+12:00) Auckland, Wellington, Christchurch, Manukau City, North Shore",
        "utc" => "+12:00"
    ],
    [
        "label" => "Pacific/Fiji (GMT+12:00)",
        "tzCode" => "Pacific/Fiji",
        "name" => "(GMT+12:00) Suva, Lautoka, Nadi, Labasa, Ba",
        "utc" => "+12:00"
    ],
    [
        "label" => "Pacific/Funafuti (GMT+12:00)",
        "tzCode" => "Pacific/Funafuti",
        "name" => "(GMT+12:00) Funafuti, Savave Village, Tanrake Village, Toga Village, Asau Village",
        "utc" => "+12:00"
    ],
    [
        "label" => "Pacific/Kwajalein (GMT+12:00)",
        "tzCode" => "Pacific/Kwajalein",
        "name" => "(GMT+12:00) Ebaye, Jabat",
        "utc" => "+12:00"
    ],
    [
        "label" => "Pacific/Majuro (GMT+12:00)",
        "tzCode" => "Pacific/Majuro",
        "name" => "(GMT+12:00) Majuro, Arno, Jabor, Wotje, Mili",
        "utc" => "+12:00"
    ],
    [
        "label" => "Pacific/Nauru (GMT+12:00)",
        "tzCode" => "Pacific/Nauru",
        "name" => "(GMT+12:00) Yaren, Baiti, Anabar, Uaboe, Ijuw",
        "utc" => "+12:00"
    ],
    [
        "label" => "Pacific/Tarawa (GMT+12:00)",
        "tzCode" => "Pacific/Tarawa",
        "name" => "(GMT+12:00) Tarawa, Betio Village, Bikenibeu Village",
        "utc" => "+12:00"
    ],
    [
        "label" => "Pacific/Wake (GMT+12:00)",
        "tzCode" => "Pacific/Wake",
        "name" => "(GMT+12:00) Wake",
        "utc" => "+12:00"
    ],
    [
        "label" => "Pacific/Wallis (GMT+12:00)",
        "tzCode" => "Pacific/Wallis",
        "name" => "(GMT+12:00) Mata-Utu, Leava, Alo",
        "utc" => "+12:00"
    ],
    [
        "label" => "Pacific/Chatham (GMT+12:45)",
        "tzCode" => "Pacific/Chatham",
        "name" => "(GMT+12:45) Waitangi",
        "utc" => "+12:45"
    ],
    [
        "label" => "Pacific/Apia (GMT+13:00)",
        "tzCode" => "Pacific/Apia",
        "name" => "(GMT+13:00) Apia, Asau, Mulifanua, Afega, Leulumoega",
        "utc" => "+13:00"
    ],
    [
        "label" => "Pacific/Enderbury (GMT+13:00)",
        "tzCode" => "Pacific/Enderbury",
        "name" => "(GMT+13:00) Enderbury",
        "utc" => "+13:00"
    ],
    [
        "label" => "Pacific/Fakaofo (GMT+13:00)",
        "tzCode" => "Pacific/Fakaofo",
        "name" => "(GMT+13:00) Atafu Village, Nukunonu, Fale old settlement",
        "utc" => "+13:00"
    ],
    [
        "label" => "Pacific/Tongatapu (GMT+13:00)",
        "tzCode" => "Pacific/Tongatapu",
        "name" => "(GMT+13:00) Nuku‘alofa, Lapaha, Neiafu, Pangai, ‘Ohonua",
        "utc" => "+13:00"
    ],
    [
        "label" => "Pacific/Kiritimati (GMT+14:00)",
        "tzCode" => "Pacific/Kiritimati",
        "name" => "(GMT+14:00) Kiritimati",
        "utc" => "+14:00"
	]
];

const TELEPHONE_CODES = [
    ["name" => 'UK (+44)', "code" => '44'],
    ["name" => 'USA (+1)', "code" => '1'],
    ["name" => 'Algeria (+213)', "code" => '213'],
    ["name" => 'Andorra (+376)', "code" => '376'],
    ["name" => 'Angola (+244)', "code" => '244'],
    ["name" => 'Anguilla (+1264)', "code" => '1264'],
    ["name" => 'Antigua & Barbuda (+1268)', "code" => '1268'],
    ["name" => 'Argentina (+54)', "code" => '54'],
    ["name" => 'Armenia (+374)', "code" => '374'],
    ["name" => 'Aruba (+297)', "code" => '297'],
    ["name" => 'Australia (+61)', "code" => '61'],
    ["name" => 'Austria (+43)', "code" => '43'],
    ["name" => 'Azerbaijan (+994)', "code" => '994'],
    ["name" => 'Bahamas (+1242)', "code" => '1242'],
    ["name" => 'Bahrain (+973)', "code" => '973'],
    ["name" => 'Bangladesh (+880)', "code" => '880'],
    ["name" => 'Barbados (+1246)', "code" => '1246'],
    ["name" => 'Belarus (+375)', "code" => '375'],
    ["name" => 'Belgium (+32)', "code" => '32'],
    ["name" => 'Belize (+501)', "code" => '501'],
    ["name" => 'Benin (+229)', "code" => '229'],
    ["name" => 'Bermuda (+1441)', "code" => '1441'],
    ["name" => 'Bhutan (+975)', "code" => '975'],
    ["name" => 'Bolivia (+591)', "code" => '591'],
    ["name" => 'Bosnia Herzegovina (+387)', "code" => '387'],
    ["name" => 'Botswana (+267)', "code" => '267'],
    ["name" => 'Brazil (+55)', "code" => '55'],
    ["name" => 'Brunei (+673)', "code" => '673'],
    ["name" => 'Bulgaria (+359)', "code" => '359'],
    ["name" => 'Burkina Faso (+226)', "code" => '226'],
    ["name" => 'Burundi (+257)', "code" => '257'],
    ["name" => 'Cambodia (+855)', "code" => '855'],
    ["name" => 'Cameroon (+237)', "code" => '237'],
    ["name" => 'Canada (+1)', "code" => '1'],
    ["name" => 'Cape Verde Islands (+238)', "code" => '238'],
    ["name" => 'Cayman Islands (+1345)', "code" => '1345'],
    ["name" => 'Central African Republic (+236)', "code" => '236'],
    ["name" => 'Chile (+56)', "code" => '56'],
    ["name" => 'China (+86)', "code" => '86'],
    ["name" => 'Colombia (+57)', "code" => '57'],
    ["name" => 'Comoros (+269)', "code" => '269'],
    ["name" => 'Congo (+242)', "code" => '242'],
    ["name" => 'Cook Islands (+682)', "code" => '682'],
    ["name" => 'Costa Rica (+506)', "code" => '506'],
    ["name" => 'Croatia (+385)', "code" => '385'],
    ["name" => 'Cuba (+53)', "code" => '53'],
    ["name" => 'Cyprus North (+90392)', "code" => '90392'],
    ["name" => 'Cyprus South (+357)', "code" => '357'],
    ["name" => 'Czech Republic (+42)', "code" => '42'],
    ["name" => 'Denmark (+45)', "code" => '45'],
    ["name" => 'Djibouti (+253)', "code" => '253'],
    ["name" => 'Dominica (+1767)', "code" => '1767'],
    ["name" => 'Dominican Republic (+1809)', "code" => '1809'],
    ["name" => 'Ecuador (+593)', "code" => '593'],
    ["name" => 'Egypt (+20)', "code" => '20'],
    ["name" => 'El Salvador (+503)', "code" => '503'],
    ["name" => 'Equatorial Guinea (+240)', "code" => '240'],
    ["name" => 'Eritrea (+291)', "code" => '291'],
    ["name" => 'Estonia (+372)', "code" => '372'],
    ["name" => 'Ethiopia (+251)', "code" => '251'],
    ["name" => 'Falkland Islands (+500)', "code" => '500'],
    ["name" => 'Faroe Islands (+298)', "code" => '298'],
    ["name" => 'Fiji (+679)', "code" => '679'],
    ["name" => 'Finland (+358)', "code" => '358'],
    ["name" => 'France (+33)', "code" => '33'],
    ["name" => 'French Guiana (+594)', "code" => '594'],
    ["name" => 'French Polynesia (+689)', "code" => '689'],
    ["name" => 'Gabon (+241)', "code" => '241'],
    ["name" => 'Gambia (+220)', "code" => '220'],
    ["name" => 'Georgia (+7880)', "code" => '7880'],
    ["name" => 'Germany (+49)', "code" => '49'],
    ["name" => 'Ghana (+233)', "code" => '233'],
    ["name" => 'Gibraltar (+350)', "code" => '350'],
    ["name" => 'Greece (+30)', "code" => '30'],
    ["name" => 'Greenland (+299)', "code" => '299'],
    ["name" => 'Grenada (+1473)', "code" => '1473'],
    ["name" => 'Guadeloupe (+590)', "code" => '590'],
    ["name" => 'Guam (+671)', "code" => '671'],
    ["name" => 'Guatemala (+502)', "code" => '502'],
    ["name" => 'Guinea (+224)', "code" => '224'],
    ["name" => 'Guinea - Bissau (+245)', "code" => '245'],
    ["name" => 'Guyana (+592)', "code" => '592'],
    ["name" => 'Haiti (+509)', "code" => '509'],
    ["name" => 'Honduras (+504)', "code" => '504'],
    ["name" => 'Hong Kong (+852)', "code" => '852'],
    ["name" => 'Hungary (+36)', "code" => '36'],
    ["name" => 'Iceland (+354)', "code" => '354'],
    ["name" => 'India (+91)', "code" => '91'],
    ["name" => 'Indonesia (+62)', "code" => '62'],
    ["name" => 'Iran (+98)', "code" => '98'],
    ["name" => 'Iraq (+964)', "code" => '964'],
    ["name" => 'Ireland (+353)', "code" => '353'],
    ["name" => 'Israel (+972)', "code" => '972'],
    ["name" => 'Italy (+39)', "code" => '39'],
    ["name" => 'Jamaica (+1876)', "code" => '1876'],
    ["name" => 'Japan (+81)', "code" => '81'],
    ["name" => 'Jordan (+962)', "code" => '962'],
    ["name" => 'Kazakhstan (+7)', "code" => '7'],
    ["name" => 'Kenya (+254)', "code" => '254'],
    ["name" => 'Kiribati (+686)', "code" => '686'],
    ["name" => 'Korea North (+850)', "code" => '850'],
    ["name" => 'Korea South (+82)', "code" => '82'],
    ["name" => 'Kuwait (+965)', "code" => '965'],
    ["name" => 'Kyrgyzstan (+996)', "code" => '996'],
    ["name" => 'Laos (+856)', "code" => '856'],
    ["name" => 'Latvia (+371)', "code" => '371'],
    ["name" => 'Lebanon (+961)', "code" => '961'],
    ["name" => 'Lesotho (+266)', "code" => '266'],
    ["name" => 'Liberia (+231)', "code" => '231'],
    ["name" => 'Libya (+218)', "code" => '218'],
    ["name" => 'Liechtenstein (+417)', "code" => '417'],
    ["name" => 'Lithuania (+370)', "code" => '370'],
    ["name" => 'Luxembourg (+352)', "code" => '352'],
    ["name" => 'Macao (+853)', "code" => '853'],
    ["name" => 'Macedonia (+389)', "code" => '389'],
    ["name" => 'Madagascar (+261)', "code" => '261'],
    ["name" => 'Malawi (+265)', "code" => '265'],
    ["name" => 'Malaysia (+60)', "code" => '60'],
    ["name" => 'Maldives (+960)', "code" => '960'],
    ["name" => 'Mali (+223)', "code" => '223'],
    ["name" => 'Malta (+356)', "code" => '356'],
    ["name" => 'Marshall Islands (+692)', "code" => '692'],
    ["name" => 'Martinique (+596)', "code" => '596'],
    ["name" => 'Mauritania (+222)', "code" => '222'],
    ["name" => 'Mayotte (+269)', "code" => '269'],
    ["name" => 'Mexico (+52)', "code" => '52'],
    ["name" => 'Micronesia (+691)', "code" => '691'],
    ["name" => 'Moldova (+373)', "code" => '373'],
    ["name" => 'Monaco (+377)', "code" => '377'],
    ["name" => 'Montserrat (+1664)', "code" => '1664'],
    ["name" => 'Morocco (+212)', "code" => '212'],
    ["name" => 'Mozambique (+258)', "code" => '258'],
    ["name" => 'Myanmar (+95)', "code" => '95'],
    ["name" => 'Namibia (+264)', "code" => '264'],
    ["name" => 'Nauru (+674)', "code" => '674'],
    ["name" => 'Nepal (+977)', "code" => '977'],
    ["name" => 'Netherlands (+31)', "code" => '31'],
    ["name" => 'New Caledonia (+687)', "code" => '687'],
    ["name" => 'New Zealand (+64)', "code" => '64'],
    ["name" => 'Nicaragua (+505)', "code" => '505'],
    ["name" => 'Niger (+227)', "code" => '227'],
    ["name" => 'Nigeria (+234)', "code" => '234'],
    ["name" => 'Niue (+683)', "code" => '683'],
    ["name" => 'Norfolk Islands (+672)', "code" => '672'],
    ["name" => 'Northern Marianas (+670)', "code" => '670'],
    ["name" => 'Norway (+47)', "code" => '47'],
    ["name" => 'Oman (+968)', "code" => '968'],
    ["name" => 'Palau (+680)', "code" => '680'],
    ["name" => 'Panama (+507)', "code" => '507'],
    ["name" => 'Papua New Guinea (+675)', "code" => '675'],
    ["name" => 'Paraguay (+595)', "code" => '595'],
    ["name" => 'Peru (+51)', "code" => '51'],
    ["name" => 'Philippines (+63)', "code" => '63'],
    ["name" => 'Poland (+48)', "code" => '48'],
    ["name" => 'Portugal (+351)', "code" => '351'],
    ["name" => 'Qatar (+974)', "code" => '974'],
    ["name" => 'Reunion (+262)', "code" => '262'],
    ["name" => 'Romania (+40)', "code" => '40'],
    ["name" => 'Russia (+7)', "code" => '7'],
    ["name" => 'Rwanda (+250)', "code" => '250'],
    ["name" => 'San Marino (+378)', "code" => '378'],
    ["name" => 'Sao Tome & Principe (+239)', "code" => '239'],
    ["name" => 'Saudi Arabia (+966)', "code" => '966'],
    ["name" => 'Senegal (+221)', "code" => '221'],
    ["name" => 'Serbia (+381)', "code" => '381'],
    ["name" => 'Seychelles (+248)', "code" => '248'],
    ["name" => 'Sierra Leone (+232)', "code" => '232'],
    ["name" => 'Singapore (+65)', "code" => '65'],
    ["name" => 'Slovak Republic (+421)', "code" => '421'],
    ["name" => 'Slovenia (+386)', "code" => '386'],
    ["name" => 'Solomon Islands (+677)', "code" => '677'],
    ["name" => 'Somalia (+252)', "code" => '252'],
    ["name" => 'South Africa (+27)', "code" => '27'],
    ["name" => 'Spain (+34)', "code" => '34'],
    ["name" => 'Sri Lanka (+94)', "code" => '94'],
    ["name" => 'St. Helena (+290)', "code" => '290'],
    ["name" => 'St. Kitts (+1869)', "code" => '1869'],
    ["name" => 'St. Lucia (+1758)', "code" => '1758'],
    ["name" => 'Sudan (+249)', "code" => '249'],
    ["name" => 'Suriname (+597)', "code" => '597'],
    ["name" => 'Swaziland (+268)', "code" => '268'],
    ["name" => 'Sweden (+46)', "code" => '46'],
    ["name" => 'Switzerland (+41)', "code" => '41'],
    ["name" => 'Syria (+963)', "code" => '963'],
    ["name" => 'Taiwan (+886)', "code" => '886'],
    ["name" => 'Tajikstan (+7)', "code" => '7'],
    ["name" => 'Thailand (+66)', "code" => '66'],
    ["name" => 'Togo (+228)', "code" => '228'],
    ["name" => 'Tonga (+676)', "code" => '676'],
    ["name" => 'Trinidad & Tobago (+1868)', "code" => '1868'],
    ["name" => 'Tunisia (+216)', "code" => '216'],
    ["name" => 'Turkey (+90)', "code" => '90'],
    ["name" => 'Turkmenistan (+7)', "code" => '7'],
    ["name" => 'Turkmenistan (+993)', "code" => '993'],
    ["name" => 'Turks & Caicos Islands (+1649)', "code" => '1649'],
    ["name" => 'Tuvalu (+688)', "code" => '688'],
    ["name" => 'Uganda (+256)', "code" => '256'],
    ["name" => 'Ukraine (+380)', "code" => '380'],
    ["name" => 'United Arab Emirates (+971)', "code" => '971'],
    ["name" => 'Uruguay (+598)', "code" => '598'],
    ["name" => 'Uzbekistan (+7)', "code" => '7'],
    ["name" => 'Vanuatu (+678)', "code" => '678'],
    ["name" => 'Vatican City (+379)', "code" => '379'],
    ["name" => 'Venezuela (+58)', "code" => '58'],
    ["name" => 'Vietnam (+84)', "code" => '84'],
    ["name" => 'Virgin Islands - British (+1284)', "code" => '1284'],
    ["name" => 'Virgin Islands - US (+1340)', "code" => '1340'],
    ["name" => 'Wallis & Futuna (+681)', "code" => '681'],
    ["name" => 'Yemen (North)(+969)', "code" => '969'],
    ["name" => 'Yemen (South)(+967)', "code" => '967'],
    ["name" => 'Zambia (+260)', "code" => '260'],
    ["name" => 'Zimbabwe (+263)', "code" => '263'],
];

const NOTIFICATION_TYPE = [
    'wallet' => 'wallet'
];
