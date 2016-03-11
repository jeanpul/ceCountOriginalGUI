--
-- Tables for BlueCountGUI
-- author: fab
--
--
-- Table structure for table Session
-- sessionid is created from php create_session
-- login refers to User table
-- timelimit refers to SESSION_TIME
-- access value is used in BluePHP with checkAccess
--
CREATE TABLE Session (
  sessionid varchar(40) NOT NULL,
  login varchar(16) NOT NULL,
  timelimit decimal(10,0) NOT NULL default '0',
  access tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (sessionid)
);

--
-- Table structure for table User
-- passwd is crypted by the md5 PHP function
-- access value is used in BluePHP with checkAccess
--
CREATE TABLE User (
  login varchar(16) NOT NULL,
  passwd varchar(60) NOT NULL,
  email varchar(40) NOT NULL,
  access tinyint(3) NOT NULL default '0',
  PRIMARY KEY (login)
);

--
-- Global Configuration table
--
CREATE TABLE Config (
  name varchar(32) NOT NULL,
  defaultValue varchar(256) NOT NULL,
  access tinyint(3) NOT NULL default '0',
  PRIMARY KEY(name)
);

CREATE TABLE ConfigUserAssoc (
  name varchar(32) NOT NULL,
  login varchar(16) NOT NULL,
  value varchar(256) NOT NULL,
  PRIMARY KEY(name, login),
  FOREIGN KEY(name) REFERENCES Config(name),
  FOREIGN KEY(login) REFERENCES User(login)
);

CREATE TABLE SharedConfig (
  name varchar(32) NOT NULL,
  value varchar(256) NOT NULL,
  PRIMARY KEY(name),
  FOREIGN KEY(name) REFERENCES Config(name)
);

--
-- Removes session entries and configurations
-- entries where the user is deleted
--
CREATE TRIGGER on_user_delete BEFORE DELETE ON User
BEGIN 
  DELETE FROM Session WHERE login=old.login;
  DELETE FROM ConfigUserAssoc WHERE login=old.login;
END;

--
-- Removes the users configurations entries for 
-- deleted configuration parameters
--
CREATE TRIGGER on_config_delete BEFORE DELETE ON Config
BEGIN
  DELETE FROM ConfigUserAssoc WHERE name=old.name;
  DELETE FROM SharedConfig WHERE name=old.name;
END;

--
-- Creates default entries
--
INSERT INTO User VALUES("admin", "blue4eye", "admin@localhost", 2);
INSERT INTO User VALUES("user1", "blue4eye", "user1@localhost", 1);
INSERT INTO User VALUES("user2", "blue4eye", "user2@localhost", 1);
INSERT INTO User VALUES("user3", "blue4eye", "user3@localhost", 1);

--
-- Creates default configuration values
--

--
-- could be modified by any user
--
INSERT INTO Config VALUES("LANG", "en_US", 1);
INSERT INTO Config VALUES("DEFAULTPAGE", "index.php", 1);
INSERT INTO Config VALUES("CSS", "styles/blueeyevideo.css", 1);

--
-- only admin user could change this values
--
INSERT INTO Config VALUES("SITE_TITLE", "no title defined", 2);
INSERT INTO Config VALUES("COPYRIGHT", "Copyright 2007 Blue Eye Video All Rights Reserved", 2);
INSERT INTO Config VALUES("SESSIONTIME", "3600", 2);
-- current logo
INSERT INTO Config VALUES("LOGO", "LogoDefault.gif", 2); 
-- path where save user logo
INSERT INTO Config VALUES("LOGO_ABSOLUTE_PATH", "/var/www/BlueCountGUI/styles/Logo.gif", 2); 
 -- name for user logo
INSERT INTO Config VALUES("LOGO_RELATIVE_PATH", "Logo.gif", 2);

--
-- high access : BEV reserved
--
--INSERT INTO Config VALUES("ADMINACCESS", "2", 32);
--INSERT INTO Config VALUES("CONSULTACCESS", "1", 32);
INSERT INTO Config VALUES("SOFTNAME", "BlueCount Local Server GUI", 32);
INSERT INTO Config VALUES("VERSION", "1758", 32);

--
-- add shared config parameters
--
INSERT INTO SharedConfig VALUES("SITE_TITLE", "no title defined");
INSERT INTO SharedConfig VALUES("COPYRIGHT", "Copyright 2007 Blue Eye Video All Rights Reserved");
INSERT INTO SharedConfig VALUES("SESSIONTIME", "10800");
INSERT INTO SharedConfig VALUES("LOGO", "LogoDefault.gif"); 
INSERT INTO SharedConfig VALUES("LOGO_ABSOLUTE_PATH", "/var/www/BlueCountGUI/styles/Logo.gif");
INSERT INTO SharedConfig VALUES("LOGO_RELATIVE_PATH", "Logo.gif");

--INSERT INTO SharedConfig VALUES("ADMINACCESS", "2");
--INSERT INTO SharedConfig VALUES("CONSULTACCESS", "1");
INSERT INTO SharedConfig VALUES("SOFTNAME", "BlueCount Local Server GUI");
INSERT INTO SharedConfig VALUES("VERSION", "1758");
