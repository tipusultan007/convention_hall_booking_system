<!DOCTYPE html>
<html lang="bn">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Receipt #{{ $booking->id }}</title>
    
     
    <style>
@php
    function pdf_font_path($file) {
        return 'file:///' . str_replace('\\', '/', public_path('fonts/' . $file));
    }
@endphp

@font-face {
    font-family: 'SolaimanLipi';
    src: url("{{ pdf_font_path('SolaimanLipi.ttf') }}") format('truetype');
    font-weight: normal;
    font-style: normal;
}

body {
    font-family: 'SolaimanLipi', sans-serif;
    font-size: 14px;
    line-height: 1.6;
}
        
        /* All your other styles for layout, tables, etc., will also work much more reliably. */
        /* You can copy the styles from the previous version here. */
        table { width: 100%; border-collapse: collapse; }
        .header { text-align: center; margin-bottom: 15px; }
        .header h1 { font-size: 24px; margin: 0; font-weight: 700; }
        .header h2 { font-size: 15px; margin: 0; }
        .header p { margin: 2px 0; font-size: 10px; }
        /* ... and so on ... */
        .contract-box { border: 1px solid #000; padding: 5px; width: 180px; }
        .contract-title { background-color: #363e75; color: white; text-align: center; padding: 3px; font-size: 15px; font-weight: bold; }
        .contract-details { padding: 5px; text-align: left; }
        .customer-info td { padding: 3px 2px; vertical-align: bottom; }
        .customer-info .label { font-weight: bold; width: 110px; }
        .customer-info .data-line { border-bottom: 1px dotted #000; padding: 0 5px; font-weight: bold; }
        .main-content { border: 2px solid #000; margin-top: 10px; }
        .main-content > tbody > tr > td { padding: 8px; vertical-align: top; }
        .description-cell { width: 65%; border-right: 2px solid #000; }
        .financials-cell { width: 35%; }
        .details-list { list-style-type: none; padding-left: 0; margin-top: 15px; }
        .details-list li { margin-bottom: 6px; }
        .financials-table { border: 1px solid #000; }
        .financials-table td { padding: 10px 8px; border-bottom: 1px solid #000; }
        .financials-table tr:last-child td { border-bottom: none; }
        .financials-table .label { font-size: 16px; }
        .financials-table .amount { font-size: 16px; font-weight: bold; text-align: right; }
        .in-words { margin-top: 10px; }
        .in-words .data-line { border-bottom: 1px dotted #000; padding: 0 5px; }
        .signature-area { margin-top: 50px; }
        .signature-box { width: 200px; text-align: center; border-top: 1px dotted #000; padding-top: 5px; }
        .terms { margin-top: 10px; font-size: 8px; border: 1px solid #ccc; padding: 5px; }

    </style>
</head>
<body>
    {{-- Your entire HTML body remains unchanged --}}
    {{-- It will now be rendered perfectly by the WebKit engine --}}
    <div class="header">
        <h1>মমতা কমিউনিটি সেন্টার</h1>
        <h2>MOMOTA COMMUNITY CENTER</h2>
        <p>বিয়ে, গায়ে হলুদ, জন্মদিন, আকিকা, মেজবান, সেমিনার সহ যে কোন সামাজিক অনুষ্ঠানের জন্য।</p>
        <p>সমতা আবাসিক এলাকা, প্রাণ হরিদাশ রোড, দক্ষিণ কাট্টলী, কাস্টম একাডেমী, পাহাড়তলী, চট্টগ্রাম। মোবাইল: ০১৮১৯-৮০৯০১৫ (মালিক), ০১৯৪৫-৮১৮৮৯৮ (ম্যানেজার)</p>
    </div>

    <table style="margin-bottom: 10px;">
        <tr>
            <td style="width: 70%;"></td>
            <td>
                <div class="contract-box">
                    <div class="contract-details">
                        <strong>ক্রমিক নং - </strong> {{ $booking->id }} <br>
                        <strong>তারিখ - </strong> {{ $booking->created_at->format('d/m/Y') }}
                    </div>
                </div>
            </td>
        </tr>
    </table>

    <table class="customer-info">
        <tr>
            <td class="label">গ্রাহকের নামঃ</td>
            <td class="data-line">{{ $booking->customer->name }}</td>
            <td class="label" style="padding-left: 20px;">ঠিকানাঃ</td>
            <td class="data-line">{{ $booking->customer->address }}</td>
        </tr>
        <tr>
            <td class="label">অনুষ্ঠানের তারিখঃ</td>
            <td class="data-line" colspan="3">
                @foreach($booking->bookingDates as $date)
                    {{ \Carbon\Carbon::parse($date->event_date)->format('d/m/Y') }} ({{ $date->time_slot }})@if(!$loop->last),&nbsp; @endif
                @endforeach
            </td>
        </tr>
    </table>

    <table class="main-content">
        <tr>
            <td class="description-cell">
                <h3 style="margin-top: 0; text-decoration: underline; font-weight: 700;">বিবরণ</h3>
                <ol style="padding-left: 20px; margin: 0;">
                    <li>বিয়ে</li> <li>গায়ে হলুদ</li> <li>জন্মদিন</li> <li>আকিকা</li> <li>মেজবান</li>
                </ol>
                <ul class="details-list">
                    <li><strong>অনুষ্ঠানের ধরনঃ</strong> {{ $booking->event_type }}</li>
                    <li><strong>মেহমান সংখ্যাঃ</strong> {{ $booking->guests_count ?? 'N/A' }}</li>
                    <li><strong>টেবিল সংখ্যাঃ</strong> {{ $booking->tables_count ?? 'N/A' }}</li>
                     <li><strong>বয়ের সংখ্যাঃ</strong> {{ $booking->boys_count ?? 'N/A' }}</li>
                </ul>
            </td>
            <td class="financials-cell">
                <table class="financials-table">
                    <tr> <td class="label">মোট</td> <td class="amount">৳ {{ number_format($booking->total_amount, 2) }}</td> </tr>
                    <tr> <td class="label">অগ্রিম</td> <td class="amount">৳ {{ number_format($booking->advance_amount, 2) }}</td> </tr>
                    <tr> <td class="label">বাকী</td> <td class="amount">৳ {{ number_format($booking->due_amount, 2) }}</td> </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="in-words">
        <tr>
            <td style="width: 50px; font-weight: 700;">কথায়ঃ</td>
            <td class="data-line">{{ $booking->notes_in_words }}</td>
        </tr>
    </table>

    <table class="signature-area">
        <tr>
            <td><div class="signature-box">গ্রাহকের স্বাক্ষর</div></td>
            <td style="text-align: right;"><div class="signature-box">ক্লাব কর্তৃপক্ষের স্বাক্ষর</div></td>
        </tr>
    </table>

    <div class="terms">
        <strong>বিঃ দ্রঃ-</strong> (১) চুক্তির টাকা সম্পূর্ণ অগ্রিম প্রদান করিতে হইবে। (২) অনুষ্ঠানের ২৪ ঘন্টা পূর্বে অবশিষ্ট বিলপরিশোধ করিতে হইবে। (৩) গ্রাহকের অতিথি নিয়ন্ত্রণ আইন মানিয়া চলিতে অনুরোধ করা যাইতেছে। (৪) অনুষ্ঠানের তারিখ কোন অবস্থাতেই পরিবর্তন যোগ্য নহে এবং অগ্রিম অর্থ ছাড় দেওয়া যেতে পারে। (৫) বকেয়া টাকা পরিশোধ করিবে গ্রাহক।
    </div>
</body>
</html>