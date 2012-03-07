F - Framework for PHP
======================

**Change before use:**

+ Both .htaccess files (RewriteBase)
+ config.php (admin & root) - The 'ABS' Constant value
+ The 'DEVELOPMENT_ENVIRONMENT' Constant value
	
**Use of Language Class:**

+ use init_language(optional $language = 'english') within your Controller to initialize the Language Class
+ create a file '$language.lang.php' under root/application/library/languages/
+ use varibales '$lang["word"]' & '$language["word"]' in your template to print words
	
**Use of Authetication Class:**

Standard SQL-Table for User (*):

`CREATE TABLE users (
   user_id int(11) not null auto_increment,
   user_username varchar(100),
   user_password text,
   user_email varchar(150),
   user_last_login int(11),
   user_last_ip varchar(30),
   user_status int(1) default '0',
   user_activation_key varchar(50),
   user_registered int(11),
   PRIMARY KEY (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;`

*All fields are required!*


**User Authorization Levels:**
+  0: God level - Can access everything everywhere. (Only for developers)
+  1: Administrator - The highest possible user level. Similar to 'God level'
+  2: Editor - Can create everything. Can edit everything. Can delete posts.
+  3: Author - Can create posts, tags, cats, pages, comments. Upload files.
+  4: Contributor - Can comment, post in forum.
+  5: Subscriber - Can read private contents.