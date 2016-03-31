--  Database = CMDB;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `application`
--
CREATE TABLE IF NOT EXISTS `application` (
  `App_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Active` int(1) NOT NULL DEFAULT '1',
  `Deactivate_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`App_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
insert into `application` (App_ID,Name) values
(1,'Active Directory');
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `accounttype`
--
CREATE TABLE IF NOT EXISTS `accounttype` (
  `Type_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Type` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Active` int(1) NOT NULL DEFAULT '1',
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
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Category` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Prefix` varchar(5) COLLATE utf8_bin DEFAULT NULL,
  `Active` int(1) NOT NULL DEFAULT '1',
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
(6,'Desktop','DST');
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `identitytype`
--
CREATE TABLE IF NOT EXISTS `identitytype` (
  `Type_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Type` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Active` int(1) NOT NULL DEFAULT '1',
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
  `Type_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Type` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Active` int(1) NOT NULL DEFAULT '1',
  `Deactivate_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`Type_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
/*+ initial Data */ 
insert into `roletype` (Type_ID,`Type`) values
(1,'Application'),
(2,'System');
-- -------------------------------------------------------------------------------
--
-- Tabelstructuur voor tabel `ram`
--
CREATE TABLE IF NOT EXISTS `ram` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
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
  `Acc_ID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Type` int(11) NOT NULL,
  `Application` int(11) NOT NULL,
  `Active` int(1) NOT NULL DEFAULT '1',
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
  `Type_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Vendor` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Type` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Category` int(11) DEFAULT NULL,
  `Active` int(1) NOT NULL DEFAULT '1',
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
  `Type` int(11) DEFAULT NULL,
  `Category` int(11) DEFAULT NULL,
  `Identity` int(11) DEFAULT NULL,
  `Active` int(1) NOT NULL DEFAULT '1',
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
  `Iden_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `E_Mail` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `UserID` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `Company` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Language` varchar(2) COLLATE utf8_bin DEFAULT NULL,
  `Type` int(11) DEFAULT NULL,
  `Active` int(1) NOT NULL DEFAULT '1',
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
  `Role_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Type` int(11) DEFAULT NULL,
  `Active` int(1) NOT NULL DEFAULT '1',
  `Deactivate_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`Role_ID`),
  INDEX (`Type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `System`
--
CREATE TABLE IF NOT EXISTS `System` (
  `Sys_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`Sys_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `IdenAccountApp`
--
CREATE TABLE IF NOT EXISTS `IdenAccountApp` (
	`Identity` int(11) NOT NULL,
	`Account` int(11) NOT NULL,
	`Application` int(11) NOT NULL,
	`ValidFrom` DATETIME DEFAULT NULL,
	`ValidEnd` DATETIME DEFAULT NULL,
	PRIMARY KEY (`Identity`,`Account`,`Application`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `AccountAppRole`
--
CREATE TABLE IF NOT EXISTS `AccountAppRole` (
	`Role` int(11) DEFAULT NULL,
	`Account` int(11) DEFAULT NULL,
    `Application` int(11) DEFAULT NULL,
	`ValidFrom` DATETIME DEFAULT NULL,
	`ValidEnd` DATETIME DEFAULT NULL,
	PRIMARY KEY (`Account`,`Role`,`Application`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `AccountAppRole`
--
CREATE TABLE IF NOT EXISTS `AccountSysRole` (
	`Role` int(11) DEFAULT NULL,
	`Account` int(11) DEFAULT NULL,
    `System` int(11) DEFAULT NULL,
	`ValidFrom` DATETIME DEFAULT NULL,
	`ValidEnd` DATETIME DEFAULT NULL,
	PRIMARY KEY (`Account`,`Role`,`System`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
/*-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `ttRoleRole`
--
CREATE TABLE IF NOT EXISTS `ttRoleRole` (
	`id` int(11) not null AUTO_increment,
	`Role1` int(11) DEFAULT NULL,
	`Role2` int(11) DEFAULT NULL,
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
	`Key_ID` int(11) not null AUTO_increment,
	`Type` int(11) DEFAULT NULL,
	`Serial` Varchar(8) COLLATE utf8_bin DEFAULT NULL,
	`AssetTag` varchar(20) COLLATE utf8_bin DEFAULT NULL,
	`Keys` int(11) NOT NULL DEFAULT 1,
	`Lock` int(1) NOT NULL DEFAULT 0,
	`Active` int(1) NOT NULL DEFAULT 1,
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
	`Admin_id` int(11) not null AUTO_increment,
	`Account` int(11) DEFAULT NULL,
	`Level` int(11) NOT NULL default 1,
	`PassWord` varchar(255) COLLATE utf8_bin NOT NULL,
	`DateSet` DATETIME NOT NULL,
	`Active` int(1) NOT NULL DEFAULT 1,
	`Deactivate_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
	PRIMARY KEY (`Admin_id`),
	INDEX (`Account`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `OldPass`
--
CREATE TABLE IF NOT EXISTS `OldPAss` (
	`Pass_id` int(11) not null AUTO_increment,
	`Account` int(11) DEFAULT NULL,
	`DateSet` DATETIME NOT NULL,
	PRIMary KEY (`Pass_id`),
	INDEX (`Account`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `Mobile`
--
CREATE TABLE IF NOT EXISTS `Mobile` (
	`IMEI` int(20) not null,
	`MobileType` int(11) DEFAULT NULL,
	`Identity` int(11) DEFAULT NULL,
	PRIMARY KEY (`IMEI`),
	INDEX (`MobileType`),
	INDEX (`Identity`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `SubscriptionType`
--
CREATE TABLE IF NOT EXISTS `SubscriptionType` (
  `Type_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Type` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Description` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Provider` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Active` int(1) NOT NULL DEFAULT '1',
  `Deactivate_reason` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `Category` int(11) DEFAULT NULL,
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
	`Sub_ID` int(11) not null,
	`PhoneNumber` VARCHAR(255) COLLATE utf8_bin not null,
	`SubscriptionType` int(11) DEFAULT NULL,
	`Identity` int(11) DEFAULT NULL,
	`IMEI` int(20) DEFAULT NULL,
	`Category` int(11) DEFAULT NULL,
	PRIMARY KEY (`Sub_ID`),
	INDEX (`Identity`),
	INDEX (`IMEI`),
	INDEX (`SubscriptionType`),
	INDEX (`Category`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;
-- --------------------------------------------------------
--
-- Tabelstructuur voor tabel `log`
--
CREATE TABLE IF NOT EXISTS `log` (
  `Log_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Log_Text` varchar(255) DEFAULT NULL,
  `Log_Date` datetime DEFAULT NULL,
  `Account` int(11) DEFAULT NULL,
  `AccountType` int(11) DEFAULT NULL,
  `Admin` int(11) DEFAULT NULL,
  `Application` int(11) DEFAULT NULL,
  `System` int(11) DEFAULT NULL,
  `Asset` varchar(20) DEFAULT NULL,
  `AssetType` int(11) DEFAULT NULL,
  `Category` int(11) DEFAULT NULL,
  `Identity` int(11) DEFAULT NULL,
  `Kensington` int(11) DEFAULT NULL,
  `Role` int(11) DEFAULT NULL,
  `RoleType` int(11) DEFAULT NULL,
  `IdentityType` int(11) DEFAULT NULL,
  `IMEI` int(20) DEFAULT NULL,
  `Subscription` int(11) DEFAULT NULL,
  `SubscriptionType` int(11) DEFAULT NULL,
  PRIMARY KEY (`Log_ID`),
  INDEX (`Account`),
  INDEX (`AccountType`),
  INDEX (`Admin`),
  INDEX (`Application`),
  INDEX (`System`),
  INDEX (`Asset`),
  INDEX (`AssetType`),
  INDEX (`Category`),
  INDEX (`Identity`),
  INDEX (`Kensington`),
  INDEX (`Role`),
  INDEX (`RoleType`),
  INDEX (`IdentityType`),
  INDEX (`IMEI`),
  INDEX (`Subscription`),
  INDEX (`SubscriptionType`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
-- -------------------------------------------------------------------------------
insert into `log` (Log_ID, Category, Log_text, Log_Date) values
(1,1,'Kensington Created by SQL Import','2012-01-01 00:00:00'),
(2,2,'Mobile Created by SQL Import','2012-01-01 00:00:00'),
(3,3,'Mobile Subscription Created by SQL Import','2012-01-01 00:00:00'),
(4,4,'Internet Subscription Created by SQL Import','2012-01-01 00:00:00'),
(5,5,'Laptop Created by SQL Import','2012-01-01 00:00:00'),
(6,6,'Desktop Created by SQL Import','2012-01-01 00:00:00');
insert into `log` (Log_ID, IdentityType, Log_text, Log_Date) values
(7,1,'Werknemer Created by SQL Import','2012-01-01 00:00:00'),
(8,2,'Exeterne Medewerker Created by SQL Import','2012-01-01 00:00:00');
insert into `log` (Log_ID, SubscriptionType, Log_text, Log_Date) values
(9,1,'Telenet Expresnet Created by SQL Import','2012-01-01 00:00:00'),
(10,2,'Telenet Fibernet Created by SQL Import','2012-01-01 00:00:00'),
(11,3,'Belgacom ADSL Created by SQL Import','2012-01-01 00:00:00'),
(12,4,'Belgacom VDSL Created by SQL Import','2012-01-01 00:00:00'),
(13,5,'Proximus Split Created by SQL Import','2012-01-01 00:00:00'),
(14,6,'Proximus EUR 25 Created by SQL Import','2012-01-01 00:00:00'),
(15,7,'Proximus FULL Created by SQL Import','2012-01-01 00:00:00');
insert into `log` (Log_ID, `Identity`, Log_text, Log_Date) values
(16,1,'Identity Stock Created by SQL Import','2012-01-01 00:00:00');
insert into `log` (Log_ID, `AccountType`, Log_text, Log_Date) values
(17,1,'Accounttype Normal User Created by SQL Import','2012-01-01 00:00:00'),
(18,2,'Accounttype Administrator Created by SQL Import','2012-01-01 00:00:00'),
(19,3,'Accounttype Servive User Created by SQL Import','2012-01-01 00:00:00');
insert into `log` (Log_ID, `Account`, Log_text, Log_Date) values
(20,1,'Account Active Directory Created by SQL Import','2012-01-01 00:00:00');
insert into `log` (Log_ID, `RoleType`, Log_text, Log_Date) values
(21,1,'Roletype Application Created by SQL Import','2012-01-01 00:00:00'),
(22,2,'Roletype System Created by SQL Import','2012-01-01 00:00:00');
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
-- Table IdenAccountApp
ALTER TABLE `IdenAccountApp` ADD FOREIGN KEY (`Identity`) REFERENCES `Identity`(`Iden_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `IdenAccountApp` ADD FOREIGN KEY (`Account`) REFERENCES `Account`(`Acc_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
ALTER TABLE `IdenAccountApp` ADD FOREIGN KEY (`Application`) REFERENCES `Application`(`App_ID`) ON DELETE SET NULL ON UPDATE CASCADE;
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