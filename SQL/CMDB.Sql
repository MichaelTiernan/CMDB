--  Database = CMDB;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `application`
--
CREATE TABLE IF NOT EXISTS `application` (
  `App_ID` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Active` INTEGER(1) NOT NULL DEFAULT '1',
  `Deactivate_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`App_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
insert into `application` (App_ID,Name) values
(1,'Active Directory'),
(2,'CMDB');
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `accounttype`
--
CREATE TABLE IF NOT EXISTS `accounttype` (
  `Type_ID` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `Type` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Active` INTEGER(1) NOT NULL DEFAULT '1',
  `Deactivate_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`Type_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- ---INITIAL DATA
INSERT INTO accounttype (Type_ID,`Type`, Description) Values
(1,'Normal User','Normal User no aditional wrights'),
(2,'Administrator','Admnistrator Account'),
(3,'Servive User','User used to run services');
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `category`
--
CREATE TABLE IF NOT EXISTS `category` (
  `ID` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `Category` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Prefix` varchar(5) COLLATE utf8_bin DEFAULT NULL,
  `Active` INTEGER(1) NOT NULL DEFAULT '1',
  `Deactivate_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/* Initial Data */
INSERT INTO category (ID,Category,`prefix`) VALUES 
(1,'Kensington',NULL),
(2,'Mobile',NULL),
(3,'Mobile Subscription',NULL),
(4,'Internet Subscription',NULL),
(5,'Laptop','LPT'),
(6,'Desktop','DST'),
(7,'Token',NULL);
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `identitytype`
--
CREATE TABLE IF NOT EXISTS `identitytype` (
  `Type_ID` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `Type` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Active` INTEGER(1) NOT NULL DEFAULT '1',
  `Deactivate_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`Type_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
/* Initial Data */
insert into `identitytype` (Type_ID,`Type`,`Description`) values
(1,'Werknemer','Werknemer'),
(2,'Exeterne','Externe medewerker');
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `roletype`
--
CREATE TABLE IF NOT EXISTS `roletype` (
  `Type_ID` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `Type` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Active` INTEGER(1) NOT NULL DEFAULT '1',
  `Deactivate_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`Type_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
/*+ initial Data */ 
insert into `roletype` (Type_ID,`Type`,`Description`) values
(1,'Application','The Application Role'),
(2,'System','The System Role');
-- -------------------------------------------------------------------------------
--
-- Tabelstructuur voor tabel `ram`
--
CREATE TABLE IF NOT EXISTS `ram` (
  `ID` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `RAM` bigint(20) DEFAULT NULL,
  `Text` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/* Initial DATA*/
INSERT INTO `ram` (`ID`, `RAM`, `Text`) VALUES
(1, 128, '128 Kb'),
(2, 256, '256 Kb'),
(3, 512, '512 Kb'),
(4, 1024, '1 Gb'),
(5, 1536, '1,5 Gb'),
(6, 2048, '2 Gb'),
(7, 2560, '2,5 Gb'),
(8, 3072, '3 Gb'),
(9, 3584, '3,5 Gb'),
(10, 4096, '4 Gb');
-- ---------------------------------------------------------------------------------
--
-- Tabelstructuur voor tabel `configuration`
--
CREATE TABLE IF NOT EXISTS `configuration` (
	`CODE` varchar(12) COLLATE utf8_bin NOT NULL,
	`SUB_CODE` varchar(12) COLLATE utf8_bin NOT NULL,
	`CFN_DATE` DATETIME DEFAULT NULL,
	`CFN_NUMBER` INT(20) DEFAULT NULL,
	`TEXT` VARCHAR(255) COLLATE utf8_bin DEFAULT NULL,
	`Description` VARCHAR(255) COLLATE utf8_bin DEFAULT NULL,
	PRIMARY KEY (`CODE`,`SUB_CODE`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/* Initial Data */

-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `account`
--
CREATE TABLE IF NOT EXISTS `account` (
  `Acc_ID` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `UserID` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Type` INTEGER(11) NOT NULL,
  `Application` INTEGER(11) NOT NULL,
  `Active` INTEGER(1) NOT NULL DEFAULT '1',
  `Deactivate_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`Acc_ID`),
  INDEX(`Type`),
  INDEX(`Application`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `assettype`
--
CREATE TABLE IF NOT EXISTS `assettype` (
  `Type_ID` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `Vendor` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Type` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Category` INTEGER(11) DEFAULT NULL,
  `Active` INTEGER(1) NOT NULL DEFAULT '1',
  `Deactivate_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`Type_ID`),
  INDEX(`Category`)  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `asset`
--
CREATE TABLE IF NOT EXISTS `asset` (
  `AssetTag` varchar(20) COLLATE utf8_bin NOT NULL DEFAULT 'IND',
  `SerialNumber` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `IP_Adress` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `MAC` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `RAM` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Type` INTEGER(11) DEFAULT NULL,
  `Category` INTEGER(11) DEFAULT NULL,
  `Identity` INTEGER(11) DEFAULT 1,
  `Active` INTEGER(1) NOT NULL DEFAULT '1',
  `Deactivate_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`AssetTag`),
  INDEX(`Type`),
  INDEX(`Category`),
  INDEX(`Identity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `identity`
--
CREATE TABLE IF NOT EXISTS `identity` (
  `Iden_ID` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `E_Mail` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `UserID` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `Company` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Language` varchar(2) COLLATE utf8_bin DEFAULT NULL,
  `Type` INTEGER(11) DEFAULT NULL,
  `Active` INTEGER(1) NOT NULL DEFAULT '1',
  `Deactivate_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`Iden_ID`),
  INDEX (`Type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- Initial Data
insert into `identity` (Name,`Language`,Type) Values('Stock','NL',1);
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `role`
--
CREATE TABLE IF NOT EXISTS `role` (
  `Role_ID` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Type` INTEGER(11) DEFAULT NULL,
  `Active` INTEGER(1) NOT NULL DEFAULT '1',
  `Deactivate_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`Role_ID`),
  INDEX (`Type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `System`
--
CREATE TABLE IF NOT EXISTS `System` (
  `Sys_ID` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`Sys_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `IdenAccount`
--
CREATE TABLE IF NOT EXISTS `IdenAccount` (
	`Identity` INTEGER(11) NOT NULL,
	`Account` INTEGER(11) NOT NULL,
	`ValidFrom` DATETIME DEFAULT NULL,
	`ValidEnd` DATETIME DEFAULT NULL,
	PRIMARY KEY (`Identity`,`Account`,`ValidFrom`,`ValidEnd`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `AccountRole`
--
CREATE TABLE IF NOT EXISTS `AccountRole` (
	`Role` INTEGER(11) DEFAULT NULL,
	`Account` INTEGER(11) DEFAULT NULL,
	`ValidFrom` DATETIME DEFAULT NULL,
	`ValidEnd` DATETIME DEFAULT NULL,
	PRIMARY KEY (`Role`,`Account`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `AccountAppRole`
--
CREATE TABLE IF NOT EXISTS `AccountSysRole` (
    `Role` INTEGER(11) DEFAULT NULL,
    `Account` INTEGER(11) DEFAULT NULL,
    `System` INTEGER(11) DEFAULT NULL,
    `ValidFrom` DATETIME DEFAULT NULL,
    `ValidEnd` DATETIME DEFAULT NULL,
    PRIMARY KEY (`Account`,`Role`,`System`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
/*-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `ttRoleRole`
--
CREATE TABLE IF NOT EXISTS `ttRoleRole` (
	`id` INTEGER(11) not null AUTO_increment,
	`Role1` INTEGER(11) DEFAULT NULL,
	`Role2` INTEGER(11) DEFAULT NULL,
	`ValidFrom` DATETIME DEFAULT NULL,
	`ValidEnd` DATETIME DEFAULT NULL,
	PRIMARY KEY (`ID`),
	KEY `Role1`(`Role1`),
	KEY `Role2` (`Role2`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 */;
--
-- Tabelstructuur voor tabel `Kensington`
--
CREATE TABLE IF NOT EXISTS `Kensington` (
	`Key_ID` INTEGER(11) not null AUTO_increment,
	`Type` INTEGER(11) DEFAULT NULL,
	`Serial` Varchar(8) COLLATE utf8_bin DEFAULT NULL,
	`AssetTag` varchar(20) COLLATE utf8_bin DEFAULT NULL,
	`AmountKeys` INTEGER(11) NOT NULL DEFAULT 1,
	`hasLock` INTEGER(1) NOT NULL DEFAULT 0,
	`Active` INTEGER(1) NOT NULL DEFAULT 1,
	`Deactivate_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
	PRIMARY KEY (`Key_ID`),
	INDEX (`Type`),
	INDEX (`AssetTag`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `Admin`
--
CREATE TABLE IF NOT EXISTS `Admin` (
	`Admin_id` INTEGER(11) not null AUTO_increment,
	`Account` INTEGER(11) DEFAULT NULL,
	`Level` INTEGER(11) NOT NULL default 1,
	`PassWord` varchar(255) COLLATE utf8_bin NOT NULL,
	`DateSet` DATETIME NOT NULL,
	`Active` INTEGER(1) NOT NULL DEFAULT 1,
	`Deactivate_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
	PRIMARY KEY (`Admin_id`),
	INDEX (`Account`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `OldPass`
--
CREATE TABLE IF NOT EXISTS `OldPAss` (
	`Pass_id` INTEGER(11) not null AUTO_increment,
	`Account` INTEGER(11) DEFAULT NULL,
	`DateSet` DATETIME NOT NULL,
	PRIMary KEY (`Pass_id`),
	INDEX (`Account`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `Mobile`
--
CREATE TABLE IF NOT EXISTS `Mobile` (
	`IMEI` INTEGER(20) not null,
	`MobileType` INTEGER(11) DEFAULT NULL,
	`Identity` INTEGER(11) DEFAULT NULL,
	PRIMARY KEY (`IMEI`),
	INDEX (`MobileType`),
	INDEX (`Identity`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `SubscriptionType`
--
CREATE TABLE IF NOT EXISTS `SubscriptionType` (
  `Type_ID` INTEGER(11) NOT NULL AUTO_INCREMENT,
  `Type` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Provider` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Active` INTEGER(1) NOT NULL DEFAULT '1',
  `Deactivate_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Category` INTEGER(11) DEFAULT NULL,
  PRIMARY KEY (`Type_ID`),
  INDEX (`Category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/* INITIAL DATA*/
insert into `SubscriptionType` (Type_ID,`Type`,Description, Provider, Category) values
(1,'Expresnet','Telenet Expresnet','Telenet',4),
(2,'Fibernet','Telenet Fibernet','Telenet',4),
(3,'ADSL','ADSL','Belgacom',4),
(4,'VDSL','VDSL','Belgacom',4),
(5,'Split','Company pays back wath the user indicates','Proximus',3),
(6,'EUR 25','Company pays EUR 25 back per month','Proximus',3),
(7,'FULL','Company pays everyting back','Proximus',3);
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `Subscription`
--
CREATE TABLE IF NOT EXISTS `Subscription` (
	`Sub_ID` INTEGER(11) not null,
	`PhoneNumber` VARCHAR(255) COLLATE utf8_bin not null,
	`SubscriptionType` INTEGER(11) DEFAULT NULL,
	`Identity` INTEGER(11) DEFAULT NULL,
	`IMEI` INTEGER(20) DEFAULT NULL,
	`Category` INTEGER(11) DEFAULT NULL,
	PRIMARY KEY (`Sub_ID`),
	INDEX (`Identity`),
	INDEX (`IMEI`),
	INDEX (`SubscriptionType`),
	INDEX (`Category`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `Menu`
--
CREATE TABLE IF NOT EXISTS `menu` ( 
  Menu_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  label varchar(50) NOT NULL default '',
  link_url varchar(100) NOT NULL default '#',
  parent_id INTEGER(11) NOT NULL default '0',
  PRIMARY KEY  (Menu_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- Initial Data
insert into menu (Menu_id, label) VALUES (1,'Identity');
insert into menu (Menu_id, label,parent_id) VALUES (2,'Identity',1);
insert into menu (Menu_id, label,link_url,parent_id) VALUES (3,'Overview','Identity.php',2);
insert into menu (Menu_id, label) VALUES (4,'Account');
insert into menu (Menu_id, label,parent_id) VALUES (5,'Account',4);
insert into menu (Menu_id, label,link_url,parent_id) VALUES (6,'Overview','Account.php',5);
insert into menu (Menu_id, label) VALUES (7,'Role');
insert into menu (Menu_id, label,parent_id) VALUES (8,'Role',7);
insert into menu (Menu_id, label,link_url,parent_id) VALUES (9,'Overview','Role.php',8);
-- Devices
INSERT INTO `menu` (`Menu_id`, `label`, `link_url`, `parent_id`) VALUES
(10, 'Devices', '#', 0),
(11, 'Laptop', '#', 10),
(12, 'Overview', 'Devices.php?Category=Laptop', 11),
(13, 'Desktop', '#', 10),
(14, 'Overview', 'Devices.php?Category=Desktop', 13),
(15, 'Token', '#', 10),
(16, 'Overview', 'Token.php', 15),
(17, 'Kensington', '#', 10),
(18, 'Overview', 'Kensington.php', 17),
(19, 'Mobile', '#', 10),
(20, 'Overview', 'Mobile.php', 19),
(21, 'Subscription', '#', 10),
(22, 'Overview', 'Subscription.php', 21);
-- Types
INSERT INTO `menu` (`Menu_id`, `label`, `link_url`, `parent_id`) VALUES
(23, 'Types', '#', 0),
(24, 'Asset Type', '#',23),
(25, 'Overview', 'AssetType.php',24),
(26, 'Asset Category', '#',23),
(27, 'Overview', 'Category.php',26),
(28, 'IdentityType', '#',23),
(29, 'Overview', 'IdentityType.php',28),
(30, 'AccountType', '#',23),
(31, 'Overview', 'AccountType.php',30),
(32, 'RoleType', '#',23),
(33, 'Overview', 'RoleType.php',32),
(34, 'Subscription Type', '#', 23),
(35, 'Overview', 'SubscriptionType.php', 34);
INSERT INTO `menu` (`Menu_id`, `label`, `link_url`, `parent_id`) VALUES
(36, 'System','#',0),
(37, 'System','#',36),
(38, 'Overview','Sytem.php',37),
(39, 'Application','#',0),
(40, 'Application','#',39),
(41, 'Overview','Application.php',40),
(42, 'Admin','#',0),
(43, 'Admin','#',42),
(44, 'Overview','Admin.php',43),
(45, 'Permissions','#',42),
(46, 'Overview','Permission.php',45);
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `Permissions`
--
CREATE TABLE IF NOT EXISTS permissions (
  perm_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  permission VARCHAR(50) NOT NULL,
  description VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (perm_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;
-- Initial Data
INSERT INTO `permissions` (perm_id,permission,description) VALUES
(1,"Read",NULL),
(2,"Update",NULL),
(3,"Delete",NULL),
(4,"Activate",NULL),
(5,"Add",NULL),
(6,"AssignAccount","This Permission is for assigning Accounts"),
(7,"AccountOverview","This Permission is to see The assigned Accounts"),
(8,"AssignDevice","This Permission is to Assign a Device"),
(9,"DeviceOverview","This Permission is see the assigned Device"),
(10,"AssignMobile","This Permission is to Assign a Mobile"),
(11,"MobileOverview","This Permission is to see the assigned Mobiles"),
(12,"AssignSubscription","This Permission is to Assign a Subscription"),
(13,"SubscriptionOverview","This Permission is to see the assigned Subscription"),
(14,"AssignIdentity","This Permission is to Assign a Identity"),
(15,"IdentityOverview","This Permission is see the assigned Identities"),
(16,"AssignAppRole","This Permission is to Assign a Application Role"),
(17,"AppRoleOverview","This Permission is to  see the assigned Application Roles"),
(18,"AssignSysRole","This Permission is to Assign a System Role"),
(19,"SysRoleOverview","This Permission is to see the assigned System Roles"),
(20,"AssignApplication","This Permission is to Assign an Application"),
(21,"ApplicationOverview","This Permission is to see the assigned Application"),
(22,"AssignSystem","This Permission is to Assign a System"),
(23,"SystemOverview","This Permission is to see the assigned Systems"),
(24,"AssignKensington","This Permission is to Assign a Kensington"),
(25,"KeyOverview","This Permission is to see the assigned Kensington"),
(26,"ReleaseDevice","This Permission is to release Device"),
(27,"ReleaseAccount","This Permission is to release Account"),
(28,"ReleaseMobile","This Permission is to release Mobile"),
(29,"ReleaseSubscription","This Permission is to release Subscription"),
(30,"ReleaseIdentity","This Permission is to release Identity"),
(31,"ReleaseAppRole","This Permission is to release Application Role"),
(32,"ReleaseSysRole","This Permission is to release System Role"),
(33,"ReleaseApplication","This Permission is to release Application"),
(34,"ReleaseSystem","This Permission is to release System"),
(35,"ReleaseKensington","This Permission is to release Kensington"),
(36,"AssignLevel","This Permission is to Assign a Level"),
(37,"PermissionOverview","This Permission is to see the Permissions"),
(38,"MenuOverview","This Permission is to see the Menu");
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `Level`
--
CREATE TABLE IF NOT EXISTS Level (
    Level INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
    PRIMARY KEY (Level)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;
insert into Level (Level) Values
(1),
(2),
(3),
(4),
(5),
(6),
(7),
(8),
(9);
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `Role_permissions`
--
CREATE TABLE IF NOT EXISTS role_perm (
role_perm_id INTEGER UNSIGNED AUTO_INCREMENT,
  level INTEGER UNSIGNED NOT NULL,
  perm_id INTEGER UNSIGNED NOT NULL,
  menu_id INTEGER UNSIGNED NOT NULL,
  PRIMARY KEY (role_perm_id),
  FOREIGN KEY (perm_id) REFERENCES permissions(perm_id),
  FOREIGN KEY (menu_id) REFERENCES Menu(Menu_id)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1;
-- Initial Data for Level 9 
-- Identity =2
INSERT INTO `role_perm` (role_perm_id, `level`, `perm_id`, `menu_id`) VALUES
(1, 9, 1, 2),
(2, 9, 2, 2),
(3, 9, 3, 2),
(4, 9, 4, 2),
(5, 9, 5, 2),
(6, 9, 6, 2),
(7, 9, 7, 2),
(8, 9, 8, 2),
(9, 9, 9, 2),
(10, 9, 10, 2),
(11, 9, 11, 2),
(12, 9, 12, 2),
(13, 9, 13, 2);
-- IdentityType =28
INSERT INTO `role_perm` (role_perm_id, `level`, `perm_id`, `menu_id`) VALUES
(14, 9, 1, 28),
(15, 9, 2, 28),
(16, 9, 3, 28),
(17, 9, 4, 28),
(18, 9, 5, 28);
-- Account =5
INSERT INTO `role_perm` (role_perm_id, `level`, `perm_id`, `menu_id`) VALUES
(19, 9, 1, 5),
(20, 9, 2, 5),
(21, 9, 3, 5),
(22, 9, 4, 5),
(23, 9, 5, 5),
(24, 9, 14, 5),
(25, 9, 15, 5);
-- Role = 8
INSERT INTO `role_perm` (role_perm_id, `level`, `perm_id`, `menu_id`) VALUES
(26, 9, 1, 8),
(27, 9, 2, 8),
(28, 9, 3, 8),
(29, 9, 4, 8),
(30, 9, 5, 8),
(31, 9, 20, 8),
(32, 9, 21, 8);
-- Laptop = 11
INSERT INTO `role_perm` (role_perm_id, `level`, `perm_id`, `menu_id`) VALUES
(33, 9, 1, 11),
(34, 9, 2, 11),
(35, 9, 3, 11),
(36, 9, 4, 11),
(37, 9, 5, 11),
(38, 9, 14, 11),
(39, 9, 15, 11),
(40, 9, 24, 11),
(41, 9, 25, 11);
-- Desktop = 13
INSERT INTO `role_perm` (role_perm_id, `level`, `perm_id`, `menu_id`) VALUES
(42, 9, 1, 13),
(43, 9, 2, 13),
(44, 9, 3, 13),
(45, 9, 4, 13),
(46, 9, 5, 13),
(47, 9, 14, 13),
(48, 9, 15, 13),
(49, 9, 24, 13),
(50, 9, 25, 13);
-- Token = 15
INSERT INTO `role_perm` (role_perm_id, `level`, `perm_id`, `menu_id`) VALUES
(51, 9, 1, 15),
(52, 9, 2, 15),
(53, 9, 3, 15),
(54, 9, 4, 15),
(55, 9, 5, 15),
(56, 9, 14, 15),
(57, 9, 15, 15);
-- Kensington = 17
INSERT INTO `role_perm` (role_perm_id, `level`, `perm_id`, `menu_id`) VALUES
(58, 9, 1, 17),
(59, 9, 2, 17),
(60, 9, 3, 17),
(61, 9, 4, 17),
(62, 9, 5, 17),
(63, 9, 8, 17),
(64, 9, 9, 17);
-- Mobile = 19
INSERT INTO `role_perm` (role_perm_id, `level`, `perm_id`, `menu_id`) VALUES
(65, 9, 1, 19),
(66, 9, 2, 19),
(67, 9, 3, 19),
(68, 9, 4, 19),
(69, 9, 5, 19),
(70, 9, 12, 19),
(71, 9, 13, 19);
-- Subscription = 21
INSERT INTO `role_perm` (role_perm_id, `level`, `perm_id`, `menu_id`) VALUES
(72, 9, 1, 21),
(73, 9, 2, 21),
(74, 9, 3, 21),
(75, 9, 4, 21),
(76, 9, 5, 21),
(77, 9, 14, 21),
(78, 9, 15, 21),
(79, 9, 10, 21),
(80, 9, 11, 21);
-- Asset Type = 24
INSERT INTO `role_perm` (role_perm_id, `level`, `perm_id`, `menu_id`) VALUES
(81, 9, 1, 24),
(82, 9, 2, 24),
(83, 9, 3, 24),
(84, 9, 4, 24),
(85, 9, 5, 24);
-- Asset Category = 26
INSERT INTO `role_perm` (role_perm_id, `level`, `perm_id`, `menu_id`) VALUES
(86, 9, 1, 26),
(87, 9, 2, 26),
(88, 9, 3, 26),
(89, 9, 4, 26),
(90, 9, 5, 26);
-- Asset Category = 26
INSERT INTO `role_perm` (role_perm_id, `level`, `perm_id`, `menu_id`) VALUES
(91, 9, 1, 26),
(92, 9, 2, 26),
(93, 9, 3, 26),
(94, 9, 4, 26),
(95, 9, 5, 26);
-- AccountType = 30
INSERT INTO `role_perm` (role_perm_id, `level`, `perm_id`, `menu_id`) VALUES
(96, 9, 1, 30),
(97, 9, 2, 30),
(98, 9, 3, 30),
(99, 9, 4, 30),
(100, 9, 5, 30);
-- RoleType = 32
INSERT INTO `role_perm` (role_perm_id, `level`, `perm_id`, `menu_id`) VALUES
(101, 9, 1, 32),
(102, 9, 2, 32),
(103, 9, 3, 32),
(104, 9, 4, 32),
(105, 9, 5, 32);
-- Subscription Type = 34
INSERT INTO `role_perm` (role_perm_id, `level`, `perm_id`, `menu_id`) VALUES
(106, 9, 1, 34),
(107, 9, 2, 34),
(108, 9, 3, 34),
(109, 9, 4, 34),
(110, 9, 5, 34);
-- System = 37
INSERT INTO `role_perm` (role_perm_id, `level`, `perm_id`, `menu_id`) VALUES
(111, 9, 1, 37),
(112, 9, 2, 37),
(113, 9, 3, 37),
(114, 9, 4, 37),
(115, 9, 5, 37);
-- Application = 40
INSERT INTO `role_perm` (role_perm_id, `level`, `perm_id`, `menu_id`) VALUES
(116, 9, 1, 40),
(117, 9, 2, 40),
(118, 9, 3, 40),
(119, 9, 4, 40),
(120, 9, 5, 40),
(121, 9, 7, 40);
-- Admin = 43
INSERT INTO `role_perm` (role_perm_id, `level`, `perm_id`, `menu_id`) VALUES
(122, 9, 1, 43),
(123, 9, 2, 43),
(124, 9, 3, 43),
(125, 9, 4, 43),
(126, 9, 5, 43);
-- Permissions = 45
INSERT INTO `role_perm` (role_perm_id, `level`, `perm_id`, `menu_id`) VALUES
(127, 9, 1, 45),
(128, 9, 2, 45),
(129, 9, 3, 45),
(130, 9, 5, 45);

-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `log`
--
CREATE TABLE IF NOT EXISTS `log` (
  `Log_ID` INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
  `Log_Text` varchar(255) DEFAULT NULL,
  `Log_Date` datetime DEFAULT NULL,
  `Account` INTEGER UNSIGNED DEFAULT NULL,
  `AccountType` INTEGER UNSIGNED DEFAULT NULL,
  `Admin` INTEGER UNSIGNED DEFAULT NULL,
  `Application` INTEGER UNSIGNED DEFAULT NULL,
  `System` INTEGER UNSIGNED DEFAULT NULL,
  `AssetTag` varchar(20) DEFAULT NULL,
  `AssetType` INTEGER UNSIGNED DEFAULT NULL,
  `Category` INTEGER UNSIGNED DEFAULT NULL,
  `Identity` INTEGER UNSIGNED DEFAULT NULL,
  `Kensington` INTEGER UNSIGNED DEFAULT NULL,
  `Role` INTEGER UNSIGNED DEFAULT NULL,
  `RoleType` INTEGER UNSIGNED DEFAULT NULL,
  `IdentityType` INTEGER UNSIGNED DEFAULT NULL,
  `IMEI` int(20) DEFAULT NULL,
  `Subscription` INTEGER UNSIGNED DEFAULT NULL,
  `SubscriptionType` INTEGER UNSIGNED DEFAULT NULL,
   permissions INTEGER UNSIGNED DEFAULT NULL,
   menu INTEGER UNSIGNED DEFAULT NULL,
   role_perm_id INTEGER UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`Log_ID`),
  INDEX (`Account`),
  INDEX (`AccountType`),
  INDEX (`Admin`),
  INDEX (`Application`),
  INDEX (`System`),
  INDEX (`AssetTag`),
  INDEX (`AssetType`),
  INDEX (`Category`),
  INDEX (`Identity`),
  INDEX (`Kensington`),
  INDEX (`Role`),
  INDEX (`RoleType`),
  INDEX (`IdentityType`),
  INDEX (`IMEI`),
  INDEX (`Subscription`),
  INDEX (`SubscriptionType`),
  INDEX (permissions),
  INDEX (menu),
  INDEX (role_perm_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- -------------------------------------------------------------------------------
insert into `log` (Log_ID, Category, Log_text, Log_Date) values
(1,1,'Kensington Created by SQL Import','2012-01-01 00:00:00'),
(2,2,'Mobile Created by SQL Import','2012-01-01 00:00:00'),
(3,3,'Mobile Subscription Created by SQL Import','2012-01-01 00:00:00'),
(4,4,'Internet Subscription Created by SQL Import','2012-01-01 00:00:00'),
(5,5,'Laptop Created by SQL Import','2012-01-01 00:00:00'),
(6,6,'Desktop Created by SQL Import','2012-01-01 00:00:00'),
(7,7,'Token Created by SQL Import','2012-01-01 00:00:00');
insert into `log` (Log_ID, IdentityType, Log_text, Log_Date) values
(8,1,'Werknemer Created by SQL Import','2012-01-01 00:00:00'),
(9,2,'Exeterne Medewerker Created by SQL Import','2012-01-01 00:00:00');
insert into `log` (Log_ID, SubscriptionType, Log_text, Log_Date) values
(10,1,'Telenet Expresnet Created by SQL Import','2012-01-01 00:00:00'),
(11,2,'Telenet Fibernet Created by SQL Import','2012-01-01 00:00:00'),
(12,3,'Belgacom ADSL Created by SQL Import','2012-01-01 00:00:00'),
(13,4,'Belgacom VDSL Created by SQL Import','2012-01-01 00:00:00'),
(14,5,'Proximus Split Created by SQL Import','2012-01-01 00:00:00'),
(15,6,'Proximus EUR 25 Created by SQL Import','2012-01-01 00:00:00'),
(16,7,'Proximus FULL Created by SQL Import','2012-01-01 00:00:00');
insert into `log` (Log_ID, `Identity`, Log_text, Log_Date) values
(17,1,'Identity Stock Created by SQL Import','2012-01-01 00:00:00');
insert into `log` (Log_ID, `AccountType`, Log_text, Log_Date) values
(18,1,'Accounttype Normal User Created by SQL Import','2012-01-01 00:00:00'),
(19,2,'Accounttype Administrator Created by SQL Import','2012-01-01 00:00:00'),
(20,3,'Accounttype Servive User Created by SQL Import','2012-01-01 00:00:00');
insert into `log` (Log_ID, `Application`, Log_text, Log_Date) values
(21,1,'Application Active Directory Created by SQL Import','2012-01-01 00:00:00'),
(22,2,'Application CMDB Created by SQL Import','2012-01-01 00:00:00');
insert into `log` (Log_ID, `RoleType`, Log_text, Log_Date) values
(23,1,'Roletype Application Created by SQL Import','2012-01-01 00:00:00'),
(24,2,'Roletype System Created by SQL Import','2012-01-01 00:00:00');
insert into `log` (Log_ID, `role_perm_id`, Log_text, Log_Date) values
(25,1,'The Menu Identity recieved Permission Read for Level: 9 this is created by SQL Import','2012-01-01 00:00:00'),
(26,2,'The Menu Identity recieved Permission Update for Level: 9 this is created by SQL Import','2012-01-01 00:00:00'),
(27,3,'The Menu Identity recieved Permission Delete for Level: 9 this is created by SQL Import','2012-01-01 00:00:00'),
(28,4,'The Menu Identity recieved Permission Activate for Level: 9 this is created by SQL Import','2012-01-01 00:00:00'),
(29,5,'The Menu Identity recieved Permission Add for Level: 9 this is created by SQL Import','2012-01-01 00:00:00'),
(30,6,'The Menu Identity recieved Permission AssignAccount for Level: 9 this is created by SQL Import','2012-01-01 00:00:00'),
(31,7,'The Menu Identity recieved Permission AccountOverview for Level: 9 this is created by SQL Import','2012-01-01 00:00:00'),
(32,8, 'The Menu Identity recieved Permission AssignDevice for Level: 9 this is created by SQL Import','2012-01-01 00:00:00'),
(33,9, 'The Menu Identity recieved Permission DeviceOverview for Level: 9 this is created by SQL Import','2012-01-01 00:00:00'),
(34,10, 'The Menu Identity recieved Permission AssignMobile for Level: 9 this is created by SQL Import','2012-01-01 00:00:00'),
(35,11, 'The Menu Identity recieved Permission MobileOverview for Level: 9 this is created by SQL Import','2012-01-01 00:00:00'),
(36,12, 'The Menu Identity recieved Permission AssignSubscription for Level: 9 this is created by SQL Import','2012-01-01 00:00:00'),
(37,13, 'The Menu Identity recieved Permission SubscriptionOverview for Level: 9 this is created by SQL Import','2012-01-01 00:00:00');
insert into `log` (Log_ID, `role_perm_id`, Log_text, Log_Date) values
(38, 14, 'The Permission: Read added for Level: 9 for Menu: IdentityType is created by SQL Import', '2012-01-01 00:00:00'),
(39, 15, 'The Permission: Update added for Level: 9 for Menu: IdentityType is created by SQL Import', '2012-01-01 00:00:00'),
(40, 16, 'The Permission: Delete added for Level: 9 for Menu: IdentityType is created by SQL Import', '2012-01-01 00:00:00'),
(41, 17, 'The Permission: Activate added for Level: 9 for Menu: IdentityType is created by SQL Import', '2012-01-01 00:00:00'),
(42, 18, 'The Permission: Add added for Level: 9 for Menu: IdentityType is created by SQL Import', '2012-06-16 01:01:58');
insert into `log` (Log_ID, `role_perm_id`, Log_text, Log_Date) values
(43, 19, 'The Permission: Read added for Level: 9 for Menu: Account is created by SQL Import', '2012-01-01 00:00:00'),
(44, 20, 'The Permission: Update added for Level: 9 for Menu: Account is created by SQL Import', '2012-01-01 00:00:00'),
(45, 21, 'The Permission: Delete added for Level: 9 for Menu: Account is created by SQL Import', '2012-01-01 00:00:00'),
(46, 22, 'The Permission: Activate added for Level: 9 for Menu: Account is created by SQL Import', '2012-01-01 00:00:00'),
(47, 23, 'The Permission: Add added for Level: 9 for Menu: Account is created by SQL Import', '2012-06-16 01:01:58'),
(48, 24, 'The Permission: Assign Identity added for Level: 9 for Menu: Account is created by SQL Import', '2012-06-16 01:01:58'),
(49, 25, 'The Permission: Identity Overview added for Level: 9 for Menu: Account is created by SQL Import', '2012-06-16 01:01:58');
insert into `log` (Log_ID, `role_perm_id`, Log_text, Log_Date) values
(50, 26, 'The Permission: Read added for Level: 9 for Menu: Role is created by SQL Import', '2012-01-01 00:00:00'),
(51, 27, 'The Permission: Update added for Level: 9 for Menu: Role is created by SQL Import', '2012-01-01 00:00:00'),
(52, 28, 'The Permission: Delete added for Level: 9 for Menu: Role is created by SQL Import', '2012-01-01 00:00:00'),
(53, 29, 'The Permission: Activate added for Level: 9 for Menu: Role is created by SQL Import', '2012-01-01 00:00:00'),
(54, 30, 'The Permission: Add added for Level: 9 for Menu: Role is created by SQL Import', '2012-06-16 01:01:58'),
(55, 31, 'The Permission: Assign Application added for Level: 9 for Menu: Role is created by SQL Import', '2012-06-16 01:01:58'),
(56, 32, 'The Permission: Application overview added for Level: 9 for Menu: Role is created by SQL Import', '2012-06-16 01:01:58');
insert into `log` (Log_ID, `role_perm_id`, Log_text, Log_Date) values
(57, 33, 'The Permission: Read added for Level: 9 for Menu: Laptop is created by SQL Import', '2012-01-01 00:00:00'),
(58, 34, 'The Permission: Update added for Level: 9 for Menu: Laptop is created by SQL Import', '2012-01-01 00:00:00'),
(59, 35, 'The Permission: Delete added for Level: 9 for Menu: Laptop is created by SQL Import', '2012-01-01 00:00:00'),
(60, 36, 'The Permission: Activate added for Level: 9 for Menu: Laptop is created by SQL Import', '2012-01-01 00:00:00'),
(61, 37, 'The Permission: Add added for Level: 9 for Menu: Laptop is created by SQL Import', '2012-06-16 01:01:58'),
(62, 38, 'The Permission: Assign Identity added for Level: 9 for Menu: Laptop is created by SQL Import', '2012-06-16 01:01:58'),
(63, 39, 'The Permission: Identity overview added for Level: 9 for Menu: Laptop is created by SQL Import', '2012-06-16 01:01:58'),
(64, 40, 'The Permission: Assign Kensington added for Level: 9 for Menu: Laptop is created by SQL Import', '2012-06-16 01:01:58'),
(65, 41, 'The Permission: Kensington overview added for Level: 9 for Menu: Laptop is created by SQL Import', '2012-06-16 01:01:58');

-- Alter Table
ALTER TABLE `identity` ADD UNIQUE(`UserID`);
/* -->Foreign keys */
/* Table Account */
--ALTER TABLE `asset` ADD CONSTRAINT `asset_ibfk_1` FOREIGN KEY (`Type`) REFERENCES `assettype` (`Type_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `account` ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`Type`) REFERENCES `accounttype`(`Type_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `account` ADD CONSTRAINT `account_ibfk_2` FOREIGN KEY (`Application`) REFERENCES `application`(`App_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
/* Table AssetType */
ALTER TABLE `assettype` ADD FOREIGN KEY (`Category`) REFERENCES `Category` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;
--Table Asset
Alter table `asset` ADD FOREIGN KEY (`Type`) REFERENCES `assettype` (`Type_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
Alter table `asset` ADD FOREIGN KEY (`Category`) REFERENCES `Category` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;
Alter table `asset` ADD FOREIGN KEY (`Identity`) REFERENCES `Identity`(`Iden_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
-- Table Identity
ALTER TABLE `identity` ADD FOREIGN KEY (`Type`) REFERENCES `identitytype` (`Type_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
-- Table Role
ALTER TABLE `role` ADD FOREIGN KEY (`Type`) REFERENCES `roletype` (`Type_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `role` ADD FOREIGN KEY (`Application`) REFERENCES `Application` (`App_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
-- Table IdenAccount
ALTER TABLE `IdenAccount` ADD FOREIGN KEY (`Identity`) REFERENCES `Identity`(`Iden_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `IdenAccount` ADD FOREIGN KEY (`Account`) REFERENCES `Account`(`Acc_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
-- Table AccountAppRole
ALTER TABLE `AccountAppRole` ADD FOREIGN KEY (`Role`) REFERENCES `Role`(`Role_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `AccountAppRole` ADD FOREIGN KEY (`Account`) REFERENCES `Account`(`Acc_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `AccountAppRole` ADD FOREIGN KEY (`Application`) REFERENCES `Application`(`App_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
-- Table Kensington
ALTER TABLE `Kensington` ADD FOREIGN KEY (`Type`)  REFERENCES `Category` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `Kensington` ADD FOREIGN KEY (`AssetTag`) REFERENCES `Asset` (`AssetTag`) ON DELETE SET NULL ON UPDATE CASCADE;
-- Table Admin
ALTER TABLE `Admin` ADD FOREIGN KEY (`Account`) REFERENCES `Account` (`Acc_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
-- Table OldPAss
ALTER TABLE `OldPAss` ADD FOREIGN KEY (`Account`) REFERENCES `Account` (`Acc_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
-- Table Mobile
ALTER TABLE `Mobile` ADD FOREIGN KEY (`MobileType`) REFERENCES `assettype` (`Type_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `Mobile` ADD FOREIGN KEY (`Identity`) REFERENCES `Identity`(`Iden_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
-- Table SubscriptionType
ALTER TABLE `SubscriptionType` ADD FOREIGN KEY (`Category`) REFERENCES `Category` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;
-- Table 'Subsription'
ALTER TABLE `Subscription` ADD FOREIGN KEY (`Identity`) REFERENCES `Identity`(`Iden_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `Subscription` ADD FOREIGN KEY (`IMEI`) REFERENCES `Mobile`(`IMEI`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `Subscription` ADD FOREIGN KEY (`Category`) REFERENCES `Category` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `Subscription` ADD FOREIGN KEY (`SubscriptionType`) REFERENCES `SubscriptionType` (`Type_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
-- Table Log
Alter TABLE `log` ADD FOREIGN KEY (`Account`) REFERENCES `Account` (`Acc_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
Alter TABLE `log` ADD FOREIGN KEY (`AccountType`) REFERENCES `accounttype` (`Type_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
Alter TABLE `log` ADD FOREIGN KEY (`Admin`) REFERENCES `Admin` (`Admin_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
Alter TABLE `log` ADD FOREIGN KEY (`Application`) REFERENCES `application` (`App_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
Alter TABLE `log` ADD FOREIGN KEY (`Asset`) REFERENCES `Asset` (`AssetTag`) ON DELETE SET NULL ON UPDATE CASCADE;
Alter TABLE `log` ADD FOREIGN KEY (`AssetType`) REFERENCES `assettype` (`Type_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
Alter TABLE `log` ADD FOREIGN KEY (`Category`) REFERENCES `Category` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE;
Alter TABLE `log` ADD FOREIGN KEY (`Identity`) REFERENCES `Identity` (`Iden_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
Alter TABLE `log` ADD FOREIGN KEY (`Kensington`) REFERENCES `kensington` (`Key_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
Alter TABLE `log` ADD FOREIGN KEY (`Role`) REFERENCES `Role` (`Role_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
Alter TABLE `log` ADD FOREIGN KEY (`RoleType`) REFERENCES `roletype` (`Type_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
Alter TABLE `log` ADD FOREIGN KEY (`IdentityType`) REFERENCES `identitytype` (`Type_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
Alter TABLE `log` ADD FOREIGN KEY (`IMEI`) REFERENCES `Mobile` (`IMEI`) ON DELETE SET NULL ON UPDATE CASCADE;
Alter TABLE `log` ADD FOREIGN KEY (`Subscription`) REFERENCES `Subscription` (`Sub_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
Alter TABLE `log` ADD FOREIGN KEY (`SubscriptionType`) REFERENCES `SubscriptionType` (`Type_ID`) ON DELETE SET NULL ON UPDATE CASCADE;