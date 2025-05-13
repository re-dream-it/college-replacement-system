ALTER TABLE `replacements` ADD `is_introduced` BOOLEAN NOT NULL DEFAULT FALSE AFTER `id`;
UPDATE replacements SET is_introduced = 1;