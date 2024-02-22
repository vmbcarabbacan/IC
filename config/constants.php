<?php

return [
    'status' => [
        1 => 'Active',
        0 => 'Inactive'
    ],

    'user_roles' => [
        1 => 'Admin',
        2 => 'Sales Agent',
        3 => 'Underwriter',
        4 => 'Accountant',
        5 => 'Marketing Agent',
        6 => 'Sales Manager',
        7 => 'Marketing Admin',
        8 => 'Team Leader',
        9 => 'Quality Compliance Assurance'
    ],

    'customer_log' => [
        1 => 'Created Customer',
        2 => 'Updated Customer Information',
        3 => 'Update Customer Status',
        4 => 'Agent assigned'
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
    ],

    'task_status' => [
        1 => 'No answer',
        4 => 'Too early to buy',
        6 => 'Quotation sent',
        8 => 'Lost lead',
        9 => 'Customer bought',
        10 => 'Callback',
        11 => 'False lead',
        12 => 'Policy Cancelled',
        13 => 'Intransact',
        16 => 'Renewal Callback',
        17 => 'Redundant Lead',
        18 => 'Add New Lead',
        19 => 'Closed',
        20 => 'Callback - Refund Closed',
        21 => 'Cancellation Renewal Callback',
    ],

    'deal_task_status' => [
        12 => 'General Enquiry',
        13 => 'Policy Enquiry'
    ],

    'cancelled_task_status' => [
        8 => 'Lost lead',
        18 => 'Add New Lead',
        19 => 'Close Lead',
    ],

    'automated_system_task_status' => [
        14 => 'Policy transferred from seller',
        15 => 'Policy transferred to buyer'
    ],

    'lost_lead_reasons' => [
        8 => 'Bought from Insurance market',
        10 => 'Bought from broker',
        11 => 'Agency repairer not available',
        14 => 'Wants insurance company we do not have',
        15 => 'Our products doesn\'t suit the needs',
        16 => 'No Tariff possible',
        17 => 'Followup many time - No Answer',
        18 => 'Followup many times - Not Interested',
        21 => 'Mobile number changed',
        22 => 'Customer Sold the car',
        23 => 'Customer left the country',
        26 => 'Bought from direct company'
    ],

    'false_lead_reasons' => [
        24 => 'Fake Number',
        25 => 'Fake Information'
    ],

    'invoice_statuses' => [
        1 => 'Pending',
        2 => 'Approved',
        3 => 'Declined',
        4 => 'Approve with Discrepancy',
        5 => 'Update Discrepancy',
        6 => 'Failed payment'
    ],

    'discrepancy_statuses' => [
        1 => 'Approve with Discrepancy',
        2 => 'Update Discrepancy'
    ],

    'payment_types' => [
        2 => 'COD',
        3 => 'Direct',
        1 => 'Online',
        4 => 'Intrasact',
    ],

    'agent_type' => [
        1 => 'New Leads',
        2 => 'Renewals',
        3 => 'Both'
    ],

    'renewal_deals' => [
        1 => 'Self Deals',
        2 => 'All Deals',
        3 => 'Lost Leads'
    ]
];
