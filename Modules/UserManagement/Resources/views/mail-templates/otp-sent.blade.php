<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{translate('booking_request_sent')}}</title>
    <link href="{{asset('public/assets')}}/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="{{asset('public/assets')}}/js/bootstrap.min.js"></script>
    <script src="{{asset('public/assets')}}/js/jquery.min.js"></script>
    <style>
        a {
            color: rgb(65, 83, 179) !important;
        }

        #invoice {
            padding: 30px;
        }

        .invoice {
            position: relative;
            background-color: #FFF;
            min-height: 680px;
            padding: 15px
        }

        .invoice header {
            padding: 10px 0;
            margin-bottom: 20px;
            border-bottom: 1px solid rgb(65, 83, 179)
        }

        .invoice .company-details {
            text-align: right
        }

        .invoice .company-details .name {
            margin-top: 0;
            margin-bottom: 0
        }

        .invoice .contacts {
            margin-bottom: 20px
        }

        .invoice .invoice-to {
            text-align: left
        }

        .invoice .invoice-to .to {
            margin-top: 0;
            margin-bottom: 0
        }

        .invoice .invoice-details {
            text-align: right
        }

        .invoice .invoice-details .invoice-id {
            margin-top: 0;
            color: rgb(65, 83, 179)
        }

        .invoice main {
            padding-bottom: 50px
        }

        .invoice main .thanks {
            margin-top: -100px;
            font-size: 2em;
            margin-bottom: 50px
        }

        .invoice main .notices {
            padding-left: 6px;
            border-left: 6px solid rgb(65, 83, 179)
        }

        .invoice main .notices .notice {
            font-size: 1.2em
        }

        .invoice table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px
        }

        .invoice table td, .invoice table th {
            padding: 15px;
            background: #eee;
            border-bottom: 1px solid #fff
        }

        .invoice table th {
            white-space: nowrap;
            font-weight: 400;
            font-size: 16px
        }

        .invoice table td h3 {
            margin: 0;
            font-weight: 400;
            color: rgb(65, 83, 179);
            font-size: 1.2em
        }

        .invoice table .qty, .invoice table .total, .invoice table .unit {
            text-align: right;
            font-size: 1.2em
        }

        .invoice table .no {
            color: #fff;
            font-size: 1.6em;
            background: rgb(65, 83, 179)
        }

        .invoice table .unit {
            background: #ddd
        }

        .invoice table .total {
            background: rgb(65, 83, 179);
            color: #fff
        }

        .invoice table tbody tr:last-child td {
            border: none
        }

        .invoice table tfoot td {
            background: 0 0;
            border-bottom: none;
            white-space: nowrap;
            text-align: right;
            padding: 10px 20px;
            font-size: 1.2em;
            border-top: 1px solid #aaa
        }

        .invoice table tfoot tr:first-child td {
            border-top: none
        }

        .invoice table tfoot tr:last-child td {
            color: rgb(65, 83, 179);
            font-size: 1.4em;
            border-top: 1px solid rgb(65, 83, 179)
        }

        .invoice table tfoot tr td:first-child {
            border: none
        }

        .invoice footer {
            width: 100%;
            text-align: center;
            color: #777;
            border-top: 1px solid #aaa;
            padding: 8px 0
        }

        @media print {
            .invoice {
                font-size: 11px !important;
                overflow: hidden !important
            }

            .invoice footer {
                position: absolute;
                bottom: 10px;
                page-break-after: always
            }

            .invoice > div:last-child {
                page-break-before: always
            }
        }
    </style>
</head>
<body style="background-color: #ececec;margin:0;padding:0">
<div id="invoice">
    <div class="invoice overflow-auto">
        <div style="min-width: 600px">
            <header>
                <div class="row">
                    <div class="col">
                        <a target="_blank" href="#">
                            @php($logo = business_config('business_logo','business_information'))
                            <img width="200" src="{{asset('storage/app/public/business')}}/{{$logo->live_values}}"
                                 data-holder-rendered="true"/>
                        </a>
                    </div>
                    <div class="col company-details">
                        <h2 class="name">
                            @php($business_name = business_config('business_name','business_information'))
                            @php($business_email = business_config('business_email','business_information'))
                            @php($business_phone = business_config('business_phone','business_information'))
                            @php($business_address = business_config('business_address','business_information'))
                            <a target="_blank" href="#">
                                {{$business_name->live_values}}
                            </a>
                        </h2>
                        <div>{{$business_address->live_values}}</div>
                        <div>{{$business_phone->live_values}}</div>
                        <div>{{$business_email->live_values}}</div>
                    </div>
                </div>
            </header>
            <main style="height: 50px">
                {{translate('Your OTP is') . ' ' . $otp}}
            </main>
            <footer>
            </footer>
        </div>
        <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
        <div></div>
    </div>
</div>
</body>
</html>
