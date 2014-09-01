CREATE TABLE iF NOT EXISTS reporting_account (account_id INT(10) PRIMARY KEY,
										conversion_date DATE,
										boxes_sent INT(10) NOT NULL,
										full_price_boxes_sent INT(10) NOT NULL,
										first_churn_date DATE,
										total_revenue INT(10) NOT NULL)