<?php
// appraisals.php
// Database connection and other PHP logic would go here
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appraisals</title>
    <style>
        body {
            font-family: 'Roboto', Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            background: white;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-radius: 3px;
        }
        .header {
            padding: 15px 20px;
            border-bottom: 1px solid #e2e2e2;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: 500;
        }
        .filters {
            display: flex;
            padding: 15px 20px;
            border-bottom: 1px solid #e2e2e2;
            gap: 20px;
        }
        .filter-group {
            flex: 1;
        }
        .filter-group h3 {
            margin: 0 0 8px 0;
            font-size: 14px;
            font-weight: 500;
        }
        .filter-options {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }
        .filter-option {
            display: flex;
            align-items: center;
            font-size: 13px;
        }
        .filter-option input {
            margin-right: 8px;
        }
        .appraisal-list {
            padding: 15px;
        }
        .appraisal-item {
            padding: 15px;
            border-bottom: 1px solid #e2e2e2;
            display: flex;
            flex-direction: column;
        }
        .appraisal-item:last-child {
            border-bottom: none;
        }
        .employee-name {
            font-weight: 500;
            margin-bottom: 5px;
            color: #714B67;
        }
        .department {
            font-size: 13px;
            color: #666;
            margin-bottom: 5px;
        }
        .date {
            font-size: 13px;
            color: #999;
        }
        .meeting {
            font-size: 13px;
            color: #1890ff;
            margin-top: 5px;
            display: flex;
            align-items: center;
        }
        .meeting:before {
            content: "•";
            margin-right: 5px;
            color: #1890ff;
        }
        .status-tag {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 12px;
            margin-left: 10px;
        }
        .status-to-confirm {
            background-color: #fff7e6;
            border: 1px solid #ffd591;
            color: #fa8c16;
        }
        .status-confirmed {
            background-color: #f6ffed;
            border: 1px solid #b7eb8f;
            color: #52c41a;
        }
        .status-done {
            background-color: #e6f7ff;
            border: 1px solid #91d5ff;
            color: #1890ff;
        }
        .status-cancelled {
            background-color: #fff1f0;
            border: 1px solid #ffa39e;
            color: #f5222d;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Appraisals</h1>
        </div>
        
        <div class="filters">
           