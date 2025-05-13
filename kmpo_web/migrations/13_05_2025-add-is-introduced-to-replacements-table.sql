ALTER TABLE `replacements` ADD `is_introduced` BOOLEAN NOT NULL DEFAULT FALSE AFTER `id`;
UPDATE replacements SET is_introduced = 1;
ALTER TABLE `replacements` CHANGE `is_introduced` `is_introduced` TINYINT(2) NOT NULL DEFAULT '0';