<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>E-Ticket</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 30px;
        }

        .ticket-container {
            background: #fff;
            max-width: 600px;
            margin: 0 auto;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .ticket-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .ticket-header img {
            max-width: 120px;
            margin-bottom: 10px;
        }

        .ticket-header h2 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
        }

        td {
            padding: 10px 0;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            color: #555;
            width: 40%;
        }

        .value {
            color: #222;
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #888;
            text-align: center;
        }

    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="ticket-header">
            <img src="{{ public_path('images/logo.png') }}" alt="Logo">
            <h2>E-Ticket</h2>
        </div>

        <table>
            <tr>
                <td class="label">Order ID:</td>
                <td class="value">{{ $order_id }}</td>
            </tr>
            <tr>
                <td class="label">Nama:</td>
                <td class="value">{{ $buyer_name }}</td>
            </tr>
            <tr>
                <td class="label">Email:</td>
                <td class="value">{{ $buyer_email }}</td>
            </tr>
            <tr>
                <td class="label">NIK:</td>
                <td class="value">{{ $nik }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Lahir:</td>
                <td class="value">{{ $birth_date }}</td>
            </tr>
            <tr>
                <td class="label">Harga Tiket:</td>
                <td class="value">Rp {{ number_format($total_payment, 0, ',', '.') }}</td>
            </tr>
        </table>

        <div class="footer">
            Harap tunjukkan e-ticket ini saat masuk ke venue. Terima kasih telah membeli tiket!
        </div>
    </div>
</body>
</html>
