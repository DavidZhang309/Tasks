CREATE TABLE `tbl_projects` (
  `ProjectID` int(11) NOT NULL AUTO_INCREMENT,
  `ProjectName` varchar(128) NOT NULL,
  `IsArchived` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ProjectID`),
  UNIQUE KEY `ProjectID_UNIQUE` (`ProjectID`)
);
