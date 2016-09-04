CREATE TABLE `tbl_tasks` (
  `TaskID` int(11) NOT NULL AUTO_INCREMENT,
  `ProjectID` int(11) NOT NULL,
  `Task` varchar(128) NOT NULL,
  `IsFinished` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`TaskID`),
  UNIQUE KEY `TaskID_UNIQUE` (`TaskID`)
);
