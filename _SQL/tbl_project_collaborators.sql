CREATE TABLE `tbl_project_collaborators` (
  `EntryID` int(11) NOT NULL AUTO_INCREMENT,
  `ProjectID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  PRIMARY KEY (`EntryID`),
  UNIQUE KEY `EntryID_UNIQUE` (`EntryID`)
);
