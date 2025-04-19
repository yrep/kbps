<!DOCTYPE html>
<html>
<head>
    <title>Новый заказ торта</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
        .order-table { width: 100%; border-collapse: collapse; }
        .order-table th, .order-table td { padding: 12px; border: 1px solid #ddd; }
        .order-table th { background-color: #f8f8f8; text-align: left; }
        h2 { color: #333; }
    </style>
</head>
<body>
    <h2>Заказ свадебного торта</h2>
    <table class="order-table">
        <tr><th>Модель торта:</th><td><?php echo esc_html($data['cake_model']); ?></td></tr>
        <tr><th>Имя клиента:</th><td><?php echo esc_html($data['name']); ?></td></tr>
        <tr><th>Контактный телефон:</th><td><?php echo esc_html($data['mobile']); ?></td></tr>
        <tr><th>Email:</th><td><?php echo esc_html($data['email']); ?></td></tr>
        <tr><th>Дата свадьбы:</th><td><?php echo esc_html($data['wedding_date']); ?></td></tr>
        <tr><th>Место проведения:</th><td><?php echo esc_html($data['venue']); ?></td></tr>
        <tr><th>Количество гостей:</th><td><?php echo esc_html($data['guests']); ?></td></tr>
        <tr><th>Дата заказа:</th><td><?php echo esc_html($data['timestamp']); ?></td></tr>
        <tr><th>IP-адрес:</th><td><?php echo esc_html($data['ip']); ?></td></tr>
    </table>
</body>
</html>