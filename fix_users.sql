ALTER TABLE sushobha_crm.users ADD COLUMN access_rights text DEFAULT NULL;
UPDATE sushobha_crm.users SET access_rights = '["Read","Write","Modify","Delete","View","Approve"]' WHERE role_id = 1;
