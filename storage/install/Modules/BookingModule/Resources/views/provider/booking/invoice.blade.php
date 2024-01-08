<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{translate('invoice')}}</title>
    <link href="{{asset('public/assets')}}/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="{{asset('public/assets')}}/js/bootstrap.min.js"></script>
    <script src="{{asset('public/assets')}}/js/jquery.min.js"></script>
    <style>
        body {
            background-color: #F5F5F5;
            font-size: 14px !important;
        }

        a {
            color: rgb(65, 83, 179) !important;
            text-decoration: none !important
            s;
        }

        @media print {
            a {
                text-decoration: none !important
                s;
                -webkit-print-color-adjust: exact;
            }
        }

        #invoice {
            padding: 30px;
        }

        .invoice {
            position: relative;
            background-color: #FFF;
            min-height: 972px;
            padding: 20px;
            max-width: 920px;
            margin-left: auto;
            margin-right: auto;

        }

        .invoice header {
            padding: 10px 0;
            margin-bottom: 20px;
        }

        .invoice .contacts {
            margin-bottom: 20px
        }

        .invoice .company-details,
        .invoice .invoice-details {
            text-align: right
        }

        .invoice .thanks {
            margin-top: 60px;
            margin-bottom: 30px
        }

        .invoice .notices {
            text-align: center;
            background-color: #F7F7F7;
            padding: 30px;
        }

        @media print {
            .invoice .notices {
                background-color: #F7F7F7 !important;
                -webkit-print-color-adjust: exact;
            }
        }

        .invoice table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .invoice table td, .invoice table th {
            padding: 15px;
        }

        .invoice table td {
            background-color: rgba(65, 83, 179, 0.05);
        }

        @media print {
            .invoice table td {
                background-color: rgba(65, 83, 179, 0.05);
                -webkit-print-color-adjust: exact;
            }
        }

        .invoice table th {
            white-space: nowrap;
            font-weight: 700;
            font-size: 14px;
            background-color: #4153B3;
            color: #fff;
        }

        @media print {
            .invoice table th {
                background-color: #4153B3 !important;
                -webkit-print-color-adjust: exact;
            }
        }

        .invoice table td h3 {
            margin: 0;
            font-weight: 400;
            color: #242A30;
            font-size: 14px;
        }

        .invoice table tfoot td {
            background: 0 0;
            border: none;
            white-space: nowrap;
            text-align: right;
            padding: 4px 14px;
        }

        .invoice table tfoot tr:first-child td {
            padding-top: 30px;
        }

        .fw-700 {
            font-weight: 700;
        }
    </style>
</head>
<body>
<div id="invoice">
    <div class="invoice d-flex flex-column">
        <div>
            <header class="border-bottom">
                <div class="row align-items-center">
                    <div class="col">
                        <a target="_blank" href="#">
                            @php($logo = business_config('business_logo','business_information'))
                            <img height="80" src="{{asset('storage/app/public/business')}}/{{$logo->live_values}}"
                                 data-holder-rendered="true"/>
                        </a>
                    </div>
                    <div class="col company-details">
                        <h3 class="name">
                            @php($business_name = business_config('business_name','business_information'))
                            @php($business_email = business_config('business_email','business_information'))
                            @php($business_phone = business_config('business_phone','business_information'))
                            @php($business_address = business_config('business_address','business_information'))
                            <a target="_blank" href="#">
                                {{$business_name->live_values}}
                            </a>
                        </h3>
                        <div>{{$business_address->live_values}}</div>
                        <div>{{$business_phone->live_values}}</div>
                        <div>{{$business_email->live_values}}</div>
                    </div>
                </div>
            </header>
            <div class="row contacts">
                @php($customer_name = $booking->customer ? $booking?->customer?->first_name.' '.$booking?->customer?->last_name : $booking?->service_address?->contact_person_name)
                @php($customer_phone = $booking->customer ? $booking?->customer?->phone : $booking?->service_address?->contact_person_number)

                <div class="col invoice-to">
                    <div class="fw-700">{{translate('invoice_to')}}:
                        #{{$booking->readable_id}}</div>
                    <h3 class="to fw-700">{{$customer_name}}</h3>
                    <div class="address">{{$booking?->service_address?->address}}</div>
                    <div class="tel"><span>{{$customer_phone}}</span></div>
                    @if(isset($booking->customer))
                        <div class="email"><a
                                href="mailto:{{$booking?->customer?->email}}">{{$booking?->customer?->email}}</a></div>
                    @endif
                </div>
                <div class="col invoice-details">
                    <h3 class="invoice-id fw-700">{{translate('invoice')}}</h3>
                    <div class="date">{{translate('date_of_invoice')}}
                        : {{date('d/m/Y H:i:s a',strtotime($booking->created_at))}}</div>
                    <div class="date">{{translate('due_date')}}
                        : {{date('d/m/Y H:i:s a',strtotime($booking->created_at))}}</div>
                </div>
            </div>
            <table border="0" cellspacing="0" cellpadding="0">
                <thead>
                <tr>
                    <th class="text-left">{{translate('SL')}}</th>
                    <th class="text-center text-uppercase">{{translate('description')}}</th>
                    <th class="text-center text-uppercase">{{translate('cost')}}</th>
                    <th class="text-right text-uppercase">{{translate('qty')}}</th>
                    <th class="text-right text-uppercase">{{translate('total')}}</th>
                </tr>
                </thead>
                <tbody>
                @php($sub_total=0)
                @foreach($booking->detail as $index=>$item)
                    <tr>
                        <td class="text-left">{{(strlen($index+1)<2?'0':'').$index+1}}</td>
                        <td class="text-center">
                            <h3>
                                {{$item->service->name??''}}
                            </h3>
                            {{$item->variant_key}}
                        </td>
                        <td class="text-center">{{with_currency_symbol($item->service_cost)}}</td>
                        <td class="text-right">{{$item->quantity}}</td>
                        <td class="text-right">{{with_currency_symbol($item->total_cost)}}</td>
                    </tr>
                    @php($sub_total+=$item->service_cost*$item->quantity)
                @endforeach
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3"></td>
                    <td class="">{{translate('subtotal')}}</td>
                    <td>{{with_currency_symbol($sub_total)}}</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td>{{translate('Discount')}}</td>
                    <td>- {{with_currency_symbol($booking->total_discount_amount)}}</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td>{{translate('Campaign_Discount')}}</td>
                    <td>- {{with_currency_symbol($booking->total_campaign_discount_amount)}}</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td class="">{{translate('Coupon_Discount')}} </td>
                    <td>- {{with_currency_symbol($booking->total_coupon_discount_amount)}}</td>
                </tr>
                <tr>
                    <td colspan="3"></td>
                    <td class="">{{translate('Vat_/_Tax')}} (%)</td>
                    <td>+ {{with_currency_symbol($booking->total_tax_amount)}}</td>
                </tr>
                @if ($booking->extra_fee > 0)
                @php($additional_charge_label_name = business_config('additional_charge_label_name', 'booking_setup')->live_values??'Fee')
                    <tr>
                        <td colspan="2"></td>
                        <td colspan="2" class="text-uppercase">{{$additional_charge_label_name}}</td>
                        <td>+ {{with_currency_symbol($booking->extra_fee)}}</td>
                    </tr>
                @endif
                <tr>
                    <td colspan="3"></td>
                    <td class="fw-700 border-top">{{translate('Total')}}</td>
                    <td class="fw-700 border-top">{{with_currency_symbol($booking->total_booking_amount)}}</td>
                </tr>
                <!-- partial -->
                @if ($booking->booking_partial_payments->isNotEmpty())
                    @foreach($booking->booking_partial_payments as $partial)
                        <tr>
                            <td colspan="3"></td>
                            <td class="fw-700 border-top">{{translate('Paid_by')}} {{str_replace('_', ' ',$partial->paid_with)}}</td>
                            <td class="fw-700 border-top">{{with_currency_symbol($partial->paid_amount)}}</td>
                        </tr>
                    @endforeach
                @endif

                <!-- due -->
                <?php
                $due_amount = 0;

                if (!$booking->is_paid && $booking?->booking_partial_payments?->count() == 1) {
                    $due_amount = $booking->booking_partial_payments->first()?->due_amount;
                }

                if (!$booking->is_paid && $booking->additional_charge > 0) {
                    $due_amount += $booking->additional_charge;
                }
                ?>
                <tr>
                    <td colspan="3"></td>
                    <td class="fw-700 border-top">{{ translate('Due_Amount') }}</td>
                    <td class="fw-700 border-top">{{ with_currency_symbol($due_amount) }}</td>
                </tr>

                <!-- refund -->
                @if($booking->payment_method != 'cash_after_service' && $booking->additional_charge < 0)
                    <tr>
                        <td colspan="3"></td>
                        <td class="fw-700 border-top">{{translate('Refund')}}</td>
                        <td class="fw-700 border-top">{{with_currency_symbol(abs($booking->additional_charge))}}</td>
                    </tr>
                @endif
                </tfoot>
            </table>
        </div>
        <div class="mt-auto">
            <div
                class="thanks text-center">{{translate('If you require any assistance or have feedback or suggestions about our site, you ')}}
                <br/> {{translate('can email us at')}} <a
                    href="#">{{isset($booking->provider) ? $booking->provider->contact_person_email : ''}}</a></div>
            <div class="notices">
                <div class="notice">{{(business_config('footer_text','business_information'))->live_values}}</div>
            </div>
        </div>
        <div></div>
    </div>
</div>

<script>
    function printContent(el) {
        var restorepage = $('body').html();
        var printcontent = $('#' + el).clone();
        $('body').empty().html(printcontent);
        window.print();
        $('body').html(restorepage);
    }

    printContent('invoice');
</script>
</body>
</html>
