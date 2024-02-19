<?php

return [
    'status' => [
        1 => 'Active',
        0 => 'Inactive'
    ],

    'customer_log' => [
        1 => 'Created Customer',
        2 => 'Updated Customer Information',
        3 => 'Update Customer Status'
    ],

    'customer_status' => [
        1 => 'New - Not Contacted' ,
        2 => 'Return - Not Contacted' ,
        3 => 'Return - Follow Up' ,
        4 => 'Customer' ,
        5 => 'Lost Potential Customer' ,
        6 => 'Customer - Not Contacted' ,
        7 => 'Customer - Follow Up' ,
        8 => 'Partial Customer' ,
        9 => 'Lost Potential Customer - Not Contacted' ,
        10 => 'Lost Potential Customer - Follow Up' ,
        11 => 'Lost Customer',
        12 => 'New - Follow Up',
        13 => 'Partial Customer - Not Contacted',
        14 => 'Partial Customer - Follow Up',
        15 => 'Lost Customer - Not Contacted',
        16 => 'Lost Customer - Follow Up',
    ],

    'lead_status' => [
        1 => 'NEW' ,
        2 => 'QUICK LEAD' ,
        3 => 'PENDING' ,
        4 => 'DEAL' ,
        5 => 'LEAD RENEWAL' ,
        6 => 'LOST LEAD' ,
        7 => 'LOST LEAD RENEWAL' ,
        8 => 'LEAD RENEWAL LOST' ,
        9 => 'DEAL TRANSFERRED',
        10 => 'SYS QUICK LEAD',
        11 => 'FALSE LEAD',
        12 => 'CANCELLED',
        13 => 'INTRANSACT',
        14 => 'CLOSED',
        15 => 'REDUNDANT LEAD',
    ]
];
