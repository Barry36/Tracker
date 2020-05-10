# Tracker
This is an administration web application building on LAMP stack, this app can display bars or send email when given conditions are met and users have ability to add/remove/change the conditions as needed. 
## Notice: The result/outcome of this app can be observed when user is running a Moodle website, hence the database and backend routing were developed based on the stucture of Moodle.  

## Environment and Tech involved
1.	Ubuntu: 16.04.3 LTS
2.	PHP: 7.0.30-0ubuntu0.16.04.1 (cli) ( NTS )
3.	Mysql:  Ver 14.14 Distrib 5.7.22, for Linux (x86_64) using  EditLine wrapper
4.	jQuery: 3.2.1
5.	Front End Template: Material Design Bootstrap
Link: https://mdbootstrap.com/
6.	Ckeditor: ckeditor 4
7.	API’s: Using API’s when sharing the promotion link/coed. 
Three API’s were used in this application: FaceBook, Twitter, Gmail. All the API’s are in JavaScript, author did customization on each of the API’s to let them fulfill the requirements. All the source code can be found in Bar_content_source_code/Gift_Card_Bar.php
  - FaceBook share-button API: https://developers.facebook.com/docs/plugins/share-button/
  - Twitter share-button API: https://help.twitter.com/en/using-twitter/add-twitter-share-button
  - Gmail API: Unfortunately, there is no tutorial link showing you how to implement this functionality, but you can use this   - Tutorial to get a big idea about how Gmail API works: https://developers.google.com/gmail/api/guides/drafts
To save time and get the prototype working, the author used some tricks to implement this API, you may want to change this implementation following the tutorial above. The author implemented this functionality by combining the Gmail API Authentication and Url Email Prefill functionality together. The Gmail API only asks for permissions to access their Gmail account, but the author noticed that there are no existing Gmail API functions to both auto-fill email address contact and prefill the Email Subject, body (There may exist functions like this… but the author did not want to waste time on this, you can find that if you like). To have those functionalities, the author used Gmail API to authenticate user and log into Gmail directly, then used url to prefill the Email Body, Subject… source code can be found in function handleAuthResult(authResult) on line 427 in Gift_Card_Bar.php

## Get Started
1. Unzip the project under your preferred localhost or server
2. Start your localhost/server
3. Start your database, and create user
4. Update database username, password, hostanme at line 4 in query.php
5. visit index.php


## Function list:
Most functions can be found in either lib.php or query.php with detailed comments

### Add parameter
To add new parameters, go to Plugin-admin /parameter directory, create a new php file, i.e newParameter.php then you need to define a class that extends abstract class parameter, abstract class parameter is defined in Plugin-admin /parameter/parameter.php

Three functions you have to define in your class:
1. Constructor: public
2. getValue(): protected function, which returns an array of object, you have to return an array of object as your return value, as the application calls this function in lib.php
3. callgetValue(): public function that call getValue() and get the return value from it, because it is a protected function and cannot be accessed directly.

### Add operator
To add new operators, go to Plugin-admin /operator directory, create a new php file, i.e newOperator.php then you need to define a class that extends abstract class operator that is defined in Plugin-admin

Two functions and one parameter you have to define in your class:
1. abstract protected function operation($actual_value, $target_value):
This function takes two argument and apply the operation defined by the protected variable $operator_name, 
2. abstract public function getOperator();
This function returns the operator name, the value defined by variable $operator_name

### Add/Delete/Edit Trigger
Trigger changes using ajax to send request to the backend and using insert.php, delete.php and update.php to handle the request then apply to the DB

1.	Add Trigger: 
Click ‘+’, then append new row to the table, and insert some default value to mdl_trigger_message and insert trigger-bar assignment to the mdl_trigger_bar_assignment. Codes are in main.js (ajax) and insert.php, query.php
2.	Delete Trigger: 
Pretty much the same as Add trigger…  Codes are in main.js (ajax) and delete.php, query.php
3.	Edit Trigger: 
Again… just updating the records in DB …  Codes are in main.js (ajax) and update.php, query.php

### Add/Delete/Edit Conditions
Condition changes are pretty much the same as trigger changes, again, source code can be found in main.js, insert.php, delete.php and update.php

### Add/Delete/Edit Bar Templates
All the source code about Bar Template page can be found in qbank/customized-admin-panel/Plugin-admin/pages/templates/bar_template.  Ajax request and front-end functions are in /js/bar-template-script.js

  - Bar_template.php and bar_template_view.php gives the view of the Bar Template Page
  - Template_new.php, template_delete.php and template_save.php are responsible for create new template, delete template and save changes on template accordingly.
  - template_preview .php and template_preview_reference_from_mdl.php obviously …
  - s3_config.php and s3_start.php are the config and libraries for uploading images to amazon aws s3 API, upload.php is the source code, please follow the link below to install composer before launching to production 
https://docs.aws.amazon.com/aws-sdk-php/v2/guide/service-s3.html

### Add/Delete/Edit Email Templates
Pretty much the same as Bar Template, but this is not as complicated as the Bar Template Page, sending email function has not been implemented yet, but again, Bar Template is the essential part of this project. 

All the source code about Email Template page can be found in qbank/customized-admin-panel/Plugin-admin/pages/templates/email_template. 
