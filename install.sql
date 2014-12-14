ALTER TABLE wbb1_thread ADD anonymizedOp CHAR(10) DEFAULT NULL;
ALTER TABLE wbb1_post ADD isAnonymized TINYINT(1) DEFAULT 0;
ALTER TABLE wbb1_board ADD anonymizationMode ENUM('default', 'hash', 'fixed', 'list') DEFAULT 'default';
ALTER TABLE wbb1_board ADD anonymizationForced TINYINT(1) DEFAULT 0;
