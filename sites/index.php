<?php

// header('Content-type: json/application');

require 'connect.php';

$type = $_SERVER['REQUEST_URI'];
$data_today = '2024-02-02';
// $data_today = date('Y-m-d');
$data_from = date('Y-m-d', strtotime('-6 days', strtotime($data_today)));

if ($type === '/getReceipts') {
    $data_yesterday = date('Y-m-d', strtotime('-1 days', strtotime($data_today)));
    $receipts = mysqli_query($connect, "SELECT * FROM receipts WHERE receipt_date >= '" . $data_from . "'");
    $receipts_list = [];
    $plus_total = $plus_total_yesterday = $plus_total_today = 0;
    $plus_cash = $plus_cash_yesterday = $plus_cash_today = 0;
    $plus_cashless = $plus_cashless_yesterday = $plus_cashless_today = 0;
    $plus_card = $plus_card_yesterday = $plus_card_today = 0;
    $average_receipt = $average_receipt_yesterday = $average_receipt_today = 0;
    $average_client = $average_client_yesterday = $average_client_today = 0;
    $minus_after = $minus_after_yesterday = $minus_after_today = 0;
    $minus_before = $minus_before_yesterday = $minus_before_today = 0;
    $clients = $clients_yesterday = $clients_today = 0;
    while($receipt = mysqli_fetch_assoc($receipts)) {
        $plus_cash += $receipt['plus_cash'];
        $plus_cashless += $receipt['plus_cashless'];
        $plus_card += $receipt['plus_card'];
        $minus_after += $receipt['minus_after'];
        $minus_before += $receipt['minus_before'];
        $clients += 1;
        if ($receipt['receipt_date'] == $data_yesterday) {
            $plus_cash_yesterday += $receipt['plus_cash'];
            $plus_cashless_yesterday += $receipt['plus_cashless'];
            $plus_card_yesterday += $receipt['plus_card'];
            $minus_after_yesterday += $receipt['minus_after'];
            $minus_before_yesterday += $receipt['minus_before'];
            $clients_yesterday += 1;
        }
        if ($receipt['receipt_date'] == $data_today) {
            $plus_cash_today += $receipt['plus_cash'];
            $plus_cashless_today += $receipt['plus_cashless'];
            $plus_card_today += $receipt['plus_card'];
            $minus_after_today += $receipt['minus_after'];
            $minus_before_today += $receipt['minus_before'];
            $clients_today += 1;
        }
    }
    $plus_total = $plus_cash + $plus_cashless + $plus_card - $minus_after - $minus_before;
    $plus_total_yesterday = $plus_cash_yesterday + $plus_cashless_yesterday + $plus_card_yesterday - $minus_after_yesterday - $minus_before_yesterday;
    $plus_total_today = $plus_cash_today + $plus_cashless_today + $plus_card_today - $minus_after_today - $minus_before_today;
    $average_client = round($plus_total / $clients);
    $average_client_yesterday = round($plus_total_yesterday / $clients_yesterday);
    $average_client_today = round($plus_total_today / $clients_today);
    $receipts_list = array(
        'plus_total' => [
            'today' => $plus_total_today,
            'yesterday' => $plus_total_yesterday,
            'week' => $plus_total,
        ],
        'plus_cash' => [
            'today' => $plus_cash_today,
            'yesterday' => $plus_cash_yesterday,
            'week' => $plus_cash,
        ],
        'plus_cashless' => [
            'today' => $plus_cashless_today,
            'yesterday' => $plus_cashless_yesterday,
            'week' => $plus_cashless,
        ],
        'plus_card' => [
            'today' => $plus_card_today,
            'yesterday' => $plus_card_yesterday,
            'week' => $plus_card,
        ],
        'average_receipt' => [
            'today' => $average_client_today,
            'yesterday' => $average_client_yesterday,
            'week' => $average_client,
        ],
        'average_client' => [
            'today' => $average_client_today,
            'yesterday' => $average_client_yesterday,
            'week' => $average_client,
        ],
        'minus_after' => [
            'today' => $minus_after_today,
            'yesterday' => $minus_after_yesterday,
            'week' => $minus_after,
        ],
        'minus_before' => [
            'today' => $minus_before_today,
            'yesterday' => $minus_before_yesterday,
            'week' => $minus_before,
        ],
        'receipts' => [
            'today' => $clients_today,
            'yesterday' => $clients_yesterday,
            'week' => $clients,
        ],
        'clients' => [
            'today' => $clients_today,
            'yesterday' => $clients_yesterday,
            'week' => $clients,
        ],
    );
    echo json_encode($receipts_list);
}

if ($type === '/getPlus') {
    $receipts = mysqli_query($connect, "SELECT * FROM receipts WHERE receipt_date >= '" . $data_from . "'");
    $receipts_list = [];
    while($receipt = mysqli_fetch_assoc($receipts)) {
        $curent_data = $receipt['receipt_date'];
        $receipts_list[$curent_data] += $receipt['plus_cash'] + $receipt['plus_cashless'] + $receipt['plus_card'] - $receipt['minus_before'] - $receipt['minus_after'];
    }
    echo json_encode($receipts_list);
}

if ($type === '/getPlusCash') {
    $receipts = mysqli_query($connect, "SELECT plus_cash, receipt_date FROM receipts WHERE receipt_date >= '" . $data_from . "'");
    $receipts_list = [];
    while($receipt = mysqli_fetch_assoc($receipts)) {
        $curent_data = $receipt['receipt_date'];
        $receipts_list[$curent_data] += $receipt['plus_cash'];
    }
    echo json_encode($receipts_list);
}

if ($type === '/getPlusCashless') {
    $receipts = mysqli_query($connect, "SELECT plus_cashless, receipt_date FROM receipts WHERE receipt_date >= '" . $data_from . "'");
    $receipts_list = [];
    while($receipt = mysqli_fetch_assoc($receipts)) {
        $curent_data = $receipt['receipt_date'];
        $receipts_list[$curent_data] += $receipt['plus_cashless'];
    }
    echo json_encode($receipts_list);
}

if ($type === '/getPlusCard') {
    $receipts = mysqli_query($connect, "SELECT plus_card, receipt_date FROM receipts WHERE receipt_date >= '" . $data_from . "'");
    $receipts_list = [];
    while($receipt = mysqli_fetch_assoc($receipts)) {
        $curent_data = $receipt['receipt_date'];
        $receipts_list[$curent_data] += $receipt['plus_card'];
    }
    echo json_encode($receipts_list);
}

if ($type === '/getMinusAfter') {
    $receipts = mysqli_query($connect, "SELECT minus_after, receipt_date FROM receipts WHERE receipt_date >= '" . $data_from . "'");
    $receipts_list = [];
    while($receipt = mysqli_fetch_assoc($receipts)) {
        $curent_data = $receipt['receipt_date'];
        $receipts_list[$curent_data] += $receipt['minus_after'];
    }
    echo json_encode($receipts_list);
}

if ($type === '/getMinusBefore') {
    $receipts = mysqli_query($connect, "SELECT minus_before, receipt_date FROM receipts WHERE receipt_date >= '" . $data_from . "'");
    $receipts_list = [];
    while($receipt = mysqli_fetch_assoc($receipts)) {
        $curent_data = $receipt['receipt_date'];
        $receipts_list[$curent_data] += $receipt['minus_before'];
    }
    echo json_encode($receipts_list);
}

if ($type === '/getCountReceipts') {
    $receipts = mysqli_query($connect, "SELECT receipt_date FROM receipts WHERE receipt_date >= '" . $data_from . "'");
    $receipts_list = [];
    while($receipt = mysqli_fetch_assoc($receipts)) {
        $curent_data = $receipt['receipt_date'];
        $receipts_list[$curent_data] += 1;
    }
    echo json_encode($receipts_list);
}

if ($type === '/getCountClients') {
    $receipts = mysqli_query($connect, "SELECT receipt_date FROM receipts WHERE receipt_date >= '" . $data_from . "'");
    $receipts_list = [];
    while($receipt = mysqli_fetch_assoc($receipts)) {
        $curent_data = $receipt['receipt_date'];
        $receipts_list[$curent_data] += 1;
    }
    echo json_encode($receipts_list);
}

if ($type === '/getAverageClient') {
    $receipts = mysqli_query($connect, "SELECT * FROM receipts WHERE receipt_date >= '" . $data_from . "'");
    $temp_list = [];
    $receipts_list = [];
    while($receipt = mysqli_fetch_assoc($receipts)) {
        $curent_data = $receipt['receipt_date'];
        $temp_list[$curent_data]['plus_total'] += $receipt['plus_cash'] + $receipt['plus_cashless'] + $receipt['plus_card'] - $receipt['minus_before'] - $receipt['minus_after'];
        $temp_list[$curent_data]['clients'] += 1;
    }
    foreach ($temp_list as $key => $value) {
        $receipts_list[$key] = round($value['plus_total'] / $value['clients']);
    }
    echo json_encode($receipts_list);
}

if ($type === '/getAverageReceipt') {
    $receipts = mysqli_query($connect, "SELECT * FROM receipts WHERE receipt_date >= '" . $data_from . "'");
    $temp_list = [];
    $receipts_list = [];
    while($receipt = mysqli_fetch_assoc($receipts)) {
        $curent_data = $receipt['receipt_date'];
        $temp_list[$curent_data]['plus_total'] += $receipt['plus_cash'] + $receipt['plus_cashless'] + $receipt['plus_card'] - $receipt['minus_before'] - $receipt['minus_after'];
        $temp_list[$curent_data]['clients'] += 1;
    }
    foreach ($temp_list as $key => $value) {
        $receipts_list[$key] = round($value['plus_total'] / $value['clients']);
    }
    echo json_encode($receipts_list);
}
