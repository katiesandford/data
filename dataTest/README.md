Graze Data Test
=====================

This is the Graze Data PHP Test


How To Install
--------------

  - You can setup the application with Vagrant. If you don't know about Vagrant you can read about it here: [Vagrant](http://docs.vagrantup.com/v2/why-vagrant/index.html)
  - Install Vagrant and VirtualBox:
    * [Vagrant: Installation](http://docs.vagrantup.com/v2/installation/index.html)
    * [VirtualBox: Installation](https://www.virtualbox.org/wiki/Downloads)
  - Clone this repository
  - In a terminal window, navigate to the root directory of the application and run "vagrant up" - [Vagrant: Getting started](http://docs.vagrantup.com/v2/getting-started/up.html)


How to use the application
-------------------------

  - From the command line, navigate to the root directory of the application and run `vagrant ssh` to connect to the virtual machine
  - From within the virtual machine, you can:
    * connect to the database: `mysql -uroot`
    * execute scripts: `php scripts/example.php`


Preamble
---------

  - There are two questions the business users are interested in answering:
    1. Which channels produce the most revenue per customer?
    2. Is there a correlation between the diversity of schedules tried by a customer and their revenue?
  - The tasks below will lead you through constructing data structures which allow you to answer these and similar questions.

Data tables
-----------

  - Operational data is available in the following tables from the `live` schema:
    * account: one entry per customer who signed up for the service
    * schedule: one or more entries per customer; each entry defines a period during which a customer receives a weekly box; each schedule has a schedule type, and links to an account entry
    * schedule_type: one entry per schedule type; contains the name of this type of schedule.
    * box: one entry per box sent to a customer; links to account and schedule
    * promotion_code: one entry per code; each code has a corresponding promotion channel.


Instructions
------------

  - When completing the tasks below, feel free to add code to the PHP files in `lib/` if required
  - Aim to spend 2 hours on the test - don't worry if at this point you haven't completed all the tasks, just submit what you have completed.


Task 1
------
  - Create a table called `reporting_account` in the `reporting` schema with the following columns:
    * account id
    * conversion date
    * number of boxes sent
    * number of full-price boxes sent
    * first churn date
    * total revenue
  - Save the your SQL statements in `sql/task1.sql`
  - Write a script which will populates this table from the existing database tables in the `live` schema.
  - Save the script in `scripts/task1.php`
  - Notes
    * Conversion date is defined as the dispatch date of the first full-price box they receive. The dispatch date can be fetched using the PHP method `getDispatchDate()` in the `Box` class
    * The first churn date is defined as the first date an account has no active schedules (a schedule is active between its start and end date)

Task 2
------
  - Extend the aggregate table to include promotion channel
  - The logic to determine the promotion channel of an account is as follows:
    * the promotion code they entered on signup (stored in the account table)
    * OR - from a CSV file sent by a third party which supplies a list of email addresses and channels; these files are sent to us once per month and are stored in `data/importFile_YYYYMM.txt`
    * OR - if the channel can’t be found in either of the above sources, the channel is recorded as “direct” 
  - Create a new column `promotion_channel` in the `reporting_account` table
  - Save the your SQL statements in `sql/task2.sql`
  - Create a copy of `scripts/task1.php` and add the required logic to populate the `promotion_channel` column
  - Save the script in `scripts/task2.php`

Task 3
------
  - Extend the aggregate table to include the list of schedule types a customer has tried
  - Create a new column `schedules_tried` in the `reporting_account` table
  - Save the your SQL statements in `sql/task3.sql`
  - Create a copy of `scripts/task2.php` and populate the schedules_tried column, storing the information in an appropriate format.
  - The list of schedules tried can be obtained either:
    * In PHP, by calling the `getSchedulesTried()` method in the `Account` class
    * In SQL, by querying the `schedule` and `schedule_type` tables
  - Save the script in `scripts/task3.php`
  - Optional question: Can you think of any problems with the logic used in `getSchedulesTried()` to determine which schedule types a customer has tried? How could this logic be improved?

Task 4
------
  - This table needs to be updated every day with an ever-growing customer base. What considerations would you have when modifying the scripts to support these requirements?
