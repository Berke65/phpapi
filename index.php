<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Opportunities</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f0f2f5;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
        }
        h1 {
            color: #1a73e8;
            margin-bottom: 20px;
            font-size: 2em;
            font-weight: 600;
        }
        form {
            margin-bottom: 30px;
        }
        select {
            padding: 12px 16px;
            font-size: 1em;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        select:focus {
            border-color: #1a73e8;
            outline: none;
            box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.2);
        }
        .opportunity-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0,0,0,0.2);
            max-width: 600px;
            width: 100%;
            text-align: center;
            margin-top: 20px;
            transition: all 0.3s ease;
        }
        .opportunity-container:hover {
            box-shadow: 0 8px 16px rgba(0,0,0,0.3);
        }
        .opportunity-banner {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        .opportunity-title {
            font-size: 1.8em;
            margin: 10px 0;
            color: #1a73e8;
            font-weight: 700;
        }
        .opportunity-content {
            margin-bottom: 20px;
            line-height: 1.8;
            color: #555;
        }
        .opportunity-date {
            font-size: 1em;
            color: #777;
            margin-bottom: 10px;
        }
        a {
            display: inline-block;
            padding: 12px 20px;
            font-size: 1em;
            color: #fff;
            background-color: #1a73e8;
            border-radius: 5px;
            text-decoration: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        a:hover {
            background-color: #155ab8;
            box-shadow: 0 6px 12px rgba(0,0,0,0.2);
        }
        p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <h1>Fırsatlar</h1>
    <?php
    function fetch_opportunities() {
        $token = 'token';
        $response = file_get_contents('address', false, stream_context_create(array(
            'http' => array(
                'header' => 'Authorization: Bearer ' . $token,
            ),
        )));

        if ($response === FALSE) {
            return '<p>Failed to fetch data from the API.</p>';
        }

        $data = json_decode($response, true);

        if (isset($data['message']) && $data['message'] === 'Unauthorized') {
            return '<p>Authorization error: Please check your API token.</p>';
        }

        return isset($data['data']) ? $data['data'] : [];
    }

    function get_opportunity_options($opportunities) {
        $options = '<option value="">Lütfen birini seçiniz</option>';
        foreach ($opportunities as $opportunity) {
            $title = isset($opportunity['title']) ? $opportunity['title'] : 'No Title';
            $options .= '<option value="' . ($opportunity['id']) . '">' . ($title) . '</option>';
        }
        return $options;
    }

    function get_opportunity_details($opportunity) {
        $title = isset($opportunity['title']) ? $opportunity['title'] : 'No Title';
        $description = isset($opportunity['description']) ? $opportunity['description'] : 'No Description';
        $link = isset($opportunity['link']) ? $opportunity['link'] : '#';
        $banner = isset($opportunity['banner']) ? $opportunity['banner'] : '';
        $content = isset($opportunity['content']) ? $opportunity['content'] : '';
        $startDate = isset($opportunity['start_date']) ? $opportunity['start_date'] : '';
        $endDate = isset($opportunity['end_date']) ? $opportunity['end_date'] : '';

        $details = '<div class="opportunity-container">';
        if ($banner) {
            $details .= '<img src="' . $banner . '" alt="' . ($title) . '" class="opportunity-banner">';
        }
        $details .= '<h3 class="opportunity-title">' . ($title) . '</h3>';
        $details .= '<p class="opportunity-content">' . ($description) . '</p>';
        $details .= '<p class="opportunity-content">' . ($content) . '</p>';
        $details .= '<p class="opportunity-date"><strong>Başlangıç tarihi:</strong> ' . ($startDate) . '</p>';
        $details .= '<p class="opportunity-date"><strong>Bitiş tarihi:</strong> ' . ($endDate) . '</p>';
        $details .= '<a href="' . ($link) . '" target="_blank">Fırsatı Görüntüle</a>';
        $details .= '</div>';

        return $details;
    }

    $opportunities = fetch_opportunities();
    $selectedOpportunity = null;

    if (isset($_POST['opportunity_id'])) {
        $selectedId = intval($_POST['opportunity_id']);
        foreach ($opportunities as $opportunity) {
            if ($opportunity['id'] === $selectedId) {
                $selectedOpportunity = $opportunity;
                break;
            }
        }
    }

    ?>
    <form method="POST" action="">
        <select name="opportunity_id" onchange="this.form.submit()">
            <?php echo get_opportunity_options($opportunities); ?>
        </select>
    </form>

    <?php
    if ($selectedOpportunity) {
        echo get_opportunity_details($selectedOpportunity);
    } else {
        echo '<p>Detayları görmek için lütfen bir seçim yapınız</p>';
    }
    ?>
</body>
</html>
