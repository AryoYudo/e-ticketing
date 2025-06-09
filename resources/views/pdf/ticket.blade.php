<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>E-Ticket</title>
    <style>
        body { font-family: sans-serif; }
        .box { border: 1px solid #000; padding: 20px; width: 100%; }
    </style>
</head>
<body>
    <h2>E-Ticket</h2>
    <div class="box">
        <p><strong>Order ID:</strong> {{ $order_id }}</p>
        <p><strong>Nama:</strong> {{ $buyer_name }}</p>
        <p><strong>Email:</strong> {{ $buyer_email }}</p>
        <p><strong>NIK:</strong> {{ $nik }}</p>
        <p><strong>Tanggal Lahir:</strong> {{ $birth_date }}</p>
        <p><strong>Harga Tiket:</strong> Rp {{ number_format($total_payment, 0, ',', '.') }}</p>
    </div>
</body>
</html>
