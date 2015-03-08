ALTER TABLE wbb1_board ADD anonymizationMode ENUM('default', 'hash', 'fixed', 'list') DEFAULT 'default';
ALTER TABLE wbb1_board ADD anonymizationForced TINYINT(1) DEFAULT 0;
